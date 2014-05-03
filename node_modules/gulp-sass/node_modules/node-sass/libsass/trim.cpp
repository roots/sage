// #include "trim.hpp"
// #include "ast.hpp"

// namespace Sass {
//   using namespace std;

//   vector<vector<Complex_Selector*> > trim(vector<vector<Complex_Selector*> > seqses)
//   {
//     if (seqses.size() > 100) return vector<vector<Complex_Selector*> >();

//     vector<vector<Complex_Selector* > > result = vector<vector<Complex_Selector* > >(seqses);

//     for (size_t i = 0, S = seqses.size(); i < S; ++i)
//     {
//       vector<Complex_Selector*>& seqs1 = seqses[i];
//       vector<Complex_Selector*> sans_rejects;
//       for (size_t j = 0, T = seqs1.size(); j < T; ++j)
//       {
//         Complex_Selector* seq1 = seqs1[j];
//         int max_spec = 0;
//         set<Complex_Selector*> srcs = seq1->sources();
//         for (set<Complex_Selector*>::iterator i = srcs.begin(); i != srcs.end(); ++i)
//         {
//           int i_spec = i->specificity();
//           max_spec = (i_spec > max_spec ? i_spec : max_spec);
//         }

        
//         for (size_t k = 0, U = results.size(); k < U; ++k)
//         {

//         }
//         // something-something
//         sans_rejects.push_back(seq1);
//       }

//       result[i] = sans_rejects;
//     }

//     return result;
//   }

// }