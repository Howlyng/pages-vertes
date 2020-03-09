<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ServiceCategories Model
 *
 * @property \App\Model\Table\EnterprisesTable|\Cake\ORM\Association\BelongsTo $Enterprises
 * @property \App\Model\Table\ServicesTable|\Cake\ORM\Association\HasMany $Services
 *
 * @method \App\Model\Entity\ServiceCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\ServiceCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ServiceCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ServiceCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServiceCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ServiceCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServiceCategoriesTable extends Table
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

        $this->setTable('service_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Enterprises', [
            'foreignKey' => 'enterprise_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Services', [
            'foreignKey' => 'service_category_id',
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
            ->minLength('name',6)
            ->maxLength('name',49)
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
        return $this->find('all')->where(['enterprise_id'=>$ent_id]);
    }
}
