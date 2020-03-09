<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ServicesController;
use App\Controller\ServiceCategoriesController;
use Cake\TestSuite\IntegrationTestCase;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServiceCategoriesTable;
use App\Model\Table\PicturesTable;
use Cake\ORM\TableRegistry;


/**
 * App\Controller\ProductsController Test Case
 */
class SuppServicesTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.enterprises',
        'app.users',
        'app.pictures',
        'app.services',
        'app.service_categories'
    ];

    public function setUp(){
        parent::setUp();
        $this->Services = TableRegistry::get('Services');
        $this->ServiceCategories = TableRegistry::get('ServiceCategories');

        $this->Pictures = TableRegistry::get('Pictures');

         $this->Auth = [
            'User' => [
                'id' => 1,
                'username' => 'p1@gmail.ca',
                'password' => '123456',
                'enterprise' =>[
                    'id'=> 1
                ]
            ]
        ];
    }

//    Fonctionnel dans SERVICES
    public function testServicesSupp9(){
      $this->setSession();
      $servId = $this->Services->get(3)->id;

        //supprimer produit 1
        $this->post('/services/delete/3');
        $DeleteServ=$this->Services->find()->where(['id'=> $servId])->toArray();
        $this->assertEmpty($DeleteServ);

    }
    
     //    Fonctionnel dans SERVICE_CATEGORIES
    public function testServicesSupp10(){
        //login user
        $this->setSession();
      $catId = $this->ServiceCategories->get(3)->id;

        //supprimer produit 1
        $this->post('/my-enterprise/serviceCategories/delete/3');
        $Deletedcat=$this->Services->ServiceCategories->find()->where(['id'=> $catId])->toArray();
        $this->assertEmpty($Deletedcat);
    }
     
     //    Fonctionnel dans PICTURE
    public function testServicesSupp11(){
         //login user
        $this->setSession();
        $picId = $this->Services->get(3)->picture_id;

        //supprimer picture3
        $this->post('/services/delete/3');
        $deletedpic=$this->Pictures->find()->where(['id'=> $picId])->toArray();
        $this->assertEmpty($deletedpic);
    } 
       
/**************DestServices*************************************************/
   //Propagation de la destruction dans PICTURES pour picture_id
    public function testServicesSupp15(){
       //login user
        $this->setSession();
        $picId = $this->Services->get(3)->picture_id;

        //supprimer picture3
        $this->post('/services/delete/3');
        $deletedpic=$this->Pictures->find()->where(['id'=> $picId])->toArray();
        $this->assertEmpty($deletedpic);
        
    }
    
   //  Restriction sur la clef étrangère SERVICE_CATEGORY_ID et l'usager connecté
    public function testServicesSupp16(){
       
       //login user
        $this->setSession();
        //trouve le premier servicede la premiere catégorie de l'entreprise 2 
      $ServId = $this->ServiceCategories->find()
            ->where(['enterprise_id' => 2])
            ->contain('Services')
            ->first()
            ->services[0]
            ->id;


       
        $this->post('/my-enterprise/serviceCategories/delete/'.$ServId);
        $Deletedserv=$this->ServiceCategories->find()
            ->where(['enterprise_id' => 2])
            ->contain('Services')
            ->first()
            ->services[0]
            ->id;
        $this->assertEquals( $ServId,$Deletedserv);

    }
   
 
    //    Destruction complète de l'enregistrement 
    public function testServicesSupp17(){
        $this->setSession();
      $servId = $this->Services->get(3)->id;

        //supprimer produit 1
        $this->post('/services/delete/3');
        $DeleteServ=$this->Services->find()->where(['id'=> $servId])->toArray();
        $this->assertEmpty($DeleteServ);
    }

    /**************DestPicture *************************************************/
   
    //   Restriction sur la clef étrangère SERVICE_CATEGORY_ID et l'usager connecté
    public function testServicesSupp20(){
        //login user
        $this->setSession();
        //trouve le premier service de la premiere catégorie de l'entreprise 2 
      $ServId = $this->ServiceCategories->find()
            ->where(['enterprise_id' => 2])
            ->contain('Services')
            ->first()
            ->services[0]
            ->id;
             //trouve la photo du premier service de la premiere catégorie de l'entreprise 2 
      $ServpicId = $this->ServiceCategories->find()
            ->where(['enterprise_id' => 2])
            ->contain('Services')
            ->first()
            ->services[0]
            ->picture_id;

        //supprimer picture
        $this->post('/services/delete/'. $ServId);
        $deletedpic=$this->Pictures->find()->where(['id'=> $ServpicId])->toArray();
        $this->assertEmpty($deletedpic);
    }  
   
         //   Destruction complète de l'enregistrement
    public function testServicesSupp21(){
       //login user
        $this->setSession();
        $picId = $this->Services->get(3)->picture_id;

        //supprimer picture3
        $this->post('/services/delete/3');
        $deletedpic=$this->Pictures->find()->where(['id'=> $picId])->toArray();
        $this->assertEmpty($deletedpic);

    }
    /**************DestServiceCategory *************************************************/


    //Demande confirmation de suppression
    public function testServicescatSupp25(){
          //login user
        $this->setSession();
        $this->get('my-enterprise/services');
        //S'assurer que le bouton delete contient un onclick pour afficher la modal
        $this->assertResponseContains('<a class="delete" href="javascript:void(0)" title="Delete Category " onclick="ModalCatDelete(');
    }

     //Liste les services qui seront supprimé
    public function testServicescatSupp26(){
       $this->setSession();
        $this->get('/my-enterprise/serviceCategories/listServices/1');
        //c'est le seul service 
       
       $this->assertResponseContains('[{"id":1,"name":"Lorem ipsum dolor sit amet"}]');
    }
   
       //Demande une seconde confirmation
    public function testServicescatSupp27(){
        $this->setSession();
        $this->get('my-enterprise/services');
        $this->assertResponseContains('confirm(&quot;Do you really want to delete this category and all its services?&quot;)');
    }
     
    //Supprime dans SERVICES les associations service_category_id
    public function testServicescatSupp28(){
       //login user
        $this->setSession();
      $catId = $this->ServiceCategories->get(2)->id;

        //supprimer produit 1
        $this->post('/my-enterprise/serviceCategories/delete/2');
       $DeleteServ=$this->Services->find()->where(['service_category_id'=> $catId])->toArray();
        $this->assertEmpty($DeleteServ);
    }

    //Supprime dans PICTURES les assocications picture_id pour chaque services
    public function testServicescatSupp29(){
       //login user
        $this->setSession();
     $servs = $this->Services->find()->where(['service_category_id'=>2])->toArray();
        $deletedPic = array_map(function($p){return $p->picture_id;}, $servs);

        //supprimer cat 1
        $this->post('/my-enterprise/serviceCategories/delete/2');
        $deleted = $this->Pictures->find('all')
            ->where(['id IN'=>$deletedPic])->toArray();

        $this->assertEmpty($deleted);
    } 
       /**************************************Messages*********************************************************/
    //La liste doit être restreinte parmis la liste des catégories de l'entreprise
      public function testServicescatSupp33(){

         //login user
        $this->setSession();
        $expected = $this->ServiceCategories->find()->where(['enterprise_id'=>$this->Auth['User']['enterprise']['id']])->toArray();
        $notexpected = $this->ServiceCategories->find()->where(['enterprise_id'=>2])->toArray();
       
        $this->get('/my-enterprise/services');
        
        $this->assertNotContains($notexpected, $expected);
    } 
    
    //des messages de validation sont affichés à l'usager
    public function testServicescatSupp34(){
         //login user
        $this->setSession();
        //service
        $this->post('/services/delete/3');
        $this->assertContains('has been deleted', $_SESSION['Flash']['flash'][0]['message']);

        //categories
        $this->post('/my-enterprise/serviceCategories/delete/2');
        $this->assertContains('have been deleted', $_SESSION['Flash']['flash'][0]['message']);
    }

    private function buildData(){
        return [
            'id' => 1,
            'name' => 'Service test',
            'price' => 5.99,
            'service_category_id' => 1,
            'picture_id'=>1,
            'image' =>  ['tmp_name' => 'webroot/img/cake-logo.png',
                       'type' => 'image/png'],
        ];
    }

    private function setSession(){
        $this->session([
            'Auth' => $this->Auth
        ]);
    }

}