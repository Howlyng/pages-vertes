<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
/**
 * ServiceCategories Controller
 *
 * @property \App\Model\Table\ServiceCategoriesTable $ServiceCategories
 *
 * @method \App\Model\Entity\ServiceCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServiceCategoriesController extends AppController
{

   
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $serviceCategory = $this->ServiceCategories->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData(); 
            $data['name'] = h($data['name']);
            $serviceCategory = $this->ServiceCategories->patchEntity($serviceCategory, $data);
            $serviceCategory->enterprise_id = $this->Auth->user()['enterprise']['id'];

            if ($this->ServiceCategories->save($serviceCategory)) {
                $this->Flash->success(__d("Services",'The service category has been saved.'));

            }else{
                 $this->Flash->error(__d("Services",'The service category could not be saved. Please, try again.'));
            }
            $this->redirect('/my-enterprise/services#categories');
        }
      
    }

    /**
     * Edit method
     *
     * @param string|null $id Service Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $serviceCategory = $this->ServiceCategories->get($id, [
            'contain' => []
        ]);
         $ent_id = $this->Auth->user()['enterprise']['id'];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData(); 
            $data['name'] = h($data['name']);
            $serviceCategory = $this->ServiceCategories->patchEntity($serviceCategory, $data);

             $CAT = $this->ServiceCategories;
               if($CAT-> exists(['id'=>$serviceCategory['id']]) &&  $CAT->get($serviceCategory['id'])['enterprise_id']!= $ent_id ){
               
                    $this->Flash->error(__d("Services","This category does not exist in your enterprise"));
                    
               } 
               else{
                    //  $serviceCategory->enterprise_id = $this->Auth->user()['enterprise']['id'];
                    if ($this->ServiceCategories->save($serviceCategory)) {
                    $this->Flash->success(__d("Services",'The service category has been saved.'));

                   return $this->redirect('/my-enterprise/services#categories');
                   
                   }

                }
           $this->Flash->error(__d("Services",'The service category could not be saved. Please, try again.'));
           return $this->redirect('/my-enterprise/services');
        }
        $enterprises = $this->ServiceCategories->Enterprises->find('list', ['limit' => 200]);
        $this->set(compact('serviceCategory', 'enterprises'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Service Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {    $ent_id = $this->Auth->user()['enterprise']['id'];
        $this->request->allowMethod(['post', 'delete']);
        $serviceCategory = $this->ServiceCategories->get($id);
        $CAT = $this->ServiceCategories;
               if($CAT-> exists(['id'=>$serviceCategory['id']]) &&  $CAT->get($serviceCategory['id'])['enterprise_id']!= $ent_id ){
               
                    $this->Flash->error(__d("Services","This category does not exist in your enterprise"));
                    
               } 
               else{
                     if ($this->ServiceCategories->delete($serviceCategory)) {
                        $this->Flash->success(__d("Services",'The service category and all its services have been deleted.'));
                     } 
                     else {
                        $this->Flash->error(__d("Services",'The service category could not be deleted. Please, try again.'));
                     }
                    } 
        $this->redirect('/my-enterprise/services#categories');
    }

     /**
     * retourne les services d'une caégorie en json
     * @param null $id
     * @return \Cake\Http\Response|null static
     */
    public function listServices($id = null)
    {    
        $ent_id = $this->Auth->user()['enterprise']['id'];
        $serviceCategory = $this->ServiceCategories->get($id);
        $CAT = $this->ServiceCategories;
               if($CAT-> exists(['id'=>$serviceCategory['id']]) &&  $CAT->get($serviceCategory['id'])['enterprise_id']!= $ent_id ){
               
                    return;
                    
               } 
               else{
                    $cats = $this->ServiceCategories->find()->contain('Services')->where(['id'=>$id])->toArray();
                    
                    if(count($cats) >0){  
                      $func =function ($p){
                      return ['id'=>$p->id, 'name'=>$p->name];
                      };
                              
                       $data= array_map($func, $cats[0]->services);
                       
                      $warning = __d("Services", "This category contains {0} services(s). They will be deleted", count($cats));
                      $response = ['warning' => $warning, 'data' => $data];

                      $this->response=$this->response->withStringBody(json_encode($response));
                    }else{
                       $this->response=$this->response->withStringBody(json_encode([]));

                    }
                $this->response->type('json');
                return $this->response;
                }
    }
}
