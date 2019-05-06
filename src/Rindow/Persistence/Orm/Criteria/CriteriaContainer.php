<?php
namespace Rindow\Persistence\Orm\Criteria;

use Rindow\Persistence\Orm\Exception;
use Rindow\Stdlib\Cache\ConfigCache\ConfigCacheFactory;

class CriteriaContainer
{
    protected $criteriaBuilder;
    protected $criteriaMapper;
    protected $cache;
    protected $configCacheFactory;
    protected $context;

    public function __construct($criteriaMapper=null,$criteriaBuilder=null,$cache=null,$configCacheFactory=null)
    {
        if($criteriaMapper)
            $this->setCriteriaMapper($criteriaMapper);
        if($criteriaBuilder)
            $this->setCriteriaBuilder($criteriaBuilder);
        if($cache)
            $this->setCache($cache);
        if($configCacheFactory)
            $this->setConfigCacheFactory($configCacheFactory);
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

    public function setConfigCacheFactory($configCacheFactory)
    {
        $this->configCacheFactory = $configCacheFactory;
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
        if($this->configCacheFactory==null)
            $this->configCacheFactory = new ConfigCacheFactory(array('enableCache'=>false));
        $this->cache = $this->configCacheFactory->create(__CLASS__);
        return $this->cache;
    }

    public function get($name,$builder)
    {
        $cache = $this->getCache();
        $cb = $this->getCriteriaBuilder();
        $cm = $this->getCriteriaMapper();
        if($cm===null)
            throw new Exception\DomainException('CriteriaMapper is not spacified.');
            
        $cm->setContext($this->getContext());
        $criteria = $cache->getEx(
            $name,
            function ($cacheKey,$args) {
                list($cb,$cm,$builder) = $args;
                $c = call_user_func($builder,$cb);
                $criteria = $cm->prepare($c);
                return $criteria;
            },
            array($cb,$cm,$builder)
        );
        return $criteria;
    }
}