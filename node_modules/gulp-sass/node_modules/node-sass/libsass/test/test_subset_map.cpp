#include <string>
#include <iostream>
#include "../subset_map.hpp"

using namespace std;
using namespace Sass;

Subset_Map<int, string> ssm;

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


int main()
{
  vector<int> s1;
  s1.push_back(1);
  s1.push_back(2);

  vector<int> s2;
  s2.push_back(2);
  s2.push_back(3);

  vector<int> s3;
  s3.push_back(3);
  s3.push_back(4);

  ssm.put(s1, "value1");
  ssm.put(s2, "value2");
  ssm.put(s3, "value3");

  vector<int> s4;
  s4.push_back(1);
  s4.push_back(2);
  s4.push_back(3);

  vector<pair<string, vector<int> > > fetched(ssm.get_kv(s4));

  cout << "PRINTING RESULTS:" << endl;
  for (size_t i = 0, S = fetched.size(); i < S; ++i) {
    cout << fetched[i].first << endl;
  }

  Subset_Map<int, string> ssm2;
  ssm2.put(s1, "foo");
  ssm2.put(s2, "bar");
  ssm2.put(s4, "hux");

  vector<pair<string, vector<int> > > fetched2(ssm2.get_kv(s4));

  cout << endl << "PRINTING RESULTS:" << endl;
  for (size_t i = 0, S = fetched2.size(); i < S; ++i) {
    cout << fetched2[i].first << endl;
  }

  cout << "TRYING ON A SELECTOR-LIKE OBJECT" << endl;

  Subset_Map<string, string> sel_ssm;
  vector<string> target;
  target.push_back("desk");
  target.push_back(".wood");

  vector<string> actual;
  actual.push_back("desk");
  actual.push_back(".wood");
  actual.push_back(".mine");

  sel_ssm.put(target, "has-aquarium");
  vector<pair<string, vector<string> > > fetched3(sel_ssm.get_kv(actual));
  cout << "RESULTS:" << endl;
  for (size_t i = 0, S = fetched3.size(); i < S; ++i) {
    cout << fetched3[i].first << endl;
  }

  return 0;
}
