<?php
namespace Rindow\Persistence\Orm\Criteria;

interface CriteriaMapper
{
    /**
    * @param  Object  $context
    * @return void
    */
    public function setContext($context);

    /**
    * @param  CriteriaQuery  $query
    * @param  String         $resultClass
    * @return PreparedCriteriaQuery
    */
    public function prepare(/* CommonAbstractCriteria */$query,$resultClass=null);
}
