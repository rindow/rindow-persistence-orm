<?php
namespace Rindow\Persistence\Orm\Criteria;

use Rindow\Persistence\Orm\Exception;
use Rindow\Stdlib\Cache\CacheFactory;

class CriteriaContainer
{
    protected $criteriaBuilder;
    protected $criteriaMapper;
    protected $cache;
    protected $context;

    public function __construct($criteriaMapper=null,$criteriaBuilder=null,$cache=null)
    {
        if($criteriaMapper)
            $this->setCriteriaMapper($criteriaMapper);
        if($criteriaBuilder)
            $this->setCriteriaBuilder($criteriaBuilder);
        if($cache)
            $this->setCache($cache);
    }

    public function setCriteriaMapper($criteriaMapper)
    {
        $this->criteriaMapper = $criteriaMapper;
    }

    public function setCriteriaBuilder($criteriaBuilder)
    {
        $this->criteriaBuilder = $criteriaBuilder;
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    public function getCriteriaMapper()
    {
        if($this->criteriaMapper==null)
            throw new Exception\DomainException('a criteria mapper is not specified.');
        return $this->criteriaMapper;
    }

    public function getCriteriaBuilder()
    {
        return $this->criteriaBuilder;
    }

    public function getContext()
    {
        if($this->context==null)
            throw new Exception\DomainException('a context is not specified.');
        return $this->context;
    }

    public function getCache()
    {
        if($this->cache)
            return $this->cache;
        return $this->cache = CacheFactory::getInstance(__CLASS__);
    }

    public function get($name,$builder)
    {
        $cache = $this->getCache();
        $cb = $this->getCriteriaBuilder();
        $cm = $this->getCriteriaMapper();
        if($cm===null)
            throw new Exception\DomainException('CriteriaMapper is not spacified.');
            
        $cm->setContext($this->getContext());
        return $cache->get($name,null,
            function ($cache,$offset,&$criteria) use ($cb,$cm,$builder) {
                $c = call_user_func($builder,$cb);
                $criteria = $cm->prepare($c);
                return true;
        });
    }
}