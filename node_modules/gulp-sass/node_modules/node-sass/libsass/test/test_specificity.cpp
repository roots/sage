#include "../ast.hpp"
#include "../context.hpp"
#include "../parser.hpp"
#include "../to_string.hpp"
#include <string>
#include <iostream>

using namespace std;
using namespace Sass;

Context ctx = Context::Data();
To_String to_string;

Selector* selector(string src)
{ return Parser::from_c_str(src.c_str(), ctx, "", Position()).parse_selector_group(); }

void spec(string sel)
{ cout << sel << "\t::\t" << selector(sel + ";")->specificity() << endl; }

int main()
{
  spec("foo bar hux");
  spec(".foo .bar hux");
  spec("#foo .bar[hux='mux']");
  spec("a b c d e f");

  return 0;
}
