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

class Config
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
}
