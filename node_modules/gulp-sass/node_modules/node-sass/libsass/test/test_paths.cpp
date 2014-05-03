#include <iostream>
#include "../paths.hpp"

using namespace std;
using namespace Sass;

template<typename T>
vector<T>& operator<<(vector<T>& v, const T& e)
{
  v.push_back(e);
  return v;
}

int main()
{
  vector<int> v1, v2, v3;
  v1 << 1 << 2;
  v2 << 3;
  v3 << 4 << 5 << 6;

  vector<vector<int> > ss;
  ss << v1 << v2 << v3;

  vector<vector<int> > ps = paths(ss);
  for (size_t i = 0, S = ps.size(); i < S; ++i) {
    cout << vector_to_string(ps[i]) << endl;
  }
  return 0;
}
