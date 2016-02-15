<?php
/**
 * Config Handler Provider Interface
 *
 * Interface for the Config Handler Providerss. All Config handler providers
 * must implement this interface to be accepted by the main Config class
 * provider.
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

class PhpConfigHandlerProvider implements
    ConfigHandlerProviderInterface,
    \Pimple\ServiceProviderInterface
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
        $container["phpConfigHandler.service"] = function () {
            return new PhpConfigHandlerProvider;
        };
    }
}
