<?php
namespace Rindow\Persistence\Orm\Criteria;

use Rindow\Persistence\Orm\Exception;

class CriteriaQuery /* implements CriteriaQueryInterface */
{
    protected $resultType;
    protected $roots;
    protected $selection;
    protected $distinctFlg = false;
    protected $groupList = array();
    protected $groupRestriction;
    protected $orderList = array();
    protected $restriction;
    protected $parameters = array();

    public function __construct($resultType = null)
    {
        $this->resultType = $resultType;
    }

    public function select(Selection $selection)
    {
        $this->selection = $selection;
        return $this;
    }

    public function multiselect(/* Selection ... $selection*/)
    {
        $selections = func_get_args();
        $selections = $this->fairingPluralArguments($selections,'Must have two expression or more.');
        foreach ($selections as $selection) {
            if(!($selection instanceof Selection))
                throw new Exception\InvalidArgumentException('Must be "Selection".');
        }
        $this->selection = new CompoundSelection($selections);
        return $this;
    }

    public function from($entityClass)
    {
        $this->roots = new Root($entityClass);
        return $this->roots;
    }

    public function distinct($distinct)
    {
        $this->distinctFlg = $distinct;
        return $this;
    }

    public function groupBy(/* Path ... $grouping */)
    {
        $groupings = func_get_args();
        $groupings = $this->fairingPluralArguments($groupings,'Must have a expression or more.',$allowSingle=true);
        foreach ($groupings as $group) {
            if(!($group instanceof Path))
                throw new Exception\InvalidArgumentException('Must be "Path".');
        }
        $this->groupList = $groupings;
        return $this;
    }

    public function having(ComparisonOperator $restriction)
    {
        $this->groupRestriction = $restriction;
        return $this;
    }

    public function orderBy(/* Order ... $order */)
    {
        $args = func_get_args();
        $args = $this->fairingPluralArguments($args,'Must have a expression or more.',$allowSingle=true);
        $orders = array();
        foreach ($args as $arg) {
            if($arg instanceof Order)
                $orders[] = $arg;
            elseif ($arg instanceof Path) {
                $orders[] = new Order($arg);
            } else {
                throw new Exception\InvalidArgumentException('Must be "Order" or "Path".');
            }
        }
        $this->orderList = $orders;
        return $this;
    }

    public function where(ComparisonOperator $restriction)
    {
        $this->restriction = $restriction;
        return $this;
    }

/*
    public function subquery($className)
    {
        return new Subquery($this,$className);
    }
*/
    public function getResultType()
    {
        return $this->resultType;
    }

    public function getSelection()
    {
        return $this->selection;
    }

    public function getRoots()
    {
        return $this->roots;
    }

    public function isDistinct()
    {
         return $this->distinctFlg ? true : false;
    }

    public function getGroupList()
    {
        return $this->groupList;
    }

    public function getOrderList()
    {
        return $this->orderList;
    }

    public function getRestriction()
    {
        return $this->restriction;
    }

    public function getGroupRestriction()
    {
        return $this->groupRestriction;
    }

    public function getParameters()
    {
        return $this->parameters;
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
}