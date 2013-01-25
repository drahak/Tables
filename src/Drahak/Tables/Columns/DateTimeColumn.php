<?php
namespace Drahak\Tables\Columns;

/**
 * DateTime column
 * @author Drahomíár Hanák
 */
class DateTimeColumn extends Column
{

	/** @var string */
	private $format;

	public function __construct($name, $label, $format = 'd/m/Y')
	{
		parent::__construct($name, $label);
		$this->format = $format;
	}

	/**
	 * @param string $format
	 * @return DateTimeColumn
	 */
	public function setFormat($format)
	{
		$this->format = $format;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
	}

	/**
	 * Parse column
	 * @param mixed $value
	 * @param mixed $rowData
	 * @return mixed|string
	 */
	public function parse($value, $rowData)
	{
		$value = parent::parse($value, $rowData);
		$value = \Nette\DateTime::from($value);
		return $value->format($this->format);
	}


}