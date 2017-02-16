<?php
/**
 * Config Component Factory
 *
 * The Factory takes care of all the Config class dependencies, instantiates
 * it, and returns its object.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.2
 */
namespace SlaxWeb\Config;

class Factory
{
    /**
     * Instantiate Config class
     *
     * Instantiate the configuration handler, pass it to the Config class when
     * instantiating it, and return its object.
     * @param string $handlerType Type of handler to use
     * @param array $resLocations Locations of configuration resource
     * @return \SlaxWeb\Config\Container
     */
    public static function init(string $handlerType, array $resLocations): Container
    {
        switch (strtolower($handlerType)) {
            case Config::PHP_CONFIG_HANDLER:
                $handler = new PhpHandler($resLocations);
                break;
            case Config::XML_CONFIG_HANDLER:
                $xml = new \Desperado\XmlBundle\Model\XmlReader;
                $handler = new XmlHandler($resLocations, $xml);
                break;
            case Config::YAML_CONFIG_HANDLER:
                $handler = new YamlHandler($resLocations);
                break;
            default:
                throw new Exception\InvalidHandlerTypeException(
                    "Handler type must be one of ['php', 'xml', 'yaml']"
                );
        }

        return new Container($handler);
    }
}
