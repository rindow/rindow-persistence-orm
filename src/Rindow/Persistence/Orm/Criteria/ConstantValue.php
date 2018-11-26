<?php
namespace Rindow\Persistence\Orm\Criteria;

class ConstantValue implements Expression
{
    protected $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getExpressionType()
    {
        return 'CONSTANT';
    }
}
