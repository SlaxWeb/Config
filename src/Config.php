<?php
/**
 * Config Class
 *
 * The Config class needs to communicate with the Config Handler class of the
 * ConfigHandlerInterface, and raise appropriate Exceptions on errors.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
namespace SlaxWeb\Config;

class Config implements \ArrayAccess
{
    /**
     * Config Handler
     *
     * @var \SlaxWeb\ConfigHandlerInterface
     */
    protected $_handler = null;

    /**
     * Config resource location
     *
     * @var string
     */
    protected $_resLocation = "";

    /**
     * Class constructor
     *
     * Set the injected config handler to the internal protected property.
     *
     * @param \SlaxWeb\ConfigurationHandlerInterface $handler Configuration
     *                                                        handler
     * @param string $resLocation Configuration resource location
     * @return void
     */
    public function __construct(
        ConfigHandlerInterface $handler,
        string $resLocation
    ) {
        $this->_handler = $handler;
        $this->_resLocation = rtrim($resLocation, "/") . "/";
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
     * @return void
     */
    public function load(string $resourceName)
    {
        switch ($this->_handler->load($this->_resLocation . $resourceName)) {
            case ConfigHandlerInterface::CONFIG_PARSE_ERROR:
                throw new Exception\ConfigParseException(
                    "Error parsing '{$this->_resLocation}{$resourceName}' "
                    . "config file"
                );
                break;
            case ConfigHandlerInterface::CONFIG_RESOURCE_NOT_FOUND:
                throw new Exception\ConfigResourceNotFoundException(
                    "Error '{$this->_resLocation}{$resourceName}' config file "
                    . "not found"
                );
                break;
        }
    }
}
