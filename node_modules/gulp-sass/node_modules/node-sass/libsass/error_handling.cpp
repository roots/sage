#ifndef SASS_ERROR_HANDLING
#include "error_handling.hpp"
#endif

#include "backtrace.hpp"
#include "prelexer.hpp"

namespace Sass {

  Error::Error(Type type, string path, Position position, string message)
  : type(type), path(path), position(position), message(message)
  { }

  void error(string msg, string path, Position position)
  { throw Error(Error::syntax, path, position, msg); }

  void error(string msg, string path, Position position, Backtrace* bt)
  {
    if (!path.empty() && Prelexer::string_constant(path.c_str()))
      path = path.substr(1, path.size() - 1);

    Backtrace top(bt, path, position, "");
    msg += top.to_string();

    throw Error(Error::syntax, path, position, msg);
  }

}
