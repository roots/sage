#include "inspect.hpp"
#include "ast.hpp"
#include "context.hpp"
#include <cmath>
#include <iostream>
#include <iomanip>

namespace Sass {
  using namespace std;

  Inspect::Inspect(Context* ctx) : buffer(""), indentation(0), ctx(ctx) { }
  Inspect::~Inspect() { }

  // statements
  void Inspect::operator()(Block* block)
  {
    if (!block->is_root()) {
      append_to_buffer(" {\n");
      ++indentation;
    }
    for (size_t i = 0, L = block->length(); i < L; ++i) {
      indent();
      (*block)[i]->perform(this);
      // extra newline at the end of top-level statements
      if (block->is_root()) append_to_buffer("\n");
      append_to_buffer("\n");
    }
    if (!block->is_root()) {
      --indentation;
      indent();
      append_to_buffer("}");
    }
    // remove extra newline that gets added after the last top-level block
    if (block->is_root()) {
      size_t l = buffer.length();
      if (l > 2 && buffer[l-1] == '\n' && buffer[l-2] == '\n') {
        buffer.erase(l-1);
        if (ctx) ctx->source_map.remove_line();
      }
    }
  }

  void Inspect::operator()(Ruleset* ruleset)
  {
    ruleset->selector()->perform(this);
    ruleset->block()->perform(this);
  }

  void Inspect::operator()(Propset* propset)
  {
    propset->property_fragment()->perform(this);
    append_to_buffer(": ");
    propset->block()->perform(this);
  }

  void Inspect::operator()(Media_Block* media_block)
  {
    if (ctx) ctx->source_map.add_mapping(media_block);
    append_to_buffer("@media ");
    media_block->media_queries()->perform(this);
    media_block->block()->perform(this);
  }

  void Inspect::operator()(At_Rule* at_rule)
  {
    append_to_buffer(at_rule->keyword());
    if (at_rule->selector()) {
      append_to_buffer(" ");
      at_rule->selector()->perform(this);
    }
    if (at_rule->block()) {
      at_rule->block()->perform(this);
    }
    else {
      append_to_buffer(";");
    }
  }

  void Inspect::operator()(Declaration* dec)
  {
    if (ctx) ctx->source_map.add_mapping(dec->property());
    dec->property()->perform(this);
    append_to_buffer(": ");
    if (ctx) ctx->source_map.add_mapping(dec->value());
    dec->value()->perform(this);
    if (dec->is_important()) append_to_buffer(" !important");
    append_to_buffer(";");
  }

  void Inspect::operator()(Assignment* assn)
  {
    append_to_buffer(assn->variable());
    append_to_buffer(": ");
    assn->value()->perform(this);
    if (assn->is_guarded()) append_to_buffer(" !default");
    append_to_buffer(";");
  }

  void Inspect::operator()(Import* import)
  {
    if (!import->urls().empty()) {
      if (ctx) ctx->source_map.add_mapping(import);
      append_to_buffer("@import ");
      import->urls().front()->perform(this);
      append_to_buffer(";");
      for (size_t i = 1, S = import->urls().size(); i < S; ++i) {
        append_to_buffer("\n");
        if (ctx) ctx->source_map.add_mapping(import);
        append_to_buffer("@import ");
        import->urls()[i]->perform(this);
        append_to_buffer(";");
      }
    }
  }

  void Inspect::operator()(Import_Stub* import)
  {
    if (ctx) ctx->source_map.add_mapping(import);
    append_to_buffer("@import ");
    append_to_buffer(import->file_name());
    append_to_buffer(";");
  }

  void Inspect::operator()(Warning* warning)
  {
    if (ctx) ctx->source_map.add_mapping(warning);
    append_to_buffer("@warn ");
    warning->message()->perform(this);
    append_to_buffer(";");
  }

  void Inspect::operator()(Comment* comment)
  {
    comment->text()->perform(this);
  }

  void Inspect::operator()(If* cond)
  {
    append_to_buffer("@if ");
    cond->predicate()->perform(this);
    cond->consequent()->perform(this);
    if (cond->alternative()) {
      append_to_buffer("\n");
      indent();
      append_to_buffer("else");
      cond->alternative()->perform(this);
    }
  }

  void Inspect::operator()(For* loop)
  {
    append_to_buffer("@for ");
    append_to_buffer(loop->variable());
    append_to_buffer(" from ");
    loop->lower_bound()->perform(this);
    append_to_buffer((loop->is_inclusive() ? " through " : " to "));
    loop->upper_bound()->perform(this);
    loop->block()->perform(this);
  }

  void Inspect::operator()(Each* loop)
  {
    append_to_buffer("@each ");
    append_to_buffer(loop->variable());
    append_to_buffer(" in ");
    loop->list()->perform(this);
    loop->block()->perform(this);
  }

  void Inspect::operator()(While* loop)
  {
    append_to_buffer("@while ");
    loop->predicate()->perform(this);
    loop->block()->perform(this);
  }

  void Inspect::operator()(Return* ret)
  {
    append_to_buffer("@return ");
    ret->value()->perform(this);
    append_to_buffer(";");
  }

  void Inspect::operator()(Extension* extend)
  {
    append_to_buffer("@extend ");
    extend->selector()->perform(this);
    append_to_buffer(";");
  }

  void Inspect::operator()(Definition* def)
  {
    if (def->type() == Definition::MIXIN) {
      append_to_buffer("@mixin ");
    } else {
      append_to_buffer("@function ");
    }
    append_to_buffer(def->name());
    def->parameters()->perform(this);
    def->block()->perform(this);
  }

  void Inspect::operator()(Mixin_Call* call)
  {
    append_to_buffer(string("@include ") += call->name());
    if (call->arguments()) {
      call->arguments()->perform(this);
    }
    if (call->block()) {
      append_to_buffer(" ");
      call->block()->perform(this);
    }
    if (!call->block()) append_to_buffer(";");
  }

  void Inspect::operator()(Content* content)
  {
    if (ctx) ctx->source_map.add_mapping(content);
    append_to_buffer("@content;");
  }

  void Inspect::operator()(List* list)
  {
    string sep(list->separator() == List::SPACE ? " " : ", ");
    if (list->empty()) return;
    bool items_output = false;
    for (size_t i = 0, L = list->length(); i < L; ++i) {
      Expression* list_item = (*list)[i];
      if (list_item->is_invisible()) {
        continue;
      }
      if (items_output) append_to_buffer(sep);
      list_item->perform(this);
      items_output = true;
    }
  }

  void Inspect::operator()(Binary_Expression* expr)
  {
    expr->left()->perform(this);
    switch (expr->type()) {
      case Binary_Expression::AND: append_to_buffer(" and "); break;
      case Binary_Expression::OR:  append_to_buffer(" or ");  break;
      case Binary_Expression::EQ:  append_to_buffer(" == ");  break;
      case Binary_Expression::NEQ: append_to_buffer(" != ");  break;
      case Binary_Expression::GT:  append_to_buffer(" > ");   break;
      case Binary_Expression::GTE: append_to_buffer(" >= ");  break;
      case Binary_Expression::LT:  append_to_buffer(" < ");   break;
      case Binary_Expression::LTE: append_to_buffer(" <= ");  break;
      case Binary_Expression::ADD: append_to_buffer(" + ");   break;
      case Binary_Expression::SUB: append_to_buffer(" - ");   break;
      case Binary_Expression::MUL: append_to_buffer(" * ");   break;
      case Binary_Expression::DIV: append_to_buffer("/");     break;
      case Binary_Expression::MOD: append_to_buffer(" % ");   break;
      default: break; // shouldn't get here
    }
    expr->right()->perform(this);
  }

  void Inspect::operator()(Unary_Expression* expr)
  {
    if (expr->type() == Unary_Expression::PLUS) append_to_buffer("+");
    else                                        append_to_buffer("-");
    expr->operand()->perform(this);
  }

  void Inspect::operator()(Function_Call* call)
  {
    append_to_buffer(call->name());
    call->arguments()->perform(this);
  }

  void Inspect::operator()(Function_Call_Schema* call)
  {
    call->name()->perform(this);
    call->arguments()->perform(this);
  }

  void Inspect::operator()(Variable* var)
  {
    append_to_buffer(var->name());
  }

  void Inspect::operator()(Textual* txt)
  {
    append_to_buffer(txt->value());
  }

  // helper functions for serializing numbers
  string frac_to_string(double f, size_t p) {
    stringstream ss;
    ss.setf(ios::fixed, ios::floatfield);
    ss.precision(p);
    ss << f;
    string result(ss.str().substr(f < 0 ? 2 : 1));
    size_t i = result.size() - 1;
    while (result[i] == '0') --i;
    result = result.substr(0, i+1);
    return result;
  }
  string double_to_string(double d, size_t p) {
    stringstream ss;
    double ipart;
    double fpart = std::modf(d, &ipart);
    ss << ipart;
    if (fpart != 0) ss << frac_to_string(fpart, 5);
    return ss.str();
  }

  void Inspect::operator()(Number* n)
  {
    stringstream ss;
    ss.precision(5);
    ss << fixed << n->value();
    string d(ss.str());
    for (size_t i = d.length()-1; d[i] == '0'; --i) {
      d.resize(d.length()-1);
    }
    if (d[d.length()-1] == '.') d.resize(d.length()-1);
    if (n->numerator_units().size() > 1 || n->denominator_units().size() > 0) {
      error(d + n->unit() + " is not a valid CSS value", n->path(), n->position());
    }
    append_to_buffer(d);
    append_to_buffer(n->unit());
  }

  // helper function for serializing colors
  template <size_t range>
  static double cap_channel(double c) {
    if      (c > range) return range;
    else if (c < 0)     return 0;
    else                return c;
  }

  void Inspect::operator()(Color* c)
  {
    stringstream ss;
    double r = cap_channel<0xff>(c->r());
    double g = cap_channel<0xff>(c->g());
    double b = cap_channel<0xff>(c->b());
    double a = cap_channel<1>   (c->a());

    // if (a >= 1 && ctx.colors_to_names.count(numval)) {
    //   ss << ctx.colors_to_names[numval];
    // }
    // else

    // retain the originally specified color definition if unchanged
    if (!c->disp().empty()) {
      ss << c->disp();
    }
    else if (a >= 1) {
      // see if it's a named color
      int numval = r * 0x10000;
      numval += g * 0x100;
      numval += b;
      if (ctx && ctx->colors_to_names.count(numval)) {
        ss << ctx->colors_to_names[numval];
      }
      else {
        // otherwise output the hex triplet
        ss << '#' << setw(2) << setfill('0');
        ss << hex << setw(2) << static_cast<unsigned long>(floor(r+0.5));
        ss << hex << setw(2) << static_cast<unsigned long>(floor(g+0.5));
        ss << hex << setw(2) << static_cast<unsigned long>(floor(b+0.5));
      }
    }
    else {
      ss << "rgba(";
      ss << static_cast<unsigned long>(r) << ", ";
      ss << static_cast<unsigned long>(g) << ", ";
      ss << static_cast<unsigned long>(b) << ", ";
      ss << a << ')';
    }
    append_to_buffer(ss.str());
  }

  void Inspect::operator()(Boolean* b)
  {
    append_to_buffer(b->value() ? "true" : "false");
  }

  void Inspect::operator()(String_Schema* ss)
  {
    // Evaluation should turn these into String_Constants, so this method is
    // only for inspection purposes.
    for (size_t i = 0, L = ss->length(); i < L; ++i) {
      if ((*ss)[i]->is_interpolant()) append_to_buffer("#{");
      (*ss)[i]->perform(this);
      if ((*ss)[i]->is_interpolant()) append_to_buffer("}");
    }
  }

  void Inspect::operator()(String_Constant* s)
  {
    append_to_buffer(s->needs_unquoting() ? unquote(s->value()) : s->value());
  }

  void Inspect::operator()(Media_Query* mq)
  {
    size_t i = 0;
    if (mq->media_type()) {
      if      (mq->is_negated())    append_to_buffer("not ");
      else if (mq->is_restricted()) append_to_buffer("only ");
      mq->media_type()->perform(this);
    }
    else {
      (*mq)[i++]->perform(this);
    }
    for (size_t L = mq->length(); i < L; ++i) {
      append_to_buffer(" and ");
      (*mq)[i]->perform(this);
    }
  }

  void Inspect::operator()(Media_Query_Expression* mqe)
  {
    if (mqe->is_interpolated()) {
      mqe->feature()->perform(this);
    }
    else {
      append_to_buffer("(");
      mqe->feature()->perform(this);
      if (mqe->value()) {
        append_to_buffer(": ");
        mqe->value()->perform(this);
      }
      append_to_buffer(")");
    }
  }

  void Inspect::operator()(Null* n)
  {
    append_to_buffer("null");
  }

  // parameters and arguments
  void Inspect::operator()(Parameter* p)
  {
    append_to_buffer(p->name());
    if (p->default_value()) {
      append_to_buffer(": ");
      p->default_value()->perform(this);
    }
    else if (p->is_rest_parameter()) {
      append_to_buffer("...");
    }
  }

  void Inspect::operator()(Parameters* p)
  {
    append_to_buffer("(");
    if (!p->empty()) {
      (*p)[0]->perform(this);
      for (size_t i = 1, L = p->length(); i < L; ++i) {
        append_to_buffer(", ");
        (*p)[i]->perform(this);
      }
    }
    append_to_buffer(")");
  }

  void Inspect::operator()(Argument* a)
  {
    if (!a->name().empty()) {
      append_to_buffer(a->name());
      append_to_buffer(": ");
    }
    // Special case: argument nulls can be ignored
    if (a->value()->concrete_type() == Expression::NULL_VAL) {
      return;
    }
    a->value()->perform(this);
    if (a->is_rest_argument()) {
      append_to_buffer("...");
    }
  }

  void Inspect::operator()(Arguments* a)
  {
    append_to_buffer("(");
    if (!a->empty()) {
      (*a)[0]->perform(this);
      for (size_t i = 1, L = a->length(); i < L; ++i) {
        append_to_buffer(", ");
        (*a)[i]->perform(this);
      }
    }
    append_to_buffer(")");
  }

  // selectors
  void Inspect::operator()(Selector_Schema* s)
  {
    s->contents()->perform(this);
  }

  void Inspect::operator()(Selector_Reference* ref)
  {
    if (ref->selector()) ref->selector()->perform(this);
    else                 append_to_buffer("&");
  }

  void Inspect::operator()(Selector_Placeholder* s)
  {
    append_to_buffer(s->name());
  }

  void Inspect::operator()(Type_Selector* s)
  {
    if (ctx) ctx->source_map.add_mapping(s);
    append_to_buffer(s->name());
  }

  void Inspect::operator()(Selector_Qualifier* s)
  {
    if (ctx) ctx->source_map.add_mapping(s);
    append_to_buffer(s->name());
  }

  void Inspect::operator()(Attribute_Selector* s)
  {
    if (ctx) ctx->source_map.add_mapping(s);
    append_to_buffer("[");
    append_to_buffer(s->name());
    if (!s->matcher().empty()) {
      append_to_buffer(s->matcher());
      append_to_buffer(s->value());
    }
    append_to_buffer("]");
  }

  void Inspect::operator()(Pseudo_Selector* s)
  {
    if (ctx) ctx->source_map.add_mapping(s);
    append_to_buffer(s->name());
    if (s->expression()) {
      s->expression()->perform(this);
      append_to_buffer(")");
    }
  }

  void Inspect::operator()(Negated_Selector* s)
  {
    if (ctx) ctx->source_map.add_mapping(s);
    append_to_buffer(":not(");
    s->selector()->perform(this);
    append_to_buffer(")");
  }

  void Inspect::operator()(Compound_Selector* s)
  {
    for (size_t i = 0, L = s->length(); i < L; ++i) {
      (*s)[i]->perform(this);
    }
  }

  void Inspect::operator()(Complex_Selector* c)
  {
    Compound_Selector*           head = c->head();
    Complex_Selector*            tail = c->tail();
    Complex_Selector::Combinator comb = c->combinator();
    if (head && !head->is_empty_reference()) head->perform(this);
    if (head && !head->is_empty_reference() && tail) append_to_buffer(" ");
    switch (comb) {
      case Complex_Selector::ANCESTOR_OF:                                        break;
      case Complex_Selector::PARENT_OF:   append_to_buffer(">"); break;
      case Complex_Selector::PRECEDES:    append_to_buffer("~"); break;
      case Complex_Selector::ADJACENT_TO: append_to_buffer("+"); break;
    }
    if (tail && comb != Complex_Selector::ANCESTOR_OF) {
      append_to_buffer(" ");
    }
    if (tail) tail->perform(this);
  }

  void Inspect::operator()(Selector_List* g)
  {
    if (g->empty()) return;
    (*g)[0]->perform(this);
    for (size_t i = 1, L = g->length(); i < L; ++i) {
      append_to_buffer(", ");
      (*g)[i]->perform(this);
    }
  }

  inline void Inspect::fallback_impl(AST_Node* n)
  { }

  void Inspect::indent()
  { append_to_buffer(string(2*indentation, ' ')); }

  string unquote(const string& s)
  {
    if (s.empty()) return "";
    if (s.length() == 1) {
      if (s[0] == '"' || s[0] == '\'') return "";
    }
    char q;
    if      (*s.begin() == '"'  && *s.rbegin() == '"')  q = '"';
    else if (*s.begin() == '\'' && *s.rbegin() == '\'') q = '\'';
    else                                                return s;
    string t;
    t.reserve(s.length()-2);
    for (size_t i = 1, L = s.length()-1; i < L; ++i) {
      // if we see a quote, we need to remove the preceding backslash from t
      if (s[i] == q) t.erase(t.length()-1);
      t.push_back(s[i]);
    }
    return t;
  }

  string quote(const string& s, char q)
  {
    if (s.empty()) return string(2, q);
    if (!q || s[0] == '"' || s[0] == '\'') return s;
    string t;
    t.reserve(s.length()+2);
    t.push_back(q);
    for (size_t i = 0, L = s.length(); i < L; ++i) {
      if (s[i] == q) t.push_back('\\');
      t.push_back(s[i]);
    }
    t.push_back(q);
    return t;
  }

  void Inspect::append_to_buffer(const string& text)
  {
    buffer += text;
    if (ctx) ctx->source_map.update_column(text);
  }

}
