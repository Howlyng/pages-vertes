<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Products Controller
 *
 *
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends AppController
{

    public $paginate = [
        'Products'=>[
                'scope'=>'products',
                'limit' =>10,
                'order'=>['Products.name'=>'asc']
            ],
        'ProductCategories'=>[
            'scope'=>'productCategories',
            'limit' =>15,
            'order'=>['ProductCategories.name'=>'asc']
            ]
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

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

        if(!$ENTERPRISE->exists(['id' => $ent_id])){
            $this->Flash->error(__d("Products",'Enterprise does not exist'));
            $this->redirect('/');
            return;
        } else {

            $this->setProdsAndCatsViewVar($isOwner, $ent_id);
            $this->set('isOwner', $isOwner);
            $this->render('index');
        }
    }
    public function privateIndex()
    {
        $ENTERPRISE = TableRegistry::get('Enterprises');
        $isOwner = true;
        $ent_id = $this->Auth->user()['enterprise']['id'];


        $this->setProdsAndCatsViewVar($isOwner, $ent_id);
        $this->set('isOwner', $isOwner);
        $this->render('index');
    }

    /**
     * View method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->set('isOwner', true);
//        $product = $this->Products->get($id, [
//            'contain' => []
//        ]);
//
//        $this->set('product', $product);
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
        $product = $this->Products->newEntity();
        $categories = $this->Products->ProductCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['enterprise_id' =>  $ent_id]);


        if ($this->request->is('post')) {
            $pic = $PICTURE->newEntity();
            $data = $this->request->getData();

            if(isset($data['name']))
               $data['name'] = h($data['name']);
            $product = $this->Products->patchEntity($product, $data);
//            $this->sanitizeProduct($product);
            //Verif si category appartient a l'entreprise
            $CAT = TableRegistry::get('ProductCategories');
            if($CAT->exists(['id' => $data['product_category_id']]) && $CAT->get($data['product_category_id'])['enterprise_id'] != $ent_id){
                $this->Flash->error(__d("Products","This category does not exist in your enterprise"));
            }
            else
            {
                if ($data['image'] == null || sizeof($data['image']) == 0 || $data['image']['tmp_name'] == null) {
                    $this->Flash->error(__d("Products","Please select an image."));
                }
                else
                {
                    //Gérer l'image
                        $pic->base64image = "data:". $data['image']['type'] .";base64," . base64_encode(file_get_contents($data['image']['tmp_name']));

                    if ($PICTURE->save($pic)) {
                        $product->picture_id = $pic->id;

                        if ($this->Products->save($product)) {
                            $this->Flash->success(__d("Products",'The product has been saved.'));

                            return $this->redirect(['action' => 'privateIndex']);
                        }
                    }
                    $this->Flash->error(__d("Products",'The product could not be saved. Please, try again.'));
                }
            }
        }


        $this->set(compact('product'));
        $this->set(compact('categories'));
        $this->render('edit');
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if($id == null){
            return $this->redirect(['action'=>'privateIndex']);
        }
        $prod = $this->Products->get($id);
        if(!$this->validateCatOwnership($prod->product_category_id, $this->Auth->user()['enterprise']['id'])){
            $this->Flash->error(__d('Products','You are not authorized to edit this product'));
            return $this->redirect(['action'=>'privateIndex']);
        }

        $this->set('isEditingMode', true);
        $ent_id = $this->Auth->user()['enterprise']['id'];
        $product = $this->Products->get($id, [
            'contain' => ['Pictures']
        ]);
        $categories = $this->Products->ProductCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['enterprise_id' =>  $ent_id]);


        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();
            if(empty($data['name'])){
                $this->Flash->error(__d("Products", "Name cannot be left empty"));
                $this->set(compact('product'));
                $this->set(compact('categories'));
                return;
            }

            $product = $this->Products->patchEntity($product, $this->request->getData());


            if ($this->validateCatOwnership($data['product_category_id'], $ent_id)) {
                //Si nouvelle image
                $pic = $data['image'];

                if ($pic != null && sizeof($pic) > 0 && $pic['tmp_name'] != null) {
                    //Gérer l'image
                    $PICTURE = TableRegistry::get('Pictures');
                    $newPic = $PICTURE->get($product->picture_id);
                    $newPic->base64image = "data:". $pic['type'] .";base64," . base64_encode(file_get_contents($pic['tmp_name']));

                    if (!$PICTURE->save($newPic)) {
                        $this->Flash->error(__d('Products', "Cannot save this image file. Please try with another one"));
                        return $this->redirect(['action' => 'privateIndex']);
                    }
                    $product->picture_id = $newPic->id;
                }
//                if(isset($data['name']))
//                    $product->name =  h($product->name);

                if ($this->Products->save($product)) {
                    $this->Flash->success(__d("Products", '{0} has been saved.', [$product->name]));
                    $product->name =  h($product->name);
                    $this->Products->save($product);
                    return $this->redirect(['action' => 'privateIndex']);
                }

                $this->Flash->error(__d("Products", '{0} could not be saved. Please, try again.', [$product->name]));
            }

        }

        $this->set(compact('product'));
        $this->set(compact('categories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $prod = $this->Products->get($id);

        if(!$this->validateCatOwnership($prod->product_category_id, $this->Auth->user('enterprise')['id'])){
            $this->Flash->error(__d('Products','You are not authorized to delete this product'));
            return $this->redirect(['action'=>'privateIndex']);
        }

        $this->request->allowMethod(['post', 'delete']);
        $product = $this->Products->get($id);
        if ($this->Products->delete($product)) {
            $this->Flash->success(__d("Products",'The product has been deleted.'));
        } else {
            $this->Flash->error(__d("Products",'The product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'privateIndex']);
    }



    private function setProdsAndCatsViewVar($isOwner, $ent_id){
        $prods = $this->Products->getProdListingFromEnterprise($ent_id, false);
        $ent = TableRegistry::get('Enterprises')->get($ent_id,['contain'=>'Pictures']);
        $enterprise =  [
            'name'=>$ent['name'],
            'base64image'=>$ent['picture']['base64image'],
            'created' => $ent['created'],
            ];

        $this->set('products', $this->paginate($prods,['scope'=>'products']));
        $this->set('enterprise',$enterprise);
        $this->set('entId',$ent_id);
        $cats = TableRegistry::get('ProductCategories')->find('all')->where(['enterprise_id'=>$ent_id]);
        $this->set('categories',$this->paginate($cats,['scope'=>'productCategories']));

        if($isOwner){
            $this->set('isOwner', true);
        }

    }

    private function validateCatOwnership($catId, $ent_id){
        $CAT = TableRegistry::get('ProductCategories');

        if($CAT->exists(['id' => $catId]))
            if($CAT->get($catId)['enterprise_id'] == $ent_id){
            {
                return true;
            }
            return false;
        }
        return false;
    }
}
