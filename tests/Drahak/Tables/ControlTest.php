<?php
namespace Tests\Drahak\Tables;

use Drahak\Tables\Control;

/**
 * Table Container test
 * @author Drahomír Hanák
 */
class ControlTest extends \PHPUnit_Framework_TestCase
{

	/** @var Control */
	private $control;

	public function setUp()
	{
		$this->control = new Control;
	}

	private function createTextColumn()
	{
		return $this->control->addText('test', 'Test name', 100);
	}

	public function testAddTextColumn()
	{
		$column = $this->createTextColumn();
		$this->assertInstanceOf('Drahak\Tables\Columns\TextColumn', $column);
		$this->assertContains($column, $this->control->getColumns());
	}

	public function testAddDateTimeColumn()
	{
		$column = $this->control->addDateTime('test', 'Test name', 'H:i:s');
		$this->assertInstanceOf('Drahak\Tables\Columns\DateTimeColumn', $column);
		$this->assertContains($column, $this->control->getColumns());
	}

	public function testTableArrayAccess()
	{
		$column = $this->createTextColumn();
		$this->assertEquals($this->control['test'], $column);
		$this->assertTrue(isset($this->control['test']));
		unset($this->control['test']);
		$this->assertFalse(isset($this->control['test']));
	}

	public function testGetColumns()
	{
		$column = $this->createTextColumn();
		$columns = $this->control->getColumns();

		$this->assertContains($column, $columns);
		$this->assertEquals(1, count($columns));
	}

	public function testNoTable()
	{
		$this->setExpectedException('Nette\InvalidStateException');
		$this->control->getTable(TRUE);
	}

}