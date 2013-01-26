<?php
namespace Drahak\Tables\Columns;

/**
 * IColumn
 * @author Drahomír Hanák
 */
interface IColumn
{

	/**
	 * Can table be ordered by this column?
	 * @return boolean
	 */
	function isSortable();

	/**
	 * Set this table sortable of not
	 * @param bool $sortable
	 * @return IColumn
	 * @throws \Nette\NotSupportedException when sort by this column is no supported
	 */
	function setSortable($sortable);

	/**
	 * Format content in this column
	 * @param mixed $value
	 * @param mixed $rowData
	 * @return string
	 */
	function render($value, $rowData);

}
