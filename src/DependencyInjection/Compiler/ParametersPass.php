<?php declare(strict_types=1);

namespace MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ParametersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->setParameter('twig.debug', $container->getParameter('sw_dev_tool_six.twig.debug'));
        $container->setParameter('shopware.store.frw', $container->getParameter('sw_dev_tool_six.shopware.run_wizard'));
        $container->setParameter('shopware.auto_update.disableAutoUpdate', !$container->getParameter('sw_dev_tool_six.shopware.auto_update'));
        $container->setParameter('shopware.api_browser.auth_required', $container->getParameter('sw_dev_tool_six.shopware.api_auth_require'));
        $container->setParameter('storefront.csrf.enabled', $container->getParameter('sw_dev_tool_six.shopware.storefront_csrf'));
    }
}
