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
class ModificationEmployesTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.employees',
        'app.employe_categories',
        'app.products',
        'app.product_categories',
        'app.enterprises',
        'app.users',
        'app.pictures',
        'app.services',
        'app.service_categories',
        'app.suppliers',
        'app.supplier_categories'
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
    public function testEmployeesModification9()
    {
        $this->setAuth();
        $data = $this->buildData(true);
        $expectedCount = $this->getEmpCount();

        $this->post('/my-enterprise/employees/edit/1', $data);
        $toModify = $this->Employees->find()->first();

        $result = $this->getEmpCount();


        $this->assertEquals(1, $toModify['id']);
        $this->assertEquals($expectedCount, $result);
        $this->assertEquals($data['firstname'], $toModify['firstname']);
        $this->assertEquals($data['lastname'], $toModify['lastname']);
        $this->assertEquals($data['address'], $toModify['address']);

        $birthdayTime = new Time ($data['birthday']['year'] . '/' . $data['birthday']['month'] . '/' . $data['birthday']['day']);
        $hite_dateTime = new Time ($data['hire_date']['year'] . '/' . $data['hire_date']['month'] . '/' . $data['hire_date']['day']);

        $this->assertEquals($birthdayTime, $toModify['birthday']);

        $this->assertEquals($hite_dateTime, $toModify['hire_date']);

        $encodedPic = base64_encode(file_get_contents($data['picture']['tmp_name']));
        $encodedPic = "data:" . $data['picture']['type'] . ";base64," . $encodedPic;

//        $this->assertEquals($encodedPic, $this->Pictures->get(['id' => 1])->base64image);
        $this->assertEquals($data['employe_category_id'], $toModify['employe_category_id']);
    }

    //  Tous les attributs ont une valeur dans EMPLOYEES
    public function testEmployeesModification10()
    {
        $this->setAuth();
        $data = $this->buildData(true);

        $this->post('/my-enterprise/employees/edit/1', $data);
        $modified = $this->Employees->get(['id' => 1]);

        $this->assertNotNull($modified->firstname);
        $this->assertNotNull($modified->lastname);
        $this->assertNotNull($modified->address);
        $this->assertNotNull($modified->birthday);
        $this->assertNotNull($modified->hire_date);
        $this->assertNotNull($modified->picture_id);
        $this->assertNotNull($modified->employe_category_id);
        $this->assertNotNull($modified->created);

        $this->assertNotEmpty($modified->firstname);
        $this->assertNotEmpty($modified->lastname);
        $this->assertNotEmpty($modified->address);
        $this->assertNotEmpty($modified->birthday);
        $this->assertNotEmpty($modified->hire_date);
        $this->assertNotEmpty($modified->employe_category_id);
        $this->assertNotEmpty($modified->picture_id);
        $this->assertNotEmpty($modified->created);

    }

    //  Fonctionnel dans EMPLOYE_CATEGORIES
    public function testEmployeesModification11()
    {
        $this->setAuth();
        $expected = $this->Employees->EmployeCategories->find()->count();
        $data = [
            'name' => 'Modified category'
        ];
        $this->post('/my-enterprise/employeCategories/edit/1', $data);

        $cat = $this->Employees->EmployeCategories->find()->first();
        $result = $this->Employees->EmployeCategories->find()->count();
        $this->assertEquals($expected, $result);
        $this->assertEquals($data['name'], $cat->name);
    }

    //  Tous les attributs ont une valeurs dans EMPLOYE_CATEGORIES
    public function testEmployeesModification12()
    {
        $this->setAuth();
        $data = [
            'name' => 'Modified category'
        ];
        $this->post('/my-enterprise/employeCategories/edit/1', $data);

        $cat = $this->Employees->EmployeCategories->find()->first();

        $this->assertNotNull($cat->id);
        $this->assertNotNull($cat->name);
        $this->assertNotNull($cat->created);
    }

    /*
     * ======================================
     * Tests par négations:
     * Sauf exception, il n'y aura PAS d'ajout à la fin de chacun de ces tests
     * ======================================
     */

    //  unicité de la clé primaire id (n'est pas modifiable)
    public function testEmployeesModification16()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['id'] = 200;
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);


        $modifiedEmployee = $this->Employees->find('all')->first();

        $this->assertEquals($employee->id, $modifiedEmployee->id);
        $this->assertEmpty($this->Employees->find()->where(['id' => 200])->toArray());
    }

    //  existence de la clé étrangère EMPLOYE_CATEGORY_ID
    public function testEmployeesModification17()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['employe_category_id'] = 50;

        $this->post('/my-enterprise/employees/edit/1', $data);

        $this->assertResponseContains('does not exist');
    }

    //  Cohérence de la clé étrangère EMPLOYE_CATEGORY_ID et de l'usager connecté
    public function testEmployeesModification18()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['employe_category_id'] = 3;

        $this->post('/my-enterprise/employees/edit/1', $data);

        $this->assertResponseContains('category does not exist');
    }

    //  existence de la clé étrangère PICTURE_ID  (n'est pas modifiable)
    public function testEmployeesModification19()
    {
        $this->setAuth();
        $data = $this->buildData(true);
        $data['picture']['picture_id'] = 500;//est-ce que ça va PETEEER ?

        $employee = $this->Employees->find('all')->first();
        $this->post('/my-enterprise/employees/edit/1', $data);

        $this->assertTrue($this->Pictures->exists(['id' => $employee["picture_id"]]));
        $this->assertEquals($employee->picture_id, $this->Employees->find('all')->first()->picture_id);
    }

    //  FIRSTNAME !=Null
    public function testEmployeesModification20()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['firstname'] = null;
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);

        //Nom inchangé
        $this->assertEquals($employee['firstname'], $this->Employees->find('all')->first()['firstname']);
        $this->assertResponseContains('employee could not be saved.');
    }

    //  FIRSTNAME  > 5 caractères
    public function testEmployeesModification21()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['firstname'] = 'Lorem';
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);

        //Nom inchangé
        $this->assertEquals($employee['firstname'], $this->Employees->find('all')->first()['firstname']);
        $this->assertResponseContains('employee could not be saved.');
    }

    //  FIRSTNAME  < 50 caractères
    public function testEmployeesModification22()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['firstname'] = 'Lorem Ipsum is simply dummy text of the printing a';
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);

        //Nom inchangé
        $this->assertEquals($employee['firstname'], $this->Employees->find('all')->first()['firstname']);
        $this->assertResponseContains('employee could not be saved.');
    }

    //  FIRSTNAME  ne contient pas de caractère html
    public function testEmployeesModification23()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['firstname'] = '<p>' . 'allo' . '</p>';
        $h_firstname = h($data['firstname']);

        $this->post('/my-enterprise/employees/edit/1', $data);

        $employee = $this->Employees->find('all')->first();

        $this->assertFalse(strpos($employee->firstname, '<p>'));
        $this->assertEquals($h_firstname, $employee->firstname);
    }

    //  LASTNAME !=Null
    public function testEmployeesModification24()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['lastname'] = null;
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);

        //Prénom inchangé
        $this->assertEquals($employee['lastname'], $this->Employees->find('all')->first()['lastname']);
        $this->assertResponseContains('employee could not be saved.');
    }

    //  LASTNAME  > 5 caractères
    public function testEmployeesModification25()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['lastname'] = 'Lorem';
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);

        //Prénom inchangé
        $this->assertEquals($employee['lastname'], $this->Employees->find('all')->first()['lastname']);
        $this->assertResponseContains('employee could not be saved.');
    }

    //  LASTNAME  < 50 caractères
    public function testEmployeesModification26()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['lastname'] = 'Lorem Ipsum is simply dummy text of the printing a';
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);

        //Prénom inchangé
        $this->assertEquals($employee['lastname'], $this->Employees->find('all')->first()['lastname']);
        $this->assertResponseContains('employee could not be saved.');
    }

    //  LASTNAME  ne contient pas de caractère html
    public function testEmployeesModification27()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['lastname'] = '<p>' . 'allo' . '</p>';
        $h_lastname = h($data['lastname']);

        $this->post('/my-enterprise/employees/edit/1', $data);

        $employee = $this->Employees->find('all')->first();

        $this->assertFalse(strpos($employee->lastname, '<p>'));
        $this->assertEquals($h_lastname, $employee->lastname);
    }

    //  BIRTHDAY != Null
    public function testEmployeesModification28()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['birthday'] = null;
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);

        $modifiedEmployee = $this->Employees->find('all')->first();
        $this->assertEquals($employee->birthday, $modifiedEmployee->birthday);
    }

    //  BIRTHDAY < Aujourd'hui
    public function testEmployeesModification29()
    {
        $this->setAuth();
        $data = $this->buildData();
        $employee = $this->Employees->find('all')->first();
        $birthdayTime = new Time ($data['birthday']['year'] . '/' . $data['birthday']['month'] . '/' . $data['birthday']['day']);

        //Ne fonctionne pas avec la date d'aujourd'hui
        $now = Time::now();
        $data['birthday'] = [
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day,
        ];

        $this->post('/my-enterprise/employees/edit/1', $data);
        $modifiedEmployee = $this->Employees->find('all')->first();
        $this->assertEquals($employee->birthday, $modifiedEmployee->birthday);

        //Ne fonctionne pas avec une date future
        $now = Time::now()->modify('+666 days');
        $data['birthday'] = [
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day,
        ];

        $this->post('/my-enterprise/employees/edit/1', $data);
        $modifiedEmployee = $this->Employees->find('all')->first();
        $this->assertEquals($employee->birthday, $modifiedEmployee->birthday);

        //Fonctionne avec une date du passé
        $now = Time::now()->modify('-333 days');
        $data['birthday'] = [
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day,
        ];

        $this->post('/my-enterprise/employees/edit/1', $data);
        $modifiedEmployee = $this->Employees->find('all')->first();
        $this->assertEquals($now->year, $modifiedEmployee->birthday->year);
        $this->assertEquals($now->month, $modifiedEmployee->birthday->month);
        $this->assertEquals($now->day, $modifiedEmployee->birthday->day);
    }

    //  HIRE_DATE != Null
    public function testEmployeesModification30()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['hire_date'] = null;
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);

        $modifiedEmployee = $this->Employees->find('all')->first();
        $this->assertEquals($employee->hire_date, $modifiedEmployee->hire_date);
    }

    // NOTE J'ai repris le critère de l'ajout car je soupçonne qu'une coquille s'est glissée dans les critères de la modification
    //  HIRE-DATE <= Aujourd'hui + 2 semaines
    public function testEmployeesModification32()
    {
        $this->setAuth();
        $data = $this->buildData();
        $employee = $this->Employees->find('all')->first();

        $now = Time::now()->addDays(15);
        $data['hire_date'] = [
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day,
        ];

        $this->post('/my-enterprise/employees/edit/1', $data);
        $modifiedEmployee = $this->Employees->find('all')->first();
        $this->assertEquals($employee->hire_date, $modifiedEmployee->hire_date);

        $now = Time::now()->addDays(14);
        $data['hire_date'] = [
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day,
        ];

        $this->post('/my-enterprise/employees/edit/1', $data);
        $modifiedEmployee = $this->Employees->find('all')->first();
        $this->assertEquals($now->year, $modifiedEmployee->hire_date->year);
        $this->assertEquals($now->month, $modifiedEmployee->hire_date->month);
        $this->assertEquals($now->day, $modifiedEmployee->hire_date->day);
    }

    //  ADDRESS !=Null
    public function testEmployeesModification33()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['address'] = null;
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);

        //Adresse inchangée
        $this->assertEquals($employee['firstname'], $this->Employees->find('all')->first()['firstname']);
        $this->assertResponseContains('employee could not be saved.');
    }

    //  ADDRESS  > 5 caractères
    public function testEmployeesModification34()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['address'] = 'Lorem';
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);

        //Adresse inchangée
        $this->assertEquals($employee['firstname'], $this->Employees->find('all')->first()['firstname']);
        $this->assertResponseContains('employee could not be saved.');
    }

    //  ADDRESS  < 100 caractères
    public function testEmployeesModification35()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['address'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae lectus sed felis efficitur inta';
        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);

        //Adresse inchangée
        $this->assertEquals($employee['firstname'], $this->Employees->find('all')->first()['firstname']);
        $this->assertResponseContains('employee could not be saved.');
    }

    //  ADDRESS  ne contient pas de caractère html
    public function testEmployeesModification36()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['address'] = '<p>' . 'allo' . '</p>';
        $h_address = h($data['address']);

        $this->post('/my-enterprise/employees/edit/1', $data);

        $employee = $this->Employees->find('all')->first();

        $this->assertFalse(strpos($employee->address, '<p>'));
        $this->assertEquals($h_address, $employee->address);
    }

    /*
     * ======================================
     * PICTURES
     * ======================================
     */

    //  unicité de la clé primaire id (n'est pas modifiable)
    public function testEmployeesModification39()
    {
        $this->setAuth();
        $data = $this->buildData(true);
        $data['picture']['picture_id'] = 200;

        $employee = $this->Employees->find('all')->first();

        $this->post('/my-enterprise/employees/edit/1', $data);


        $this->assertEquals($employee->picture_id, $this->Employees->find('all')->first()->picture_id);
    }

    //  Cohérence de la clef étrangère picture_id et l'usager connecté (n'est pas modifiable)
    public function testEmployeesModification40()
    {
        //Même test que le 41, vu que l'usager connecté n'influt pas sur l'enregistrement ou non de l'image
        $this->setAuth();
        $data = $this->buildData(true);
        $data['picture_id'] = 2;
        $employee = $this->Employees->get(1, ['contain' => 'Pictures']);
        $this->post('/my-enterprise/employees/edit/1', $data);

        $modifiedEmployee = $this->Employees->get(1, ['contain' => 'Pictures']);

        $this->assertEquals($employee->picture->id, $modifiedEmployee->picture->id);
    }

    // Cohérence de la clef étrangère picture_id et le parent(employees, products...)(n'est pas modifiable)
    public function testEmployeesModification41()
    {
        $this->setAuth();
        $data = $this->buildData(true);
        $data['picture_id'] = 2;
        $employee = $this->Employees->get(1, ['contain' => 'Pictures']);
        $this->post('/my-enterprise/employees/edit/1', $data);

        $modifiedEmployee = $this->Employees->get(1, ['contain' => 'Pictures']);

        $this->assertEquals($employee->picture->id, $modifiedEmployee->picture->id);
    }

    //  Vérification que base64image est bien formatée: Début par "data:image/png;base64,"
    public function testEmployeesModification42()
    {
        $this->setAuth();
        $data = $this->buildData(true);
        $employee = $this->Employees->get(1, ['contain' => 'Pictures']);

        $this->post('/my-enterprise/employees/edit/1', $data);
        $modifiedEmployee = $this->Employees->get(1, ['contain' => 'Pictures']);

        $pos = strpos($modifiedEmployee->picture->base64image, "data:image/png;base64,") === 0;
        $this->assertTrue($pos);
        $this->assertNotEquals($employee->picture, $modifiedEmployee->picture);
    }

    //  Vérification que base64image est bien formatée: Fin par l'image encodé en base64
    public function testEmployeesModification43()
    {
        $this->setAuth();
        $data = $this->buildData(true);

        $this->post('/my-enterprise/employees/edit/1', $data);

        $base64 = base64_encode(file_get_contents($data['picture']['tmp_name']));
        $pos = strpos($this->Pictures->find()->first()->base64image, $base64) !== false;
//        $this->assertTrue($pos);
    }

    //  Vérification que base64image est non null
    public function testEmployeesModification44()
    {
        $this->setAuth();
        $data = $this->buildData(true);
        $data['picture']['tmp_name'] = null;
        $employee = $this->Employees->get(1, ['contain' => 'Pictures']);

        $this->post('/my-enterprise/employees/edit/1', $data);

        $modifiedEmployee = $this->Employees->get(1, ['contain' => 'Pictures']);

        $this->assertEquals($employee->picture->base64image, $this->Pictures->find()->first()->base64image);
        $this->assertNotNull($this->Employees->get(1, ['contain' => 'Pictures'])->picture->base64image);
    }
    /*
     * ======================================
     * EMPLOYE CATEGORIES
     * ======================================
     */

    //  unicité de la clé primaire id (n'est pas modifiable)
    public function testEmployeesModification47()
    {
        $this->setAuth();
        $data = [
            'id' => 200,
            'name' => 'Category name'
        ];

        $cat = $this->EmployeCategories->find('all')->first();

        $this->post('/my-enterprise/employeCategories/edit/1', $data);


        $modifiedCat = $this->EmployeCategories->find('all')->first();

        $this->assertEquals($cat->id, $modifiedCat->id);
        $this->assertEmpty($this->EmployeCategories->find()->where(['id' => 200])->toArray());

    }

    //  existence de la clé étrangère ENTERPRISE_ID  (n'est pas modifiable)
    public function testEmployeesModification48()
    {
        $this->setAuth();
        $data = [
            'name' => 'Category name',
            'enterprise_id' => 50
        ];

        $cat = $this->EmployeCategories->find('all')->first();

        $this->post('/my-enterprise/employeCategories/edit/1', $data);
        $modifiedCat = $this->EmployeCategories->find('all')->first();

        $this->assertEquals($cat->enterprise_id, $modifiedCat->enterprise_id);
        $this->assertNotEquals(50, $modifiedCat->enterprise_id);
    }

//      Cohérence de la clef étrangère ENTERPRISE_ID et l'usager connecté (ce n'est pas demandé)
//    public function testEmployeesModification48BIS()
//    {
//        $this->setAuth();
//        $data = [
//            'name' => 'Category name',
//        ];
//        $enterprise_id = $this->Auth['User']['enterprise']['id'];
//        $cat = $this->EmployeCategories->find('all')->first();
//
//        $this->post('/my-enterprise/employeCategories/edit/1', $data);
//        $modifiedCat = $this->EmployeCategories->find('all')->first();
//
//        $this->assertEquals($cat->enterprise_id, $modifiedCat->enterprise_id);
//        $this->assertEquals($enterprise_id, $modifiedCat->enterprise_id);
//    }

    //  NAME !=Null
    public function testEmployeesModification49()
    {
        $this->setAuth();
        $data['name'] = null;
        $cat = $this->EmployeCategories->find('all')->first();

        $this->post('/my-enterprise/employeCategories/edit/1', $data);

        //Nom inchangé
        $this->assertEquals($cat['name'], $this->EmployeCategories->find('all')->first()['name']);
    }

    //  NAME  > 5 caractères
    public function testEmployeesModification50()
    {
        $this->setAuth();
        $data['name'] = 'Lorem';
        $cat = $this->EmployeCategories->find('all')->first();

        $this->post('/my-enterprise/employeCategories/edit/1', $data);

        //Nom inchangé
        $this->assertEquals($cat['name'], $this->EmployeCategories->find('all')->first()['name']);
    }

    //  NAME  < 50 caractères
    public function testEmployeesModification51()
    {
        $this->setAuth();
        $data['name'] = 'Lorem Ipsum is simply dummy text of the printing a';
        $cat = $this->EmployeCategories->find('all')->first();

        $this->post('/my-enterprise/employeCategories/edit/1', $data);

        //Nom inchangé
        $this->assertEquals($cat['name'], $this->EmployeCategories->find('all')->first()['name']);
    }

    //  NAME  ne contient pas de caractère html
    public function testEmployeesModification52()
    {
        $this->setAuth();
        $data['name'] = '<p>' . 'allo' . '</p>';
        $h_name = h($data['name']);

        $this->post('/my-enterprise/employeCategories/edit/1', $data);

        $cat = $this->EmployeCategories->find('all')->first();

        $this->assertFalse(strpos($cat->name, '<p>'));
        $this->assertEquals($h_name, $cat->name);
    }

    /*
     * ======================================
     * Saisies et messages
     * ======================================
     */

    //  on ne peut saisir les attributs d'information de l'employé
    public function testMessagesModification55()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['firstname'] = null;

        $this->post('/my-enterprise/employees/edit/1', $data);
        $this->assertResponseContains('cannot be empty');

        $forcedId = 200;
        $data = $this->buildData();
        $data['id'] = $forcedId;

        $this->post('/my-enterprise/employees/edit/1', $data);

        $this->assertNotEquals($forcedId, $this->Employees->find('all')->first()->id);
    }

    //  on ne peut saisir l'attribut BIRTHDAY dans un calendrier
    public function testMessagesModification56()
    {
        $this->setAuth();
        $data = $this->buildData();
        $data['birthday'] = [
            'year' => '2017',
            'month' => '02',
            'day' => '31',
        ];

        $this->post('/my-enterprise/employees/edit/1', $data);
        $this->assertResponseContains('invalid');
    }

    //  on ne peut saisir l'attribut EMPLOYE_CATEGORY_ID par une liste
    public function testMessagesModification57()
    {
        $this->setAuth();
        $data = $this->buildData();
        $allCategories = TableRegistry::get('EmployeCategories');
        $catEnt1 = $allCategories->find('all')->where(['enterprise_id' => 1]);
        $catEnt2 = $allCategories->get(['enterprise_id' => 2]);

        $this->get('/my-enterprise/employees/edit/1');

        $this->assertNotEquals($catEnt1, $catEnt2);
        $this->assertEquals($catEnt1->count(), $this->viewVariable('categories')->count());
    }

    //  on ne peut saisir une image de profil
    // /!\  NOTE : en fait, on peut ne pas mettre d'image au moment de l'ÉDITION, le système va juste garder
    //      la dernière image stockée
    public function testMessagesModification59()
    {
        echo PHP_EOL;
        echo '>>Coquille dans les critères ou erreur de compréhension (testMessagesModification59), voir note<<';
        echo PHP_EOL;
        $this->markTestSkipped(
            'Coquille dans les critères ou erreur de compréhension (testMessagesModification59)'
        );
        $this->setAuth();
        $data = $this->buildData(true);
        $data['picture']['tmp_name'] = null;
        $this->post('/my-enterprise/employees/edit/1', $data);
        $this->assertResponseContains('Please select an image.');
    }

    //  des messages de validation sont affichés à l'usager
    public function testMessagesModification60()
    {
        $this->setAuth();
        $data = $this->buildData();

        $this->post('/my-enterprise/employees/edit/1', $data);

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
    function buildData($withPicture = false)
    {
        if ($withPicture) {
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
                'employe_category_id' => 1,
                'picture' => [
                    'tmp_name' => 'webroot/img/cake.icon.png',
                    'type' => 'image/png'
                ],
            ];

        }

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
            'employe_category_id' => 1,
            'picture_id' => 1,
            'picture' => [
                'tmp_name' => "",
                'type' => ""
            ],
        ];
    }
}