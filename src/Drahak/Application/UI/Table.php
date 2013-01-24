<?php
namespace Drahak\Application\UI;

use Nette\Database\Table\Selection,
	Drahak\Tables\DataSources\DefaultDataSource;

/**
 * Table control
 * @author Drahomír Hanák
 */
class Table extends \Nette\Application\UI\Control
{

	/** @persistent */
	public $page = 1;

	/** @persistent */
	public $order;

	/** @persistent */
	public $invert = FALSE;

	/** @persistent */
	public $perPage = 50;

	/** @var \Nette\Localization\ITranslator */
	private $translator;

	/**
	 * Set translator
	 * @param \Nette\Localization\ITranslator $translator
	 * @return Table
	 */
	public function setTranslator(\Nette\Localization\ITranslator $translator)
	{
		$this->translator = $translator;
		return $this;
	}

	/**
	 * @return \Nette\Localization\ITranslator
	 */
	public function getTranslator()
	{
		return $this->translator;
	}

	public function render()
	{
		$table = $this->getTable();
		$table->order = $this->order;
		$table->sort = $this->invert;

		$this->template->table = $table;
		$this->template->paginator = $table->getPaginator();
		$this->template->columns = $table->getColumns();
		$this->template->rows = $table->getRows();
		$this->template->ajax = TRUE;
		$this->template->render();
	}

	/**
	 * Set table data source
	 * @param \Drahak\Tables\DataSources\IDataSource|Selection $source
	 * @return \Drahak\Tables\Table
	 */
	public function setData($source)
	{
		$table = $this->getTable();

		if ($source instanceof Selection) {
			$table->setDataSource(new DefaultDataSource($source));
			return $this;
		}

		$table->setDataSource($source);
		return $this;
	}

	/**
	 * Get table container
	 * @return \Drahak\Tables\Table
	 */
	public function getTable()
	{
		return $this->getComponent('table', TRUE);
	}

	/******************** Table factories ********************/

	/**
	 * Create template
	 * @param string|NULL $class
	 * @return \Nette\Templating\ITemplate
	 */
	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		$template->setFile(__DIR__ . '/templates/Table.latte');
		try {
			$template->setTranslator($this->translator);
		} catch (\Nette\InvalidArgumentException $e) {
		}
		return $template;
	}

	/**
	 * Create table
	 * @return \Drahak\Tables\Table
	 */
	protected function createComponentTable()
	{
		return new \Drahak\Tables\Table($this, $this->getName());
	}

	/**
	 * Create text column
	 * @param string $name
	 * @param string $label
	 * @param int $maxLength
	 * @return \Drahak\Tables\Columns\TextColumn
	 */
	public function addText($name, $label, $maxLength = null)
	{
		return $this->getTable()->addText($name, $label, $maxLength);
	}

	/**
	 * Create DateTime column
	 * @param string $name
	 * @param string $label
	 * @param string $format
	 * @return \Drahak\Tables\Columns\DateTimeColumn
	 */
	public function addDateTime($name, $label, $format = null)
	{
		return $this->getTable()->addDateTime($name, $label, $format);
	}

}