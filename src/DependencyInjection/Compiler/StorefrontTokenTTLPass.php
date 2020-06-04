<?php declare(strict_types=1);

namespace MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler;

use MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication\ApiAuthenticationListenerExtension;
use MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication\AuthController;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StorefrontTokenTTLPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        # shopware auth
        $container->getDefinition('Shopware\Core\Framework\Api\EventListener\Authentication\ApiAuthenticationListener')
            ->setClass(ApiAuthenticationListenerExtension::class)
            ->addTag('kernel.event_subscriber')
            ->setArgument(0, $container->getDefinition('shopware.api.resource_server'))
            ->setArgument(1, $container->getDefinition('marco_faul.api.authorization_server'))
            ->setArgument(2, $container->getDefinition('Shopware\Core\Framework\Api\OAuth\UserRepository'))
            ->setArgument(3, $container->getDefinition('Shopware\Core\Framework\Api\OAuth\RefreshTokenRepository'))
            ->setArgument(4, $container->getDefinition('Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory'))
            ->setArgument(5, $container->getDefinition('Shopware\Core\Framework\Routing\RouteScopeRegistry'))
            ->setArgument(6, $container->getParameter('sw_dev_tool_six.access_token_ttl'));

        $container->getDefinition('Shopware\Core\Framework\Api\Controller\AuthController')
            ->setClass(AuthController::class)
            ->setArgument(0, $container->getDefinition('marco_faul.api.authorization_server'))
            ->setArgument(1, $container->getDefinition('Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory'));
    }
}
