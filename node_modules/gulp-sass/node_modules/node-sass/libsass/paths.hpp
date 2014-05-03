#include <vector>
#include <iostream>
#include <string>
#include <sstream>

using namespace std;

template<typename T>
string vector_to_string(vector<T> v)
{
  stringstream buffer;
  buffer << "[";

  if (!v.empty())
  {  buffer << v[0]; }
  else
  { buffer << "]"; }

  if (v.size() == 1)
  { buffer << "]"; }
  else
  {
    for (size_t i = 1, S = v.size(); i < S; ++i) buffer << ", " << v[i];
    buffer << "]";
  }

  return buffer.str();
}

namespace Sass {

  using namespace std;

  template<typename T>
  vector<vector<T> > paths(vector<vector<T> > strata, size_t from_end = 0)
  {
    if (strata.empty()) {
      return vector<vector<T> >();
    }

    size_t end = strata.size() - from_end;
    if (end <= 1) {
      vector<vector<T> > starting_points;
      starting_points.reserve(strata[0].size());
      for (size_t i = 0, S = strata[0].size(); i < S; ++i) {
        vector<T> starting_point;
        starting_point.push_back(strata[0][i]);
        starting_points.push_back(starting_point);
      }
      return starting_points;
    }

    vector<vector<T> > up_to_here = paths(strata, from_end + 1);
    vector<T>          here       = strata[end-1];

    vector<vector<T> > branches;
    branches.reserve(up_to_here.size() * here.size());
    for (size_t i = 0, S1 = up_to_here.size(); i < S1; ++i) {
      for (size_t j = 0, S2 = here.size(); j < S2; ++j) {
        vector<T> branch = up_to_here[i];
        branch.push_back(here[j]);
        branches.push_back(branch);
      }
    }

    return branches;
  }

}
