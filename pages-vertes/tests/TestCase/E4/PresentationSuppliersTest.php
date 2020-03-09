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
class PresentationSuppliersTest extends IntegrationTestCase
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

    /*** Liste des fournisseurs dans les tables de la BD ***/

    // Fonctionnel dans SUPPLIERS
    public function testSuppliersListe9(){
        $this->setSession();
        $this->get('my-enterprise/suppliers');
        $supls = $this->viewVariable('suppliers');

        $this->assertNotEmpty($supls);
    }

    // Attributs identifiant un fournisseur dans SUPPLIERS (avec photo)
    public function testSuppliersListe10(){
        $this->setSession();
        $this->get('my-enterprise/suppliers');
        $supl = $this->viewVariable('suppliers')->first();

        // On teste la présence des attributs (hors id)
        $this->assertNotEmpty($supl['name']);
        $this->assertNotEmpty($supl['address']);
        $this->assertNotEmpty($supl['supplier_category_id']);
        $this->assertNotEmpty($supl['picture']['base64image']);
    }

    // Fonctionnel dans SUPPLIER_CATEGORIES
    public function testSupplierCatgeoriesListe11(){
        $this->setSession();
        $this->get('my-enterprise/suppliers');
        $categories = $this->viewVariable('categories');

        $this->assertNotEmpty($categories);
    }

    // Attributs identifiant un fournisseur dans SUPPLIER_CATEGORIES(avec liste sommaire des fournisseurs
    public function testSupplierCatgeoriesListe12(){
        $this->setSession();
        $this->get('my-enterprise/suppliers');
        $cat = $this->viewVariable('suppliers')->first();

        // Vérification identifiant
        $this->assertNotEmpty($cat['name']);

        // Vérification liste produits
        $this->get('/my-enterprise/supplierCategories/listSuppliers/'.$cat->id);
        $this->assertResponseContains('{"warning":"This category contains 13 supplier(s).","data":[{"id":1,"name":"Lorem ipsum dolor sit amet"},{"id":2,"name":"Lorem ipsum dolor sit amet"},{"id":3,"name":"Lorem ipsum dolor sit amet"},{"id":5,"name":"Lorem ipsum dolor sit amet"},{"id":6,"name":"Lorem ipsum dolor sit amet"},{"id":7,"name":"Lorem ipsum dolor sit amet"},{"id":8,"name":"Lorem ipsum dolor sit amet"},{"id":9,"name":"Lorem ipsum dolor sit amet"},{"id":10,"name":"Lorem ipsum dolor sit amet"},{"id":11,"name":"Lorem ipsum dolor sit amet"},{"id":12,"name":"Lorem ipsum dolor sit amet"},{"id":13,"name":"Lorem ipsum dolor sit amet"},{"id":14,"name":"Lorem ipsum dolor sit amet"}]}');

    }

    // Section publique: Affichage des fournisseurs et leur rôle (catégorie)
    public function testSuppliersListe13(){
        $this->get('enterprises/1/suppliers');
        $this-> assertResponseContains('<h3 class="title">Lorem ipsum dolor sit amet</h3>');
        $this-> assertResponseContains('<td class="product-category"><span class="categories">Lorem ipsum dolor sit amet</span></td>');

    }

    /*** LISTE FOURNISSEUR ***/

    // Les clés primaires ne sont pas visible
    public function testSuppliersListe17(){
        $this->setSession();
        $this->get('my-enterprise/suppliers');

        $this->assertResponseContains('<h3 class="title">Lorem ipsum dolor sit amet</h3>
                                        <span><strong>Address</strong>Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.</span>');
    }

    // Pagination de 20 enregistrements par page
    public function testSuppliersListe18(){
        $this->setSession();
        $this->get('/my-enterprise/suppliers');
        $supls = $this->viewVariable('suppliers')->toArray();

        // Pour des raisons esthétiques, on a préféré n'en afficher que 10
        $this->assertEquals(10, count($supls));
    }

    // Restriction des fournisseurs pour l'entreprise (l'usager connecté)
    public function testSuppliersListe19(){
        $this->setSession();
        $this->get('my-enterprise/suppliers');
        $firstId = $this->viewVariable('suppliers')->first()->id;

        $this->get('/enterprises/2/suppliers');
        $newFirstId = $this->viewVariable('suppliers')->first()->id;

        $this->assertNotEquals($firstId,$newFirstId);

    }

    /*** LISTE SUPPLIER_CATEGORIES ***/

    // Les clés primaires ne sont pas visible
    public function testSupplierCategoriesListe23(){
        $this->setSession();
        $this->get('my-enterprise/suppliers');
        $this->assertResponseContains(' <input type="hidden" name="id" id="id" value="13"/>');

    }

    // Pagination de 10 enregistrements par page
    public function testSupplierCategoriesListe24(){
        $this->setSession();
        $this->get('/my-enterprise/suppliers');
        $cats = $this->viewVariable('categories');
        // Pour des raisons esthétiques, on a préféré en afficher 15
        $this->assertEquals(15, count($cats));
    }

    // Restriction des catégories de fournisseurs pour l'entreprise (l'usager connecté)
    public function testSupplierCategoriesListe25(){
        $this->setSession();
        $this->get('my-enterprise/suppliers');
        $firstId = $this->viewVariable('categories')->first()->id;
        $this->get('/enterprises/2/suppliers');
        $newFirstId = $this->viewVariable('categories')->first()->id;
        $this->assertNotEquals($firstId,$newFirstId);

    }

    // Affichage du nombre de fournisseurs pour chaque catégories
    public function testSupplierCategoriesListe26(){
        $this->setSession();
        $this->get('my-enterprise/suppliers');
        $cat = $this->viewVariable('suppliers')->first();

        // Vérification nombre produits
        $this->get('/my-enterprise/supplierCategories/listSuppliers/'.$cat->id);
        $this->assertResponseContains('This category contains 13 supplier(s)');


    }

    /*** Aide contextuelle ***/

    // L'aide est fonctionnelle à partir de n'importe quel module
    // L'aide est contextuelle  à partir de n'importe quel module
    public function testSupplierAide29(){
        $this->get('enterprises/1/suppliers');
        $this->assertResponseContains('title="View enterprise\'s employees"');
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
}
