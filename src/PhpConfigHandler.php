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
namespace SlaxWeb\Config;

class PhpConfigHandler extends ConfigHandler
{
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
    public function load(string $config): int
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
}
