<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ContentViewEvent extends Event
{
    public const NAME = 'content.viewed';

    private object $content;

    public function __construct(object $content)
    {
        $this->content = $content;
    }

    public function getContent(): object
    {
        return $this->content;
    }
}
