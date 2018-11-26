<?php
namespace Rindow\Persistence\Orm\Criteria;

use Rindow\Persistence\Orm\Exception;

class CriteriaBuilder /* implements CriteriaBuilderInterface */
{
    protected $parameterAutoName = 0;

    public function createQuery($resultType=null)
    {
        return new CriteriaQuery($resultType);
    }

    public function equal($x, $y)
    {
        return new ComparisonOperator(ComparisonOperator::EQUAL, array($this->wrap($x), $this->wrap($y)));
    }

    public function ge($x, $y)
    {
        return new ComparisonOperator(ComparisonOperator::GREATER_THAN_OR_EQUAL, array($this->wrap($x), $this->wrap($y)));
    }

    public function gt($x, $y)
    {
        return new ComparisonOperator(ComparisonOperator::GREATER_THAN, array($this->wrap($x), $this->wrap($y)));
    }

    public function le($x, $y)
    {
        return new ComparisonOperator(ComparisonOperator::LESS_THAN_OR_EQUAL, array($this->wrap($x), $this->wrap($y)));
    }

    public function lt($x, $y)
    {
        return new ComparisonOperator(ComparisonOperator::LESS_THAN, array($this->wrap($x), $this->wrap($y)));
    }

    public function and_(/* $expression, .... */)
    {
        $expressions = func_get_args();
        $expressions = $this->fairingPluralArguments($expressions,'Must have two expression or more.');
        foreach ($expressions as $expression) {
            if(!($expression instanceof ComparisonOperator))
                throw new Exception\InvalidArgumentException('Must be "ComparisonOperator".');
        }
        return new ComparisonOperator(ComparisonOperator::AND_, $expressions);
    }

    public function or_(/* $expression, .... */)
    {
        $expressions = func_get_args();
        $expressions = $this->fairingPluralArguments($expressions,'Must have two expression or more.');
        foreach ($expressions as $expression) {
            if(!($expression instanceof ComparisonOperator))
                throw new Exception\InvalidArgumentException('Must be "ComparisonOperator".');
        }
        return new ComparisonOperator(ComparisonOperator::OR_, $expressions);
    }

    public function not(ComparisonOperator $expression)
    {
        return $expression->not();
    }

    public function asc(Path $expression)
    {
        return new Order($expression);
    }

    public function desc(Path $expression)
    {
        return new Order($expression,$ascending=false);
    }

    public function count(Selection $expression)
    {
        return new AggregateFunction(AggregateFunction::COUNT, array($expression));
    }

    public function sum(Selection $expression)
    {
        return new AggregateFunction(AggregateFunction::SUM, array($expression));
    }

    public function max(Selection $expression)
    {
        return new AggregateFunction(AggregateFunction::MAX, array($expression));
    }

    public function min(Selection $expression)
    {
        return new AggregateFunction(AggregateFunction::MIN, array($expression));
    }

    public function avg(Selection $expression)
    {
        return new AggregateFunction(AggregateFunction::AVG, array($expression));
    }

    public function parameter($paramClass=null, $name=null)
    {
        if($name===null) {
            $name = 'prmatnm'.$this->parameterAutoName;
            $this->parameterAutoName += 1;
        }
        return new Parameter($paramClass, $name);
    }

    public function tuple(/* Selection ... $selection*/)
    {
        $selections = func_get_args();
        $selections = $this->fairingPluralArguments($selections,'Must have two expression or more.');
        foreach ($selections as $selection) {
            if(!($selection instanceof Selection))
                throw new Exception\InvalidArgumentException('Must be "Selection".');
        }
        return new CompoundSelection($selections);
    }

    public function array_(/* Selection ... $selection*/)
    {
        $selections = func_get_args();
        $selections = $this->fairingPluralArguments($selections,'Must have two expression or more.');
        foreach ($selections as $selection) {
            if(!($selection instanceof Selection))
                throw new Exception\InvalidArgumentException('Must be "Selection".');
        }
        return new CompoundSelection($selections);
    }

    protected function fairingPluralArguments($arguments,$exceptionMessage,$allowSingle=false)
    {
        if(count($arguments)==0) {
            throw new Exception\InvalidArgumentException($exceptionMessage);
        } elseif(count($arguments)==1) {
            if(!is_array($arguments[0])) {
                if(!$allowSingle)
                    throw new Exception\InvalidArgumentException($exceptionMessage);
            } else {
                $arguments = $arguments[0];
            }
        }
        return $arguments;
    }

    protected function wrap($value)
    {
        if($value instanceof Expression)
            return $value;
        if(!is_scalar($value))
            throw new Exception\InvalidArgumentException('Constant must be scalar value.');
        return new ConstantValue($value);
    }
}