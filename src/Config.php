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
namespace SlaxWeb;

class Config implements \ArrayAccess
{
    /**
     * Config Handler
     *
     * @var \SlaxWeb\ConfigHandlerInterface
     */
    protected $_handler = null;

    /**
     * Class constructor
     *
     * Set the injected config handler to the internal protected property.
     *
     * @return void
     */
    public function __construct(ConfigHandlerInterface $handler)
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
    public function offsetExists($offset)
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

    }
}