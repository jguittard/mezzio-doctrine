<?php
/**
 *
 */
declare(strict_types=1);

namespace Mezzio\Doctrine\Exception;

use DomainException as SplDomainException;

/**
 * Class DomainException
 *
 * @package Mezzio\Doctrine\Exception
 */
class DomainException extends SplDomainException implements ExceptionInterface
{

}
