<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SupplierCategories Model
 *
 * @property \App\Model\Table\EnterprisesTable|\Cake\ORM\Association\BelongsTo $Enterprises
 * @property \App\Model\Table\SuppliersTable|\Cake\ORM\Association\HasMany $Suppliers
 *
 * @method \App\Model\Entity\SupplierCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\SupplierCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SupplierCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SupplierCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SupplierCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SupplierCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SupplierCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SupplierCategoriesTable extends Table
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

        $this->setTable('supplier_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Enterprises', [
            'foreignKey' => 'enterprise_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Suppliers', [
            'foreignKey' => 'supplier_category_id',
            'dependent' => true,
            'cascadeCallbacks' => true
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
            ->lengthBetween('name', [6,49])
            ->requirePresence('name', 'create')
            ->notEmpty('name');

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

    public function findByEnterpriseId($ent_id){
        return $this->find()->where(['enterprise_id'=>$ent_id])->toArray();
    }
}
