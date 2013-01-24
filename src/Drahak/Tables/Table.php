<?php
namespace Drahak\Tables;

use Nette\ComponentModel\IContainer,
	Nette\Utils\Paginator,
	Nette\Utils\Html;

/**
 * Table base class
 * @author Drahomír Hanák
 */
class Table extends Container
{

	/** Tale order options */
	const ORDER_DESC = true;
	const ORDER_ASC = false;

	/** @var string */
	private $order;

	/** @var boolean */
	private $sort = self::ORDER_ASC;

	/** @var \Nette\Utils\Html */
	protected $element;

	/** @var Paginator */
	private $paginator;

	/** @var DataSources\IDataSource */
	private $dataSource;


	public function __construct(IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($name, $parent);
		$this->element = Html::el('table');

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

	/******************** Getters & setters ********************/

	/**
	 * Set order
	 * @param string $order
	 * @param boolean|null $sort
	 * @return Table
	 */
	public function setOrder($order, $sort = NULL)
	{
		if (!$order) return $this;
		$this->order = $order;
		$this->sort = $sort !== NULL ? $sort : $this->sort;
		$this->dataSource->order($this->order, (bool)$this->sort);
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
	 * @return boolean
	 */
	public function getSort()
	{
		return $this->sort;
	}

	/**
	 * @return string
	 */
	public function getOrder()
	{
		return $this->order;
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
		return $this->element;
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