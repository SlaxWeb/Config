<?php
/**
 * Config Handler Test
 *
 * Test class for the Config Handler abstract class of the SlaxWeb\Config
 * component.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.2
 */
namespace SlaxWeb\Config\Tests;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    /**
     * Test the 'set' method
     *
     * Ensure that we can add, and overwrite configuration values, after they
     * have been loaded from configuration resource(s), and that the 'set'
     * method will respond with false, when the key is not a string value.
     */
    public function testSet()
    {
        $handler = $this->getMockBuilder("\\SlaxWeb\\Config\\Handler")
            ->setConstructorArgs([["some/path"]])
            ->setMethods(["load"])
            ->getMock();

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
        $this->assertEquals($handler->get("test.config.write"), true);
        $this->assertEquals($handler->get("test.config.missing"), null);

        return $handler;
    }

    /**
     * Test the 'remove' method
     *
     * Ensure that we can remove already set configuration items. On a missing
     * key, false must be returned. On success, true.
     *
     * @depends testGet
     */
    public function testRemove($handler)
    {
        $this->assertTrue($handler->remove("test.config.write"));
        $this->assertEquals($handler->get("test.config.write"), null);
        $this->assertFalse($handler->remove("test.config.missing"));
    }

    /**
     * Test the 'exists' method
     *
     * Ensure that we can properly check if a configuration item exists, based
     * on its key.
     *
     * @depends testSet
     */
    public function testExists($handler)
    {
        $handler->set("test.config", "test");
        $this->assertTrue($handler->exists("test.config"));
        $this->assertFalse($handler->exists("test.config.missing"));
    }

    /**
     * Test name prepend
     *
     * Ensure that the prepend method is correctly prepending the retrieved
     * name.
     *
     * @depends testSet
     */
    public function testNamePrepend($handler)
    {
        $this->assertEquals(
            ["test.config" => "test"],
            $handler->prependResourceName(["config" => "test"], "test")
        );
    }
}
