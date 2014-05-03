#define SASS_PARSER

#include <vector>
#include <map>

#ifndef SASS_PRELEXER
#include "prelexer.hpp"
#endif

#ifndef SASS_TOKEN
#include "token.hpp"
#endif

#ifndef SASS_CONTEXT
#include "context.hpp"
#endif

#ifndef SASS_AST
#include "ast.hpp"
#endif

#ifndef SASS_POSITION
#include "position.hpp"
#endif

#include <iostream>

struct Selector_Lookahead {
  const char* found;
  bool has_interpolants;
};

namespace Sass {
  using std::string;
  using std::vector;
  using std::map;
  using namespace Prelexer;

  class Parser {
  public:
    class AST_Node;

    enum Syntactic_Context { nothing, mixin_def, function_def };

    Context& ctx;
    vector<Syntactic_Context> stack;
    const char* source;
    const char* position;
    const char* end;
    string path;
    size_t column;
    Position source_position;


    Token lexed;

    Parser(Context& ctx, string path, Position source_position)
    : ctx(ctx), stack(vector<Syntactic_Context>()),
      source(0), position(0), end(0), path(path), column(1), source_position(source_position)
    { stack.push_back(nothing); }

    static Parser from_string(string src, Context& ctx, string path = "", Position source_position = Position());
    static Parser from_c_str(const char* src, Context& ctx, string path = "", Position source_position = Position());
    static Parser from_token(Token t, Context& ctx, string path = "", Position source_position = Position());

#ifdef __clang__

    // lex and peak uses the template parameter to branch on the action, which
    // triggers clangs tautological comparison on the single-comparison
    // branches. This is not a bug, just a merging of behaviour into
    // one function

#pragma clang diagnostic push
#pragma clang diagnostic ignored "-Wtautological-compare"

#endif

    template <prelexer mx>
    const char* peek(const char* start = 0)
    {
      if (!start) start = position;
      const char* after_whitespace;
      if (mx == block_comment) {
        after_whitespace = // start;
          zero_plus< alternatives<spaces, line_comment> >(start);
      }
      else if (/*mx == ancestor_of ||*/ mx == no_spaces) {
        after_whitespace = position;
      }
      else if (mx == spaces || mx == ancestor_of) {
        after_whitespace = mx(start);
        if (after_whitespace) {
          return after_whitespace;
        }
        else {
          return 0;
        }
      }
      else if (mx == optional_spaces) {
        after_whitespace = optional_spaces(start);
      }
      else if (mx == line_comment_prefix || mx == block_comment_prefix) {
        after_whitespace = position;
      }
      else {
        after_whitespace = spaces_and_comments(start);
      }
      const char* after_token = mx(after_whitespace);
      if (after_token) {
        return after_token;
      }
      else {
        return 0;
      }
    }

    template <prelexer mx>
    const char* lex()
    {
      const char* after_whitespace;
      if (mx == block_comment) {
        after_whitespace = // position;
          zero_plus< alternatives<spaces, line_comment> >(position);
      }
      else if (mx == url) {
        after_whitespace = position;
      }
      else if (mx == ancestor_of || mx == no_spaces) {
        after_whitespace = position;
      }
      else if (mx == spaces) {
        after_whitespace = spaces(position);
        if (after_whitespace) {
          source_position.line += count_interval<'\n'>(position, after_whitespace);
          lexed = Token(position, after_whitespace);
          return position = after_whitespace;
        }
        else {
          return 0;
        }
      }
      else if (mx == optional_spaces) {
        after_whitespace = optional_spaces(position);
      }
      else {
        after_whitespace = spaces_and_comments(position);
      }
      const char* after_token = mx(after_whitespace);
      if (after_token) {
        size_t previous_line = source_position.line;
        source_position.line += count_interval<'\n'>(position, after_token);

        size_t whitespace = 0;
        const char* ptr = after_whitespace - 1;
        while (ptr >= position) {
          if (*ptr == '\n')
            break;
          whitespace++;
          ptr--;
        }
        if (previous_line != source_position.line) {
          column = 1;
        }

        source_position.column = column + whitespace;
        column += after_token - after_whitespace + whitespace;
        lexed = Token(after_whitespace, after_token);

        return position = after_token;
      }
      else {
        return 0;
      }
    }

#ifdef __clang__

#pragma clang diagnostic pop

#endif

    void error(string msg, Position pos = Position());
    void read_bom();

    Block* parse();
    Import* parse_import();
    Definition* parse_definition();
    Parameters* parse_parameters();
    Parameter* parse_parameter();
    Mixin_Call* parse_mixin_call();
    Arguments* parse_arguments();
    Argument* parse_argument();
    Assignment* parse_assignment();
    Propset* parse_propset();
    Ruleset* parse_ruleset(Selector_Lookahead lookahead);
    Selector_Schema* parse_selector_schema(const char* end_of_selector);
    Selector_List* parse_selector_group();
    Complex_Selector* parse_selector_combination();
    Compound_Selector* parse_simple_selector_sequence();
    Simple_Selector* parse_simple_selector();
    Negated_Selector* parse_negated_selector();
    Pseudo_Selector* parse_pseudo_selector();
    Attribute_Selector* parse_attribute_selector();
    Block* parse_block();
    Declaration* parse_declaration();
    Expression* parse_list();
    Expression* parse_comma_list();
    Expression* parse_space_list();
    Expression* parse_disjunction();
    Expression* parse_conjunction();
    Expression* parse_relation();
    Expression* parse_expression();
    Expression* parse_term();
    Expression* parse_factor();
    Expression* parse_value();
    Function_Call* parse_calc_function();
    Function_Call* parse_function_call();
    Function_Call_Schema* parse_function_call_schema();
    String* parse_interpolated_chunk(Token);
    String* parse_string();
    String* parse_ie_stuff();
    String_Schema* parse_value_schema();
    String* parse_identifier_schema();
    String_Schema* parse_url_schema();
    If* parse_if_directive(bool else_if = false);
    For* parse_for_directive();
    Each* parse_each_directive();
    While* parse_while_directive();
    Media_Block* parse_media_block();
    List* parse_media_queries();
    Media_Query* parse_media_query();
    Media_Query_Expression* parse_media_expression();
    At_Rule* parse_at_rule();
    Warning* parse_warning();

    Selector_Lookahead lookahead_for_selector(const char* start = 0);
    Selector_Lookahead lookahead_for_extension_target(const char* start = 0);

    Expression* fold_operands(Expression* base, vector<Expression*>& operands, Binary_Expression::Type op);
    Expression* fold_operands(Expression* base, vector<Expression*>& operands, vector<Binary_Expression::Type>& ops);

    void throw_syntax_error(string message, size_t ln = 0);
    void throw_read_error(string message, size_t ln = 0);
  };

  size_t check_bom_chars(const char* src, const char *end, const unsigned char* bom, size_t len);
}
