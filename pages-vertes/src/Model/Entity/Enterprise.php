<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Enterprise Entity
 *
 * @property int $id
 * @property string $name
 * @property string $domain_name
 * @property int $owner_id
 * @property int $logo_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Picture $picture
 * @property \App\Model\Entity\EmployeCategory[] $employe_categories
 * @property \App\Model\Entity\ProductCategory[] $product_categories
 * @property \App\Model\Entity\ServiceCategory[] $service_categories
 * @property \App\Model\Entity\SupplierCategory[] $supplier_categories
 */
class Enterprise extends Entity
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
        'domain_name' => true,
        'owner_id' => true,
        'logo_id' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'picture' => true,
        'description' =>true,
        'employe_categories' => true,
        'product_categories' => true,
        'service_categories' => true,
        'supplier_categories' => true
    ];
}
