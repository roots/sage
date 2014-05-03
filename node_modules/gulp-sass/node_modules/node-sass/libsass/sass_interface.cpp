#ifdef _WIN32
#include <io.h>
#else
#include <unistd.h>
#endif

#include "sass_interface.h"
#include "context.hpp"

#ifndef SASS_ERROR_HANDLING
#include "error_handling.hpp"
#endif

#include <iostream>
#include <sstream>
#include <string>
#include <cstdlib>
#include <cstring>
#include <iostream>

extern "C" {
  using namespace std;

  sass_context* sass_new_context()
  { return (sass_context*) calloc(1, sizeof(sass_context)); }

  void free_string_array(char ** arr, int num) {
    if(!arr)
        return;

    for(int i = 0; i < num; i++) {
      free(arr[i]);
    }

    free(arr);
  }

  void sass_free_context(sass_context* ctx)
  {
    if (ctx->output_string) free(ctx->output_string);
    if (ctx->error_message) free(ctx->error_message);

    free_string_array(ctx->included_files, ctx->num_included_files);

    free(ctx);
  }

  sass_file_context* sass_new_file_context()
  { return (sass_file_context*) calloc(1, sizeof(sass_file_context)); }

  void sass_free_file_context(sass_file_context* ctx)
  {
    if (ctx->output_string)     free(ctx->output_string);
    if (ctx->source_map_string) free(ctx->source_map_string);
    if (ctx->error_message)     free(ctx->error_message);

    free_string_array(ctx->included_files, ctx->num_included_files);

    free(ctx);
  }

  sass_folder_context* sass_new_folder_context()
  { return (sass_folder_context*) calloc(1, sizeof(sass_folder_context)); }

  void sass_free_folder_context(sass_folder_context* ctx)
  {
    free_string_array(ctx->included_files, ctx->num_included_files);
    free(ctx);
  }

  void copy_strings(const std::vector<std::string>& strings, char*** array, int* n) {
    int num = strings.size();
    char** arr = (char**) malloc(sizeof(char*)* num);

    for(int i = 0; i < num; i++) {
      arr[i] = (char*) malloc(sizeof(char) * strings[i].size() + 1);
      std::copy(strings[i].begin(), strings[i].end(), arr[i]);
      arr[i][strings[i].size()] = '\0';
    }

    *array = arr;
    *n = num;
  }

  int sass_compile(sass_context* c_ctx)
  {
    using namespace Sass;
    try {
      Context cpp_ctx(
        Context::Data().source_c_str(c_ctx->source_string)
                       .entry_point("")
                       .output_style((Output_Style) c_ctx->options.output_style)
                       .source_comments(c_ctx->options.source_comments == SASS_SOURCE_COMMENTS_DEFAULT)
                       .source_maps(false) // Only supported for files.
                       .image_path(c_ctx->options.image_path)
                       .include_paths_c_str(c_ctx->options.include_paths)
                       .include_paths_array(0)
                       .include_paths(vector<string>())
      );
      c_ctx->output_string = cpp_ctx.compile_string();
      c_ctx->error_message = 0;
      c_ctx->error_status = 0;

      copy_strings(cpp_ctx.get_included_files(), &c_ctx->included_files, &c_ctx->num_included_files);
    }
    catch (Error& e) {
      stringstream msg_stream;
      msg_stream << e.path << ":" << e.position.line << ": error: " << e.message << endl;
      c_ctx->error_message = strdup(msg_stream.str().c_str());
      c_ctx->error_status = 1;
      c_ctx->output_string = 0;
    }
    catch(bad_alloc& ba) {
      stringstream msg_stream;
      msg_stream << "Unable to allocate memory: " << ba.what() << endl;
      c_ctx->error_message = strdup(msg_stream.str().c_str());
      c_ctx->error_status = 1;
      c_ctx->output_string = 0;
    }
    // TO DO: CATCH EVERYTHING ELSE
    return 0;
  }

  int sass_compile_file(sass_file_context* c_ctx)
  {
    using namespace Sass;
    try {
      bool source_maps = false;
      string source_map_file = "";
      if (c_ctx->source_map_file && (c_ctx->options.source_comments == SASS_SOURCE_COMMENTS_MAP)) {
        source_maps = true;
        source_map_file = c_ctx->source_map_file;
      }
      string output_path = c_ctx->output_path ? c_ctx->output_path : "";
      Context cpp_ctx(
        Context::Data().entry_point(c_ctx->input_path)
	               .output_path(output_path)
                       .output_style((Output_Style) c_ctx->options.output_style)
                       .source_comments(c_ctx->options.source_comments == SASS_SOURCE_COMMENTS_DEFAULT)
                       .source_maps(source_maps)
                       .source_map_file(source_map_file)
                       .image_path(c_ctx->options.image_path)
                       .include_paths_c_str(c_ctx->options.include_paths)
                       .include_paths_array(0)
                       .include_paths(vector<string>())
      );
      c_ctx->output_string = cpp_ctx.compile_file();
      c_ctx->source_map_string = cpp_ctx.generate_source_map();
      c_ctx->error_message = 0;
      c_ctx->error_status = 0;

      copy_strings(cpp_ctx.get_included_files(), &c_ctx->included_files, &c_ctx->num_included_files);
    }
    catch (Error& e) {
      stringstream msg_stream;
      msg_stream << e.path << ":" << e.position.line << ": error: " << e.message << endl;
      c_ctx->error_message = strdup(msg_stream.str().c_str());
      c_ctx->error_status = 1;
      c_ctx->output_string = 0;
      c_ctx->source_map_string = 0;
    }
    catch(bad_alloc& ba) {
      stringstream msg_stream;
      msg_stream << "Unable to allocate memory: " << ba.what() << endl;
      c_ctx->error_message = strdup(msg_stream.str().c_str());
      c_ctx->error_status = 1;
      c_ctx->output_string = 0;
      c_ctx->source_map_string = 0;
    }
    catch(string& bad_path) {
      // couldn't find the specified file in the include paths; report an error
      stringstream msg_stream;
      msg_stream << "error reading file \"" << bad_path << "\"" << endl;
      c_ctx->error_message = strdup(msg_stream.str().c_str());
      c_ctx->error_status = 1;
      c_ctx->output_string = 0;
      c_ctx->source_map_string = 0;
    }
    // TO DO: CATCH EVERYTHING ELSE
    return 0;
  }

  int sass_compile_folder(sass_folder_context* c_ctx)
  {
    return 1;
  }

}
