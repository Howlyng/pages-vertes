<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ServiceCategoriesFixture
 *
 */
class ServiceCategoriesFixture extends TestFixture
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
            'service_categories_ibfk_1' => ['type' => 'foreign', 'columns' => ['enterprise_id'], 'references' => ['enterprises', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'created' => '2018-02-14 18:53:33',
            'modified' => '2018-02-14 18:53:33'
        ],
        [
            'id' => 2,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-14 18:53:33',
            'modified' => '2018-02-14 18:53:33'
        ],
        [
            'id' => 3,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-14 18:53:33',
            'modified' => '2018-02-14 18:53:33'
        ],
        [
            'id' => 4,
            'name' => 'Lorems ipsum dolor sit amet',
            'enterprise_id' => 2,
            'created' => '2018-02-14 18:53:33',
            'modified' => '2018-02-14 18:53:33'
        ],

    ];
     public function init()
    { 
        parent::init(); // TODO: Change the autogenerated stub
         

        for($i=0;$i<25;$i++){
            array_push($this->records,[
                'id' => $i+5,
                'name' => 'Lorem ipsum dolor sit amet',
                'enterprise_id' => $i+1 == 2 ? 2 : 1,
                'created' => '2018-02-14 18:53:33',
                'modified' => '2018-02-14 18:53:33'
            ]);
        }
    }
}
