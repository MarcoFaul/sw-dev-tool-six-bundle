<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests\PHPUnit\Subscriber\Storefront;


use MarcoFaul\SwDevToolSixBundle\Subscriber\Storefront\ErrorHandler;
use MarcoFaul\SwDevToolSixBundle\Tests\KernelTest;
use PHPUnit\Framework\TestCase;
use Shopware\Core\PlatformRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ErrorHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function getSubscribedEvents(): void
    {
        $this->assertEquals([KernelEvents::EXCEPTION => ['disableFrontendErrorHandling', -95]], ErrorHandler::getSubscribedEvents());
    }

    /**
     * @test
     */
    public function disableFrontendErrorHandlingOnSetAttribute(): void
    {
        $errorHandler = new ErrorHandler();
        $request = new Request();
        $request->attributes->set(PlatformRequest::ATTRIBUTE_SALES_CHANNEL_CONTEXT_OBJECT, 'XYZ');
        $event = new ExceptionEvent(
            new KernelTest(),
            $request,
            1,
            new \Exception()
        );

        $errorHandler->disableFrontendErrorHandling($event);

        $this->assertEquals(true, $event->isPropagationStopped());
    }

    /**
     * @test
     */
    public function disableFrontendErrorHandlingOnNoneSetAttribute(): void
    {
        $errorHandler = new ErrorHandler();
        $request = new Request();
        $event = new ExceptionEvent(
            new KernelTest(),
            $request,
            1,
            new \Exception()
        );

        $errorHandler->disableFrontendErrorHandling($event);

        $this->assertEquals(false, $event->isPropagationStopped());
    }
}
