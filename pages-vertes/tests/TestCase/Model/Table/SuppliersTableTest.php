<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SuppliersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SuppliersTable Test Case
 */
class SuppliersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SuppliersTable
     */
    public $Suppliers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.suppliers',
        'app.supplier_categories',
        'app.enterprises',
        'app.products',
        'app.product_categories',
        'app.pictures',
        'app.employees',
        'app.employe_categories',
        'app.services',
        'app.service_categories',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Suppliers') ? [] : ['className' => SuppliersTable::class];
        $this->Suppliers = TableRegistry::get('Suppliers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Suppliers);

        parent::tearDown();
    }

    public function testGetSuplListingFromEnterprise_length(){
        $result = $this->Suppliers->getSuplListingFromEnterprise(1);
        $expected = 2;

        $this->assertEquals(sizeof($result), $expected);
    }

    public function testGetSuplListingFromEnterprise_SuplId(){
        $result = $this->Suppliers->getSuplListingFromEnterprise(1);
        $mapped = array_map(function ($id){return $id->id;}, $result);

        $this->assertEquals($mapped,[1, 2]);
    }

    public function testGetSuplListingFromEnterprise_Cat(){
        $result = $this->Suppliers->getSuplListingFromEnterprise(1);
        $mapped = array_map(function ($p){return $p->Category;}, $result);

        //On vérifie les catégories
        $this->assertEquals($result[0]->supplier_category['id'], 1);
        $this->assertEquals($result[1]->supplier_category['id'], 2);
        //On vérifie les entreprises
        $this->assertEquals($result[1]->supplier_category->enterprise_id, 1);
        $this->assertEquals($result[1]->supplier_category->enterprise_id, 1);
    }

    public function testGetSuplListingFromEnterprise_Pic(){
        $result = $this->Suppliers->getSuplListingFromEnterprise(1);
        $mapped = array_map(function ($p){return $p->picture['id'];}, $result);

        //On vérifie les photos
        $this->assertEquals($mapped, [1,1]);
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
