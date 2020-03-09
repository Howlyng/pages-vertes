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
class ModifServicesTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.products',
        'app.product_categories',
        'app.enterprises',
        'app.users',
        'app.pictures',
        'app.employees',
        'app.services',
        'app.service_categories',
        'app.suppliers',
        'app.employe_categories',
        'app.supplier_categories'
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
    public function testServicesModif9(){
        //login user
        $this->setSession();
        $data = $this->buildData();
    
        $this->post('/my-enterprise/services/1', $data);

        $moded = $this->Services->get(1, ["contain"=>"Pictures"]);

        $this->assertEquals($moded["name"], $data["name"]);
        $this->assertEquals($moded["price"], $data["price"]);
        $this->assertEquals( $moded["service_category_id"] , $data["service_category_id"]);
        $this->assertEquals($moded["picture"]["base64image"], "data:image/png;base64," . base64_encode(file_get_contents($data["image"]["tmp_name"])));

    }
    
     //    Pas de valeur null dans SERVICES
    public function testServicesModif10(){
        //login user
         $this->setSession();
        $data = $this->buildData();
    
        $this->post('/my-enterprise/services/1', $data);

        $newservice= $this->Services->get(['id'=>1]);
    
        $this->assertNotEmpty($newservice->id);
        $this->assertNotNull($newservice->id);
        $this->assertNotEmpty($newservice->name);
        $this->assertNotNull($newservice->name);
        $this->assertNotEmpty($newservice->price);       
        $this->assertNotNull($newservice->price);
        $this->assertNotEmpty($newservice['service_category_id']); 
        $this->assertNotNull($newservice['service_category_id']); 
        $this->assertNotEmpty($newservice['picture_id']);  
        $this->assertNotNull($newservice['picture_id']);
    }
     
     //    Fonctionnel dans SERVICE_CATEGORIES
    public function testServicesModif11(){
        //login user
        $this->setSession();
        
        $newCat = [       
            'name' => 'New serv Category'       
        ]; 
        $this->post('/my-enterprise/serviceCategories/1', $newCat);

        $result = $this->Services->ServiceCategories->get(1);
       $this->assertEquals($result['name'], $newCat["name"]);
    } 
    

     //    Pas de valeur null dans SERVICE_CATEGORIES
    public function testServicesModif12(){
       //login user
        $this->setSession();
        
        $newCat = [
            'id'=> 1,  
            'name' => 'New serv Category',
            'enterprise_id' => 1,
        ]; 
        $this->post('/my-enterprise/serviceCategories/1', $newCat);

        $result = $this->Services->ServiceCategories->get(1);
        $this->assertNotNull($result['name']);
        $this->assertNotEmpty($result['name']);
        
    }
    
   //    Unicité de la clé primaire id (n'est pas modifiable)
    public function testServicesModif16(){
       //login user
         $this->setSession();
        $data = $this->buildData();
        $data["id"]=90;
        $this->post('/my-enterprise/services/1', $data);

        $service1= $this->Services->find()->first();
    
        $this->assertNotEmpty($service1);
        $this->assertNotNull($service1);
        
        $newservice= $this->Services->find()->where(['id'=>90])->toArray();
        $this->assertEmpty($newservice);

    }
    
 
    //    Existence de la clé étrangère sevices_category_id
    public function testServicesModif17(){
        //login user
        $this->setSession();
        $data = $this->buildData();
       $data['service_category_id']=800;

        $this->post('/my-enterprise/services/1', $data);
        $newservice= $this->Services->find()->first();
        $this->assertNotEquals($newservice['service_category_id'], $data['service_category_id']);
       $this->assertResponseContains('This value does not exist');
    }
  
    //    cohésion sevices_category_id et l'usager connecter
    public function testServicesModif18(){
        //login user
        $this->setSession();
        $data = $this->buildData();
         $data['service_category_id']=4;
       $this->post('/my-enterprise/services/1', $data);
         $newservice= $this->Services->find()->first();
        $this->assertNotEquals($newservice['service_category_id'], $data['service_category_id']); 
       $this->assertResponseContains('This category does not exist in your enterprise');
    }  
    
         //    la clée picture_id existe
    public function testServicesModif19(){
        //login user
        $this->setSession();
        $data = $this->buildData();
          
       $expectedp =  sizeof($this->Pictures->find()->toArray());
        $this->post('/my-enterprise/services/1', $data);
       
         $resp =  sizeof($this->Pictures->find()->toArray());
        $this->assertEquals($resp, $expectedp);
        
        $modedservices = $this->Services->find()->first();
      
        $lastpicture =$this->Pictures->get(['id'=>$modedservices['picture_id']]);
        $this->assertNotEmpty($lastpicture['base64image']);

    }



    //     picture_id null si aucun
    public function testServicesModif20(){
        //login user
      
        $this->setSession();
       
        // test que quand pas de photo la photo est pas modifier 
         $servicemod = $this->Services->find()->first();
          $lastpicture =$this->Pictures->get(['id'=>$servicemod['picture_id']]);
            $imdata = $lastpicture['base64image'];
       
        $data =  [
            'id' => 1, 
            'name' => 'Service test',
            'price' => 5.99,
            'service_category_id' => 1, 
            'picture_id'=>1,
            'image' =>  ['tmp_name' => ''],
            'Deleteimage'=>0,
        ];
             

       $expectedp =  sizeof($this->Pictures->find()->toArray())  ;
       $this->post('/my-enterprise/services/1', $data); 
         $resp =  sizeof($this->Pictures->find()->toArray()) ;
         $this->assertEquals($resp, $expectedp);
        
        $servicemod = $this->Services->find()->first();
      
        $this->assertNotEquals($servicemod['picture_id'],null);
        $lastpicture =$this->Pictures->get(['id'=>$servicemod['picture_id']]);
        $this->assertNotEmpty($lastpicture['base64image']);
        $this->assertEquals($lastpicture['base64image'], $imdata);

         // test que quand pas de photo et coche delete photo la photo est supprimer 
      // ajoute une photo et un  service pcq photo référencer par plusieur chosse 

          $this->setSession(); 
          $data = null;
        $data =   [
            'name' => 'Service testi',
            'price' => 5.99,
            'service_category_id' => 1,
            'image' =>  ['tmp_name' => 'webroot/img/cake-logo.png',
                       'type' => 'image/png'],
        ];
        $expected = sizeof($this->Services->find()->toArray()) +1 ;

        $this->post('/my-enterprise/services/add', $data);
          $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
        $newservice= $this->Services->find()->last();
    
        $this->assertNotEmpty($newservice->name);
        $this->assertNotEmpty($newservice->price);
        $this->assertNotEmpty($newservice['service_category_id']); 
        $this->assertNotEmpty($newservice['picture_id']);
      
          $data= null;
        $data2 =  [
        'id'=>4,
            'name' => 'Service test222',
            'price' => 5.99,
            'service_category_id' => 1, 
            'image' =>  ['tmp_name' => '',
                       'type' => ''
                       ],
            'Deleteimage'=>'1',
        ];
        $idpicdel = $newservice['picture_id'];  
     
       $this->post('/my-enterprise/services/'.$newservice['id'], $data2); 
         $this->Services = TableRegistry::get('Services');
        $servicemoded = $this->Services->find()->last();
       // var_dump( $servicemoded);
        $this->assertEquals( $servicemoded['picture_id'], null);
     $delpicture =$this->Pictures->find()->where(['id'=>$idpicdel])->toArray();
            $this->assertEmpty($delpicture);
       
       
    } 
   
    //NAME !=Null
    public function testServicesModif21(){
        $this->setSession();
        $data = $this->buildData();
        $this->post('/my-enterprise/services/1', $data);
       
        //Assert pas null ou empty
        $this->assertNotEmpty($this->Services->get(1)->name);
        $this->assertNotNull($this->Services->get(1)->name);

        $data['name'] = null;
    
       $this->post('/my-enterprise/services/1', $data);
         $this->assertNotNull($this->Services->get(1)->name);
    }

     //NAME >5
    public function testServicesModif22(){
        $this->setSession();
      
        $data = $this->buildData();
        $this->post('/my-enterprise/services/1', $data);
       
        $namelenght = strlen($this->Services->get(1)->name) ;
        //Assert pas null ou <5
        $this->assertTrue( $namelenght > 5);
        $this->assertNotNull($this->Services->get(1)->name);

     //assert  nom trop petit ajoute pas 
        $data['name'] = '1234';
        $this->post('/my-enterprise/services/1', $data);
         $this->assertNotEquals($this->Services->get(1)->name,$data['name'] );
    }
    
       //NAME < 50
    public function testServicesModif23(){
        $this->setSession();
      
        $data = $this->buildData();
        $this->post('/my-enterprise/services/1', $data);
       
        $namelenght = strlen($this->Services->get(1)->name) ;
        //Assert pas null ou >50
        $this->assertTrue( $namelenght < 50);
        $this->assertNotNull($this->Services->get(1)->name);

    
        $newName = "";
        for($i=0;$i<51;$i++){
            $newName = $newName . "a";
        }
        //Assert marche pas
        $data['name'] = $newName;
       $this->post('/my-enterprise/services/1', $data);
         $this->assertNotEquals($this->Services->get(1)->name,$data['name'] );
    }
    
    //NAME  ne contient pas de caractère html
    public function testServicesModif24(){
        $this->setSession();
        $data = $this->buildData();
        $data['name']= "0<div>";

        $this->post('/my-enterprise/services/1', $data);
         $this->assertNotEquals($this->Services->get(1)->name, $data['name'] );
    }
    
     //PRICE !=Null
    public function testServicesModif25(){
        $this->setSession();
        $data = $this->buildData();
        $this->post('/my-enterprise/services/1', $data);
       
        //Assert pas null ou empty
        $this->assertNotEmpty($this->Services->get(1)->price);
        $this->assertNotNull($this->Services->get(1)->price);

        $data['price'] = null;
    
       $this->post('/my-enterprise/services/1', $data);
         $this->assertNotNull($this->Services->get(1)->price);
    }
   
     //PRICE >0
    public function testServicesModif26(){
       $this->setSession();
     
        $data = $this->buildData();
        $this->post('/my-enterprise/services/1', $data);
       
        $pricee = $this->Services->get(1)->price;
        //Assert pas null ou <5
        $this->assertTrue( $pricee > 5);
        $this->assertNotNull($this->Services->get(1)->price);

     //assert  nom trop petit ajoute pas 
        $data['price'] = 0;
        $this->post('/my-enterprise/services/1', $data);
         $this->assertNotEquals($this->Services->get(1)->price,$data['price'] );
    }
     
     //PRICE < 1000000
    public function testServicesModif27(){
        $this->setSession();
      
        $data = $this->buildData();
        $this->post('/my-enterprise/services/1', $data);
       
        $pricee = $this->Services->get(1)->price;
        //Assert pas null ou > 1000000
        $this->assertTrue( $pricee < 1000000);
        $this->assertNotNull($this->Services->get(1)->price);

     //assert  nom trop petit ajoute pas 
        $data['price'] = 1000000;
        $this->post('/my-enterprise/services/1', $data);
         $this->assertNotEquals($this->Services->get(1)->price,$data['price'] );
    }

/*************************PICTURE************************************/

    //unicité clé primaire
    public function testPictureModif30(){
        $lastPictureId = $this->Services->Pictures->get(1)->id;
        $this->setSession();
        $data = $this->buildData();
      
        $this->post('/my-enterprise/services/1', $data);
        $this->assertEquals($lastPictureId, $this->Services->Pictures->get(1)->id);
    }

    //Cohérence de la clef étrangère picture_id et l'usager connecté

    public function testPictureModif31(){
        $this->setSession();
        $data = $this->buildData();
        $data['picture_id']= 3;

        $serv =  $this->Services->get(1);
        $this->post('/my-enterprise/services/1', $data);
         
        // assert modif un service et  image 
        $this->assertEquals( $serv['picture_id'],  $this->Services->get(1)->picture_id);
    }

//    Cohérence de la clef étrangère picture_id et le parent(employees, products...)
    public function testPictureModif32(){
        $this->setSession();
        $data = $this->buildData();
        $data['picture_id']= 3;

        $serv =  $this->Services->get(1);
        $this->post('/my-enterprise/services/1', $data);

        // assert modif un service et  image 
        //pas modifier pcq pas a lui 
        $this->assertEquals( $serv['picture_id'],  $this->Services->get(1)->picture_id);
    }

//    Vérification que base64image est bien formaté: Début par "data:image/png;base64,"
    public function testPictureModif33(){
        $this->setSession();
        $data = $this->buildData();

      
        $this->post('/my-enterprise/services/1', $data);
 
        $pos = strpos($this->Services->get(1,['contain'=>'Pictures'])->picture->base64image,"data:image/png;base64,") === 0;
        $this->assertTrue($pos);
    }
   
//Vérification que base64image est bien formaté: Fin par l'image encodé en base64
    public function testPictureModif34(){
        $this->setSession();
        $data = $this->buildData();
        $this->post('/my-enterprise/services/1', $data);
       
        $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Services->get(1,['contain'=>'Pictures'])->picture->base64image,$base64) !== false;
        $this->assertTrue($pos);
    }
     
//Vérification que base64image est non null
    public function testPictureModif35(){
        $this->setSession();
        $data = $this->buildData();


        $this->post('/my-enterprise/services/1', $data);
       $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Services->get(1,['contain'=>'Pictures'])->picture->base64image, $base64) !== false;
        $this->assertTrue($pos);

        $data['image']['tmp_name'] = null;
        $data['Deleteimage']=0;
        $this->post('/my-enterprise/services/1', $data);
        $this->assertNotNull($this->Services->get(1,['contain'=>'Pictures'])->picture->base64image);

    }


/**************ServiceCategories*************************************************/
    
     //    Valleur id de servicecategories unique générer
    public function testServicesCategoriesModif38(){
        //login user
        $this->setSession();
        $data['name']='aaaaaa';
         $data["id"]=43;
        
        $this->post('/my-enterprise/serviceCategories/1', $data);

         $service1= $this->ServiceCategories->find()->first();
    
        $this->assertNotEmpty($service1);
        $this->assertNotNull($service1);
        
        $newservice= $this->ServiceCategories->find()->where(['id'=>43])->toArray();
        $this->assertEmpty($newservice);
    }
    

    //    Existence de la clé étrangère ENTERPRISE_ID 
    public function testServicesCategoriesModif39(){
        //login user
        $this->setSession();
       $data['name']='aaaaaa';
       $data['enterprise_id']=5;
 
        $this->post('/my-enterprise/serviceCategories/1', $data);
           $newservice= $this->ServiceCategories->find()->first();
        $this->assertNotEquals($newservice['enterprise_id'], $data['enterprise_id']);
    }
 
 
     //NAME !=Null
    public function testServicesCategoriesModif40(){
        $this->setSession();
        $data['name']='aaaaaa';
         $this->post('/my-enterprise/serviceCategories/1', $data);
        //Assert pas null ou empty
        $this->assertNotEmpty($this->ServiceCategories->get(1)->name);
        $this->assertNotNull($this->ServiceCategories->get(1)->name);

        $data['name'] = null;
    
       $this->post('/my-enterprise/serviceCategories/1', $data);
         $this->assertNotNull($this->ServiceCategories->get(1)->name);
    }
     //NAME >5
    public function testServicesCategoriesModif41(){
        $this->setSession();
        $data['name']='aaaaaa';
        
        $this->post('/my-enterprise/serviceCategories/1', $data);
        $namelenght = strlen($this->ServiceCategories->get(1)->name) ;
        //Assert pas null ou >5
        $this->assertTrue( $namelenght > 5);
        $this->assertNotNull($this->ServiceCategories->get(1)->name);

     //assert  nom trop petit ajoute pas 
        $data['name'] = '1234';
        $this->post('/my-enterprise/serviceCategories/1', $data);
         $this->assertNotEquals($this->ServiceCategories->get(1)->name,$data['name'] );
    }

   
     //NAME < 50
    public function testServicesCategoriesModif42(){
        $this->setSession();
        $data['name']='aaaaaa';
               
        $this->post('/my-enterprise/serviceCategories/1', $data);
        $namelenght = strlen($this->ServiceCategories->get(1)->name) ;
        //Assert pas null ou <50
        $this->assertTrue( $namelenght<50);
        $this->assertNotNull($this->ServiceCategories->find()->last()->name);

    
        $newName = "";
        for($i=0;$i<51;$i++){
            $newName = $newName . "a";
        }
        //Assert marche pas
        $data['name'] = $newName;
       $this->post('/my-enterprise/serviceCategories/1', $data);
         $this->assertNotEquals($this->ServiceCategories->get(1)->name,$data['name'] );
    }

    
    //NAME  ne contient pas de caractère html
    public function testServicesCategoriesModif43(){
        $this->setSession();
       
        $data['name']= "4ee<div></div>";

         $this->post('/my-enterprise/serviceCategories/1', $data);
        $this->assertFalse(strpos($this->ServiceCategories->get(1)->name, $data['name']));
    }



/*******************Saisies et messages*******************************************************/

    public function testMessageModif46(){
       $this->setSession();
       $data = $this->buildData();
       $data['name'] = null;

       $this->post('/my-enterprise/services/1', $data);
       $this->assertResponseContains('cannot be left empty');
    
    }
    //on ne peut saisir l'attribut SERVICE_CATEGORY_ID par une liste
    public function testMessageModif47(){
       $this->setSession();
       $this->get('/my-enterprise/services/1');

       //les options de la liste propose le  nom  des categoie
       $this->assertResponseContains(
        '><select name="service_category_id" class="form-control wide" id="service-category-id"><option value="1" selected="selected">Lorem ipsum dolor sit amet</option>');

    }
      //on ne peut saisir une image de service
     public function testMessageModif49(){
       $this->setSession();
       $this->get('/my-enterprise/services/1');

       //les options de la liste propose le  nom  des categoie
       $this->assertResponseContains('<input type="file" name="image" class="form-control-file d-inline" id="image">');

    }

     //des messages de validation sont affichés à l'usager
      public function testMessageModif50(){
        $this->setSession();
        $data = $this->buildData();
       $data['service_category_id']=4;
       
        $this->post('/my-enterprise/services/1', $data);

       $this->assertResponseContains('This category does not exist in your enterprise');
    }
   
   //les messages de validation sont précis pour l'usager
   public function testMessageModif51(){
       $this->setSession();
       $data = $this->buildData();

       $this->post('/my-enterprise/services/1', $data);

       $this->assertContains($_SESSION['Flash']['flash'][0]['message'], 'The service has been saved.');
    }
     
     //La liste de sélection SERVICE_CATEGORY_ID est fonctionnelle et conforme à l'usager connecté
    public function testMessageModif52(){
       $this->setSession();
       $this->get('/my-enterprise/services/1');

       //les options de la liste propose le  nom  des categoie
       $this->assertResponseContains(
        '<label for="service-category-id">Category</label><select name="service_category_id" class="form-control wide" id="service-category-id"><option value="1" selected="selected">Lorem ipsum dolor sit amet</option><option value="2">Lorem ipsum dolor sit amet</option><option value="3">Lorem ipsum dolor sit amet</option><option value="5">Lorem ipsum dolor sit amet</option><option value="7">Lorem ipsum dolor sit amet</option><option value="8">Lorem ipsum dolor sit amet</option>');

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