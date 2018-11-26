<?php
namespace RindowTest\Persistence\Orm\Criteria\CriteriaContainerTest;

use PHPUnit\Framework\TestCase;
use Rindow\Container\ModuleManager;
use Rindow\Persistence\Orm\Criteria\CriteriaMapper;
use Rindow\Persistence\Orm\Criteria\PreparedCriteria;
use Rindow\Persistence\Orm\Criteria\CriteriaBuilder;

class TestPreparedCriteria implements PreparedCriteria
{
    protected $q;

    public function __construct($q)
    {
        $this->q = $q;
    }

    public function getCriteria()
    {
        return $this->q;
    }
    public function getEntityClass()
    {}
}

class TestEntityManager
{

} 
class TestCriteriaMapper implements CriteriaMapper
{
    protected $entityManager;
    public function prepare(/* CriteriaQuery */$query,$resultClass=null)
    {
        if(!($this->entityManager instanceof TestEntityManager))
            throw new \Exception('Invalid EntityManager!!');
            
        return new TestPreparedCriteria($query);
    }
    public function setContext($context)
    {
        $this->entityManager = $context;
    }
}

class Test extends TestCase
{
    public function setup()
    {
        \Rindow\Stdlib\Cache\CacheFactory::clearCache();
    }

    public function getConfig()
    {
        $config = array(
            'module_manager' => array(
                'modules' => array(
                ),
            ),
            'container' => array(
                'aliases' => array(
                    'CriteriaContainer' => __NAMESPACE__.'\TestCriteriaContainer',
                ),
                //'debug' => true,
                'components' => array(
                    __NAMESPACE__.'\TestCriteriaContainer' => array(
                        'class' => 'Rindow\Persistence\Orm\Criteria\CriteriaContainer',
                        'properties' => array(
                            'criteriaBuilder' => array('ref'=>__NAMESPACE__.'\TestCriteriaBuilder'),
                            'criteriaMapper'  => array('ref'=>__NAMESPACE__.'\TestCriteriaMapper'),
                            'context'   => array('ref'=>__NAMESPACE__.'\TestEntityManager'),
                        ),
                    ),
                    __NAMESPACE__.'\TestCriteriaBuilder' => array(
                        'class' => 'Rindow\Persistence\Orm\Criteria\CriteriaBuilder',
                    ),
                    __NAMESPACE__.'\TestCriteriaMapper'=>array(
                    ),
                    __NAMESPACE__.'\TestEntityManager'=>array(
                    ),
                ),
            ),
        );
        return $config;
    }

    public function testSimple()
    {
        $count = 0;
        $mm = new ModuleManager($this->getConfig());
        $cc = $mm->getServiceLocator()->get('CriteriaContainer');

        for ($i=0; $i < 10; $i++) { 
            $criteria = $cc->get(__METHOD__,function ($cb) use (&$count){
                if(!($cb instanceof CriteriaBuilder))
                    throw new \Exception('Invalid CriteriaBuilder!!');
                    
                $cq = $cb->createQuery();
                $cq->select($cq->from('TestEntity'));
                $count++;
                return $cq;
            });
        }

        $this->assertInstanceOf(__NAMESPACE__.'\TestPreparedCriteria',$criteria);
        $this->assertEquals('TestEntity',$criteria->getCriteria()->getRoots()->getNodeName());
        $this->assertEquals(1,$count);
    }
}