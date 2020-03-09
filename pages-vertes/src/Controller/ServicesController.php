<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
/**
 * Services Controller
 *
 *
 * @method \App\Model\Entity\Service[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServicesController extends AppController
{   

    public $paginate = [
        'Services'=>[
                'scope'=>'services',
                'limit' =>10,
                'order'=>['Services.name'=>'asc']
            ],
        'ServiceCategories'=>[
            'scope'=>'serviceCategories',
            'limit' =>15,
            'order'=>['ServiceCategories.name'=>'asc']
            ]
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    } 
    
     public function publicIndex()
    {

        $this->set('isEditing', false);
 
      $ENTERPRISE = TableRegistry::get('Enterprises');
        $isOwner = false;
        $ent_id = $this->request->getParam('enterprise_id');
        
        if(!$ENTERPRISE->exists(['id' => $ent_id])){
            $this->Flash->error(__d("Services",'Enterprise does not exist'));
            $this->redirect('/');
            return;
        } else {
            
            $this->setServsAndCatsViewVar($isOwner, $ent_id);
            $this->set('isOwner', $isOwner);
            $this->render('index');
        }

    }
    public function privateIndex()
    {   
        # $ENTERPRISE =  TableRegistry::get('Enterprises');
         $isOwner = true;

         $ent_id = $this->Auth->user()['enterprise']['id'];

         

         
     
      //  $this->set('isEditing', true);

        $this->setServsAndCatsViewVar($isOwner, $ent_id);
        $this->set('isOwner', $isOwner);
        $this->render('index');
    }
   

    
    public function index()
    {
        $services = $this->paginate($this->Services);
        $this->set('isOwner', true);
        $this->render('index');
       ## $this->set(compact('services'));
    }

    /**
     * View method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => []
        ]);

        $this->set('service', $service);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {     $this->set('isEditingMode', false);
          // $ENTERPRISE =  TableRegistry::get('Enterprises');
           $PICTURE = TableRegistry::get('Pictures');
         $isOwner = true;
         $service = $this->Services->newEntity();
        
         $ent_id = $this->Auth->user()['enterprise']['id'];

        $this->setServsCatsAdd($isOwner, $ent_id);

        
          
       
         
       if ($this->request->is('post')) {
                
          $pic = $PICTURE->newEntity();
          $data = $this->request->getData();
          $data['name'] = h($data['name']);
                if(sizeof($data)==0){
                   $this->Flash->error(__d("Services","All fields are required"));
                   return $this->redirect(['action' => 'privateIndex']);
                }
                $service = $this->Services->patchEntity($service,$data);
              
               $CAT = TableRegistry::get('ServiceCategories');
               if($CAT-> exists(['id'=>$data['service_category_id']]) &&  $CAT->get($data['service_category_id'])['enterprise_id']!= $ent_id ){
               
                    $this->Flash->error(__d("Services","This category does not exist in your enterprise"));
                    
               } 
               else{
               
 
                 if (strlen($data['image']['tmp_name']) != 0 &&( sizeof($data['image']) == 0 || $data['image']['tmp_name'] == null)) {
                   $this->Flash->error(__d("Services","The image is invalid."));
                 } 
                 else{
                   if(strlen($data['image']['tmp_name'])!=0){

                      //gérer l'image
                      $pic->base64image = "data:". $data['image']['type'] .";base64," . base64_encode(file_get_contents($data['image']['tmp_name']));
                      
                      if($PICTURE->save($pic)) {
                         $service->picture_id=$pic->id;
                    
                         if ($this->Services->save($service)) {
                     
                            $this->Flash->success(__d("Services",'The service has been saved.'));
                            return $this->redirect(['action' => 'privateIndex']); 
                          }
                      }   
                
                      $this->Flash->error(__d("Services",'The service could not be saved. Please, try again.'));
                   }else{

                           
                         if ($this->Services->save($service)) {
                     
                            $this->Flash->success(__d("Services",'The service has been saved.'));
                            return $this->redirect(['action' => 'privateIndex']); 
                          }
                         $this->Flash->error(__d("Services",'The service could not be saved . Please, try again.'));
                    }
                 

                 }
               }
             
           
        }
        $this->set(compact('service'));
         $this->set(compact('categories'));
        $this->render('edit');
    }

    /**
     * Edit method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {    
          if($id == null){
            return $this->redirect(['action'=>'privateIndex']);
          } 

        $this->set('isEditingMode', true);
      
            $PICTURE = TableRegistry::get('Pictures');
         $isOwner = true;
        $service = $this->Services->get($id, [  'contain' => ['Pictures']]);
        
         $ent_id = $this->Auth->user()['enterprise']['id'];
     
        $this->setServsCatsAdd($isOwner, $ent_id);

         $this->set('service', $service);
          
       
         
       if ($this->request->is(['patch', 'post', 'put'])) {
                
          $pic = $PICTURE->newEntity();
          $data = $this->request->getData();
      
          $data['name'] = h($data['name']);
                if(sizeof($data)==0){
                   $this->Flash->error(__d("Services","All fields are required"));
                   return $this->redirect(['action' => 'privateIndex']);
                }
                $service = $this->Services->patchEntity($service,$data);
              
               $CAT = TableRegistry::get('ServiceCategories');
               if($CAT-> exists(['id'=>$data['service_category_id']]) &&  $CAT->get($data['service_category_id'])['enterprise_id']!= $ent_id ){
               
                    $this->Flash->error(__d("Services","This category does not exist in your enterprise"));
                    
               } 
               else{
               
 
                 if (strlen($data['image']['tmp_name']) != 0 &&( sizeof($data['image']) == 0 || $data['image']['tmp_name'] == null)) {
                   $this->Flash->error(__d("Services","The image is invalid."));
                 } 
                 else{
                      if(strlen($data['image']['tmp_name'])!=0){
                         //créée pic si yen a pas associer au service
                       
                         if($service->picture_id!=null && $service->picture !=null){ 
                             $pic= $service->picture;
                             
                         } 
                         else{
                           $pic = $PICTURE->newEntity();   
                         }
                         //gérer l'image
                       
                         $pic->base64image = "data:". $data['image']['type'] .";base64," . base64_encode(file_get_contents($data['image']['tmp_name']));
                        //savegarde image 
                         if($PICTURE->save($pic)) {
                           $service->picture_id=$pic->id;
                          
                         }   
                 

                      }
                      else{    
                             //si pas de photo dans le imput 
                            if($service->picture_id!=null){
                               
                              if($data['Deleteimage']=='1')
                              { $idpic =  $service->picture_id;
                                $service->picture_id=null; 

                                 $PICTURE = TableRegistry::get('Pictures');                    
                                 $picdel= $PICTURE ->get($idpic);  
                                
                                 // supprime la photo i y faut quelle soit supprimer   
                                 if($PICTURE->delete($picdel))
                                 {$this->Flash->success(__d("Services",'The picture has been deleted.'));
                                   $service->picture =null; 
                                  
                                 }
                               
                              }                             
                            }
                           
                      }
                      

                                        
                       $SERVICE = TableRegistry::get('Services');   

                                    if ($SERVICE->save($service)) {
                                       
                                     $this->Flash->success(__d("Services",'The service has been saved.')); 

                                      return $this->redirect(['action' => 'privateIndex']); 
                                     } 
                                     else{ 
                                       
                                    }
                                     $this->Flash->error(__d("Services",'The service could not be saved . Please, try again.'));
                     

                 }
               }
             
           
        }
        $this->set(compact('service'));
         $this->set(compact('categories'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {  
         if($id == null){
            return $this->redirect(['action'=>'privateIndex']);
          } 
   
        $service = $this->Services->get($id);
               
        $this->request->allowMethod(['post', 'delete']);
        
        if ($this->Services->delete($service)) {
            $this->Flash->success(__('The service has been deleted.'));
        } else {
            $this->Flash->error(__('The service could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'privateIndex']);
    }

     private function setServsAndCatsViewVar($isOwner, $ent_id){
        $servs = $this->Services->getServListingFromEnterprise($ent_id,false);
         $ENTERPRISE = TableRegistry::get('Enterprises');
        $enttreprise = $this->Services->ServiceCategories->Enterprises->find()->where(['Enterprises.id' => $ent_id])->contain('Pictures')->first();
             $this->set('entreprises', $enttreprise);
        
        $this->set('services', $this->paginate($servs,['scope'=>'services']));

       $this->set('entId',$ent_id);
 
            $cats =  $this->Services->ServiceCategories->findByEnterpriseId($ent_id);
            $this->set('categories', $this->paginate($cats,['scope'=>'serviceCategories']));
         if($isOwner){
            $this->set('isOwner', true);
        }

    }

    private function setServsCatsAdd($isOwner, $ent_id){
       
        if($isOwner){
            $this->set('isOwner', true);

            ##$cats = TableRegistry::get('ServiceCategories')->findByEnterpriseId($ent_id);
            $cats = $this->Services->ServiceCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'])->where(['enterprise_id' =>  $ent_id]);
            
            $this->set('categories', $cats);
        }

    }
}
