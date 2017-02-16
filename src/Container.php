<?php
/**
 * Config Class
 *
 * The Container class needs to communicate with the Config Handler, and raise appropriate
 * Exceptions on errors.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.2
 */
namespace SlaxWeb\Config;

class Container implements \ArrayAccess
{
    /**
     * Config handler constants
     */
    const PHP_CONFIG_HANDLER = "php";
    const XML_CONFIG_HANDLER = "xml";
    const YAML_CONFIG_HANDLER = "yaml";

    /**
     * Config Handler
     *
     * @var \SlaxWeb\Config\Handler
     */
    protected $_handler = null;

    /**
     * Class constructor
     *
     * Set the injected config handler to the internal protected property.
     *
     * @param \SlaxWeb\Config\Handler $handler Configuration handler
     */
    public function __construct(Handler $handler)
    {
        $this->_handler = $handler;
    }

    /**
     * Check offset exists
     *
     * Check in the handler that the offset exists, and return true if it does,
     * false otherwise.
     *
     * @param string $offset Configuration key name
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->_handler->exists($offset);
    }

    /**
     * Get offset value
     *
     * Get the value from the handler for the passed in offset. Return the value
     * provided by the handler.
     *
     * @param string $offset Configuration key name
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->_handler->get($offset);
    }

    /**
     * Set offset value
     *
     * Set the value to the handler for the passed in offset and value.
     *
     * @param string $offset Configuration key name
     * @param mixed $value Configuration value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->_handler->set($offset, $value);
    }

    /**
     * Unset offset
     *
     * Unset the configuration item with the offset key.
     *
     * @param string $offset Configuration key name
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ($this->_handler->remove($offset) === false) {
            throw new Exception\MissingKeyException(
                "The key does not exists, and can not be unset"
            );
        }
    }

    /**
     * Load config resource
     *
     * Combine the received configuration resource name with the
     * '_configLocation' protected property, and pass it to the config handler.
     *
     * @param string $resourceName Name of the configuration resource
     * @param bool $prependResourceName If the resource name should be prepended
     *                                  to each config key
     * @return void
     */
    public function load(
        string $resourceName,
        bool $prependResourceName = false
    ) {
        switch ($this->_handler->load($resourceName, $prependResourceName)) {
            case Handler::CONFIG_PARSE_ERROR:
                throw new Exception\ConfigParseException(
                    "Error parsing '{$resourceName}' configuration resource"
                );
            case Handler::CONFIG_RESOURCE_NOT_FOUND:
                throw new Exception\ConfigResourceNotFoundException(
                    "Error '{$resourceName}' configuration resource not found"
                );
        }
    }

    /**
     * Add Resource Location
     *
     * Add an additional configuration resource location.
     *
     * @param string $dir Additional resource location
     * @return void
     */
    public function addResDir(string $dir)
    {
        $this->_handler->addResDir($dir);
    }
}
