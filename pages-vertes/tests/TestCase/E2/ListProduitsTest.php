<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ProductsController;
use App\Controller\ProductCategoriesController;
use Cake\TestSuite\IntegrationTestCase;
use App\Model\Table\ProductsTable;
use App\Model\Table\ProductCategoriesTable;
use Cake\ORM\TableRegistry;


/**
 * App\Controller\ProductsController Test Case
 */
class ListProduitsTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.products',
        'app.product_categories',
        'app.enterprises',
        'app.users',
        'app.pictures',
//        'app.employees',
//        'app.services',
//        'app.service_categories',
//        'app.suppliers',
//        'app.employe_categories',
//        'app.supplier_categories'
    ];

    public function setUp(){
        parent::setUp();
        $this->Products = TableRegistry::get('Products');
        $this->Pictures = TableRegistry::get('Pictures');
        $this->ProductCategories = TableRegistry::get('ProductCategories');

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
        $this->setSession();
    }

//    Fonctionnel dans PRODUCTS
    public function testProduitsList9(){

        $this->get('my-enterprise/products');
        $prods = $this->viewVariable('products');

        $this->assertNotEmpty($prods);
    }

//    Attributs identifiant un produit dans PRODUCTS (avec photo)
    public function testProduitCategoriesList10(){
        $this->get('my-enterprise/products');
        $prods = $this->viewVariable('products')->first();

        //On s'assure que tout est inclu!
        $this->assertNotEmpty($prods['name']);
        $this->assertNotEmpty($prods['price']);
        $this->assertNotEmpty($prods['quantity_min_limit']);
        $this->assertNotEmpty($prods['quantity_max_limit']);
        $this->assertNotEmpty($prods['quantity_available']);
        $this->assertNotEmpty($prods['picture']['base64image']);
    }

//  Fonctionnel dans PRODUCT_CATEGORIES
    public function testPictureList11(){
        $this->get('my-enterprise/products');
        $categories = $this->viewVariable('categories');

        $this->assertNotEmpty($categories);
    }

//	Attributs identifiant un produit dans PRODUCT_CATEGORIES(avec liste sommaire des produits)
    public function testProduitList12(){
        $this->get('my-enterprise/products');
        $cats = $this->viewVariable('categories')->first();

        //On s'assure que tout est inclu!
        $this->assertNotEmpty($cats['name']);

        //fetch la liste de produits
        $this->get('/my-enterprise/productCategories/listProducts/'.$cats->id);
        $this->assertResponseContains('{"warning":"This category contains 14 product(s).","data":[{"id":1,"name":"Lorem ipsum dolor sit amet"},{"id":4,"name":"Lorem ipsum dolor sit amet"},{"id":7,"name":"Lorem ipsum dolor sit amet"},{"id":10,"name":"Lorem ipsum dolor sit amet"},{"id":13,"name":"Lorem ipsum dolor sit amet"},{"id":16,"name":"Lorem ipsum dolor sit amet"},{"id":19,"name":"Lorem ipsum dolor sit amet"},{"id":22,"name":"Lorem ipsum dolor sit amet"},{"id":25,"name":"Lorem ipsum dolor sit amet"},{"id":28,"name":"Lorem ipsum dolor sit amet"},{"id":31,"name":"Lorem ipsum dolor sit amet"},{"id":34,"name":"Lorem ipsum dolor sit amet"},{"id":37,"name":"Lorem ipsum dolor sit amet"},{"id":40,"name":"Lorem ipsum dolor sit amet"}]}');
    }

/******************************* PRÉ ET POST CONDITIONS********************************************************************************/
//Les clés primaires ne sont pas visible
    public function testProduitList17(){
        $this->get('my-enterprise/products');

        //L'entrée de détail d'un produit ne contient pas son id
        $this->assertResponseContains(
                        '<td class="product-details">
                                    <h3 class="title">Lorem ipsum dolor sit amet</h3>
                                    <span class="add-id"><strong>Qty available</strong>2</span>
                                    <span><strong>Price </strong>1$</span>
                                </td>
                                ');
    }

    //Pagination de 20 enregistrements par page -----> on en met 10 dans notre cas pour rester dans le design
    public function testProduitsList18()
    {
        $this->get('/my-enterprise/products');
        $prods = $this->viewVariable('products')->toArray();

        $this->assertEquals(10, count($prods));
    }

//Restriction des produits pour l'entreprise (l'usager connecté)
    public function testProduitCatList19(){
        //Aller chercher ceux de l'entreprise
        $this->get('my-enterprise/products');
        $firstId = $this->viewVariable('products')->first()->id;


        //Aller chercher ses produits
        $this->get('/enterprises/2/products');
        $newFirstId = $this->viewVariable('products')->first()->id;

        $this->assertNotEquals($firstId,$newFirstId);

    }
/***************************   LISTE PRODUCT_CATEGORIES ***********************************************************/
    //Les clés primaires ne sont pas visible
    public function testProduitCatList23(){
        $this->get('/my-enterprise/products');

        //Le id est caché
        $this->assertResponseContains('<input type="hidden" name="id" id="id" value="4"/>');
    }

//Pagination de 10 enregistrements par page -----> dans notre cas on en a mis 15
    public function testProduitCatList24(){
        $this->get('/my-enterprise/products');
        $cats = $this->viewVariable('categories');
        $this->assertEquals(15, count($cats));
    }

    //Restriction des catégories de produits pour l'entreprise (l'usager connecté)
    public function testProduitCatDelete25(){
        //Aller chercher ceux de l'entreprise
        $this->get('my-enterprise/products');
        $firstId = $this->viewVariable('categories')->first()->id;

        //Aller chercher ses produits
        $this->get('/enterprises/2/products');
        $newFirstId = $this->viewVariable('categories')->first()->id;

        $this->assertNotEquals($firstId,$newFirstId);
    }

/***********************Aide contextuelle**************************************************************************************/
    //   L'aide est contextuelle  à partir de n'importe quel module
    public function testProduitCatDelete30(){
        $this->get('my-enterprise/products');
        $this->assertResponseContains('title="Edit this product"');
    }



/*********************************************************************************************************/
        private function setSession(){
        $this->session([
            'Auth' => $this->Auth
        ]);
    }
}
