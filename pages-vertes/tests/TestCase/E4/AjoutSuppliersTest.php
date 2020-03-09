<?php
namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;
use App\Model\Table\SuppliertCategoriesTable;
use Cake\ORM\TableRegistry;


/**
 * App\Controller\SuppliersController Test Case
 */
class AjoutSuppliersTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.enterprises',
        'app.users',
        'app.pictures',
        'app.suppliers',
        'app.supplier_categories'
    ];

    public function setUp(){
        parent::setUp();
        $this->Suppliers = TableRegistry::get('Suppliers');
        $this->Pictures = TableRegistry::get('Pictures');
        $this->SupplierCategories = TableRegistry::get('SupplierCategories');
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

    /***   Création d'un fournisseur dans les tables de la BD ***/

    // Fonctionnel dans SUPPLIERS
    public function testSuppliersAjout9(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getSuplCount() +1 ;

        $this->post('/my-enterprise/suppliers/add', $data);

        $result = $this->getSuplCount();
        $this->assertEquals($expected, $result);
    }

    // Tous les attributs ont une valeur dans SUPPLIERS
    public function testSuppliersAjout10(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getSuplCount() +1 ;
        $this->post('/my-enterprise/suppliers/add', $data);

        $newSupplier = $this->Suppliers->get(['id'=>1]);

        $this->assertNotEmpty($newSupplier->id);
        $this->assertNotNull($newSupplier->id);
        $this->assertNotEmpty($newSupplier->name);
        $this->assertNotNull($newSupplier->name);
        $this->assertNotEmpty($newSupplier->address);
        $this->assertNotNull($newSupplier->address);
        $this->assertNotEmpty($newSupplier['supplier_category_id']);
        $this->assertNotNull($newSupplier['supplier_category_id']);
        $this->assertNotEmpty($newSupplier['picture_id']);
        $this->assertNotNull($newSupplier['picture_id']);
    }

    // Fonctionnel dans SUPPLIER_CATEGORIES
    public function testSupplierCategoriesAjout11(){
        $this->setSession();
        $expected = $this->Suppliers->SupplierCategories->find()->count()+1;
        $newCat = [
            'name' => 'New supl Category'
        ];
        $this->post('/my-enterprise/supplierCategories/add', $newCat);
        $result = $this->Suppliers->SupplierCategories->find()->count();
        $this->assertEquals($expected, $result);
    }

    // Tous les attributs ont une valeur dans PICTURES
    public function testPictureAjout12(){
        $this->setSession();
        $data = $this->buildData();
        $this->post('/my-enterprise/suppliers/add', $data);
        $pictureId = $this->Suppliers->find('all')->last()->picture_id;
        $lastPicture = TableRegistry::get('Pictures')->get(['id'=>$pictureId]);

        $this->assertNotEmpty($lastPicture->id);
        $this->assertNotEmpty($lastPicture->base64image);
    }

    /*** AJOUT SUPPLIER ***/

    // Unicité de la clé primaire id (généré)
    public function testSuppliersAjout16(){
        $this->setSession();
        $data = $this->buildData();
        $lastId = $this->Suppliers->find('all')->last()->id;
        $this->post('/my-enterprise/suppliers/add', $data);
        $newSupl = $this->Suppliers->find('all')->last();

        $this->assertEquals($lastId+1, $newSupl->id);
    }

    // Existence de la clé étrangère SUPPLIER_CATEGORY_ID
    public function testSuppliersAjout17(){
        $this->setSession();
        $data = $this->buildData();
        $data['supplier_category_id'] = 800;
        $expected = $this->getSuplCount();
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());
        $this->assertResponseContains('This value does not exist');
    }

    // Cohérence de la clef étrangère SUPPLIER_CATEGORY_ID et l'usager connecté
    public function testSuppliersAjout18(){
        $this->setSession();
        $data = $this->buildData();
        $data['supplier_category_id'] = 3;
        $expected = $this->getSuplCount();
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());
        $this->assertResponseContains('category does not exist');
    }

    // Existence de la clé étrangère PICTURE_ID
    public function testSuppliersAjout19(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getSuplCount()+1;
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Suppliers->find()->last()->picture_id);
        $this->assertNotNull($this->Suppliers->find()->last()->picture_id);

        $this->assertTrue($this->Pictures->exists(['id'=>$this->Suppliers->find()->last()->picture_id]));

        $data['image'] = null;
        $expected = $this->getSuplCount()+1;
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());
    }

    // picture_id null si aucun
    public function testSuppliersAjout20(){
        //login user
        $this->setSession();
        $data = ['name' => 'Supplier test',
            'address' => '10 rue Test',
            'supplier_category_id' => 1,
            'image' =>  [
            'tmp_name' => '',
            'type' => 'image/png']
        ];
        $expected = $this->getSuplCount()+1;
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());
        $this->assertNull($this->Suppliers->find()->last()->picture_id);

    }

    // NAME !=Null
    public function testSuppliersAjout21(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getSuplCount()+1;
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Suppliers->find()->last()->name);
        $this->assertNotNull($this->Suppliers->find()->last()->name);

        $data['name'] = null;
        $expected = $this->getSuplCount();
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());
    }

    // NAME > 5
    public function testSuppliersAjout22(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getSuplCount()+1;
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());

        $nameLength = strlen($this->Suppliers->find()->last()->name);
        //Assert > 5
        $this->assertTrue($nameLength>5);
        $this->assertNotNull($this->Suppliers->find()->last()->name);

        //Assert marche pas
        $data['name'] = '1234';
        $expected = $this->getSuplCount();
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());
    }

    // NAME < 50
    public function testSuppliersAjout23(){
        $this->setAuth();
        $data = $this->buildData();
        $data['name'] = 'Lorem Ipsum is simply dummy text of the printing a';
        $expectedCount = $this->getSuplCount();

        $this->post('/my-enterprise/suppliers/add', $data);

        $this->assertEquals($expectedCount, $this->getSuplCount());
    }

    // NAME  ne contient pas de caractère html
    public function testSuppliersAjout24(){
        $this->setSession();
        $data = $this->buildData();
        $data['name']= "0<div>";

        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertFalse(strpos($this->Suppliers->find()->last()->name, '<div>'));
    }

    // ADDRESS !=Null
    public function testSuppliersAjout25(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getSuplCount()+1;
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Suppliers->find()->last()->address);
        $this->assertNotNull($this->Suppliers->find()->last()->address);

        $data['address'] = null;
        $expected = $this->getSuplCount();
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());
    }

    // ADDRESS > 5
    public function testSuppliersAjout26(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getSuplCount()+1;
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());

        $addressLength = strlen($this->Suppliers->find()->last()->address);
        //Assert > 5
        $this->assertTrue($addressLength>5);
        $this->assertNotNull($this->Suppliers->find()->last()->address);

        //Assert marche pas
        $data['address'] = '1234';
        $expected = $this->getSuplCount();
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());
    }

    // ADDRESS < 100
    public function testSuppliersAjout27(){
        $this->setSession();
        $data = $this->buildData();
        $expected = $this->getSuplCount()+1;
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());

        $addressLength = strlen($this->Suppliers->find()->last()->address);
        //Assert >
        $this->assertTrue($addressLength < 100);
        $this->assertNotNull($this->Suppliers->find()->last()->address);

        $newAddress = "";
        for($i=0;$i<101;$i++){
            $newAddress = $newAddress . "a";
        }
        //Assert marche pas
        $data['address'] = $newAddress;
        $expected = $this->getSuplCount();
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->getSuplCount());
    }

    // ADDRESS  ne contient pas de caractère html
    public function testSuppliersAjout28(){
        $this->setSession();
        $data = $this->buildData();
        $data['address']= "0<div>";

        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertFalse(strpos($this->Suppliers->find()->last()->address, '<div>'));
    }

    /*** AJOUT PICTURES ***/

    // Unicité de la clé primaire id (généré)
    public function testPictureAjout31(){
        $lastPictureId = $this->Suppliers->Pictures->find()->last()->id;
        $this->setSession();
        $data = $this->buildData();
        $expected = $lastPictureId+1;
        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertEquals($expected, $this->Suppliers->Pictures->find()->last()->id);
    }

    // Cohérence de la clef étrangère picture_id et l'usager connecté
    public function testPictureAjout32(){
        $this->setSession();
        $data = $this->buildData();
        $data['picture_id']= 1;

        $expected = $this->getSuplCount()+1;
        $expectedPic = $this->Pictures->find()->count()+1;
        $this->post('/my-enterprise/suppliers/add', $data);

        //Assert ajout de supl ET d'image
        $this->assertEquals($expected, $this->getSuplCount());
        $this->assertEquals($expectedPic, $this->Pictures->find()->count());

        $forcedImage64 = $this->Pictures->get($data['picture_id'])->base64image;
        $this->assertNotEquals($this->Pictures->find()->last()->base64image, $forcedImage64);
    }

    // Cohérence de la clef étrangère picture_id et le parent(employees, products...)
    public function testPictureAjout33(){
        $this->setSession();
        $data = $this->buildData();

        $expected = $this->getSuplCount()+1;
        $expectedPic = $this->Pictures->find()->count()+1;
        $this->post('/my-enterprise/suppliers/add', $data);

        //Assert ajout de supl ET d'image
        $this->assertEquals($expected, $this->getSuplCount());
        $this->assertEquals($expectedPic, $this->Pictures->find()->count());

        $lasPic = $this->Pictures->find()->last();
        $lasSupl = $this->Suppliers->find()->last();
        $this->assertEquals($lasPic->id, $lasSupl->picture_id );
    }

    // Vérification que base64image est bien formaté: Début par "data:image/png;base64,"
    public function testPictureAjout34(){
        $this->setSession();
        $data = $this->buildData();

        $expected = $this->getSuplCount()+1;
        $expectedPic = $this->Pictures->find()->count()+1;
        $this->post('/my-enterprise/suppliers/add', $data);

        //Assert ajout de supl ET d'image
        $this->assertEquals($expected, $this->getSuplCount());
        $this->assertEquals($expectedPic, $this->Pictures->find()->count());

        $pos = strpos($this->Pictures->find()->last()->base64image,"data:image/png;base64,") === 0;
        $this->assertTrue($pos);
    }

    // Vérification que base64image est bien formaté: Fin par l'image encodé en base64
    public function testPictureAjout35(){
        $this->setSession();
        $data = $this->buildData();


        $this->post('/my-enterprise/suppliers/add', $data);
        $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Pictures->find()->last()->base64image,$base64) !== false;
        $this->assertTrue($pos);
    }

    // Vérification que base64image est non null
    public function testPictureAjout36(){
        $this->setSession();
        $data = $this->buildData();


        $this->post('/my-enterprise/suppliers/add', $data);
        $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Pictures->find()->last()->base64image, $base64) !== false;
        $this->assertTrue($pos);

        $expected = $this->Pictures->find()->count();
        $data['image']['tmp_name'] = null;
        $this->post('/my-enterprise/suppliers/add', $data);
        $result = $this->Pictures->find()->count();
        $this->assertEquals($expected, $result);

    }

    /*** AJOUT SUPPLIER_CATEGORIES ***/

    // Unicité de la clé primaire id (généré)
    public function testSupplierCategoryAjout39(){
        $this->setSession();
        $data = ['name'=>'aaassa'];
        $expected = $this->SupplierCategories->find()->last()->id + 1;
        $this->post('/my-enterprise/supplierCategories/add', $data);
        $result = $this->SupplierCategories->find()->last()->id;
        $this->assertEquals($expected, $result);

    }

    // Existence de la clé étrangère ENTERPRISE_ID
    public function testSupplierCategoryAjout40(){
        $this->setSession();
        $data = ['name'=>'aaassa'];

        $this->post('/my-enterprise/supplierCategories/add', $data);
        $result = $this->SupplierCategories->find()->last()->enterprise_id;
        $this->assertTrue($this->SupplierCategories->Enterprises->exists(['id'=>$result]));
    }

    // Cohérence de la clef étrangère ENTERPRISE_ID et l'usager connecté
    public function testSupplierCategoryAjout41(){
        $this->setSession();
        $data = ['name'=>'aaassa'];

        $this->post('/my-enterprise/supplierCategories/add', $data);
        $result = $this->SupplierCategories->find()->contain('Enterprises')->last();
        $this->assertEquals($this->Auth['User']['id'], $result->enterprise->owner_id);
    }

    // NAME != null
    public function testSupplierCategoryAjout42(){
        $this->setSession();
        $data = ['name'=> null];

        $expected = $this->SupplierCategories->find()->count();
        $this->post('/my-enterprise/supplierCategories/add', $data);
        $result = $this->SupplierCategories->find()->count();
        $this->assertEquals($expected, $result);
    }

    // NAME  > 5 caractères
    public function testSupplierCategoryAjout43(){
        $this->setSession();
        $data = ['name'=> '1234'];

        $expected = $this->SupplierCategories->find()->count();
        $this->post('/my-enterprise/supplierCategories/add', $data);
        $result = $this->SupplierCategories->find()->count();
        $this->assertEquals($expected, $result);
    }

    // NAME  < 50 caractères ?
    public function testSupplierCategoryAjout44(){
        $this->setSession();
        $data = ['name'=> ''];
        for($i=0;$i<51;$i++){
            $data['name'] .= 'a';
        }

        $expected = $this->SupplierCategories->find()->count();
        $this->post('/my-enterprise/supplierCategories/add', $data);
        $result = $this->SupplierCategories->find()->count();
        $this->assertEquals($expected, $result);
    }

    //NAME  ne contient pas de caractère html
    public function testSupplierCategoryAjout45(){
        $this->setSession();
        $data = ['name'=> '45<div></div>'];

        $this->post('/my-enterprise/supplierCategories/add', $data);

        $cat = $this->SupplierCategories->find()->last();
        $this->assertFalse(strpos($cat->name, $data['name']));
    }

        /*** SAISIES et MESSAGES ***/

    // on ne peut saisir les attributs d'information du fournisseur
    public function testMessagesAjout48(){
        $this->setSession();
        $data = $this->buildData();
        $data['name'] = null;

        $this->post('/my-enterprise/suppliers/add', $data);
        $this->assertResponseContains('cannot be empty');
    }

    // on ne peut saisir l'attribut SUPPLIER_CATEGORY_ID par une liste
    public function testMessagesAjout49(){
        $this->setSession();
        $this->get('/my-enterprise/suppliers/add');

        //Les options de la liste de sélection propose le nom de la catégorie
        $this->assertResponseContains(
            '<select name="supplier_category_id" class="form-control wide" required="required" placeholder="Select associated categories" id="supplier-category-id"><option value="1">Lorem ipsum dolor sit amet</option><option value="2">Lorem ipsum dolor sit amet</option></select>');

    }


    // des messages de validation sont affichés à l'usager
    public function testMessagesAjout52(){
        $this->setSession();
        $data = $this->buildData();

        $this->post('/my-enterprise/suppliers/add', $data);

        $this->assertContains($_SESSION['Flash']['flash'][0]['message'], 'The supplier has been saved.');
    }

    private function buildData(){
        return [
            'name' => 'Supplier test',
            'address' => '10 rue Test',
            'supplier_category_id' => 1,
            'image' =>  [
                'tmp_name' => 'webroot/img/nav-toogle-icon.png',
                'type' => 'image/png'
            ],
        ];
    }

    function setAuth()
    {
        $this->session([
            'Auth' => $this->Auth
        ]);
    }
    private function setSession(){
        $this->session([
            'Auth' => $this->Auth
        ]);
    }

    private function getSuplCount(){
        return $this->Suppliers->find()->count();
    }
}
