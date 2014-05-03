ACLOCAL_AMFLAGS = -I m4

pkgconfigdir = $(libdir)/pkgconfig
pkgconfig_DATA = support/libsass.pc

lib_LTLIBRARIES = libsass.la
libsass_la_SOURCES = \
	ast.cpp \
	base64vlq.cpp \
	bind.cpp \
	constants.cpp \
	context.cpp \
	contextualize.cpp \
	copy_c_str.cpp \
	emscripten_wrapper.cpp \
	error_handling.cpp \
	eval.cpp \
	expand.cpp \
	extend.cpp \
	file.cpp \
	functions.cpp \
	inspect.cpp \
	output_compressed.cpp \
	output_nested.cpp \
	parser.cpp \
	prelexer.cpp \
	sass.cpp \
	sass_interface.cpp \
	source_map.cpp \
	to_c.cpp \
	to_string.cpp \
	units.cpp

libsass_la_LDFLAGS = -no-undefined -version-info 0:0:0

include_HEADERS = sass_interface.h sass.h

if ENABLE_TESTS

noinst_PROGRAMS = sass-tester

sass_tester_SOURCES = $(SASS_SASSC_PATH)/sassc.c
sass_tester_LDADD = libsass.la
sass_tester_LDFLAGS = -no-install

TESTS = \
	$(SASS_SPEC_PATH)/spec/basic \
	$(SASS_SPEC_PATH)/spec/benchmarks \
	$(SASS_SPEC_PATH)/spec/bourbon \
	$(SASS_SPEC_PATH)/spec/libsass \
	$(SASS_SPEC_PATH)/spec/scss \
	$(SASS_SPEC_PATH)/spec/todo

LOG_COMPILER = $(RUBY) $(SASS_SPEC_PATH)/sass-spec.rb
AM_LOG_FLAGS = -c ./sass-tester

SASS_SASSC_PATH ?= sassc
SASS_SPEC_PATH ?= sass-spec
SASSC_BIN = $(SASS_SASSC_PATH)/bin/sassc

$(SASSC_BIN): libsass.la
	cd $(SASS_SASSC_PATH) && make

test: $(SASSC_BIN) libsass.la
	ruby $(SASS_SPEC_PATH)/sass-spec.rb -c $(SASSC_BIN) -s $(LOG_FLAGS) $(SASS_SPEC_PATH)

test_build: $(SASSC_BIN) libsass.la
	ruby $(SASS_SPEC_PATH)/sass-spec.rb -c $(SASSC_BIN) -s --ignore-todo $(LOG_FLAGS) $(SASS_SPEC_PATH)

test_issues: $(SASSC_BIN) libsass.la
	ruby $(SASS_SPEC_PATH)/sass-spec.rb -c $(SASSC_BIN) $(LOG_FLAGS) $(SASS_SPEC_PATH)/spec/issues

endif

