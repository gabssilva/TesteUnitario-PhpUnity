<?php

namespace Webjump\DrupalStock\Api;

use Magento\Catalog\Model\Product;

interface MessageInterface
{
    /**
     * @param string $message
     * @return void
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param Product $product
     * @return string
    */
    public function prepareMessage($product);
}