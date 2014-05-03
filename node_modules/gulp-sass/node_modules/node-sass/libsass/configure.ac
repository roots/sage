#                                               -*- Autoconf -*-
# Process this file with autoconf to produce a configure script.

AC_PREREQ([2.61])
AC_INIT([libsass], [1.0], [support@moovweb.com])
AC_CONFIG_SRCDIR([ast.hpp])
AC_CONFIG_MACRO_DIR([m4])
AC_CONFIG_HEADERS([config.h])
AM_INIT_AUTOMAKE([1.10 foreign])
m4_ifdef([AM_SILENT_RULES], [AM_SILENT_RULES([yes])])
LT_INIT

# Checks for programs.
AC_PROG_CXX

AC_LANG([C++])

# Checks for header files.
AC_CHECK_HEADERS([unistd.h])

# Checks for typedefs, structures, and compiler characteristics.
AC_TYPE_SIZE_T

# Checks for library functions.
AC_FUNC_MALLOC
AC_CHECK_FUNCS([floor getcwd strtol])

# Checks for testing.
AC_ARG_ENABLE(tests, AS_HELP_STRING([--enable-tests], [enable testing the build]),
              [enable_tests="$enableval"], [enable_tests=no])

if test "x$enable_tests" = "xyes"; then
  AC_PROG_CC
  AC_PROG_AWK
  AC_PATH_PROG(RUBY, [ruby])

  AC_ARG_WITH(sassc-dir,
              AS_HELP_STRING([--with-sassc-dir=<dir>], [specify directory of sassc sources for testing (default: sassc)]),
              [sassc_dir="$withval"], [sassc_dir="sassc"])
  AC_CHECK_FILE([$sassc_dir/sassc.c], [], [
    AC_MSG_ERROR([Unable to find sassc directory.
You must clone the sassc repository in this directory or specify
the --with-sassc-dir=<dir> argument.
])
  ])
  SASS_SASSC_PATH=$sassc_dir
  AC_SUBST(SASS_SASSC_PATH)

  AC_ARG_WITH(sass-spec-dir,
              AS_HELP_STRING([--with-sass-spec-dir=<dir>], [specify directory of sass-spec for testing (default: sass-spec)]),
              [sass_spec_dir="$withval"], [sass_spec_dir="sass-spec"])
  AC_CHECK_FILE([$sass_spec_dir/sass-spec.rb], [], [
    AC_MSG_ERROR([Unable to find sass-spec directory.
You must clone the sass-spec repository in this directory or specify
the --with-sass-spec-dir=<dir> argument.
])
  ])
  # Automake doesn't like its tests in an absolute path, so we make it relative.
  case $sass_spec_dir in
    /*)
      SASS_SPEC_PATH=`$RUBY -e "require 'pathname';puts Pathname.new('$sass_spec_dir').relative_path_from(Pathname.new('$PWD')).to_s"`
      ;;
    *)
      SASS_SPEC_PATH="$sass_spec_dir"
      ;;
  esac
  AC_SUBST(SASS_SPEC_PATH)
fi

AM_CONDITIONAL(ENABLE_TESTS, test "x$enable_tests" = "xyes")

AC_CONFIG_FILES([Makefile support/libsass.pc])
AC_OUTPUT
