<?php
namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;
use App\Model\Table\SuppliertCategoriesTable;
use Cake\ORM\TableRegistry;


/**
 * App\Controller\SuppliersController Test Case
 */
class ModifSuppliersTest extends IntegrationTestCase
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
        $this->setSession();
    }

    /***   Création d'un fournisseur dans les tables de la BD ***/

    // Fonctionnel dans SUPPLIERS
    public function testSuppliersModif9(){
        $data = $this->buildData();
        $this->post('/my-enterprise/suppliers/1', $data);

        $moded = $this->Suppliers->get(1, ["contain"=>"Pictures"]);

        $this->assertEquals($moded["name"], $data["name"]);
        $this->assertEquals($moded["address"], $data["address"]);
        $this->assertEquals($moded["picture"]["base64image"], "data:image/png;base64," . base64_encode(file_get_contents($data["image"]["tmp_name"])));
    }

    // Tous les attributs ont une valeur dans SUPPLIERS
    public function testSuppliersModif10(){
        $data = $this->buildData();
        //modifier produit 1
        $this->post('/my-enterprise/suppliers/1', $data);

        $newSupplier = $this->Suppliers->get(1);

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
    public function testSupplierCategoriesModif11(){

        $newCat = [
            'name' => 'Moded supl Category'
        ];
        $this->post('/my-enterprise/supplierCategories/edit/1', $newCat);
        $result = $this->Suppliers->SupplierCategories->get(1);
        $this->assertEquals($result['name'], $newCat["name"]);
    }

    // Tous les attributs ont une valeur dans PICTURES
    public function testPictureModif12(){
        $data = $this->buildData();
        $this->post('/my-enterprise/suppliers/edit/1', $data);
        $pictureId = $this->Suppliers->get(1)->picture_id;
        $lastPicture = TableRegistry::get('Pictures')->get(['id'=>$pictureId]);

        $this->assertNotEmpty($lastPicture->id);
        $this->assertNotEmpty($lastPicture->base64image);
    }

    /*** Modif SUPPLIER ***/

    // Unicité de la clé primaire id (n'est pas modifiable)
    public function testSuppliersModif16(){
        $data = $this->buildData();
        $data["id"] = 48;
        $this->post('/my-enterprise/suppliers/1', $data);

        //On s'assure que le fournisseur 1 existe encore
        $firstSupl = $this->Suppliers->find()->first();
        $this->assertNotNull($firstSupl);
        $this->assertNotEmpty($firstSupl);
        //On s'assure que le 48 n'Existe pas. Donc On n'a pas modifié le champs id
        $supl48 = $this->Suppliers->find()->where(['id'=>48])->toArray();
        $this->assertEmpty($supl48);
    }

    // Existence de la clé étrangère SUPPLIER_CATEGORY_ID
    public function testSuppliersModif17(){
        $data = $this->buildData();
        $data['supplier_category_id'] = 800;
        $this->post('/my-enterprise/suppliers/1', $data);
        $supl = $this->Suppliers->get(1);
        $this->assertNotEquals($supl['supplier_category_id'], $data['supplier_category_id']);
        $this->assertResponseContains('This value does not exist');
    }

    // Cohérence de la clef étrangère SUPPLIER_CATEGORY_ID et l'usager connecté
    public function testSuppliersModif18(){
        $data = $this->buildData();
        $data['supplier_category_id'] = 3;
        $supl = $this->Suppliers->get(1);
        $this->post('/my-enterprise/suppliers/1', $data);
        $this->assertNotEquals($supl['supplier_category_id'], $data['supplier_category_id']);
        $this->assertResponseContains('category does not exist');
    }

    // Existence de la clé étrangère PICTURE_ID
    public function testSuppliersModif19(){
        $data['picture_id']=8000;
        $supl = $this->Suppliers->get(1);
        //Assert pas changé
        $this->assertTrue($this->Suppliers->exists(['id'=>$supl["picture_id"]]));
    }

    // picture_id null si aucun
    public function testSuppliersModif20(){
        $data = $this->buildData();
        $data['image']['tmp_name'] = '';
        $this->post('/my-enterprise/suppliers/3', $data);
        $this->assertNull($this->Suppliers->get(3)->picture_id);

    }

    // NAME !=Null
    public function testSuppliersModif21(){
        $data = $this->buildData();

        $this->post('/my-enterprise/suppliers/1', $data);

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Suppliers->get(1)->name);
        $this->assertNotNull($this->Suppliers->get(1)->name);

        $data['name'] = null;
        $this->post('/my-enterprise/suppliers/1', $data);
        $this->assertNotNull($this->Suppliers->get(1)->name);
    }

    // NAME > 5
    public function testSuppliersModif22(){
        $data = $this->buildData();
        $this->post('/my-enterprise/suppliers/1', $data);

        $nameLength = strlen($this->Suppliers->get(1)->name);
        //Assert > 5
        $this->assertTrue($nameLength>5);
        $this->assertNotNull($this->Suppliers->get(1)->name);

        //Assert marche pas
        $data['name'] = '1234';
        $this->post('/my-enterprise/suppliers/1', $data);
        $this->assertNotNull($this->Suppliers->get(1)->name);
    }

    // NAME < 50
    public function testSuppliersModif23(){
        $data = $this->buildData();
        $data['name'] = 'Lorem Ipsum is simply dummy text of the printing a';

        $this->post('/my-enterprise/suppliers/suppliers/1', $data);
        $this->assertNotNull($this->Suppliers->get(1)->name);

    }

    // NAME  ne contient pas de caractère html
    public function testSuppliersModif24(){
        $data = $this->buildData();
        $data['name']= "0<div>";

        $this->post('/my-enterprise/suppliers/1', $data);
        $this->assertFalse(strpos($this->Suppliers->get(1)->name, '<div>'));
    }

    // ADDRESS !=Null
    public function testSuppliersModif25(){
        $data = $this->buildData();

        $this->post('/my-enterprise/suppliers/1', $data);

        //Assert pas null ou empty
        $this->assertNotEmpty($this->Suppliers->get(1)->address);
        $this->assertNotNull($this->Suppliers->get(1)->address);

        $data['address'] = null;
        $this->post('/my-enterprise/suppliers/1', $data);
        $this->assertNotNull($this->Suppliers->get(1)->address);
    }

    // ADDRESS > 5
    public function testSuppliersModif26(){
        $data = $this->buildData();
        $this->post('/my-enterprise/suppliers/1', $data);

        $addressLength = strlen($this->Suppliers->get(1)->address);
        //Assert > 5
        $this->assertTrue($addressLength>5);
        $this->assertNotNull($this->Suppliers->get(1)->address);

        //Assert marche pas
        $data['address'] = '1234';
        $this->post('/my-enterprise/suppliers/1', $data);
        $this->assertNotNull($this->Suppliers->get(1)->address);
    }

    // ADDRESS < 100
    public function testSuppliersModif27(){
        $data = $this->buildData();
        $data['address'] = 'Lorem Ipsum is simply dummy text of the printing a Lorem Ipsum is simply dummy text of the printing a Lorem Ipsum is simply dummy text of the printing a';

        $this->post('/my-enterprise/suppliers/suppliers/1', $data);
        $this->assertNotNull($this->Suppliers->get(1)->address);
    }

    // ADDRESS  ne contient pas de caractère html
    public function testSuppliersModif28(){
        $data = $this->buildData();
        $data['address']= "0<div>";

        $this->post('/my-enterprise/suppliers/1', $data);
        $this->assertFalse(strpos($this->Suppliers->get(1)->address, '<div>'));
    }

    /*** MODIFICATION PICTURES ***/

    // Unicité de la clé primaire id (n'est pas modifiable)
    public function testPictureModif31(){
        $lastPictureId = $this->Suppliers->Pictures->get(1)->id;
        $data = $this->buildData();
        $data['picture']['picture_id'] = 8000;

        $this->post('/my-enterprise/suppliers/1', $data);
        $this->assertEquals($lastPictureId, $this->Suppliers->Pictures->get(1)->id);
    }

    // Cohérence de la clef étrangère picture_id et l'usager connecté
    public function testPictureModif32(){
        $data = $this->buildData();
        $data['picture_id']= 1;

        $supl =  $this->Suppliers->get(4);
        $this->post('/my-enterprise/suppliers/4', $data);

        //Assert modif de supl ET d'image
        $this->assertEquals( $supl['picture_id'],  $this->Suppliers->get(4)->picture_id);
    }

    // Cohérence de la clef étrangère picture_id et le parent(employees, products...)
    public function testPictureModif33(){
        $data = $this->buildData();
        $data['picture_id']= 1;

        $supl =  $this->Suppliers->get(4);
        $this->post('/my-enterprise/suppliers/4', $data);

        //Assert Modif de supl ET d'image
        $this->assertEquals( $supl['picture_id'],  $this->Suppliers->get(4)->picture_id);
    }

    // Vérification que base64image est bien formaté: Début par "data:image/png;base64,"
    public function testPictureModif34(){
        $data = $this->buildData();
        $this->post('/my-enterprise/suppliers/1', $data);

        $pos = strpos($this->Suppliers->get(1,['contain'=>'Pictures'])->picture->base64image,"data:image/png;base64,") === 0;
        $this->assertTrue($pos);
    }

    // Vérification que base64image est bien formaté: Fin par l'image encodé en base64
    public function testPictureModif35(){
        $data = $this->buildData();

        $this->post('/my-enterprise/suppliers/1', $data);
        $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Suppliers->get(1,['contain'=>'Pictures'])->picture->base64image,$base64) !== false;
        $this->assertTrue($pos);
    }

    // Vérification que base64image est non null
    public function testPictureModif36(){
        $data = $this->buildData();

        $this->post('/my-enterprise/suppliers/1', $data);
        $base64 = base64_encode(file_get_contents($data['image']['tmp_name']));
        $pos = strpos($this->Suppliers->get(1,['contain'=>'Pictures'])->picture->base64image, $base64) !== false;
        $this->assertTrue($pos);

        $data['image']['tmp_name'] = null;
        $this->post('/my-enterprise/suppliers/1', $data);
        $this->assertNotNull($this->Suppliers->get(1,['contain'=>'Pictures'])->picture->base64image);

    }

    /*** Modif SUPPLIER_CATEGORIES ***/

    // Unicité de la clé primaire id (généré)
    public function testSupplierCategoryModif39(){
        $data = ['id'=>90, 'name'=>'aaassa'];
        $this->post('/my-enterprise/supplierCategories/edt/1', $data);

        $this->assertEmpty($this->Suppliers->SupplierCategories->find()->where(['id'=>90])->toArray());

    }

    // Existence de la clé étrangère ENTERPRISE_ID
    public function testSupplierCategoryModif40(){
        $data = ['id'=>1,'name'=>'aaassa', 'enterprise_id'=>1];

        $this->post('/my-enterprise/supplierCategories/1', $data);
        $this->assertEquals(1, $this->SupplierCategories->get(1)->enterprise_id);
    }

    // NAME != null
    public function testSupplierCategoryModif41(){
        $data = ['name'=> null];
        $this->post('/my-enterprise/supplierCategories/1', $data);
        $this->assertNotNull($this->SupplierCategories->get(1)->name);
    }

    // NAME  > 5 caractères
    public function testSupplierCategoryModif42(){
        $data = ['name'=> '12345'];

        $expected = $this->SupplierCategories->get(1)->name;
        $this->post('/my-enterprise/supplierCategories/1', $data);
        $this->assertEquals($expected, $this->SupplierCategories->get(1)->name);
    }

    // NAME  < 50 caractères
    public function testSupplierCategoryModif43(){
        $data = ['name'=> ''];
        for($i=0;$i<51;$i++){
            $data['name'] .= 'a';
        }

        $expected = $this->SupplierCategories->get(1)->name;
        $this->post('/my-enterprise/supplierCategories/1', $data);
        $this->assertEquals($expected, $this->SupplierCategories->get(1)->name);
    }

    //NAME  ne contient pas de caractère html
    public function testSupplierCategoryModif44(){
        $data['name']= '45<div></div>';

        $this->post('/my-enterprise/supplierCategories/1', $data);
        $this->assertFalse(strpos($this->SupplierCategories->get(1)->name, $data['name']));
    }

        /*** SAISIES et MESSAGES ***/

    // on ne peut saisir les attributs d'information du fournisseur
    public function testMessagesModif47(){
        $data = $this->buildData();
        $data['name'] = null;
        $data['deletepicture']= 0;

        $this->post('/my-enterprise/suppliers/1', $data);
        $this->assertResponseContains('cannot be empty');
    }

    // on ne peut saisir l'attribut SUPPLIER_CATEGORY_ID par une liste
    // La liste doit être restreinte parmis la liste des catégories de l'entreprise
    public function testMessagesModif48_49(){
        $this->get('/my-enterprise/suppliers/1');

        $this->assertResponseContains(
            '<select name="supplier_category_id" class="form-control wide" required="required" placeholder="Select associated categories" id="supplier-category-id"><option value="1" selected="selected">Lorem ipsum dolor sit amet</option><option value="2">Lorem ipsum dolor sit amet</option></select>');
    }

    // on ne peut saisir un logo du fournisseur
    public function testMessagesModif50(){
        $data = $this->buildData();
        $data['image'] = ['tmp_name'=> null, 'type'=>null];
        $data['picture_id'] = null;
        $data['picture'] = null;
        $expected = $this->Suppliers->get(1,['contain'=>'Pictures'])->picture;
        $this->post('/my-enterprise/products/edit/1', $data);
        $this->assertEquals($expected,$this->Suppliers->get(1,['contain'=>'Pictures'])->picture);
    }

    // des messages de validation sont affichés à l'usager
    public function testMessagesModif52(){
        $data = $this->buildData();

        $this->post('/my-enterprise/suppliers/1', $data);

        $this->assertContains($_SESSION['Flash']['flash'][0]['message'], 'Supplier test has been saved.');
    }

    private function buildData(){
        return [
            'id' => 1,
            'name' => 'Supplier test',
            'address' => '10 rue Test',
            'supplier_category_id' => 1,
            'image' =>  [
                'tmp_name' => 'webroot/img/nav-toogle-icon.png',
                'type' => 'image/png'
            ],
            'deletepicture'=>0
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
