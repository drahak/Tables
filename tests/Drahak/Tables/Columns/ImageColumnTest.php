<?php
namespace Tests\Drahak\Tables\Columns;

use Drahak\Tables\Columns\ImageColumn;

/**
 * Image column test
 * @author Drahomír Hanák
 */
class ImageColumnTest extends \PHPUnit_Framework_TestCase
{

	/** @var ImageColumn */
	private $column;

	public function setUp()
	{
		$this->column = new ImageColumn('test', 'Test', '');
	}

	public function testGetFilePath()
	{
		$this->column->setImagePath(__DIR__);
		$this->assertEquals(__DIR__, $this->column->getImagePath());

		$method = new \ReflectionMethod('Drahak\Tables\Columns\ImageColumn', 'getFilePath');
		$method->setAccessible(TRUE);
		$path = $method->invoke($this->column, 'test');
		$this->assertEquals(__DIR__ . DIRECTORY_SEPARATOR . 'test.png', $path);
	}

	public function testGetNonExistingFilePath()
	{
		$this->setExpectedException('Nette\FileNotFoundException');
		$method = new \ReflectionMethod('Drahak\Tables\Columns\ImageColumn', 'getFilePath');
		$method->setAccessible(TRUE);
		$method->invoke($this->column, 'test');
	}

	public function testRender()
	{
		$this->column->setImagePath(__DIR__);
		$result = $this->column->render('test', array());
		$this->assertInstanceOf('Nette\Utils\Html', $result);
		$this->assertEquals('Test', $result->alt);
		$this->assertEquals(__DIR__ . DIRECTORY_SEPARATOR . 'test.png', $result->src);
	}

}