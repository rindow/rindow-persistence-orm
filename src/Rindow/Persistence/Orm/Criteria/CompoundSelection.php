<?php
namespace Rindow\Persistence\Orm\Criteria;

class CompoundSelection extends Selection implements Expression /* , CompoundSelectionInterface */
{
    protected $compoundSelectionItems;

    public function __construct(array $compoundSelectionItems)
    {
        $this->compoundSelectionItems = $compoundSelectionItems;
    }

    public function getCompoundSelectionItems()
    {
        return $this->compoundSelectionItems;
    }

    public function isCompoundSelection()
    {
        return true;
    }

    public function getExpressionType()
    {
        return 'COMPOUND';
    }
}
