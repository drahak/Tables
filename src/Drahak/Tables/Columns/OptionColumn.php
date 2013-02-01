<?php
namespace Drahak\Tables\Columns;

/**
 * Column with options
 * @author Drahomír Hanák
 */
abstract class OptionColumn extends Column
{

	/** @var array */
	private $options = array();

	/**
	 * @param array $options
	 * @return TextColumn
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Remove all options
	 * @return TextColumn
	 */
	public function removeOptions()
	{
		$this->options = array();
		return $this;
	}

	/**
	 * Render option column
	 * @param mixed $value
	 * @param mixed $rowData
	 * @return mixed|string
	 * @throws \Nette\InvalidArgumentException
	 */
	public function render($value, $rowData)
	{
		$originalValue = $value;
		$value = parent::render($value, $rowData);

		// Chek options with no filtered
		if ($this->options && is_array($this->options)) {
			if (!in_array($originalValue, $this->options) && !isset($this->options[$originalValue])) {
				throw new \Nette\InvalidArgumentException(
					'Value ' . $value . ' not found in column options. You can add it to the OrderColumn::options array.'
				);
			}
		}

		return is_string($value) && isset($this->options[$value]) ? $this->options[$value] : $value;
	}


}