<?php
/**
 * XML Config Handler
 *
 * Handles loading of XML config files, and parsing their contents into the
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

class XmlHandler extends Handler
{
    /**
     * XML Library
     *
     * @var \Desperado\XmlBundle\Model\XmlReader
     */
    protected $_xml = null;

    /**
     * XmlHandler constructor
     *
     * Set the Desperado\XmlBundle\Model\XmlReader to the protected property for
     * use later.
     *
     * @param array $resDir Configuration resource locations
     * @param \Desperado\XmlBundle\Model\XmlReader $xml XML reader object
     */
    public function __construct(array $resDir, \Desperado\XmlBundle\Model\XmlReader $xml)
    {
        parent::__construct($resDir);
        $this->_xml = $xml;
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
        if (($configuration = $this->_xml->processConvert($configContents))
            === []) {
            return static::CONFIG_PARSE_ERROR;
        }

        if ($prependResourceName === true) {
            $filename = basename($config, ".xml");
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
