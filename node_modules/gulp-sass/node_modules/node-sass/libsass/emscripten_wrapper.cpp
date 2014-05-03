#include <cstdlib>
#include <cstring>
#include "sass_interface.h"
#include "emscripten_wrapper.hpp"

char *sass_compile_emscripten(
  char *source_string,
  int output_style,
  int source_comments,
  char *include_paths,
  char **error_message
) {
  char *output_string;
  struct sass_options options;
  struct sass_context *ctx = sass_new_context();

  options.source_comments = source_comments;
  options.output_style = output_style;
  options.image_path = NULL;
  options.include_paths = include_paths;

  ctx->options = options;
  ctx->source_string = source_string;

  sass_compile(ctx);

  if (ctx->output_string) {
    output_string = strdup(ctx->output_string);
  } else {
    output_string = NULL;
  }

  if (ctx->error_status) {
    *error_message = strdup(ctx->error_message);
  } else {
    *error_message = NULL;
  }

  sass_free_context(ctx);
  return output_string;
}

