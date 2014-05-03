CXX      ?= g++
CXXFLAGS = -Wall -O2 -fPIC -g
LDFLAGS  = -fPIC

PREFIX    = /usr/local
LIBDIR    = $(PREFIX)/lib

SASS_SASSC_PATH ?= sassc
SASS_SPEC_PATH ?= sass-spec
SASSC_BIN = $(SASS_SASSC_PATH)/bin/sassc

SOURCES = \
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

OBJECTS = $(SOURCES:.cpp=.o)

all: static

static: libsass.a
shared: libsass.so

js: static
	emcc -O2 libsass.a -o libsass.js -s EXPORTED_FUNCTIONS="['_sass_compile_emscripten']" -s DISABLE_EXCEPTION_CATCHING=0

libsass.a: $(OBJECTS)
	$(AR) rvs $@ $(OBJECTS)

libsass.so: $(OBJECTS)
	$(CXX) -shared $(LDFLAGS) -o $@ $(OBJECTS)

%.o: %.cpp
	$(CXX) $(CXXFLAGS) -c -o $@ $<

%: %.o libsass.a
	$(CXX) $(CXXFLAGS) -o $@ $+ $(LDFLAGS)

install: libsass.a
	install -Dpm0755 $< $(DESTDIR)$(LIBDIR)/$<

install-shared: libsass.so
	install -Dpm0755 $< $(DESTDIR)$(LIBDIR)/$<

$(SASSC_BIN): libsass.a
	cd $(SASS_SASSC_PATH) && make

test: $(SASSC_BIN) libsass.a
	ruby $(SASS_SPEC_PATH)/sass-spec.rb -c $(SASSC_BIN) -s $(LOG_FLAGS) $(SASS_SPEC_PATH)

test_build: $(SASSC_BIN) libsass.a
	ruby $(SASS_SPEC_PATH)/sass-spec.rb -c $(SASSC_BIN) -s --ignore-todo $(LOG_FLAGS) $(SASS_SPEC_PATH)

test_issues: $(SASSC_BIN) libsass.a
	ruby $(SASS_SPEC_PATH)/sass-spec.rb -c $(SASSC_BIN) $(LOG_FLAGS) $(SASS_SPEC_PATH)/spec/issues

clean:
	rm -f $(OBJECTS) *.a *.so


.PHONY: all static shared bin install install-shared clean

