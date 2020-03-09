<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Service Entity
 *
 * @property int $id
 * @property string $name
 * @property string $price
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $service_category_id
 * @property int $picture_id
 *
 * @property \App\Model\Entity\ServiceCategory $service_category
 * @property \App\Model\Entity\Picture $picture
 */
class Service extends Entity
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
        'created' => true,
        'modified' => true,
        'service_category_id' => true,
        'picture_id' => true,
        'service_category' => true,
        'picture' => true
    ];
}
