<?php
/**
 * Config Handler Abstract Class
 *
 * Provides functionality for retrieval, setting, and removing of config items,
 * as well as defines an abstract method for loading of config items from a
 * resource, and parsing them to the internal container.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
namespace SlaxWeb;

abstract class ConfigHandler implements ConfigHandlerInterface
{
    /**
     * Configuration values
     *
     * @var array
     */
    protected $_configValues = [];

    /**
     * Load the config
     *
     * Require the config resource, or load its contents, depending on type of
     * handler that extends this abstract class.
     *
     * @param string $config Path to the config resource
     * @return int
     */
    abstract public function load(string $config): int;

    /**
     * Set config item
     *
     * Set a new config item, ro overwrite an existing item.
     *
     * @param string $key Config item key
     * @param mixed $value Config item value
     * @return bool
     */
    public function set(string $key, $value): bool
    {
        if (is_string($key) === false || $key === "") {
            return false;
        }

        $this->_configValues[$key] = $value;
        return true;
    }

    /**
     * Get config item
     *
     * Get a config item from the internal config container. On a missing key,
     * return null.
     *
     * @param string $key Config item key
     * @return mixed
     */
    public function get(string $key)
    {
        return isset($this->_configValues[$key])
            ? $this->_configValues[$key]
            : null;
    }

    /**
     * Remove config item
     *
     * Check if an item with provided key exists, and remove it.
     *
     * @param string $key Config item key
     * @return bool
     */
    public function remove(string $key): bool
    {
        if (isset($this->_configValues[$key]) === false) {
            return false;
        }

        unset($this->_configValues[$key]);
        return true;
    }

    /**
     * Check config item exists
     *
     * If the config item does not exist, returns false, true otherwise.
     *
     * @param string $key Config item key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return isset($this->_configValues[$key]);
    }
}
