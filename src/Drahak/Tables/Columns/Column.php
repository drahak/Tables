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

	/** @var Html */
	protected $cell;

	/** @var Html */
	protected $label;

	/** @var bool */
	protected $orderable = TRUE;

	/** @var callable Column content renderer */
	public $renderer;

	public function __construct($name, $label)
	{
		$this->monitor('Drahak\Tables\Table');
		parent::__construct(NULL, $name);
		$this->cell = Html::el('td');
		$this->label = Html::el('th', $label);
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
			$value = $this->renderer->invoke($value, $rowData, $this->cell);
		}

		return $value;
	}

	/**
	 * Is table orderable by this column?
	 * @return boolean
	 */
	public function isOrderable()
	{
		return $this->orderable;
	}

	/********************* Getters & setters *********************/

	/**
	 * Get column label
	 * @return Html
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * Get column cell
	 * @return \Nette\Utils\Html
	 */
	public function getCell()
	{
		return $this->cell;
	}

	/**
	 * Add class to this column
	 * @param string $class
	 * @return Column
	 */
	public function addClass($class)
	{
		$this->label->class[] = $class;
	}

	/**
	 * Set column class
	 * @param string $class
	 * @return Column
	 */
	public function setClass($class)
	{
		$this->label->class = array((string)$class);
		return $this;
	}

	/**
	 * Get column class
	 * @return string|array
	 */
	public function getClass()
	{
		return $this->label->class;
	}

	/**
	 * Set orderabůe
	 * @param bool $orderable
	 * @return Column
	 */
	public function setOrderable($orderable)
	{
		$this->orderable = $orderable;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getOrderable()
	{
		return $this->orderable;
	}

	/**
	 * Get column
	 * @return \Nette\Utils\Html
	 */
	public function getColumn()
	{
		return $this->column;
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