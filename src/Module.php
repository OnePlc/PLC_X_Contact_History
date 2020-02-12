<?php
/**
 * Module.php - Module Class
 *
 * Module Class File for History History Plugin
 *
 * @category Config
 * @package Contact\History
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Contact\History;

use Application\Controller\CoreEntityController;
use Laminas\Mvc\MvcEvent;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\EventManager\EventInterface as Event;
use Laminas\ModuleManager\ModuleManager;
use OnePlace\Contact\History\Controller\HistoryController;
use OnePlace\Contact\History\Model\HistoryTable;
use OnePlace\Contact\Model\ContactTable;

class Module {
    /**
     * Module Version
     *
     * @since 1.0.0
     */
    const VERSION = '1.0.1';

    /**
     * Load module config file
     *
     * @since 1.0.0
     * @return array
     */
    public function getConfig() : array {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(Event $e)
    {
        // This method is called once the MVC bootstrapping is complete
        $application = $e->getApplication();
        $container    = $application->getServiceManager();
        $oDbAdapter = $container->get(AdapterInterface::class);
        $tableGateway = $container->get(HistoryTable::class);

        # Register Filter Plugin Hook
        CoreEntityController::addHook('contact-view-before',(object)['sFunction'=>'attachHistoryForm','oItem'=>new HistoryController($oDbAdapter,$tableGateway,$container)]);
        CoreEntityController::addHook('contacthistory-add-before-save',(object)['sFunction'=>'attachHistoryToContact','oItem'=>new HistoryController($oDbAdapter,$tableGateway,$container)]);
    }

    /**
     * Load Models
     */
    public function getServiceConfig() : array {
        return [
            'factories' => [
                # History Plugin - Base Model
                Model\HistoryTable::class => function($container) {
                    $tableGateway = $container->get(Model\HistoryTableGateway::class);
                    return new Model\HistoryTable($tableGateway,$container);
                },
                Model\HistoryTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\History($dbAdapter));
                    return new TableGateway('contact_history', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    } # getServiceConfig()

    /**
     * Load Controllers
     */
    public function getControllerConfig() : array {
        return [
            'factories' => [
                Controller\HistoryController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    $tableGateway = $container->get(HistoryTable::class);

                    # hook start
                    # hook end
                    return new Controller\HistoryController(
                        $oDbAdapter,
                        $tableGateway,
                        $container
                    );
                },
                # Installer
                Controller\InstallController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\InstallController(
                        $oDbAdapter,
                        $container->get(Model\AddressTable::class),
                        $container
                    );
                },
            ],
        ];
    } # getControllerConfig()
}
