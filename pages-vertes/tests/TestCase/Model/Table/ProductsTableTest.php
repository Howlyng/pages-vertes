<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use function PHPSTORM_META\map;

/**
 * App\Model\Table\ProductsTable Test Case
 */
class ProductsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductsTable
     */
    public $Products;

    /**
     * Fixtures
     *
     * @var array
     */
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

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Products') ? [] : ['className' => ProductsTable::class];
        $this->Products = TableRegistry::get('Products', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Products);

        parent::tearDown();
    }

//////////////////Test de fetch de produits par entreprise///////////////////////////////////
    public function testGetProdListingFromEnterprise_length(){
        $result = $this->Products->getProdListingFromEnterprise(1);
        $expected = 2;

        $this->assertEquals(sizeof($result), $expected);
    }
    public function testGetProdListingFromEnterprise_ProdId(){
        $result = $this->Products->getProdListingFromEnterprise(1);
        $mapped = array_map(function ($id){return $id->id;}, $result);

        $this->assertEquals($mapped,[1, 2]);
    }
    public function testGetProdListingFromEnterprise_Cat(){
        $result = $this->Products->getProdListingFromEnterprise(1);
        $mapped = array_map(function ($p){return $p->Category;}, $result);

        //Bonne catégories
        $this->assertEquals($result[0]->product_category['id'], 1);
        $this->assertEquals($result[1]->product_category['id'], 2);
        //Confirme que pour bonne entreprise
        $this->assertEquals($result[1]->product_category->enterprise_id, 1);
        $this->assertEquals($result[1]->product_category->enterprise_id, 1);
    }
    public function testGetProdListingFromEnterprise_Pic(){
        $result = $this->Products->getProdListingFromEnterprise(1);
        $mapped = array_map(function ($p){return $p->picture['id'];}, $result);

        //Bonnes photos
        $this->assertEquals($mapped, [1,1]);
    }
///////////////////////////////////////////////////////////////////////////////////////////////
}
