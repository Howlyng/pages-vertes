<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SupplierCategoriesFixture
 *
 */
class SupplierCategoriesFixture extends TestFixture
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
            'supplier_categories_ibfk_1' => ['type' => 'foreign', 'columns' => ['enterprise_id'], 'references' => ['enterprises', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 2,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 3,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 2,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 4,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 5,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 6,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 7,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 8,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 9,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 10,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 11,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 12,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 13,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 14,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 15,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 16,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 17,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ],
        [
            'id' => 18,
            'name' => 'Lorem ipsum dolor sit amet',
            'enterprise_id' => 1,
            'created' => '2018-02-26 09:45:10',
            'modified' => '2018-02-26 09:45:10'
        ]
    ];
}
