<?php

namespace App\EventSubscriber;

use App\Event\ContentViewEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentViewSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){}

    public static function getSubscribedEvents()
    {
       return [
            ContentViewEvent::NAME => 'onContentView',
       ];
    }

    public function onContentView(ContentViewEvent $event)
    {
        $content = $event->getContent();

        if (method_exists($content, 'incrementView')) {
            $content->incrementView();
            $this->entityManager->flush();
        }

    }
}
