<?php
namespace Rindow\Persistence\Orm\Repository;

use Interop\Lenient\Dao\Repository\CrudRepository;
use Rindow\Database\Dao\Exception;

class OrmRepository implements CrudRepository
{
    protected $entityManager;
    protected $className;
    protected $queryBuilder;

    public function __construct($entityManager=null,$className=null,$queryBuilder=null)
    {
        if($entityManager)
            $this->setEntityManager($entityManager);
        if($className)
            $this->setClassName($className);
        if($queryBuilder)
            $this->setQueryBuilder($queryBuilder);
    }

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setClassName($className)
    {
        $this->className = $className;
    }

    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    protected function assertClassName()
    {
        if(!$this->className)
            throw new Exception\DomainException('Class name is not specified.');
    }

    protected function buildNamedQueryString($filter,$sort)
    {
        return $this->queryBuilder->buildNamedQueryString($filter,$sort);
    }

    protected function createQuery($prefix,$filter,$sort=null,$limit=null,$offset=null)
    {
        list($namedQueryString,$params) = $this->buildNamedQueryString($filter,$sort);
        $namedQueryString = $prefix.$namedQueryString;
        $query = $this->entityManager->createNamedQuery(/*String*/ $namedQueryString, /*String*/ $this->className);
        foreach($params as $name => $value) {
            $query->setParameter($name,$value);
        }
        if($limit)
            $query->setMaxResults($limit);
        if($offset)
            $query->setFirstResult($offset);
        return $query;
    }

    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    public function save($entity)
    {
        $this->entityManager->persist($entity);
    }

    public function findById($id)
    {
        $this->assertClassName();
        return $this->entityManager->find($this->className,$id);
    }

    public function findAll(array $filter=null,array $sort=null,$limit=null,$offset=null)
    {
        $this->assertClassName();
        $query = $this->createQuery('find',$filter,$sort,$limit,$offset);
        return $query->getResultList();
    }

    public function findOne(array $filter=null,array $sort=null,$offset=null)
    {
        $limit = 1;
        $entity = null;
        $results = $this->findAll($filter,$sort,$limit,$offset);
        foreach ($results as $entity) {
            $result = $entity;
        }
        return $entity;
    }

    public function delete($entity)
    {
        $this->entityManager->remove($entity);
    }

    public function deleteById($id)
    {
        $entity = $this->findById($id);
        $this->delete($entity);
    }

    public function deleteAll(array $filter=null)
    {
        throw new Exception\DomainException('This operation is not implemented.');
    }

    public function existsById($id)
    {
        $this->assertClassName();
        $entity = $this->findById($id);
        return ($entity) ? true : false;
    }

    public function count(array $filter=null)
    {
        $query = $this->createQuery('count',$filter);
        return $query->getSingleResult();
    }
}
