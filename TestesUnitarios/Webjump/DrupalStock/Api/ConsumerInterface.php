<?php

namespace Webjump\DrupalStock\Api;

use Magento\Framework\Exception\LocalizedException;
use Webjump\DrupalStock\Model\Message;

interface ConsumerInterface
{
    /**
     * @param Message
     * @return $this
     * @throws LocalizedException
     */
    public function process(Message $message);
}