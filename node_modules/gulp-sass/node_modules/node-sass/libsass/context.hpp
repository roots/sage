#define SASS_CONTEXT

#include <string>
#include <vector>
#include <map>
#include "kwd_arg_macros.hpp"

#ifndef SASS_MEMORY_MANAGER
#include "memory_manager.hpp"
#endif

#ifndef SASS_ENVIRONMENT
#include "environment.hpp"
#endif

#ifndef SASS_SOURCE_MAP
#include "source_map.hpp"
#endif

#ifndef SASS_SUBSET_MAP
#include "subset_map.hpp"
#endif

namespace Sass {
  using namespace std;
  class AST_Node;
  class Block;
  class Expression;
  class Color;
  struct Backtrace;
  // typedef const char* Signature;
  // struct Context;
  // typedef Environment<AST_Node*> Env;
  // typedef Expression* (*Native_Function)(Env&, Context&, Signature, string, size_t);

  enum Output_Style { NESTED, EXPANDED, COMPACT, COMPRESSED, FORMATTED };

  struct Context {
    Memory_Manager<AST_Node> mem;

    const char* source_c_str;
    vector<const char*> sources; // c-strs containing Sass file contents
    vector<string> include_paths;
    vector<pair<string, const char*> > queue; // queue of files to be parsed
    map<string, Block*> style_sheets; // map of paths to ASTs
    SourceMap source_map;

    string       image_path; // for the image-url Sass function
    bool         source_comments;
    bool         source_maps;
    Output_Style output_style;
    string       source_map_file;

    map<string, Color*> names_to_colors;
    map<int, string>    colors_to_names;

    KWD_ARG_SET(Data) {
      KWD_ARG(Data, const char*,     source_c_str);
      KWD_ARG(Data, string,          entry_point);
      KWD_ARG(Data, string,          output_path);
      KWD_ARG(Data, string,          image_path);
      KWD_ARG(Data, const char*,     include_paths_c_str);
      KWD_ARG(Data, const char**,    include_paths_array);
      KWD_ARG(Data, vector<string>,  include_paths);
      KWD_ARG(Data, bool,            source_comments);
      KWD_ARG(Data, bool,            source_maps);
      KWD_ARG(Data, Output_Style,    output_style);
      KWD_ARG(Data, string,          source_map_file)
    };

    Context(Data);
    ~Context();
    void collect_include_paths(const char* paths_str);
    void collect_include_paths(const char* paths_array[]);
    void setup_color_map();
    string add_file(string);
    string add_file(string, string);
    char* compile_string();
    char* compile_file();
    char* generate_source_map();

    std::vector<string> get_included_files();

  private:
    string format_source_mapping_url(const string& file) const;
    string get_cwd();

    vector<string> included_files;
    string cwd;

    // void register_built_in_functions(Env* env);
    // void register_function(Signature sig, Native_Function f, Env* env);
    // void register_function(Signature sig, Native_Function f, size_t arity, Env* env);
    // void register_overload_stub(string name, Env* env);

  public:
    multimap<Compound_Selector, Complex_Selector*> extensions;
    Subset_Map<string, pair<Complex_Selector*, Compound_Selector*> > subset_map;
  };

}
