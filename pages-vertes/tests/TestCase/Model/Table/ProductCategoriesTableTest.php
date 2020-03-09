<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductCategoriesTable Test Case
 */
class ProductCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductCategoriesTable
     */
    public $ProductCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.product_categories',
        'app.enterprises',
        'app.users',
        'app.pictures',
        'app.employees',
        'app.products',
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
        $config = TableRegistry::exists('ProductCategories') ? [] : ['className' => ProductCategoriesTable::class];
        $this->ProductCategories = TableRegistry::get('ProductCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductCategories);

        parent::tearDown();
    }
}
