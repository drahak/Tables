<?php
namespace Tests\Drahak\Tables\DataSources;

use Drahak\Tables\DataSources\ArrayDataSource;

/**
 * Array data source test
 * @author Drahomír Hanák
 */
class ArrayDataSourceTest extends \PHPUnit_Framework_TestCase
{

	/** @var ArrayDataSource */
	private $dataSource;

	public function setUp()
	{
		$array = array();
		for ($i = 1; $i < 500; $i++) {
			$array[] = array(
				'guid' => $i,
				'name' => \Nette\Utils\Strings::random(10),
				'account' => rand(1, 1000),
				'type' => rand(0, 5)
			);
		}

		$this->dataSource = new ArrayDataSource($array);
	}

	public function testGetData()
	{
		$data = $this->dataSource->getData();
		$this->assertTrue(is_array($data));
		$this->assertEquals(499, count($data));
	}

	public function testCount()
	{
		$this->assertEquals(499, $this->dataSource->count());
	}

	public function testOrder()
	{
		$this->dataSource->limit(5, 0);
		$this->dataSource->order('account', TRUE);
		$data = $this->dataSource->getData();

		$lastAccount = $data[0]['account'];
		foreach ($data as $row) {
			$this->assertTrue($row['account'] <= $lastAccount);
		}
	}

	public function testLimit()
	{
		$this->dataSource->limit(10, 0);
		$this->assertEquals(10, count($this->dataSource->getData()));
		$this->assertEquals(499, count($this->dataSource->getOriginalData()));
	}

	public function testFilter()
	{
		$this->dataSource->limit(10, 0);
		$this->dataSource->filter(array('type' => 5));
		foreach ($this->dataSource->getData() as $value) {
			$this->assertEquals(5, $value['type']);
		}
	}

}