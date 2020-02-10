<?php

namespace Webjump\DrupalStock\Test\Unit\Model;

class MessageTest extends \PHPUnit\Framework\TestCase
{
    private $productMock;

    /**
     * @var \Webjump\DrupalStock\Model\Message
     */

    private $extensionAttributesMock;
    private $model;
    private $stockItemMock;

    public function setUp()
    {
        $this->productMock = $this->getProductMock();
        $this->extensionAttributesMock = $this->getExtensionAttributesMock();
        $this->stockItemMock = $this->getStockItemMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject(
           \Webjump\DrupalStock\Model\Message::class
       );
    }

    private function getProductMock()
    {
        $mock = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->setMethods(['getSku', 'getPrice' , 'getQty' , 'getTypeId', 'getExtensionAttributes'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        return $mock;
    }

    private function getExtensionAttributesMock()
    {
        $mock = $this->getMockBuilder(\Magento\Catalog\Api\Data\ProductExtension::class)
            ->setMethods(['getStockItem'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        return $mock;
    }
    private function getStockItemMock()
    {
        $mock = $this->getMockBuilder(\Magento\CatalogInventory\Model\Stock\Item::class)
            ->setMethods(['getQty'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        return $mock;
    }
    public function testGetMessage()
    {
        $this->model->setMessage('teste');
        $value = $this->model->getMessage();
        $this->assertEquals('teste', $value);
    }

    public function testPrepareMessage()
    {
        $this->extensionAttributesMock->expects($this->once())
            ->method('getStockItem')
            ->willReturn($this->stockItemMock);

        $this->productMock->expects($this->once())
            ->method('getSku')
            ->willReturn('sku45566665544');

        $this->productMock->expects($this->once())
            ->method('getPrice')
            ->willReturn(1.1);

        $this->stockItemMock->expects($this->once())
            ->method('getQty')
            ->willReturn(1);

        $this->productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn(21312);

        $this->productMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($this->extensionAttributesMock);

        $this->assertEquals([
            'sku' => 'sku45566665544',
            'price' => 1.1,
            'stock' => 1,
            'type' => 21312,
            'retries' => 0
        ], $this->model->prepareMessage($this->productMock));
    }
}
