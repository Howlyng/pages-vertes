<?php
namespace App\Test\TestCase\Controller;

use App\Controller\SuppliersController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\SuppliersController Test Case
 */
class SuppliersControllerTest extends IntegrationTestCase
{

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
     * Test publicIndex method
     *
     * @return void
     */
    public function testPublicIndex__GoodId()
    {
        $this->get('/enterprises/1/suppliers');
        $this->assertResponseOk();
        $this->assertResponseNotContains('input');
    }
    public function testPublicIndex_BadId()
    {
        $this->get('/enterprises/s/suppliers');
        $this->assertRedirect('/');
    }

    /**
     * Test privateIndex method
     *
     * @return void
     */
    public function testPrivateIndex()
    {
        $this->get('/my-enterprise/suppliers');
        $this->assertRedirect('/login?redirect=%2Fmy-enterprise%2Fsuppliers');


        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'username' => 'p1@gmail.ca',
                    'password' => '123456',
                    'enterprise' =>[
                        'id'=> 1
                    ]
                ]
            ]
        ]);

        $this->get('/my-enterprise/suppliers');
        $this->assertResponseOk();
        $this->assertResponseContains('input');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
