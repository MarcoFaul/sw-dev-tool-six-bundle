<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests\PHPUnit\DependencyInjection\Compiler;


use Doctrine\DBAL\Connection;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\ResourceServer;
use MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler\OverrideConfigurationPass;
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

class OverrideConfigurationPassTest extends TestCase
{
    /** @var ContainerBuilder */
    private $containerBuilder;

    /** @var OverrideConfigurationPass */
    private $pass;

    /**
     * initialize all services once
     */
    public function setUp(): void
    {
        $this->pass = new OverrideConfigurationPass();
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->setParameter('sw_dev_tool_six.shopware.skip_first_run_wizard_client', true);
        $this->containerBuilder->setParameter('sw_dev_tool_six.shopware.enable_auto_update', true);
        $this->containerBuilder->setParameter('sw_dev_tool_six.shopware.enable_api_auth_require', true);
        $this->containerBuilder->setParameter('sw_dev_tool_six.shopware.enable_storefront_csrf', true);
        $this->containerBuilder->setParameter('sw_dev_tool_six.enable_dal_caching', true);
        $this->containerBuilder->setParameter('sw_dev_tool_six.access_token_ttl', 'P1W');

        $this->containerBuilder->setDefinition('Shopware\Core\Framework\Api\EventListener\Authentication\ApiAuthenticationListener', new Definition(ApiAuthenticationListener::class, []));
        $this->containerBuilder->setDefinition('shopware.api.resource_server', new Definition(ResourceServer::class, []));
        $this->containerBuilder->setDefinition('shopware.api.authorization_server', new Definition(AuthorizationServer::class, []));
        $this->containerBuilder->setDefinition('Shopware\Core\Framework\Api\OAuth\UserRepository', new Definition(UserRepository::class, []));
        $this->containerBuilder->setDefinition('Shopware\Core\Framework\Api\OAuth\RefreshTokenRepository', new Definition(RefreshTokenRepository::class, []));
        $this->containerBuilder->setDefinition('Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory', new Definition(PsrHttpFactory::class, []));
        $this->containerBuilder->setDefinition('Shopware\Core\Framework\Routing\RouteScopeRegistry', new Definition(RouteScopeRegistry::class, []));
    }

    /**
     * @test
     * @group configuration
     */
    public function process()
    {
        $this->pass->process($this->containerBuilder);

        $this->assertTrue($this->containerBuilder->getParameter('sw_dev_tool_six.shopware.skip_first_run_wizard_client'));
        $this->assertTrue($this->containerBuilder->getParameter('sw_dev_tool_six.shopware.enable_auto_update'));
        $this->assertTrue($this->containerBuilder->getParameter('sw_dev_tool_six.shopware.enable_api_auth_require'));
        $this->assertTrue($this->containerBuilder->getParameter('sw_dev_tool_six.shopware.enable_storefront_csrf'));
    }

    /**
     * @test
     * @group configuration
     */
    public function overrideCachedEntity()
    {
        $this->containerBuilder->setParameter('sw_dev_tool_six.enable_dal_caching', false);
        $this->containerBuilder->setDefinition('Shopware\Core\Framework\DataAbstractionLayer\Cache\CachedEntitySearcher', new Definition(CachedEntitySearcher::class, []));
        $this->containerBuilder->setDefinition('Doctrine\DBAL\Connection', new Definition(Connection::class, []));
        $this->containerBuilder->setDefinition('Shopware\Core\Framework\DataAbstractionLayer\Search\Parser\SqlQueryParser', new Definition(SqlQueryParser::class, []));
        $this->containerBuilder->setDefinition('Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper', new Definition(EntityDefinitionQueryHelper::class, []));
        $this->containerBuilder->setDefinition('Shopware\Core\Framework\DataAbstractionLayer\Search\Term\SearchTermInterpreter', new Definition(SearchTermInterpreter::class, []));
        $this->containerBuilder->setDefinition('Shopware\Core\Framework\DataAbstractionLayer\Search\Term\EntityScoreQueryBuilder', new Definition(EntityScoreQueryBuilder::class, []));

        $this->pass->process($this->containerBuilder);

        $this->assertEquals(EntitySearcher::class, $this->containerBuilder->getDefinition('Shopware\Core\Framework\DataAbstractionLayer\Cache\CachedEntitySearcher')->getClass());
    }
}
