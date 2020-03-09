<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Services Model
 *
 * @property \App\Model\Table\ServiceCategoriesTable|\Cake\ORM\Association\BelongsTo $ServiceCategories
 * @property \App\Model\Table\PicturesTable|\Cake\ORM\Association\BelongsTo $Pictures
 *
 * @method \App\Model\Entity\Service get($primaryKey, $options = [])
 * @method \App\Model\Entity\Service newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Service[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Service|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Service patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Service[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Service findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServicesTable extends Table
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

        $this->setTable('services');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ServiceCategories', [
            'foreignKey' => 'service_category_id',
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
            ->LengthBetween('name', [5,50])
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('price')
            ->maxLength('price', 255)
            ->requirePresence('price', 'create')
            ->notEmpty('price')
            ->add('price', 'money', array('rule' => array('money','left'),
                'message' => __('Please supply a valid monetary amount.')))
            ->add('price', 'minValue',['rule' => function($val){return $val > 0;}, 'message'=>'Value must be > 0'] )
            ->add('price', 'maxValue',['rule' => function($val){return $val < 1000000;},'message'=>'Value must be < 1 000 000'] );
            ##->notEmpty('picture_id');
      
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
        $rules->add($rules->existsIn(['service_category_id'], 'ServiceCategories'));
        //$rules->add($rules->existsIn(['picture_id'], 'Pictures'));
        $rules->add(
        function ($entity, $options) {
        $rule = new \Cake\ORM\Rule\ExistsIn(['picture_id'], 'Pictures');
        return $entity->picture_id === NULL || $rule($entity, $options);
        },
         ['errorField' => 'picture_id', 'message' => 'Picture ID specified but does not exist']
        );  
        return $rules;
    }
    public function getServListingFromEnterprise( $ent_id, $toarray){
        $q = $this->find()
            ->contain('ServiceCategories')
            ->contain('Pictures')
            ->where(['enterprise_id' => $ent_id]);
             return($toarray ? $q->toArray() : $q);
    }
    // va  supprimer la photo associser a la picture id 
    public function afterDelete($event, $entity){
        $PICTURE = TableRegistry::get("Pictures");
        if($entity->picture_id==null){
             return true;
        }else{
           $Pic =$PICTURE->find()->where(['id'=> $entity->picture_id])->first();
            if($Pic==null) {
                return false;
            }else{                 

               if($PICTURE->delete($Pic)){
                   return true;
               }else{
               return false;
               }
            }
       }
    }
}
