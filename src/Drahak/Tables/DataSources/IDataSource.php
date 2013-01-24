<?php
namespace Drahak\Tables\DataSources;

/**
 * Table data source interfae
 * @author Drahomír Hanák
 */
interface IDataSource
{

	/**
	 * Returns data
	 */
	public function getData();

	/**
	 * Count columns
	 * @param string $countBy
	 * @return int
	 */
	public function count($countBy = '*');

	/**
	 * Order data
	 * @param string $by
	 * @param boolean $invert
	 */
	public function order($by, $invert);

	/**
	 * Limit data
	 * @param int $limit
	 * @param int $offset
	 */
	public function limit($limit, $offset);

	/**
	 * Filter data
	 * @param array $filter
	 */
	public function filter(array $filter);
}