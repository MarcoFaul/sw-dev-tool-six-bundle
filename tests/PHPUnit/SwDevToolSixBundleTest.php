<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests;


use MarcoFaul\SwDevToolSixBundle\DependencyInjection\Compiler\OverrideConfigurationPass;
use MarcoFaul\SwDevToolSixBundle\DependencyInjection\ConfigurationExtension;
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
     * check that our override pass is added
     * @test
     */
    public function build()
    {
        $container = new ContainerBuilder();
        $this->swDevToolSixBundle->build($container);
        $success = false;

        foreach ($container->getCompilerPassConfig()->getPasses() as $pass) {
            if ($pass instanceof OverrideConfigurationPass) {
                $success = true;
                break;
            }
        }

        $this->assertTrue($success);
    }
}
