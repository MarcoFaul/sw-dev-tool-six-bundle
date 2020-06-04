<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SwDevToolSixExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        # custom
        $container->setParameter('sw_dev_tool_six.access_token_ttl', $config['access_token_ttl']);
        $container->setParameter('sw_dev_tool_six.enable_dal_caching', $config['enable_dal_caching']);

        # twig
        $container->setParameter('sw_dev_tool_six.twig.debug', $config['twig']['debug']);

        # shopware
        $container->setParameter('sw_dev_tool_six.shopware.run_wizard', $config['shopware']['run_wizard']);
        $container->setParameter('sw_dev_tool_six.shopware.auto_update', $config['shopware']['auto_update']);
        $container->setParameter('sw_dev_tool_six.shopware.api_auth_require', $config['shopware']['api_auth_require']);
        $container->setParameter('sw_dev_tool_six.shopware.storefront_csrf', $config['shopware']['storefront_csrf']);
    }
}
