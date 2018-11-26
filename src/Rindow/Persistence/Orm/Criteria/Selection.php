<?php
namespace Rindow\Persistence\Orm\Criteria;

abstract class Selection
{
    protected $aliasName;
    abstract public function getExpressionType();

    public function alias($name)
    {
        $this->aliasName = $name;
        return $this;
    }
    
    public function getAlias()
    {
        return $this->aliasName;
    }

    public function getCompoundSelectionItems()
    {
        throw new Exception\DomainExcetion('Not supported Operation.');
    }

    public function isCompoundSelection()
    {
        return false;
    }
}
