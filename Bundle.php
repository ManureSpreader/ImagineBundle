<?php

namespace Bundle\ImagineBundle;

use Symfony\Foundation\Bundle\Bundle as BaseBundle,
    Symfony\Components\DependencyInjection\ContainerInterface,
    Symfony\Components\DependencyInjection\Loader\Loader,
    Bundle\ImagineBundle\DependencyInjection\ImagineExtension;

class Bundle extends BaseBundle
{
    public function buildContainer(ContainerInterface $container)
    {
        Loader::registerExtension(new ImagineExtension());
    }
}
