#include "libsass/sass_interface.h"
#include <nan.h>

#ifdef __cplusplus
extern "C" {
#endif

struct sass_context_wrapper {
  sass_context* ctx;
  sass_file_context* fctx;
  uv_work_t request;
  NanCallback* callback;
  NanCallback* errorCallback;
};

struct sass_context_wrapper*      sass_new_context_wrapper(void);
void sass_free_context_wrapper(struct sass_context_wrapper* ctx);

#ifdef __cplusplus
}
#endif
