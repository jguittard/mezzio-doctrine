<?php
/**
 *
 */
declare(strict_types=1);

namespace Zend\Expressive\Doctrine\Validator;

/**
 * Class NoObjectExists
 *
 * @package Zend\Expressive\Doctrine\Validator
 */
class NoObjectExists extends ObjectExists
{
    /**
     * Error constants
     */
    const ERROR_OBJECT_FOUND = 'objectFound';
    /**
     * @var array Message templates
     */
    protected $messageTemplates = [
        self::ERROR_OBJECT_FOUND    => "An object matching '%value%' was found",
    ];
    /**
     * {@inheritDoc}
     */
    public function isValid($value)
    {
        $cleanedValue = $this->cleanSearchValue($value);
        $match        = $this->objectRepository->findOneBy($cleanedValue);
        if (is_object($match)) {
            $this->error(self::ERROR_OBJECT_FOUND, $value);
            return false;
        }
        return true;
    }
}