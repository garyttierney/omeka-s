<?php
namespace Omeka\Site\BlockLayout;

use Omeka\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class Manager extends AbstractPluginManager
{
    protected $autoAddInvokableClass = false;

    protected $instanceOf = BlockLayoutInterface::class;

    /**
     * {@inheritDoc}
     */
    public function get($name, $options = [], $usePeeringServiceManagers = true)
    {
        try {
            $instance = parent::get($name, $options, $usePeeringServiceManagers);
        } catch (ServiceNotFoundException $e) {
            $logger = parent::get('Omeka\Logger');
            $logger->warn($e->getMessage(), [
                'exception' => $e
            ]);

            $instance = new Fallback($name);
        }
        return $instance;
    }
}
