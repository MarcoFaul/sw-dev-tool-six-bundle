<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SwDevToolSixExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('sw_dev_tool_six.access_token_ttl', $config['access_token_ttl']);
        $container->setParameter('sw_dev_tool_six.enable_dal_caching', $config['enable_dal_caching']);
        $container->setParameter('sw_dev_tool_six.shopware.skipFirstRunWizardClient', $config['shopware']['skipFirstRunWizardClient']['skipFirstRunWizardClient']);
        $container->setParameter('sw_dev_tool_six.shopware.disableAutoUpdate', !$config['shopware']['disableAutoUpdate']['disableAutoUpdate']);
        $container->setParameter('sw_dev_tool_six.shopware.disableApiAuthRequire', !$config['shopware']['disableApiAuthRequire']['disableApiAuthRequire']);
        $container->setParameter('sw_dev_tool_six.shopware.disableCSRF', !$config['shopware']['disableCSRF']['disableCSRF']);
    }
}
