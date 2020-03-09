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
class ModifProduitsTest extends IntegrationTestCase
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
    public function testProduitsModif9(){
        $data = $this->buildData();
        //modifier produit 1
        $this->post('/my-enterprise/products/1', $data);

        $moded = $this->Products->get(1, ["contain"=>"Pictures"]);

        $this->assertEquals($moded["name"], $data["name"]);
        $this->assertEquals($moded["price"], $data["price"]);
        $this->assertEquals($moded["quantity_min_limit"] , $data["quantity_min_limit"]);
        $this->assertEquals( $moded["quantity_max_limit"] , $data["quantity_max_limit"]);
        $this->assertEquals($moded["quantity_available"] , $data["quantity_available"]);
        $this->assertEquals( $moded["product_category_id"] , $data["product_category_id"]);
        $this->assertEquals($moded["picture"]["base64image"], "data:image/png;base64," . base64_encode(file_get_contents($data["image"]["tmp_name"])));
    }

//    Tous les attributs ont une valeur dans PRODUCTS
    public function testProduitsModif10(){
        $data = $this->buildData();
        //modifier produit 1
        $this->post('/my-enterprise/products/1', $data);

        $newProduct = $this->Products->get(1);

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
    public function testProduitCategoriesModif11(){
        $newCat = [
            'name' => 'Moded prod Category'
        ];
        $this->post('/my-enterprise/productCategories/edit/1', $newCat);
        $result = $this->Products->ProductCategories->get(1);
        $this->assertEquals($result['name'], $newCat["name"]);
    }
//Tous les attributs ont une valeur dans PRODUCT_CATEGORIES
    public function testPictureModif12(){
        $newCat = [
            'name' => 'Moded prod Category'
        ];
        $this->post('/my-enterprise/productCategories/edit/1', $newCat);
        $result = $this->Products->ProductCategories->get(1);

        $this->assertNotNull($result['name']);
        $this->assertNotEmpty($result['name']);
    }
//Unicité de la clé primaire id (non modifiable)
    public function testProductsModif16(){
        $data = $this->buildData();
        $data["id"] = 48;
        $this->post('/my-enterprise/products/1', $data);

        //On s'assure que le produit 1 existe encore
        $firstProd = $this->Products->find()->first();
        $this->assertNotNull($firstProd);
        $this->assertNotEmpty($firstProd);
        //On s'assure que le 48 n'Existe pas. Donc On n'a pas modifié le champs id
        $prod48 = $this->Products->find()->where(['id'=>48])->toArray();
        $this->assertEmpty($prod48);
    }

//Existence de la clé étrangère PRODUCT_CATEGORY_ID
    public function testProductsModif17(){
        $data = $this->buildData();
        $data['product_category_id'] = 800;

        $this->post('/my-enterprise/products/1', $data);
        $prod = $this->Products->get(1);
        $this->assertNotEquals($prod['product_category_id'], $data['product_category_id']);
        $this->assertResponseContains('This value does not exist');
    }

//Cohérence de la clef étrangère PRODUCT_CATEGORY_ID et l'usager connecté
    public function testProductsModif18(){
        $data = $this->buildData();
        $data['product_category_id'] = 300;
        $prod = $this->Products->get(1);
        $this->post('/my-enterprise/products/1', $data);

        $this->assertNotEquals($prod['product_category_id'], $data['product_category_id']);
        $this->assertResponseContains('value does not exist');
    }

//    Existence de la clé étrangère PICTURE_ID(non modifiable)
    public function testProductsModif19(){
        $data = $this->buildData();

        $data['picture_id']=8000;
        $prod = $this->Products->get(1);
        //Assert pas changé
        $this->assertTrue($this->Pictures->exists(['id'=>$prod["picture_id"]]));
    }

//NAME !=Null
    public function testProductsModif20(){
        $data = $this->buildData();

        $this->post('/my-enterprise/products/1', $data);

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Products->get(1)->name);
        $this->assertNotNull($this->Products->get(1)->name);

        $data['name'] = null;
        $this->post('/my-enterprise/products/1', $data);
        $this->assertNotNull($this->Products->get(1)->name);
    }
//NAME > 5
    public function testProductsModif21(){
        $data = $this->buildData();
        $this->post('/my-enterprise/products/1', $data);

        $nameLength = strlen($this->Products->get(1)->name);
        //Assert > 5
        $this->assertTrue($nameLength>5);
        $this->assertNotNull($this->Products->find()->last()->name);

        //Assert marche pas
        $data['name'] = '1234';
        $this->post('/my-enterprise/products/1', $data);
        $this->assertNotEquals($this->Products->find()->last()->name, $data['name']);
    }

    //NAME < 50
    public function testProductsModif22(){
        $data = $this->buildData();
        $this->post('/my-enterprise/products/1', $data);

        $nameLength = strlen($this->Products->get(1)->name);
        //Assert >
        $this->assertTrue($nameLength < 50);
        $this->assertNotNull($this->Products->get(1)->name);

        $newName = "";
        for($i=0;$i<51;$i++){
            $newName = $newName . "a";
        }
        //Assert marche pas
        $data['name'] = $newName;
        $this->post('/my-enterprise/products/1', $data);
        $this->assertNotEquals($this->Products->get(1)->name, $data['name']);
    }

//NAME  ne contient pas de caractère html
    public function testProductsModif23(){
        $data = $this->buildData();
        $data['name']= "0<div>";

        $this->post('/my-enterprise/products/1', $data);
        $this->assertEquals('0&lt;div&gt;',$this->Products->get(1)->name);
    }

//Price !=Null
    public function testProductsModif24(){
        $data = $this->buildData();
        $this->post('/my-enterprise/products/1', $data);

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Products->get(1)->price);
        $this->assertNotNull($this->Products->get(1)->price);

        $data['price'] = null;
        $this->post('/my-enterprise/products/1', $data);
        $this->assertNotNull($this->Products->get(1)->price);
    }

//Price > 0
    public function testProductsModif25(){
        $data = $this->buildData();
        $this->post('/my-enterprise/products/1', $data);

        $this->assertTrue($this->Products->get(1)->price > 0);

        //on reprend l'ancien pour comparer
        $expected = $this->Products->get(1)->price;

        $data['price'] = 0;
        $this->post('/my-enterprise/products/1', $data);
        //On s'assure que ca a pas changé
        $this->assertEquals($expected, $this->Products->get(1)->price);
    }
//Price < 1000000
    public function testProductsModif26(){
        $data = $this->buildData();
        $this->post('/my-enterprise/products/1', $data);

        $this->assertTrue($this->Products->get(1)->price <1000000);

        //on reprend l'ancien pour comparer
        $expected = $this->Products->get(1)->price;
        $data['price'] = 1000000;
        $this->post('/my-enterprise/products/1', $data);
        $this->assertEquals($expected, $this->Products->get(1)->price);
    }

//Qte_ava !=Null
    public function testProductsModif27(){
        $data = $this->buildData();
        $this->post('/my-enterprise/products/1', $data);

        //Assert pas null ou empty
        $this->assertNotEmpty( $this->Products->get(1)->quantity_available);
        $this->assertNotNull( $this->Products->get(1)->quantity_available);

        //on reprend l'ancien pour comparer
        $expected = $this->Products->get(1)->quantity_available;
        $data['quantity_available'] = null;
        $this->post('/my-enterprise/products/add', $data);
        $this->assertEquals($expected, $this->Products->get(1)->quantity_available);
    }

//quantity_available >= 0
    public function testProductsModif28(){
        $data = $this->buildData();
        $this->post('/my-enterprise/products/1', $data);

        //Assert pas null ou empty
        $this->assertTrue($this->Products->get(1)->quantity_available >= 0);

        //on reprend l'ancien pour comparer
        $expected = $this->Products->get(1)->quantity_available;
        $data['quantity_available'] = -1;
        $this->post('/my-enterprise/products/1', $data);
        $this->assertEquals($expected, $this->Products->get(1)->quantity_available);
    }
//quantity_min_limit !=Null
    public function testProductsModif29(){
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/1', $data);

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Products->get(1)->quantity_min_limit);
        $this->assertNotNull($this->Products->get(1)->quantity_min_limit);

        //on reprend l'ancien pour comparer
        $expected = $this->Products->get(1)->quantity_min_limit;
        $data['quantity_min_limit'] = null;
        $this->post('/my-enterprise/products/1', $data);
        $this->assertEquals($expected,$this->Products->get(1)->quantity_min_limit);
    }

//quantity_min_limit >= 0
    public function testProductsModif30(){
        $data = $this->buildData();
        $this->post('/my-enterprise/products/1', $data);

        $this->assertTrue($this->Products->get(1)->quantity_min_limit >= 0);

        //on reprend l'ancien pour comparer
        $expected = $this->Products->get(1)->quantity_min_limit;
        $data['quantity_min_limit'] = -1;
        $this->post('/my-enterprise/products/1', $data);
        $this->assertEquals($expected, $this->Products->get(1)->quantity_min_limit);
    }

    //quantity_min_limit < 9999
    public function testProductsModif31(){
        $data = $this->buildData();
        $this->post('/my-enterprise/products/1', $data);

        $this->assertTrue($this->Products->get(1)->quantity_min_limit < 9999);

        //on reprend l'ancien pour comparer
        $expected = $this->Products->get(1)->quantity_min_limit;
        $data['quantity_min_limit'] = 10000;
        $this->post('/my-enterprise/products/1', $data);
        $this->assertEquals($expected, $this->Products->get(1)->quantity_min_limit);
    }
//quantity_max_limit !=Null
    public function testProductsModif32(){
        $data = $this->buildData();
        $expected = $this->getProdCount()+1;
        $this->post('/my-enterprise/products/1', $data);

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Products->get(1)->quantity_max_limit);
        $this->assertNotNull($this->Products->get(1)->quantity_max_limit);

        //on reprend l'ancien pour comparer
        $expected = $this->Products->get(1)->quantity_max_limit;
        $data['quantity_max_limit'] = null;
        $this->post('/my-enterprise/products/1', $data);
        $this->assertEquals($expected,$this->Products->get(1)->quantity_max_limit);
    }

//quantity_max_limit >= quantity_min_limit
    public function testProductsModif33(){
        $data = $this->buildData();
        $this->post('/my-enterprise/products/1', $data);

        $this->assertTrue($this->Products->get(1)->quantity_max_limit >= $this->Products->get(1)->quantity_min_limit);

        //on reprend l'ancien pour comparer
        $expected = $this->Products->get(1)->quantity_max_limit;
        $data['quantity_max_limit'] = $this->Products->get(1)->quantity_min_limit -1;
        $this->post('/my-enterprise/products/1', $data);
        $this->assertEquals($expected, $this->Products->get(1)->quantity_max_limit);
    }
//quantity_max_limit <= 9999999
    public function testProductsModif36(){
        $data = $this->buildData();
        $this->post('/my-enterprise/products/1', $data);

        $this->assertTrue($this->Products->get(1)->quantity_max_limit < 9999999);

        //on reprend l'ancien pour comparer
        $expected = $this->Products->get(1)->quantity_max_limit;
        $data['quantity_min_limit'] = 10000000;
        $this->post('/my-enterprise/products/1', $data);
        $this->assertEquals($expected, $this->Products->get(1)->quantity_max_limit);
    }

/*************************PICTURE************************************************************************************************/

    //unicité clé primaire (non modifiable)
    public function testPictureModif37(){
        $lastPictureId = $this->Products->Pictures->get(1)->id;
        $data = $this->buildData();
        $data['picture']['picture_id'] = 8000;

        $this->post('/my-enterprise/products/1', $data);
        $this->assertEquals($lastPictureId, $this->Products->Pictures->get(1)->id);
    }

    //Cohérence de la clef étrangère picture_id et l'usager connecté
    public function testPictureModif38(){
        $data = $this->buildData();
        $data['picture_id']= 3;

        $prod =  $this->Products->get(1);
        $this->post('/my-enterprise/products/1', $data);

        //Assert Modif de prod ET d'image
        $this->assertEquals( $prod['picture_id'],  $this->Products->get(1)->picture_id);
    }

//    Cohérence de la clef étrangère picture_id et le parent(employees, products...)
    public function testPictureModif39(){
        $data = $this->buildData();
        $data['picture_id']= 3;

        $prod =  $this->Products->get(1);
        $this->post('/my-enterprise/products/1', $data);

        //Assert Modif de prod ET d'image
        $this->assertEquals( $prod['picture_id'],  $this->Products->get(1)->picture_id);
    }

//    Vérification que base64image est bien formaté: Début par "data:image/png;base64,"
    public function testPictureModif40(){
        $this->setSession();
        $data = $this->buildData();

        $this->post('/my-enterprise/products/1', $data);

        $pos = strpos($this->Products->get(1,['contain'=>'Pictures'])->picture->base64image,"data:image/png;base64,") === 0;
        $this->assertTrue($pos);
    }
//Vérification que base64image est bien formaté: Fin par l'image encodé en base64
    public function testPictureModif43(){
        $data = $this->buildData();

        $this->post('/my-enterprise/products/1', $data);
        $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Products->get(1,['contain'=>'Pictures'])->picture->base64image,$base64) !== false;
        $this->assertTrue($pos);
    }
//Vérification que base64image est non null
    public function testPictureModif44(){
        $data = $this->buildData();

        $this->post('/my-enterprise/products/1', $data);
        $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Products->get(1,['contain'=>'Pictures'])->picture->base64image, $base64) !== false;
        $this->assertTrue($pos);

        $data['image']['tmp_name'] = null;
        $this->post('/my-enterprise/products/1', $data);
        $result = $this->Pictures->find()->count();
        $this->assertNotNull($this->Products->get(1,['contain'=>'Pictures'])->picture->base64image);

    }
/**************ProductCategories*************************************************/
//Unicité de la clé primaire id (généré)
    public function testProductCategoryModif45(){
        $data = ['id'=>90, 'name'=>'aaassa'];
        $this->post('/my-enterprise/productCategories/edt/1', $data);

        $this->assertEmpty($this->Products->ProductCategories->find()->where(['id'=>90])->toArray());

    }
    //Existence de la clé étrangère ENTERPRISE_ID
    public function testProductCategoryModif46(){
        $data = ['id'=>1,'name'=>'aaassa', 'enterprise_id'=>2];

        $this->post('/my-enterprise/productCategories/edit/1', $data);
        $this->assertEquals(1, $this->ProductCategories->get(1)->enterprise_id);
    }

    //Name != null
    public function testProductCategoryModif47(){
        $data = ['name'=> null];

        $this->post('/my-enterprise/productCategories/edit/1', $data);
        $result = $this->ProductCategories->get(1)->name;
        $this->assertNotNull($result);
    }
    //Name lgt > 5
    public function testProductCategoryModif48(){
        $data = ['name'=> '12345'];

        $expected = $this->ProductCategories->get(1)->name;
        $this->post('/my-enterprise/productCategories/edit/1', $data);
        $result = $this->ProductCategories->get(1)->name;
        $this->assertEquals($expected, $result);
    }
    //Name lgt <50
    public function testProductCategoryModif49(){
        $data = ['name'=> ''];
        for($i=0;$i<50;$i++){
            $data['name'] .= 'a';
        }

        $expected = $this->ProductCategories->get(1)->name;
        $this->post('/my-enterprise/productCategories/edit/1', $data);
        $result = $this->ProductCategories->get(1)->name;
        $this->assertEquals($expected, $result);
    }

    //Name pas html
    public function testProductCategoryModif53(){
        $this->setSession();
        $data = ['name'=> '<div></div>'];

        $this->post('/my-enterprise/productCategories/edit/1', $data);

        $result = $this->ProductCategories->get(1)->name;
        $this->assertEquals(h('<div></div>'), $result);
    }



/*******************Saisies et messages*******************************************************/
//on ne peut saisir les attributs d'information du produit
public function testMessagesModif52(){
    $data = $this->buildData();
    $data['name'] = null;

    $this->post('/my-enterprise/products/1', $data);
    $this->assertResponseContains('cannot be left empty');

}

//on ne peut saisir l'attribut PRODUCT_CATEGORY_ID par une liste
    public function testMessagesModif53(){
    $this->setSession();
    $this->get('/my-enterprise/products/1');

    //Les options de la liste de sélection propose le nom de la catégorie
    $this->assertResponseContains(
        '<option value="1" selected="selected">Lorem ipsum dolor sit amet</option><');

}

    public function testMessagesModif54_58(){
    $this->get('/my-enterprise/products/1');

    //Les options de la liste ne sont que les  catégories reliés a cette entrprise
    //La catégorie  value 2 de la fixture est associée à une autre
    $this->assertResponseContains(
        '<select name="product_category_id" class="form-control wide" required="required" id="product-category-id">'.
        '<option value="1" selected="selected">Lorem ipsum dolor sit amet</option>'.
        '<option value="3">Lorem ipsum dolor sit amet</option>'.
        '<option value="4">Lorem ipsum dolor sit amet</option>');
}

//on ne peut saisir une image de produit
public function testMessagesModif55(){
    $data = $this->buildData();
    $data['image'] = ['tmp_name'=> null, 'type'=>null];
    $data['picture_id'] = null;
    $data['picture'] = null;
    $expected = $this->Products->get(1,['contain'=>'Pictures'])->picture;
    $this->post('/my-enterprise/products/edit/1', $data);
    $result = $this->Products->get(1,['contain'=>'Pictures'])->picture;


    $this->assertEquals($expected,$result);
}

///on messages de validation
    public function testMessagesModif56(){
    $this->setSession();
    $data = $this->buildData();

    $expected = $this->Products->get(1,['contain'=>'Pictures'])->picture;

    $this->post('/my-enterprise/products/1', $data);
    $this->assertContains('Produit test has been saved.', $_SESSION['Flash']['flash'][0]['message']);
}

    private function buildData(){
        return [
            'id' => 1,
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
}
