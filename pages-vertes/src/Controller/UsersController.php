<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;
use Cake\Collection\Collection;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Controller\Component\AuthComponent;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->Auth->allow('translate');
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $user["enterprise"] = $this->Users->get($user['id'], ['contain' => ['Enterprises']])['enterprise'];
                $this->Auth->setUser($user);
                $this->Flash->success(__("Successfully logged in"));

                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Wrong email or password');
        }

    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function register()
    {
        $ENTERPRISE = TableRegistry::get('Enterprises');
        $PICTURE = TableRegistry::get('Pictures');

        $user = $this->Users->newEntity();
        $pic = $PICTURE->newEntity();
        $enterprise = $ENTERPRISE->newEntity();


        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $pic->base64image = base64_encode(file_get_contents($data['image']['tmp_name']));

            $user = $this->Users->patchEntity($user, $this->request->getData());
            $enterprise->user = $user;
            $pic->base64image = "data:" . $data['image']['type'] . ";base64," . $pic->base64image;
            $enterprise->picture = $pic;
            $enterprise->name = $data["name"];
            $enterprise->domain_name = $data["domain"];
            $enterprise->description = $data["description"];

            if ($ENTERPRISE->save($enterprise)) {
                $this->Flash->success(__('Account created! Welcome!'));

                $authUser = $this->Users->get($user->id)->toArray();
                $authUser['enterprise'] = $enterprise;
                $this->Auth->setUser($authUser);
                return $this->redirect('/');
            }
            $this->Flash->error(__('Can\'t create your account. Please try again and make sure you entered all info'));
        }
        $this->set(compact('user'));

    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($id == null) {
            $user = $this->Auth->user()["id"];
            $user = TableRegistry::get('Users')->get($user);
        } else {
            $user = $this->Users->get($id, [
                'contain' => 'Enterprises'
            ]);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $passwordOk = true;

            if (isset($data['current_password']) && strlen($data['current_password']) > 0) {
                if ((new DefaultPasswordHasher)->check($data['current_password'], $user['password'])) {
                    if ($data['new_password'] === $data['new_password_confirmation']) {
                        $data['password'] = $data['new_password'];
                    } else {
                        $this->Flash->error(__('Passwords don\'t match. Please, try again.'));
                        $passwordOk = false;
                    }
                } else {
                    $this->Flash->error(__('Wrong password. Please, try again.'));
                    $passwordOk = false;
                }
            }
            if ($passwordOk) {
                $user = $this->Users->patchEntity($user, $data);
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The user has been saved.'));

                    return $this->redirect('/my-enterprise/edit');
                }
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        return $this->redirect('/my-enterprise/edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public
    function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     *
     */
    public
    function logout()
    {
        $this->Auth->logout();
        $this->Flash->success(__('Thank you! Come again!'));
        $this->redirect('/');
    }

    /**
     * @param $lang
     */
    public
    function translate()
    {
        $language = $this->request->getParam('lang');

        $langs = new Collection(['fr', 'en']);
        if ($langs->contains($language)) {
            $session = $this->request->getSession();
            $session->write('Config.language', $language);
        }
        $this->redirect($this->referer());
    }


}
