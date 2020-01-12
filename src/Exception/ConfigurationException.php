<?php
/**
 *
 */

declare(strict_types=1);

namespace Mezzio\Doctrine\Exception;

use Exception as SplException;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class ConfigurationException
 *
 * @package Mezzio\Doctrine\Exception
 */
class ConfigurationException extends SplException implements ContainerExceptionInterface
{

}
