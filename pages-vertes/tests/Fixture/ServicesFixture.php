<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ServicesFixture
 *
 */
class ServicesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'price' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'service_category_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'picture_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'BY_SERVICE_CATEGORY_ID' => ['type' => 'index', 'columns' => ['service_category_id'], 'length' => []],
            'BY_PICTURE_ID' => ['type' => 'index', 'columns' => ['picture_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'services_ibfk_1' => ['type' => 'foreign', 'columns' => ['service_category_id'], 'references' => ['service_categories', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'price' => 'Lorem ipsum dolor sit amet',
            'created' => '2018-02-14 16:44:06',
            'modified' => '2018-02-14 16:44:06',
            'service_category_id' => 1,
            'picture_id' => 1
        ],
        [
            'id' => 2,
            'name' => 'Lorem ipsum dolor sit amet',
            'price' => 'Lorem ipsum dolor sit amet',
            'created' => '2018-02-14 16:44:06',
            'modified' => '2018-02-14 16:44:06',
            'service_category_id' => 2,
            'picture_id' => 2
        ],
        [
            'id' => 3,
            'name' => 'Lorem ipsum dolor sit amet',
            'price' => 'Lorem ipsum dolor sit amet',
            'created' => '2018-02-14 16:44:06',
            'modified' => '2018-02-14 16:44:06',
            'service_category_id' => 2,
            'picture_id' => 3
        ],
        [
            'id' => 4,
            'name' => 'Lorem ipsum dolor sit amet',
            'price' => 'Lorem ipsum dolor sit amet',
            'created' => '2018-02-14 16:44:06',
            'modified' => '2018-02-14 16:44:06',
            'service_category_id' => 4,
            'picture_id' => 4
        ],
    ];
     public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        for($i=0;$i<40;$i++){
            array_push($this->records,  [
                'id' => $i+5,
                'name' => 'Lorem ipsum dolor sit amet',
                'price' => 1,
                'service_category_id' => ($i % 3)+5,
                'picture_id' => 7,
                'created' => '2018-02-14 15:38:08',
                'modified' => '2018-02-14 15:38:08'
            ]);
        }
    }
}