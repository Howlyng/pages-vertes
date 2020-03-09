<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Products Model
 *
 * @property \App\Model\Table\ProductCategoriesTable|\Cake\ORM\Association\BelongsTo $ProductCategories
 * @property \App\Model\Table\PicturesTable|\Cake\ORM\Association\BelongsTo $Pictures
 *
 * @method \App\Model\Entity\Product get($primaryKey, $options = [])
 * @method \App\Model\Entity\Product newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Product[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Product[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductsTable extends Table
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

        $this->setTable('products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ProductCategories', [
            'foreignKey' => 'product_category_id',
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
            ->scalar('name')
            ->minLength('name', 6, __d("Products", "Required length is between 6 and 49"))
            ->maxLength('name', 49, __d("Products", "Required length is between 6 and 49"))
            ->requirePresence('name',['create', 'update'], __d('Products','This field cannot be left empty'))
            ->notEmpty('name',__d('Products','This field cannot be left empty'));

        $validator
            ->scalar('price')
            ->numeric('price')
            ->requirePresence('price', 'create')
            ->notEmpty('price')
            ->add('price', 'money', array('rule' => array('money','left'),
                'message' => __('Please supply a valid monetary amount.')))
            ->add('price', 'minValue',['rule' => function($val){return $val > 0;}, 'message'=>'Value must be > 0'] )
            ->add('price', 'maxValue',['rule' => function($val){return $val < 1000000;},'message'=>'Value must be < 1 000 000'] );

        $validator
            ->scalar('quantity_available')
            ->numeric('quantity_available')
            ->requirePresence('quantity_available', 'create')
            ->notEmpty('quantity_available')
            ->add('quantity_available', 'minValue',['rule' => function($val){return $val >= 0;}, 'message'=>'Value must be >= 0'] );


        $validator
            ->scalar('quantity_min_limit')
            ->numeric('quantity_min_limit')
            ->requirePresence('quantity_min_limit', 'create')
            ->notEmpty('quantity_min_limit')
            ->add('quantity_min_limit', 'minValue',['rule' => function($val){return $val >= 0;}, 'message'=>'Value must be >= 0'] )
            ->add('quantity_min_limit', 'miaxalue',['rule' => function($val){return $val <= 9999;}, 'message'=>'Value must be <= 9999'] );


        $validator
            ->scalar('quantity_max_limit')
            ->numeric('quantity_max_limit')
            ->requirePresence('quantity_max_limit', 'create')
            ->notEmpty('quantity_max_limit')
            ->add('quantity_max_limit', 'maxValue',['rule' => function($val){return $val <= 9999999;}, 'message'=>'Value must be < 10 000 000'] )
            ->notEmpty('picture_id')
            ->notEmpty('product_category_id');

        return $validator;
    }
    function minValue($val, $limit){return $val > $limit;}
    function maxValue($val, $limit){return $val < $limit;}
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['product_category_id'], 'ProductCategories'));
        $rules->add($rules->existsIn(['picture_id'], 'Pictures'));

        //Limit de qte////////////////////////////////////////
        $rules->add(function($entity, $options){
            return ($entity->quantity_min_limit <= $entity->quantity_max_limit);
        }, 'ValidateQuantities', ['errorField'=>'quantity_min_limit', 'message'=>__('Minimum quantity can\'t be higher than maximum')]);


        return $rules;
    }

    public function getProdListingFromEnterprise( $ent_id, $toarray){
       $q = $this->find()
           ->contain('ProductCategories')
           ->contain('Pictures')
           ->where(['enterprise_id' => $ent_id]);

        return($toarray ? $q->toArray() : $q);
    }

    public function afterDelete($event, $entity){
        $PIC = TableRegistry::get("Pictures");

        if($PIC->delete($PIC->get($entity->picture_id)))
            return true;
        return false;
    }
}
