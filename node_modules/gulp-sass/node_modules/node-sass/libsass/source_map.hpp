#define SASS_SOURCE_MAP

#include <vector>

#ifndef SASS_MAPPING
#include "mapping.hpp"
#endif

#ifndef SASS_AST
#include "ast.hpp"
#endif

#ifndef SASSS_BASE64VLQ
#include "base64vlq.hpp"
#endif



namespace Sass {
  using std::vector;

  struct Context;

  class SourceMap {

  public:
    vector<string> files;

    SourceMap(const string& file);

    void remove_line();
    void update_column(const string& str);
    void add_mapping(AST_Node* node);

    string generate_source_map();

  private:

    string serialize_mappings();

    vector<Mapping> mappings;
    Position current_position;
    string file;
    Base64VLQ base64vlq;
  };

}
