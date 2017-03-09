<?php
/**
 * YAWIK Stackoverflow API
 *
 * @filesource
 * @license MIT
 * @copyright  2016 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace StackoverflowApiTest\Factory\Client;

use CoreTestUtils\TestCase\ServiceManagerMockTrait;
use CoreTestUtils\TestCase\TestInheritanceTrait;
use StackoverflowApi\Client\Client;
use StackoverflowApi\Factory\Client\ClientFactory;
use StackoverflowApi\Options\ModuleOptions;
use Zend\Log\Logger;
use Zend\ServiceManager\FactoryInterface;

/**
 * Tests for \StackoverflowApi\Factory\Client\ClientFactory
 * 
 * @covers \StackoverflowApi\Factory\Client\ClientFactory
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @group StackoverflowApi
 * @group StackoverflowApi.Factory
 * @group StackoverflowApi.Factory.Client
 */
class ClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestInheritanceTrait, ServiceManagerMockTrait;

    /**
     *
     *
     * @var array|\PHPUnit_Framework_MockObject_MockObject|ClientFactory
     */
    private $target = [
        ClientFactory::class,
        '@testCreateService' => [
            'mock' => ['__invoke'],
        ]
    ];

    private $inheritance = [ FactoryInterface::class ];

    public function testCreateService()
    {
        $container = $this->getServiceManagerMock();

        $this->target
            ->expects($this->once())
            ->method('__invoke')
            ->with($container, Client::class);

        $this->target->createService($container);
    }

    public function testInvokationCreatesClientInstance()
    {
        $options = new ModuleOptions();
        $options->setAuthorizationCode('test-auth-code');

        $log     = new Logger();

        $container = $this->getServiceManagerMock([
            'StackoverflowApi/ModuleOptions' => $options,
            'Log/StackoverflowApi' => $log
        ]);

        $client = $this->target->__invoke($container, 'irrelevant');

        $this->assertEquals('https://talent.stackoverflow.com/api/jobs?code=test-auth-code', $client->getUri()->toString());
        $this->assertSame($log, $client->getLogger());
    }
}