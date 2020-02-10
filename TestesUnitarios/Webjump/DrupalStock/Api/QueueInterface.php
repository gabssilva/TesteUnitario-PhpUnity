<?php

namespace Webjump\DrupalStock\Api;

use Webjump\DrupalStock\Model\Message;

interface QueueInterface
{
    /**
     * @param Message $message
     * @return void
     */
    public function send($message);

    /**
     * @param Message $message
     * @return array
     */
    public function messageToArray(Message $message);
}
