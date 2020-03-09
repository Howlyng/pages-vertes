<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property string $name
 * @property string $price
 * @property string $quantity_available
 * @property string $quantity_min_limit
 * @property string $quantity_max_limit
 * @property int $product_category_id
 * @property int $picture_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ProductCategory $product_category
 * @property \App\Model\Entity\Picture $picture
 */
class Product extends Entity
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
        'price' => true,
        'quantity_available' => true,
        'quantity_min_limit' => true,
        'quantity_max_limit' => true,
        'product_category_id' => true,
        'picture_id' => false,
        'created' => true,
        'modified' => true,
        'product_category' => true,
        'picture' => true
    ];



}
