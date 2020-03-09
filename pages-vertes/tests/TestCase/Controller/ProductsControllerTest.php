<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ProductsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\ProductsController Test Case
 */
class ProductsControllerTest extends IntegrationTestCase
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
     * Test publicIndex method
     *
     * @return void
     */
    public function testPublicIndex_GoodId()
    {
        $this->get('/enterprises/1/products');
        $this->assertResponseOk();
        $this->assertResponseNotContains('input');
    }
    public function testPublicIndex_BadId()
    {
        $this->get('/enterprises/s/products');
        $this->assertRedirect('/');
    }


    /**
     * Test privateIndex method
     *
     * @return void
     */
    public function testPrivateIndex()
    {
        $this->get('/my-enterprise/products');
        $this->assertRedirect('/login?redirect=%2Fmy-enterprise%2Fproducts');


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

        $this->get('/my-enterprise/products');
        $this->assertResponseOk();
        $this->assertResponseContains('input');
    }
}
