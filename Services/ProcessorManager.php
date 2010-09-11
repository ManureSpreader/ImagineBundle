<?php

namespace Bundle\ImagineBundle\Services;

use Symfony\Component\DependencyInjection\Container;

class ProcessorManager
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getProcessor($name)
    {
        $serviceName = 'imagine.processor.' . $name;
        return $this->container->get($serviceName);
    }
}
