<?php
namespace Drahak\Tables\DataSources;

use Nette\Database\Table\Selection;

/**
 * Nette database data source
 * @author Drahomír Hanák
 */
class DefaultDataSource extends \Nette\Object implements IDataSource
{

	/** @var \Nette\Database\Table\Selection */
	private $table;

	/**
	 * Create data source from Table\Selection
	 * @param \Nette\Database\Table\Selection $table
	 */
	public function __construct(Selection $table)
	{
		$this->table = $table;
	}

	/**
	 * Returns data
	 */
	public function getData()
	{
		return $this->table;
	}

	/**
	 * Count columns
	 * @param string $countBy
	 * @return int
	 */
	public function count($countBy = '*')
	{
		return $this->table->count($countBy);
	}

	/**
	 * Order data
	 * @param string $by
	 * @param bool $invert
	 * @throws \Nette\InvalidArgumentException
	 */
	public function order($by, $invert)
	{
		$this->table->order($by . ' ' . ($invert ? 'DESC' : 'ASC'));
	}

	/**
	 * Limit data
	 * @param int $limit
	 * @param int $offset
	 */
	public function limit($limit, $offset)
	{
		$this->table->limit($limit, $offset);
	}

	/**
	 * Filter data
	 * @param array $filter
	 */
	public function filter(array $filter)
	{
		foreach ($filter as $key => $value) {
			$value = is_numeric($value) ? $value : '%' . $value . '%';
			$this->table->where($key . ' LIKE ?', $value);
		}
	}
}