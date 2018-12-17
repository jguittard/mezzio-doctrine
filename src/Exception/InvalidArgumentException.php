<?php
/**
 *
 */

declare(strict_types=1);

namespace Zend\Expressive\Doctrine\Exception;

use InvalidArgumentException as SplInvalidArgumentException;

/**
 * Class InvalidArgumentException
 *
 * @package Zend\Expressive\Doctrine\Exception
 */
class InvalidArgumentException extends SplInvalidArgumentException implements ExceptionInterface
{

}
