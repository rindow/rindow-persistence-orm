<?php
namespace Rindow\Persistence\Orm\Criteria;

class Join extends Path
{
    protected $joinType;
    protected $restriction;

    public function __construct($parent, $attribute, $joinType=null)
    {
        $this->parent = $parent;
        $this->nodeName = $attribute;
        $this->joinType = $joinType;
    }

    public function on(ComparisonOperator $restriction)
    {
        $this->restriction = $restriction;
    }

    public function getOn()
    {
        return $this->restriction;
    }

    public function getAttribute()
    {
        return $this->getNodeName();
    }

    public function getJoinType()
    {
        return $this->joinType;
    }

    public function getParent()
    {
        return $this->getParentPath();
    }

    public function getExpressionType()
    {
        return 'JOIN';
    }
}