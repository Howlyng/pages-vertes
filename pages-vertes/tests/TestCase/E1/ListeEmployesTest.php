<?php

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\File;
use App\Controller\ProductsController;
use App\Controller\ProductCategoriesController;
use App\Model\Table\ProductsTable;
use App\Model\Table\ProductCategoriesTable;

/**
 * App\Controller\EmployeesController Test Case
 */
class ListeEmployesTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.employees',
        'app.employe_categories',
        'app.enterprises',
        'app.users',
        'app.pictures',
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Employees = TableRegistry::get('Employees');
        $this->Pictures = TableRegistry::get('Pictures');
        $this->EmployeCategories = TableRegistry::get('EmployeCategories');
        $this->Auth = [
            'User' => [
                'id' => 1,
                'username' => 'e1@gmail.ca',
                'password' => '123456',
                'enterprise' => [
                    'id' => 1
                ]
            ]
        ];
        $this->setAuth();
    }

    //  Fonctionnel dans EMPLOYEES
    public function testEmployeesList9()
    {
        $this->get('my-enterprise/employees');
        $employees = $this->viewVariable('employees');

        $this->assertNotEmpty($employees);
    }

    //  Attributs identifiant un employé dans EMPLOYEES (avec photo)
    public function testEmployeesList10()
    {
        $this->get('my-enterprise/employees');
        $employees = $this->viewVariable('employees');

        $emp = $employees->first();

        $this->assertNotEmpty($emp['firstname']);
        $this->assertNotEmpty($emp['lastname']);
        $this->assertNotEmpty($emp['picture']['base64image']);
    }

    //  Fonctionnel dans EMPLOYE_CATEGORIES
    public function testEmployeesList11()
    {
        $this->get('my-enterprise/employees');
        $categories = $this->viewVariable('categories');

        $this->assertNotEmpty($categories);
    }

    //  Attributs identifiant un employé dans EMPLOYE_CATEGORIES(avec liste sommaire des employés)
    public function testEmployeesList12()
    {
        //tester la liste des catégories
        $this->get('my-enterprise/employees');
        $categories = $this->viewVariable('categories');

        $cat = $categories->first();

        $this->assertNotEmpty($cat['name']);

        //tester les employés dans la catégorie

        //La catégorie 1 contient deux employés
        $this->get('my-enterprise/employeCategories/listEmployees/1');
        $this->assertResponseContains('"data":[{"id":1,"firstname":"Lorem ipsum dolor","lastname":"Lorem ipsum dolor"},{"id":2,"firstname":"Lorem ipsum dolor","lastname":"Lorem ipsum dolor"}]');
    }

    //  Section publique: Affichage des employés et leur rôle (catégorie)
    public function testEmployeesList13()
    {
        $this->get('enterprises/1/employees');
        $employees = $this->viewVariable('employees');

        $emp = $employees->first();

        $this->assertNotEmpty($emp['employe_category']['name']);
    }

    /*
  * ======================================
  * PRÉ ET POST CONDITIONS
  * ======================================
  */
    //  Les clés primaires ne sont pas visible
    public function testEmployeesList17()
    {
        $this->get('my-enterprise/employees');

        $HTMLRecord = '<td class="product-details">
                                            <h3 class="title">Lorem ipsum dolor Lorem ipsum dolor</h3>
                                            <span class="add-id"><strong>Birthday</strong>2/14/18</span>
                                            <span class="add-id"><strong>Since</strong>2/14/18</span>';

        $this->assertResponseContains($HTMLRecord);
    }

    //  Pagination de 20 enregistrements par page (Pour nos standards, on affiche 10 par page)
    public function testEmployeesList18()
    {
        $this->get('my-enterprise/employees');
        $employees = $this->viewVariable('employees');

        $totalEmpCount = $this->Employees->find('all')->count();
        $viewEmpCount = $employees->count();
        $limitPerPage = 10;

        $this->assertGreaterThan($viewEmpCount, $totalEmpCount);
        $this->assertEquals($limitPerPage, $viewEmpCount);
    }

    //  Restriction des employés pour l'entreprise (l'usager connecté)
    public function testEmployeesList19()
    {
        $this->get('my-enterprise/employees');
        $empFromEnt1 = $this->viewVariable('employees')->first();

        $this->get('enterprises/2/employees');
        $employeesFromEnt2 = $this->viewVariable('employees');

        $notPresent = true;
        foreach ($employeesFromEnt2 as $e2) {
            if ($e2['id'] === $empFromEnt1['id'])
                $notPresent = false;
        }

        $this->assertNotEquals($empFromEnt1['id'], $employeesFromEnt2->first()['id']);
        $this->assertTrue($notPresent);
    }

    //  Les clés primaires ne sont pas visible
    public function testEmployeesList23()
    {
        $this->get('my-enterprise/employees');

        $HTMLRecord = '<input type="submit" id="submit2"
                                                       style="display: none"/>';
        $this->assertResponseContains($HTMLRecord);
    }

    //  Pagination de 10 enregistrements par page (Pour nos standards, on affiche 15 par page)
    public function testEmployeesList24()
    {
        $this->get('enterprises/2/employees');
        $categories = $this->viewVariable('categories');

        $totalCatCount = $this->Employees->find('all')->count();
        $viewCatCount = $categories->count();
        $limitPerPage = 15;

        $this->assertGreaterThan($viewCatCount, $totalCatCount);
        $this->assertEquals($limitPerPage, $viewCatCount);
    }

    //  Restriction des catégories d'employés pour l'entreprise (l'usager connecté)
    public function testEmployeesList25()
    {
        $this->get('my-enterprise/employees');
        $catFromEnt1 = $this->viewVariable('categories')->first();

        $this->get('enterprises/2/employees');
        $categoriesFromEnt2 = $this->viewVariable('categories');

        $notPresent = true;
        foreach ($categoriesFromEnt2 as $c2) {
            if ($c2['id'] === $catFromEnt1['id'])
                $notPresent = false;
        }

        $this->assertNotEquals($catFromEnt1['id'], $categoriesFromEnt2->first()['id']);
        $this->assertTrue($notPresent);
    }

    //  Affichage du nombre d'employé pour chaque catégories
    public function testEmployeesList26()
    {
        //NOTE : De part notre facon d'utiliser la liste associé à une table d'attribut, la traduction se fait côté
        //server pour ajouter le count à la phrase. (Le count testé est le même que celui dans la phrase affichée,
        //mais on ne l'utilise pas dans la view.

        $this->get('my-enterprise/employeCategories/listEmployees/1');

        $this->assertResponseContains('"totalEmployees":2');
    }

    //     L'aide est fonctionnelle à partir de n'importe quel module
    public function testEmployeesList29()
    {
        //Test de l'aide pour l'édition d'un employé
        $this->get('my-enterprise/employees');

        $this->assertResponseContains('<a class="edit"
                                                               title="Edit this employee"
                                                               href="/my-enterprise/employees/edit/1">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>');
    }

    //  L'aide est complète pour les employés  et les catégories
    public function testEmployeesList31()
    {
        //Test de l'aide pour l'ajout
        $this->get('my-enterprise/employees');

        //Je ne sais pas s'il faut mettre le link en entier ou juste le title, alors je mets les deux
        $this->assertResponseContains('<a class="delete" href="javascript:void(0)"
                                                               title="Delete Category "
                                                               onclick="ModalCatDelete(2)">
                                                                <i class="fa fa-trash"></i>
                                                            </a>');
        $this->assertResponseContains('title="Delete Category "');
    }

    /*
  * ======================================
  * Utilitaires
  * ======================================
  */

    private
    function getEmpCount()
    {
        return $this->Employees->find('all')->count();
    }

    private
    function getCatEmpCount()
    {
        return $this->EmployeCategories->find('all')->count();
    }

    private
    function setAuth()
    {
        $this->session([
            'Auth' => $this->Auth
        ]);
    }
}