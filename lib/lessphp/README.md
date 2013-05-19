# lessphp v0.3.9
### <http://leafo.net/lessphp>

[![Build Status](https://secure.travis-ci.org/leafo/lessphp.png)](http://travis-ci.org/leafo/lessphp)

`lessphp` is a compiler for LESS written in PHP. The documentation is great,
so check it out: <http://leafo.net/lessphp/docs/>.

Here's a quick tutorial:

### How to use in your PHP project

The only file required is `lessc.inc.php`, so copy that to your include directory.

The typical flow of **lessphp** is to create a new instance of `lessc`,
configure it how you like, then tell it to compile something using one built in
compile methods.

The `compile` method compiles a string of LESS code to CSS.

```php
<?php
require "lessc.inc.php";

$less = new lessc;
echo $less->compile(".block { padding: 3 + 4px }");
```

The `compileFile` method reads and compiles a file. It will either return the
result or write it to the path specified by an optional second argument.

```php
<?php
echo $less->compileFile("input.less");
```

The `compileChecked` method is like `compileFile`, but it only compiles if the output
file doesn't exist or it's older than the input file:

```php
<?php
$less->checkedCompile("input.less", "output.css");
```

If there any problem compiling your code, an exception is thrown with a helpful message:

```php
<?php
try {
  $less->compile("invalid LESS } {");
} catch (exception $e) {
  echo "fatal error: " . $e->getMessage();
}
```

The `lessc` object can be configured through an assortment of instance methods.
Some possible configuration options include [changing the output format][1],
[setting variables from PHP][2], and [controlling the preservation of
comments][3], writing [custom functions][4] and much more. It's all described
in [the documentation][0].


 [0]: http://leafo.net/lessphp/docs/
 [1]: http://leafo.net/lessphp/docs/#output_formatting
 [2]: http://leafo.net/lessphp/docs/#setting_variables_from_php
 [3]: http://leafo.net/lessphp/docs/#preserving_comments
 [4]: http://leafo.net/lessphp/docs/#custom_functions


### How to use from the command line

An additional script has been included to use the compiler from the command
line. In the simplest invocation, you specify an input file and the compiled
css is written to standard out:

    $ plessc input.less > output.css

Using the -r flag, you can specify LESS code directly as an argument or, if 
the argument is left off, from standard in:

    $ plessc -r "my less code here"

Finally, by using the -w flag you can watch a specified input file and have it 
compile as needed to the output file:

    $ plessc -w input-file output-file

Errors from watch mode are written to standard out.

The -f flag sets the [output formatter][1]. For example, to compress the
output run this:

    $ plessc -f=compressed myfile.less

For more help, run `plessc --help`

