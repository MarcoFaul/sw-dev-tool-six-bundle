<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests\PHPUnit\Api\EventListener\Authentication;


use MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication\ApiAuthenticationListenerExtension;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Routing\KernelListenerPriorities;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiAuthenticationListenerExtensionTest extends TestCase
{

    public function getSubscribedEvents()
    {
        $events = [
            KernelEvents::REQUEST => [
                ['setupOAuth', 128],
            ],
            KernelEvents::CONTROLLER => [
                ['validateRequest', KernelListenerPriorities::KERNEL_CONTROLLER_EVENT_PRIORITY_AUTH_VALIDATE],
            ],
        ];

        $this->assertEquals(ApiAuthenticationListenerExtension::getSubscribedEvents(), $events);
    }

    /**
     * @test
     */
    public function blub()
    {
        $this->assertEquals(42, 42);
    }
}
