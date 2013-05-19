<?php

require_once __DIR__ . "/../lessc.inc.php";

class ApiTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->less = new lessc();
		$this->less->importDir = array(__DIR__ . "/inputs/test-imports");
	}

	public function testPreserveComments() {
		$input = <<<EOD
// what is going on?

/** what the heck **/

/**

Here is a block comment

**/


// this is a comment

/*hello*/div /*yeah*/ { //surew
	border: 1px solid red; // world
	/* another property */
	color: url('http://mage-page.com');
	string: "hello /* this is not a comment */";
	world: "// neither is this";
	string: 'hello /* this is not a comment */' /*what if this is a comment */;
	world: '// neither is this' // hell world;
	;
	what-ever: 100px;
	background: url(/*this is not a comment?*/); // uhh what happens here
}
EOD;


		$outputWithComments = <<<EOD
/** what the heck **/
/**

Here is a block comment

**/
/*hello*/
/*yeah*/
div /*yeah*/ {
  /* another property */
  border: 1px solid red;
  color: url('http://mage-page.com');
  string: "hello /* this is not a comment */";
  world: "// neither is this";
  /*what if this is a comment */
  string: 'hello /* this is not a comment */';
  world: '// neither is this';
  what-ever: 100px;
  /*this is not a comment?*/
  background: url();
}
EOD;

		$outputWithoutComments = <<<EOD
div {
  border: 1px solid red;
  color: url('http://mage-page.com');
  string: "hello /* this is not a comment */";
  world: "// neither is this";
  string: 'hello /* this is not a comment */';
  world: '// neither is this';
  what-ever: 100px;
  background: url(/*this is not a comment?*/);
}
EOD;

		$this->assertEquals($this->compile($input), trim($outputWithoutComments));
		$this->less->setPreserveComments(true);
		$this->assertEquals($this->compile($input), trim($outputWithComments));
	}

	public function testOldInterface() {
		$this->less = new lessc(__DIR__ . "/inputs/hi.less");
		$out = $this->less->parse(array("hello" => "10px"));
		$this->assertEquals(trim($out), trim('
div:before {
  content: "hi!";
}'));

	}

	public function testInjectVars() {
		$out = $this->less->parse(".magic { color: @color;  width: @base - 200; }",
			array(
				'color' => 'red',
				'base' => '960px'
			));
	
		$this->assertEquals(trim($out), trim("
.magic {
  color: red;
  width: 760px;
}"));

	}

	public function testDisableImport() {
		$this->less->importDisabled = true;
		$this->assertEquals(
			"/* import disabled */",
			$this->compile("@import 'file3';"));
	}

	public function testUserFunction() {
		$this->less->registerFunction("add-two", function($list) {
			list($a, $b) = $list[2];
			return $a[1] + $b[1];
		});

		$this->assertEquals(
			$this->compile("result: add-two(10, 20);"),
			"result: 30;");
		
		return $this->less;
	}

	/**
	 * @depends testUserFunction
	 */
	public function testUnregisterFunction($less) {
		$less->unregisterFunction("add-two");

		$this->assertEquals(
			$this->compile("result: add-two(10, 20);"),
			"result: add-two(10,20);");
	}



	public function testFormatters() {
		$src = "
			div, pre {
				color: blue;
				span, .big, hello.world {
					height: 20px;
					color:#ffffff + #000;
				}
			}";

		$this->less->setFormatter("compressed");
		$this->assertEquals(
			$this->compile($src), "div,pre{color:blue;}div span,div .big,div hello.world,pre span,pre .big,pre hello.world{height:20px;color:#fff;}");

		// TODO: fix the output order of tags
		$this->less->setFormatter("lessjs");
		$this->assertEquals(
			$this->compile($src),
"div,
pre {
  color: blue;
}
div span,
div .big,
div hello.world,
pre span,
pre .big,
pre hello.world {
  height: 20px;
  color: #ffffff;
}");

		$this->less->setFormatter("classic");
		$this->assertEquals(
			$this->compile($src),
trim("div, pre { color:blue; }
div span, div .big, div hello.world, pre span, pre .big, pre hello.world {
  height:20px;
  color:#ffffff;
}
"));

	}

	public function compile($str) {
		return trim($this->less->parse($str));
	}

}
