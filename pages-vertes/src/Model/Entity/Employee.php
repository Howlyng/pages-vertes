<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Employee Entity
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property \Cake\I18n\FrozenDate $birthday
 * @property \Cake\I18n\FrozenDate $hire_date
 * @property string $address
 * @property int $employe_category_id
 * @property int $picture_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\EmployeCategory $employe_category
 * @property \App\Model\Entity\Picture $picture
 */
class Employee extends Entity
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
        'firstname' => true,
        'lastname' => true,
        'birthday' => true,
        'hire_date' => true,
        'address' => true,
        'employe_category_id' => true,
        'picture_id' => false,
        'created' => true,
        'modified' => true,
        'employe_category' => true,
        'picture' => false
    ];

    protected function _getFullName(){
      return $this->_properties['firstname'] . " " . $this->_properties['lastname'];
    }
}
