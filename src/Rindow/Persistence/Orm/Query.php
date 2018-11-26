<?php
namespace Rindow\Persistence\Orm;

interface Query
{
    public function getFirstResult();
    public function getLockMode();
    public function getMaxResults();
    public function getParameters();
    public function getParameterValue($name);
    public function getResultList();
    public function getSingleResult();
    public function setFirstResult($startPosition);
    public function setLockMode($lockMode);
    public function setMaxResults($maxResult);
    public function setParameter($name, $value);
    public function unwrap($class=null);
/*
    public function executeUpdate();
    public function getFlushMode();
    public function getHints();
    public function getParameter($name);
    public function isBound(Parameter $param);
    public function setFlushMode($flushMode);
    public function setHint($hintName, Object $value);
*/

}
