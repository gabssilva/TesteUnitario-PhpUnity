<?php

namespace Webjump\DrupalStock\Test\Unit\Model;

class SaveStockPluginTest extends \PHPUnit\Framework\TestCase
{
    private $productMock;

    private $stockItemMock;
    private $queueMock;
    private $messageMock;
    private $serializerMock;
    private $productRepositoryMock;

    private $model;

    public function setUp()
    {
        $this->productMock = $this->getProductMock();
        $this->productRepositoryMock= $this->getProductRepositoryMock();
        $this->stockItemMock = $this->getStockItemMock();
        $this->messageMock = $this->getMessageMock();
        $this->queueMock = $this->getQueueMock();
        $this->serializerMock = $this->getSerializerMock();


        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject(
            \Webjump\DrupalStock\Plugin\SaveStockPlugin::class,
            [
                "message" => $this->messageMock ,
                "serializer" => $this->serializerMock,
                "queue" => $this->queueMock,
                "productRepository" => $this->productRepositoryMock
            ]
        );
    }

    private function getStockItemRepositoryMock()
    {
        $mock = $this->getMockBuilder(\Magento\CatalogInventory\Model\Stock\StockItemRepository::class)
         ->disableOriginalConstructor()
         ->getMockForAbstractClass();

        return $mock;
    }

    private function getSerializerMock()
    {
        $mock = $this->getMockBuilder(\Magento\Framework\Serialize\SerializerInterface::class)
            ->setMethods(['serialize'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        return $mock;
    }

    private function getProductRepositoryMock()
    {
        $mock = $this->getMockBuilder(\Magento\Catalog\Api\ProductRepositoryInterface::class)
            ->setMethods(['getById'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        return $mock;
    }

    private function getStockItemMock()
    {
        $mock = $this->getMockBuilder(\Magento\CatalogInventory\Model\Stock\Item::class)
            ->setMethods(['getProductId'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        return $mock;
    }

    private function getMessageMock()
    {
        $mock = $this->getMockBuilder(\Webjump\DrupalStock\Api\MessageInterface::class)
            ->setMethods(['prepareMessage' , 'setMessage'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        return $mock;
    }

    private function getQueueMock()
    {
        $mock = $this->getMockBuilder(\Webjump\DrupalStock\Api\QueueInterface::class)
            ->setMethods(['send'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        return $mock;
    }

    private function getProductMock()
    {
        $mock = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->setMethods(['getSku', 'getPrice' , 'getQty' , 'getTypeId', 'getExtensionAttributes'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        return $mock;
    }


    public function testAfterSave()
    {
        $this->productRepositoryMock->expects($this->once())
            ->method('getById')
            ->willReturn($this->productMock);

        $this->stockItemMock->expects($this->once())
            ->method('getProductId')
            ->willReturn(1);

        $this->messageMock->expects($this->once())
            ->method('prepareMessage')
            ->willReturn([
                'sku' => '12312',
                'price' => 1.1,
                'stock' => 1,
                'type' => 'simple',
                'retries' => 0
            ]);

        $this->queueMock->expects($this->once())
            ->method('send')
            ->willReturn(null);

        $this->messageMock->expects($this->once())
            ->method('setMessage')
            ->willReturn(null);

        $this->model->afterSave($this->getStockItemRepositoryMock(), [], $this->stockItemMock);
    }
}
