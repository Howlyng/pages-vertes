<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\FrozenDate;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Controller\Component\PaginatorComponent;
use Cake\I18n\Time;

/**
 * Employees Controller
 *
 *
 * @method \App\Model\Entity\Employee[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmployeesController extends AppController
{
    public $paginate = [
        'Employees'=>[
            'scope'=>'employees',
            'limit' =>10,
            'order'=>['Products.name'=>'asc']
        ],
        'EmployeCategories'=>[
            'scope'=>'EmployeCategories',
            'limit' =>15,
            'order'=>['EmployeCategories.name'=>'asc']
        ]
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    public function publicIndex($id = null)
    {
        $enterprises = TableRegistry::get('Enterprises');

        if ($id == null || !$enterprises->exists(['id' => $id])) {
            $this->Flash->error(__d("Employees", 'Sorry, this enterprise does not exist.'));
            $this->redirect('/');
            return;
        } else {
            $this->set('isOwner', false);
            $this->fetchViewVars($id);
            $this->render('index');
        }
    }

    public function privateIndex()
    {
        $isOwner = true;
        $enterprise_id = $this->Auth->user()['enterprise']['id'];

        $this->fetchViewVars($enterprise_id, true);

        $this->set('isOwner', $isOwner);
        $this->render('index');
    }

    /**
     * Set up view's variables : enterprise, employees, categories according to the $entId
     *
     * @param int $entId .
     * @param string|null $isOwner .
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function fetchViewVars($entId, $isOwner = false)
    {
        $newCategory = TableRegistry::get('EmployeCategories')->newEntity();
        $employees = $this->Employees->findByEnterpriseId($entId, false);

        $this->set('employees',  $this->paginate($employees, ['scope'=>'employees']));

        $enterprise = TableRegistry::get('Enterprises')
            ->get($entId, ['contain' => 'Pictures']);

        $this->set(compact('enterprise', $enterprise));

        $categories = TableRegistry::get('EmployeCategories')->findByEnterpriseId($entId, false);
        $this->set('categories', $this->paginate($categories, ['scope'=>'EmployeCategories']));

        if ($isOwner) {
            $this->set(compact('newCategory'));
        }
    }

    /**
     * View method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employee = $this->Employees->get($id, [
            'contain' => []
        ]);

        $this->set('employee', $employee);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->set('isEditing', false);
        $this->set('isOwner', true);
        $enterprise_id = $this->Auth->user()['enterprise']['id'];
        $employee = $this->Employees->newEntity();
        $categories = $this->Employees->EmployeCategories->find('list',
            [
                'keyField' => 'id',
                'valueField' => 'name'
            ])->where(['enterprise_id' => $enterprise_id]);

        if ($this->request->is('post')) {
            $data = $this->request->getData();


            //Nettoyer les champs saisis
            if (isset($data['firstname']) && isset($data['lastname']) && isset($data['address'])) {
                $data['firstname'] = h($data['firstname']);
                $data['lastname'] = h($data['lastname']);
                $data['address'] = h($data['address']);
            }

            $allCategories = TableRegistry::get('EmployeCategories');
            if ($allCategories->exists(['id' => $data['employe_category_id']]) &&
                $allCategories->get($data['employe_category_id'])['enterprise_id'] != $enterprise_id) {
                $this->Flash->error(__d("Employees", "This category does not exist"));
            } else {
                $employee = $this->Employees->patchEntity($employee, $data);

//                echo '<pre>';
//                $time = Time::now();
//                print_r($time);
//                print_r($employee->birthday);
//                var_dump(Time::now()->diff($employee->birthday));
//                echo '</pre>';
//                die;

                if (!$data || sizeof($data['picture']) == 0 || $data['picture']['tmp_name'] == null) {
                    $this->Flash->error(__d("Employees", 'Please select an image.'));
                } else {
                    $pictures = TableRegistry::get('Pictures');
                    $newPicture = $pictures->newEntity();
                    $newPicture->base64image = base64_encode(file_get_contents($data['picture']['tmp_name']));

                    //Gestion d'image avant de sauver l'employé
                    $newPicture->base64image = "data:" . $data['picture']['type'] . ";base64," . $newPicture->base64image;
                    $pictures->save($newPicture);

                    $employee->picture = $newPicture;

                    if ($this->Employees->save($employee)) {
                        $this->Flash->success(__d("Employees", 'The employee has been saved.'));

                        return $this->redirect(['action' => 'privateIndex']);
                    }
                    $this->Flash->error(__d("Employees", 'The employee could not be saved. Please, try again.'));
                }
            }
        }
        $this->set(compact('categories'));
        $this->set(compact('employee'));

        $this->render('edit');
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->set('isEditing', true);
        $this->set('isOwner', true);

        $enterprise_id = $this->Auth->user()['enterprise']['id'];
        $employee = $this->Employees->get(['id' => $id]);

        $category = TableRegistry::get('EmployeCategories')->get(['id' => $employee->employe_category_id]);
        $picture = TableRegistry::get('Pictures')->get(['id' => $employee->picture_id]);

        if ($category->enterprise_id != $enterprise_id) {
            $this->Flash->error(__d('Employees', 'You can only modify your employees'));
            return $this->redirect([
                'controller' => 'Employees',
                'action' => 'privateIndex']);
        }

        $categories = $this->Employees->EmployeCategories->find('list',
            [
                'keyField' => 'id',
                'valueField' => 'name'
            ])->where(['enterprise_id' => $enterprise_id]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $pictureReady = true; //La picture existe déjà

            //Nettoyer les champs saisis
            if (isset($data['firstname']) && isset($data['lastname']) && isset($data['address'])) {
                $data['firstname'] = h($data['firstname']);
                $data['lastname'] = h($data['lastname']);
                $data['address'] = h($data['address']);
            }

            $allCategories = TableRegistry::get('EmployeCategories');
            if ($allCategories->exists(['id' => $data['employe_category_id']]) &&
                $allCategories->get($data['employe_category_id'])['enterprise_id'] != $enterprise_id) {
                $this->Flash->error(__d("Employees", "This category does not exist"));
            } else {
                $employee = $this->Employees->patchEntity($employee, $data);

                if ($data && sizeof($data['picture']) != 0 && $data['picture']['tmp_name'] != null) {
                    $picture->base64image = base64_encode(file_get_contents($data['picture']['tmp_name']));
                    $picture->base64image = "data:" . $data['picture']['type'] . ";base64," . $picture->base64image;
                    if (!TableRegistry::get('Pictures')->save($picture)) {
                        $this->Flash->error(__d("Employees", 'The employee could not be saved. Please, try again.'));
                        $pictureReady = false;
                    }
                }

                //Gestion d'image avant de sauver l'employé
                if ($pictureReady) {
                    if ($this->Employees->save($employee)) {
                        $this->Flash->success(__d("Employees", 'The employee has been saved.'));

                        return $this->redirect(['action' => 'privateIndex']);
                    }
                    $this->Flash->error(__d("Employees", 'The employee could not be saved. Please, try again.'));
                }


            }

        }


//        if ($this->request->is(['patch', 'post', 'put'])) {
//            $employee = $this->Employees->patchEntity($employee, $this->request->getData());
//            if ($this->Employees->save($employee)) {
//                $this->Flash->success(__('The employee has been saved.'));
//
//                return $this->redirect(['action' => 'index']);
//            }
//            $this->Flash->error(__('The employee could not be saved. Please, try again.'));
//        }

        $this->set(compact('categories'));
        $this->set(compact('employee'));
        $this->set(compact('picture'));

        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $employee = $this->Employees->get($id);
        $cat = TableRegistry::get('EmployeCategories')->get($employee['employe_category_id']);
        $enterprise_id = $this->Auth->user()['enterprise']['id'];

        if ($cat['enterprise_id'] != $enterprise_id) {
            $this->Flash->error(__d('Employees', 'You can only delete your employees'));
        } else {

            $this->request->allowMethod(['post', 'delete']);
            if ($this->Employees->delete($employee)) {
                $this->Flash->success(__('The employee has been deleted.'));
            } else {
                $this->Flash->error(__('The employee could not be deleted. Please, try again.'));
            }
        }

        return $this->redirect(['action' => 'privateIndex']);
    }
}
