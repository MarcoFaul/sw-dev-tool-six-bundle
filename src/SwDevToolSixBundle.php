<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle;


use MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler\OverrideConfigurationCompilerPass;
use MarcoFaul\SwDevToolSixBundle\DependencyInjection\ConfigurationExtension;
use MarcoFaul\SwDevToolSixBundle\DependencyInjection\SwDevToolSixExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SwDevToolSixBundle extends Bundle
{
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
        parent::build($container);

        $container->addCompilerPass(new OverrideConfigurationCompilerPass());
    }
}
