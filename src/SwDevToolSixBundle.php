<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle;


use MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler\DALCachingPass;
use MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler\ParametersPass;
use MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler\StorefrontTokenTTLPass;
use MarcoFaul\SwDevToolSixBundle\DependencyInjection\SwDevToolSixExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SwDevToolSixBundle extends Bundle
{
    public const PASSES = [ParametersPass::class, DALCachingPass::class, StorefrontTokenTTLPass::class];

    /**
     * Overridden to allow for the custom extension alias.
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new SwDevToolSixExtension();
        }

        return $this->extension;
    }

    public function build(ContainerBuilder $container)
    {
        foreach (self::PASSES as $pass) {
            $container->addCompilerPass(new $pass);
        }

        parent::build($container);
    }
}
