<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServiceCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServiceCategoriesTable Test Case
 */
class ServiceCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ServiceCategoriesTable
     */
    public $ServiceCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.service_categories',
        'app.enterprises',
        'app.users',
        'app.pictures',
        'app.employees',
        'app.products',
        'app.services',
        'app.suppliers',
        'app.employe_categories',
        'app.product_categories',
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
        $config = TableRegistry::exists('ServiceCategories') ? [] : ['className' => ServiceCategoriesTable::class];
        $this->ServiceCategories = TableRegistry::get('ServiceCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ServiceCategories);

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
