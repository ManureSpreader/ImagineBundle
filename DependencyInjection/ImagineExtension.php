<?php

namespace Bundle\ImagineBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Definition,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\DependencyInjection\Container,
    Symfony\Component\DependencyInjection\Parameter,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ImagineExtension extends Extension
{
    protected $resources = array(
        'imagine' => 'imagine.xml'
    );

    public function imagineLoad($config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
        $loader->load($this->resources['imagine']);
        
        $config = (array) $config;
        foreach ($config as $processorName => $config)
        {
            $commands = isset ($config['commands']) ? $config['commands'] : array();
            $processDef = new Definition('Imagine\Processor');
            foreach ($commands as $command)
            {
                if ( ! isset ($command['name'])) {
                    throw new \LogicException('Command doesn\'t have a name, check your app configuration');
                }
                $processDef->addMethodCall($command['name'], (array)$command['arguments']);
            }
            $container->setDefinition('imagine.processor.' . $processorName, $processDef);
        }
    }

    /**
     * Returns the namespace to be used for this extension (XML namespace).
     *
     * @return string The XML namespace
     */
    public function getNamespace()
    {
        return 'http://www.symfony-project.org/schema/dic/symfony';
    }

    /**
     * @return string
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__ . '/../Resources/config/';
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return 'imagine';
    }
}
