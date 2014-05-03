#define SASS_SUBSET_MAP

#include <vector>
#include <map>
#include <set>
#include <algorithm>
#include <iterator>
#include <iostream>
#include <sstream>

// using namespace std;

// template<typename T>
// string vector_to_string(vector<T> v)
// {
//   stringstream buffer;
//   buffer << "[";

//   if (!v.empty())
//   {  buffer << v[0]; }
//   else
//   { buffer << "]"; }

//   if (v.size() == 1)
//   { buffer << "]"; }
//   else
//   {
//     for (size_t i = 1, S = v.size(); i < S; ++i) buffer << ", " << v[i];
//     buffer << "]";
//   }

//   return buffer.str();
// }

// template<typename T>
// string set_to_string(set<T> v)
// {
//   stringstream buffer;
//   buffer << "[";
//   typename set<T>::iterator i = v.begin();
//   if (!v.empty())
//   {  buffer << *i; }
//   else
//   { buffer << "]"; }

//   if (v.size() == 1)
//   { buffer << "]"; }
//   else
//   {
//     for (++i; i != v.end(); ++i) buffer << ", " << *i;
//     buffer << "]";
//   }

//   return buffer.str();
// }

namespace Sass {
  using namespace std;

  template<typename F, typename S, typename T>
  struct triple {
    F first;
    S second;
    T third;

    triple(const F& f, const S& s, const T& t) : first(f), second(s), third(t) { }
  };

  template<typename F, typename S, typename T>
  triple<F, S, T> make_triple(const F& f, const S& s, const T& t)
  { return triple<F, S, T>(f, s, t); }

  template<typename K, typename V>
  class Subset_Map {
  private:
    vector<V> values_;
    map<K, vector<triple<vector<K>, set<K>, size_t> > > hash_;
  public:
    void put(const vector<K>& s, const V& value);
    vector<pair<V, vector<K> > > get_kv(const vector<K>& s);
    vector<V> get_v(const vector<K>& s);
    bool empty() { return values_.empty(); }
  };

  template<typename K, typename V>
  void Subset_Map<K, V>::put(const vector<K>& s, const V& value)
  {
    if (s.empty()) throw "internal error: subset map keys may not be empty";
    size_t index = values_.size();
    values_.push_back(value);
    set<K> ss;
    for (size_t i = 0, S = s.size(); i < S; ++i)
    { ss.insert(s[i]); }
    for (size_t i = 0, S = s.size(); i < S; ++i)
    {
      hash_[s[i]];
      hash_[s[i]].push_back(make_triple(s, ss, index));
    }
  }

  template<typename K, typename V>
  vector<pair<V, vector<K> > > Subset_Map<K, V>::get_kv(const vector<K>& s)
  {
    vector<K> sorted = s;
    sort(sorted.begin(), sorted.end());
    vector<pair<size_t, vector<K> > > indices;
    for (size_t i = 0, S = s.size(); i < S; ++i) {
      // cerr << "looking for " << s[i] << endl;
      if (!hash_.count(s[i])) {
        // cerr << "didn't find " << s[i] << endl;
        continue;
      }
      vector<triple<vector<K>, set<K>, size_t> > subsets = hash_[s[i]];
      // cerr << "length of subsets: " << subsets.size() << endl;
      for (size_t j = 0, T = subsets.size(); j < T; ++j) {
        if (!includes(sorted.begin(), sorted.end(), subsets[j].second.begin(), subsets[j].second.end())) {
          // cout << vector_to_string(s) << " doesn't include " << set_to_string(subsets[j].second) << endl;
          continue;
        }
        indices.push_back(make_pair(subsets[j].third, subsets[j].first));
        // cerr << "pushed " << subsets[j].third << " and " << vector_to_string(subsets[j].first) << " onto indices" << endl;
      }
    }
    sort(indices.begin(), indices.end());
    typename vector<pair<size_t, vector<K> > >::iterator indices_end = unique(indices.begin(), indices.end());
    indices.resize(distance(indices.begin(), indices_end));

    vector<pair<V, vector<K> > > results;
    for (size_t i = 0, S = indices.size(); i < S; ++i) {
      results.push_back(make_pair(values_[indices[i].first], indices[i].second));
    }
    return results;
  }

  template<typename K, typename V>
  vector<V> Subset_Map<K, V>::get_v(const vector<K>& s)
  {
    vector<pair<V, vector<K> > > kvs = get_kv(s);
    vector<V> results;
    for (size_t i = 0, S = kvs.size(); i < S; ++i) results.push_back(kvs[i].first);
    return results;
  }

}