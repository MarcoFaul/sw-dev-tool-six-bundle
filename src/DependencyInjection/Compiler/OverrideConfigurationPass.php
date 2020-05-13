<?php declare(strict_types=1);

namespace MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler;

use MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication\ApiAuthenticationListenerExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntitySearcher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

 class OverrideConfigurationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->setParameter('shopware.store.frw', $container->getParameter('sw_dev_tool_six.shopware.skip_first_run_wizard_client'));
        $container->setParameter('shopware.auto_update.disableAutoUpdate', !$container->getParameter('sw_dev_tool_six.shopware.enable_auto_update'));
        $container->setParameter('shopware.api_browser.auth_required', $container->getParameter('sw_dev_tool_six.shopware.enable_api_auth_require'));
        $container->setParameter('storefront.csrf.enabled', $container->getParameter('sw_dev_tool_six.shopware.enable_storefront_csrf'));

        if ($container->getParameter('sw_dev_tool_six.enable_dal_caching') === false) {
            $container->getDefinition('Shopware\Core\Framework\DataAbstractionLayer\Cache\CachedEntitySearcher')
                ->setClass(EntitySearcher::class)
                ->setArgument(0, $container->getDefinition('Doctrine\DBAL\Connection'))
                ->setArgument(1, $container->getDefinition('Shopware\Core\Framework\DataAbstractionLayer\Search\Parser\SqlQueryParser'))
                ->setArgument(2, $container->getDefinition('Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper'))
                ->setArgument(3, $container->getDefinition('Shopware\Core\Framework\DataAbstractionLayer\Search\Term\SearchTermInterpreter'))
                ->setArgument(4, $container->getDefinition('Shopware\Core\Framework\DataAbstractionLayer\Search\Term\EntityScoreQueryBuilder'));
        }


        $container->getDefinition('Shopware\Core\Framework\Api\EventListener\Authentication\ApiAuthenticationListener')
            ->setClass(ApiAuthenticationListenerExtension::class)
            ->addTag('kernel.event_subscriber')
            ->setArgument(0, $container->getDefinition('shopware.api.resource_server'))
            ->setArgument(1, $container->getDefinition('shopware.api.authorization_server'))
            ->setArgument(2, $container->getDefinition('Shopware\Core\Framework\Api\OAuth\UserRepository'))
            ->setArgument(3, $container->getDefinition('Shopware\Core\Framework\Api\OAuth\RefreshTokenRepository'))
            ->setArgument(4, $container->getDefinition('Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory'))
            ->setArgument(5, $container->getDefinition('Shopware\Core\Framework\Routing\RouteScopeRegistry'))
            ->setArgument(6, $container->getParameter('sw_dev_tool_six.access_token_ttl'));

    }
}
