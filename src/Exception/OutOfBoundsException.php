<?php
/**
 *
 */

declare(strict_types=1);

namespace Zend\Expressive\Doctrine\Exception;

use OutOfBoundsException as SplOutOfBoundsException;

/**
 * Class OutOfBoundsException
 *
 * @package Zend\Expressive\Doctrine\Exception
 */
class OutOfBoundsException extends SplOutOfBoundsException implements ExceptionInterface
{

}
