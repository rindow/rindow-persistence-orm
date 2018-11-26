<?php
namespace RindowTest\Persistence\Orm\Criteria\CriteriaBuilderTest;

use PHPUnit\Framework\TestCase;
use Rindow\Persistence\Orm\Criteria\CriteriaBuilder;
use Rindow\Persistence\Orm\Criteria\ComparisonOperator;
use Rindow\Persistence\Orm\Criteria\AggregateFunction;

class Test extends TestCase
{
    public function testPath()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$r);
        $this->assertEquals('FooEntity',$r->getNodeName());
        $this->assertEquals('FooEntity',$r->typeString());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$r->get('field1'));
        $this->assertEquals('FooEntity->field1',$r->get('field1')->typeString());
    }

    public function testParameter()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p = $cb->parameter('integer','p1');
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Parameter',$p);
        $this->assertEquals('integer',$p->getParameterType());
        $this->assertEquals('p1',$p->getName());
    }

    public function testEqualOperator()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p = $cb->parameter('integer','p1');
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$cb->equal($r->get('field1'),$p));
        $this->assertEquals(ComparisonOperator::EQUAL,$cb->equal($r->get('field1'),$p)->getOperator());

        $expressions = $cb->equal($r->get('field1'),$p)->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$expressions[0]);
        $this->assertEquals('FooEntity->field1',$expressions[0]->typeString());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Parameter',$expressions[1]);
        $this->assertEquals('p1',$expressions[1]->getName());
    }

    public function testGeOperator()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p = $cb->parameter('integer','p1');
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$cb->ge($r->get('field1'),$p));
        $this->assertEquals(ComparisonOperator::GREATER_THAN_OR_EQUAL,$cb->ge($r->get('field1'),$p)->getOperator());

        $expressions = $cb->ge($r->get('field1'),$p)->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$expressions[0]);
        $this->assertEquals('FooEntity->field1',$expressions[0]->typeString());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Parameter',$expressions[1]);
        $this->assertEquals('p1',$expressions[1]->getName());
    }

    public function testGtOperator()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p = $cb->parameter('integer','p1');
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$cb->gt($r->get('field1'),$p));
        $this->assertEquals(ComparisonOperator::GREATER_THAN,$cb->gt($r->get('field1'),$p)->getOperator());

        $expressions = $cb->gt($r->get('field1'),$p)->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$expressions[0]);
        $this->assertEquals('FooEntity->field1',$expressions[0]->typeString());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Parameter',$expressions[1]);
        $this->assertEquals('p1',$expressions[1]->getName());
    }

    public function testLeOperator()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p = $cb->parameter('integer','p1');
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$cb->le($r->get('field1'),$p));
        $this->assertEquals(ComparisonOperator::LESS_THAN_OR_EQUAL,$cb->le($r->get('field1'),$p)->getOperator());

        $expressions = $cb->le($r->get('field1'),$p)->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$expressions[0]);
        $this->assertEquals('FooEntity->field1',$expressions[0]->typeString());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Parameter',$expressions[1]);
        $this->assertEquals('p1',$expressions[1]->getName());
    }

    public function testLtOperator()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p = $cb->parameter('integer','p1');
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$cb->lt($r->get('field1'),$p));
        $this->assertEquals(ComparisonOperator::LESS_THAN,$cb->lt($r->get('field1'),$p)->getOperator());

        $expressions = $cb->lt($r->get('field1'),$p)->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$expressions[0]);
        $this->assertEquals('FooEntity->field1',$expressions[0]->typeString());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Parameter',$expressions[1]);
        $this->assertEquals('p1',$expressions[1]->getName());
    }

    public function testAndOperator1()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p1 = $cb->parameter('integer','p1');
        $p2 = $cb->parameter('integer','p2');
        $lt = $cb->lt($r->get('field1'),$p1);
        $gt = $cb->gt($r->get('field1'),$p2);

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$cb->and_($lt,$gt));
        $this->assertEquals(ComparisonOperator::AND_,$cb->and_($lt,$gt)->getOperator());

        $expressions = $cb->and_($lt,$gt)->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$expressions[0]);
        $this->assertEquals(ComparisonOperator::LESS_THAN,$expressions[0]->getOperator());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$expressions[1]);
        $this->assertEquals(ComparisonOperator::GREATER_THAN,$expressions[1]->getOperator());
    }

    public function testAndOperator2()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p1 = $cb->parameter('integer','p1');
        $p2 = $cb->parameter('integer','p2');
        $lt = $cb->lt($r->get('field1'),$p1);
        $gt = $cb->gt($r->get('field1'),$p2);

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$cb->and_(array($lt,$gt)));
        $this->assertEquals(ComparisonOperator::AND_,$cb->and_(array($lt,$gt))->getOperator());

        $expressions = $cb->and_(array($lt,$gt))->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$expressions[0]);
        $this->assertEquals(ComparisonOperator::LESS_THAN,$expressions[0]->getOperator());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$expressions[1]);
        $this->assertEquals(ComparisonOperator::GREATER_THAN,$expressions[1]->getOperator());
    }

    /**
     * @expectedException        Rindow\Persistence\Orm\Exception\InvalidArgumentException
     * @expectedExceptionMessage Must have two expression or more.
     */
    public function testAndOperatorNoExpression()
    {
        $cb = new CriteriaBuilder();

        $cb->and_();
    }

    /**
     * @expectedException        Rindow\Persistence\Orm\Exception\InvalidArgumentException
     * @expectedExceptionMessage Must have two expression or more.
     */
    public function testAndOperatorOneExpression()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p1 = $cb->parameter('integer','p1');
        $lt = $cb->lt($r->get('field1'),$p1);

        $cb->and_($lt);
    }

    public function testOrOperator1()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p1 = $cb->parameter('integer','p1');
        $p2 = $cb->parameter('integer','p2');
        $lt = $cb->lt($r->get('field1'),$p1);
        $gt = $cb->gt($r->get('field1'),$p2);

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$cb->or_($lt,$gt));
        $this->assertEquals(ComparisonOperator::OR_,$cb->or_($lt,$gt)->getOperator());

        $expressions = $cb->or_($lt,$gt)->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$expressions[0]);
        $this->assertEquals(ComparisonOperator::LESS_THAN,$expressions[0]->getOperator());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$expressions[1]);
        $this->assertEquals(ComparisonOperator::GREATER_THAN,$expressions[1]->getOperator());
    }

    public function testOrOperator2()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p1 = $cb->parameter('integer','p1');
        $p2 = $cb->parameter('integer','p2');
        $lt = $cb->lt($r->get('field1'),$p1);
        $gt = $cb->gt($r->get('field1'),$p2);

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$cb->or_(array($lt,$gt)));
        $this->assertEquals(ComparisonOperator::OR_,$cb->or_(array($lt,$gt))->getOperator());

        $expressions = $cb->or_(array($lt,$gt))->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$expressions[0]);
        $this->assertEquals(ComparisonOperator::LESS_THAN,$expressions[0]->getOperator());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$expressions[1]);
        $this->assertEquals(ComparisonOperator::GREATER_THAN,$expressions[1]->getOperator());
    }

    /**
     * @expectedException        Rindow\Persistence\Orm\Exception\InvalidArgumentException
     * @expectedExceptionMessage Must have two expression or more.
     */
    public function testOrOperatorNoExpression()
    {
        $cb = new CriteriaBuilder();

        $cb->or_();
    }

    /**
     * @expectedException        Rindow\Persistence\Orm\Exception\InvalidArgumentException
     * @expectedExceptionMessage Must have two expression or more.
     */
    public function testOrOperatorOneExpression()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p1 = $cb->parameter('integer','p1');
        $lt = $cb->lt($r->get('field1'),$p1);

        $cb->or_($lt);
    }

    public function testNotOperator1()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p1 = $cb->parameter('integer','p1');
        $equal = $cb->equal($r->get('field1'),$p1);
        $this->assertEquals(ComparisonOperator::EQUAL,$equal->getOperator());
        $this->assertFalse($equal->isNegated());

        $this->assertNotEquals(spl_object_hash($equal),spl_object_hash($equal->not()));
        $this->assertEquals(ComparisonOperator::EQUAL,$equal->not()->getOperator());
        $this->assertTrue($equal->not()->isNegated());

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$cb->not($equal));
        $this->assertEquals(ComparisonOperator::EQUAL,$cb->not($equal)->getOperator());
        $this->assertTrue($cb->not($equal)->isNegated());

        $expressions = $cb->not($equal)->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$expressions[0]);
        $this->assertEquals('FooEntity->field1',$expressions[0]->typeString());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Parameter',$expressions[1]);
        $this->assertEquals('p1',$expressions[1]->getName());
    }

    public function testNotOperator2()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $p1 = $cb->parameter('integer','p1');
        $equal = $cb->equal($r->get('field1'),$p1);
        $this->assertEquals(ComparisonOperator::EQUAL,$equal->getOperator());
        $this->assertFalse($equal->isNegated());

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$equal->not());
        $this->assertEquals(ComparisonOperator::EQUAL,$equal->not()->getOperator());
        $this->assertTrue($equal->not()->isNegated());

        $expressions = $equal->not()->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$expressions[0]);
        $this->assertEquals('FooEntity->field1',$expressions[0]->typeString());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Parameter',$expressions[1]);
        $this->assertEquals('p1',$expressions[1]->getName());
    }

    public function testWhere()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');
        $p1 = $cb->parameter('integer','p1');

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\CriteriaQuery',
            $q->where($cb->equal($r->get('field1'),$p1)));
     
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$q->getRestriction());
        $this->assertEquals(ComparisonOperator::EQUAL,$q->getRestriction()->getOperator());
        $expressions = $q->getRestriction()->getExpressions();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$expressions[0]);
        $this->assertEquals('FooEntity->field1',$expressions[0]->typeString());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Parameter',$expressions[1]);
        $this->assertEquals('p1',$expressions[1]->getName());
    }

    public function testOrderSingle()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Order',$cb->asc($r->get('field1')));
        $this->assertTrue($cb->asc($r->get('field1'))->isAscending());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$cb->asc($r->get('field1'))->getExpression());
        $this->assertEquals('FooEntity->field1',$cb->asc($r->get('field1'))->getExpression()->typeString());

        $q->orderBy($cb->desc($r->get('field1')));
        $orders = $q->getOrderList();
        $this->assertCount(1,$orders);
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Order',$orders[0]);
        $this->assertFalse($orders[0]->isAscending());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$orders[0]->getExpression());
        $this->assertEquals('FooEntity->field1',$orders[0]->getExpression()->typeString());
    }

    public function testOrderMulti1()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->orderBy($cb->desc($r->get('field1')),$cb->asc($r->get('field2')));
        $orders = $q->getOrderList();
        $this->assertCount(2,$orders);

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Order',$orders[0]);
        $this->assertFalse($orders[0]->isAscending());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$orders[0]->getExpression());
        $this->assertEquals('FooEntity->field1',$orders[0]->getExpression()->typeString());

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Order',$orders[1]);
        $this->assertTrue($orders[1]->isAscending());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$orders[1]->getExpression());
        $this->assertEquals('FooEntity->field2',$orders[1]->getExpression()->typeString());
    }

    public function testOrderMulti2()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->orderBy(array($cb->desc($r->get('field1')),$cb->asc($r->get('field2'))));
        $orders = $q->getOrderList();
        $this->assertCount(2,$orders);

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Order',$orders[0]);
        $this->assertFalse($orders[0]->isAscending());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$orders[0]->getExpression());
        $this->assertEquals('FooEntity->field1',$orders[0]->getExpression()->typeString());

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Order',$orders[1]);
        $this->assertTrue($orders[1]->isAscending());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$orders[1]->getExpression());
        $this->assertEquals('FooEntity->field2',$orders[1]->getExpression()->typeString());
    }

    public function testGroupingSingle()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->groupBy($r->get('field1'));
        $groups = $q->getGroupList();
        $this->assertCount(1,$groups);
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$groups[0]);
        $this->assertEquals('FooEntity->field1',$groups[0]->typeString());
    }

    public function testGroupingMulti1()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->groupBy($r->get('field1'),$r->get('field2'));
        $groups = $q->getGroupList();
        $this->assertCount(2,$groups);
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$groups[0]);
        $this->assertEquals('FooEntity->field1',$groups[0]->typeString());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$groups[1]);
        $this->assertEquals('FooEntity->field2',$groups[1]->typeString());
    }

    public function testGroupingMulti2()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->groupBy(array($r->get('field1'),$r->get('field2')));
        $groups = $q->getGroupList();
        $this->assertCount(2,$groups);
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$groups[0]);
        $this->assertEquals('FooEntity->field1',$groups[0]->typeString());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$groups[1]);
        $this->assertEquals('FooEntity->field2',$groups[1]->typeString());
    }

    public function testHaving()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->having($cb->gt($r->get('field1'),1));
        $restriction = $q->getGroupRestriction();
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$restriction);
        $this->assertEquals(ComparisonOperator::GREATER_THAN,$restriction->getOperator());
        $expressions = $restriction->getExpressions();
        $this->assertCount(2,$expressions);
        $this->assertEquals('FooEntity->field1',$expressions[0]->typeString());
        $this->assertEquals(1,$expressions[1]->getValue());
    }

    public function testSingleSelection()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->select($r->get('field1')->alias('f'));

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$q->getSelection());
        $this->assertFalse($q->getSelection()->isCompoundSelection());
        $this->assertEquals('FooEntity->field1',$q->getSelection()->typeString());
        $this->assertEquals('f',$q->getSelection()->getAlias());
    }

    public function testCompoundSelection1()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->multiselect($r->get('field1')->alias('f1'),$r->get('field2')->alias('f2'));

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\CompoundSelection',$q->getSelection());
        $this->assertTrue($q->getSelection()->isCompoundSelection());
        $items = $q->getSelection()->getCompoundSelectionItems();
        $this->assertCount(2,$items);
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$items[0]);
        $this->assertEquals('FooEntity->field1',$items[0]->typeString());
        $this->assertEquals('f1',$items[0]->getAlias());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$items[1]);
        $this->assertEquals('FooEntity->field2',$items[1]->typeString());
        $this->assertEquals('f2',$items[1]->getAlias());
    }

    public function testCompoundSelection2()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->multiselect(array($r->get('field1')->alias('f1'),$r->get('field2')->alias('f2')));

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\CompoundSelection',$q->getSelection());
        $this->assertTrue($q->getSelection()->isCompoundSelection());
        $items = $q->getSelection()->getCompoundSelectionItems();
        $this->assertCount(2,$items);
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$items[0]);
        $this->assertEquals('FooEntity->field1',$items[0]->typeString());
        $this->assertEquals('f1',$items[0]->getAlias());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$items[1]);
        $this->assertEquals('FooEntity->field2',$items[1]->typeString());
        $this->assertEquals('f2',$items[1]->getAlias());
    }

    public function testCompoundSelection3()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->select($cb->array_($r->get('field1')->alias('f1'),$r->get('field2')->alias('f2')));

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\CompoundSelection',$q->getSelection());
        $this->assertTrue($q->getSelection()->isCompoundSelection());
        $items = $q->getSelection()->getCompoundSelectionItems();
        $this->assertCount(2,$items);
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$items[0]);
        $this->assertEquals('FooEntity->field1',$items[0]->typeString());
        $this->assertEquals('f1',$items[0]->getAlias());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$items[1]);
        $this->assertEquals('FooEntity->field2',$items[1]->typeString());
        $this->assertEquals('f2',$items[1]->getAlias());
    }

    public function testCompoundSelection4()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->select($cb->tuple($r->get('field1')->alias('f1'),$r->get('field2')->alias('f2')));

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\CompoundSelection',$q->getSelection());
        $this->assertTrue($q->getSelection()->isCompoundSelection());
        $items = $q->getSelection()->getCompoundSelectionItems();
        $this->assertCount(2,$items);
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$items[0]);
        $this->assertEquals('FooEntity->field1',$items[0]->typeString());
        $this->assertEquals('f1',$items[0]->getAlias());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$items[1]);
        $this->assertEquals('FooEntity->field2',$items[1]->typeString());
        $this->assertEquals('f2',$items[1]->getAlias());
    }

    public function testCountFunction()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('RooResult');
        $r = $q->from('FooEntity');

        $q->select($cb->count($r->get('field1')->alias('f'))->alias('c'));

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\AggregateFunction',$q->getSelection());
        $this->assertFalse($q->getSelection()->isCompoundSelection());
        $this->assertEquals(AggregateFunction::COUNT,$q->getSelection()->getOperator());
        $this->assertEquals('c',$q->getSelection()->getAlias());
    }

    public function testCriteriaQuery()
    {
        $cb = new CriteriaBuilder();
        $q = $cb->createQuery('FooResult');
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\CriteriaQuery',$q);

        $r = $q->from('FooEntity')->alias('p');
        $p1 = $cb->parameter('integer','p1');
        $q->select($r)
            ->where($cb->equal($r->get('field1'),$p1))
            ->orderBy($cb->desc($r->get('field2')));

        $this->assertEquals('FooResult',$q->getResultType());

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$q->getRoots());
        $this->assertEquals('FooEntity',$q->getRoots()->typeString());

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Path',$q->getSelection());
        $this->assertEquals('FooEntity',$q->getSelection()->typeString());

        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\ComparisonOperator',$q->getRestriction());
        $this->assertEquals(ComparisonOperator::EQUAL,$q->getRestriction()->getOperator());

        $this->assertCount(1,$q->getOrderList());
        $this->assertInstanceOf('Rindow\Persistence\Orm\Criteria\Order',current($q->getOrderList()));
        $this->assertEquals('FooEntity->field2',current($q->getOrderList())->getExpression()->typeString());
        $this->assertFalse(current($q->getOrderList())->isAscending());
    }
}