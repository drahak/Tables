<?php
define('LIBS_DIR', __DIR__ . '/../libs');
define('TMP_DIR', __DIR__ . '/../temp');

require LIBS_DIR . '/autoload.php';


// Configure application
$configurator = new Nette\Config\Configurator;

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(TMP_DIR);
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../src')
	->register();

\Nette\Diagnostics\Debugger::enable(false);
\Nette\Diagnostics\Debugger::$strictMode = true;