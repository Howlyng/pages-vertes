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
class SuppressionEmployesTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.employees',
        'app.employe_categories',
//        'app.products',
//        'app.product_categories',
        'app.enterprises',
        'app.users',
        'app.pictures',
//        'app.services',
//        'app.service_categories',
//        'app.suppliers',
//        'app.supplier_categories'
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
    /*
         * ======================================
         * Tests positif:
         * Il y aura une suppression dans la base de données après chacun de ces tests
         * ======================================
         */

    //  Fonctionnel dans EMPLOYEES
    public function testEmployeesSuppression9()
    {
        $expectedCount = $this->getEmpCount() - 1;

        $this->post('/employees/delete/1');

        $this->assertEquals($expectedCount, $this->getEmpCount());
        $deletedEmployee = $this->Employees->find()->where(['id' => 1])->toArray();
        $this->assertEmpty($deletedEmployee);
    }

    //  Fonctionnel dans EMPLOYE_CATEGORIES
    public function testEmployeesSuppression10()
    {
        //Selon les fixtures, l'employé 1 et 2 sont à la catégorie 1
        $expectedEmpCount = $this->getEmpCount() - 2;
        $expectedCatCount = $this->getCatEmpCount() - 1;

        $this->post('/employeCategories/delete/1');

        $deletedEmpCategory = $this->EmployeCategories->find()->where(['id' => 1])->toArray();

        $this->assertEquals($expectedEmpCount, $this->getEmpCount());
        $this->assertEquals($expectedCatCount, $this->getCatEmpCount());
        $this->assertEmpty($deletedEmpCategory);
    }

    //  Fonctionnel dans PICTURES
    public function testEmployeesSuppression11()
    {
        $emp = $this->Employees->get(1);
        //Chercher la photo du premier employé
        $picture = $this->Pictures->get($emp['picture_id']);

        $expectedPictureCount = $this->Pictures->find('all')->count() - 1;
        $expectedEmpCount = $this->getEmpCount() - 1;

        $this->post('/employees/delete/1');

        $deletedPicture = $this->Pictures->find()->where(['id' => $picture['id']])->toArray();

        $this->assertEquals($expectedEmpCount, $this->getEmpCount());
        $this->assertEquals($expectedPictureCount, $this->Pictures->find('all')->count());
        $this->assertEmpty($deletedPicture);
    }

    //  Propagation de la destruction dans PICTURES pour picture_id
    public function testEmployeesSuppression15()
    {
        //Même test que plus haut, car on fait la même chose.
        $emp = $this->Employees->get(1);
        //Chercher la photo du premier employé
        $picture = $this->Pictures->get($emp['picture_id']);

        $expectedPictureCount = $this->Pictures->find('all')->count() - 1;
        $expectedEmpCount = $this->getEmpCount() - 1;

        $this->post('/employees/delete/1');

        $deletedPicture = $this->Pictures->find()->where(['id' => $picture['id']])->toArray();

        $this->assertEquals($expectedEmpCount, $this->getEmpCount());
        $this->assertEquals($expectedPictureCount, $this->Pictures->find('all')->count());
        $this->assertEmpty($deletedPicture);
    }

    //  Restriction sur la clef étrangère EMPLOYE_CATEGORY_ID et l'usager connecté
    public function testEmployeesSuppression16()
    {
        //Selon les fixtures, l'employé 1 et 2 sont à la catégorie 1
        //Le user connecté (le 1) va tenter de supprimer l'employé 3

        $expectedEmpCount = $this->getEmpCount();
        $emp = $this->Employees->find()->where(['id' => 3])->toArray();

        $this->post('/employees/delete/3');

        $empNonDeleted = $this->Employees->find()->where(['id' => 3])->toArray();

        $this->assertEquals($expectedEmpCount, $this->getEmpCount());
        $this->assertEquals($emp, $empNonDeleted);

        //Toujours le même user va tenter de supprimer la catégorie 3

        $cat = $this->EmployeCategories->find()->where(['id' => 3])->toArray();
        $expectedCatCount = $this->getCatEmpCount();
        $this->post('/employeCategories/delete/3');

        $catNonDeleted = $this->EmployeCategories->find()->where(['id' => 3])->toArray();
        $this->assertEquals($expectedCatCount, $this->getCatEmpCount());
        $this->assertEquals($emp, $empNonDeleted);
    }

    //	Destruction complète de l'enregistrement
    public function testEmployeesSuppression17()
    {
        //Supprimer Employé 1
        $this->post('/employees/delete/1');

        $deleted = $this->Employees->find()->where(['id' => 1])->toArray();
        $this->assertEmpty($deleted);
    }

    //  Restriction sur la clef étrangère EMPLOYE_CATEGORY_ID et l'usager connecté
    public function testEmployeesSuppression20()
    {
        //Selon les fixtures, l'employé 1 et 2 sont à la catégorie 1
        //Le user connecté (le 1) va tenter de supprimer un employé pas à lui pour tester la photo

        $expectedPictureCount = $this->Pictures->find('all')->count();
        $emp = $this->Employees->get(3);
        $pic = $this->Pictures->get($emp['picture_id']);

        $this->post('/employees/delete/3');

        $picNonDeleted = $this->Pictures->get($emp['picture_id']);

        $this->assertEquals($expectedPictureCount, $this->Pictures->find('all')->count());
        $this->assertEquals($pic, $picNonDeleted);
    }

    //	Destruction complète de l'enregistrement
    public function testEmployeesSuppression21()
    {
        //Supprimer Employé 1
        $emp = $this->Employees->get(1);
        $pic = $this->Pictures->get($emp['picture_id']);
        $this->post('/employees/delete/1');

        $deleted = $this->Pictures->find()->where(['id' => $pic['id']])->toArray();
        $this->assertEmpty($deleted);
    }

    //  Demande confirmation de suppression
    public function testEmployeesSuppression25()
    {

        $this->get('my-enterprise/employees');
        //Le onclick devrait afficher une modale
        $this->assertResponseContains('onclick="ModalCatDelete(');
    }

    //  Liste les employés qui seront supprimés
    public function testEmployeesSuppression26()
    {
        //Selon les fixtures, l'employé 1 et 2 sont à la catégorie 1
        //Les nom/prénoms sont identiques
        $this->get('/my-enterprise/employeCategories/listEmployees/1');
        $this->assertResponseContains('{"id":1,"firstname":"Lorem ipsum dolor","lastname":"Lorem ipsum dolor"},{"id":2,"firstname":"Lorem ipsum dolor","lastname":"Lorem ipsum dolor"}');
    }

    //Demande une seconde confirmation
    public function testEmployeesSuppression27()
    {
        $this->get('/my-enterprise/employees');
        $this->assertResponseContains('confirm(&quot;Do you really want to delete this category and all its employees');
    }

    //  Supprime dans EMPLOYEES les associations employe_category_id
    public function testEmployeesSuppression28()
    {
        //Selon les fixtures, l'employé 1 et 2 sont à la catégorie 1
        $expectedEmpCount = $this->getEmpCount() - 2;
        $expectedCatCount = $this->getCatEmpCount() - 1;

        $this->post('/employeCategories/delete/1');

        $deletedEmpCategory = $this->EmployeCategories->find()->where(['id' => 1])->toArray();

        $this->assertEquals($expectedEmpCount, $this->getEmpCount());
        $this->assertEquals($expectedCatCount, $this->getCatEmpCount());
        $this->assertEmpty($deletedEmpCategory);
    }

    //  Supprime dans PICTURES les assocications picture_id pour chaque employés
    public function testEmployeesSuppression29()
    {
        $emp = $this->Employees->find()->where(['employe_category_id' => 1])->toArray();
        $deletedPic = array_map(function ($e) {
            return $e->picture_id;
        }, $emp);

        //supprimer cat 1
        $this->post('/employeCategories/delete/1');
        $deleted = $this->Pictures->find('all')
            ->where(['id IN' => $deletedPic])->toArray();

        $this->assertEmpty($deleted);
    }

    //La liste doit être restreinte parmis la liste des catégories de l'entreprise
    public function testEmployeesSuppression33()
    {
        $expected = $this->EmployeCategories->find()->where(['enterprise_id' => $this->Auth['User']['enterprise']['id']])->toArray();
        $foreign = $this->EmployeCategories->find()->where(['enterprise_id' => 2])->first();

        $this->get('/my-enterprise/employees');
        $list = $this->viewVariable('categories');

        $this->assertNotContains($foreign, $list);
    }

    //  des messages de validation sont affichés à l'usager
    public function testEmployeesSuppression34()
    {
        $this->post('/employees/delete/1');
        $this->assertContains('has been deleted', $_SESSION['Flash']['flash'][0]['message']);

        $this->post('/employe-categories/delete/1');
        $this->assertContains('has been deleted', $_SESSION['Flash']['flash'][0]['message']);
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