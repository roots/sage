#include "extend.hpp"
#include "context.hpp"
#include "contextualize.hpp"
#include "to_string.hpp"
#include "backtrace.hpp"
#include "paths.hpp"
#include "parser.hpp"
#include <iostream>

namespace Sass {

  Extend::Extend(Context& ctx, multimap<Compound_Selector, Complex_Selector*>& extensions, Subset_Map<string, pair<Complex_Selector*, Compound_Selector*> >& ssm, Backtrace* bt)
  : ctx(ctx), extensions(extensions), subset_map(ssm), backtrace(bt)
  { }

  void Extend::operator()(Block* b)
  {
    for (size_t i = 0, L = b->length(); i < L; ++i) {
      (*b)[i]->perform(this);
    }
  }

  void Extend::operator()(Ruleset* r)
  {
    // To_String to_string;
    // ng = new (ctx.mem) Selector_List(sg->path(), sg->position(), sg->length());
    // // for each selector in the group
    // for (size_t i = 0, L = sg->length(); i < L; ++i) {
    //   Complex_Selector* sel = (*sg)[i];
    //   *ng << sel;
    //   // if it's supposed to be extended
    //   Compound_Selector* sel_base = sel->base();
    //   if (sel_base && extensions.count(*sel_base)) {
    //     // extend it wrt each of its extenders
    //     for (multimap<Compound_Selector, Complex_Selector*>::iterator extender = extensions.lower_bound(*sel_base), E = extensions.upper_bound(*sel_base);
    //          extender != E;
    //          ++extender) {
    //       *ng += generate_extension(sel, extender->second);
    //       extended = true;
    //     }
    //   }
    // }
    // if (extended) r->selector(ng);
    To_String to_string;
    Selector_List* sg = static_cast<Selector_List*>(r->selector());
    Selector_List* all_subbed = new (ctx.mem) Selector_List(r->selector()->path(), r->selector()->position());
    for (size_t i = 0, L = sg->length(); i < L; ++i) {
      Complex_Selector* cplx = (*sg)[i];
      bool extended = true;
      Selector_List* ng = 0;
      while (cplx) {
        Selector_Placeholder* sp = cplx->find_placeholder();
        if (!sp) break;
        Compound_Selector* placeholder = new (ctx.mem) Compound_Selector(cplx->path(), cplx->position(), 1);
        *placeholder << sp;
        // if the current placeholder can be subbed
        if (extensions.count(*placeholder)) {
          ng = new (ctx.mem) Selector_List(sg->path(), sg->position());
          // perform each substitution and accumulate
          for (multimap<Compound_Selector, Complex_Selector*>::iterator extender = extensions.lower_bound(*placeholder), E = extensions.upper_bound(*placeholder);
               extender != E;
               ++extender) {
            Contextualize do_sub(ctx, 0, 0, backtrace, placeholder, extender->second);
            Complex_Selector* subbed = static_cast<Complex_Selector*>(cplx->perform(&do_sub));
            *ng << subbed;
            // cplx = subbed;
          }
        }
        else extended = false;
        // if at any point we fail to sub a placeholder, then break and skip this entire complex selector
        // else {
          cplx = 0;
        // }
      }
      // if we make it through the loop and `extended` is still true, then
      // we've subbed all placeholders in the current complex selector -- add
      // it to the result
      if (extended && ng) *all_subbed += ng;
    }


    if (all_subbed->length()) {
      // re-parse in order to restructure expanded placeholder nodes correctly
      r->selector(
        Parser::from_c_str(
          (all_subbed->perform(&to_string) + ";").c_str(),
          ctx,
          all_subbed->path(),
          all_subbed->position()
        ).parse_selector_group()
      );
    }

    // let's try the new stuff here; eventually it should replace the preceding
    set<Compound_Selector> seen;
    // Selector_List* new_list = new (ctx.mem) Selector_List(sg->path(), sg->position());
    bool extended = false;
    sg = static_cast<Selector_List*>(r->selector());
    Selector_List* ng = new (ctx.mem) Selector_List(sg->path(), sg->position(), sg->length());
    // for each complex selector in the list
    for (size_t i = 0, L = sg->length(); i < L; ++i)
    {
      // get rid of the useless backref that's at the front of the selector
      (*sg)[i] = (*sg)[i]->tail();
      if (!(*sg)[i]->has_placeholder()) *ng << (*sg)[i];
      // /* *new_list += */ extend_complex((*sg)[i], seen);
      // cerr << "checking [ " << (*sg)[i]->perform(&to_string) << " ]" << endl;
      Selector_List* extended_sels = extend_complex((*sg)[i], seen);
      // cerr << "extended by [ " << extended_sels->perform(&to_string) << " ]" << endl;
      if (extended_sels->length() > 0)
      {
        // cerr << "EXTENDED SELS: " << extended_sels->perform(&to_string) << endl;
        extended = true;
        for (size_t j = 0, M = extended_sels->length(); j < M; ++j)
        {
          // cerr << "GENERATING EXTENSION FOR " << (*sg)[i]->perform(&to_string) << " AND " << (*extended_sels)[j]->perform(&to_string) << endl;
          // cerr << "length of extender [ " << (*extended_sels)[j]->perform(&to_string) << " ] is " << (*extended_sels)[j]->length() << endl;
          // cerr << "extender's tail is [ " << (*extended_sels)[j]->tail()->perform(&to_string) << " ]" << endl;
          Selector_List* fully_extended = generate_extension((*sg)[i], (*extended_sels)[j]->tail()); // TODO: figure out why the extenders each have an extra node at the beginning
          // cerr << "combining extensions into [ " << fully_extended->perform(&to_string) << " ]" << endl;
          *ng += fully_extended;
        }
      }
    }

    // if (extended) cerr << "FINAL SELECTOR: " << ng->perform(&to_string) << endl;
    if (extended) r->selector(ng);

    // If there are still placeholders after the preceding, filter them out.
    if (r->selector()->has_placeholder())
    {
      Selector_List* current = static_cast<Selector_List*>(r->selector());
      Selector_List* final = new (ctx.mem) Selector_List(sg->path(), sg->position());
      for (size_t i = 0, L = current->length(); i < L; ++i)
      {
        if (!(*current)[i]->has_placeholder()) *final << (*current)[i];
      }
      r->selector(final);
    }
    r->block()->perform(this);
  }

  void Extend::operator()(Media_Block* m)
  {
    m->block()->perform(this);
  }

  void Extend::operator()(At_Rule* a)
  {
    if (a->block()) a->block()->perform(this);
  }

  Selector_List* Extend::generate_extension(Complex_Selector* extendee, Complex_Selector* extender)
  {
    To_String to_string;
    Selector_List* new_group = new (ctx.mem) Selector_List(extendee->path(), extendee->position());
    if (extendee->perform(&to_string) == extender->perform(&to_string)) return new_group;
    Complex_Selector* extendee_context = extendee->context(ctx);
    Complex_Selector* extender_context = extender->context(ctx);
    if (extendee_context && extender_context) {
      // cerr << "extender and extendee have a context" << endl;
      // cerr << extender_context->length() << endl;
      Complex_Selector* base = new (ctx.mem) Complex_Selector(new_group->path(), new_group->position(), Complex_Selector::ANCESTOR_OF, extender->base(), 0);
      extendee_context->innermost()->tail(extender);
      *new_group << extendee_context;
      // make another one so we don't erroneously share tails
      extendee_context = extendee->context(ctx);
      extendee_context->innermost()->tail(base);
      extender_context->innermost()->tail(extendee_context);
      *new_group << extender_context;
    }
    else if (extendee_context) {
      // cerr << "extendee has a context" << endl;
      extendee_context->innermost()->tail(extender);
      *new_group << extendee_context;
    }
    else {
      // cerr << "extender has a context" << endl;
      *new_group << extender;
    }
    return new_group;
  }

  Selector_List* Extend::extend_complex(Complex_Selector* sel, set<Compound_Selector>& seen)
  {
    To_String to_string;
    // cerr << "EXTENDING COMPLEX: " << sel->perform(&to_string) << endl;
    // vector<Selector_List*> choices; // 
    Selector_List* extended = new (ctx.mem) Selector_List(sel->path(), sel->position());

    Compound_Selector* h = sel->head();
    Complex_Selector* t = sel->tail();
    if (h && !h->is_empty_reference())
    {
      // Selector_List* extended = extend_compound(h, seen);
      *extended += extend_compound(h, seen);
      // bool found = false;
      // for (size_t i = 0, L = extended->length(); i < L; ++i)
      // {
      //   if ((*extended)[i]->is_superselector_of(h))
      //   { found = true; break; }
      // }
      // if (!found)
      // {
      //   *extended << new (ctx.mem) Complex_Selector(sel->path(), sel->position(), Complex_Selector::ANCESTOR_OF, h, 0);
      // }
      // choices.push_back(extended);
    }
    while(t)
    {
      h = t->head();
      t = t->tail();
      if (h && !h->is_empty_reference())
      {
        // Selector_List* extended = extend_compound(h, seen);
        *extended += extend_compound(h, seen);
        // bool found = false;
        // for (size_t i = 0, L = extended->length(); i < L; ++i)
        // {
        //   if ((*extended)[i]->is_superselector_of(h))
        //   { found = true; break; }
        // }
        // if (!found)
        // {
        //   *extended << new (ctx.mem) Complex_Selector(sel->path(), sel->position(), Complex_Selector::ANCESTOR_OF, h, 0);
        // }
        // choices.push_back(extended);
      }
    }
    // cerr << "EXTENSIONS: " << extended->perform(&to_string) << endl;
    return extended;
    // cerr << "CHOICES:" << endl;
    // for (size_t i = 0, L = choices.size(); i < L; ++i)
    // {
    //   cerr << choices[i]->perform(&to_string) << endl;
    // }

    // vector<vector<Complex_Selector*> > cs;
    // for (size_t i = 0, S = choices.size(); i < S; ++i)
    // {
    //   cs.push_back(choices[i]->elements());
    // }
    // vector<vector<Complex_Selector*> > ps = paths(cs);
    // cerr << "PATHS:" << endl;
    // for (size_t i = 0, S = ps.size(); i < S; ++i)
    // {
    //   for (size_t j = 0, T = ps[i].size(); j < T; ++j)
    //   {
    //     cerr << ps[i][j]->perform(&to_string) << ", ";
    //   }
    //   cerr << endl;
    // }
    // vector<Selector_List*> new_choices;
    // for (size_t i = 0, S = ps.size(); i < S; ++i)
    // {
    //   Selector_List* new_list = new (ctx.mem) Selector_List(sel->path(), sel->position());
    //   for (size_t j = 0, T = ps[i].size(); j < T; ++j)
    //   {
    //     *new_list << ps[i][j];
    //   }
    //   new_choices.push_back(new_list);
    // }
    // return new_choices;
  }

  Selector_List* Extend::extend_compound(Compound_Selector* sel, set<Compound_Selector>& seen)
  {
    To_String to_string;
    // cerr << "EXTEND_COMPOUND: " << sel->perform(&to_string) << endl;
    Selector_List* results = new (ctx.mem) Selector_List(sel->path(), sel->position());

    // TODO: Do we need to group the results by extender?
    vector<pair<Complex_Selector*, Compound_Selector*> > entries = subset_map.get_v(sel->to_str_vec());

    for (size_t i = 0, S = entries.size(); i < S; ++i)
    {
      if (seen.count(*entries[i].second)) continue;
      // cerr << "COMPOUND: " << sel->perform(&to_string) << " KEYS TO " << entries[i].first->perform(&to_string) << " AND " << entries[i].second->perform(&to_string) << endl;
      Compound_Selector* diff = sel->minus(entries[i].second, ctx);
      Compound_Selector* last = entries[i].first->base();
      if (!last) last = new (ctx.mem) Compound_Selector(sel->path(), sel->position());
      // cerr << sel->perform(&to_string) << " - " << entries[i].second->perform(&to_string) << " = " << diff->perform(&to_string) << endl;
      // cerr << "LAST: " << last->perform(&to_string) << endl;
      Compound_Selector* unif;
      if (last->length() == 0) unif = diff;
      else if (diff->length() == 0) unif = last;
      else unif = last->unify_with(diff, ctx);
      // if (unif) cerr << "UNIFIED: " << unif->perform(&to_string) << endl;
      if (!unif || unif->length() == 0) continue;
      Complex_Selector* cplx = entries[i].first->clone(ctx);
      // cerr << "cplx: " << cplx->perform(&to_string) << endl;
      Complex_Selector* new_innermost = new (ctx.mem) Complex_Selector(sel->path(), sel->position(), Complex_Selector::ANCESTOR_OF, unif, 0);
      // cerr << "new_innermost: " << new_innermost->perform(&to_string) << endl;
      cplx->set_innermost(new_innermost, cplx->clear_innermost());
      // cerr << "new cplx: " << cplx->perform(&to_string) << endl;
      *results << cplx;
      set<Compound_Selector> seen2 = seen;
      seen2.insert(*entries[i].second);
      Selector_List* ex2 = extend_complex(cplx, seen2);
      *results += ex2;
      // cerr << "RECURSIVELY CALLING EXTEND_COMPLEX ON " << cplx->perform(&to_string) << endl;
      // vector<Selector_List*> ex2 = extend_complex(cplx, seen2);
      // for (size_t j = 0, T = ex2.size(); j < T; ++j)
      // {
      //   *results += ex2[i];
      // }
    }

    // cerr << "RESULTS: " << results->perform(&to_string) << endl;
    return results;
  }

}