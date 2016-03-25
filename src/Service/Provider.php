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

class Provider implements \Pimple\ServiceProviderInterface
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
            return new \SlaxWeb\Config\Container(
                $cont["configHandler.service"]
            );
        };

        $container["configHandler.service"] = function (Container $cont) {
            switch ($cont["configHandler"]) {
                case \SlaxWeb\Config\Container::PHP_CONFIG_HANDLER:
                    return new \SlaxWeb\Config\PhpHandler(
                        $cont["configResourceLocation"]
                    );
                    break;
                case \SlaxWeb\Config\Container::XML_CONFIG_HANDLER:
                    return new \SlaxWeb\Config\XmlHandler(

                        $cont["configResourceLocation"]
                        new \Desperado\XmlBundle\Model\XmlReader
                    );
                    break;
                case \SlaxWeb\Config\Container::YAML_CONFIG_HANDLER:
                    return new \SlaxWeb\Config\YamlHandler(
                        $cont["configResourceLocation"]
                    );
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
