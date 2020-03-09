<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;


/**
 * SupplierCategories Controller
 *
 * @property \App\Model\Table\SupplierCategoriesTable $SupplierCategories
 *
 * @method \App\Model\Entity\SupplierCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SupplierCategoriesController extends AppController
{

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $supplierCategory = $this->SupplierCategories->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['name'] = h($data['name']);
            $supplierCategory = $this->SupplierCategories->patchEntity($supplierCategory, $data);
            $supplierCategory->enterprise_id = $this->Auth->user()['enterprise']['id'];
            if ($this->SupplierCategories->save($supplierCategory)) {
                $this->Flash->success(__d("Suppliers",'The supplier category has been saved.'));

            } else {
                $this->Flash->error(__d("Suppliers",'The supplier category could not be saved. Please, try again.'));
            }
        }
        $this->redirect('/my-enterprise/suppliers#categories');
    }

    /**
     * Edit method
     *
     * @param string|null $id Supplier Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if(!$this->validateCatOwnership($id, $this->Auth->user('enterprise')['id'])){
            $this->Flash->error(__d("Suppliers","You are not authorized to edit this category"));
            return $this->redirect(['controller'=>'supplier', 'action'=>'privateIndex']);
        }
        $supplierCategory = $this->SupplierCategories->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $supplierCategory = $this->SupplierCategories->patchEntity($supplierCategory, $this->request->getData());

            if ($this->SupplierCategories->save($supplierCategory)) {
                $this->Flash->success(__d("Suppliers",'The supplier category has been saved.'));

                return $this->redirect('/my-enterprise/suppliers#categories');
            }
            $this->Flash->error(__d("Suppliers",'The supplier category could not be saved. Please, try again.'));
            return $this->redirect('/my-enterprise/suppliers#categories');
        }
        $this->redirect('/my-enterprise/suppliers#categories');
    }

    /**
     * Delete method
     *
     * @param string|null $id Supplier Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if(!$this->validateCatOwnership($id, $this->Auth->user('enterprise')['id'])){
            $this->Flash->error(__d("Suppliers","You are not authorized to delete this category"));
            return $this->redirect(['controller'=>'supplier', 'action'=>'privateIndex']);
        }
        $this->request->allowMethod(['post', 'delete']);
        $supplierCategory = $this->SupplierCategories->get($id);
        if ($this->SupplierCategories->delete($supplierCategory)) {
            $this->Flash->success(__d("Suppliers",'The supplier category has been deleted.'));
        } else {
            $this->Flash->error(__d("Suppliers",'The supplier category could not be deleted. Please, try again.'));
        }

        $this->redirect('/my-enterprise/suppliers#categories');
    }

    public function listSuppliers($id = null){

        $cat = $this->SupplierCategories->find()->contain('Suppliers')->where(['id'=>$id])->toArray();

        if(count($cat) >0){
            $func = function($s){
                return ['id'=>$s->id, 'name'=>$s->name];
            };

            $data = array_map($func, $cat[0]->suppliers);

            $info = __d("Suppliers", "This category contains {0} supplier(s).", count($cat[0]->suppliers));

            $message = [
                'warning'=>$info,
                'data'=>$data
            ];


            $this->response=$this->response->withStringBody(json_encode($message));
        }
        else{
            $this->response=$this->response->withStringBody(json_encode([]));
        }

        $this->response->type('json');
        return $this->response;
    }

    private function validateCatOwnership($catId, $ent_id){
        $CAT = TableRegistry::get('SupplierCategories');
        if($CAT->exists(['id' => $catId]) && $CAT->get($catId)['enterprise_id'] != $ent_id){
            $this->Flash->error(__d("Suppliers","This category does not exist in your enterprise"));
            return false;
        }
        return true;
    }
}
