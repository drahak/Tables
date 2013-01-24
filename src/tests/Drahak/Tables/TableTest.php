<?php
namespace Tests\Drahak\Tables;

use Drahak\Tables\Table;

/**
 * Table test
 * @author Drahomír Hanák
 */
class TableTest extends \PHPUnit_Framework_TestCase
{

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $dataSource;

	/** @var \Drahak\Tables\Table */
	private $table;

	public function setUp()
	{
		$this->dataSource = $this->getMockBuilder('Drahak\Tables\DataSources\DefaultDataSource')
			->disableOriginalConstructor()
			->getMock();
		$this->table = new Table;
	}

	public function testOrderSetter()
	{
		$this->dataSource
			->expects($this->once())
			->method('order')
			->with($this->equalTo('name'), $this->equalTo(Table::ORDER_DESC))
			->will($this->returnSelf());

		$this->table->setDataSource($this->dataSource);
		$this->table->setOrder('name', Table::ORDER_DESC);

		$this->assertEquals($this->table->sort, Table::ORDER_DESC);
		$this->assertEquals($this->table->order, 'name');
	}

	public function testSortSetter()
	{
		$this->dataSource
			->expects($this->once())
			->method('order')
			->with($this->equalTo(NULL), $this->equalTo(Table::ORDER_DESC))
			->will($this->returnSelf());

		$this->table->setDataSource($this->dataSource);
		$this->table->setSort(Table::ORDER_DESC);

		$this->assertEquals($this->table->sort, Table::ORDER_DESC);
	}

	public function testChangeDatasource()
	{
		$this->table->setDataSource($this->dataSource);
		$this->setExpectedException('Nette\InvalidStateException');
		$this->table->setDataSource($this->dataSource);
	}

}