<?php
namespace Rindow\Persistence\Orm;

interface EntityManager
{
    public function find(/*String*/ $entityClass, $primaryKey, $lockMode=null, array $properties=null);

    public function contains($entity);

    public function remove($entity);

    public function persist($entity);

    public function detach($entity);

    public function merge($entity);

    public function clear();

    public function flush();

    public function createQuery($query, /*String*/ $resultClass=null);

    public function createNamedQuery(/*String*/ $name, /*String*/ $resultClass=null);

    public function close();

    public function getCriteriaBuilder();

/*
    public function getLockMode(Object $entity);
    public function getProperties();
    public function getEntityGraph(String $graphName);
    public function getTransaction();
    public function isJoinedToTransaction();
    public function joinTransaction();
    public function setProperty(String $propertyName, Object $value);
    public function lock($entity,$lockMode,array $options=null);
*/
}
