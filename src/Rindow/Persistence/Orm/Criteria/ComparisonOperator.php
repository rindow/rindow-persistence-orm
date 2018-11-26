<?php
namespace Rindow\Persistence\Orm\Criteria;

class ComparisonOperator implements Expression /* , PredicateInterface */
{
    const AND_ = 'AND';
    const OR_ = 'OR';
    const EQUAL = 'EQUAL';
    const GREATER_THAN = 'GREATER_THAN';
    const GREATER_THAN_OR_EQUAL = 'GREATER_THAN_OR_EQUAL';
    const LESS_THAN = 'LESS_THAN';
    const LESS_THAN_OR_EQUAL = 'LESS_THAN_OR_EQUAL';

    protected $negated = false;

    public function setNegated($negated=true)
    {
        $this->negated = $negated;
        return $this;
    }

    public function isNegated()
    {
        return $this->negated;
    }

    public function not()
    {
        $inversed = clone $this;
        $inversed->setNegated(!$inversed->isNegated());
        return $inversed;
    }

    /**
    * Predicate implementations.
    */
    protected $operator;
    protected $expressions = array();

    public function __construct($operator,array $expressions)
    {
        $this->operator = $operator;
        $this->expressions = $expressions;
    }

    public function getExpressions()
    {
        return $this->expressions;
    }

    public function getOperator()
    {
        return $this->operator;
    }

    public function getExpressionType()
    {
        return 'OPERATOR';
    }
}
