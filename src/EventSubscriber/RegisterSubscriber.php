<?php

namespace App\EventSubscriber;

use App\Event\UserRegisterEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegisterSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onUserRegisterEvent(UserRegisterEvent $event)
    {
        if($event->getUser()){
            $this->logger->info('Yea, a new user is coming!');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::class => 'onUserRegisterEvent',
        ];
    }
}
