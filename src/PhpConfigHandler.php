<?php
/**
 * PHP Config Handler
 *
 * Handles loading of PHP config files, and parsing their contents into the
 * config array.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
namespace SlaxWeb;

class PhpConfigHandler implements ConfigHandlerInterface
{
    /**
     * Configuration values
     *
     * @var array
     */
    protected $_configValues = [];

    /**
     * Load the Config File
     *
     * Check if the file exists, load it, and parse the containing array into
     * the the internal container array. Return 'CONFIG_LOADED' constant on
     * success. If the file is not found, return 'CONFIG_RESOURCE_NOT_FOUND'
     * constant, and 'CONFIG_PARSE_ERROR' if the contants could not have been
     * parsed.
     *
     * @param string $config Path to the config resource
     * @return int
     */
    public function load($config)
    {
        // check file exists
        if (file_exists($config) === false) {
            return static::CONFIG_RESOURCE_NOT_FOUND;
        }

        require_once $config;

        if (isset($configuration) === false) {
            return static::CONFIG_PARSE_ERROR;
        }

        $this->_configValues = array_merge($this->_configValues, $configuration);
        return static::CONFIG_LOADED;
    }

    /**
     * Set config item
     *
     * Set a new config item, ro overwrite an existing item.
     *
     * @param string $key Config item key
     * @param mixed $value Config item value
     * @return bool
     */
    public function set($key, $value)
    {
        if (is_string($key) === false) {
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
    public function get($key)
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
    public function remove($key)
    {
        if (isset($this->_configValues[$key]) === false) {
            return false;
        }

        unset($this->_configValues[$key]);
        return true;
    }
}
