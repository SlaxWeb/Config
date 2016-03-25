<?php
/**
 * YAML Config Handler Test
 *
 * Test class for the YAML Config Handler class of the SlaxWeb\Config component.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.2
 */
namespace SlaxWeb\Config\Tests;

use SlaxWeb\Config\YamlHandler as Handler;

class YamlHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    /**
     * Test 'load' method
     *
     * This test ensures that the 'load' method functions according to
     * specification. The 'load' method should receive full absolute path to the
     * config file it must load, and forward the contents to the '_parse'
     * protected method.
     *
     * If the file it should load does not exist, it must return the 'file not
     * found' constant, or if the contents of the file could not have been
     * parsed it must return the 'parse error' constant. If everything went as
     * expected it must return the 'loaded' constants.
     */
    public function testLoad()
    {
        $handler = $this->getMockBuilder("\\SlaxWeb\\Config\\YamlHandler")
            ->setConstructorArgs([[__DIR__ . "/../_support/TestConfig/"]])
            ->setMethods(["prependResourceName"])
            ->getMock();

        $handler->expects($this->once())
            ->method("prependResourceName")
            ->with(["test.config" => "test"], "YamlConfig")
            ->willReturn(["test.config" => "test"]);

        // file found, and parsed
        $this->assertEquals(
            Handler::CONFIG_LOADED,
            $handler->load("YamlConfig.yaml", true)
        );
        // file not found
        $this->assertEquals(
            Handler::CONFIG_RESOURCE_NOT_FOUND,
            $handler->load("NotFound.yaml")
        );
        // file found, parsing failed
        $this->assertEquals(
            Handler::CONFIG_PARSE_ERROR,
            $handler->load("NotYamlConfig.yaml")
        );

        $this->assertEquals($handler->get("test.config"), "test");
    }
}
