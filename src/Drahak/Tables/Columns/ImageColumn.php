<?php
namespace Drahak\Tables\Columns;

use Nette\Utils\Html;

/**
 * Table image column
 * @author Drahomír Hanák
 */
class ImageColumn extends OptionColumn
{

	/** @var array */
	private $supposedExtension = array('png', 'jpg', 'jpeg', 'jpe', 'gif', 'bmp', 'tiff', 'ico', 'tif', 'svg', 'svgz');

	/** @var string */
	private $imagePath;

	public function __construct($name, $label, $imagePath = '')
	{
		parent::__construct($name, $label);
		$this->imagePath = $imagePath;
	}

	/**
	 * @param string $imagePath
	 * @return ImageColumn
	 */
	public function setImagePath($imagePath)
	{
		$this->imagePath = $imagePath;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getImagePath()
	{
		return $this->imagePath;
	}

	/**
	 * Parse image file path
	 * @param string $value
	 * @param array $rowData
	 * @return mixed|string
	 * @throws \Nette\FileNotFoundException
	 */
	public function render($value, $rowData)
	{
		$value = parent::render($value, $rowData);
		$path = $this->getFilePath($value);

		// Set image source
		$img = Html::el('img');
		$img->alt = $this->cellPrototype->getText() ?
			$this->cellPrototype->getText() :
			$this->labelPrototype->getText();
		$img->src = $path;

		return $img;
	}

	/**
	 * Get image file path
	 * @param string $value
	 * @return string
	 * @throws \Nette\FileNotFoundException
	 */
	private function getFilePath($value)
	{
		$path = $this->imagePath . DIRECTORY_SEPARATOR . $value;
		$fileInfo = pathinfo($path);

		// Check file extension
		if (!isset($fileInfo['extension'])) {
			foreach ($this->supposedExtension as $extension) {
				if (file_exists($path . '.' . $extension)) {
					$path .= '.' . $extension;
					break;
				}
			}
		}

		// If file doesn't esist
		if (!file_exists($path)) {
			throw new \Nette\FileNotFoundException(
				'File for ' . $value . ' in table column ' . $this->getName() . ' not found in ' . $path
			);
		}

		return $path;
	}

}