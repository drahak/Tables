<?php
namespace Tests\Drahak\Tables\DataSources;

use Drahak\Tables\DataSources\DefaultDataSource;

/**
 * Default (Nette\Database) data source test
 * @author Drahomír Hanák
 */
class DefaultDataSourceTest extends \PHPUnit_Framework_TestCase
{

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $table;

	/** @var DefaultDataSource */
	private $dataSource;

	public function setUp()
	{
		$this->table = $this->getMockBuilder('Nette\Database\Table\Selection')
			->disableOriginalConstructor()
			->getMock();
		$this->dataSource = new DefaultDataSource($this->table);
	}

	public function testGetData()
	{
		$this->assertEquals($this->table, $this->dataSource->getData());
	}

	public function testCount()
	{
		$this->table
			->expects($this->once())
			->method('count')
			->will('*')
			->will($this->returnValue(5));

		$this->assertEquals(5, $this->dataSource->count());
	}

	public function testOrder()
	{
		$this->table
			->expects($this->once())
			->method('order')
			->with('test ASC');

		$this->dataSource->order('test', FALSE);
	}

	public function testLimit()
	{
		$this->table
			->expects($this->once())
			->method('limit')
			->with(10, 5);

		$this->dataSource->limit(10, 5);
	}

	public function testFilter()
	{
		$this->table
			->expects($this->once())
			->method('where')
			->with(array('name' => 'test'));

		$this->dataSource->filter(array('name' => 'test'));
	}

}