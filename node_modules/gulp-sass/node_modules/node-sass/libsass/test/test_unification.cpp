#include "../ast.hpp"
#include "../context.hpp"
#include "../parser.hpp"
#include "../to_string.hpp"
#include <string>

using namespace Sass;

Context ctx = Context(Context::Data());
To_String to_string;

Compound_Selector* selector(string src)
{ return Parser::from_c_str(src.c_str(), ctx, "", Position()).parse_simple_selector_sequence(); }

void unify(string lhs, string rhs)
{
  Compound_Selector* unified = selector(lhs + ";")->unify_with(selector(rhs + ";"), ctx);
  cout << lhs << " UNIFIED WITH " << rhs << " =\t" << (unified ? unified->perform(&to_string) : "NOTHING") << endl;
}

int main()
{
  unify(".foo", ".foo.bar");
  unify("div:nth-of-type(odd)", "div:first-child");
  unify("div", "span:whatever");
  unify("div", "span");
  unify("foo:bar::after", "foo:bar::first-letter");
  unify(".foo#bar.hux", ".hux.foo#bar");
  unify(".foo#bar.hux", ".hux.foo#baz");
  unify("*:blah:fudge", "p:fudge:blah");

  return 0;
}
