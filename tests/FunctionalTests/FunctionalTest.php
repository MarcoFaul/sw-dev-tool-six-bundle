<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests;


use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollectionBuilder;

class FunctionalTest extends TestCase
{
    public function testServiceWiring()
    {
        $this->markTestSkipped();
        $kernel = new KernelTest();
        $kernel->boot();

        $container = $kernel->getContainer();

        $apiAuthenticationListenerExtension = $container->get('MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication\ApiAuthenticationListenerExtension');

//        $this->assertInstanceOf(ApiAuthenticationListenerExtension::class, $apiAuthenticationListenerExtension);
//        $this->assertIsString('string', $apiAuthenticationListenerExtension->);
    }
}
