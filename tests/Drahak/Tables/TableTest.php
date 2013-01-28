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

		$this->table->addText('name', 'Name');

		$this->table->setDataSource($this->dataSource);
		$this->table->setOrder('name', Table::ORDER_DESC);

		$this->assertEquals($this->table->sort, Table::ORDER_DESC);
		$this->assertEquals($this->table->order, 'name');
	}

	public function testOrderByUndefinedColumn()
	{
		$this->setExpectedException('Nette\InvalidArgumentException');

		$this->table->setDataSource($this->dataSource);
		$this->table->setOrder('undefined', Table::ORDER_DESC);
	}

	public function testDisabledSortSetter()
	{
		$this->dataSource
			->expects($this->never())
			->method('order');

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

	public function testEnableSort()
	{
		$this->table->addText('name', 'Name');
		$this->table->addText('age', 'Age');
		$this->table['name']->sortable = FALSE;
		$this->table['age']->sortable = FALSE;

		$this->table->enableSort();

		$this->assertEquals(TRUE, $this->table['name']->isSortable());
		$this->assertEquals(TRUE, $this->table['age']->isSortable());
	}

	public function testDisableSort()
	{
		$this->table->addText('name', 'Name');
		$this->table->addText('age', 'Age');

		$this->table->disableSort();

		$this->assertEquals(FALSE, $this->table['name']->isSortable());
		$this->assertEquals(FALSE, $this->table['age']->isSortable());
	}

	public function testSetLimit()
	{
		$this->dataSource
			->expects($this->once())
			->method('limit')
			->with(10, 0);

		$this->table->setDataSource($this->dataSource);
		$this->table->setLimit(10, 0);
	}

	public function testGetRows()
	{
		$this->dataSource
			->expects($this->once())
			->method('getData')
			->will($this->returnValue(array()));

		$this->table->setDataSource($this->dataSource);
		$this->assertEquals(array(), $this->table->getRows());
	}

	public function testElement()
	{
		$table = $this->table->getElementPrototype();
		$this->assertInstanceOf('Nette\Utils\Html', $table);
		$this->assertEquals('table', $table->getName());
	}

	public function testPaginator()
	{
		$paginator = $this->getMock('Nette\Utils\Paginator');

		// Data source expectation
		$this->table->setDataSource($this->dataSource);
		$this->dataSource
			->expects($this->once())
			->method('limit')
			->with(10, 0);

		// Paginator expectation
		$paginator->expects($this->once())
			->method('getItemsPerPage')
			->will($this->returnValue(10));

		$paginator->expects($this->once())
			->method('getOffset')
			->will($this->returnValue(0));

		$this->table->setPaginator($paginator);
		$this->assertInstanceOf('Nette\Utils\Paginator', $this->table->getPaginator());
		$this->assertSame($paginator, $this->table->getPaginator());
	}

}