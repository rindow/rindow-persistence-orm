<?php
namespace Rindow\Persistence\Orm\Criteria;

class AggregateFunction extends Selection implements Expression /*, PredicateInterface */
{
    const COUNT = 'COUNT';
    const SUM = 'SUM';
    const MAX = 'MAX';
    const MIN = 'MIN';
    const AVG = 'AVG';

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
        return 'FUNCTION';
    }
}
