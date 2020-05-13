<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication;


use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\ResourceServer;
use Shopware\Core\Framework\Api\EventListener\Authentication\ApiAuthenticationListener;
use Shopware\Core\Framework\Routing\RouteScopeRegistry;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ApiAuthenticationListenerExtension extends ApiAuthenticationListener
{
    /** @var AuthorizationServer */
    private $authorizationServer;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var RefreshTokenRepositoryInterface */
    private $refreshTokenRepository;

    /** @var string */
    private $accessTokenInterval;

    public function __construct(
        ResourceServer $resourceServer,
        AuthorizationServer $authorizationServer,
        UserRepositoryInterface $userRepository,
        RefreshTokenRepositoryInterface $refreshTokenRepository,
        PsrHttpFactory $psrHttpFactory,
        RouteScopeRegistry $routeScopeRegistry,
        string $accessTokenInterval
    )
    {
        parent::__construct($resourceServer, $authorizationServer, $userRepository, $refreshTokenRepository, $psrHttpFactory, $routeScopeRegistry);

        $this->authorizationServer = $authorizationServer;
        $this->userRepository = $userRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->accessTokenInterval = $accessTokenInterval;

    }

    public function setupOAuth(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $tenMinuteInterval = new \DateInterval($this->accessTokenInterval);
        $oneWeekInterval = new \DateInterval($this->accessTokenInterval);

        $passwordGrant = new PasswordGrant($this->userRepository, $this->refreshTokenRepository);
        $passwordGrant->setRefreshTokenTTL($oneWeekInterval);

        $refreshTokenGrant = new RefreshTokenGrant($this->refreshTokenRepository);
        $refreshTokenGrant->setRefreshTokenTTL($oneWeekInterval);

        $this->authorizationServer->enableGrantType($passwordGrant, $tenMinuteInterval);
        $this->authorizationServer->enableGrantType($refreshTokenGrant, $tenMinuteInterval);
        $this->authorizationServer->enableGrantType(new ClientCredentialsGrant(), $tenMinuteInterval);
    }
}
