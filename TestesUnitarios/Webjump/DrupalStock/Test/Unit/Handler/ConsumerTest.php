<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2018 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\DrupalStock\Test\Unit\Handler;


class ConsumerTest extends \PHPUnit\Framework\TestCase
{
    private $loggerMock;

    private $serializerMock;

    private $queueMock;

    private $configMock;

    private $messageMock;

    private $boostMock;

    /**
     * @var \Webjump\DrupalStock\Handler\Consumer
     */
    private $model;

    public function setUp()
    {

       $this->loggerMock = $this->getLoggerMock();
       $this->serializerMock = $this->getSerializerMock();
       $this->queueMock = $this->getQueueMock();
       $this->configMock = $this->getConfigMock();
       $this->boostMock = $this->getBoostMock();
       $this->messageMock = $this->getMessageMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject(
            \Webjump\DrupalStock\Handler\Consumer::class,
            [
                "logger" => $this->loggerMock,
                "serializer" => $this->serializerMock,
                "queue" => $this->queueMock,
                "config" => $this->configMock,
                "boost" => $this->boostMock,
            ]
        );
    }

    private function getLoggerMock()
    {
        $mock = $this->createMock(\Webjump\DrupalStock\Model\Logger\Logger::class);

        return $mock;
    }

    private function getSerializerMock()
    {
        $mock = $this->createMock(\Magento\Framework\Serialize\SerializerInterface::class);
        return $mock;
    }

    private function getQueueMock()
    {
        $mock = $this->getMockBuilder(\Webjump\DrupalStock\Api\QueueInterface::class)
            ->setMethods(['messageToArray'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
            ;

        $mock->expects($this->once())
            ->method('messageToArray')
            ->willReturn([
                'sku' => '07613034787958',
                'price' => 44.9500,
                'stock' => 993,
                'type' => 'simple',
                'retries' => 0
            ]);

        return $mock;
    }

    private function getConfigMock()
    {
        $mock = $this->getMockBuilder(\Webjump\DrupalStock\Api\ConfigInterface::class)
        ->setMethods(['getScopeConfigRetries'])
        ->disableOriginalConstructor()
        ->getMockForAbstractClass()
        ;

        $mock->expects($this->once())
            ->method('getScopeConfigRetries')
            ->willReturn(1);

        return $mock;
    }

    private function getBoostMock()
    {
        $mock = $this->getMockBuilder(\Webjump\DrupalStock\Api\BoostInterface::class)
            ->setMethods(['sendPost'])
            ->getMock();

        $mock->expects($this->once())
            ->method('sendPost')
            ->willReturn(true);
        return $mock;
    }

    private function getMessageMock()
    {
        $mock = $this->getMockBuilder(\Webjump\DrupalStock\Model\Message::class)
            ->setMethods(['getMessage'])
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())
            ->method('getMessage')
            ->willReturn('"{"sku":"07613034787958","price":"44.9500","stock":993,"type":"simple","retries":0}"');

        return $mock;
    }

    public function testProcess()
    {
        $this->model->process($this->messageMock);
    }
}