#define SASS_AST

#include <string>
#include <sstream>
#include <vector>
#include <set>
#include <algorithm>

#ifdef __clang__

/*
 * There are some overloads used here that trigger the clang overload
 * hiding warning. Specifically:
 *
 * Type type() which hides string type() from Expression
 *
 * and
 *
 * Block* block() which hides virtual Block* block() from Statement
 *
 */

#pragma clang diagnostic push
#pragma clang diagnostic ignored "-Woverloaded-virtual"

#endif

#ifndef SASS_CONSTANTS
#include "constants.hpp"
#endif

#ifndef SASS_OPERATION
#include "operation.hpp"
#endif

#ifndef SASS_TOKEN
#include "token.hpp"
#endif

#ifndef SASS_ENVIRONMENT
#include "environment.hpp"
#endif

#ifndef SASS
#include "sass.h"
#endif

#include "units.hpp"

#ifndef SASS_ERROR_HANDLING
#include "error_handling.hpp"
#endif

#include "ast_def_macros.hpp"

#include <sstream>
#include <iostream>
#include <typeinfo>

#ifndef SASS_POSITION
#include "position.hpp"
#endif

namespace Sass {
  using namespace std;

  /////////////////////////////////////////////////////////////////////////////
  // Mixin class for AST nodes that should behave like vectors. Uses the
  // "Template Method" design pattern to allow subclasses to adjust their flags
  // when certain objects are pushed.
  /////////////////////////////////////////////////////////////////////////////
  template <typename T>
  class Vectorized {
    vector<T> elements_;
  protected:
    virtual void adjust_after_pushing(T element) { }
  public:
    Vectorized(size_t s = 0) : elements_(vector<T>())
    { elements_.reserve(s); }
    virtual ~Vectorized() = 0;
    size_t length() const   { return elements_.size(); }
    bool empty() const      { return elements_.empty(); }
    T& operator[](size_t i) { return elements_[i]; }
    Vectorized& operator<<(T element)
    {
      elements_.push_back(element);
      adjust_after_pushing(element);
      return *this;
    }
    Vectorized& operator+=(Vectorized* v)
    {
      for (size_t i = 0, L = v->length(); i < L; ++i) *this << (*v)[i];
      return *this;
    }
    vector<T>& elements() { return elements_; }
    vector<T>& elements(vector<T>& e) { elements_ = e; return elements_; }
  };
  template <typename T>
  inline Vectorized<T>::~Vectorized() { }

  //////////////////////////////////////////////////////////
  // Abstract base class for all abstract syntax tree nodes.
  //////////////////////////////////////////////////////////
  class Block;
  class Statement;
  class Expression;
  class Selector;
  class AST_Node {
    ADD_PROPERTY(string, path);
    ADD_PROPERTY(Position, position);
  public:
    AST_Node(string path, Position position) : path_(path), position_(position) { }
    virtual ~AST_Node() = 0;
    // virtual Block* block() { return 0; }
    ATTACH_OPERATIONS();
  };
  inline AST_Node::~AST_Node() { }

  /////////////////////////////////////////////////////////////////////////
  // Abstract base class for statements. This side of the AST hierarchy
  // represents elements in expansion contexts, which exist primarily to be
  // rewritten and macro-expanded.
  /////////////////////////////////////////////////////////////////////////
  class Statement : public AST_Node {
  public:
    Statement(string path, Position position) : AST_Node(path, position) { }
    virtual ~Statement() = 0;
    // needed for rearranging nested rulesets during CSS emission
    virtual bool   is_hoistable() { return false; }
    virtual Block* block()  { return 0; }
  };
  inline Statement::~Statement() { }

  ////////////////////////
  // Blocks of statements.
  ////////////////////////
  class Block : public Statement, public Vectorized<Statement*> {
    ADD_PROPERTY(bool, is_root);
    // needed for properly formatted CSS emission
    ADD_PROPERTY(bool, has_hoistable);
    ADD_PROPERTY(bool, has_non_hoistable);
  protected:
    void adjust_after_pushing(Statement* s)
    {
      if (s->is_hoistable()) has_hoistable_     = true;
      else                   has_non_hoistable_ = true;
    };
  public:
    Block(string path, Position position, size_t s = 0, bool r = false)
    : Statement(path, position),
      Vectorized<Statement*>(s),
      is_root_(r), has_hoistable_(false), has_non_hoistable_(false)
    { }
    Block* block() { return this; }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////////////////////////////
  // Abstract base class for statements that contain blocks of statements.
  ////////////////////////////////////////////////////////////////////////
  class Has_Block : public Statement {
    ADD_PROPERTY(Block*, block);
  public:
    Has_Block(string path, Position position, Block* b)
    : Statement(path, position), block_(b)
    { }
    virtual ~Has_Block() = 0;
  };
  inline Has_Block::~Has_Block() { }

  /////////////////////////////////////////////////////////////////////////////
  // Rulesets (i.e., sets of styles headed by a selector and containing a block
  // of style declarations.
  /////////////////////////////////////////////////////////////////////////////
  class Selector;
  class Ruleset : public Has_Block {
    ADD_PROPERTY(Selector*, selector);
  public:
    Ruleset(string path, Position position, Selector* s, Block* b)
    : Has_Block(path, position, b), selector_(s)
    { }
    // nested rulesets need to be hoisted out of their enclosing blocks
    bool is_hoistable() { return true; }
    ATTACH_OPERATIONS();
  };

  /////////////////////////////////////////////////////////
  // Nested declaration sets (i.e., namespaced properties).
  /////////////////////////////////////////////////////////
  class String;
  class Propset : public Has_Block {
    ADD_PROPERTY(String*, property_fragment);
  public:
    Propset(string path, Position position, String* pf, Block* b = 0)
    : Has_Block(path, position, b), property_fragment_(pf)
    { }
    ATTACH_OPERATIONS();
  };

  /////////////////
  // Media queries.
  /////////////////
  class List;
  class Media_Block : public Has_Block {
    ADD_PROPERTY(List*, media_queries);
    ADD_PROPERTY(Selector*, enclosing_selector);
  public:
    Media_Block(string path, Position position, List* mqs, Block* b)
    : Has_Block(path, position, b), media_queries_(mqs), enclosing_selector_(0)
    { }
    bool is_hoistable() { return true; }
    ATTACH_OPERATIONS();
  };

  ///////////////////////////////////////////////////////////////////////
  // At-rules -- arbitrary directives beginning with "@" that may have an
  // optional statement block.
  ///////////////////////////////////////////////////////////////////////
  class At_Rule : public Has_Block {
    ADD_PROPERTY(string, keyword);
    ADD_PROPERTY(Selector*, selector);
    ADD_PROPERTY(Expression*, value);
  public:
    At_Rule(string path, Position position, string kwd, Selector* sel = 0, Block* b = 0)
    : Has_Block(path, position, b), keyword_(kwd), selector_(sel), value_(0) // set value manually if needed
    { }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////////////////////////////
  // Declarations -- style rules consisting of a property name and values.
  ////////////////////////////////////////////////////////////////////////
  class Declaration : public Statement {
    ADD_PROPERTY(String*, property);
    ADD_PROPERTY(Expression*, value);
    ADD_PROPERTY(bool, is_important);
  public:
    Declaration(string path, Position position,
                String* prop, Expression* val, bool i = false)
    : Statement(path, position), property_(prop), value_(val), is_important_(i)
    { }
    ATTACH_OPERATIONS();
  };

  /////////////////////////////////////
  // Assignments -- variable and value.
  /////////////////////////////////////
  class Variable;
  class Expression;
  class Assignment : public Statement {
    ADD_PROPERTY(string, variable);
    ADD_PROPERTY(Expression*, value);
    ADD_PROPERTY(bool, is_guarded);
  public:
    Assignment(string path, Position position,
               string var, Expression* val, bool guarded = false)
    : Statement(path, position), variable_(var), value_(val), is_guarded_(guarded)
    { }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////////////////////////////////
  // Import directives. CSS and Sass import lists can be intermingled, so it's
  // necessary to store a list of each in an Import node.
  ////////////////////////////////////////////////////////////////////////////
  class Import : public Statement {
    vector<string>         files_;
    vector<Expression*> urls_;
  public:
    Import(string path, Position position)
    : Statement(path, position),
      files_(vector<string>()), urls_(vector<Expression*>())
    { }
    vector<string>&         files() { return files_; }
    vector<Expression*>& urls()     { return urls_; }
    ATTACH_OPERATIONS();
  };

  class Import_Stub : public Statement {
    ADD_PROPERTY(string, file_name);
  public:
    Import_Stub(string path, Position position, string f)
    : Statement(path, position), file_name_(f)
    { }
    ATTACH_OPERATIONS();
  };

  //////////////////////////////
  // The Sass `@warn` directive.
  //////////////////////////////
  class Warning : public Statement {
    ADD_PROPERTY(Expression*, message);
  public:
    Warning(string path, Position position, Expression* msg)
    : Statement(path, position), message_(msg)
    { }
    ATTACH_OPERATIONS();
  };

  ///////////////////////////////////////////
  // CSS comments. These may be interpolated.
  ///////////////////////////////////////////
  class Comment : public Statement {
    ADD_PROPERTY(String*, text);
  public:
    Comment(string path, Position position, String* txt)
    : Statement(path, position), text_(txt)
    { }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////
  // The Sass `@if` control directive.
  ////////////////////////////////////
  class If : public Statement {
    ADD_PROPERTY(Expression*, predicate);
    ADD_PROPERTY(Block*, consequent);
    ADD_PROPERTY(Block*, alternative);
  public:
    If(string path, Position position, Expression* pred, Block* con, Block* alt = 0)
    : Statement(path, position), predicate_(pred), consequent_(con), alternative_(alt)
    { }
    ATTACH_OPERATIONS();
  };

  /////////////////////////////////////
  // The Sass `@for` control directive.
  /////////////////////////////////////
  class For : public Has_Block {
    ADD_PROPERTY(string, variable);
    ADD_PROPERTY(Expression*, lower_bound);
    ADD_PROPERTY(Expression*, upper_bound);
    ADD_PROPERTY(bool, is_inclusive);
  public:
    For(string path, Position position,
        string var, Expression* lo, Expression* hi, Block* b, bool inc)
    : Has_Block(path, position, b),
      variable_(var), lower_bound_(lo), upper_bound_(hi), is_inclusive_(inc)
    { }
    ATTACH_OPERATIONS();
  };

  //////////////////////////////////////
  // The Sass `@each` control directive.
  //////////////////////////////////////
  class Each : public Has_Block {
    ADD_PROPERTY(string, variable);
    ADD_PROPERTY(Expression*, list);
  public:
    Each(string path, Position position, string var, Expression* lst, Block* b)
    : Has_Block(path, position, b), variable_(var), list_(lst)
    { }
    ATTACH_OPERATIONS();
  };

  ///////////////////////////////////////
  // The Sass `@while` control directive.
  ///////////////////////////////////////
  class While : public Has_Block {
    ADD_PROPERTY(Expression*, predicate);
  public:
    While(string path, Position position, Expression* pred, Block* b)
    : Has_Block(path, position, b), predicate_(pred)
    { }
    ATTACH_OPERATIONS();
  };

  /////////////////////////////////////////////////////////////
  // The @return directive for use inside SassScript functions.
  /////////////////////////////////////////////////////////////
  class Return : public Statement {
    ADD_PROPERTY(Expression*, value);
  public:
    Return(string path, Position position, Expression* val)
    : Statement(path, position), value_(val)
    { }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////
  // The Sass `@extend` directive.
  ////////////////////////////////
  class Extension : public Statement {
    ADD_PROPERTY(Selector*, selector);
  public:
    Extension(string path, Position position, Selector* s)
    : Statement(path, position), selector_(s)
    { }
    ATTACH_OPERATIONS();
  };

  /////////////////////////////////////////////////////////////////////////////
  // Definitions for both mixins and functions. The two cases are distinguished
  // by a type tag.
  /////////////////////////////////////////////////////////////////////////////
  struct Context;
  struct Backtrace;
  class Parameters;
  typedef Environment<AST_Node*> Env;
  typedef const char* Signature;
  typedef Expression* (*Native_Function)(Env&, Context&, Signature, const string&, Position, Backtrace*);
  typedef const char* Signature;
  class Definition : public Has_Block {
  public:
    enum Type { MIXIN, FUNCTION };
    ADD_PROPERTY(string, name);
    ADD_PROPERTY(Parameters*, parameters);
    ADD_PROPERTY(Env*, environment);
    ADD_PROPERTY(Type, type);
    ADD_PROPERTY(Native_Function, native_function);
    ADD_PROPERTY(Sass_C_Function, c_function);
    ADD_PROPERTY(bool, is_overload_stub);
    ADD_PROPERTY(Signature, signature);
  public:
    Definition(string path,
               Position position,
               string n,
               Parameters* params,
               Block* b,
               Type t)
    : Has_Block(path, position, b),
      name_(n),
      parameters_(params),
      environment_(0),
      type_(t),
      native_function_(0),
      c_function_(0),
      is_overload_stub_(false),
      signature_(0)
    { }
    Definition(string path,
               Position position,
               Signature sig,
               string n,
               Parameters* params,
               Native_Function func_ptr,
               bool overload_stub = false)
    : Has_Block(path, position, 0),
      name_(n),
      parameters_(params),
      environment_(0),
      type_(FUNCTION),
      native_function_(func_ptr),
      c_function_(0),
      is_overload_stub_(overload_stub),
      signature_(sig)
    { }
    Definition(string path,
               Position position,
               Signature sig,
               string n,
               Parameters* params,
               Sass_C_Function func_ptr,
               bool whatever,
               bool whatever2)
    : Has_Block(path, position, 0),
      name_(n),
      parameters_(params),
      environment_(0),
      type_(FUNCTION),
      native_function_(0),
      c_function_(func_ptr),
      is_overload_stub_(false),
      signature_(sig)
    { }
    ATTACH_OPERATIONS();
  };

  //////////////////////////////////////
  // Mixin calls (i.e., `@include ...`).
  //////////////////////////////////////
  class Arguments;
  class Mixin_Call : public Has_Block {
    ADD_PROPERTY(string, name);
    ADD_PROPERTY(Arguments*, arguments);
  public:
    Mixin_Call(string path, Position position, string n, Arguments* args, Block* b = 0)
    : Has_Block(path, position, b), name_(n), arguments_(args)
    { }
    ATTACH_OPERATIONS();
  };

  ///////////////////////////////////////////////////
  // The @content directive for mixin content blocks.
  ///////////////////////////////////////////////////
  class Content : public Statement {
  public:
    Content(string path, Position position) : Statement(path, position) { }
    ATTACH_OPERATIONS();
  };

  //////////////////////////////////////////////////////////////////////
  // Abstract base class for expressions. This side of the AST hierarchy
  // represents elements in value contexts, which exist primarily to be
  // evaluated and returned.
  //////////////////////////////////////////////////////////////////////
  class Expression : public AST_Node {
  public:
    enum Concrete_Type {
      NONE,
      BOOLEAN,
      NUMBER,
      COLOR,
      STRING,
      LIST,
      NULL_VAL,
      NUM_TYPES
    };
  private:
    // expressions in some contexts shouldn't be evaluated
    ADD_PROPERTY(bool, is_delayed);
    ADD_PROPERTY(bool, is_interpolant);
    ADD_PROPERTY(Concrete_Type, concrete_type);
  public:
    Expression(string path, Position position,
               bool d = false, bool i = false, Concrete_Type ct = NONE)
    : AST_Node(path, position),
      is_delayed_(d), is_interpolant_(i), concrete_type_(ct)
    { }
    virtual operator bool() { return true; }
    virtual ~Expression() = 0;
    virtual string type() { return ""; /* TODO: raise an error? */ }
    virtual bool is_invisible() { return false; }
    static string type_name() { return ""; }
    virtual bool is_false() { return false; }
  };
  inline Expression::~Expression() { }

  ///////////////////////////////////////////////////////////////////////
  // Lists of values, both comma- and space-separated (distinguished by a
  // type-tag.) Also used to represent variable-length argument lists.
  ///////////////////////////////////////////////////////////////////////
  class List : public Expression, public Vectorized<Expression*> {
  public:
    enum Separator { SPACE, COMMA };
  private:
    ADD_PROPERTY(Separator, separator);
    ADD_PROPERTY(bool, is_arglist);
  public:
    List(string path, Position position,
         size_t size = 0, Separator sep = SPACE, bool argl = false)
    : Expression(path, position),
      Vectorized<Expression*>(size),
      separator_(sep), is_arglist_(argl)
    { concrete_type(LIST); }
    string type() { return is_arglist_ ? "arglist" : "list"; }
    static string type_name() { return "list"; }
    bool is_invisible() { return !length(); }
    Expression* value_at_index(size_t i);
    ATTACH_OPERATIONS();
  };

  //////////////////////////////////////////////////////////////////////////
  // Binary expressions. Represents logical, relational, and arithmetic
  // operations. Templatized to avoid large switch statements and repetitive
  // subclassing.
  //////////////////////////////////////////////////////////////////////////
  class Binary_Expression : public Expression {
  public:
    enum Type {
      AND, OR,                   // logical connectives
      EQ, NEQ, GT, GTE, LT, LTE, // arithmetic relations
      ADD, SUB, MUL, DIV, MOD,   // arithmetic functions
      NUM_OPS                    // so we know how big to make the op table
    };
  private:
    ADD_PROPERTY(Type, type);
    ADD_PROPERTY(Expression*, left);
    ADD_PROPERTY(Expression*, right);
  public:
    Binary_Expression(string path, Position position,
                      Type t, Expression* lhs, Expression* rhs)
    : Expression(path, position), type_(t), left_(lhs), right_(rhs)
    { }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////////////////////////////////
  // Arithmetic negation (logical negation is just an ordinary function call).
  ////////////////////////////////////////////////////////////////////////////
  class Unary_Expression : public Expression {
  public:
    enum Type { PLUS, MINUS };
  private:
    ADD_PROPERTY(Type, type);
    ADD_PROPERTY(Expression*, operand);
  public:
    Unary_Expression(string path, Position position, Type t, Expression* o)
    : Expression(path, position), type_(t), operand_(o)
    { }
    ATTACH_OPERATIONS();
  };

  //////////////////
  // Function calls.
  //////////////////
  class Function_Call : public Expression {
    ADD_PROPERTY(string, name);
    ADD_PROPERTY(Arguments*, arguments);
  public:
    Function_Call(string path, Position position, string n, Arguments* args)
    : Expression(path, position), name_(n), arguments_(args)
    { concrete_type(STRING); }
    ATTACH_OPERATIONS();
  };

  /////////////////////////
  // Function call schemas.
  /////////////////////////
  class Function_Call_Schema : public Expression {
    ADD_PROPERTY(String*, name);
    ADD_PROPERTY(Arguments*, arguments);
  public:
    Function_Call_Schema(string path, Position position, String* n, Arguments* args)
    : Expression(path, position), name_(n), arguments_(args)
    { concrete_type(STRING); }
    ATTACH_OPERATIONS();
  };

  ///////////////////////
  // Variable references.
  ///////////////////////
  class Variable : public Expression {
    ADD_PROPERTY(string, name);
  public:
    Variable(string path, Position position, string n)
    : Expression(path, position), name_(n)
    { }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////////////////////////////////
  // Textual (i.e., unevaluated) numeric data. Variants are distinguished with
  // a type tag.
  ////////////////////////////////////////////////////////////////////////////
  class Textual : public Expression {
  public:
    enum Type { NUMBER, PERCENTAGE, DIMENSION, HEX };
  private:
    ADD_PROPERTY(Type, type);
    ADD_PROPERTY(string, value);
  public:
    Textual(string path, Position position, Type t, string val)
    : Expression(path, position, true), type_(t), value_(val)
    { }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////
  // Numbers, percentages, dimensions, and colors.
  ////////////////////////////////////////////////
  class Number : public Expression {
    ADD_PROPERTY(double, value);
    vector<string> numerator_units_;
    vector<string> denominator_units_;
  public:
    Number(string path, Position position, double val, string u = "")
    : Expression(path, position),
      value_(val),
      numerator_units_(vector<string>()),
      denominator_units_(vector<string>())
    {
      if (!u.empty()) numerator_units_.push_back(u);
      concrete_type(NUMBER);
    }
    vector<string>& numerator_units()   { return numerator_units_; }
    vector<string>& denominator_units() { return denominator_units_; }
    string type() { return "number"; }
    static string type_name() { return "number"; }
    string unit()
    {
      stringstream u;
      for (size_t i = 0, S = numerator_units_.size(); i < S; ++i) {
        if (i) u << '*';
        u << numerator_units_[i];
      }
      if (!denominator_units_.empty()) u << '/';
      for (size_t i = 0, S = denominator_units_.size(); i < S; ++i) {
        if (i) u << '*';
        u << denominator_units_[i];
      }
      return u.str();
    }
    bool is_unitless()
    { return numerator_units_.empty() && denominator_units_.empty(); }
    void normalize(string to = "")
    {
      // (multiple passes because I'm too tired to think up something clever)
      // Find a unit to convert everything to, if one isn't provided.
      if (to.empty()) {
        for (size_t i = 0, S = numerator_units_.size(); i < S; ++i) {
          string u(numerator_units_[i]);
          if (string_to_unit(u) == INCOMMENSURABLE) {
            continue;
          }
          else {
            to = u;
            break;
          }
        }
      }
      if (to.empty()) {
        for (size_t i = 0, S = denominator_units_.size(); i < S; ++i) {
          string u(denominator_units_[i]);
          if (string_to_unit(u) == INCOMMENSURABLE) {
            continue;
          }
          else {
            to = u;
            break;
          }
        }
      }
      // Now loop through again and do all the conversions.
      for (size_t i = 0, S = numerator_units_.size(); i < S; ++i) {
        string from(numerator_units_[i]);
        if (string_to_unit(from) == INCOMMENSURABLE) continue;
        value_ *= conversion_factor(from, to);
        numerator_units_[i] = to;
      }
      for (size_t i = 0, S = denominator_units_.size(); i < S; ++i) {
        string from(denominator_units_[i]);
        if (string_to_unit(from) == INCOMMENSURABLE) continue;
        value_ /= conversion_factor(from, to);
        denominator_units_[i] = to;
      }
      // Now divide out identical units in the numerator and denominator.
      vector<string> ncopy;
      ncopy.reserve(numerator_units_.size());
      for (vector<string>::iterator n = numerator_units_.begin();
           n != numerator_units_.end();
           ++n) {
        vector<string>::iterator d = find(denominator_units_.begin(),
                                          denominator_units_.end(),
                                          *n);
        if (d != denominator_units_.end()) {
          denominator_units_.erase(d);
        }
        else {
          ncopy.push_back(*n);
        }
      }
      numerator_units_ = ncopy;
      // Sort the units to make them pretty and, well, normal.
      sort(numerator_units_.begin(), numerator_units_.end());
      sort(denominator_units_.begin(), denominator_units_.end());
    }
    // useful for making one number compatible with another
    string find_convertible_unit()
    {
      for (size_t i = 0, S = numerator_units_.size(); i < S; ++i) {
        string u(numerator_units_[i]);
        if (string_to_unit(u) != INCOMMENSURABLE) return u;
      }
      for (size_t i = 0, S = denominator_units_.size(); i < S; ++i) {
        string u(denominator_units_[i]);
        if (string_to_unit(u) != INCOMMENSURABLE) return u;
      }
      return string();
    }
    ATTACH_OPERATIONS();
  };

  //////////
  // Colors.
  //////////
  class Color : public Expression {
    ADD_PROPERTY(double, r);
    ADD_PROPERTY(double, g);
    ADD_PROPERTY(double, b);
    ADD_PROPERTY(double, a);
    ADD_PROPERTY(string, disp);
  public:
    Color(string path, Position position, double r, double g, double b, double a = 1, const string disp = "")
    : Expression(path, position), r_(r), g_(g), b_(b), a_(a), disp_(disp)
    { concrete_type(COLOR); }
    string type() { return "color"; }
    static string type_name() { return "color"; }
    ATTACH_OPERATIONS();
  };

  ////////////
  // Booleans.
  ////////////
  class Boolean : public Expression {
    ADD_PROPERTY(bool, value);
  public:
    Boolean(string path, Position position, bool val) : Expression(path, position), value_(val)
    { concrete_type(BOOLEAN); }
    virtual operator bool() { return value_; }
    string type() { return "bool"; }
    static string type_name() { return "bool"; }
    virtual bool is_false() { return !value_; }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////////////////////////////
  // Abstract base class for Sass string values. Includes interpolated and
  // "flat" strings.
  ////////////////////////////////////////////////////////////////////////
  class String : public Expression {
    ADD_PROPERTY(bool, needs_unquoting);
  public:
    String(string path, Position position, bool unq = false, bool delayed = false)
    : Expression(path, position, delayed), needs_unquoting_(unq)
    { concrete_type(STRING); }
    static string type_name() { return "string"; }
    virtual ~String() = 0;
    ATTACH_OPERATIONS();
  };
  inline String::~String() { };

  ///////////////////////////////////////////////////////////////////////
  // Interpolated strings. Meant to be reduced to flat strings during the
  // evaluation phase.
  ///////////////////////////////////////////////////////////////////////
  class String_Schema : public String, public Vectorized<Expression*> {
    ADD_PROPERTY(char, quote_mark);
  public:
    String_Schema(string path, Position position, size_t size = 0, bool unq = false, char qm = '\0')
    : String(path, position, unq), Vectorized<Expression*>(size), quote_mark_(qm)
    { }
    string type() { return "string"; }
    static string type_name() { return "string"; }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////////////
  // Flat strings -- the lowest level of raw textual data.
  ////////////////////////////////////////////////////////
  class String_Constant : public String {
    ADD_PROPERTY(string, value);
  public:
    String_Constant(string path, Position position, string val, bool unq = false)
    : String(path, position, unq, true), value_(val)
    { }
    String_Constant(string path, Position position, const char* beg, bool unq = false)
    : String(path, position, unq, true), value_(string(beg))
    { }
    String_Constant(string path, Position position, const char* beg, const char* end, bool unq = false)
    : String(path, position, unq, true), value_(string(beg, end-beg))
    { }
    String_Constant(string path, Position position, const Token& tok, bool unq = false)
    : String(path, position, unq, true), value_(string(tok.begin, tok.end))
    { }
    string type() { return "string"; }
    static string type_name() { return "string"; }
    bool is_quoted() { return value_.length() && (value_[0] == '"' || value_[0] == '\''); }
    char quote_mark() { return is_quoted() ? value_[0] : '\0'; }
    ATTACH_OPERATIONS();
  };

  /////////////////
  // Media queries.
  /////////////////
  class Media_Query : public Expression,
                      public Vectorized<Media_Query_Expression*> {
    ADD_PROPERTY(String*, media_type);
    ADD_PROPERTY(bool, is_negated);
    ADD_PROPERTY(bool, is_restricted);
  public:
    Media_Query(string path, Position position,
                String* t = 0, size_t s = 0, bool n = false, bool r = false)
    : Expression(path, position), Vectorized<Media_Query_Expression*>(s),
      media_type_(t), is_negated_(n), is_restricted_(r)
    { }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////////
  // Media expressions (for use inside media queries).
  ////////////////////////////////////////////////////
  class Media_Query_Expression : public Expression {
    ADD_PROPERTY(Expression*, feature);
    ADD_PROPERTY(Expression*, value);
    ADD_PROPERTY(bool, is_interpolated);
  public:
    Media_Query_Expression(string path, Position position,
                           Expression* f, Expression* v, bool i = false)
    : Expression(path, position), feature_(f), value_(v), is_interpolated_(i)
    { }
    ATTACH_OPERATIONS();
  };

  //////////////////
  // The null value.
  //////////////////
  class Null : public Expression {
  public:
    Null(string path, Position position) : Expression(path, position) { concrete_type(NULL_VAL); }
    string type() { return "null"; }
    static string type_name() { return "null"; }
    bool is_invisible() { return true; }
    operator bool() { return false; }
    bool is_false() { return true; }
    ATTACH_OPERATIONS();
  };

  /////////////////////////////////
  // Thunks for delayed evaluation.
  /////////////////////////////////
  class Thunk : public Expression {
    ADD_PROPERTY(Expression*, expression);
    ADD_PROPERTY(Env*, environment);
  public:
    Thunk(string path, Position position, Expression* exp, Env* env = 0)
    : Expression(path, position), expression_(exp), environment_(env)
    { }
  };

  /////////////////////////////////////////////////////////
  // Individual parameter objects for mixins and functions.
  /////////////////////////////////////////////////////////
  class Parameter : public AST_Node {
    ADD_PROPERTY(string, name);
    ADD_PROPERTY(Expression*, default_value);
    ADD_PROPERTY(bool, is_rest_parameter);
  public:
    Parameter(string p, Position pos,
              string n, Expression* def = 0, bool rest = false)
    : AST_Node(p, pos), name_(n), default_value_(def), is_rest_parameter_(rest)
    {
      if (default_value_ && is_rest_parameter_) {
        error("variable-length parameter may not have a default value", path(), position());
      }
    }
    ATTACH_OPERATIONS();
  };

  /////////////////////////////////////////////////////////////////////////
  // Parameter lists -- in their own class to facilitate context-sensitive
  // error checking (e.g., ensuring that all optional parameters follow all
  // required parameters).
  /////////////////////////////////////////////////////////////////////////
  class Parameters : public AST_Node, public Vectorized<Parameter*> {
    ADD_PROPERTY(bool, has_optional_parameters);
    ADD_PROPERTY(bool, has_rest_parameter);
  protected:
    void adjust_after_pushing(Parameter* p)
    {
      if (p->default_value()) {
        if (has_rest_parameter_) {
          error("optional parameters may not be combined with variable-length parameters", p->path(), p->position());
        }
        has_optional_parameters_ = true;
      }
      else if (p->is_rest_parameter()) {
        if (has_rest_parameter_) {
          error("functions and mixins cannot have more than one variable-length parameter", p->path(), p->position());
        }
        if (has_optional_parameters_) {
          error("optional parameters may not be combined with variable-length parameters", p->path(), p->position());
        }
        has_rest_parameter_ = true;
      }
      else {
        if (has_rest_parameter_) {
          error("required parameters must precede variable-length parameters", p->path(), p->position());
        }
        if (has_optional_parameters_) {
          error("required parameters must precede optional parameters", p->path(), p->position());
        }
      }
    }
  public:
    Parameters(string path, Position position)
    : AST_Node(path, position),
      Vectorized<Parameter*>(),
      has_optional_parameters_(false),
      has_rest_parameter_(false)
    { }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////////////////
  // Individual argument objects for mixin and function calls.
  ////////////////////////////////////////////////////////////
  class Argument : public Expression {
    ADD_PROPERTY(Expression*, value);
    ADD_PROPERTY(string, name);
    ADD_PROPERTY(bool, is_rest_argument);
  public:
    Argument(string p, Position pos, Expression* val, string n = "", bool rest = false)
    : Expression(p, pos), value_(val), name_(n), is_rest_argument_(rest)
    {
      if (!name_.empty() && is_rest_argument_) {
        error("variable-length argument may not be passed by name", path(), position());
      }
    }
    ATTACH_OPERATIONS();
  };

  //////////////////////////////////////////////////////////////////////////////////////////
  // Additional method on Lists to retrieve values directly or from an encompassed Argument.
  //////////////////////////////////////////////////////////////////////////////////////////
  inline Expression* List::value_at_index(size_t i) { return is_arglist_ ? ((Argument*)(*this)[i])->value() : (*this)[i]; }

  ////////////////////////////////////////////////////////////////////////
  // Argument lists -- in their own class to facilitate context-sensitive
  // error checking (e.g., ensuring that all ordinal arguments precede all
  // named arguments).
  ////////////////////////////////////////////////////////////////////////
  class Arguments : public Expression, public Vectorized<Argument*> {
    ADD_PROPERTY(bool, has_named_arguments);
    ADD_PROPERTY(bool, has_rest_argument);
  protected:
    void adjust_after_pushing(Argument* a)
    {
      if (!a->name().empty()) {
        if (has_rest_argument_) {
          error("named arguments must precede variable-length argument", a->path(), a->position());
        }
        has_named_arguments_ = true;
      }
      else if (a->is_rest_argument()) {
        if (has_rest_argument_) {
          error("functions and mixins may only be called with one variable-length argument", a->path(), a->position());
        }
        if (has_named_arguments_) {
          error("functions and mixins may not be called with both named arguments and variable-length arguments", a->path(), a->position());
        }
        has_rest_argument_ = true;
      }
      else {
        if (has_rest_argument_) {
          error("ordinal arguments must precede variable-length arguments", a->path(), a->position());
        }
        if (has_named_arguments_) {
          error("ordinal arguments must precede named arguments", a->path(), a->position());
        }
      }
    }
  public:
    Arguments(string path, Position position)
    : Expression(path, position),
      Vectorized<Argument*>(),
      has_named_arguments_(false),
      has_rest_argument_(false)
    { }
    ATTACH_OPERATIONS();
  };

  /////////////////////////////////////////
  // Abstract base class for CSS selectors.
  /////////////////////////////////////////
  class Selector : public AST_Node {
    ADD_PROPERTY(bool, has_reference);
    ADD_PROPERTY(bool, has_placeholder);
  public:
    Selector(string path, Position position, bool r = false, bool h = false)
    : AST_Node(path, position), has_reference_(r), has_placeholder_(h)
    { }
    virtual ~Selector() = 0;
    virtual Selector_Placeholder* find_placeholder();
    virtual int specificity() { return Constants::SPECIFICITY_BASE; }
  };
  inline Selector::~Selector() { }

  /////////////////////////////////////////////////////////////////////////
  // Interpolated selectors -- the interpolated String will be expanded and
  // re-parsed into a normal selector class.
  /////////////////////////////////////////////////////////////////////////
  class Selector_Schema : public Selector {
    ADD_PROPERTY(String*, contents);
  public:
    Selector_Schema(string path, Position position, String* c)
    : Selector(path, position), contents_(c)
    { }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////
  // Abstract base class for simple selectors.
  ////////////////////////////////////////////
  class Simple_Selector : public Selector {
  public:
    Simple_Selector(string path, Position position)
    : Selector(path, position)
    { }
    virtual ~Simple_Selector() = 0;
    virtual Compound_Selector* unify_with(Compound_Selector*, Context&);
    virtual bool is_pseudo_element() { return false; }
  };
  inline Simple_Selector::~Simple_Selector() { }

  /////////////////////////////////////
  // Parent references (i.e., the "&").
  /////////////////////////////////////
  class Selector_Reference : public Simple_Selector {
    ADD_PROPERTY(Selector*, selector);
  public:
    Selector_Reference(string path, Position position, Selector* r = 0)
    : Simple_Selector(path, position), selector_(r)
    { has_reference(true); }
    virtual int specificity()
    {
      if (selector()) return selector()->specificity();
      else            return 0;
    }
    ATTACH_OPERATIONS();
  };

  /////////////////////////////////////////////////////////////////////////
  // Placeholder selectors (e.g., "%foo") for use in extend-only selectors.
  /////////////////////////////////////////////////////////////////////////
  class Selector_Placeholder : public Simple_Selector {
    ADD_PROPERTY(string, name);
  public:
    Selector_Placeholder(string path, Position position, string n)
    : Simple_Selector(path, position), name_(n)
    { has_placeholder(true); }
    virtual Selector_Placeholder* find_placeholder();
    ATTACH_OPERATIONS();
  };

  /////////////////////////////////////////////////////////////////////
  // Type selectors (and the universal selector) -- e.g., div, span, *.
  /////////////////////////////////////////////////////////////////////
  class Type_Selector : public Simple_Selector {
    ADD_PROPERTY(string, name);
  public:
    Type_Selector(string path, Position position, string n)
    : Simple_Selector(path, position), name_(n)
    { }
    virtual int specificity()
    {
      if (name() == "*") return 0;
      else               return 1;
    }
    virtual Compound_Selector* unify_with(Compound_Selector*, Context&);
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////
  // Selector qualifiers -- i.e., classes and ids.
  ////////////////////////////////////////////////
  class Selector_Qualifier : public Simple_Selector {
    ADD_PROPERTY(string, name);
  public:
    Selector_Qualifier(string path, Position position, string n)
    : Simple_Selector(path, position), name_(n)
    { }
    virtual int specificity()
    {
      if (name()[0] == '#') return Constants::SPECIFICITY_BASE * Constants::SPECIFICITY_BASE;
      else                  return Constants::SPECIFICITY_BASE;
    }
    virtual Compound_Selector* unify_with(Compound_Selector*, Context&);
    ATTACH_OPERATIONS();
  };

  ///////////////////////////////////////////////////
  // Attribute selectors -- e.g., [src*=".jpg"], etc.
  ///////////////////////////////////////////////////
  class Attribute_Selector : public Simple_Selector {
    ADD_PROPERTY(string, name);
    ADD_PROPERTY(string, matcher);
    ADD_PROPERTY(string, value);
  public:
    Attribute_Selector(string path, Position position, string n, string m, string v)
    : Simple_Selector(path, position), name_(n), matcher_(m), value_(v)
    { }
    ATTACH_OPERATIONS();
  };

  //////////////////////////////////////////////////////////////////
  // Pseudo selectors -- e.g., :first-child, :nth-of-type(...), etc.
  //////////////////////////////////////////////////////////////////
  class Pseudo_Selector : public Simple_Selector {
    ADD_PROPERTY(string, name);
    ADD_PROPERTY(String*, expression);
  public:
    Pseudo_Selector(string path, Position position, string n, String* expr = 0)
    : Simple_Selector(path, position), name_(n), expression_(expr)
    { }
    virtual int specificity()
    {
      // TODO: clean up the pseudo-element checking
      if (name() == ":before"       || name() == "::before"     ||
          name() == ":after"        || name() == "::after"      ||
          name() == ":first-line"   || name() == "::first-line" ||
          name() == ":first-letter" || name() == "::first-letter")
        return 1;
      else
        return Constants::SPECIFICITY_BASE;
    }
    virtual bool is_pseudo_element()
    {
      return name() == ":before"       || name() == "::before"     ||
             name() == ":after"        || name() == "::after"      ||
             name() == ":first-line"   || name() == "::first-line" ||
             name() == ":first-letter" || name() == "::first-letter";
    }
    virtual Compound_Selector* unify_with(Compound_Selector*, Context&);
    ATTACH_OPERATIONS();
  };

  /////////////////////////////////////////////////
  // Negated selector -- e.g., :not(:first-of-type)
  /////////////////////////////////////////////////
  class Negated_Selector : public Simple_Selector {
    ADD_PROPERTY(Selector*, selector);
  public:
    Negated_Selector(string path, Position position, Selector* sel)
    : Simple_Selector(path, position), selector_(sel)
    { }
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////////////////////////////////
  // Simple selector sequences. Maintains flags indicating whether it contains
  // any parent references or placeholders, to simplify expansion.
  ////////////////////////////////////////////////////////////////////////////
  class Compound_Selector : public Selector, public Vectorized<Simple_Selector*> {
  private:
    set<Complex_Selector> sources_;
  protected:
    void adjust_after_pushing(Simple_Selector* s)
    {
      if (s->has_reference())   has_reference(true);
      if (s->has_placeholder()) has_placeholder(true);
    }
  public:
    Compound_Selector(string path, Position position, size_t s = 0)
    : Selector(path, position),
      Vectorized<Simple_Selector*>(s)
    { }
    bool operator<(const Compound_Selector& rhs) const;
    Compound_Selector* unify_with(Compound_Selector* rhs, Context& ctx);
    virtual Selector_Placeholder* find_placeholder();
    Simple_Selector* base()
    {
      if (length() > 0 && typeid(*(*this)[0]) == typeid(Type_Selector))
        return (*this)[0];
      return 0;
    }
    bool is_superselector_of(Compound_Selector* rhs);
    virtual int specificity()
    {
      int sum = 0;
      for (size_t i = 0, L = length(); i < L; ++i)
      { sum += (*this)[i]->specificity(); }
      return sum;
    }
    bool is_empty_reference()
    {
      return length() == 1 &&
             typeid(*(*this)[0]) == typeid(Selector_Reference) &&
             !static_cast<Selector_Reference*>((*this)[0])->selector();
    }
    vector<string> to_str_vec(); // sometimes need to convert to a flat "by-value" data structure

    set<Complex_Selector>& sources() { return sources_; }
    Compound_Selector* minus(Compound_Selector* rhs, Context& ctx);
    ATTACH_OPERATIONS();
  };

  ////////////////////////////////////////////////////////////////////////////
  // General selectors -- i.e., simple sequences combined with one of the four
  // CSS selector combinators (">", "+", "~", and whitespace). Essentially a
  // linked list.
  ////////////////////////////////////////////////////////////////////////////
  struct Context;
  class Complex_Selector : public Selector {
  public:
    enum Combinator { ANCESTOR_OF, PARENT_OF, PRECEDES, ADJACENT_TO };
  private:
    ADD_PROPERTY(Combinator, combinator);
    ADD_PROPERTY(Compound_Selector*, head);
    ADD_PROPERTY(Complex_Selector*, tail);
  public:
    Complex_Selector(string path, Position position,
                         Combinator c,
                         Compound_Selector* h,
                         Complex_Selector* t)
    : Selector(path, position), combinator_(c), head_(h), tail_(t)
    {
      if ((h && h->has_reference())   || (t && t->has_reference()))   has_reference(true);
      if ((h && h->has_placeholder()) || (t && t->has_placeholder())) has_placeholder(true);
    }
    Compound_Selector* base();
    Complex_Selector* context(Context&);
    Complex_Selector* innermost();
    size_t length();
    bool is_superselector_of(Compound_Selector*);
    bool is_superselector_of(Complex_Selector*);
    virtual Selector_Placeholder* find_placeholder();
    Combinator clear_innermost();
    void set_innermost(Complex_Selector*, Combinator);
    virtual int specificity()
    {
      int sum = 0;
      if (head()) sum += head()->specificity();
      if (tail()) sum += tail()->specificity();
      return sum;
    }
    bool operator<(const Complex_Selector& rhs) const;
    set<Complex_Selector> sources()
    {
      set<Complex_Selector> srcs;
      Compound_Selector* h = head();
      Complex_Selector*  t = tail();
      if (!h && !t) return srcs;
      if (!h && t)  return srcs = t->sources();
      if (h && !t)  return srcs = h->sources();
      if (h && t)
      {
        vector<Complex_Selector> vec;
        set_union(h->sources().begin(), h->sources().end(),
                  t->sources().begin(), t->sources().end(),
                  vec.begin());
        for (size_t i = 0, S = vec.size(); i < S; ++i) srcs.insert(vec[i]);
        return srcs;
      }
      // fallback
      return srcs;
    }
    Complex_Selector* clone(Context&);
    vector<Compound_Selector*> to_vector();
    ATTACH_OPERATIONS();
  };

  ///////////////////////////////////
  // Comma-separated selector groups.
  ///////////////////////////////////
  class Selector_List
      : public Selector, public Vectorized<Complex_Selector*> {
  protected:
    void adjust_after_pushing(Complex_Selector* c)
    {
      if (c->has_reference())   has_reference(true);
      if (c->has_placeholder()) has_placeholder(true);
    }
  public:
    Selector_List(string path, Position position, size_t s = 0)
    : Selector(path, position), Vectorized<Complex_Selector*>(s)
    { }
    virtual Selector_Placeholder* find_placeholder();
    virtual int specificity()
    {
      int sum = 0;
      for (size_t i = 0, L = length(); i < L; ++i)
      { sum += (*this)[i]->specificity(); }
      return sum;
    }
    // vector<Complex_Selector*> members() { return elements_; }
    ATTACH_OPERATIONS();
  };
}

#ifdef __clang__

#pragma clang diagnostic pop

#endif
