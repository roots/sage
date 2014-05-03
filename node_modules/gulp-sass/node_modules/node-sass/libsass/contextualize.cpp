#include "contextualize.hpp"
#include "ast.hpp"
#include "eval.hpp"
#include "backtrace.hpp"
#include "to_string.hpp"
#include "parser.hpp"

namespace Sass {

  Contextualize::Contextualize(Context& ctx, Eval* eval, Env* env, Backtrace* bt, Selector* placeholder, Selector* extender)
  : ctx(ctx), eval(eval), env(env), parent(0), backtrace(bt), placeholder(placeholder), extender(extender)
  { }

  Contextualize::~Contextualize() { }

  Selector* Contextualize::fallback_impl(AST_Node* n)
  { return parent; }

  Contextualize* Contextualize::with(Selector* s, Env* e, Backtrace* bt, Selector* p, Selector* ex)
  {
    parent = s;
    env = e;
    backtrace = bt;
    placeholder = p;
    extender = ex;
    return this;
  }

  Selector* Contextualize::operator()(Selector_Schema* s)
  {
    To_String to_string;
    string result_str(s->contents()->perform(eval->with(env, backtrace))->perform(&to_string));
    result_str += '{'; // the parser looks for a brace to end the selector
    Selector* result_sel = Parser::from_c_str(result_str.c_str(), ctx, s->path(), s->position()).parse_selector_group();
    return result_sel->perform(this);
  }

  Selector* Contextualize::operator()(Selector_List* s)
  {
    Selector_List* p = static_cast<Selector_List*>(parent);
    Selector_List* ss = 0;
    if (p) {
      ss = new (ctx.mem) Selector_List(s->path(), s->position(), p->length() * s->length());
      for (size_t i = 0, L = p->length(); i < L; ++i) {
        for (size_t j = 0, L = s->length(); j < L; ++j) {
          parent = (*p)[i];
          Complex_Selector* comb = static_cast<Complex_Selector*>((*s)[j]->perform(this));
          if (comb) *ss << comb;
        }
      }
    }
    else {
      ss = new (ctx.mem) Selector_List(s->path(), s->position(), s->length());
      for (size_t j = 0, L = s->length(); j < L; ++j) {
        Complex_Selector* comb = static_cast<Complex_Selector*>((*s)[j]->perform(this));
        if (comb) *ss << comb;
      }
    }
    return ss->length() ? ss : 0;
  }

  Selector* Contextualize::operator()(Complex_Selector* s)
  {
    To_String to_string;
    Complex_Selector* ss = new (ctx.mem) Complex_Selector(*s);
    Compound_Selector* new_head = 0;
    Complex_Selector* new_tail = 0;
    if (ss->head()) {
      new_head = static_cast<Compound_Selector*>(s->head()->perform(this));
      ss->head(new_head);
    }
    if (ss->tail()) {
      new_tail = static_cast<Complex_Selector*>(s->tail()->perform(this));
      ss->tail(new_tail);
    }
    if ((new_head && new_head->has_placeholder()) || (new_tail && new_tail->has_placeholder())) {
      ss->has_placeholder(true);
    }
    else {
      ss->has_placeholder(false);
    }
    if (!ss->head() && ss->combinator() == Complex_Selector::ANCESTOR_OF) {
      return ss->tail();
    }
    else {
      return ss;
    }
  }

  Selector* Contextualize::operator()(Compound_Selector* s)
  {
    To_String to_string;
    if (placeholder && extender && s->perform(&to_string) == placeholder->perform(&to_string)) {
      return extender;
    }
    Compound_Selector* ss = new (ctx.mem) Compound_Selector(s->path(), s->position(), s->length());
    for (size_t i = 0, L = s->length(); i < L; ++i) {
      Simple_Selector* simp = static_cast<Simple_Selector*>((*s)[i]->perform(this));
      if (simp) *ss << simp;
    }
    return ss->length() ? ss : 0;
  }

  Selector* Contextualize::operator()(Negated_Selector* s)
  {
    Selector* old_parent = parent;
    parent = 0;
    Negated_Selector* neg = new (ctx.mem) Negated_Selector(s->path(),
                                                           s->position(),
                                                           s->selector()->perform(this));
    parent = old_parent;
    return neg;
  }

  Selector* Contextualize::operator()(Pseudo_Selector* s)
  { return s; }

  Selector* Contextualize::operator()(Attribute_Selector* s)
  { return s; }

  Selector* Contextualize::operator()(Selector_Qualifier* s)
  { return s; }

  Selector* Contextualize::operator()(Type_Selector* s)
  { return s; }

  Selector* Contextualize::operator()(Selector_Placeholder* p)
  {
    To_String to_string;
    if (placeholder && extender && p->perform(&to_string) == placeholder->perform(&to_string)) {
      return extender;
    }
    else {
      return p;
    }
  }

  Selector* Contextualize::operator()(Selector_Reference* s)
  {
    if (!parent) return 0;
    Selector_Reference* ss = new (ctx.mem) Selector_Reference(*s);
    ss->selector(parent);
    return ss;
  }


}
