<?php
/**
 * PHP Config Handler Interface
 *
 * Defines methods required in each of the config handlers that implement this
 * interface.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
namespace SlaxWeb;

interface ConfigHandlerInterface
{
    /**
     * Config was loaded and parsed successfuly
     */
    const CONFIG_LOADED = 100;
    /**
     * Config resource could not be found
     */
    const CONFIG_RESOURCE_NOT_FOUND = 101;
    /**
     * Config resource was found, but an error occured while parsing
     */
    const CONFIG_PARSE_ERROR = 102;

    /**
     * Load the config
     *
     * Require the config resource, or load its contents, depending on type of
     * handler that implements the interface.
     *
     * @param string $config Path to the config resource
     * @return int
     */
    public function load($config);

    /**
     * Set config item
     *
     * Set a new config item, ro overwrite an existing item.
     *
     * @param string $key Config item key
     * @param mixed $value Config item value
     * @return bool
     */
    public function set($key, $value);
}
