<?php
/**
 * Pop PHP Framework Unit Tests
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.TXT.
 * It is also available through the world-wide-web at this URL:
 * http://www.popphp.org/LICENSE.TXT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@popphp.org so we can send you a copy immediately.
 *
 */

namespace PopTest\Code;

use Pop\Loader\Autoloader,
    Pop\Code\FunctionGenerator;

// Require the library's autoloader.
require_once __DIR__ . '/../../../src/Pop/Loader/Autoloader.php';

// Call the autoloader's bootstrap function.
Autoloader::factory()->splAutoloadRegister();

function someFunction($arg1, $arg2) {
    echo $arg1 . ' ' . $arg2;
}

class FunctionTest extends \PHPUnit_Framework_TestCase
{

    public function testFactory()
    {
        $this->assertInstanceOf('Pop\Code\FunctionGenerator', FunctionGenerator::factory('newFunction'));
    }

    public function testClosure()
    {
        $f = FunctionGenerator::factory('newFunction');
        $f->setClosure(true);
        $this->assertTrue($f->isClosure());
    }

    public function testDesc()
    {
        $f = FunctionGenerator::factory('newFunction');
        $f->setDesc('This is a test function');
        $f->setDesc('This is REALLY a test function');
        $this->assertEquals('This is REALLY a test function', $f->getDesc());
    }

    public function testIndent()
    {
        $f = FunctionGenerator::factory('newFunction');
        $f->setIndent('    ');
        $this->assertEquals('    ', $f->getIndent());
    }

    public function testName()
    {
        $f = FunctionGenerator::factory('newFunction');
        $f->setName('newNameFunction');
        $this->assertEquals('newNameFunction', $f->getName());
    }

    public function testBody()
    {
        $f = FunctionGenerator::factory('newFunction');
        $f->setBody('echo $arg;');
        $f->appendToBody('echo $arg;');
        $this->assertEquals('    echo $arg;' . PHP_EOL . '    echo $arg;' . PHP_EOL, $f->getBody());
    }

    public function testArguments()
    {
        $f = FunctionGenerator::factory('newFunction');
        $f->addArgument('testVar', 123, 'int');
        $f->addParameter('oneMoreTestVar', 789, 'int');
        $f->addArguments(array(
            array('name' => 'anotherTestVar', 'value' => 456, 'type' => 'int')
        ));
        $f->addParameters(array(
            array('name' => 'yetAnotherTestVar', 'value' => 987, 'type' => 'int')
        ));
        $this->assertTrue(is_array($f->getArguments()));
        $this->assertTrue(is_array($f->getParameters()));
        $arg = $f->getArgument('testVar');
        $par = $f->getParameter('oneMoreTestVar');
        $this->assertEquals(123, $arg['value']);
        $this->assertEquals(789, $par['value']);

    }

    public function testRender()
    {
        $f = FunctionGenerator::factory('newFunction');
        $f->setBody('some body code', true);
        $f->appendToBody('some more body code');
        $f->appendToBody('even more body code', false);
        $f->addArgument('testVar', 123, 'int');
        $f->addParameter('oneMoreTestVar', 789, 'int');

        $codeStr = (string)$f;
        $code = $f->render(true);

        ob_start();
        $f->render();
        $output = ob_get_clean();
        $this->assertContains('function newFunction($testVar = 123, $oneMoreTestVar = 789)', $output);
        $this->assertContains('function newFunction($testVar = 123, $oneMoreTestVar = 789)', $code);
        $this->assertContains('function newFunction($testVar = 123, $oneMoreTestVar = 789)', $codeStr);
    }

}
