<?php
/**
 * Config Provider
 *
 * Register the correct config handler based on the container property
 * 'configHandler', and the Config service itself.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
namespace SlaxWeb\Config\Service;

use Pimple\Container;

class ConfigProvider implements \Pimple\ServiceProviderInterface
{
    /**
     * Register provider
     *
     * Register is called by the container, when the provider gets registered.
     *
     * @param \Pimple\Container $container Dependency Injection Container
     * @return void
     */
    public function register(Container $container)
    {
        $container["config.service"] = function (Container $cont) {
            return new \SlaxWeb\Config\Config(
                $cont["configHandler.service"],
                $cont["configResourceLocation"]
            );
        };

        $container["configHandler.service"] = function (Container $cont) {
            switch ($cont["configHandler"]) {
                case \SlaxWeb\Config\Config::PHP_CONFIG_HANDLER:
                    return new \SlaxWeb\Config\PhpConfigHandler;
                    break;
                case \SlaxWeb\Config\Config::XML_CONFIG_HANDLER:
                    $xml = new \Desperado\XmlBundle\Model\XmlReader;
                    return new \SlaxWeb\Config\XmlConfigHandler($xml);
                    break;
                case \SlaxWeb\Config\Config::YAML_CONFIG_HANDLER:
                    return new \SlaxWeb\Config\YamlConfigHandler;
                    break;
                default:
                    throw new \SlaxWeb\Config\Exception\InvalidHandlerTypeException(
                        "Handler type property 'configHandler' must be one of "
                        . "['php', 'xml', 'yaml']"
                    );
            }
        };
    }
}
