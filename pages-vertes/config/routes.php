<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /*Fournisseurs - pages publiques */
    $routes->connect('/enterprises/:enterprise_id/suppliers',['controller' => 'Suppliers', 'action' => 'publicIndex']);
    $routes->connect('/enterprises/:enterprise_id/suppliers/:supplier_id',['controller' => 'Suppliers', 'action' => 'view']);
    /*Fournisseurs - pages privées */
    $routes->connect('/my-enterprise/suppliers/:id',['controller' => 'Suppliers', 'action' => 'edit'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);
    $routes->connect('/my-enterprise/suppliers/add',['controller' => 'Suppliers', 'action' => 'add']);
    $routes->connect('/my-enterprise/suppliers',['controller' => 'Suppliers', 'action' => 'privateIndex']);
    $routes->connect('/my-enterprise/supplierCategories/add',['controller' => 'SupplierCategories', 'action' => 'add']);
    $routes->connect('/my-enterprise/supplierCategories/edit/:id',['controller' => 'SupplierCategories', 'action' => 'edit'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);
    $routes->connect('/my-enterprise/supplierCategories/listSuppliers/:id',['controller'=>'SupplierCategories','action'=>'listSuppliers'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);
    $routes->connect('/my-enterprise/supplierCategories/delete/:id',['controller'=>'SupplierCategories','action'=>'delete'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);

    /*Produits - pages publiques */
    $routes->connect('/enterprises/:enterprise_id/products',['controller' => 'Products', 'action' => 'publicIndex']);
    $routes->connect('/enterprises/:enterprise_id/products/:product_id',['controller' => 'Products', 'action' => 'view']);
    /*Produits - pages privées */
    $routes->connect('/my-enterprise/products/:id',['controller' => 'Products', 'action' => 'edit'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);
    $routes->connect('/my-enterprise/products/add',['controller' => 'Products', 'action' => 'add']);
    $routes->connect('/my-enterprise/products',['controller' => 'Products', 'action' => 'privateIndex']);
    $routes->connect('/my-enterprise/productCategories/add',['controller' => 'ProductCategories', 'action' => 'add']);
    $routes->connect('/my-enterprise/productCategories/edit/:id',['controller' => 'ProductCategories', 'action' => 'edit'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);
    $routes->connect('/my-enterprise/productCategories/listProducts/:id',['controller'=>'ProductCategories','action'=>'listProducts'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);
    $routes->connect('/my-enterprise/productCategories/delete/:id',['controller'=>'ProductCategories','action'=>'delete'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);


    /*Employés - pages publiques*/
    $routes->connect('/enterprises/:id/employees/',['controller' => 'Employees', 'action' => 'publicIndex'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);
    /*Employés - pages privées*/
    $routes->connect('/my-enterprise/employees/edit/:id',['controller' => 'Employees', 'action' => 'edit'])
        ->setPass(['id']);
    $routes->connect('/my-enterprise/employees/add',['controller' => 'Employees', 'action' => 'add']);
    $routes->connect('/my-enterprise/employees/delete/:id',['controller' => 'Employees', 'action' => 'add'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']) ;

    $routes->connect('/my-enterprise/employeCategories/add',['controller' => 'EmployeCategories', 'action' => 'add']);
    $routes->connect('/my-enterprise/employeCategories/edit/:id',['controller' => 'EmployeCategories', 'action' => 'edit'])
        ->setPass(['id']);
    $routes->connect('/my-enterprise/employeCategories/listEmployees/:id',['controller'=>'EmployeCategories','action'=>'listEmployees'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);
    $routes->connect('/my-enterprise/employeCategories/delete/:id',['controller'=>'EmployeCategories','action'=>'delete'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);
    $routes->connect('/my-enterprise/employees',['controller' => 'Employees', 'action' => 'privateIndex']);

    /*Enterprise -  détails*/
    $routes->connect('/enterprises/:id', ['controller' => 'Enterprises', 'action' => 'view'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);

    /*Enterprise -  EDIT*/
    $routes->connect('/my-enterprise/edit',['controller'=>'Enterprises','action'=>'edit']);
    $routes->connect('/my-enterprise/user/edit', ['controller' => 'Users', 'action' => 'edit']);



    /*Services - pages publiques */
    $routes->connect('/enterprises/:enterprise_id/services',['controller' => 'Services', 'action' => 'publicIndex']);
    /*Services - pages privées */
    $routes->connect('/my-enterprise/services/add',['controller' => 'Services', 'action' => 'add']);
    $routes->connect('/my-enterprise/services/:id',['controller' => 'Services', 'action' => 'edit'])->setPatterns(['id' => '\d+'])
    ->setPass(['id']);
    $routes->connect('/my-enterprise/services',['controller' => 'Services', 'action' => 'privateIndex']);
    $routes->connect('/my-enterprise/serviceCategories/add',['controller' => 'ServiceCategories', 'action' => 'add']);
    $routes->connect('/my-enterprise/serviceCategories/:id',['controller' => 'ServiceCategories', 'action' => 'edit'])->setPatterns(['id' => '\d+'])  ->setPass(['id']);
    $routes->connect('/my-enterprise/serviceCategories/listServices/:id',['controller'=>'ServiceCategories','action'=>'listServices'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']);
    $routes->connect('/my-enterprise/serviceCategories/delete/:id',['controller'=>'ServiceCategories','action'=>'delete'])
        ->setPatterns(['id'=>'\d+'])
        ->setPass(['id']) ;   

    /*Authentification*/
    $routes->connect('/register', ['controller' => 'Users', 'action' => 'register']);
    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);


    /*TRADUCTION*/
    $routes->connect('/lang/:lang',['controller'=>'Users', 'action' => 'translate']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
