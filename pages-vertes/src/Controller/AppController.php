<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\Routing\Router;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' =>[
                    'fields' =>[
                        'username' =>'email',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' =>[
                'controller' => 'Users',
                'action' =>'login'
            ],
            'unauthorizedRedirect'=>$this->referer(),
            'redirect'=>'/'
        ]);

        $this->Auth->allow(['display','view','index', 'login', 'register', 'publicIndex']);
        $this->set('isLogged', $this->request->getSession()->read('Auth.User') != null);

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $session = $this->request->getSession();
        if (!$session->check('Config.language')) {
            // Config.language existe et n'est pas null.
            $session->write('Config.language', 'en');
        }
        I18n::setLocale($session->read('Config.language'));
        Router::parseNamedParams($this->request);
    }

    protected function nicePrintR($var, $title = null){
        if($title == null){
            $title =  $this->request['controller'] . '#' .  $this->request['action'];
        }
        echo "<pre>";
        echo "<h2>$title</h2>";
        print_r($var);
        echo "</pre>";
        die;
    }


}
