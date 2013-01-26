<?php
namespace Tests\Drahak\Tables\Columns;

use Drahak\Tables\Columns\Column,
	Nette\Utils\Html;

/**
 * Column test
 * @author Drahomír Hanák
 */
class ColumnTest extends \PHPUnit_Framework_TestCase
{

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $column;

	public function setUp()
	{
		$this->column = $this->getMockForAbstractClass('Drahak\Tables\Columns\Column', array('test', 'Test'));
	}

	public function testCreation()
	{
		$this->assertEquals('test', $this->column->name);
		$this->assertInstanceOf('Nette\Utils\Html', $this->column->getLabelPrototype());
		$this->assertEquals('Test', $this->column->getLabelPrototype()->getText());
		$this->assertInstanceOf('Nette\Utils\Html', $this->column->getCellPrototype());
	}

	public function testRender()
	{
		$result = $this->column->render(array('testing', 'is', 'awesome'), array());
		$this->assertEquals('testing, is, awesome', $result);
	}

	public function testRenderWithRenderer()
	{
		$test = $this;
		$this->column->setRenderer(function($value, $rowData, $cell) use($test) {
			$test->assertTrue(is_string($value));
			$test->assertInstanceOf('stdClass', $rowData);
			$test->assertInstanceOf('Nette\Utils\Html', $cell);
			return \Nette\Utils\Strings::upper($value);
		});

		$result = $this->column->render(array('testing', 'is', 'awesome'), array());
		$this->assertEquals('TESTING, IS, AWESOME', $result);
	}

	public function testSortable()
	{
		$this->column->setSortable(TRUE);
		$this->assertTrue($this->column->isSortable());
	}

}