<?php
namespace Drahak\Tables;

/**
 * Table container
 * @author Drahomír Hanák
 */
class Control extends \Nette\Application\UI\Control
{

	/**
	 * Get table columns
	 * @return \ArrayIterator
	 */
	public function getColumns()
	{
		return $this->getComponents(TRUE, 'Drahak\Tables\Columns\IColumn');
	}

	/**
	 * Get table
	 * @param bool $need throws excepion if table doesn't esist
	 * @return \Nette\ComponentModel\IComponent
	 */
	public function getTable($need = FALSE)
	{
		return $this->lookup('Drahak\Tables\Table', $need);
	}

	/******************** Table column factories ********************/

	/**
	 * Create text column
	 * @param string $name
	 * @param string $label
	 * @param int $maxLength
	 * @return Columns\TextColumn
	 */
	public function addText($name, $label, $maxLength = null)
	{
		$this[$name] = new Columns\TextColumn($name, $label, $maxLength);
		return $this[$name];
	}

	/**
	 * Create DateTime column
	 * @param string $name
	 * @param string $label
	 * @param string $format
	 * @return Columns\DateTimeColumn
	 */
	public function addDateTime($name, $label, $format = null)
	{
		$this[$name] = new Columns\DateTimeColumn($name, $label, $format);
		return $this[$name];
	}

}