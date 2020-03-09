<?php
/**
 * Created by PhpStorm.
 * User: Maxime
 * Date: 2018-02-25
 * Time: 21:31
 */


use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * App\Controller\ProductsController Test Case
 */
class AjoutEmployesTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.employees',
        'app.employe_categories',
//        'app.products',
//        'app.product_categories',
        'app.enterprises',
        'app.users',
        'app.pictures'
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
    }

    /*
     * ======================================
     * Tests positif:
     * Il y aura un ajout dans la base de donnée après chacun de ces tests
     * ======================================
     */

    //  Fonctionnel dans EMPLOYEES
    public function testEmployeesAjout9()
    {
        $this->setAuth();
        $data = $this->buildData();

        $expected = $this->getEmpCount() + 1;

        $this->post('/my-enterprise/employees/add', $data);

        $result = $this->getEmpCount();
        $this->assertEquals($expected, $result);
    }

    //  Tous les attributs ont une valeur dans EMPLOYEES
    public function testEmployeesAjout10()
    {
        $this->setAuth();
        $data = $this->buildData();

        $this->post('/my-enterprise/employees/add', $data);

        //Le controller pourrait éventuellement retourner l'id pour qu'on soit sûr d'utiliser
        //le bon enregistrement
        $employee = $this->Employees->find('all')->last();

        $this->assertNotNull($employee->firstname);
        $this->assertNotNull($employee->lastname);
        $this->assertNotNull($employee->address);
        $this->assertNotNull($employee->birthday);
        $this->assertNotNull($employee->hire_date);
        $this->assertNotNull($employee->picture_id);
        $this->assertNotNull($employee->employe_category_id);
        $this->assertNotNull($employee->created);

        $this->assertNotEmpty($employee->firstname);
        $this->assertNotEmpty($employee->lastname);
        $this->assertNotEmpty($employee->address);
        $this->assertNotEmpty($employee->birthday);
        $this->assertNotEmpty($employee->hire_date);
        $this->assertNotEmpty($employee->employe_category_id);
        $this->assertNotEmpty($employee->picture_id);
        $this->assertNotEmpty($employee->created);

    }

    //  Fonctionnel dans EMPLOYE_CATEGORIES
    public function testEmployeesAjout11()
    {
        $this->setAuth();
        $expected = $this->Employees->EmployeCategories->find()->count() + 1;
        $data = [
            'name' => 'Category name'
        ];
        $this->post('/my-enterprise/employeCategories/add', $data);
        $result = $this->Employees->EmployeCategories->find()->count();
        $this->assertEquals($expected, $result);
    }

    //  Tous les attributs ont une valeurs dans PICTURES
    public function testEmployeesAjout12()
    {
        $this->setAuth();
        $data = $this->buildData();
        $this->post('/my-enterprise/employees/add', $data);

        $employee = $this->Employees->find('all')->last();
        $picture = $this->Pictures->get(['id' => $employee->picture_id]);

        $this->assertNotNull($picture->id);
        $this->assertNotNull($picture->base64image);
        $this->assertNotNull($picture->created);
    }

    /*
     * ======================================
     * Tests par négations:
     * Sauf exception, il n'y aura PAS d'ajout à la fin de chacun de ces tests
     * ======================================
     */

    //  Unicité de la clé primaire id (générée)
    public function testEmployeesAjout16()
    {
        $this->setAuth();
        $data = $this->buildData();
        $actualLastId = $this->Employees->find('all')->last()->id;

        //AJOUT d'un employé (déjà testé)
        $this->post('/my-enterprise/employees/add', $data);

        $LastId = $this->Employees->find('all')->last()->id;

        $this->assertGreaterThan($actualLastId, $LastId);
    }

    //  Existance de la clé étrangère EMPLOYE_CATEGORY_ID
    public function testEmployeesAjout17()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['employe_category_id'] = 50;
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  Cohérence de la clé étrangère EMPLOYE_CATEGORY_ID et de l'usager connecté
    public function testEmployeesAjout18()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['employe_category_id'] = 3;
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
        $this->assertResponseContains('category does not exist');
    }

    //  Existance de la clé étrangère PICTURE_ID
    public function testEmployeesAjout19()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['picture'] = null;
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  FIRSTNAME !=Null
    public function testEmployeesAjout20()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['firstname'] = null;
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  FIRSTNAME  > 5 caractères
    public function testEmployeesAjout21()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['firstname'] = 'Lorem';
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  FIRSTNAME  < 50 caractères
    public function testEmployeesAjout22()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['firstname'] = 'Lorem Ipsum is simply dummy text of the printing a';
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  FIRSTNAME  ne contient pas de caractère html
    public function testEmployeesAjout23()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['firstname'] = '<p>' . 'allo' . '</p>';
        $h_firstname = h($data['firstname']);

        $expectedCount = $this->getEmpCount() + 1;
        $this->post('/my-enterprise/employees/add', $data);

        $employee = $this->Employees->find('all')->last();

        $this->assertEquals($expectedCount, $this->getEmpCount());
        $this->assertFalse(strpos($employee->firstname, '<p>'));
        $this->assertEquals($h_firstname, $employee->firstname);
    }

    //  LASTNAME !=Null
    public function testEmployeesAjout24()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['lastname'] = null;
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  LASTNAME  > 5 caractères
    public function testEmployeesAjout25()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['lastname'] = 'Lorem';
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  LASTNAME  < 50 caractères
    public function testEmployeesAjout26()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['lastname'] = 'Lorem Ipsum is simply dummy text of the printing a';
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  LASTNAME  ne contient pas de caractère html
    public function testEmployeesAjout27()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['lastname'] = '<p>' . 'allo' . '</p>';
        $h_lastname = h($data['lastname']);

        $expectedCount = $this->getEmpCount() + 1;
        $this->post('/my-enterprise/employees/add', $data);

        $employee = $this->Employees->find('all')->last();

        $this->assertEquals($expectedCount, $this->getEmpCount());
        $this->assertFalse(strpos($employee->lastname, '<p>'));
        $this->assertEquals($h_lastname, $employee->lastname);
    }

    //  BIRTHDAY != Null
    public function testEmployeesAjout28()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['birthday'] = null;
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  BIRTHDAY < Aujourd'hui
    public function testEmployeesAjout29()
    {
        $this->setAuth();
        $data = $this->buildData();

        //Ne fonctionne pas avec la date d'aujourd'hui
        $now = Time::now();
        $data['birthday'] = [
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day,
        ];

        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);
        $this->assertEquals($expectedCount, $this->getEmpCount());

        //Ne fonctionne pas avec une date future
        $now = Time::now()->modify('+666 days');
        $data['birthday'] = [
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day,
        ];

        $this->post('/my-enterprise/employees/add', $data);
        $this->assertEquals($expectedCount, $this->getEmpCount());

        $expectedCount = $this->getEmpCount() + 1;

        //Fonctionne avec une date du passé
        $now = Time::now()->modify('-333 days');
        $data['birthday'] = [
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day,
        ];

        $this->post('/my-enterprise/employees/add', $data);
        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  HIRE_DATE != Null
    public function testEmployeesAjout30()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['hire_date'] = null;
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  HIRE-DATE <= Aujourd'hui + 2 semaines
    public function testEmployeesAjout32()
    {
        $this->setAuth();
        $data = $this->buildData();

        $now = Time::now()->addDays(15);
        $data['hire_date'] = [
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day,
        ];
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());

        $now = Time::now()->addDays(14);
        $data['hire_date'] = [
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day,
        ];
        $expectedCount = $this->getEmpCount() + 1;

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  ADDRESS !=Null
    public function testEmployeesAjout33()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['address'] = null;
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  ADDRESS  > 5 caractères
    public function testEmployeesAjout34()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['address'] = 'Lorem';
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  ADDRESS  < 100 caractères
    public function testEmployeesAjout35()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['address'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae lectus sed felis efficitur inta';
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
    }

    //  ADDRESS  ne contient pas de caractère html
    public function testEmployeesAjout36()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['address'] = '<p>' . 'allo' . '</p>';
        $h_address = h($data['address']);

        $expectedCount = $this->getEmpCount() + 1;
        $this->post('/my-enterprise/employees/add', $data);

        $employee = $this->Employees->find('all')->last();

        $this->assertEquals($expectedCount, $this->getEmpCount());
        $this->assertFalse(strpos($employee->address, '<p>'));
        $this->assertEquals($h_address, $employee->address);
    }

    /*
     * ======================================
     * PICTURES
     * ======================================
     */

    //  Unicité de la clé primaire id (générée)
    public function testEmployeesAjout39()
    {
        $this->setAuth();
        $data = $this->buildData();

        $actualLastId = $this->Pictures->find('all')->last()->id;

        $this->post('/my-enterprise/employees/add', $data);

        $LastId = $this->Pictures->find('all')->last()->id;

        $this->assertGreaterThan($actualLastId, $LastId);
    }

    //  Cohérence de la clef étrangère picture_id et l'usager connecté
    public function testEmployeesAjout40()
    {
        //Même test que le 41, vu que l'usager connecté n'influt pas sur l'enregistrement ou non de l'image
        $this->setAuth();
        $data = $this->buildData();
        $this->post('/my-enterprise/employees/add', $data);

        $picture = $this->Pictures->find('all')->last();
        $employee = $this->Employees->find('all')->last();

        $this->assertEquals($employee->picture_id, $picture->id);
    }

    //  Cohérence de la clef étrangère picture_id et le parent(employees, products...)
    public function testEmployeesAjout41()
    {
        $this->setAuth();
        $data = $this->buildData();
        $this->post('/my-enterprise/employees/add', $data);

        $picture = $this->Pictures->find('all')->last();
        $employee = $this->Employees->find('all')->last();

        $this->assertEquals($employee->picture_id, $picture->id);
    }

    //  Vérification que base64image est bien formatée: Début par "data:image/png;base64,"
    public function testEmployeesAjout42()
    {
        $this->setAuth();
        $data = $this->buildData();

        $this->post('/my-enterprise/employees/add', $data);

        $pos = strpos($this->Pictures->find()->last()->base64image, "data:image/png;base64,") === 0;
        $this->assertTrue($pos);
    }

    //  Vérification que base64image est bien formatée: Fin par l'image encodé en base64
    public function testEmployeesAjout43()
    {
        $this->setAuth();
        $data = $this->buildData();

        $this->post('/my-enterprise/employees/add', $data);

        $base64 = base64_encode(file_get_contents($data['picture']['tmp_name']));
        $pos = strpos($this->Pictures->find()->last()->base64image, $base64) !== false;
        $this->assertTrue($pos);
    }

    //  Vérification que base64image est non null
    public function testEmployeesAjout44()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['picture']['tmp_name'] = null;
        $expectedCount = $this->Pictures->find('all')->count();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->Pictures->find('all')->count());
    }

    /*
     * ======================================
     * EMPLOYE CATEGORIES
     * ======================================
     */

    //  Unicité de la clé primaire id (générée)
    public function testEmployeesAjout47()
    {
        $this->setAuth();
        $data = [
            'name' => 'Category name'
        ];

        $actualLastId = $this->EmployeCategories->find('all')->last()->id;

        $this->post('/my-enterprise/employeCategories/add', $data);

        $LastId = $this->EmployeCategories->find('all')->last()->id;

        $this->assertGreaterThan($actualLastId, $LastId);
    }

    //  existence de la clé étrangère ENTERPRISE_ID
    public function testEmployeesAjout48()
    {
        $this->setAuth();
        $data = [
            'name' => 'Category name',
            'enterprise_id' => 50
        ];

        $expectedCount = $this->EmployeCategories->find('all')->count();

        $this->post('/my-enterprise/employeCategories/add', $data);

        $this->assertEquals($expectedCount, $this->EmployeCategories->find('all')->count());
    }

    //  Cohérence de la clef étrangère ENTERPRISE_ID et l'usager connecté
    public function testEmployeesAjout49()
    {
        $this->setAuth();
        $data = [
            'name' => 'Category name',
        ];
        $enterprise_id = $this->Auth['User']['enterprise']['id'];
        $expectedCount = $this->EmployeCategories->find('all')->count() + 1;

        $this->post('/my-enterprise/employeCategories/add', $data);

        $this->assertEquals($expectedCount, $this->EmployeCategories->find('all')->count());
        $this->assertEquals($enterprise_id, $this->EmployeCategories->find('all')->last()->enterprise_id);
    }

    //  NAME !=Null
    public function testEmployeesAjout50()
    {
        $this->setAuth();
        $data['name'] = null;
        $expectedCount = $this->getCatEmpCount();

        $this->post('/my-enterprise/employeCategories/add', $data);

        $this->assertEquals($expectedCount, $this->getCatEmpCount());
    }

    //  NAME  > 5 caractères
    public function testEmployeesAjout51()
    {
        $this->setAuth();
        $data['name'] = 'Lorem';
        $expectedCount = $this->getCatEmpCount();

        $this->post('/my-enterprise/employeCategories/add', $data);

        $this->assertEquals($expectedCount, $this->getCatEmpCount());
    }

    //  NAME  < 50 caractères
    public function testEmployeesAjout52()
    {
        $this->setAuth();
        $data['name'] = 'Lorem Ipsum is simply dummy text of the printing a';
        $expectedCount = $this->getCatEmpCount();

        $this->post('/my-enterprise/employeCategories/add', $data);

        $this->assertEquals($expectedCount, $this->getCatEmpCount());
    }

    //  NAME  ne contient pas de caractère html
    public function testEmployeesAjout53()
    {
        $this->setAuth();
        $data['name'] = '<p>' . 'allo' . '</p>';
        $h_name = h($data['name']);

        $expectedCount = $this->getCatEmpCount() + 1;
        $this->post('/my-enterprise/employeCategories/add', $data);

        $employeCat = $this->EmployeCategories->find('all')->last();


        $this->assertEquals($expectedCount, $this->getCatEmpCount());
        $this->assertFalse(strpos($employeCat->name, '<p>'));
        $this->assertEquals($h_name, $employeCat->name);
    }

    /*
     * ======================================
     * Saisies et messages
     * ======================================
     */

    //  on ne peut saisir les attributs d'information de l'employé
    public function testMessagesAjout56()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['firstname'] = null;

        $this->post('/my-enterprise/employees/add', $data);
        $this->assertResponseContains('cannot be empty');

        $forcedId = 200;
        $data = $this->buildData();
        $data['id'] = $forcedId;
        $expectedCount = $this->getEmpCount() + 1;

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertEquals($expectedCount, $this->getEmpCount());
        $this->assertNotEquals($forcedId, $this->Employees->find('all')->last()->id);

    }

    //  on ne peut saisir l'attribut BIRTHDAY dans un calendrier
    public function testMessagesAjout57()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['birthday'] = [
            'year' => '2017',
            'month' => '02',
            'day' => '31',
        ];

        $this->post('/my-enterprise/employees/add', $data);
        $this->assertResponseContains('invalid');
    }

    //  on ne peut saisir l'attribut EMPLOYE_CATEGORY_ID par une liste
    public function testMessagesAjout58()
    {
        $this->setAuth();
        $data = $this->buildData();
        $allCategories = TableRegistry::get('EmployeCategories');
        $catEnt1 = $allCategories->find('all')->where(['enterprise_id' => 1]);
        $catEnt2 = $allCategories->get(['enterprise_id' => 2]);

        $this->get('/my-enterprise/employees/add');

        $this->assertNotEquals($catEnt1, $catEnt2);
        $this->assertEquals($catEnt1->count(), $this->viewVariable('categories')->count());
    }

    //  on ne peut saisir une image de profil
    public function testMessagesAjout60()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['picture'] = ['tmp_name' => null, 'type' => null];
        $this->post('/my-enterprise/employees/add', $data);
        $this->assertResponseContains('Please select an image.');
    }

    //  des messages de validation sont affichés à l'usager
    public function testMessagesAjout61()
    {
        $this->setAuth();
        $data = $this->buildData();

        $this->post('/my-enterprise/employees/add', $data);

        $this->assertContains($_SESSION['Flash']['flash'][0]['message'], 'The employee has been saved.');
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

    private
    function buildData()
    {
        return [
            'firstname' => 'Emp firstname',
            'lastname' => 'Emp lastname',
            'address' => '8 Allée des Amazonites',
            'birthday' => [
                'year' => '1996',
                'month' => '02',
                'day' => '27',
            ],
            'hire_date' => [
                'year' => '2017',
                'month' => '05',
                'day' => '25',
            ],
//            'hire_date'=> Time::now()->modify('-1 years'),
//            'birthday' => Time::now()->modify('-20 years'),
            'employe_category_id' => 1,
            'picture' => [
                'tmp_name' => 'webroot/img/cake.icon.png',
                'type' => 'image/png'
            ],
        ];
    }
}