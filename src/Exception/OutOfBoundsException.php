<?php
/**
 *
 */

declare(strict_types=1);

namespace Mezzio\Doctrine\Exception;

use OutOfBoundsException as SplOutOfBoundsException;

/**
 * Class OutOfBoundsException
 *
 * @package Mezzio\Doctrine\Exception
 */
class OutOfBoundsException extends SplOutOfBoundsException implements ExceptionInterface
{

}
