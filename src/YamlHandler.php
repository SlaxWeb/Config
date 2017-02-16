<?php
/**
 * Yaml Config Handler
 *
 * Handles loading of Yaml config files, and parsing their contents into the
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

class YamlHandler extends Handler
{
    /**
     * YamlHandler constructor
     *
     * Check that the Symfony\Component\Yaml\Yaml class exists, if it does not
     * throw an exception.
     *
     * @param array $resDir Configuration resource locations
     */
    public function __construct(array $resDir)
    {
        parent::__construct($resDir);

        if (class_exists("\\Symfony\\Component\\Yaml\\Yaml") === false) {
            throw new Exception\YamlParserMissingException(
                "Please ensure that you have installed 3.0.x version of the "
                . "package 'symfony/yaml'"
            );
        }
    }

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

        $configContents = file_get_contents($config);
        try {
            $configuration = \Symfony\Component\Yaml\Yaml::parse(
                $configContents
            );
        } catch (\Symfony\Component\Yaml\Exception\ParseException $e) {
            return static::CONFIG_PARSE_ERROR;
        }

        if ($prependResourceName === true) {
            $filename = preg_replace("~\.ya?ml$~", "", basename($config));
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
