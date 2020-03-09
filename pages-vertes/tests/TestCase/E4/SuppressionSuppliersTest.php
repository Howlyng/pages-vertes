<?php
namespace App\Test\TestCase\Controller;

use App\Model\Table\PicturesTable;
use Cake\ORM\Table;
use Cake\TestSuite\IntegrationTestCase;
use App\Model\Table\SuppliertCategoriesTable;
use Cake\ORM\TableRegistry;


/**
 * App\Controller\SuppliersController Test Case
 */
class DeleteSuppliersTest extends IntegrationTestCase
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

    /*** Destruction d'un fournisseur dans les tables de la BD ***/

    // Fonctionnel dans SUPPLIERS
    public function testSuppliersSuppression9(){
        $this->post('suppliers/delete/4');

        $deleted = $this->Suppliers->find()->where(['id'=>4])->toArray();
        $this->assertEmpty($deleted);
    }

    // Fonctionnel dans SUPPLIER_CATEGORIES
    public function testSupplierCategoriesSuppression10(){

        $this->post('/supplier-categories/delete/2');

        $deletedCat = $this->SupplierCategories->find()->where(['id'=>2])->toArray();
        $this->assertEmpty($deletedCat);
    }

    // Fonctionnel dans PICTURES
    public function testPicturesSuppression11(){
        $picId = $this->Suppliers->get(4)->picture_id;

        $this->post('/suppliers/delete/4');
        $deletedPic=$this->Pictures->find()->where(['id'=> $picId])->toArray();
        $this->assertEmpty($deletedPic);
    }

    /*** DESTRUCTION FOURNISSEUR ***/

    // Propagation de la destruction dans PICTURES pour picture_id
    public function testSuppliersSuppression15(){
        $picId = $this->Suppliers->get(4)->picture_id;

        $this->post('/suppliers/delete/4');
        $deletedPic=$this->Pictures->find()->where(['id'=> $picId])->toArray();
        $this->assertEmpty($deletedPic);
    }

    // Restriction sur la clef étrangère SUPPLIER_CATEGORY_ID et l'usager connecté
    public function testSuppliersSuppression16(){

        $SuplId = $this->SupplierCategories->find()
            ->where(['enterprise_id' => 2])
            ->contain('Suppliers')
            ->first()
            ->id;

        $this->post('/my-enterprise/supplierCategories/delete/'.$SuplId);
        $deletedSupl=$this->SupplierCategories->find()
            ->where(['enterprise_id' => 2])
            ->contain('Suppliers')
            ->first()
            ->id;
        $this->assertEquals( $SuplId,$deletedSupl);
    }

    // Destruction complète de l'enregistrement
    public function testSuppliersSuppression17(){
        $SuplId = $this->Suppliers->get(4)->id;

        $this->post('/suppliers/delete/4');
        $deletedSupl=$this->Suppliers->find()->where(['id'=> $SuplId])->toArray();
        $this->assertEmpty($deletedSupl);
    }

    /*** DESTRUCTION PICTURES ***/

    // Restriction sur la clef étrangère SUPPLIER_CATEGORY_ID et l'usager connecté
    public function testPicturesSuppression20(){
        $SuplId = $this->SupplierCategories->find()
            ->where(['enterprise_id' => 2])
            ->contain('Suppliers')
            ->first()
            ->id;

        $SuplpicId = $this->SupplierCategories->find()
            ->where(['enterprise_id' => 2])
            ->contain('Suppliers')
            ->first()
            ->picture_id;

        $this->post('/suppliers/delete/'. $SuplId);
        $deletedpic=$this->Pictures->find()->where(['id'=> $SuplpicId])->toArray();
        $this->assertEmpty($deletedpic);
    }

    // Destruction complète de l'enregistrement
    public function testPicturesSuppression21(){
        $picId = $this->Suppliers->get(4)->picture_id;

        $this->post('/suppliers/delete/4');
        $deletedPic=$this->Pictures->find()->where(['id'=> $picId])->toArray();
        $this->assertEmpty($deletedPic);

    }

    /*** DESTRUCTION SUPPLIER_CATEGORIES ***/

    // Demande confirmation de suppression
    public function testSupplierCategoriesSuppression25(){
        $this->get('my-enterprise/suppliers');
        $this->assertResponseContains('<a class="delete" href="javascript:void(0)" title="Delete Category " onclick="ModalCatDelete(');
    }

    // Liste les fournisseurs qui seront supprimés
    public function testSupplierCategoriesSuppression26(){
        $this->get('/my-enterprise/supplierCategories/listSuppliers/2');
        // Il n'y a qu'un fournisseur associé à cette catégorie

        $this->assertResponseContains('["Lorem ipsum dolor sit amet"]');
    }

    // Demande une seconde confirmation
    public function testSupplierCategoriesSuppression27(){
        $this->get('my-enterprise/suppliers');
        $this->assertResponseContains('confirm(&quot;Do you really want to delete this category and all its suppliers?&quot;)');
    }

    // Supprime dans SUPPLIERS les associations supplier_category_id
    public function testSupplierCategoriesSuppression28(){
        $catId = $this->SupplierCategories->get(2)->id;

        $this->post('/my-enterprise/supplierCategories/delete/2');
        $deletedSupl=$this->Suppliers->find()->where(['supplier_category_id'=> $catId])->toArray();
        $this->assertEmpty($deletedSupl);
    }

    // Supprime dans PICTURES les associations picture_id pour chaque fournisseurs
    public function testSupplierCategoriesSuppression29(){
        $supls = $this->Suppliers->find()->where(['supplier_category_id'=>2])->toArray();
        $deletedPic = array_map(function($s){return $s->picture_id;}, $supls);

        $this->post('/my-enterprise/supplierCategories/delete/2');
        $deleted = $this->Pictures->find('all')
            ->where(['id IN'=>$deletedPic])->toArray();

        $this->assertEmpty($deleted);
    }

    /***    SAISIES et MESSAGES ***/

    // La liste doit être restreinte parmis la liste des catégories de l'entreprise
    public function testMessageSuppression33(){
        $expected = $this->SupplierCategories->find()->where(['enterprise_id'=>$this->Auth['User']['enterprise']['id']])->toArray();
        $error = $this->SupplierCategories->find()->where(['enterprise_id'=>2])->first();

        $this->get('/my-enterprise/suppliers');
        $list = $this->viewVariable('categories');

        $this->assertEquals($expected, $list);
        $this->assertNotContains($error, $list);
    }

    // Des messages de validation sont affichés à l'usager
    public function testMessageSuppression34(){
        $this->post('/suppliers/delete/4');
        $this->assertContains('has been deleted', $_SESSION['Flash']['flash'][0]['message']);

        $this->post('/my-enterprise/supplierCategories/delete/2');
        $this->assertContains('has been deleted', $_SESSION['Flash']['flash'][0]['message']);
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
