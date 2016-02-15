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
 * @version   0.1
 */
namespace SlaxWeb\Config\Tests;

use SlaxWeb\Config\YamlConfigHandler as ConfigHandler;

class YamlConfigHandlerTest extends \PHPUnit_Framework_TestCase
{
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
        $handler = $this->getMockBuilder("\\SlaxWeb\\Config\\YamlConfigHandler")
            ->setMethods(null)
            ->getMock();

        // file found, and parsed
        $this->assertEquals(
            $handler->load(__DIR__ . "/TestConfig/YamlConfig.yaml"),
            ConfigHandler::CONFIG_LOADED
        );
        // file not found
        $this->assertEquals(
            $handler->load(__DIR__ . "/TestConfig/NotFound.yaml"),
            ConfigHandler::CONFIG_RESOURCE_NOT_FOUND
        );
        // file found, parsing failed
        $this->assertEquals(
            $handler->load(__DIR__ . "/TestConfig/NotYamlConfig.yaml"),
            ConfigHandler::CONFIG_PARSE_ERROR
        );

        $this->assertEquals($handler->get("test.config"), "test");
    }
}
