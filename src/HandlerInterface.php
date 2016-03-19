<?php
/**
 * Config Handler Interface
 *
 * Interface for the Config Handlers. All Config handlers must implement this
 * interface to be accepted by the main Config class. Normally the Config
 * Handler already implements this interface.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
namespace SlaxWeb\Config;

interface HandlerInterface
{
    /**
     * Config was loaded and parsed successfuly
     */
    const CONFIG_LOADED = 100;
    /**
     * Config resource could not be found
     */
    const CONFIG_RESOURCE_NOT_FOUND = 101;
    /**
     * Config resource was found, but an error occured while parsing
     */
    const CONFIG_PARSE_ERROR = 102;

    /**
     * Load the config
     *
     * Require the config resource, or load its contents, depending on type of
     * handler that extends this abstract class.
     *
     * @param string $config Path to the config resource
     * @param bool $prependResourceName If the resource name should be prepended
     *                                  to each config key
     * @return int
     */
    public function load(
        string $config,
        bool $prependResourceName = false
    ): int;

    /**
     * Set config item
     *
     * Set a new config item, ro overwrite an existing item.
     *
     * @param string $key Config item key
     * @param mixed $value Config item value
     * @return bool
     */
    public function set(string $key, $value): bool;

    /**
     * Get config item
     *
     * Get a config item from the internal config container. On a missing key,
     * return null.
     *
     * @param string $key Config item key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Remove config item
     *
     * Check if an item with provided key exists, and remove it.
     *
     * @param string $key Config item key
     * @return bool
     */
    public function remove(string $key): bool;

    /**
     * Check config item exists
     *
     * If the config item does not exist, returns false, true otherwise.
     *
     * @param string $key Config item key
     * @return bool
     */
    public function exists(string $key): bool;
}
