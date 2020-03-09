<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EmployeCategoriesFixture
 *
 */
class EmployeCategoriesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 200, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'enterprise_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'BY_ENTERPRISE_ID' => ['type' => 'index', 'columns' => ['enterprise_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'employe_categories_ibfk_1' => ['type' => 'foreign', 'columns' => ['enterprise_id'], 'references' => ['enterprises', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,

            'created' => '2018-02-18 15:18:56',
            'modified' => '2018-02-18 15:18:56'
        ],

        [
            'id' => 2,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,

            'created' => '2018-02-26 15:18:56',
            'modified' => '2018-02-26 15:18:56'
        ],

        [
            'id' => 3,
            'name' => 'Bonjour',
            'enterprise_id' => 2,

            'created' => '2018-02-26 15:18:56',
            'modified' => '2018-02-26 15:18:56'
        ],

        [
            'id' => 4,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,

            'created' => '2018-02-18 15:18:56',
            'modified' => '2018-02-18 15:18:56'
        ],

        [
            'id' => 5,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,

            'created' => '2018-02-26 15:18:56',
            'modified' => '2018-02-26 15:18:56'
        ],

        [
            'id' => 6,
            'name' => 'Bonjour',
            'enterprise_id' => 2,

            'created' => '2018-02-26 15:18:56',
            'modified' => '2018-02-26 15:18:56'
        ],
    ];

    public function init()
    {
        parent::init();

        for($i=6;$i<43;$i++){
            array_push($this->records,  [
                'id' => $i + 1,
                'name' => 'Bonjour' . ($i + 1),
                'enterprise_id' => 2,

                'created' => '2018-02-26 15:18:56',
                'modified' => '2018-02-26 15:18:56'
            ]);
        }
    }
}
