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
class AjoutServicesTest extends IntegrationTestCase
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
        $this->Entreprises = TableRegistry::get('Enterprises');
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
    public function testServicesAjout9(){
        //login user
        $this->setSession();
        $data = $this->buildData();
        $expected = sizeof($this->Services->find()->toArray()) +1 ;

        $this->post('/my-enterprise/services/add', $data);

        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
    }
     
     //    Pas de valeur null dans SERVICES
    public function testServicesAjout10(){
        //login user
        $this->setSession();
        $data = $this->buildData();
        $expected = sizeof($this->Services->find()->toArray()) +1 ;

        $this->post('/my-enterprise/services/add', $data);

        $newservice= $this->Services->find('all')->last();

        $this->assertNotEmpty($newservice->name);
        $this->assertNotEmpty($newservice->price);
        $this->assertNotEmpty($newservice['service_category_id']); 
        $this->assertNotEmpty($newservice['picture_id']);
    }
    
     //    Fonctionnel dans SERVICE_CATEGORIES
    public function testServicesAjout11(){
        //login user
        $this->setSession();
        
        $expected = sizeof($this->ServiceCategories->find()->toArray()) +1 ;
        $newCat = [
            'name' => 'New serv Category',
            'enterprise_id' => 1,
        ]; 
        $this->post('/my-enterprise/serviceCategories/add', $newCat);

        $result = sizeof($this->ServiceCategories->find()->toArray());
        $this->assertEquals($expected, $result);
    } 
    

     //    Pas de valeur null dans PICTURE
    public function testServicesAjout12(){
        //login user
        $this->setSession();
        $data = $this->buildData();

        $this->post('/my-enterprise/services/add', $data);
        $pictureid= $this->Services->find('all')->last()->picture_id;
         
        $lastpic= TableRegistry::get('pictures')->get(['id'=>$pictureid]);

        $this->assertNotEmpty($lastpic->id);
        $this->assertNotEmpty($lastpic->base64image);
        
    }
     
   //    Valleur id de service unique générer
    public function testServicesAjout16(){
        //login user
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->Services->find()->last()->id +1  ;

        $this->post('/my-enterprise/services/add', $data);

        $result =  $this->Services->find()->last()->id;
        $this->assertEquals($expected, $result);
    }
    

    //    Existence de la clé étrangère sevices_category_id
    public function testServicesAjout17(){
        //login user
        $this->setSession();
        $data = $this->buildData();
       $data['service_category_id']=800;

        $this->post('/my-enterprise/services/add', $data);

       $this->assertResponseContains('This value does not exist');
    }
  
    //    cohésion sevices_category_id et l'usager connecter
    public function testServicesAjout18(){
        //login user
        $this->setSession();
        $data = $this->buildData();
         $data['service_category_id']=4;
        $this->post('/my-enterprise/services/add', $data);

       $this->assertResponseContains('This category does not exist in your enterprise');
    }  
    
     
     //    la clée picture_id existe
    public function testServicesAjout19(){
        //login user
        $this->setSession();
        $data = $this->buildData();
          
    // verifie que il cré un service et une photo
       $expected = sizeof($this->Services->find()->toArray()) +1;

       $expectedp =  sizeof($this->Pictures->find()->toArray())+1;
        $this->post('/my-enterprise/services/add', $data);
       
        $result = sizeof($this->Services->find()->toArray());
 
         $resp =  sizeof($this->Pictures->find()->toArray());
        $this->assertEquals($expected, $result);
        
        $this->assertEquals($resp, $expectedp);
        
        $lastid = $this->Services->find("all")->last();
      
        $lastpicture =$this->Pictures->get(['id'=>$lastid['picture_id']]);
        $this->assertNotEmpty($lastpicture['base64image']);

    }


    //     picture_id null si aucun
    public function testServicesAjout20(){
        //login user
        $this->setSession();
        $data =  [
            'name' => 'Service test',
            'price' => 5.99,
            'service_category_id' => 1, 
            'image' =>  ['tmp_name' => ''],
        ];
          
    // verifie que il cré un service et pas de photo
       $expected = sizeof($this->Services->find()->toArray()) +1 ;

       $expectedp =  sizeof($this->Pictures->find()->toArray())  ;
        $this->post('/my-enterprise/services/add', $data);
       
        $result = sizeof($this->Services->find()->toArray());
 
         $resp =  sizeof($this->Pictures->find()->toArray()) ;
        $this->assertEquals($expected, $result);
        
        $this->assertEquals($resp, $expectedp);
        
        $lastid = $this->Services->find("all")->last();
      
        $this->assertEquals($lastid['picture_id'],null);

    }
    
    //NAME !=Null
    public function testServicesAjout21(){
        $this->setSession();
        $data = $this->buildData();
        $expected = sizeof($this->Services->find()->toArray()) +1 ;
        $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
       
        //Assert pas null ou empty
        $this->assertNotEmpty($this->Services->find()->last()->name);
        $this->assertNotNull($this->Services->find()->last()->name);

        $data['name'] = null;
        $expected = sizeof($this->Services->find()->toArray()) ;
        $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
    }

     //NAME >5
    public function testServicesAjout22(){
        $this->setSession();
        $data = $this->buildData();
        $expected = sizeof($this->Services->find()->toArray()) +1 ;
        $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
       
        $namelenght = strlen($this->Services->find()->last()->name) ;
        //Assert pas null ou <5
        $this->assertTrue( $namelenght > 5);
        $this->assertNotNull($this->Services->find()->last()->name);

     //assert  nom trop petit ajoute pas 
        $data['name'] = '1234';
        $expected = sizeof($this->Services->find()->toArray()) ;
         $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
    }
    
     //NAME < 50
    public function testServicesAjout23(){
        $this->setSession();
        $data = $this->buildData();
        $expected = sizeof($this->Services->find()->toArray()) +1 ;
        $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
       
        $namelenght = strlen($this->Services->find()->last()->name) ;
        //Assert pas null ou <5
        $this->assertTrue( $namelenght<50);
        $this->assertNotNull($this->Services->find()->last()->name);

    
        $newName = "";
        for($i=0;$i<51;$i++){
            $newName = $newName . "a";
        }
        //Assert marche pas
        $data['name'] = $newName;
        $expected = sizeof($this->Services->find()->toArray()) ;
         $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
    }
    
    //NAME  ne contient pas de caractère html
    public function ttestServicesAjout24(){
        $this->setSession();
        $data = $this->buildData();
        $data['name']= "0<div>";

        $this->post('/my-enterprise/services/add', $data);
        $this->assertFalse(strpos($this->Services->find()->last()->name, '<div>'));
    }
    
     //PRICE !=Null
    public function testServicesAjout25(){
        $this->setSession();
        $data = $this->buildData();
        $expected = sizeof($this->Services->find()->toArray()) +1 ;
        $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
       
        //Assert pas null ou empty
        $this->assertNotEmpty($this->Services->find()->last()->price);
        $this->assertNotNull($this->Services->find()->last()->price);

        $data['price'] = null;
        $expected = sizeof($this->Services->find()->toArray()) ;
        $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
    }

     //PRICE >0
    public function testServicesAjout26(){
        $this->setSession();
        $data = $this->buildData();
        $expected = sizeof($this->Services->find()->toArray()) +1 ;
        $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
       
        
        //Assert pas null ou >0
        $this->assertTrue( $this->Services->find()->last()->price > 0);
        $this->assertNotNull($this->Services->find()->last()->price);

     //assert  price trop petit ajoute pas 
        $data['price'] = 0;
        $expected = sizeof($this->Services->find()->toArray()) ;
         $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
    }
    
     //NAME < 1000000
    public function testServicesAjout27(){
        $this->setSession();
        $data = $this->buildData();
        $expected = sizeof($this->Services->find()->toArray()) +1 ;
        $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
       

        //Assert pas null ou <1000000
        $this->assertTrue( $this->Services->find()->last()->price < 1000000);
        $this->assertNotNull($this->Services->find()->last()->price);

    
        $data['price'] = 1000000;
        $expected = sizeof($this->Services->find()->toArray()) ;
         $this->post('/my-enterprise/services/add', $data);
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
    }

/*************************PICTURE************************************/

    //unicité clé primaire
    public function testPictureAjout30(){
        $lastPictureId = $this->Services->Pictures->find()->last()->id;
        $this->setSession();
        $data = $this->buildData();
        $expected = $lastPictureId+1;
        $this->post('/my-enterprise/services/add', $data);
        $this->assertEquals($expected, $this->Services->Pictures->find()->last()->id);
    }

    //Cohérence de la clef étrangère picture_id et l'usager connecté

    public function testPictureAjout31(){
        $this->setSession();
        $data = $this->buildData();
        $data['picture_id']= 1;

        $expected = sizeof($this->Services->find()->toArray())+1;
        $expectedPic = $this->Pictures->find()->count()+1;
        $this->post('/my-enterprise/services/add', $data);

        // assert ajouter un service et une image 
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
        $this->assertEquals($expectedPic, $this->Pictures->find()->count());

        $forcedImage64 = $this->Pictures->get($data['picture_id'])->base64image;
        $this->assertNotEquals($this->Pictures->find()->last()->base64image, $forcedImage64);
    }

//    Cohérence de la clef étrangère picture_id et le parent(employees, products...)
    public function testPictureAjout32(){
        $this->setSession();
        $data = $this->buildData();

        $expected = sizeof($this->Services->find()->toArray())+1;
        $expectedPic = $this->Pictures->find()->count()+1;
        $this->post('/my-enterprise/services/add', $data);

        /// assert ajouter un service et une image 
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
        $this->assertEquals($expectedPic, $this->Pictures->find()->count());

        $lastPic = $this->Pictures->find()->last();
        $lastServ = $this->Services->find()->last();
        $this->assertEquals($lastPic->id, $lastServ->picture_id );
    }

//    Vérification que base64image est bien formaté: Début par "data:image/png;base64,"
    public function testPictureAjout33(){
        $this->setSession();
        $data = $this->buildData();

        $expected = sizeof($this->Services->find()->toArray())+1;
        $expectedPic = $this->Pictures->find()->count()+1;
        $this->post('/my-enterprise/services/add', $data);

        // assert ajouter un service et une image 
        $result = sizeof($this->Services->find()->toArray());
        $this->assertEquals($expected, $result);
        $this->assertEquals($expectedPic, $this->Pictures->find()->count());

        $pos = strpos($this->Pictures->find()->last()->base64image,"data:image/png;base64,") === 0;
        $this->assertTrue($pos);
    }
//Vérification que base64image est bien formaté: Fin par l'image encodé en base64
    public function testPictureAjout34(){
        $this->setSession();
        $data = $this->buildData();
        $this->post('/my-enterprise/services/add', $data);
       
        $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Pictures->find()->last()->base64image,$base64) !== false;
        $this->assertTrue($pos);
    }
//Vérification que base64image est non null
    public function testPictureAjout35(){
        $this->setSession();
        $data = $this->buildData();


        $this->post('/my-enterprise/services/add', $data);
        $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Pictures->find()->last()->base64image, $base64) !== false;
        $this->assertTrue($pos);

        $expected = $this->Pictures->find()->count();
        $data['image']['tmp_name'] = null;
        $this->post('/my-enterprise/services/add', $data);
        $result = $this->Pictures->find()->count();
        $this->assertEquals($expected, $result);

    }


/**************ServiceCategories*************************************************/
    
     //    Valleur id de servicecategories unique générer
    public function testServicesCategoriesAjout38(){
        //login user
        $this->setSession();
        $data['name']='aaaaaa';
        $expected = $this->ServiceCategories->find()->last()->id +1  ;

        $this->post('/my-enterprise/serviceCategories/add', $data);

        $result =  $this->ServiceCategories->find()->last()->id;
        $this->assertEquals($expected, $result);
    }
    

    //    Existence de la clé étrangère ENTERPRISE_ID 
    public function testServicesCategoriesAjout39(){
        //login user
        $this->setSession();
       $data['name']='aaaaaa';

        $this->post('/my-enterprise/serviceCategories/add', $data);

       $result = $this->ServiceCategories->find()->last()->enterprise_id;
        $this->assertTrue($this->ServiceCategories->Enterprises->exists(['id'=>$result]));
    }
  
    //    cohésion ENTERPRISE_ID  et l'usager connecter
    public function testServicesCategoriesAjout40(){
        //login user
        $this->setSession();
        $data['name']='aaaaaa';
       $expected = sizeof($this->ServiceCategories->find()->toArray()) +1 ;
        $this->post('/my-enterprise/serviceCategories/add', $data);
        $result = sizeof($this->ServiceCategories->find()->toArray());
        $this->assertEquals($expected, $result);
          $lastcat = $this->ServiceCategories->find()->last();
          $result = $this->Entreprises->find()->where(['id'=>$lastcat->enterprise_id])->first();
          $this->assertEquals($this->Auth['User']['id'], $result->owner_id);
    }  

     //NAME !=Null
    public function testServicesCategoriesAjout41(){
        $this->setSession();
        $data['name']='aaaaaa';
        $expected = sizeof($this->ServiceCategories->find()->toArray()) +1 ;
        $this->post('/my-enterprise/serviceCategories/add', $data);
        $result = sizeof($this->ServiceCategories->find()->toArray());
        $this->assertEquals($expected, $result);
       
        //Assert pas null ou empty
        $this->assertNotEmpty($this->ServiceCategories->find()->last()->name);
        $this->assertNotNull($this->ServiceCategories->find()->last()->name);

        $data['name'] = null;
        $expected = sizeof($this->ServiceCategories->find()->toArray()) ;
        $this->post('/my-enterprise/serviceCategories/add', $data);
        $result = sizeof($this->ServiceCategories->find()->toArray());
        $this->assertEquals($expected, $result);
    }

     //NAME >5
    public function testServicesCategoriesAjout42(){
        $this->setSession();
        $data['name']='aaaaaa';
        $expected = sizeof($this->ServiceCategories->find()->toArray()) +1 ;
        $this->post('/my-enterprise/serviceCategories/add', $data);
        $result = sizeof($this->ServiceCategories->find()->toArray());
        $this->assertEquals($expected, $result);
       
        $namelenght = strlen($this->ServiceCategories->find()->last()->name) ;
        //Assert pas null ou <5
        $this->assertTrue( $namelenght > 5);
        $this->assertNotNull($this->ServiceCategories->find()->last()->name);

     //assert  nom trop petit ajoute pas 
        $data['name'] = '1234';
        $expected = sizeof($this->ServiceCategories->find()->toArray()) ;
         $this->post('/my-enterprise/serviceCategories/add', $data);
        $result = sizeof($this->ServiceCategories->find()->toArray());
        $this->assertEquals($expected, $result);
    }
    
     //NAME < 50
    public function testServicesCategoriesAjout43(){
        $this->setSession();
       $data['name']='aaaaaa';
        $expected = sizeof($this->ServiceCategories->find()->toArray()) +1 ;
        $this->post('/my-enterprise/serviceCategories/add', $data);
        $result = sizeof($this->ServiceCategories->find()->toArray());
        $this->assertEquals($expected, $result);
       
        $namelenght = strlen($this->ServiceCategories->find()->last()->name) ;
        //Assert pas null ou <5
        $this->assertTrue( $namelenght<50);
        $this->assertNotNull($this->ServiceCategories->find()->last()->name);

    
        $newName = "";
        for($i=0;$i<51;$i++){
            $newName = $newName . "a";
        }
        //Assert marche pas
        $data['name'] = $newName;
        $expected = sizeof($this->ServiceCategories->find()->toArray()) ;
        $this->post('/my-enterprise/serviceCategories/add', $data);
        $result = sizeof($this->ServiceCategories->find()->toArray());
        $this->assertEquals($expected, $result);
    }
    
    //NAME  ne contient pas de caractère html
    public function testServicesCategoriesAjout44(){
        $this->setSession();
       
        $data['name']= "4ee<div></div>";

         $this->post('/my-enterprise/serviceCategories/add', $data);
        $this->assertFalse(strpos($this->ServiceCategories->find()->last()->name, $data['name']));
    }



/*******************Saisies et messages*******************************************************/

    public function testMessageAjout47(){
       $this->setSession();
       $data = $this->buildData();
       $data['name'] = null;

       $this->post('/my-enterprise/services/add', $data);
       $this->assertResponseContains('cannot be left empty');
    
    }

    public function testMessageAjout48(){
       $this->setSession();
       $this->get('/my-enterprise/services/add');

       //les options de la liste propose le  nom  des categoie
       $this->assertResponseContains(
        '<select name="service_category_id" class="form-control wide" id="service-category-id"><option value="1">Lorem ipsum dolor sit amet</option>');

    }

    public function testMessageAjout49(){
       $this->setSession();
       $this->get('/my-enterprise/services/add');

        //les  option  dans la liste sont les 3 premieres categorie   la 4 eme est  a une autre entreprise 
        $this->assertResponseContains(
         '<label for="service-category-id">Category</label><select name="service_category_id" class="form-control wide" id="service-category-id"><option value="1">Lorem ipsum dolor sit amet</option><option value="2">Lorem ipsum dolor sit amet</option><option value="3">Lorem ipsum dolor sit amet</option>'); 

    }
    public function testMessageAjout51(){
        $this->setSession();
        $data = $this->buildData();
       $data['service_category_id']=4;
       
        $this->post('/my-enterprise/services/add', $data);

       $this->assertResponseContains('This category does not exist in your enterprise');
    }
    public function testMessageAjout52(){
       $this->setSession();
       $data = $this->buildData();

       $this->post('/my-enterprise/services/add', $data);

       $this->assertContains($_SESSION['Flash']['flash'][0]['message'], 'The service has been saved.');
    }









    private function buildData(){
        return [
            'name' => 'Service test',
            'price' => 5.99,
            'service_category_id' => 1,
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