<?php
/**
 *
 */

declare(strict_types=1);

namespace Mezzio\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Trait ProvidesObjectManager
 *
 * @package Mezzio\Doctrine
 */
trait ProvidesObjectManager
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Set the object manager
     *
     * @param ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Get the object manager
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
}
