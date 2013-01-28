<?php
namespace Drahak\Tables;

/**
 * Drahak\Tables extension
 * @author Drahomír Hanák
 */
class Extension extends \Nette\Config\CompilerExtension
{

	public function loadConfiguration()
	{
		$config = $this->getConfig();
		$builder = $this->containerBuilder;

		$builder->getDefinition('nette.latte')
			->addSetup('Drahak\Tables\TableMacros::install(?->compiler)', '@self');
	}

}