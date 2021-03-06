<?php
/**
 * YAWIK Stackoverflow API
 *
 * @filesource
 * @license MIT
 * @copyright  2016 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace StackoverflowApi\Factory\Client;

use Interop\Container\ContainerInterface;
use StackoverflowApi\Client\Client;
use StackoverflowApi\Client\DataTransformer;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \StackoverflowApi\Client\Client
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @since 0.1.0
 */
class ClientFactory implements FactoryInterface
{

    /**
     * Create the client.
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return Client
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var \StackOverflowApi\Options\ModuleOptions $options */
        $options     = $container->get('StackoverflowApi/ModuleOptions');
        $log         = $container->get('Log/StackoverflowApi');
        $transformer = $container->get(DataTransformer::class);

        $client = new Client($options->getAuthorizationCode());
        $client
            ->setLogger($log)
            ->setTransformer($transformer)
        ;

        return $client;
    }
}
