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
 * @version   0.2
 */
namespace SlaxWeb\Config;

class PhpHandler extends Handler
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
     * @param bool $prependResourceName If the resource name should be prepended
     *                                  to each config key
     * @return int
     */
    public function load(string $config, bool $prependResourceName = false): int
    {
        // obtain absolute path to configuration resource
        if (($config = $this->_getAbsPath($config)) === "") {
            return static::CONFIG_RESOURCE_NOT_FOUND;
        }

        require $config;

        if (isset($configuration) === false) {
            return static::CONFIG_PARSE_ERROR;
        }

        if ($prependResourceName === true) {
            $filename = basename($config, ".php");
            $configuration = $this->prependResourceName(
                $configuration,
                $filename
            );
        }

        $this->_configValues = array_merge_recursive(
            $this->_configValues,
            $configuration
        );

        return static::CONFIG_LOADED;
    }
}
