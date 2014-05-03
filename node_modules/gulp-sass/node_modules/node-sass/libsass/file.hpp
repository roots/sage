#include <string>

namespace Sass {
  using namespace std;
  struct Context;
  namespace File {
    string base_name(string);
    string dir_name(string);
    string join_paths(string, string);
    bool is_absolute_path(const string& path);
    string make_absolute_path(const string& path, const string& cwd);
    string resolve_relative_path(const string& uri, const string& base, const string& cwd);
    char* resolve_and_load(string path, string& real_path);
    char* read_file(string path);
  }
}
