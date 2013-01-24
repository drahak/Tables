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
		if (!$by) {
			throw new \Nette\InvalidArgumentException('IDataSource::order expects column name in argumne #1');
		}
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
		$this->table->where($filter);
	}
}