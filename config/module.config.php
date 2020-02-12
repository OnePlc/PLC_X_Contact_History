<?php
/**
 * module.config.php - History Config
 *
 * Main Config File for Contact History Plugin
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

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    # History Module - Routes
    'router' => [
        'routes' => [
            'contact-history' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/contact/history[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\HistoryController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'contact-history-setup' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/contact/history/setup',
                    'defaults' => [
                        'controller' => Controller\InstallController::class,
                        'action'     => 'checkdb',
                    ],
                ],
            ],
        ],
    ], # Routes

    # View Settings
    'view_manager' => [
        'template_path_stack' => [
            'contact-history' => __DIR__ . '/../view',
        ],
    ],
];
