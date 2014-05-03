#define SASS_EXTEND

#include <vector>
#include <map>
#include <set>
#include <iostream>

#ifndef SASS_AST
#include "ast.hpp"
#endif

#ifndef SASS_OPERATION
#include "operation.hpp"
#endif

#ifndef SASS_SUBSET_MAP
#include "subset_map.hpp"
#endif

namespace Sass {
  using namespace std;

  struct Context;
  struct Backtrace;

  class Extend : public Operation_CRTP<void, Extend> {

    Context&          ctx;
    multimap<Compound_Selector, Complex_Selector*>& extensions;
    Subset_Map<string, pair<Complex_Selector*, Compound_Selector*> >& subset_map;

    Backtrace*        backtrace;

    void fallback_impl(AST_Node* n) { };

  public:
    Extend(Context&, multimap<Compound_Selector, Complex_Selector*>&, Subset_Map<string, pair<Complex_Selector*, Compound_Selector*> >&, Backtrace*);
    virtual ~Extend() { }

    using Operation<void>::operator();

    void operator()(Block*);
    void operator()(Ruleset*);
    void operator()(Media_Block*);
    void operator()(At_Rule*);

    Selector_List* generate_extension(Complex_Selector*, Complex_Selector*);
    Selector_List* extend_complex(Complex_Selector*, set<Compound_Selector>&);
    Selector_List* extend_compound(Compound_Selector*, set<Compound_Selector>&);

    template <typename U>
    void fallback(U x) { return fallback_impl(x); }
  };


}
