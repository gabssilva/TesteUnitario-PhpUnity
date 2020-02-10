<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2018 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\DrupalStock\Test\Unit\Model;

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    private $scopeConfigMock;

    /**
     * @var \Webjump\DrupalStock\Model\Config
     */
    private $model;


    public function setUp()
    {
        $this->scopeConfigMock = $this->getConfigMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject(
            \Webjump\DrupalStock\Model\Config::class,
            [
                "scopeConfig" => $this->scopeConfigMock,
            ]
        );
    }

    private function getConfigMock()
    {
        $mock = $this->getMockBuilder(\Magento\Framework\App\Config\ScopeConfigInterface::class)
            ->setMethods(['getValue'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $mock->expects($this->once())
            ->method('getValue')
            ->willReturn(1);

        return $mock;
    }

    public function testGetScopeConfigApiKey()
    {
        $value = $this->model->getScopeConfigApiKey();
        $this->assertEquals('string', gettype($value));
    }

    /**
     * {@inheritDoc}
     */
    public function testGetConfigValue()
    {
        $value = $this->model->getConfigValue('Test');
    }

    /**
     * {@inheritDoc}
     */
    public function testGetScopeConfigRetries()
    {
        $value = $this->model->getScopeConfigRetries();
        $this->assertEquals('integer', gettype($value));
    }

    /**
     * {@inheritDoc}
     */
    public function testIsBoostStockApiEnabled()
    {
        $value = $this->model->isBoostStockApiEnabled();
        $this->assertEquals('boolean', gettype($value));
    }

    /**
     * {@inheritDoc}
     */
    public function testGetScopeConfigApiUri()
    {
        $value = $this->model->getScopeConfigApiUri();
        $this->assertEquals('string', gettype($value));
    }
}
