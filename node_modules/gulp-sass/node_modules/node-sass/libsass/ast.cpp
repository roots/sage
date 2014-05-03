#include "ast.hpp"
#include "context.hpp"
#include "to_string.hpp"
#include <set>
#include <algorithm>
#include <iostream>

namespace Sass {
  using namespace std;

  bool Compound_Selector::operator<(const Compound_Selector& rhs) const
  {
    To_String to_string;
    // ugly
    return const_cast<Compound_Selector*>(this)->perform(&to_string) <
           const_cast<Compound_Selector&>(rhs).perform(&to_string);
  }

  bool Complex_Selector::operator<(const Complex_Selector& rhs) const
  {
    To_String to_string;
    return const_cast<Complex_Selector*>(this)->perform(&to_string) <
           const_cast<Complex_Selector&>(rhs).perform(&to_string);
  }

  Compound_Selector* Compound_Selector::unify_with(Compound_Selector* rhs, Context& ctx)
  {
    Compound_Selector* unified = rhs;
    for (size_t i = 0, L = length(); i < L; ++i)
    {
      if (!unified) break;
      else          unified = (*this)[i]->unify_with(unified, ctx);
    }
    return unified;
  }

  Compound_Selector* Simple_Selector::unify_with(Compound_Selector* rhs, Context& ctx)
  {
    To_String to_string;
    for (size_t i = 0, L = rhs->length(); i < L; ++i)
    { if (perform(&to_string) == (*rhs)[i]->perform(&to_string)) return rhs; }

    // check for pseudo elements because they need to come last
    size_t i, L;
    bool found = false;
    if (typeid(*this) == typeid(Pseudo_Selector) || typeid(*this) == typeid(Negated_Selector))
    {
      for (i = 0, L = rhs->length(); i < L; ++i)
      {
        if ((typeid(*(*rhs)[i]) == typeid(Pseudo_Selector) || typeid(*(*rhs)[i]) == typeid(Negated_Selector)) && (*rhs)[L-1]->is_pseudo_element())
        { found = true; break; }
      }  
    }
    else
    {
      for (i = 0, L = rhs->length(); i < L; ++i)
      {
        if (typeid(*(*rhs)[i]) == typeid(Pseudo_Selector) || typeid(*(*rhs)[i]) == typeid(Negated_Selector))
        { found = true; break; }
      }
    }
    if (!found)
    {
      Compound_Selector* cpy = new (ctx.mem) Compound_Selector(*rhs);
      (*cpy) << this;
      return cpy;
    }
    Compound_Selector* cpy = new (ctx.mem) Compound_Selector(rhs->path(), rhs->position());
    for (size_t j = 0; j < i; ++j)
    { (*cpy) << (*rhs)[j]; }
    (*cpy) << this;
    for (size_t j = i; j < L; ++j)
    { (*cpy) << (*rhs)[j]; }
    return cpy;
  }

  Compound_Selector* Type_Selector::unify_with(Compound_Selector* rhs, Context& ctx)
  {
    // TODO: handle namespaces

    // if this is a universal selector, just return the rhs
    if (name() == "*")
    { return new (ctx.mem) Compound_Selector(*rhs); }

    Simple_Selector* rhs_0 = (*rhs)[0];
    // otherwise, this is a tag name
    if (typeid(*rhs_0) == typeid(Type_Selector))
    {
      // if rhs is universal, just return this tagname + rhs's qualifiers
      if (static_cast<Type_Selector*>(rhs_0)->name() == "*")
      {
        Compound_Selector* cpy = new (ctx.mem) Compound_Selector(rhs->path(), rhs->position());
        (*cpy) << this;
        for (size_t i = 1, L = rhs->length(); i < L; ++i)
        { (*cpy) << (*rhs)[i]; }
        return cpy;
      }
      // if rhs is another tag name and it matches this, return rhs
      else if (static_cast<Type_Selector*>(rhs_0)->name() == name())
      { return new (ctx.mem) Compound_Selector(*rhs); }
      // else the tag names don't match; return nil
      else
      { return 0; }
    }
    // else it's a tag name and a bunch of qualifiers -- just append them
    Compound_Selector* cpy = new (ctx.mem) Compound_Selector(rhs->path(), rhs->position());
    (*cpy) << this;
    (*cpy) += rhs;
    return cpy;
  }

  Compound_Selector* Selector_Qualifier::unify_with(Compound_Selector* rhs, Context& ctx)
  {
    if (name()[0] == '#')
    {
      for (size_t i = 0, L = rhs->length(); i < L; ++i)
      {
        Simple_Selector* rhs_i = (*rhs)[i];
        if (typeid(*rhs_i) == typeid(Selector_Qualifier) &&
            static_cast<Selector_Qualifier*>(rhs_i)->name()[0] == '#' &&
            static_cast<Selector_Qualifier*>(rhs_i)->name() != name())
          return 0;
      }
    }
    return Simple_Selector::unify_with(rhs, ctx);
  }

  Compound_Selector* Pseudo_Selector::unify_with(Compound_Selector* rhs, Context& ctx)
  {
    if (is_pseudo_element())
    {
      for (size_t i = 0, L = rhs->length(); i < L; ++i)
      {
        Simple_Selector* rhs_i = (*rhs)[i];
        if (typeid(*rhs_i) == typeid(Pseudo_Selector) &&
            static_cast<Pseudo_Selector*>(rhs_i)->is_pseudo_element() &&
            static_cast<Pseudo_Selector*>(rhs_i)->name() != name())
        { return 0; }
      }
    }
    return Simple_Selector::unify_with(rhs, ctx);
  }

  bool Compound_Selector::is_superselector_of(Compound_Selector* rhs)
  {
    To_String to_string;

    Simple_Selector* lbase = base();
    Simple_Selector* rbase = rhs->base();

    set<string> lset, rset;

    // TODO: check pseudo-elements once we store semantic info for them
    if (!lbase) // no lbase; just see if the left-hand qualifiers are a subset of the right-hand selector
    {
      for (size_t i = 0, L = length(); i < L; ++i)
      { lset.insert((*this)[i]->perform(&to_string)); }
      for (size_t i = 0, L = rhs->length(); i < L; ++i)
      { rset.insert((*rhs)[i]->perform(&to_string)); }
      return includes(rset.begin(), rset.end(), lset.begin(), lset.end());
    }
    else { // there's an lbase
      for (size_t i = 1, L = length(); i < L; ++i)
      { lset.insert((*this)[i]->perform(&to_string)); }
      if (rbase)
      {
        if (lbase->perform(&to_string) != rbase->perform(&to_string)) // if there's an rbase, make sure they match
        { return false; }
        else // the bases do match, so compare qualifiers
        {
          for (size_t i = 1, L = rhs->length(); i < L; ++i)
          { rset.insert((*rhs)[i]->perform(&to_string)); }
          return includes(rset.begin(), rset.end(), lset.begin(), lset.end());
        }
      }
    }
    // catch-all
    return false;
  }

  bool Complex_Selector::is_superselector_of(Compound_Selector* rhs)
  {
    if (length() != 1)
    { return false; }
    return base()->is_superselector_of(rhs);
  }

  bool Complex_Selector::is_superselector_of(Complex_Selector* rhs)
  {
    Complex_Selector* lhs = this;
    To_String to_string;
    // check for selectors with leading or trailing combinators
    if (!lhs->head() || !rhs->head())
    { return false; }
    Complex_Selector* l_innermost = lhs->innermost();
    if (l_innermost->combinator() != Complex_Selector::ANCESTOR_OF && !l_innermost->tail())
    { return false; }
    Complex_Selector* r_innermost = rhs->innermost();
    if (r_innermost->combinator() != Complex_Selector::ANCESTOR_OF && !r_innermost->tail())
    { return false; }
    // more complex (i.e., longer) selectors are always more specific
    size_t l_len = lhs->length(), r_len = rhs->length();
    if (l_len > r_len)
    { return false; }

    if (l_len == 1)
    { return lhs->head()->is_superselector_of(rhs->base()); }

    bool found = false;
    Complex_Selector* marker = rhs;
    for (size_t i = 0, L = rhs->length(); i < L; ++i) {
      if (i == L-1)
      { return false; }
      if (lhs->head()->is_superselector_of(marker->head()))
      { found = true; break; }
      marker = marker->tail();
    }
    if (!found)
    { return false; }

    /* 
      Hmm, I hope I have the logic right:

      if lhs has a combinator:
        if !(marker has a combinator) return false
        if !(lhs.combinator == '~' ? marker.combinator != '>' : lhs.combinator == marker.combinator) return false
        return lhs.tail-without-innermost.is_superselector_of(marker.tail-without-innermost)
      else if marker has a combinator:
        if !(marker.combinator == ">") return false
        return lhs.tail.is_superselector_of(marker.tail)
      else
        return lhs.tail.is_superselector_of(marker.tail)
    */
    if (lhs->combinator() != Complex_Selector::ANCESTOR_OF)
    {
      if (marker->combinator() == Complex_Selector::ANCESTOR_OF)
      { return false; }
      if (!(lhs->combinator() == Complex_Selector::PRECEDES ? marker->combinator() != Complex_Selector::PARENT_OF : lhs->combinator() == marker->combinator()))
      { return false; }
      return lhs->tail()->is_superselector_of(marker->tail());
    }
    else if (marker->combinator() != Complex_Selector::ANCESTOR_OF)
    {
      if (marker->combinator() != Complex_Selector::PARENT_OF)
      { return false; }
      return lhs->tail()->is_superselector_of(marker->tail());
    }
    else
    {
      return lhs->tail()->is_superselector_of(marker->tail());
    }
    // catch-all
    return false;
  }

  size_t Complex_Selector::length()
  {
    // TODO: make this iterative
    if (!tail()) return 1;
    return 1 + tail()->length();
  }

  Compound_Selector* Complex_Selector::base()
  {
    if (!tail()) return head();
    else return tail()->base();
  }

  Complex_Selector* Complex_Selector::context(Context& ctx)
  {
    if (!tail()) return 0;
    if (!head()) return tail()->context(ctx);
    return new (ctx.mem) Complex_Selector(path(), position(), combinator(), head(), tail()->context(ctx));
  }

  Complex_Selector* Complex_Selector::innermost()
  {
    if (!tail()) return this;
    else         return tail()->innermost();
  }

  Complex_Selector::Combinator Complex_Selector::clear_innermost()
  {
    Combinator c;
    if (!tail() || tail()->length() == 1)
    { c = combinator(); combinator(ANCESTOR_OF); tail(0); }
    else
    { c = tail()->clear_innermost(); }
    return c;
  }

  void Complex_Selector::set_innermost(Complex_Selector* val, Combinator c)
  {
    if (!tail())
    { tail(val); combinator(c); }
    else
    { tail()->set_innermost(val, c); }
  }

  Complex_Selector* Complex_Selector::clone(Context& ctx)
  {
    Complex_Selector* cpy = new (ctx.mem) Complex_Selector(*this);
    if (tail()) cpy->tail(tail()->clone(ctx));
    return cpy;
  }

  Selector_Placeholder* Selector::find_placeholder()
  {
    return 0;
  }

  Selector_Placeholder* Selector_List::find_placeholder()
  {
    if (has_placeholder()) {
      for (size_t i = 0, L = length(); i < L; ++i) {
        if ((*this)[i]->has_placeholder()) return (*this)[i]->find_placeholder();
      }
    }
    return 0;
  }

  Selector_Placeholder* Complex_Selector::find_placeholder()
  {
    if (has_placeholder()) {
      if (head() && head()->has_placeholder()) return head()->find_placeholder();
      else if (tail() && tail()->has_placeholder()) return tail()->find_placeholder();
    }
    return 0;
  }

  Selector_Placeholder* Compound_Selector::find_placeholder()
  {
    if (has_placeholder()) {
      for (size_t i = 0, L = length(); i < L; ++i) {
        if ((*this)[i]->has_placeholder()) return (*this)[i]->find_placeholder();
      }
      // return this;
    }
    return 0;
  }

  Selector_Placeholder* Selector_Placeholder::find_placeholder()
  {
    return this;
  }

  vector<string> Compound_Selector::to_str_vec()
  {
    To_String to_string;
    vector<string> result;
    result.reserve(length());
    for (size_t i = 0, L = length(); i < L; ++i)
    { result.push_back((*this)[i]->perform(&to_string)); }
    return result;
  }

  Compound_Selector* Compound_Selector::minus(Compound_Selector* rhs, Context& ctx)
  {
    To_String to_string;
    Compound_Selector* result = new (ctx.mem) Compound_Selector(path(), position());

    // not very efficient because it needs to preserve order
    for (size_t i = 0, L = length(); i < L; ++i)
    {
      bool found = false;
      for (size_t j = 0, M = rhs->length(); j < M; ++j)
      {
        if ((*this)[i]->perform(&to_string) == (*rhs)[j]->perform(&to_string))
        {
          found = true;
          break;
        }
      }
      if (!found) (*result) << (*this)[i];
    }

    return result;
  }

  vector<Compound_Selector*> Complex_Selector::to_vector()
  {
    vector<Compound_Selector*> result;
    Compound_Selector* h = head();
    Complex_Selector* t = tail();
    if (h) result.push_back(h);
    while (t)
    {
      h = t->head();
      t = t->tail();
      if (h) result.push_back(h);
    }
    return result;
  }

}

