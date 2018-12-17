<?php
/**
 *
 */

declare(strict_types=1);

namespace Zend\Expressive\Doctrine\Exception;

use Exception as SplException;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class ConfigurationException
 *
 * @package Zend\Expressive\Doctrine\Exception
 */
class ConfigurationException extends SplException implements ContainerExceptionInterface
{

}
