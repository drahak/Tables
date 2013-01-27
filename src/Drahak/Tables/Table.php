<?php
namespace Drahak\Tables;

use Nette\ComponentModel\IContainer,
	Nette\Utils\Paginator,
	Nette\Utils\Html;

/**
 * Table base class
 * @author Drahomír Hanák
 */
class Table extends Control
{

	/** Tale order options */
	const ORDER_DESC = true;
	const ORDER_ASC = false;

	/** @var string */
	public $order;

	/** @var boolean */
	public $sort = self::ORDER_ASC;

	/** @var \Nette\Utils\Html */
	protected $elementPrototype;

	/** @var Paginator */
	private $paginator;

	/** @var DataSources\IDataSource */
	private $dataSource;


	public function __construct(IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($name, $parent);
		$this->elementPrototype = Html::el('table');

		// Setup table
		$this->setup();
	}

	/**
	 * Setup table
	 * @return \Drahak\Tables\Table
	 */
	protected function setup()
	{
		if ($this->order) {
			$this->setOrder($this->order, $this->sort);
		}

		if ($this->getPaginator()) {
			$this->setLimit($this->getPaginator()->getItemsPerPage(), $this->getPaginator()->getOffset());
		}
		return $this;
	}

	/**
	 * Disable table sort by all columns
	 * @return Table provides fluent interface
	 */
	public function disableSort()
	{
		foreach ($this->getColumns() as $column) {
			try {
				$column->setSortable(FALSE);
			} catch (\Nette\NotSupportedException $e) {
				/** Sort by this column is not supported */
			}
		}
		return $this;
	}

	/**
	 * Enable table sort by all columns
	 * @return Table provides fluent interface
	 */
	public function enableSort()
	{
		foreach ($this->getColumns() as $column) {
			try {
				$column->setSortable(TRUE);
			} catch (\Nette\NotSupportedException $e) {
				/** Sort by this column is not supported */
			}
		}
		return $this;
	}

	/******************** Getters & setters ********************/

	/**
	 * Set order
	 * @param string $order
	 * @param boolean|null $sort
	 * @return Table
	 * @throws \Nette\InvalidArgumentException when undefined column f
	 */
	public function setOrder($order, $sort = NULL)
	{
		if (!$order) return $this;
		$this->order = $order;
		$this->sort = $sort !== NULL ? $sort : $this->sort;
		$this->dataSource->order($this->getComponent($this->order, TRUE)->getColumn(), (bool)$this->sort);
		return $this;
	}

	/**
	 * Set sort
	 * @param string $sort
	 * @return Table
	 */
	public function setSort($sort)
	{
		$this->sort = (bool)$sort;
		$this->setOrder($this->order, $this->sort);
		return $this;
	}

	/**
	 * Set limit
	 * @param int $limit
	 * @param int|null $offset
	 * @return Table
	 */
	public function setLimit($limit, $offset = NULL)
	{
		if (!$limit) return $this;
		$this->dataSource->limit($limit, $offset);
		return $this;
	}

	/**
	 * Get table rows
	 * @return array|mixed
	 */
	public function getRows()
	{
		return $this->dataSource->getData();
	}

	/**
	 * Get table element prototype
	 * @return \Nette\Utils\Html
	 */
	public function getElementPrototype()
	{
		return $this->elementPrototype;
	}

	/**
	 * Set table data source
	 * @param DataSources\IDataSource $dataSource
	 * @throws \Nette\InvalidStateException
	 */
	public function setDataSource(DataSources\IDataSource $dataSource)
	{
		if ($this->dataSource) {
			throw new \Nette\InvalidStateException('Table data source has already been set');
		}
		$this->dataSource = $dataSource;
	}

	/**
	 * Set table paginator
	 * @return \Nette\Utils\Paginator
	 */
	public function getPaginator()
	{
		return $this->paginator;
	}

	/**
	 * @param \Nette\Utils\Paginator $paginator
	 * @return Table
	 */
	public function setPaginator(Paginator $paginator)
	{
		$this->paginator = $paginator;
		$this->setLimit($this->getPaginator()->getItemsPerPage(), $this->getPaginator()->getOffset());
		return $this;
	}

}