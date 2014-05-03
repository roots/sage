#ifdef _WIN32
#define S_ISDIR(mode) (((mode) & S_IFMT) == S_IFDIR)
#endif

#include <iostream>
#include <fstream>
#include <cctype>
#include <algorithm>
#include <sys/stat.h>
#include "file.hpp"
#include "context.hpp"

namespace Sass {
  namespace File {
    using namespace std;

    size_t find_last_folder_separator(const string& path, size_t limit = string::npos)
    {
      size_t pos = string::npos;
      size_t pos_p = path.find_last_of('/', limit);
      size_t pos_w = string::npos;
      #ifdef _WIN32
      pos_w = path.find_last_of('\\', limit);
      #endif
      if (pos_p != string::npos && pos_w != string::npos) {
        pos = max(pos_p, pos_w);
      }
      else if (pos_p != string::npos) {
        pos = pos_p;
      }
      else {
        pos = pos_w;
      }
      return pos;
    }

    string base_name(string path)
    {
      size_t pos = find_last_folder_separator(path);
      if (pos == string::npos) return path;
      else                     return path.substr(pos+1);
    }

    string dir_name(string path)
    {
      size_t pos = find_last_folder_separator(path);
      if (pos == string::npos) return "";
      else                     return path.substr(0, pos+1);
    }

    string join_paths(string l, string r)
    {
      if (l.empty()) return r;
      if (r.empty()) return l;
      if (is_absolute_path(r)) return r;

      if (l[l.length()-1] != '/') l += '/';

      while ((r.length() > 3) && ((r.substr(0, 3) == "../") || (r.substr(0, 3)) == "..\\")) {
        r = r.substr(3);
        size_t pos = find_last_folder_separator(l, l.length() - 2);
        l = l.substr(0, pos == string::npos ? pos : pos + 1);
      }

      return l + r;
    }

    bool is_absolute_path(const string& path)
    {
      if (path[0] == '/') return true;
      // TODO: UN-HACKIFY THIS
      #ifdef _WIN32
      if (path.length() >= 2 && isalpha(path[0]) && path[1] == ':') return true;
      #endif
      return false;
    }

    string make_absolute_path(const string& path, const string& cwd)
    {
      return (is_absolute_path(path) ? path : join_paths(cwd, path));
    }

    string resolve_relative_path(const string& uri, const string& base, const string& cwd)
    {
      string absolute_uri = make_absolute_path(uri, cwd);
      string absolute_base = make_absolute_path(base, cwd);

      string stripped_uri = "";
      string stripped_base = "";

      size_t index = 0;
      size_t minSize = min(absolute_uri.size(), absolute_base.size());
      for (size_t i = 0; i < minSize; ++i) {
        if (absolute_uri[i] != absolute_base[i]) break;
        if (absolute_uri[i] == '/') index = i + 1;
      }
      for (size_t i = index; i < absolute_uri.size(); ++i) {
        stripped_uri += absolute_uri[i];
      }
      for (size_t i = index; i < absolute_base.size(); ++i) {
        stripped_base += absolute_base[i];
      }
      size_t directories = 0;
      for (size_t i = 0; i < stripped_base.size(); ++i) {
        if (stripped_base[i] == '/') ++directories;
      }
      string result = "";
      for (size_t i = 0; i < directories; ++i) {
        result += "../";
      }
      result += stripped_uri;

      return result;
    }

    char* resolve_and_load(string path, string& real_path)
    {
      // Resolution order for ambiguous imports:
      // (1) filename as given
      // (2) underscore + given
      // (3) underscore + given + extension
      // (4) given + extension
      char* contents = 0;
      real_path = path;
      // if the file isn't found with the given filename ...
      if (!(contents = read_file(real_path))) {
        string dir(dir_name(path));
        string base(base_name(path));
        string _base("_" + base);
        real_path = dir + _base;
        // if the file isn't found with '_' + filename ...
        if (!(contents = read_file(real_path))) {
          string _base_scss(_base + ".scss");
          real_path = dir + _base_scss;
          // if the file isn't found with '_' + filename + ".scss" ...
          if (!(contents = read_file(real_path))) {
            string base_scss(base + ".scss");
            // try filename + ".scss" as the last resort
            real_path = dir + base_scss;
            contents = read_file(real_path);
          }
        }
      }
      return contents;
    }

    char* read_file(string path)
    {
      struct stat st;
      if (stat(path.c_str(), &st) == -1 || S_ISDIR(st.st_mode)) return 0;
      ifstream file(path.c_str(), ios::in | ios::binary | ios::ate);
      char* contents = 0;
      if (file.is_open()) {
        size_t size = file.tellg();
        contents = new char[size + 1]; // extra byte for the null char
        file.seekg(0, ios::beg);
        file.read(contents, size);
        contents[size] = '\0';
        file.close();
      }
      return contents;
    }

  }
}
