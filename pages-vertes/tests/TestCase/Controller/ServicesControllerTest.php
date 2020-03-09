<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ServicesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\ServicesController Test Case
 */
class ServicesControllerTest extends IntegrationTestCase
{

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
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
       ## $this->markTestIncomplete('Not implemented yet.');
        $this->session([
  'Auth' => [
    'User' => [
      'id' => 1,
      'username' => 'p1@gmail.ca',
      'password' => '123456'
    ]
  ]
]);
        $this->get('/enterprises/1/services');
        $this->assertResponseOk();
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
//        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
//        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
//        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
//        $this->markTestIncomplete('Not implemented yet.');
    }
}
