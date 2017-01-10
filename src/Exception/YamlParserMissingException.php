<?php
/**
 * Yaml Missing Exception
 *
 * Thrown when the symfony/yaml package is not installed or is in the incorrect
 * version.
 *
 * @package   SlaxWeb\Config
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.2
 */
namespace SlaxWeb\Config\Exception;

class YamlParserMissingException extends \Exception
{
}
