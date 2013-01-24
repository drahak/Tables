<?php
namespace Tests\Drahak\Tables\Columns;

use Drahak\Tables\Columns\TextColumn;

/**
 * Text column test
 * @author Drahomír Hanák
 */
class TextColumnTest extends \PHPUnit_Framework_TestCase
{

	/** @var TextColumn */
	private $column;

	public function setUp()
	{
		$this->column = new TextColumn('test', 'Test', 5);
	}

	public function testParseMaxLength()
	{
		$result = $this->column->parse('Testing is awesome', array());
		$this->assertEquals("Test\xE2\x80\xA6", $result);
	}

	public function testParseNoTruncate()
	{
		$this->column->setMaxLength(0);
		$result = $this->column->parse('Testing is awesome', array());
		$this->assertEquals('Testing is awesome', $result);
	}

}