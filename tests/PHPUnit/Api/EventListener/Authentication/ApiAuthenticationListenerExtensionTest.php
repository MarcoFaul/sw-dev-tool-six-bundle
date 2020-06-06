<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests\PHPUnit\Api\EventListener\Authentication;


use MarcoFaul\SwDevToolSixBundle\Api\EventListener\Authentication\ApiAuthenticationListenerExtension;
use MarcoFaul\SwDevToolSixBundle\Tests\KernelTest;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Routing\KernelListenerPriorities;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiAuthenticationListenerExtensionTest extends TestCase
{
    /**
     * @test
     */
    public function getSubscribedEvents(): void
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
}
