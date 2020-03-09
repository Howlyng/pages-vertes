<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeCategories Controller
 *
 * @property \App\Model\Table\EmployeCategoriesTable $EmployeCategories
 *
 * @method \App\Model\Entity\EmployeCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmployeCategoriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Enterprises']
        ];
        $employeCategories = $this->paginate($this->EmployeCategories);

        $this->set(compact('employeCategories'));
    }

    /**
     * View method
     *
     * @param string|null $id Employe Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeCategory = $this->EmployeCategories->get($id, [
            'contain' => ['Enterprises', 'Employees']
        ]);

        $this->set('employeCategory', $employeCategory);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $enterprise_id = $this->Auth->user()['enterprise']['id'];
        $data = $this->request->getData();
        if ($data['name'] == "") {
            $this->Flash->error( __d("Employees",'You must enter a category name in order to create one category. Please, try again.'));
            return $this->redirect([
                'controller' => 'Employees',
                'action' => 'privateIndex',
                /*'#' => 'top'*/]);
        }



        $employeCategory = $this->EmployeCategories->newEntity();
        $employeCategory->enterprise_id = $enterprise_id;
        if ($this->request->is('post')) {

            if(isset($data['name']))
                $data['name'] = h($data['name']);

            if(strlen($data['name']) < 5){
                $this->Flash->error( __d("Employees",'The name of the category must be > 5 and < 50 characters'));

                return $this->redirect([
                    'controller' => 'Employees',
                    'action' => 'privateIndex',
                    '#' => 'categories']);
            }

            $employeCategory = $this->EmployeCategories->patchEntity($employeCategory, $data);
            if ($this->EmployeCategories->save($employeCategory)) {
                $this->Flash->success( __d("Employees",'The employee category has been saved.'));

//                return $this->redirect(['action' => 'index']);
                return $this->redirect([
                    'controller' => 'Employees',
                    'action' => 'privateIndex']);
            }
            $this->Flash->error( __d("Employees",'The employe category could not be saved. Please, try again.'));
        }
        $enterprises = $this->EmployeCategories->Enterprises->find('list', ['limit' => 200]);
        $this->set(compact('employeCategory', 'enterprises'));

        return $this->redirect([
            'controller' => 'Employees',
            'action' => 'privateIndex']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employe Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employeCategory = $this->EmployeCategories->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if(isset($data['name']))
                $data['name'] = h($data['name']);

            if(strlen($data['name']) < 5){
                $this->Flash->error( __d("Employees",'The name of the category must be > 5 and < 50 characters'));

                return $this->redirect([
                    'controller' => 'Employees',
                    'action' => 'privateIndex',
                    '#' => 'categories']);
            }

            $employeCategory = $this->EmployeCategories->patchEntity($employeCategory, $data);
            if ($this->EmployeCategories->save($employeCategory)) {
                $this->Flash->success( __d("Employees",'The employe category has been saved.'));

                return $this->redirect([
                    'controller' => 'Employees',
                    'action' => 'privateIndex',
                    '#' => 'categories']);
            }
            $this->Flash->error( __d("Employees",'The employe category could not be saved. Please, try again.'));
        }


        return $this->redirect([
            'controller' => 'Employees',
            'action' => 'privateIndex',
            '#' => 'categories']);
    }

    /**
     * List all employees in a category
     *
     * @param null $id
     * @return \Cake\Http\Response|null|static
     */
    public function listEmployees($id = null){
        //$category = $this->EmployeCategories->get($id, ['contain' => 'Employees']);

        $category = $this->EmployeCategories->find()->contain('Employees')->where(['id'=>$id])->toArray();


        if(count($category) > 0){
            $func = function($emp){
                $var = ['id' => $emp->id, 'firstname' => $emp->firstname, 'lastname' => $emp->lastname];
                return $var;
            };


            $data = array_map($func, $category[0]->employees);
            $totalEmployees = count($category[0]->employees);


            $warning = __d("Employees", "This category contains {0} employee(s).", $totalEmployees);
            $response = ['warning' => $warning, 'data' => $data, 'totalEmployees' => $totalEmployees];

            $this->response=$this->response->withStringBody(json_encode($response));
        }
        else{
            $this->response=$this->response->withStringBody(json_encode([]));
        }

        $this->response->type('json');
        return $this->response;
    }

    /**
     * Delete method
     *
     * @param string|null $id Employe Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if($id == null)
            return;

        $employeCategory = $this->EmployeCategories->get($id);
        $enterprise_id = $this->Auth->user()['enterprise']['id'];

        if($employeCategory['enterprise_id'] != $enterprise_id) {
            $this->Flash->error(__d('Employees', 'You can only delete your categories'));
        }
        else{
            $this->request->allowMethod(['post', 'delete']);
            $employeCategory = $this->EmployeCategories->get($id);
            if ($this->EmployeCategories->delete($employeCategory)) {
                $this->Flash->success(__d("Employees", 'The employe category has been deleted.'));
            } else {
                $this->Flash->error(__d("Employees", 'The employe category could not be deleted. Please, try again.'));
            }
        }


        return $this->redirect('/my-enterprise/employees#categories');
    }
}
