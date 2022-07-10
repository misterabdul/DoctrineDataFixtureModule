<?php

namespace DoctrineDataFixtureTest\TestAsset\Fixtures\HasSL;

use Doctrine\Common\DataFixtures\FixtureInterface;

class FixtureA implements FixtureInterface
{
    /**
     * @var \Laminas\ServiceManager\ServiceLocatorInterface|null
     */
    protected $serviceLocator = null;

    /**
     * @param  \Doctrine\Persistence\ObjectManager  $manager
     */
    public function load($manager)
    {
    }

    /**
     * Set service locator
     *
     * @param  \Laminas\ServiceManager\ServiceLocatorInterface  $serviceLocator
     * @return mixed
     */
    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return \Laminas\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
