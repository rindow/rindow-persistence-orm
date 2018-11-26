<?php
namespace Rindow\Persistence\Orm;

class Module
{
    public function getConfig()
    {
        return array(
            'container' => array(
                /*
                *   'aliases' => array(
                *       'Rindow\\Persistence\\Orm\\Repository\\DefaultEntityManager' => 'your EntityManager',
                *   ),
                */
                'components' => array(
                    //
                    // Orm Criteria Builder
                    //
                    'Rindow\\Persistence\\Orm\\DefaultCriteriaBuilder' => array(
                        'class' => 'Rindow\\Persistence\\Orm\\Criteria\\CriteriaBuilder',
                    ),
                    //
                    // Orm Repository
                    //
                    'Rindow\\Persistence\\Orm\\Repository\\AbstractOrmRepository' => array(
                        'class' => 'Rindow\\Persistence\\Orm\\Repository\\OrmRepository',
                        'properties' => array(
                            'entityManager' => array('ref'=>'Rindow\\Persistence\\Orm\\Repository\\DefaultEntityManager'),
                            'queryBuilder' => array('ref'=>'Rindow\\Persistence\\Orm\\Repository\\DefaultQueryBuilder'),
                            // Specifiy the className with same entity class
                            //'className' => array('value'=>'override_to_entity_class_name'),
                        ),
                    ),
                    'Rindow\\Persistence\\Orm\\Repository\\DefaultQueryBuilder' => array(
                        'class' => 'Rindow\\Database\\Dao\\Support\\QueryBuilder',
                    ),
                ),
            ),
            'aop' => array(
                'intercept_to' => array(
                    'Rindow\\Persistence\\Orm\\Repository\\OrmRepository'=>true,
                ),
                'aspectOptions' => array(
                    'Rindow\\Transaction\\DefaultTransactionAdvisor' => array(
                        'pointcuts' => array(
                            'Rindow\\Persistence\\Orm\\Repository\\OrmRepository'=> 
                                'execution(Rindow\\Persistence\\Orm\\Repository\\OrmRepository::'.
                                    '(save|findById|findAll|findOne|delete|deleteById|existsById|count)())',
                        ),
                        'advices' => array(
                            'required' => array(
                                'pointcut_ref' => array(
                                    'Rindow\\Persistence\\Orm\\Repository\\OrmRepository'=>true,
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
}
