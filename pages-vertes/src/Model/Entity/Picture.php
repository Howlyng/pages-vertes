<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Picture Entity
 *
 * @property int $id
 * @property string $base64image
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Employee[] $employees
 * @property \App\Model\Entity\Product[] $products
 * @property \App\Model\Entity\Service[] $services
 * @property \App\Model\Entity\Supplier[] $suppliers
 */
class Picture extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'base64image' => true,
        'created' => true,
        'modified' => true,
        'employees' => true,
        'products' => true,
        'services' => true,
        'suppliers' => true
    ];
}
