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
class ListeServicesTest extends IntegrationTestCase
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
    public function testServicesList9(){
         $this->setSession();

        $this->get('my-enterprise/services');
        $serv = $this->viewVariable('services');
      
        $this->assertNotEmpty($serv);
    }

//    Attributs identifiant un services dans SERVICES (avec photo)
    public function testServiceCategoriesList10(){
         $this->setSession();
        $this->get('my-enterprise/services');
        $servi = $this->viewVariable('services')->first();
        
        $serv=$servi; 
        //On s'assure que tout est inclu!
        $this->assertNotEmpty($serv['name']);
        $this->assertNotEmpty($serv['price']);
        $this->assertNotEmpty($serv['picture']['base64image']);
    }

//  Fonctionnel dans SERVICE_CATEGORIES
    public function testPictureList11(){
         $this->setSession();
        $this->get('my-enterprise/services');
        $categories = $this->viewVariable('categories');

        $this->assertNotEmpty($categories);
    }

//  Attributs identifiant un Services dans SERVICE_CATEGORIES(avec liste sommaire des services)
    public function testServiceList12(){
         $this->setSession();
        $this->get('my-enterprise/services');
        $cats = $this->viewVariable('categories')->first();;

        //On s'assure que tout est inclu!
        $this->assertNotEmpty($cats['name']);

        //fetch la liste de services
        $this->get('/my-enterprise/serviceCategories/listServices/'.$cats->id);
        $this->assertResponseContains('{"warning":"This category contains 1 services(s). They will be deleted","data":[{"id":1,"name":"Lorem ipsum dolor sit amet"}]}');
    }
       
/******************************* PRÉ ET POST CONDITIONS********************************************************************************/
//Les clés primaires ne sont pas visible
    public function testServiceList17(){
         $this->setSession();
        $this->get('my-enterprise/services');

        //L'entrée de détail d'un produit ne contient pas son id
        $this->assertResponseNotContains('<td>id</td>');
    }

    //Pagination de 20 enregistrements par page -----> on en met 10 dans notre cas pour rester dans le design
    public function testServicesList18()
    {      $this->setSession();
        $this->get('/my-enterprise/services');
        $servs = $this->viewVariable('services')->toArray();

        $this->assertEquals(10, count($servs));
    }

//Restriction des produits pour l'entreprise (l'usager connecté)
    public function testServiceCategoriesList19(){
         $this->setSession();
        //Aller chercher ceux de l'entreprise
        $this->get('my-enterprise/services');
        $firstId = $this->viewVariable('services')->first()->id;


        //Aller chercher ses produits
        $this->get('/enterprises/2/services');
        $newFirstId = $this->viewVariable('services')->first()->id;

        $this->assertNotEquals($firstId,$newFirstId);

    }
/***************************   LISTE SERVICES_CATEGORIES ***********************************************************/
    //Les clés primaires ne sont pas visible
    public function testServiceCategoriesList23(){
        $this->setSession();
        $this->get('/my-enterprise/services');

        //Le id est caché
        $this->assertResponseContains('<input type="hidden" name="id" id="id" value="1"/>');
    }

//Pagination de 10 enregistrements par page -----> dans notre cas on en a mis 20
    public function testServiceCategoriesList24(){
        $this->setSession();
        $this->get('/my-enterprise/services');
        $cats = $this->viewVariable('categories');
        $this->assertEquals(15, count($cats));
    }

    //Restriction des catégories de services pour l'entreprise (l'usager connecté)
    public function testServiceCategoriesList25(){
        $this->setSession();
        //Aller chercher ceux de l'entreprise
        $this->get('my-enterprise/services');
        $firstId = $this->viewVariable('categories')->first()->id;

        //Aller chercher ses produits
        $this->get('/enterprises/2/services');
        $newFirstId = $this->viewVariable('categories')->first()->id;

        $this->assertNotEquals($firstId,$newFirstId);
    }
/***********************Aide contextuelle**************************************************************************************/
    //   L'aide est contextuelle  à partir de n'importe quel module
    public function testServiceCategoriesList30(){
        $this->setSession();
        $this->get('my-enterprise/services');
        $this->assertResponseContains('title="Edit this service"');
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