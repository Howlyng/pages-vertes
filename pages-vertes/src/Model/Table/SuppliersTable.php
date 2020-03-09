<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
/**
 * Suppliers Model
 *
 * @property \App\Model\Table\SupplierCategoriesTable|\Cake\ORM\Association\BelongsTo $SupplierCategories
 * @property \App\Model\Table\PicturesTable|\Cake\ORM\Association\BelongsTo $Pictures
 *
 * @method \App\Model\Entity\Supplier get($primaryKey, $options = [])
 * @method \App\Model\Entity\Supplier newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Supplier[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Supplier|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Supplier patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Supplier[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Supplier findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SuppliersTable extends Table
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

        $this->setTable('suppliers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('SupplierCategories', [
            'foreignKey' => 'supplier_category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Pictures', [
            'foreignKey' => 'picture_id',
            'joinType' => 'LEFT'
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
            ->lengthBetween('name',[6,49], __d("Suppliers", "Value must be between 5 and 50."))
            ->requirePresence('name', 'create')
            ->notEmpty('name', __d("Suppliers","Value cannot be empty."));

        $validator
            ->scalar('address')
            ->lengthBetween('address',[6,99], __d("Suppliers", "Value must be between 5 and 100."))
            ->requirePresence('address', 'create')
            ->notEmpty('address', __d("Suppliers",'Value cannot be empty'));

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
        $rules->add($rules->existsIn(['supplier_category_id'], 'SupplierCategories'));
        $rules->add(
            function ($entity, $options) {
                $rule = new \Cake\ORM\Rule\ExistsIn(['picture_id'], 'Pictures');
                return $entity->picture_id === NULL || $rule($entity, $options);
            },
            ['errorField' => 'picture_id', 'message' => 'Picture ID specified but does not exist']
        );

        return $rules;
    }

    public function getSuplListingFromEnterprise( $ent_id, $toarray){
        $query = $this->find()
            ->contain('SupplierCategories')
            ->contain('Pictures')
            ->where(['enterprise_id' => $ent_id]);

        return($toarray ? $query->toArray() : $query);
    }

    public function afterDelete($event, $entity)
    {
        $PICTURE = TableRegistry::get("Pictures");
        if ($entity->picture_id == null) {
            return true;
        } else {
            $Pic = $PICTURE->find()->where(['id' => $entity->picture_id])->first();
            if ($Pic == null) {
                return false;
            } else {

                if ($PICTURE->delete($Pic)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}
