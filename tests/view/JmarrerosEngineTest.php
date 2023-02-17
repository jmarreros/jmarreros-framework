<?php

namespace Jmarreros\Test\view;

use Jmarreros\View\JmarrerosEngine;
use PHPUnit\Framework\TestCase;

class JmarrerosEngineTest extends TestCase {
    public function testRenderTemplateWithParams() {
        $parameter1 = "Test 1";
        $parameter2 = 2;

        $expected = "<html>
	<body>
		<h1>$parameter1</h1>
		<h1>$parameter2</h1>
	</body>
</html>";

        $engine  = new JmarrerosEngine(__DIR__ . "/views");
        $content = $engine->render("test", compact('parameter1', 'parameter2'), 'layout');

        $this->assertEquals(
            preg_replace("/\s*/", "", $expected),
            preg_replace("/\s*/", "", $content)
        );
    }
}
