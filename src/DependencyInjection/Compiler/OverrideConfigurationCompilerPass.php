<?php declare(strict_types=1);

namespace MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideConfigurationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->setParameter('shopware.store.frw', $container->getParameter('sw_dev_tool_six.shopware.skipFirstRunWizardClient'));
        $container->setParameter('shopware.auto_update.disableAutoUpdate', $container->getParameter('sw_dev_tool_six.shopware.disableAutoUpdate'));
        $container->setParameter('shopware.api_browser.auth_required', $container->getParameter('sw_dev_tool_six.shopware.disableApiAuthRequire'));
        $container->setParameter('storefront.csrf.enabled', $container->getParameter('sw_dev_tool_six.shopware.disableCSRF'));

    }
}
