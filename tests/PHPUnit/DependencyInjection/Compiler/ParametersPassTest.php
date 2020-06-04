<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests\PHPUnit\DependencyInjection\Compiler;


use Doctrine\DBAL\Connection;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\ResourceServer;
use MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler\ParametersPass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Api\EventListener\Authentication\ApiAuthenticationListener;
use Shopware\Core\Framework\Api\OAuth\RefreshTokenRepository;
use Shopware\Core\Framework\Api\OAuth\UserRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntitySearcher;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Parser\SqlQueryParser;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Term\EntityScoreQueryBuilder;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Term\SearchTermInterpreter;
use Shopware\Core\Framework\Routing\RouteScopeRegistry;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ParametersPassTest extends TestCase
{
    /** @var ContainerBuilder */
    private $containerBuilder;

    /** @var ParametersPass */
    private $pass;

    /**
     * initialize all services once
     */
    public function setUp(): void
    {
        $this->pass = new ParametersPass();
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->setParameter('sw_dev_tool_six.shopware.run_wizard', true);
        $this->containerBuilder->setParameter('sw_dev_tool_six.shopware.auto_update', true);
        $this->containerBuilder->setParameter('sw_dev_tool_six.shopware.api_auth_require', true);
        $this->containerBuilder->setParameter('sw_dev_tool_six.shopware.storefront_csrf', true);
        $this->containerBuilder->setParameter('sw_dev_tool_six.twig.debug', true);
        $this->containerBuilder->setParameter('sw_dev_tool_six.enable_dal_caching', true);
        $this->containerBuilder->setParameter('sw_dev_tool_six.access_token_ttl', 'P1W');
    }

    /**
     * @test
     * @group configuration
     */
    public function process()
    {
        $this->pass->process($this->containerBuilder);

        $this->assertTrue($this->containerBuilder->getParameter('sw_dev_tool_six.shopware.run_wizard'));
        $this->assertTrue($this->containerBuilder->getParameter('sw_dev_tool_six.shopware.auto_update'));
        $this->assertTrue($this->containerBuilder->getParameter('sw_dev_tool_six.shopware.api_auth_require'));
        $this->assertTrue($this->containerBuilder->getParameter('sw_dev_tool_six.shopware.storefront_csrf'));
    }
}
