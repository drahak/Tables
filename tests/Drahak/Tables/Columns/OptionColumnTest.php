<?php
namespace Tests\Drahak\Tables\Columns;

/**
 * Option column test
 * @author Drahomír Hanák
 */
class OptionColumnTest extends \PHPUnit_Framework_TestCase
{

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $column;

	public function setUp()
	{
		$this->column = $this->getMockForAbstractClass('Drahak\Tables\Columns\OptionColumn', array('test', 'Test'));
	}

	public function testRenderWithOptions()
	{
		$otpions = array('test', 'has', 'processed');
		$this->column->setOptions($otpions);
		$result = $this->column->render('test', array());

		$this->assertEquals('test', $result);
		$this->assertSame($otpions, $this->column->getOptions());
	}

	public function testRenderWithBadOptions()
	{
		$otpions = array('test', 'has', 'failed');
		$this->column->setOptions($otpions);
		$this->setExpectedException('Nette\InvalidArgumentException');
		$this->column->render('processed', array());

		$this->assertSame($otpions, $this->column->getOptions());
	}

	public function testWitoutOptions()
	{
		$otpions = array('test', 'has', 'processed');
		$this->column->setOptions($otpions);
		$this->assertSame($otpions, $this->column->getOptions());

		$this->column->removeOptions();

		$this->assertEquals('test', $this->column->render('test', array()));
	}

}