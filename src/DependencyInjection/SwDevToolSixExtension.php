<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\DependencyInjection;


use SebastianBergmann\GlobalState\TestFixture\BlacklistedInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SwDevToolSixExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new Filelocator(__DIR__ . '/../Resources/config/'));
        $loader->load('services.xml');


        $container->setParameter('Shopware\Core\Framework\Api\EventListener\Authentication\ApiAuthenticationListener', 'MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication\ApiAuthenticationListenerExtension');

        $container->registerForAutoconfiguration(BlacklistedInterface::class)->addTag('blub');
        $definition = $container->getDefinition('MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication\ApiAuthenticationListenerExtension');
        $definition->setArgument(6, $config['access_token_ttl']);


//        var_dump($config);die;
    }
}
