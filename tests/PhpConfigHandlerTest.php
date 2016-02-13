<?php
/**
 * PHP Config Handler Test
 *
 * Test class for the PHP Config Handler class of the SlaxWeb\Config component.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
namespace SlaxWeb\Config\Tests;

use SlaxWeb\PhpConfigHandler as ConfigHandler;

class RouterTest extends \PHPUnit_Framework_TestCase
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
        $handler = $this->getMockBuilder("\\SlaxWeb\\PhpConfigHandler")
            ->setMethods(null)
            ->getMock();

        // file found, and parsed
        $this->assertEquals(
            $handler->load(__DIR__ . "/TestConfig/PhpConfig.php"),
            ConfigHandler::CONFIG_LOADED
        );
        // file not found
        $this->assertEquals(
            $handler->load(__DIR__ . "/TestConfig/NotFound.php"),
            ConfigHandler::CONFIG_RESOURCE_NOT_FOUND
        );
        // file found, parsing failed
        $this->assertEquals(
            $handler->load(__DIR__ . "/TestConfig/MissingPhpConfig.php"),
            ConfigHandler::CONFIG_PARSE_ERROR
        );

        return $handler;
    }

    /**
     * Test the 'set' method
     *
     * Ensure that we can add, and overwrite configuration values, after they
     * have been loaded from configuration resource(s), and that the 'set'
     * method will respond with false, when the key is not a string value.
     *
     * @depends testLoad
     */
    public function testSet($handler)
    {
        $this->assertTrue($handler->set("test.config.overwrite", "overwritten"));
        $this->assertTrue($handler->set("test.config.write", true));
        $this->assertFalse($handler->set(false, "error"));

        return $handler;
    }

    /**
     * Test the 'get' method
     *
     * Ensure that we can get the propper values from the config, and that the
     * configuration values were properly set by the 'set' method. On a missing
     * key, a null value must be returned.
     *
     * @depends testSet
     */
    public function testGet($handler)
    {
        $this->assertEquals($handler->get("test.config"), "test");
        $this->assertEquals(
            $handler->get("test.config.overwrite"),
            "overwritten"
        );
        $this->assertEquals($handler->get("test.config.write"), true);
        $this->assertEquals($handler->get("test.config.missing"), null);
    }
}
