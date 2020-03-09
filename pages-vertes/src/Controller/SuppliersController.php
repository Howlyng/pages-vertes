<?php
/**
 * Created by PhpStorm.
 * User: Howly
 * Date: 05/02/2018
 * Time: 15:51
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;


class SuppliersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function publicIndex()
    {
        $ENTERPRISE = TableRegistry::get('Enterprises');
        $isOwner = false;
        $ent_id = $this->request->getParam('enterprise_id');

        if (!$ENTERPRISE->exists(['id' => $ent_id])) {
            $this->Flash->error(__d("Suppliers", 'Enterprise does not exist'));
            $this->redirect('/');
            return;
        } else {

            $this->setSuplsAndCatsViewVar($isOwner, $ent_id);
            $this->set('isOwner', $isOwner);
            $this->render('index');
        }
    }

    public function privateIndex()
    {
        $ENTERPRISE = TableRegistry::get('Enterprises');
        $isOwner = true;
        $ent_id = $this->Auth->user()['enterprise']['id'];


        $this->setSuplsAndCatsViewVar($isOwner, $ent_id);
        $this->set('isOwner', $isOwner);
        $this->render('index');
    }

    /**
     * View method
     *
     * @param string|null $id Supplier id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->set('isOwner', true);
//        $supplier = $this->Suppliers->get($id, [
//            'contain' => []
//        ]);
//
//        $this->set('supplier', $supplier);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->set('isEditingMode', false);
        $PICTURE = TableRegistry::get('Pictures');
        $ent_id = $this->Auth->user()['enterprise']['id'];
        $supplier = $this->Suppliers->newEntity();
        $categories = $this->Suppliers->SupplierCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['enterprise_id' => $ent_id]);

        if ($this->request->is('post')) {
            $pic = $PICTURE->newEntity();
            $data = $this->request->getData();

            if (isset($data['name']))
                $data['name'] = h($data['name']);
            if (isset($data['address']))
                $data['address'] = h($data['address']);
            $supplier = $this->Suppliers->patchEntity($supplier, $data);
            $CAT = TableRegistry::get('SupplierCategories');
            if ($CAT->exists(['id' => $data['supplier_category_id']]) && $CAT->get($data['supplier_category_id'])['enterprise_id'] != $ent_id) {
                $this->Flash->error(__d("Suppliers", "This category does not exist in your enterprise"));
            } else {
                if (strlen($data['image']['tmp_name']) != 0 && (sizeof($data['image']) == 0 || $data['image']['tmp_name'] == null)) {
                    $this->Flash->error(__d("Suppliers", "The image is invalid."));
                } else {
                    if (strlen($data['image']['tmp_name']) != 0) {

                        //gérer l'image
                        $pic->base64image = "data:" . $data['image']['type'] . ";base64," . base64_encode(file_get_contents($data['image']['tmp_name']));

                        if ($PICTURE->save($pic)) {
                            $supplier->picture_id = $pic->id;

                            if ($this->Suppliers->save($supplier)) {

                                $this->Flash->success(__d("Suppliers", 'The supplier has been saved.'));
                                return $this->redirect(['action' => 'privateIndex']);
                            }
                        }

                        $this->Flash->error(__d("Suppliers", 'The supplier could not be saved. Please, try again.'));
                    } else {


                        if ($this->Suppliers->save($supplier)) {

                            $this->Flash->success(__d("Suppliers", 'The supplier has been saved.'));
                            return $this->redirect(['action' => 'privateIndex']);
                        }
                        $this->Flash->error(__d("Suppliers", 'The supplier could not be saved . Please, try again.'));
                    }
                }
            }
        }

        $this->set(compact('supplier'));
        $this->set(compact('categories'));
        $this->render('edit');
    }

    /**
     * Edit method
     *
     * @param string|null $id Supplier id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($id == null) {
            return $this->redirect(['action' => 'privateIndex']);
        }

        if(!$this->validateCatOwnership($id, $this->Auth->user('enterprise')['id'])){
            $this->Flash->error(__d("Suppliers","You are not authorized to edit this supplier"));
            return $this->redirect(['action'=>'privateIndex']);
        }

        $this->set('isEditingMode', true);
        $ent_id = $this->Auth->user('enterprise')['id'];
        $supplier = $this->Suppliers->get($id, [
            'contain' => ['Pictures']
        ]);
        $categories = $this->Suppliers->SupplierCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['enterprise_id' => $ent_id]);


        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();
            $supplier = $this->Suppliers->patchEntity($supplier, $this->request->getData());

            if ($this->validateCatOwnership($data['supplier_category_id'], $ent_id)) {
                //Si nouvelle image
                $pic = $data['image'];


                if ($pic != null && sizeof($pic) > 0 && $pic['tmp_name'] != null) {
                    //Gérer l'image
                    $PICTURE = TableRegistry::get('Pictures');

                    if (strlen($pic['tmp_name']) != 0) {

                        if ($supplier->picture_id == null) {

                            $newPic = $PICTURE->newEntity();
                            $newPic->base64image = "data:" . $pic['type'] . ";base64," . base64_encode(file_get_contents($pic['tmp_name']));

                            if ($PICTURE->save($newPic)) {
                                $supplier->picture_id = $newPic->id;
                            } else {
                                $this->Flash->error(__d('Suppliers', "Cannot save this image file. Please try with another one"));
                                return $this->redirect(['action' => 'privateIndex']);
                            }

                        } else {

                            $newPic = $PICTURE->get($supplier->picture_id);

                            $newPic->base64image = "data:" . $pic['type'] . ";base64," . base64_encode(file_get_contents($pic['tmp_name']));

                            if (!$PICTURE->save($newPic)) {
                                $this->Flash->error(__d('Suppliers', "Cannot save this image file. Please try with another one"));
                                return $this->redirect(['action' => 'privateIndex']);
                            }
                            $supplier->picture_id = $newPic->id;
                        }
                    }


                }

                // Gestion suppression image

                if ($pic == null || sizeof($pic) > 0 || $pic['tmp_name'] != null) {

                    if ($supplier->picture_id != null) {

                        if ($data['deletepicture'] == '1') {
                            $idpic = $supplier->picture_id;
                            $supplier->picture_id = null;

                            $this->Suppliers->save($supplier);

                            $PICTURE = TableRegistry::get('Pictures');
                            $picdel = $PICTURE->get($idpic);

                            // On supprime l'image dans la table Pictures
                            if ($PICTURE->delete($picdel)) {
                                $this->Flash->success(__d("Suppliers", 'The picture has been deleted.'));
                                $supplier->picture = null;

                            }

                        }
                    }
                }

                $supplier->name = h($supplier->name);
                $supplier->address = h($supplier->address);
                if ($this->Suppliers->save($supplier)) {
                    $this->Flash->success(__d("Suppliers", '{0} has been saved.', [$supplier->name]));

                    return $this->redirect(['action' => 'privateIndex']);
                }
                $this->Flash->error(__d("Suppliers", '{0} could not be saved. Please, try again.', [$supplier->name]));
            }

        }

        $this->set(compact('supplier'));
        $this->set(compact('categories'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Supplier id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {

        if($id == null){
            return $this->redirect(['action'=>'privateIndex']);
        }
        if(!$this->validateCatOwnership($id, $this->Auth->user('enterprise')['id'])){
            $this->Flash->error(__d("Suppliers","You are not authorized to delete this supplier"));
            return $this->redirect(['action'=>'privateIndex']);
        }
        $this->request->allowMethod(['post', 'delete']);
        $supplier = $this->Suppliers->get($id);
        if ($this->Suppliers->delete($supplier)) {
            $this->Flash->success(__d("Suppliers",'The supplier has been deleted.'));
        } else {
            $this->Flash->error(__d("Suppliers",'The supplier could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'privateIndex']);
    }

    public $paginate = [
        'Suppliers'=>[
            'scope'=>'suppliers',
            'limit' =>10,
            'order'=>['Suppliers.name'=>'asc']
        ],
        'SupplierCategories'=>[
            'scope'=>'supplierCategories',
            'limit' =>15,
            'order'=>['SupplierCategories.name'=>'asc']
        ]
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }



    private function setSuplsAndCatsViewVar($isOwner, $ent_id){
        $supls = $this->Suppliers->getSuplListingFromEnterprise($ent_id, false);

        $this->set('suppliers', $supls);

        $supls = $this->Suppliers->getSuplListingFromEnterprise($ent_id, false);
        $ent = TableRegistry::get('Enterprises')->get($ent_id,['contain'=>'Pictures']);
        $enterprise =  [
            'name'=>$ent['name'],
            'base64image'=>$ent['picture']['base64image'],
        ];

        $this->set('suppliers', $this->paginate($supls,['scope'=>'suppliers']));
        $this->set('enterprise',$enterprise);
        $this->set('entId',$ent_id);
        $cats = TableRegistry::get('SupplierCategories')->find('all')->where(['enterprise_id'=>$ent_id]);
        $this->set('categories',$this->paginate($cats,['scope'=>'supplierCategories']));

        if($isOwner){
            $this->set('isOwner', true);
        }
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
