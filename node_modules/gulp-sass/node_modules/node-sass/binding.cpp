#include <nan.h>
#include <string>
#include <cstring>
#include <iostream>
#include <cstdlib>
#include "sass_context_wrapper.h"

using namespace v8;
using namespace std;


void WorkOnContext(uv_work_t* req) {
  sass_context_wrapper* ctx_w = static_cast<sass_context_wrapper*>(req->data);
  if (ctx_w->ctx) {
    sass_context* ctx = static_cast<sass_context*>(ctx_w->ctx);
    sass_compile(ctx);
  } else if (ctx_w->fctx) {
    sass_file_context* ctx = static_cast<sass_file_context*>(ctx_w->fctx);
    sass_compile_file(ctx);
  }
}

void extractOptions(_NAN_METHOD_ARGS, void* cptr, sass_context_wrapper* ctx_w, bool isFile) {
  char *source;
  char* pathOrData;
  char* imagePath;
  int output_style;
  int source_comments;
  String::AsciiValue astr(args[0]);

  String::AsciiValue bstr(args[1]);
  imagePath = new char[strlen(*bstr)+1];
  strcpy(imagePath, *bstr);

  if (ctx_w) {
    // async (callback) style
    Local<Function> callback = Local<Function>::Cast(args[2]);
    Local<Function> errorCallback = Local<Function>::Cast(args[3]);
    if (isFile) {
      ctx_w->fctx = (sass_file_context*) cptr;
    } else {
      ctx_w->ctx = (sass_context*) cptr;
    }
    ctx_w->request.data = ctx_w;
    ctx_w->callback = new NanCallback(callback);
    ctx_w->errorCallback = new NanCallback(errorCallback);
    output_style = args[5]->Int32Value();
    source_comments = args[6]->Int32Value();
    String::AsciiValue cstr(args[4]);
    pathOrData = new char[strlen(*cstr)+1];
    strcpy(pathOrData, *cstr);
  } else {
    // synchronous style
    output_style = args[3]->Int32Value();
    source_comments = args[4]->Int32Value();
    String::AsciiValue cstr(args[2]);
    pathOrData = new char[strlen(*cstr)+1];
    strcpy(pathOrData, *cstr);
  }

  if (isFile) {
    sass_file_context *ctx = (sass_file_context*)cptr;
    char *filename = new char[strlen(*astr)+1];
    strcpy(filename, *astr);
    ctx->input_path = filename;
    ctx->options.image_path = imagePath;
    ctx->options.output_style = output_style;
    ctx->options.source_comments = source_comments;
    ctx->options.include_paths = pathOrData;
    if (source_comments == SASS_SOURCE_COMMENTS_MAP) {
      String::AsciiValue dstr(args[7]);
      ctx->source_map_file = new char[strlen(*dstr)+1];
      strcpy((char*) ctx->source_map_file, *dstr);
    }
  } else {
    sass_context *ctx = (sass_context*)cptr;
    source = new char[strlen(*astr)+1];
    strcpy(source, *astr);
    ctx->source_string = source;
    ctx->options.image_path = imagePath;
    ctx->options.output_style = output_style;
    ctx->options.source_comments = source_comments;
    ctx->options.include_paths = pathOrData;
  }
}

void MakeCallback(uv_work_t* req) {
    NanScope();
  TryCatch try_catch;
  sass_context_wrapper* ctx_w = static_cast<sass_context_wrapper*>(req->data);
  Handle<Value> val, err;
  const unsigned argc = 2;
  int error_status = ctx_w->ctx ? ctx_w->ctx->error_status : ctx_w->fctx->error_status;

  if (error_status == 0) {
    // if no error, do callback(null, result)
    Handle<Value> source_map;
    if (ctx_w->fctx && ctx_w->fctx->options.source_comments == SASS_SOURCE_COMMENTS_MAP) {
      source_map = String::New(ctx_w->fctx->source_map_string);
    } else {
      source_map = Null();
    }

    val = ctx_w->ctx ? NanNewLocal(String::New(ctx_w->ctx->output_string)) : NanNewLocal(String::New(ctx_w->fctx->output_string));
    Local<Value> argv[argc] = {
      NanNewLocal(val),
      NanNewLocal(source_map),
    };
    ctx_w->callback->Call(argc, argv);
  } else {
    // if error, do callback(error)
    err = ctx_w->ctx ? NanNewLocal(String::New(ctx_w->ctx->error_message)) : NanNewLocal(String::New(ctx_w->fctx->error_message));
    Local<Value> argv[argc] = {
      NanNewLocal(err),
      NanNewLocal(Integer::New(error_status))
    };
    ctx_w->errorCallback->Call(argc, argv);
  }
  if (try_catch.HasCaught()) {
    node::FatalException(try_catch);
  }
  if (ctx_w->ctx) {
    delete ctx_w->ctx->source_string;
  } else {
    delete ctx_w->fctx->input_path;
  }
  sass_free_context_wrapper(ctx_w);
}

NAN_METHOD(Render) {
  NanScope();
  sass_context* ctx = sass_new_context();
  sass_context_wrapper* ctx_w = sass_new_context_wrapper();
  ctx_w->ctx = ctx;
  extractOptions(args, ctx, ctx_w, false);

  int status = uv_queue_work(uv_default_loop(), &ctx_w->request, WorkOnContext, (uv_after_work_cb)MakeCallback);
  assert(status == 0);

  NanReturnUndefined();
}

NAN_METHOD(RenderSync) {
  NanScope();
  sass_context* ctx = sass_new_context();
  extractOptions(args, ctx, NULL, false);

  sass_compile(ctx);

  delete ctx->source_string;
  ctx->source_string = NULL;
  delete ctx->options.include_paths;
  ctx->options.include_paths = NULL;

  if (ctx->error_status == 0) {
    Local<Value> output = NanNewLocal(String::New(ctx->output_string));
    sass_free_context(ctx);
    NanReturnValue(output);
  }

  Local<String> error = String::New(ctx->error_message);

  sass_free_context(ctx);
  NanThrowError(error);
  NanReturnUndefined();
}

NAN_METHOD(RenderFile) {
  NanScope();
  sass_file_context* fctx = sass_new_file_context();
  sass_context_wrapper* ctx_w = sass_new_context_wrapper();
  ctx_w->fctx = fctx;
  extractOptions(args, fctx, ctx_w, true);

  int status = uv_queue_work(uv_default_loop(), &ctx_w->request, WorkOnContext, (uv_after_work_cb)MakeCallback);
  assert(status == 0);

  NanReturnUndefined();
}

NAN_METHOD(RenderFileSync) {
  NanScope();
  sass_file_context* ctx = sass_new_file_context();
  extractOptions(args, ctx, NULL, true);

  sass_compile_file(ctx);

  delete ctx->input_path;
  ctx->input_path = NULL;
  delete ctx->options.include_paths;
  ctx->options.include_paths = NULL;

  if (ctx->error_status == 0) {
    Local<Value> output = NanNewLocal(String::New(ctx->output_string));
    sass_free_file_context(ctx);

    NanReturnValue(output);
  }
  Local<String> error = String::New(ctx->error_message);
  sass_free_file_context(ctx);

  NanThrowError(error);
  NanReturnUndefined();
}

void RegisterModule(v8::Handle<v8::Object> target) {
  NODE_SET_METHOD(target, "render", Render);
  NODE_SET_METHOD(target, "renderSync", RenderSync);
  NODE_SET_METHOD(target, "renderFile", RenderFile);
  NODE_SET_METHOD(target, "renderFileSync", RenderFileSync);
}

NODE_MODULE(binding, RegisterModule);
