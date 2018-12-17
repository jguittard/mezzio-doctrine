<?php
/**
 *
 */
declare(strict_types=1);

namespace Zend\Expressive\Doctrine\Exception;

use DomainException as SplDomainException;

/**
 * Class DomainException
 *
 * @package Zend\Expressive\Doctrine\Exception
 */
class DomainException extends SplDomainException implements ExceptionInterface
{

}
