<?php
namespace Drahak\Tables\DataSources;

use Nette\Utils\Strings;

/**
 * Array data source - always use limit!
 * @author Drahomír Hanák
 */
class ArrayDataSource extends \Nette\Object implements IDataSource
{

	/** @var array */
	private $originalData = array();

	/** @var array */
	private $data = array();

	public function __construct(array $data)
	{
		$this->originalData = $data;
	}

	/**
	 * Returns data
	 * @return array
	 */
	public function getData()
	{
		return $this->data ? $this->data : $this->originalData;
	}

	/**
	 * Returns original data
	 * @return array
	 */
	public function getOriginalData()
	{
		return $this->originalData;
	}

	/**
	 * Count columns
	 * @param string $countBy
	 * @return int
	 */
	public function count($countBy = '*')
	{
		return count($this->getData());
	}

	/**
	 * Order data
	 * @param string $by
	 * @param boolean $invert
	 */
	public function order($by, $invert)
	{
		$invert = $invert ? -1 : 1;
		$data = $this->getData();
		usort($data, function($a, $b) use($by, $invert) {
			if ($a[$by] === $b[$by]) {
				return 0;
			}
			$a = $a[$by];
			$b = $b[$by];
			$condition = is_string($a) ? ord($a) < ord($b) : $a < $b;
			return $condition ? -1*$invert : 1*$invert;
		});
		$this->data = $data;
	}

	/**
	 * Limit data
	 * @param int $limit
	 * @param int $offset
	 */
	public function limit($limit, $offset)
	{
		$data = $this->originalData;
		$this->data = array_slice($data, $offset, $limit);
	}

	/**
	 * Filter data
	 * @param array $filter
	 */
	public function filter(array $filter)
	{
		$data = $this->getData();
		$this->data = array_filter($data, function($value) use($filter) {
			foreach ($filter as $key => $val) {
				if ($value[$key] != $val)
					return FALSE;
			}
			return TRUE;
		});
	}

}