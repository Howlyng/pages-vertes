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
class DeleteProduitsTest extends IntegrationTestCase
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
//        'app.employe_categories',\
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
    public function testProduitsDelete9(){

        //supprimer produit 1
        $this->post('/products/delete/1');

        $deleted = $this->Products->find()->where(['id'=>1])->toArray();
        $this->assertEmpty($deleted);
    }

//     Fonctionnel dans PRODUCTCATEGORIES
    public function testProduitCategoriesDelete10(){
        //supprimer cat 1
        $this->post('/product-categories/delete/1');
        $deletedCat = $this->ProductCategories->find()->where(['id'=>1])->toArray();
       $this->assertEmpty($deletedCat);

    }

//  Fonctionnel Pictures
    public function testPictureDelete11(){
       //Aller chercher la photo du 1er produit de la 1ere catégorie
        $picId = $this->ProductCategories->find()
            ->contain('Products')
            ->first()
            ->products[0]
            ->picture_id;

        $this->post('/product-categories/delete/1');
        $deletedPic = $this->Pictures->find()->where(['id' => $picId])->toArray();

        $this->assertEmpty($deletedPic);
    }


/**************************Dest produit*****************************************************/

//	Propagation de la destruction dans PICTURES pour picture_id
    public function testProduitDelete15(){
        $picId = $this->Products->get(1)->picture_id;

        //supprimer produit 1
        $this->post('/products/delete/1');

        $this->assertEmpty($this->Pictures->find()->where(['id'=> $picId])->toArray());
    }

//	Restriction sur la clef étrangère PRODUCT_CATEGORY_ID et l'usager connecté
    public function testProduitDelete16(){
        //l'usagé setté dans cette connexion est de l'entreprise 1;

        //aller chercher le 1er produit de 1ere categorie de entreprise 2;
        $prodId = $this->ProductCategories->find()
            ->where(['enterprise_id' => 2])
            ->contain('Products')
            ->first()
            ->products[0]
            ->id;

        $this->post('products/delete/'.$prodId);

        $prodId2 = $this->ProductCategories->find()
            ->where(['enterprise_id' => 2])
            ->contain('Products')
            ->first()
            ->products[0]
            ->id;
        //Il est toujours present!
        $this->assertEquals($prodId,$prodId2);
    }

    //	Destruction complète de l'enregistrement
    public function testProduitsDelete17()
    {
        //supprimer produit 1
        $this->post('/products/delete/1');

        $deleted = $this->Products->find()->where(['id'=>1])->toArray();
        $this->assertEmpty($deleted);
    }
/*******************************PICTURES************************************************************************/



/********************************Product Categories**************************************************************************/
//Demande confirmation de suppression
    public function testProduitCatDelete25(){

        $this->get('my-enterprise/products');
        //S'assurer que le bouton delete contient un onclick pour afficher la modal
        $this->assertResponseContains('<a class="delete" href="javascript:void(0)" title="Delete Category " onclick="ModalCatDelete(');

    }
    //Liste les produits qui seront supprimé
    public function testProduitCatDelete26(){
        $this->get('/my-enterprise/productCategories/listProducts/1');
        $this->assertResponseContains('{"warning":"This category contains 14 product(s).","data":[{"id":1,"name":"Lorem ipsum dolor sit amet"},{"id":4,"name":"Lorem ipsum dolor sit amet"},{"id":7,"name":"Lorem ipsum dolor sit amet"},{"id":10,"name":"Lorem ipsum dolor sit amet"},{"id":13,"name":"Lorem ipsum dolor sit amet"},{"id":16,"name":"Lorem ipsum dolor sit amet"},{"id":19,"name":"Lorem ipsum dolor sit amet"},{"id":22,"name":"Lorem ipsum dolor sit amet"},{"id":25,"name":"Lorem ipsum dolor sit amet"},{"id":28,"name":"Lorem ipsum dolor sit amet"},{"id":31,"name":"Lorem ipsum dolor sit amet"},{"id":34,"name":"Lorem ipsum dolor sit amet"},{"id":37,"name":"Lorem ipsum dolor sit amet"},{"id":40,"name":"Lorem ipsum dolor sit amet"}]}');
    }
    public function testProduitCatDelete27(){
        $this->get('/my-enterprise/products');
        $this->assertResponseContains('confirm(&quot;Do you really want to delete this category and all its products?&quot;)');
    }

    //Supprime dans PRODUCTS les associations product_category_id
    public function testProduitCatDelete28(){
        //supprimer cat 1
        $this->post('/product-categories/delete/1');
        $deletedProd = $this->Products->find()->where(['product_category_id'=>1])->toArray();
        $this->assertEmpty($deletedProd);
    }

    //Supprime dans PICTURES les assocications picture_id pour chaque produits
    public function testProduitCatDelete29(){
        $prods = $this->Products->find()->where(['product_category_id'=>1])->toArray();
        $deletedPic = array_map(function($p){return $p->picture_id;}, $prods);

        //supprimer cat 1
        $this->post('/product-categories/delete/1');
        $deleted = $this->Pictures->find('all')
            ->where(['id IN'=>$deletedPic])->toArray();

        $this->assertEmpty($deleted);
    }

    /**************************************Messages*********************************************************/
    //La liste doit être restreinte parmis la liste des catégories de l'entreprise
    public function testProduitCatDelete33(){
        //supprimer cat 1
        $expected = $this->ProductCategories->find()->where(['enterprise_id'=>$this->Auth['User']['enterprise']['id']])->order(['name'=>'asc'])->limit(15)->toArray();
        $notMine = $this->ProductCategories->find()->where(['enterprise_id'=>2])->first();

        $this->get('/my-enterprise/products');
        $list = $this->viewVariable('categories')->toArray();

        $this->assertEquals($expected, $list);
        $this->assertNotContains($notMine, $list);
    }
    //des messages de validation sont affichés à l'usager
    public function testProduitsDelete34(){
        //Produits
        $this->post('/products/delete/1');
        $this->assertContains('has been deleted', $_SESSION['Flash']['flash'][0]['message']);

        //Categories
        $this->post('/product-categories/delete/1');
        $this->assertContains('have been deleted', $_SESSION['Flash']['flash'][0]['message']);
    }


/*********************************************************************************************************/
        private function setSession(){
        $this->session([
            'Auth' => $this->Auth
        ]);
    }
}
