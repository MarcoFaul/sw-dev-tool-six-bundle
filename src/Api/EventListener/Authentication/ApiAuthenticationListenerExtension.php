<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication;


use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\ResourceServer;
use Shopware\Core\Framework\Routing\ApiContextRouteScopeDependant;
use Shopware\Core\Framework\Routing\KernelListenerPriorities;
use Shopware\Core\Framework\Routing\RouteScopeCheckTrait;
use Shopware\Core\Framework\Routing\RouteScopeRegistry;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiAuthenticationListenerExtension implements EventSubscriberInterface
{
    use RouteScopeCheckTrait;

    /** @var ResourceServer */
    private $resourceServer;

    /** @var AuthorizationServer  */
    private $authorizationServer;

    /** @var UserRepositoryInterface  */
    private $userRepository;

    /** @var RefreshTokenRepositoryInterface */
    private $refreshTokenRepository;

    /** @var PsrHttpFactory  */
    private $psrHttpFactory;

    /** @var RouteScopeRegistry  */
    private $routeScopeRegistry;

    public function __construct(
        ResourceServer $resourceServer,
        AuthorizationServer $authorizationServer,
        UserRepositoryInterface $userRepository,
        RefreshTokenRepositoryInterface $refreshTokenRepository,
        PsrHttpFactory $psrHttpFactory,
        RouteScopeRegistry $routeScopeRegistry
    ) {
        $this->resourceServer = $resourceServer;
        $this->authorizationServer = $authorizationServer;
        $this->userRepository = $userRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->psrHttpFactory = $psrHttpFactory;
        $this->routeScopeRegistry = $routeScopeRegistry;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['setupOAuth', 128],
            ],
            KernelEvents::CONTROLLER => [
                ['validateRequest', KernelListenerPriorities::KERNEL_CONTROLLER_EVENT_PRIORITY_AUTH_VALIDATE],
            ],
        ];
    }

    /**
     * @param RequestEvent $event
     * @codeCoverageIgnore
     */
    public function setupOAuth(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        # these values should be overridden anyways
        $tenMinuteInterval = new \DateInterval('PT10M');
        $oneWeekInterval = new \DateInterval('P1W');

        $passwordGrant = new PasswordGrant($this->userRepository, $this->refreshTokenRepository);
        $passwordGrant->setRefreshTokenTTL($oneWeekInterval);

        $refreshTokenGrant = new RefreshTokenGrant($this->refreshTokenRepository);
        $refreshTokenGrant->setRefreshTokenTTL($oneWeekInterval);

        $this->authorizationServer->enableGrantType($passwordGrant, $tenMinuteInterval);
        $this->authorizationServer->enableGrantType($refreshTokenGrant, $tenMinuteInterval);
        $this->authorizationServer->enableGrantType(new ClientCredentialsGrant(), $tenMinuteInterval);
    }

    /**
     * @param ControllerEvent $event
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     * @codeCoverageIgnore
     */
    public function validateRequest(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->attributes->get('auth_required', true)) {
            return;
        }

        if (!$this->isRequestScoped($request, ApiContextRouteScopeDependant::class)) {
            return;
        }

        $psr7Request = $this->psrHttpFactory->createRequest($event->getRequest());
        $psr7Request = $this->resourceServer->validateAuthenticatedRequest($psr7Request);

        $request->attributes->add($psr7Request->getAttributes());
    }

    /**
     * @return RouteScopeRegistry
     * @codeCoverageIgnore
     */
    protected function getScopeRegistry(): RouteScopeRegistry
    {
        return $this->routeScopeRegistry;
    }
}
