<?php
namespace Drahak\Tables\Columns;

use Nette\ComponentModel\Component,
	Nette\Utils\Strings,
	Nette\Utils\Html;

/**
 * Table column
 * @author Drahomír Hanák
 */
abstract class Column extends Component implements IColumn
{

	/** @var string Database column name */
	protected $column;

	/** @var Html */
	protected $cellPrototype;

	/** @var Html */
	protected $labelPrototype;

	/** @var bool */
	protected $sortable = TRUE;

	/** @var callable Column content renderer */
	public $renderer;

	public function __construct($name, $label)
	{
		$this->monitor('Drahak\Tables\Table');
		parent::__construct(NULL, $name);
		$this->cellPrototype = Html::el('td');
		$this->labelPrototype = Html::el('th');
		$this->labelPrototype->add(Html::el('a', $label));
		$this->column = $name;
	}



	/********************* IColumn interface *********************/

	/**
	 * Format this column content
	 * @param mixed $value
	 * @param mixed $rowData
	 * @return mixed|string
	 */
	public function parse($value, $rowData)
	{
		if (is_array($value)) {
			$value = implode(', ', $value);
		}

		// Apply renderer if any
		if ($this->renderer) {
			$value = $this->renderer->invoke($value, $rowData, $this->cellPrototype);
		}

		return $value;
	}

	/**
	 * Is table orderable by this column?
	 * @return boolean
	 */
	public function isSortable()
	{
		return $this->sortable;
	}


	/********************* Getters & setters *********************/

	/**
	 * @return \Nette\Utils\Html
	 */
	public function getCellPrototype()
	{
		return $this->cellPrototype;
	}

	/**
	 * @return \Nette\Utils\Html
	 */
	public function getLabelPrototype()
	{
		return $this->labelPrototype;
	}

	/**
	 * Set database column name
	 * @param string $column
	 * @return Column
	 */
	public function setColumn($column)
	{
		$this->column = $column;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getColumn()
	{
		return $this->column;
	}

	/**
	 * Set orderabůe
	 * @param bool $orderable
	 * @return Column
	 */
	public function setSortable($orderable)
	{
		$this->sortable = $orderable;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getSortable()
	{
		return $this->sortable;
	}

	/**
	 * Get table heading
	 * @return \Nette\Utils\Html
	 */
	public function getHeading()
	{
		return $this->heading;
	}

	/**
	 * @param callable $renderer
	 * @return Column
	 * @throws \Nette\InvalidArgumentException
	 */
	public function setRenderer($renderer)
	{
		$this->renderer = new \Nette\Callback($renderer);
		return $this;
	}

}