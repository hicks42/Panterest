<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogoutSubSubscriber implements EventSubscriberInterface
{
    private $urlGen;
    public function __construct(UrlGeneratorInterface $urlGen){
        $this->urlGen = $urlGen;
    }
    
    public function onLogoutEvent(LogoutEvent $event)
    {
        $event->getRequest()->getSession()->getFlashBag()->add(
            'success',
            'Successsfully logged out'
        );
        $event->setResponse(new RedirectResponse($this->urlGen->generate('app_home')));
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
