<?php
namespace Drahak\Application\UI;

use Nette,
	Nette\ComponentModel\IContainer,
	Nette\Localization\ITranslator;

/**
 * Table control
 * @author Drahomír Hanák
 */
class Table extends \Drahak\Tables\Table
{

	/** @persistent */
	public $page = 1;

	/** @persistent */
	public $order;

	/** @persistent */
	public $sort = self::ORDER_ASC;

	/** @persistent */
	public $perPage = 50;

	/** @var \Nette\Localization\ITranslator */
	private $translator;

	public function __construct(IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($name, $parent);
	}

	/**
	 * Render table control
	 */
	public function render(array $attributes = array())
	{
		$this->setup();
		$this->template->setFile(__DIR__ . '/templates/Table.latte');
		try {
			$this->template->setTranslator($this->translator);
		} catch (\Nette\InvalidArgumentException $e) {}

		$this->template->attributes = $attributes;
		$this->template->paginator = $this->getPaginator();
		$this->template->ajax = TRUE;
		$this->template->render();
	}

	public function templatePrepareFilters($template)
	{
		$template->registerFilter($latte = new Nette\Latte\Engine);
		\Drahak\Tables\TableMacros::install($latte->compiler);
	}


	/**
	 * @return \Nette\Localization\ITranslator
	 */
	public function getTranslator()
	{
		return $this->translator;
	}

	/**
	 * Set translator
	 * @param \Nette\Localization\ITranslator $translator
	 * @return Table
	 */
	public function setTranslator(ITranslator $translator)
	{
		$this->translator = $translator;
		return $this;
	}

}