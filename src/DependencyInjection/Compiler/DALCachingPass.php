<?php declare(strict_types=1);

namespace MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler;

use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntitySearcher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DALCachingPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('sw_dev_tool_six.enable_dal_caching') === false) {
            $container->getDefinition('Shopware\Core\Framework\DataAbstractionLayer\Cache\CachedEntitySearcher')
                ->setClass(EntitySearcher::class)
                ->setArgument(0, $container->getDefinition('Doctrine\DBAL\Connection'))
                ->setArgument(1, $container->getDefinition('Shopware\Core\Framework\DataAbstractionLayer\Search\Parser\SqlQueryParser'))
                ->setArgument(2, $container->getDefinition('Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper'))
                ->setArgument(3, $container->getDefinition('Shopware\Core\Framework\DataAbstractionLayer\Search\Term\SearchTermInterpreter'))
                ->setArgument(4, $container->getDefinition('Shopware\Core\Framework\DataAbstractionLayer\Search\Term\EntityScoreQueryBuilder'));
        }
    }
}
