<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Supplier Entity
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $supplier_category_id
 * @property int $picture_id
 *
 * @property \App\Model\Entity\SupplierCategory $supplier_category
 * @property \App\Model\Entity\Picture $picture
 */
class Supplier extends Entity
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
        'name' => true,
        'address' => true,
        'created' => true,
        'modified' => true,
        'supplier_category_id' => true,
        'picture_id' => true,
        'supplier_category' => true,
        'picture' => true
    ];
}
