<?php
namespace Omeka\Service;

use Zend\Log\Logger;
use Zend\Log\Writer\Noop;
use Zend\Log\Writer\Stream;
use Zend\Log\Filter\Priority;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * Logger factory.
 */
class LoggerFactory implements FactoryInterface
{
    /**
     * Create the logger service.
     *
     * @return Logger
     */
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $config = $serviceLocator->get('Config');
        $loggerOptions = [];

        if (isset($config['logger']['log'])
            && $config['logger']['log']
            && isset($config['logger']['path'])
            && is_writable($config['logger']['path'])
            && is_dir($config['logger']['path']) === false
        ) {
            $writer = new Stream($config['logger']['path']);

            if (isset($config['logger']['options'])) {
                $loggerOptions = $config['logger']['options'];
            }
        } else {
            $writer = new Noop;
        }

        $logger = new Logger($loggerOptions);
        $logger->addWriter($writer);

        $filter = new Priority($config['logger']['priority']);
        $writer->addFilter($filter);
        return $logger;
    }
}
