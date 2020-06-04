<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication;

use DateInterval;
use Defuse\Crypto\Key;
use League\Event\EmitterAwareInterface;
use League\Event\EmitterAwareTrait;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\GrantTypeInterface;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use League\OAuth2\Server\ResponseTypes\AbstractResponseType;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizationServer implements EmitterAwareInterface
{
    use EmitterAwareTrait;

    /** @var GrantTypeInterface[] */
    protected $enabledGrantTypes = [];

    /** @var DateInterval[] */
    protected $grantTypeAccessTokenTTL = [];

    /** @var CryptKey */
    protected $privateKey;

    /** @var CryptKey */
    protected $publicKey;

    /** @var BearerTokenResponse|ResponseTypeInterface */
    protected $responseType;

    /** @var ClientRepositoryInterface */
    private $clientRepository;

    /** @var AccessTokenRepositoryInterface */
    private $accessTokenRepository;

    /** @var ScopeRepositoryInterface */
    private $scopeRepository;

    /** @var Key|string */
    private $encryptionKey;

    /** @var string */
    private $defaultScope = '';

    /** @var string */
    private $accessTokenInterval;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var RefreshTokenRepositoryInterface */
    private $refreshTokenRepository;

    /**
     * New server instance.
     *
     * @param ClientRepositoryInterface $clientRepository
     * @param AccessTokenRepositoryInterface $accessTokenRepository
     * @param ScopeRepositoryInterface $scopeRepository
     * @param CryptKey|string $privateKey
     * @param string|Key $encryptionKey
     * @param string $accessTokenInterval
     * @param UserRepositoryInterface $userRepository
     * @param RefreshTokenRepositoryInterface $refreshTokenRepository
     * @param null|ResponseTypeInterface $responseType
     */
    public function __construct(
        ClientRepositoryInterface $clientRepository,
        AccessTokenRepositoryInterface $accessTokenRepository,
        ScopeRepositoryInterface $scopeRepository,
        $privateKey,
        $encryptionKey,
        string $accessTokenInterval,
        UserRepositoryInterface $userRepository,
        RefreshTokenRepositoryInterface $refreshTokenRepository,
        ResponseTypeInterface $responseType = null
    ) {
        $this->clientRepository = $clientRepository;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->scopeRepository = $scopeRepository;

        if ($privateKey instanceof CryptKey === false) {
            $privateKey = new CryptKey($privateKey);
        }

        $this->privateKey = $privateKey;
        $this->encryptionKey = $encryptionKey;

        if ($responseType === null) {
            $responseType = new BearerTokenResponse();
        } else {
            $responseType = clone $responseType;
        }

        $this->responseType = $responseType;
        $this->userRepository = $userRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->accessTokenInterval = $accessTokenInterval;
    }

    /**
     * Enable a grant type on the server.
     *
     * @param GrantTypeInterface $grantType
     * @param null|DateInterval  $accessTokenTTL
     */
    public function enableGrantType(GrantTypeInterface $grantType, DateInterval $accessTokenTTL = null)
    {
        # set our custom token interval to all grandTypes
        $accessTokenTTL = new DateInterval($this->accessTokenInterval);

        $grantType->setAccessTokenRepository($this->accessTokenRepository);
        $grantType->setClientRepository($this->clientRepository);
        $grantType->setScopeRepository($this->scopeRepository);
        $grantType->setDefaultScope($this->defaultScope);
        $grantType->setPrivateKey($this->privateKey);
        $grantType->setEmitter($this->getEmitter());
        $grantType->setEncryptionKey($this->encryptionKey);

        $this->enabledGrantTypes[$grantType->getIdentifier()] = $grantType;
        $this->grantTypeAccessTokenTTL[$grantType->getIdentifier()] = $accessTokenTTL;
    }

    /**
     * Validate an authorization request
     *
     * @param ServerRequestInterface $request
     *
     * @throws OAuthServerException
     *
     * @return AuthorizationRequest
     */
    public function validateAuthorizationRequest(ServerRequestInterface $request)
    {
        foreach ($this->enabledGrantTypes as $grantType) {
            if ($grantType->canRespondToAuthorizationRequest($request)) {
                return $grantType->validateAuthorizationRequest($request);
            }
        }

        throw OAuthServerException::unsupportedGrantType();
    }

    /**
     * Complete an authorization request
     *
     * @param AuthorizationRequest $authRequest
     * @param ResponseInterface    $response
     *
     * @return ResponseInterface
     */
    public function completeAuthorizationRequest(AuthorizationRequest $authRequest, ResponseInterface $response)
    {
        return $this->enabledGrantTypes[$authRequest->getGrantTypeId()]
            ->completeAuthorizationRequest($authRequest)
            ->generateHttpResponse($response);
    }

    /**
     * Return an access token response.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @throws OAuthServerException
     *
     * @return ResponseInterface
     */
    public function respondToAccessTokenRequest(ServerRequestInterface $request, ResponseInterface $response)
    {
        # same logic as within the api authentication listener
        if(\count($this->enabledGrantTypes) === 0) {
            $tenMinuteInterval = new \DateInterval($this->accessTokenInterval);
            $oneWeekInterval = new \DateInterval($this->accessTokenInterval);

            $passwordGrant = new PasswordGrant($this->userRepository, $this->refreshTokenRepository);
            $passwordGrant->setRefreshTokenTTL($oneWeekInterval);

            $refreshTokenGrant = new RefreshTokenGrant($this->refreshTokenRepository);
            $refreshTokenGrant->setRefreshTokenTTL($oneWeekInterval);

            $this->enableGrantType($passwordGrant, $tenMinuteInterval);
            $this->enableGrantType($refreshTokenGrant, $tenMinuteInterval);
            $this->enableGrantType(new ClientCredentialsGrant(), $tenMinuteInterval);
        }

        foreach ($this->enabledGrantTypes as $grantType) {
            if (!$grantType->canRespondToAccessTokenRequest($request)) {
                continue;
            }
            $tokenResponse = $grantType->respondToAccessTokenRequest(
                $request,
                $this->getResponseType(),
                $this->grantTypeAccessTokenTTL[$grantType->getIdentifier()]
            );

            if ($tokenResponse instanceof ResponseTypeInterface) {
                return $tokenResponse->generateHttpResponse($response);
            }
        }

        throw OAuthServerException::unsupportedGrantType();
    }

    /**
     * Get the token type that grants will return in the HTTP response.
     *
     * @return ResponseTypeInterface
     */
    protected function getResponseType()
    {
        $responseType = clone $this->responseType;

        if ($responseType instanceof AbstractResponseType) {
            $responseType->setPrivateKey($this->privateKey);
        }

        $responseType->setEncryptionKey($this->encryptionKey);

        return $responseType;
    }

    /**
     * Set the default scope for the authorization server.
     *
     * @param string $defaultScope
     */
    public function setDefaultScope($defaultScope)
    {
        $this->defaultScope = $defaultScope;
    }
}
