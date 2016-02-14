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
     * Internal Error Cotnainer
     *
     * @var array
     */
    private $__error = [];

    /**
     * Set up the test case
     *
     * Clear the internal error container, and set the errpr handler
     */
    protected function setUp()
    {
        $this->__error = [];
        set_error_handler([$this, "errorHandler"]);
    }

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

        $config->__construct(new \stdClass, "some/path");
        $this->assertContains(
            "Argument 1 passed to SlaxWeb\\Config::__construct() must "
            . "implement interface SlaxWeb\\ConfigHandlerInterface",
            $this->__error[0]["errorString"]
        );

        $this->__error = [];
        $config->__construct($handler, "some/path");
        $this->assertEquals($this->__error, []);

        $this->setExpectedException(
            "\\SlaxWeb\\Exception\\ResourceLocationException",
            "The passed in resource location must be in string format"
        );
        $this->__error = [];
        $config->__construct();
        $this->assertContains(
            "Argument 1 passed to SlaxWeb\\Config::__construct() must "
            . "implement interface SlaxWeb\\ConfigHandlerInterface",
            $this->__error[0]["errorString"]
        );
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
     * Test Error Handler
     *
     * Set the error to the internal error container for checking later
     */
    public function errorHandler(
        $errorNumber,
        $errorString,
        $errorFile,
        $errorLine,
        $errorContext
    ) {
        $this->__error[] = compact(
            "errorNumber",
            "errorString",
            "errorFile",
            "errorLine",
            "errorContext"
        );

        return true;
    }
}
