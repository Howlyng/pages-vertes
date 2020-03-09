<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Enterprises Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PicturesTable|\Cake\ORM\Association\BelongsTo $Pictures
 * @property \App\Model\Table\EmployeCategoriesTable|\Cake\ORM\Association\HasMany $EmployeCategories
 * @property \App\Model\Table\ProductCategoriesTable|\Cake\ORM\Association\HasMany $ProductCategories
 * @property \App\Model\Table\ServiceCategoriesTable|\Cake\ORM\Association\HasMany $ServiceCategories
 * @property \App\Model\Table\SupplierCategoriesTable|\Cake\ORM\Association\HasMany $SupplierCategories
 *
 * @method \App\Model\Entity\Enterprise get($primaryKey, $options = [])
 * @method \App\Model\Entity\Enterprise newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Enterprise[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Enterprise|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Enterprise patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Enterprise[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Enterprise findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EnterprisesTable extends Table
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

        $this->setTable('enterprises');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Products',[
            'through'=>'ProductCategories'
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'owner_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Pictures', [
            'foreignKey' => 'logo_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('EmployeCategories', [
            'foreignKey' => 'enterprise_id'
        ]);
        $this->hasMany('ProductCategories', [
            'foreignKey' => 'enterprise_id'
        ]);
        $this->hasMany('ServiceCategories', [
            'foreignKey' => 'enterprise_id'
        ]);
        $this->hasMany('SupplierCategories', [
            'foreignKey' => 'enterprise_id'
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
            ->maxLength('name', 200)
            ->requirePresence('name', 'create')
            ->notEmpty('name','logo_id')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('domain_name')
            ->maxLength('domain_name', 200)
            ->allowEmpty('domain_name');

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
        $rules->add($rules->isUnique(['name']));
        $rules->add($rules->existsIn(['owner_id'], 'Users'));
        $rules->add($rules->existsIn(['logo_id'], 'Pictures'));

        return $rules;
    }
}
