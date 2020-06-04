<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Subscriber\Storefront;


use Shopware\Core\PlatformRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ErrorHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['disableFrontendErrorHandling', -95]
        ];
    }

    public function disableFrontendErrorHandling(ExceptionEvent $event)
    {
        if ($event->getRequest()->attributes->has(PlatformRequest::ATTRIBUTE_SALES_CHANNEL_CONTEXT_OBJECT)) {
            $event->stopPropagation();
        }
    }
}
