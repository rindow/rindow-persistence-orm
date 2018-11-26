<?php
namespace Rindow\Persistence\Orm\Criteria;

interface PreparedCriteria
{
    /**
    * @return Source criteria
    */
    public function getCriteria();

    /**
    * @return String
    */
    public function getEntityClass();
}
