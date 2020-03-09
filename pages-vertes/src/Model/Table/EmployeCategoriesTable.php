<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmployeCategories Model
 *
 * @property \App\Model\Table\EnterprisesTable|\Cake\ORM\Association\BelongsTo $Enterprises
 * @property \App\Model\Table\EmployeesTable|\Cake\ORM\Association\HasMany $Employees
 *
 * @method \App\Model\Entity\EmployeCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmployeCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EmployeCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmployeCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmployeCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmployeCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EmployeCategoriesTable extends Table
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

        $this->setTable('employe_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Enterprises', [
            'foreignKey' => 'enterprise_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Employees', [
            'foreignKey' => 'employe_category_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
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
            ->scalar('name')
            ->lengthBetween('name', [6,49], __d('Employees', 'Value must be > 5 and < 50 characters'))
            ->requirePresence('name', 'create')
            ->notEmpty('name',  __d("Employees",'Value cannot be empty'));

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['enterprise_id'], 'Enterprises'));

        return $rules;
    }

    public function findByEnterpriseId($id_enterprise, $toArray = true)
    {
        $return =$this->find()
            ->where(['enterprise_id' => $id_enterprise]);

        return($toArray ? $return->toArray() : $return);
    }
}
