<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pictures Model
 *
 * @property \App\Model\Table\EmployeesTable|\Cake\ORM\Association\HasMany $Employees
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\HasMany $Products
 * @property \App\Model\Table\ServicesTable|\Cake\ORM\Association\HasMany $Services
 * @property \App\Model\Table\SuppliersTable|\Cake\ORM\Association\HasMany $Suppliers
 *
 * @method \App\Model\Entity\Picture get($primaryKey, $options = [])
 * @method \App\Model\Entity\Picture newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Picture[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Picture|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Picture patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Picture[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Picture findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PicturesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('pictures');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Employees', [
            'foreignKey' => 'picture_id'
        ]);
        $this->hasMany('Products', [
            'foreignKey' => 'picture_id'
        ]);
        $this->hasMany('Services', [
            'foreignKey' => 'picture_id'
        ]);
        $this->hasMany('Suppliers', [
            'foreignKey' => 'picture_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('base64image')
            ->maxLength('base64image', 4294967295)
            ->requirePresence('base64image', 'create')
            ->notEmpty('base64image');

        return $validator;
    }
}
