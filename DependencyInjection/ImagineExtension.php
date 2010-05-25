<?php

namespace Bundle\ImagineBundle\DependencyInjection;

use Symfony\Components\DependencyInjection\Loader\LoaderExtension,
    Symfony\Components\DependencyInjection\Definition,
    Symfony\Components\DependencyInjection\Reference,
    Symfony\Components\DependencyInjection\Container,
    Symfony\Components\DependencyInjection\Parameter,
    Symfony\Components\DependencyInjection\BuilderConfiguration,
    Symfony\Components\DependencyInjection\Loader\XmlFileLoader;

class ImagineExtension extends LoaderExtension
{
    protected $resources = array(
        'imagine' => 'imagine.xml'
    );

    protected $commands = array(
        'crop'   => 'Imagine\Processor\Crop',
        'resize' => 'Imagine\Processor\ResizeCommand',
        'delete' => 'Imagine\Processor\DeleteCommand',
        'save'   => 'Imagine\Processor\SaveCommand',
    );

    public function imagineLoad($config)
    {
        $configuration = new BuilderConfiguration();
        $loader = new XmlFileLoader(__DIR__.'/../Resources/config');
        $configuration->merge($loader->load($this->resources['imagine']));
        
        $config = (array) $config;
        foreach ($config as $processorName => $config)
        {
            $commands = isset ($config['commands']) ? $config['commands'] : array();
            $processDef = new Definition('Imagine\ImageProcessor');
            foreach ($commands as $params)
            {
                if ( ! isset ($params['name'])) {
                    throw new \LogicException('Command doesn\'t have a name, check you app configuration');
                }
                $name = $params['name'];
                $args = isset ($params['arguments']) ? (array) $params['arguments'] : array();
                $args = (isset ($args)) ? (array) $args : array();
                $commandDef = new Definition(
                    new Parameter('imagine.command.' . $name . '.class')
                , $args);
                $serviceId = 'imagine.processor.' . $processorName . '.command.' . $name;
                $configuration->setDefinition($serviceId, $commandDef);
                $processDef->addMethodCall('addCommand', array(
                    new Reference($serviceId, Container::EXCEPTION_ON_INVALID_REFERENCE)
                ));
            }
            $configuration->setDefinition('imagine.processor.' . $processorName, $processDef);
        }
        return $configuration;
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
