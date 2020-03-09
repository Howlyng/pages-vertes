<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EnterprisesFixture
 *
 */
class EnterprisesFixture extends TestFixture
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
        'domain_name' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'owner_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'logo_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'BY_OWNER_ID' => ['type' => 'index', 'columns' => ['owner_id'], 'length' => []],
            'BY_LOGO_ID' => ['type' => 'index', 'columns' => ['logo_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'UNIQUE_NAME' => ['type' => 'unique', 'columns' => ['name'], 'length' => []],
            'enterprises_ibfk_1' => ['type' => 'foreign', 'columns' => ['logo_id'], 'references' => ['pictures', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'enterprises_ibfk_2' => ['type' => 'foreign', 'columns' => ['owner_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'domain_name' => 'Lorem ipsum dolor sit amet',
            'owner_id' => 1,
            'logo_id' => 1,
            'created' => '2018-02-07 20:55:51',
            'modified' => '2018-02-07 20:55:51'
        ],
        [
            'id' => 2,
            'name' => 'Lorem ipsum dolor sit amet2',
            'domain_name' => 'Lorem ipsum dolor sit amet',
            'owner_id' => 2,
            'logo_id' => 6,
            'created' => '2018-02-07 20:55:51',
            'modified' => '2018-02-07 20:55:51'
        ],
    ];
}
