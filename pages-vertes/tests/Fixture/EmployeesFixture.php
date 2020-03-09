<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EmployeesFixture
 *
 */
class EmployeesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'firstname' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'lastname' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'birthday' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'hire_date' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'address' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'employe_category_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'picture_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'BY_EMPLOYE_CATEGORY_ID' => ['type' => 'index', 'columns' => ['employe_category_id'], 'length' => []],
            'BY_PICTURE_ID' => ['type' => 'index', 'columns' => ['picture_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'employees_ibfk_1' => ['type' => 'foreign', 'columns' => ['employe_category_id'], 'references' => ['employe_categories', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'employees_ibfk_2' => ['type' => 'foreign', 'columns' => ['picture_id'], 'references' => ['pictures', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'firstname' => 'Lorem ipsum dolor',
            'lastname' => 'Lorem ipsum dolor',
            'birthday' => '2018-02-14',
            'hire_date' => '2018-02-14',
            'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat.',
            'employe_category_id' => 1,
            'picture_id' => 7,

            'created' => '2018-02-14 15:03:57',
            'modified' => '2018-02-14 15:03:57'
        ],

        [
            'id' => 2,
            'firstname' => 'Lorem ipsum dolor',
            'lastname' => 'Lorem ipsum dolor',
            'birthday' => '2018-02-14',
            'hire_date' => '2018-02-14',
            'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat.',
            'employe_category_id' => 1,
            'picture_id' => 8,

            'created' => '2018-02-14 15:03:57',
            'modified' => '2018-02-14 15:03:57'
        ],

        [
            'id' => 3,
            'firstname' => 'Lorem ipsum dolor',
            'lastname' => 'Lorem ipsum dolor',
            'birthday' => '2018-02-14',
            'hire_date' => '2018-02-14',
            'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat.',
            'employe_category_id' => 3,
            'picture_id' => 9,

            'created' => '2018-02-14 15:03:57',
            'modified' => '2018-02-14 15:03:57'
        ],
    ];

    public function init()
    {
        parent::init();

        for($i=3;$i<43;$i++){
            array_push($this->records,  [
                'id' => $i + 1,
                'firstname' => 'Lorem ipsum dolor',
                'lastname' => 'Lorem ipsum dolor',
                'birthday' => '2018-02-14',
                'hire_date' => '2018-02-14',
                'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat.',
                'employe_category_id' => ($i % 3)+3,
                'picture_id' => 9,

                'created' => '2018-02-14 15:03:57',
                'modified' => '2018-02-14 15:03:57'
            ]);
        }
    }
}
