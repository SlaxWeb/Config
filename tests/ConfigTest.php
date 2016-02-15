<?php
/**
 * Config Test
 *
 * The Config class needs to communicate with the Config Handler class of the
 * ConfigHandlerInterface. This test ensures that all the calls are properly
 * forwarded to the handler class, and that appropriate exceptions are being
 * thrown.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
namespace SlaxWeb\Config\Tests;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the class constructor
     *
     * The Config class must receive a handler that implements the
     * ConfigHandlerInterface as injection.
     */
    public function testConstruct()
    {
        $config = $this->getMockBuilder("\\SlaxWeb\\Config")
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $handler = $this->getMockBuilder("\\SlaxWeb\\PhpConfigHandler")
            ->setMethods(null)
            ->getMock();

        try {
            $config->__construct(new \stdClass, "some/path");
        } catch (\TypeError $e) {
            if (preg_match(
                    "~^Arg.*?1.*?SlaxWeb\\\\Config::__construct.*?interface\s"
                    . "SlaxWeb\\\\ConfigHandlerInterface.*?stdClass.*$~",
                    $e->getMessage()) == false
            ) {
                throw new \Exception
                    ("Not the expected error message: " . $e->getMessage()
                );
            }
        }

        $config->__construct($handler, "some/path");

        try {
            $config->__construct($handler, new \stdClass);
        } catch (\TypeError $e) {
            if (preg_match(
                    "~^Arg.*?2.*?SlaxWeb\\\\Config::__construct.*?type\s"
                    . "string.*?object.*$~",
                    $e->getMessage()) == false
            ) {
                throw new \Exception
                    ("Not the expected error message: " . $e->getMessage()
                );
            }
        }
    }

    /**
     * Test offsetExists
     *
     * Test the 'exists' method of the ArrayAccess. It should simply just pass
     * back the true/false values from the handler.
     */
    public function testOffsetExists()
    {
        $config = $this->getMockBuilder("\\SlaxWeb\\Config")
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $handler = $this->getMockBuilder("\\SlaxWeb\\PhpConfigHandler")
            ->setMethods(["exists"])
            ->getMock();

        $handler->expects($this->exactly(2))
            ->method("exists")
            ->withConsecutive(
                [$this->equalTo("existing")],
                [$this->equalTo("missing")]
            )->will($this->onConsecutiveCalls(true, false));

        $config->__construct($handler, "some/path");

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
        $config = $this->getMockBuilder("\\SlaxWeb\\Config")
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $handler = $this->getMockBuilder("\\SlaxWeb\\PhpConfigHandler")
            ->setMethods(["get"])
            ->getMock();

        $handler->expects($this->once())
            ->method("get")
            ->with("test.config")
            ->willReturn("value");

        $config->__construct($handler, "some/path");

        $this->assertEquals($config["test.config"], "value");
    }
}
