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
	function isOrderable();

	/**
	 * Format content in this column
	 * @param mixed $value
	 * @param mixed $rowData
	 * @return string
	 */
	function parse($value, $rowData);

}
