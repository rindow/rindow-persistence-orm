<?php
namespace Rindow\Persistence\Orm\Criteria;

class Root extends Path
{
    protected $joins = array();

    public function join($attribute,$joinType=null)
    {
        $join = new Join($this, $attribute, $joinType);
        $this->joins[] = $join;
        return $join;
    }

    public function getJoins()
    {
        return $this->joins;
    }

    public function getExpressionType()
    {
        return 'ROOT';
    }
}