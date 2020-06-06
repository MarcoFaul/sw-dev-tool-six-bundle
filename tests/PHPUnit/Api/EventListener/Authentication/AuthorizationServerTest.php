<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests\PHPUnit\Api\EventListener\Authentication;


use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication\AuthorizationServer;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class AuthorizationServerTest extends TestCase
{
    /**
     * @test
     */
    public function respondToAccessTokenRequest()
    {
        $clientRepository = $this->getMockBuilder(ClientRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $accessTokenRepository = $this->getMockBuilder(AccessTokenRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $scopeRepository = $this->getMockBuilder(ScopeRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userRepository = $this->getMockBuilder(UserRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $refreshTokenRepository = $this->getMockBuilder(RefreshTokenRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $authServer = new AuthorizationServer($clientRepository, $accessTokenRepository, $scopeRepository, new CryptKey(__DIR__ . '/../../../../Stubs/public.pem', null, false), '2', 'P1W', $userRepository, $refreshTokenRepository);

        $serverRequest = new ServerRequest('GET', 'BLUB');
        $response = new Response();


        $this->expectException(OAuthServerException::class);
        $authServer->respondToAccessTokenRequest($serverRequest, $response);
    }
}
