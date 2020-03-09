<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EmployeCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EmployeCategoriesTable Test Case
 */
class EmployeCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EmployeCategoriesTable
     */
    public $EmployeCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.employe_categories',
        'app.enterprises',
        'app.products',
        'app.product_categories',
        'app.pictures',
        'app.employees',
        'app.services',
        'app.service_categories',
        'app.suppliers',
        'app.users',
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
        $config = TableRegistry::exists('EmployeCategories') ? [] : ['className' => EmployeCategoriesTable::class];
        $this->EmployeCategories = TableRegistry::get('EmployeCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EmployeCategories);

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
