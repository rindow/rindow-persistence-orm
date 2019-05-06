<?php
namespace RindowTest\Persistence\Orm\Repository\OrmRepositoryTest;

use PHPUnit\Framework\TestCase;
use Rindow\Persistence\Orm\Repository\OrmRepository;
use Rindow\Persistence\Orm\EntityManager;
use Rindow\Persistence\Orm\Query;
use Rindow\Database\Dao\Support\QueryBuilder;
use Rindow\Container\ModuleManager;

class TestEntityManager implements EntityManager
{
	protected $datas = array();
	protected $lastQueryName;
	protected $lastResultClass;
	protected $lastQuery;
	protected $logger;

	public function setLogger($logger)
	{
		$this->logger = $logger;
	}

	public function setData(array $datas)
	{
		$this->datas = $datas;
	}

	public function getData()
	{
		return $this->datas;
	}

	public function logging($text)
	{
		if($this->logger==null)
			return;
		$this->logger->logging($text);
	}

    public function find(/*String*/ $entityClass, $primaryKey, $lockMode=null, array $properties=null)
    {
    	$this->logging('find');
    	if($entityClass != 'Foo')
    		throw new \Exception('Invalid EntityClass:'.$entityClass);
		if(!isset($this->datas[$primaryKey]))
			return null;
    	return $this->datas[$primaryKey];
    }

    public function contains($entity)
    { throw new \Exception('Illegal Operation'); }

    public function remove($entity)
    {
    	$this->logging('remove');
    	$id = $entity->id;
    	unset($this->datas[$id]);
    }

    public function persist($entity)
    {
    	$this->logging('persist');
    	$this->datas[$entity->id] = $entity;
    }

    public function detach($entity)
    { throw new \Exception('Illegal Operation'); }

    public function merge($entity)
    { throw new \Exception('Illegal Operation'); }

    public function clear()
    { throw new \Exception('Illegal Operation'); }

    public function flush()
    { throw new \Exception('Illegal Operation'); }

    public function createQuery($query, /*String*/ $resultClass=null)
    { throw new \Exception('Illegal Operation'); }

    public function createNamedQuery(/*String*/ $name, /*String*/ $resultClass=null)
    {
    	$this->logging('createNamedQuery');
    	$query = new TestNamedQuery($this->datas,$name,$resultClass);
    	$this->lastQueryName = $name;
    	$this->lastResultClass = $resultClass;
    	$this->lastQuery = $query;
    	return $query;
    }
    public function getLastQueryName()
    {
    	$this->logging('getLastQueryName');
    	return $this->lastQueryName;
    }
    public function getLastResultClass()
    {
    	$this->logging('getLastResultClass');
    	return $this->lastResultClass;
    }
    public function getLastQuery()
    {
    	$this->logging('getLastQuery');
    	return $this->lastQuery;
    }

    public function close()
    { throw new \Exception('Illegal Operation'); }

    public function getCriteriaBuilder()
    { throw new \Exception('Illegal Operation'); }
}

class TestNamedQuery implements Query
{
	protected $datas;
	protected $name;
	protected $resultClass;
	protected $parameters = array();
	protected $maxResult;
	protected $startPosition;
	public function __construct($datas,$name,$resultClass)
	{
		$this->datas = $datas;
		$this->name = $name;
		$this->resultClass = $resultClass;
	}
    public function getFirstResult()
    {
    	return $this->startPosition;
    }
    public function getLockMode()
    { throw new \Exception('Illegal Operation'); }
    public function getMaxResults()
    {
    	return $this->maxResult;
    }
    public function getParameters()
    {
    	return $this->parameters;
    }
    public function getParameterValue($name)
    { throw new \Exception('Illegal Operation'); }
    public function getResultList()
    {
    	return $this->datas;
    }
    public function getSingleResult()
    {
    	if(strpos($this->name, 'count')===0)
    		return count($this->datas);
    	$results = $this->datas;
    	return reset($results);
    }
    public function setFirstResult($startPosition)
    {
    	$this->startPosition = $startPosition;
    }
    public function setLockMode($lockMode)
    { throw new \Exception('Illegal Operation'); }
    public function setMaxResults($maxResult)
    {
    	$this->maxResult = $maxResult;
    }
    public function setParameter($name, $value)
    {
    	$this->parameters[$name] = $value;
    }
    public function unwrap($class=null)
    { throw new \Exception('Illegal Operation'); }
}

class TestLogger
{
	protected $log = array();

	public function getLog()
	{
		return $this->log;
	}
	public function logging($text)
	{
		$this->log[] = $text;
	}
	public function debug($text)
	{
		$this->logging($text);
	}
}

class TestResourceModule
{
    public function getConfig()
    {
        return array(
            'aop' => array(
                'plugins' => array(
                    'Rindow\\Transaction\\Support\\AnnotationHandler'=>true,
                ),
                'transaction' => array(
                    'defaultTransactionManager' => __NAMESPACE__.'\TestTransactionManager',
                    'managers' => array(
                        __NAMESPACE__.'\TestTransactionManager' => array(
                            'transactionManager' => __NAMESPACE__.'\TestTransactionManager',
                            'advisorClass' => 'Rindow\\Transaction\\Support\\TransactionAdvisor',
                        ),
                    ),
                ),
            ),
            'container' => array(
                'aliases' => array(
                	'Logger' => __NAMESPACE__.'\TestLogger',
                ),
                'components' => array(
                    __NAMESPACE__.'\TestTransactionManager' => array(
                        'class'=>'Rindow\\Transaction\\Local\\TransactionManager',
                        'properties' => array(
                            //'useSavepointForNestedTransaction' => array('value'=>true),
                            // === for debug options ===
                            'debug' => array('value'=>true),
                            'logger' => array('ref'=>'Logger'),
                        ),
                        'proxy' => 'disable',
                    ),
                ),
			),
    	);
	}
}


class Test extends TestCase
{
    public function setUp()
    {
    }

	public function getRepository()
	{
		$entityManager = new TestEntityManager();
		$className = 'Foo';
		$queryBuilder = new QueryBuilder();
		$repository = new OrmRepository($entityManager,$className,$queryBuilder);
		return array($repository,$entityManager);
	}

	public function testSave()
	{
		list($repository,$testBox) = $this->getRepository();
		$entity = (object)array('id'=>'id1');
		$repository->save($entity);

		$results = $testBox->getData();
		foreach ($results as $entity) {
			$datas[] = $entity->id;
		}
		$this->assertEquals(array('id1'),$datas);
	}

	public function testFindById()
	{
		list($repository,$testBox) = $this->getRepository();
		$testBox->setData(array(
			'id1'=>(object)array('id'=>'id1'),
			'id2'=>(object)array('id'=>'id2'),
		));

		$entity = $repository->findById('id1');
		$this->assertEquals('id1',$entity->id);
	}

	public function testFindAll()
	{
		list($repository,$testBox) = $this->getRepository();
		$testBox->setData(array(
			'id1'=>(object)array('id'=>'id1','dept'=>'A'),
			'id2'=>(object)array('id'=>'id2','dept'=>'A'),
		));

		// All
		$results = $repository->findAll();
		$datas = array();
		foreach ($results as $entity) {
			$datas[] = $entity->id;
		}
		$this->assertEquals(array('id1','id2'),$datas);
		$this->assertEquals('findAll',$testBox->getLastQueryName());
		$this->assertEquals('Foo',$testBox->getLastResultClass());
		$this->assertEquals(array(),$testBox->getLastQuery()->getParameters());

		// Filter
		$results = $repository->findAll(array('dept'=>'A'));
		$datas = array();
		foreach ($results as $entity) {
			$datas[] = $entity->id;
		}
		$this->assertEquals('findByDeptEq',$testBox->getLastQueryName());
		$this->assertEquals('Foo',$testBox->getLastResultClass());
		$this->assertEquals(array('dept_0'=>'A'),$testBox->getLastQuery()->getParameters());

		// Sort
		$results = $repository->findAll(array('dept'=>'A'),array('id'=>1));
		$datas = array();
		foreach ($results as $entity) {
			$datas[] = $entity->id;
		}
		$this->assertEquals('findByDeptEqOrderByIdAsc',$testBox->getLastQueryName());
		$this->assertEquals('Foo',$testBox->getLastResultClass());
		$this->assertEquals(array('dept_0'=>'A'),$testBox->getLastQuery()->getParameters());

		// limit and offset
		$results = $repository->findAll(array('dept'=>'A'),array('id'=>1),100,10);
		$datas = array();
		foreach ($results as $entity) {
			$datas[] = $entity->id;
		}
		$this->assertEquals('findByDeptEqOrderByIdAsc',$testBox->getLastQueryName());
		$this->assertEquals('Foo',$testBox->getLastResultClass());
		$this->assertEquals(array('dept_0'=>'A'),$testBox->getLastQuery()->getParameters());
		$this->assertEquals(100,$testBox->getLastQuery()->getMaxResults());
		$this->assertEquals(10,$testBox->getLastQuery()->getFirstResult());
	}

	public function testFindOne()
	{
		list($repository,$testBox) = $this->getRepository();
		$testBox->setData(array(
			'id1'=>(object)array('id'=>'id1','dept'=>'A'),
			'id2'=>(object)array('id'=>'id2','dept'=>'A'),
		));

		$entity = $repository->findOne(array('dept'=>'A'),array('id'=>1),10);
		$this->assertEquals('id2',$entity->id);
		$this->assertEquals('findByDeptEqOrderByIdAsc',$testBox->getLastQueryName());
		$this->assertEquals('Foo',$testBox->getLastResultClass());
		$this->assertEquals(array('dept_0'=>'A'),$testBox->getLastQuery()->getParameters());
		$this->assertEquals(1,$testBox->getLastQuery()->getMaxResults());
		$this->assertEquals(10,$testBox->getLastQuery()->getFirstResult());
	}

	public function testDelete()
	{
		list($repository,$testBox) = $this->getRepository();
		$testBox->setData(array(
			'id1'=>(object)array('id'=>'id1','dept'=>'A'),
			'id2'=>(object)array('id'=>'id2','dept'=>'A'),
		));

		$entity = (object)array('id'=>'id1','dept'=>'A');
		$repository->delete($entity);
		$results = $testBox->getData();
		foreach ($results as $entity) {
			$datas[] = $entity->id;
		}
		$this->assertEquals(array('id2'),$datas);
	}

	public function testDeleteById()
	{
		list($repository,$testBox) = $this->getRepository();
		$testBox->setData(array(
			'id1'=>(object)array('id'=>'id1','dept'=>'A'),
			'id2'=>(object)array('id'=>'id2','dept'=>'A'),
		));

		$repository->deleteById('id1');
		$results = $testBox->getData();
		foreach ($results as $entity) {
			$datas[] = $entity->id;
		}
		$this->assertEquals(array('id2'),$datas);
	}

    /**
     * @expectedException        Rindow\Database\Dao\Exception\DomainException
     * @expectedExceptionMessage This operation is not implemented.
     */
	public function testDeleteAll()
	{
		list($repository,$testBox) = $this->getRepository();
		$repository->deleteAll();
	}

	public function testExistsById()
	{
		list($repository,$testBox) = $this->getRepository();
		$testBox->setData(array(
			'id1'=>(object)array('id'=>'id1','dept'=>'A'),
			'id2'=>(object)array('id'=>'id2','dept'=>'A'),
		));

		$this->assertTrue($repository->existsById('id1'));
		$this->assertFalse($repository->existsById('idx'));
	}

	public function testCount()
	{
		list($repository,$testBox) = $this->getRepository();
		$testBox->setData(array(
			'id1'=>(object)array('id'=>'id1','dept'=>'A'),
			'id2'=>(object)array('id'=>'id2','dept'=>'A'),
		));

		// All
		$this->assertEquals(2,$repository->count());
		$this->assertEquals('countAll',$testBox->getLastQueryName());
		$this->assertEquals('Foo',$testBox->getLastResultClass());
		$this->assertEquals(array(),$testBox->getLastQuery()->getParameters());

		// Filter
		$this->assertEquals(2,$repository->count(array('dept'=>'A')));
		$this->assertEquals('countByDeptEq',$testBox->getLastQueryName());
		$this->assertEquals('Foo',$testBox->getLastResultClass());
		$this->assertEquals(array('dept_0'=>'A'),$testBox->getLastQuery()->getParameters());
	}

	public function testWithTransactionOnModule()
	{
		$config = array(
			'module_manager' => array(
				'modules' => array(
					'Rindow\Aop\Module' => true,
					'Rindow\Transaction\Local\Module' => true,
					'Rindow\Persistence\Orm\Module' => true,
					__NAMESPACE__.'\TestResourceModule'=>true,
				),
                'enableCache' => false,
			),
            'container' => array(
                'aliases' => array(
                    'Rindow\\Persistence\\Orm\\Repository\\DefaultEntityManager' => __NAMESPACE__.'\TestEntityManager',
                ),
                'components' => array(
                	__NAMESPACE__.'\TestEntityManager'=>array(
                        'properties' => array(
                			'logger'=>__NAMESPACE__.'\TestLogger'
	                    ),
                	),

                	__NAMESPACE__.'\TestRepository'=>array(
                		'parent'=>'Rindow\\Persistence\\Orm\\Repository\\AbstractOrmRepository',
                        'properties' => array(
	                        'className' => array('value'=>'Foo'),
	                    ),
                	),
                	__NAMESPACE__.'\TestLogger'=>array(
                	),
                ),
            ),
		);
		$mm = new ModuleManager($config);
		$repository = $mm->getServiceLocator()->get(__NAMESPACE__.'\TestRepository');
		$logger = $mm->getServiceLocator()->get(__NAMESPACE__.'\TestLogger');
		$entityManager = $mm->getServiceLocator()->get(__NAMESPACE__.'\TestEntityManager');

		$entity = (object)array('id'=>'id1');
		$logger->debug('==save==');
		$repository->save($entity);

		$logger->debug('==findById==');
		$repository->findById('id1');

		$logger->debug('==findAll==');
		$repository->findAll(array('id'=>'id1'));

		$logger->debug('==findOne==');
		$repository->findOne(array('id'=>'id1'));

		$logger->debug('==delete==');
		$entityManager->setData(array('id1'=>$entity));
		$repository->delete($entity);

		$logger->debug('==deleteById==');
		$entityManager->setData(array('id1'=>$entity));
		$repository->deleteById('id1');

		//$logger->debug('==deleteAll==');
		//$entityManager->setData(array('id1'=>$entity));
		//$repository->deleteAll(array('id'=>'id1'));

		$logger->debug('==existsById==');
		$entityManager->setData(array('id1'=>$entity));
		$repository->existsById('id1');

		$logger->debug('==count==');
		$entityManager->setData(array('id1'=>$entity));
		$repository->count(array('id'=>'id1'));

		$logger->debug('==getQueryBuilder==');
		$repository->getQueryBuilder();

		$logger->debug('==setClassName==');
		$repository->setClassName('Foo');

		$logger->debug('==setEntityManager==');
		$repository->setEntityManager($mm->getServiceLocator()->get(__NAMESPACE__.'\TestEntityManager'));

		$result = array(
   			"==save==",
   			"begin transaction.",
   			"persist",
   			"commiting transaction.",
   			"execute the before completion.",
   			"execute the after completion.",
   			"success to commit transaction.",
   			"==findById==",
   			"begin transaction.",
   			"find",
   			"commiting transaction.",
   			"execute the before completion.",
   			"execute the after completion.",
   			"success to commit transaction.",
   			"==findAll==",
   			"begin transaction.",
   			"createNamedQuery",
   			"commiting transaction.",
   			"execute the before completion.",
   			"execute the after completion.",
   			"success to commit transaction.",
   			"==findOne==",
   			"begin transaction.",
   			"createNamedQuery",
   			"commiting transaction.",
   			"execute the before completion.",
   			"execute the after completion.",
   			"success to commit transaction.",
   			"==delete==",
   			"begin transaction.",
   			"remove",
   			"commiting transaction.",
   			"execute the before completion.",
   			"execute the after completion.",
   			"success to commit transaction.",
   			"==deleteById==",
   			"begin transaction.",
   			"find",
   			"remove",
   			"commiting transaction.",
   			"execute the before completion.",
   			"execute the after completion.",
   			"success to commit transaction.",
   			//"==deleteAll==",
   			//"begin transaction.",
   			//"find",
   			//"commiting transaction.",
   			//"execute the before completion.",
   			//"execute the after completion.",
   			//"success to commit transaction.",
   			"==existsById==",
   			"begin transaction.",
   			"find",
   			"commiting transaction.",
   			"execute the before completion.",
   			"execute the after completion.",
   			"success to commit transaction.",
   			"==count==",
   			"begin transaction.",
   			"createNamedQuery",
   			"commiting transaction.",
   			"execute the before completion.",
   			"execute the after completion.",
   			"success to commit transaction.",
   			"==getQueryBuilder==",
   			"==setClassName==",
   			"==setEntityManager==",
   		);
   		$this->assertEquals($result,$logger->getLog());
	}
}