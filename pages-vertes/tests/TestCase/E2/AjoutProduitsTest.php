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
class AjoutProduitsTest extends IntegrationTestCase
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
    }

//    Fonctionnel dans PRODUCTS
    public function testProduitsAjout9(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount() +1 ;

        $this->post('/my-enterprise/products/add', $data);

        $result = $this->getProdCount();
        $this->assertEquals($expected, $result);
    }

//    Tous les attributs ont une valeur dans PRODUCTS
    public function testProduitsAjout10(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount() +1 ;
        $this->post('/my-enterprise/products/add', $data);

        $newProduct = $this->Products->get(['id'=>3]);

        $this->assertNotEmpty($newProduct->id);
        $this->assertNotNull($newProduct->id);
        $this->assertNotEmpty($newProduct->name);
        $this->assertNotNull($newProduct->name);
        $this->assertNotEmpty($newProduct->price);
        $this->assertNotNull($newProduct->price);
        $this->assertNotEmpty($newProduct['quantity_min_limit']);
        $this->assertNotNull($newProduct['quantity_min_limit']);
        $this->assertNotEmpty($newProduct['quantity_max_limit']);
        $this->assertNotNull($newProduct['quantity_max_limit']);
        $this->assertNotEmpty($newProduct['quantity_available']);
        $this->assertNotNull($newProduct['quantity_available']);
        $this->assertNotEmpty($newProduct['product_category_id']);
        $this->assertNotNull($newProduct['product_category_id']);
        $this->assertNotEmpty($newProduct['picture_id']);
        $this->assertNotNull($newProduct['picture_id']);
    }

//  Fonctionnel dans Produit Categories
    public function testProduitCategoriesAjout11(){
        $this->setSession();
        $expected = $this->Products->ProductCategories->find()->count()+1;
        $newCat = [
            'name' => 'New prod Category'
        ];
        $this->post('/my-enterprise/productCategories/add', $newCat);
        $result = $this->Products->ProductCategories->find()->count();
        $this->assertEquals($expected, $result);
    }
//Tous les attributs ont une valeur dans PICTURES
    public function testPictureAjout12(){
        $this->setSession();
        $data = $this->buildData();
        $this->post('/my-enterprise/products/add', $data);
        $pictureId = $this->Products->find('all')->last()->picture_id;
        $lastPicture = TableRegistry::get('Pictures')->get(['id'=>$pictureId]);

        $this->assertNotEmpty($lastPicture->id);
        $this->assertNotEmpty($lastPicture->base64image);
    }
//Unicité de la clé primaire id (généré)
    public function testProductsAjout16(){
        $this->setSession();
        $data = $this->buildData();
        $lastId = $this->Products->find('all')->last()->id;
        $this->post('/my-enterprise/products/add', $data);
        $newProd = $this->Products->find('all')->last();

        $this->assertEquals($lastId+1, $newProd->id);
    }

//Existence de la clé étrangère PRODUCT_CATEGORY_ID
    public function testProductsAjout17(){
        $this->setSession();
        $data = $this->buildData();
        $data['product_category_id'] = 800;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
        $this->assertResponseContains('This value does not exist');
    }

//Cohérence de la clef étrangère PRODUCT_CATEGORY_ID et l'usager connecté
    public function testProductsAjout18(){
        $this->setSession();
        $data = $this->buildData();
        $data['product_category_id'] = 2;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
        $this->assertResponseContains('category does not exist');
    }

//    Existence de la clé étrangère PICTURE_ID
    public function testProductsAjout19(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Products->find()->last()->picture_id);
        $this->assertNotNull($this->Products->find()->last()->picture_id);

        $this->assertTrue($this->Pictures->exists(['id'=>$this->Products->find()->last()->picture_id]));

        $data['image'] = null;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }

//NAME !=Null
    public function testProductsAjout20(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Products->find()->last()->name);
        $this->assertNotNull($this->Products->find()->last()->name);

        $data['name'] = null;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }
//NAME > 5
    public function testProductsAjout21(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        $nameLength = strlen($this->Products->find()->last()->name);
        //Assert > 5
        $this->assertTrue($nameLength>5);
        $this->assertNotNull($this->Products->find()->last()->name);

        //Assert marche pas
        $data['name'] = '1234';
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }

    //NAME < 50
    public function testProductsAjout22(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        $nameLength = strlen($this->Products->find()->last()->name);
        //Assert >
        $this->assertTrue($nameLength < 50);
        $this->assertNotNull($this->Products->find()->last()->name);

        $newName = "";
        for($i=0;$i<51;$i++){
            $newName = $newName . "a";
        }
        //Assert marche pas
        $data['name'] = $newName;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }

//NAME  ne contient pas de caractère html
    public function testProductsAjout25(){
        $this->setSession();
        $data = $this->buildData();
        $data['name']= "0<div>";

        $this->post('/my-enterprise/products/add', $data);
        $this->assertFalse(strpos($this->Products->find()->last()->name, '<div>'));
    }

//Price !=Null
    public function testProductsAjout26(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Products->find()->last()->price);
        $this->assertNotNull($this->Products->find()->last()->price);

        $data['price'] = null;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }

//Price > 0
    public function testProductsAjout27(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        $this->assertTrue($this->Products->find()->last()->price > 0);

        $data['price'] = 0;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }
//Price < 1000000
    public function testProductsAjout28(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        $this->assertTrue($this->Products->find()->last()->price <1000000);

        $data['price'] = 1000000;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }

//Qte_ava !=Null
    public function testProductsAjout29(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Products->find()->last()->quantity_available);
        $this->assertNotNull($this->Products->find()->last()->quantity_available);

        $data['quantity_available'] = null;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }

//quantity_available >= 0
    public function testProductsAjout30(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        //Assert pas null ou empty
        $this->assertTrue($this->Products->find()->last()->quantity_available >= 0);

        $data['quantity_available'] = -1;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }
//quantity_min_limit !=Null
    public function testProductsAjout31(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Products->find()->last()->quantity_min_limit);
        $this->assertNotNull($this->Products->find()->last()->quantity_min_limit);

        $data['quantity_min_limit'] = null;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }

//quantity_min_limit >= 0
    public function testProductsAjout32(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        $this->assertTrue($this->Products->find()->last()->quantity_min_limit >= 0);

        $data['quantity_min_limit'] = -1;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }
//quantity_max_limit !=Null
    public function testProductsAjout34(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Products->find()->last()->quantity_max_limit);
        $this->assertNotNull($this->Products->find()->last()->quantity_max_limit);

        $data['quantity_max_limit'] = null;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }

//quantity_max_limit >= quantity_min_limit
    public function testProductsAjout35(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());


        $prod = $this->Products->find()->last();
        $this->assertTrue($prod->quantity_max_limit >= $prod->quantity_min_limit);

        $data['quantity_min_limit'] = 2;
        $data['quantity_max_limit'] = 0;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }
//quantity_max_limit <= 9999999
    public function testProductsAjout36(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());


        $prod = $this->Products->find()->last();
        $this->assertTrue($prod->quantity_max_limit <= 9999999);

        $data['quantity_max_limit'] = 10000000;
        $expected = $this->getProdCount();
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->getProdCount());
    }

/*************************PICTURE************************************/

    //unicité clé primaire
    public function testPictureAjout39(){
        $lastPictureId = $this->Products->Pictures->find()->last()->id;
        $this->setSession();
        $data = $this->buildData();
        $expected = $lastPictureId+1;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->Products->Pictures->find()->last()->id);
    }

    //Cohérence de la clef étrangère picture_id et l'usager connecté

    public function testPictureAjout40(){
        $this->setSession();
        $data = $this->buildData();
        $data['picture_id']= 1;

        $expected = $this->getProdCount()+1;
        $expectedPic = $this->Pictures->find()->count()+1;
        $this->post('/my-enterprise/products/add', $data);

        //Assert ajout de prod ET d'image
        $this->assertEquals($expected, $this->getProdCount());
        $this->assertEquals($expectedPic, $this->Pictures->find()->count());

        $forcedImage64 = $this->Pictures->get($data['picture_id'])->base64image;
        $this->assertNotEquals($this->Pictures->find()->last()->base64image, $forcedImage64);
    }

//    Cohérence de la clef étrangère picture_id et le parent(employees, products...)
    public function testPictureAjout41(){
        $this->setSession();
        $data = $this->buildData();

        $expected = $this->getProdCount()+1;
        $expectedPic = $this->Pictures->find()->count()+1;
        $this->post('/my-enterprise/products/add', $data);

        //Assert ajout de prod ET d'image
        $this->assertEquals($expected, $this->getProdCount());
        $this->assertEquals($expectedPic, $this->Pictures->find()->count());

        $lasPic = $this->Pictures->find()->last();
        $lasProd = $this->Products->find()->last();
        $this->assertEquals($lasPic->id, $lasProd->picture_id );
    }

//    Vérification que base64image est bien formaté: Début par "data:image/png;base64,"
    public function testPictureAjout42(){
        $this->setSession();
        $data = $this->buildData();

        $expected = $this->getProdCount()+1;
        $expectedPic = $this->Pictures->find()->count()+1;
        $this->post('/my-enterprise/products/add', $data);

        //Assert ajout de prod ET d'image
        $this->assertEquals($expected, $this->getProdCount());
        $this->assertEquals($expectedPic, $this->Pictures->find()->count());

        $pos = strpos($this->Pictures->find()->last()->base64image,"data:image/png;base64,") === 0;
        $this->assertTrue($pos);
    }
//Vérification que base64image est bien formaté: Fin par l'image encodé en base64
    public function testPictureAjout43(){
        $this->setSession();
        $data = $this->buildData();


        $this->post('/my-enterprise/products/add', $data);
        $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Pictures->find()->last()->base64image,$base64) !== false;
        $this->assertTrue($pos);
    }
//Vérification que base64image est non null
    public function testPictureAjout44(){
        $this->setSession();
        $data = $this->buildData();


        $this->post('/my-enterprise/products/add', $data);
        $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Pictures->find()->last()->base64image, $base64) !== false;
        $this->assertTrue($pos);

        $expected = $this->Pictures->find()->count();
        $data['image']['tmp_name'] = null;
        $this->post('/my-enterprise/products/add', $data);
        $result = $this->Pictures->find()->count();
        $this->assertEquals($expected, $result);

    }

/**************ProductCategories*************************************************/
//Unicité de la clé primaire id (généré)
    public function testProductCategoryAjout47(){
        $this->setSession();
        $data = ['name'=>'aaassa'];
        $expected = $this->ProductCategories->find()->last()->id + 1;
        $this->post('/my-enterprise/productCategories/add', $data);
        $result = $this->ProductCategories->find()->last()->id;
        $this->assertEquals($expected, $result);

    }
    //Existence de la clé étrangère ENTERPRISE_ID
    public function testProductCategoryAjout48(){
        $this->setSession();
        $data = ['name'=>'aaassa'];

        $this->post('/my-enterprise/productCategories/add', $data);
        $result = $this->ProductCategories->find()->last()->enterprise_id;
        $this->assertTrue($this->ProductCategories->Enterprises->exists(['id'=>$result]));
    }
    //Cohérence de la clef étrangère ENTERPRISE_ID et l'usager connecté
    public function testProductCategoryAjout49(){
        $this->setSession();
        $data = ['name'=>'aaassa'];

        $this->post('/my-enterprise/productCategories/add', $data);
        $result = $this->ProductCategories->find()->contain('Enterprises')->order(['ProductCategories.id'=>'asc'])->last();
        $this->assertEquals($this->Auth['User']['id'], $result->enterprise->owner_id);
    }
    //Name != null
    public function testProductCategoryAjout50(){
        $this->setSession();
        $data = ['name'=> null];

        $expected = $this->ProductCategories->find()->count();
        $this->post('/my-enterprise/productCategories/add', $data);
        $result = $this->ProductCategories->find()->count();
        $this->assertEquals($expected, $result);
    }
    //Name lgt > 5
    public function testProductCategoryAjout51(){
        $this->setSession();
        $data = ['name'=> '1234'];

        $expected = $this->ProductCategories->find()->count();
        $this->post('/my-enterprise/productCategories/add', $data);
        $result = $this->ProductCategories->find()->count();
        $this->assertEquals($expected, $result);
    }
    //Name lgt <50
    public function testProductCategoryAjout52(){
        $this->setSession();
        $data = ['name'=> ''];
        for($i=0;$i<50;$i++){
            $data['name'] .= 'a';
        }

        $expected = $this->ProductCategories->find()->count();
        $this->post('/my-enterprise/productCategories/add', $data);
        $result = $this->ProductCategories->find()->count();
        $this->assertEquals($expected, $result);
    }

    //Name pas html
    public function testProductCategoryAjout53(){
        $this->setSession();
        $data = ['name'=> '45<div></div>'];

        $this->post('/my-enterprise/productCategories/add', $data);

        $cat = $this->ProductCategories->find()->last();
        $this->assertFalse(strpos($cat->name, $data['name']));
    }



/*******************Saisies et messages*******************************************************/

public function testMessagesAjout56(){
    $this->setSession();
    $data = $this->buildData();
    $data['name'] = null;

    $this->post('/my-enterprise/products/add', $data);
    $this->assertResponseContains('cannot be left empty');
}

public function testMessagesAjout57(){
    $this->setSession();
    $this->get('/my-enterprise/products/add');

    //Les options de la liste de sélection propose le nom de la catégorie
    $this->assertResponseContains(
        '<select name="product_category_id" class="form-control wide" required="required" id="product-category-id"><option value="1">Lorem ipsum dolor sit amet</option>');

}
public function testMessagesAjout58(){
    $this->setSession();
    $this->get('/my-enterprise/products/add');

    //Les options de la liste ne sont que les deux catégories reliés a cette entrprise
    //La 2e catégire de la fixture est asséciée à une autre
    $this->assertResponseContains(
        '<select name="product_category_id" class="form-control wide" required="required" id="product-category-id"><option value="1">Lorem ipsum dolor sit amet</option><option value="3">Lorem ipsum dolor sit amet</option>');
}
public function testMessagesAjout59(){
    $this->setSession();
    $data = $this->buildData();
    $data['image'] = ['tmp_name'=> null, 'type'=>null];
    $this->post('/my-enterprise/products/add', $data);
    $this->assertResponseContains('Please select an image.');
}
public function testMessagesAjout60(){
    $this->setSession();
    $data = $this->buildData();

    $this->post('/my-enterprise/products/add', $data);

    $this->assertContains($_SESSION['Flash']['flash'][0]['message'], 'The product has been saved.');
}

    private function buildData(){
        return [
            'name' => 'Produit test',
            'price' => 5.99,
            'quantity_min_limit' => 1,
            'quantity_max_limit'=> 10,
            'quantity_available'=> 4,
            'product_category_id' => 1,
            'image' =>  [
                'tmp_name' => 'webroot/img/nav-toogle-icon.png',
                'type' => 'image/png'
            ],
        ];
    }

    private function setSession(){
        $this->session([
            'Auth' => $this->Auth
        ]);
    }

    private function getProdCount(){
        return $this->Products->find()->count();
    }
//    public function testAdd_notLogged(){
//        $this->post('/my-enterprise/products/add');
//        $this->assertRedirect('/login');
//    }
//
//    public function testAdd_logged_missing_data(){
//        $this->setSession();
//        $data = [];
//        //Data vide
//        $this->post('/my-enterprise/products/add', $data);
//        $this->assertResponseContains('required');
//
//        //Seulement image
//        $data['image'] = ['tmp_name' => 'tmp/tests/testFichier'];
//        $this->post('/my-enterprise/products/add', $data);
//        $this->assertResponseContains('field is required');
//
//        //Ajoute produit avec max < min
//        $data['name'] = 'Produit test';
//        $data['price'] = 5.99;
//        $data['quantity_min_limit'] = 9;
//        $data['quantity_max_limit'] = 1;
//        $data['quantity_available'] = 4;
//        $data['product_category_id'] = 1;
//        $this->post('/my-enterprise/products/add', $data);
//        $this->assertResponseContains('be higher than maximum');
//    }
}
