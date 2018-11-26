<?php
namespace Rindow\Persistence\Orm\Criteria;

class Path extends Selection implements Expression /* , SelectionInterface, PathInterface */
{
    protected $nodeName;
    protected $parent;

    public function __construct($nodeName, $parent=null)
    {
        $this->nodeName = $nodeName;
        $this->parent = $parent;
    }

    public function get($nodeName)
    {
        return new Path($nodeName,$this);
    }

    public function getParentPath()
    {
        return $this->parent;
    }

    public function typeString()
    {
        if($this->parent==null)
            return $this->nodeName;
        return $this->parent->typeString().'->'.$this->nodeName;
    }

    public function getNodeName()
    {
        return $this->nodeName;
    }

    public function getExpressionType()
    {
        return 'PATH';
    }
}
