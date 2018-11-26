<?php
namespace Rindow\Persistence\Orm\Criteria;

class Parameter implements Expression /* , ParameterInterface */
{
    protected $position;
    protected $name;
    protected $parameterType;

    public function __construct($parameterType=null,$name=null)
    {
        $this->parameterType = $parameterType;
        $this->name = $name;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParameterType()
    {
        return $this->parameterType;
    }

    public function getExpressionType()
    {
        return 'PARAMETER';
    }
}
