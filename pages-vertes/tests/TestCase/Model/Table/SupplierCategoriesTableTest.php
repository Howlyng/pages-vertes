<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SupplierCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SupplierCategoriesTable Test Case
 */
class SupplierCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SupplierCategoriesTable
     */
    public $SupplierCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.supplier_categories',
        'app.enterprises',
        'app.products',
        'app.product_categories',
        'app.pictures',
        'app.employees',
        'app.employe_categories',
        'app.services',
        'app.service_categories',
        'app.suppliers',
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
        $config = TableRegistry::exists('SupplierCategories') ? [] : ['className' => SupplierCategoriesTable::class];
        $this->SupplierCategories = TableRegistry::get('SupplierCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SupplierCategories);

        parent::tearDown();
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
