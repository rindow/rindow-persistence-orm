<?php
namespace Rindow\Persistence\Orm\Criteria;

class Order implements Expression /* , OrderInerface */
{
    protected $expression;
    protected $ascending = true;

    public function __construct(Path $expression,$ascending=true)
    {
        $this->expression = $expression;
        $this->ascending = $ascending;
    }

    public function getExpression()
    {
        return $this->expression;
    }

    public function isAscending()
    {
        return $this->ascending;
    }

    public function reverse()
    {
        $this->ascending = !$this->ascending;
        return $this;
    }

    public function getExpressionType()
    {
        return 'ORDER';
    }
}
