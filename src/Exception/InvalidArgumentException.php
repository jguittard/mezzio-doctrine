<?php
/**
 *
 */

declare(strict_types=1);

namespace Mezzio\Doctrine\Exception;

use InvalidArgumentException as SplInvalidArgumentException;

/**
 * Class InvalidArgumentException
 *
 * @package Mezzio\Doctrine\Exception
 */
class InvalidArgumentException extends SplInvalidArgumentException implements ExceptionInterface
{

}
