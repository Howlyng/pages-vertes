<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;


/**
 * Employees Model
 *
 * @property \App\Model\Table\EmployeCategoriesTable|\Cake\ORM\Association\BelongsTo $EmployeCategories
 * @property \App\Model\Table\PicturesTable|\Cake\ORM\Association\BelongsTo $Pictures
 *
 * @method \App\Model\Entity\Employee get($primaryKey, $options = [])
 * @method \App\Model\Entity\Employee newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Employee[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Employee|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Employee patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Employee[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Employee findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EmployeesTable extends Table
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

        $this->setTable('employees');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('EmployeCategories', [
            'foreignKey' => 'employe_category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Pictures', [
            'foreignKey' => 'picture_id',
            'joinType' => 'INNER'
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
            ->scalar('firstname')
            ->lengthBetween('firstname', [6, 49], __d("Employees", 'Value must be > 5 and < 50 characters'))
            ->requirePresence('firstname', 'create')
            ->notEmpty('firstname', __d("Employees", 'Value cannot be empty'));

        $validator
            ->scalar('lastname')
            ->lengthBetween('lastname', [6, 49], __d("Employees", 'Value must be > 5 and < 50 characters'))
            ->requirePresence('lastname', 'create')
            ->notEmpty('lastname', __d("Employees", 'Value cannot be empty'));

        $validator
            ->date('birthday')
            ->requirePresence('birthday', 'create')
            ->notEmpty('birthday', __d("Employees", 'Value cannot be empty'));

        $validator
            ->date('hire_date')
            ->requirePresence('hire_date', 'create')
            ->notEmpty('hire_date', __d("Employees", 'Value cannot be empty'));

        $validator
            ->scalar('address')
            ->lengthBetween('address', [6, 99], __d("Employees", 'Value must be > 5 and < 100 characters'))
            ->requirePresence('address', 'create')
            ->notEmpty('address', __d("Employees", 'Value cannot be empty'));

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
        $rules->add($rules->existsIn(['employe_category_id'], 'EmployeCategories'));
        $rules->add($rules->existsIn(['picture_id'], 'Pictures', 'create'));
//        Time::now()->diff($employee->birthday)->d == 0
        // ----- DATES -----
        //Birthday must be != today
        $rules->add(function ($entity) {
            return (Time::now()->diff($entity->birthday)->days != 0);
        }, 'BirthdayValidateToday', ['errorField' => 'birthday', 'message' => __d("Employees", 'Birthday must be < today')]);
        //Birthday must be < today
        $rules->add(function ($entity) {
            return ($entity->birthday < Time::now());
        }, 'BirthdayValidateFuture', ['errorField' => 'birthday', 'message' => __d("Employees", 'Birthday must be < today')]);

//        $rules->add(function($entity){
//            return ($entity->birthday < Time::now());
//        },'BirthdayValidateFuture', ['errorField'=>'birthday', 'message'=>__('Birthday must be < today')]);


        //Hire date can't be higher than today + 14 days
        $rules->add(function ($entity) {
            return ($entity->hire_date <= Time::now()->modify('+14 days'));
        }, 'HireDateValidate', ['errorField' => 'hire_date', 'message' => __d("Employees", 'Hire date must be < today + 14 days')]);


        return $rules;
    }

    public function findByEnterpriseId($id_enterprise, $toArray = true)
    {
        $return = ($this->find()
            ->contain('EmployeCategories')
            ->contain('Pictures')
            ->where(['enterprise_id' => $id_enterprise]));

        return($toArray ? $return->toArray() : $return);
    }

    public function afterDelete($event, $entity)
    {
        $PIC = TableRegistry::get("Pictures");

        if ($PIC->delete($PIC->get($entity->picture_id)))
            return true;
        return false;
    }
}
