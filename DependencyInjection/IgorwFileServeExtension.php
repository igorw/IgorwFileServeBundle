<?php

namespace Igorw\FileServeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class IgorwFileServeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setAlias('igorw_file_serve.response_factory', sprintf('igorw_file_serve.response_factory.%s', $config['factory']));
        $container->setParameter('igorw_file_serve.base_dir', $config['base_dir']);
        $container->setParameter('igorw_file_serve.is_absolute_path', $config['is_absolute_path']);
    }
}
