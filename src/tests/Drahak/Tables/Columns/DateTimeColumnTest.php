<?php
namespace Tests\Drahak\Tables\Columns;

use Drahak\Tables\Columns\DateTimeColumn;

/**
 * Date time column test
 * @author Drahomír Hanák
 */
class DateTimeColumnTest extends \PHPUnit_Framework_TestCase
{

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $column;

	public function setUp()
	{
		$this->column = new DateTimeColumn('test', 'Test', 'd/m/Y');
	}

	public function testParse()
	{
		$this->assertEquals('25/10/2012', $this->column->parse(strtotime('10/25/2012 20:15:10'), array()));
		$this->assertEquals('25/10/2012', $this->column->parse(new \DateTime('10/25/2012 20:15:10'), array()));
	}

	public function testCustomFromatParse()
	{
		$this->column->setFormat('d.m.Y H:i');
		$this->assertEquals('25.10.2012 10:45', $this->column->parse(new \DateTime('10/25/2012 10:45'), array()));
	}

}