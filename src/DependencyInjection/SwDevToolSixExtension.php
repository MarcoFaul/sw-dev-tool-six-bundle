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
        $container->setParameter('sw_dev_tool_six.shopware.skip_first_run_wizard_client', $config['shopware']['skip_first_run_wizard_client']);
        $container->setParameter('sw_dev_tool_six.shopware.enable_auto_update', $config['shopware']['enable_auto_update']);
        $container->setParameter('sw_dev_tool_six.shopware.enable_api_auth_require', $config['shopware']['enable_api_auth_require']);
        $container->setParameter('sw_dev_tool_six.shopware.enable_storefront_csrf', $config['shopware']['enable_storefront_csrf']);
    }
}
