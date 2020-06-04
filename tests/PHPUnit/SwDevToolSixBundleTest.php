<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests;


use MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler\DALCachingPass;
use MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler\ParametersPass;
use MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler\StorefrontTokenTTLPass;
use MarcoFaul\SwDevToolSixBundle\DependencyInjection\SwDevToolSixExtension;
use MarcoFaul\SwDevToolSixBundle\SwDevToolSixBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SwDevToolSixBundleTest extends TestCase
{
    /** @var SwDevToolSixBundle */
    private $swDevToolSixBundle;

    /**
     * initialize all services once
     */
    public function setUp(): void
    {
        $this->swDevToolSixBundle = new SwDevToolSixBundle();
    }

    /**
     * check that out extension is added to the extensions
     * @test
     */
    public function getContainerExtension()
    {
        $extension = $this->swDevToolSixBundle->getContainerExtension();

        $this->assertEquals(new SwDevToolSixExtension(), $extension);
    }

    /**
     * @test
     */
    public function PASSES(): void
    {
        $this->assertEquals([ParametersPass::class, DALCachingPass::class, StorefrontTokenTTLPass::class], SwDevToolSixBundle::PASSES);
    }

    /**
     * check that our override pass is added
     * @test
     */
    public function build()
    {
        $container = new ContainerBuilder();
        $this->swDevToolSixBundle->build($container);
        $successCount = 0;

        foreach ($container->getCompilerPassConfig()->getPasses() as $pass) {

            if ($pass instanceof ParametersPass || $pass instanceof DALCachingPass || $pass instanceof StorefrontTokenTTLPass) {
                $successCount++;
            }
        }

        $this->assertEquals(\count(SwDevToolSixBundle::PASSES), $successCount);
    }
}
