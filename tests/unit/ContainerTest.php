<?php
/**
 * Container Test
 *
 * The Config class needs to communicate with the Config Handler class. This test
 * ensures that all the calls are properly forwarded to the handler class, and that
 * appropriate exceptions are being thrown.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.2
 */
namespace SlaxWeb\Config\Tests;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    /**
     * Test the class constructor
     *
     * The Config class must receive a handler that extends the Handler class as
     * injection.
     */
    public function testConstruct()
    {
        $config = $this->getMockBuilder("\\SlaxWeb\\Config\\Container")
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $handler = $this->getMockBuilder("\\SlaxWeb\\Config\\PhpHandler")
            ->setConstructorArgs([["some/path"]])
            ->setMethods(null)
            ->getMock();

        try {
            $config->__construct(new \stdClass);
        } catch (\TypeError $e) {
            if (preg_match(
                "~^Arg.*?1.*?SlaxWeb\\\\Config\\\\Container::__construct.*?"
                    . "instance of\sSlaxWeb\\\\Config\\\\Handler"
                    . ".*?stdClass.*$~",
                $e->getMessage()
            ) == false) {
                throw new \Exception(
                    "Not the expected error message: " . $e->getMessage()
                );
            }
        }

        $config->__construct($handler);
    }

    /**
     * Test offsetExists
     *
     * Test the 'exists' method of the ArrayAccess. It should simply just pass
     * back the true/false values from the handler.
     */
    public function testOffsetExists()
    {
        $config = $this->getMockBuilder("\\SlaxWeb\\Config\\Container")
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $handler = $this->getMockBuilder("\\SlaxWeb\\Config\\PhpHandler")
            ->setConstructorArgs([["some/path"]])
            ->setMethods(["exists"])
            ->getMock();

        $handler->expects($this->exactly(2))
            ->method("exists")
            ->withConsecutive(
                [$this->equalTo("existing")],
                [$this->equalTo("missing")]
            )->will($this->onConsecutiveCalls(true, false));

        $config->__construct($handler);

        $this->assertTrue(isset($config["existing"]));
        $this->assertFalse(isset($config["missing"]));
    }

    /**
     * Test offsetGet
     *
     * Test the 'get' method of the ArrayAccess. It should return the value of
     * the configuration item.
     */
    public function testOffsetGet()
    {
        $config = $this->getMockBuilder("\\SlaxWeb\\Config\\Container")
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $handler = $this->getMockBuilder("\\SlaxWeb\\Config\\PhpHandler")
            ->setConstructorArgs([["some/path"]])
            ->setMethods(["get"])
            ->getMock();

        $handler->expects($this->once())
            ->method("get")
            ->with("test.config")
            ->willReturn("value");

        $config->__construct($handler);

        $this->assertEquals($config["test.config"], "value");
    }

    /**
     * Test offsetSet
     *
     * Test the 'set' method of the ArrayAccess. It must set the proper value
     * through the configuration handler.
     */
    public function testOffsetSet()
    {
        $config = $this->getMockBuilder("\\SlaxWeb\\Config\\Container")
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $handler = $this->getMockBuilder("\\SlaxWeb\\Config\\PhpHandler")
            ->setConstructorArgs([["some/path"]])
            ->setMethods(["set"])
            ->getMock();

        $handler->expects($this->once())
            ->method("set")
            ->with("test.config", "value");

        $config->__construct($handler);

        $config["test.config"] = "value";
    }

    /**
     * Test offsetUnset
     *
     * Test the 'unset' method of the ArrayAccess implementation. It must unset
     * the received offset through the configuration handler.
     */
    public function testOffsetUnset()
    {
        $config = $this->getMockBuilder("\\SlaxWeb\\Config\\Container")
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $handler = $this->getMockBuilder("\\SlaxWeb\\Config\\PhpHandler")
            ->setConstructorArgs([["some/path"]])
            ->setMethods(["remove"])
            ->getMock();

        $handler->expects($this->exactly(2))
            ->method("remove")
            ->withConsecutive(["test.config"], ["test.missing"])
            ->will($this->onConsecutiveCalls(true, false));

        $config->__construct($handler);

        unset($config["test.config"]);

        $isException = false;
        try {
            unset($config["test.missing"]);
        } catch (\SlaxWeb\Config\Exception\MissingKeyException $e) {
            $isException = true;
        }
        if ($isException === false) {
            throw new \Exception(
                "Test was expected to throw 'MissingKeyException'"
            );
        }
    }

    /**
     * Test config file loading
     *
     * Test that the 'load' method forwards the correct file name to the
     * configuration handler, and handles the responding code accordingly.
     */
    public function testLoad()
    {
        $config = $this->getMockBuilder("\\SlaxWeb\\Config\\Container")
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $handler = $this->getMockBuilder("\\SlaxWeb\\Config\\PhpHandler")
            ->setConstructorArgs([["some/path"]])
            ->setMethods(["load"])
            ->getMock();

        $handler->expects($this->exactly(3))
            ->method("load")
            ->withConsecutive(
                ["config.ok.php"],
                ["config.parse.error.php"],
                ["config.missing.php"]
            )->will($this->onConsecutiveCalls(100, 102, 101));

        $config->__construct($handler);

        // ok load
        $config->load("config.ok.php");

        // parse error
        $isException = false;
        try {
            $config->load("config.parse.error.php");
        } catch (\SlaxWeb\Config\Exception\ConfigParseException $e) {
            if ($e->getMessage() ===
                "Error parsing 'config.parse.error.php' configuration "
                . "resource") {
                $isException = true;
            }
        }
        if ($isException === false) {
            throw new \Exception(
                "Test was expected to throw 'ConfigParseException'"
            );
        }

        // missing config file
        $isException = false;
        try {
            $config->load("config.missing.php");
        } catch (\SlaxWeb\Config\Exception\ConfigResourceNotFoundException $e) {
            if ($e->getMessage() ===
                "Error 'config.missing.php' configuration resource not found") {
                $isException = true;
            }
        }
        if ($isException === false) {
            throw new \Exception(
                "Test was expected to throw 'ConfigResourceNotFoundException'"
            );
        }
    }
}
