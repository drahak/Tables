<?php
namespace Drahak\Tables\Columns;

use Nette\Utils\Strings;

/**
 * TextColumn
 * @author Drahomír Hanák
 */
class TextColumn extends Column
{

	/** @var int */
	private $maxLength = 0;

	public function __construct($name, $label, $maxLength = NULL)
	{
		parent::__construct($name, $label);
		$this->maxLength = $maxLength === NULL ? $this->maxLength : (int)$maxLength;
	}

	/**
	 * Set max cell length
	 * @param int $maxLength
	 * @return TextColumn
	 */
	public function setMaxLength($maxLength)
	{
		$this->maxLength = (int)$maxLength;
		return $this;
	}

	/**
	 * Get max cell legnth
	 * @return int
	 */
	public function getMaxLength()
	{
		return $this->maxLength;
	}

	/**
	 * Format text column
	 * @param string $value
	 * @param mixed $rowData
	 * @return mixed|string
	 */
	public function parse($value, $rowData)
	{
		$value = parent::parse($value, $rowData);

		$value = $this->maxLength > 0 ?
			Strings::truncate($value, $this->maxLength) :
			$value;

		return $value;
	}

}