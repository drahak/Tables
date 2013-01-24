<?php
namespace Drahak\Tables;

/**
 * Table container
 * @author Drahomír Hanák
 */
class Container extends \Nette\ComponentModel\Container implements \ArrayAccess
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

	/******************** Interface ArrayAccess ********************/

	/**
	 * Component exists
	 * @param string $name
	 * @return bool
	 */
	public function offsetExists($name)
	{
		return $this->getComponent($name, FALSE) !== NULL;
	}

	/**
	 * Get component
	 * @param string $name
	 * @return mixed|void
	 */
	public function offsetGet($name)
	{
		return $this->getComponent($name, TRUE);
	}

	/**
	 * Set component
	 * @param string $name
	 * @param \Nette\ComponentModel\IComponent $component
	 */
	public function offsetSet($name, $component)
	{
		$this->addComponent($component, $name);
	}

	/**
	 * Unset component
	 * @param string $name
	 */
	public function offsetUnset($name)
	{
		$component = $this->getComponent($name, FALSE);
		if ($component !== NULL) {
			$this->removeComponent($component);
		}
	}


}