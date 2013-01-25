<?php
namespace Drahak\Tables;

use Drahak\Tables\Table,
	Nette\Latte\Macros\MacroSet,
	Nette\Latte;

/**
 * Table macros
 * @author Drahomír Hanák
 */
class TableMacros extends MacroSet
{

	/**
	 * Install table macros
	 * @param \Nette\Latte\Compiler $compiler
	 * @return \Nette\Latte\Macros\MacroSet|void
	 */
	public static function install(Latte\Compiler $compiler)
	{
		$macros = new self($compiler);
		$macros->addMacro('table',
			'echo Drahak\Tables\TableMacros::renderTableBegin(
				$table = $_table = $control = $_control = (is_object(%node.word) ? %node.word : $_control[%node.word]), %node.array
			);',
			'echo Drahak\Tables\TableMacros::renderTableEnd($_table);'
		);
		$macros->addMacro('th', array($macros, 'macroTableHeading'), '?></th><?php');
		$macros->addMacro('td', array($macros, 'macroTableCell'), '?></td><?php');
	}

	/******************** Table macros ********************/

	public function macroTableHeading(Latte\MacroNode $node, Latte\PhpWriter $writer)
	{
		$orderControls = ' if ($_column->isSortable()) :' .
		'?><div class="order">
			<a href="<?php echo $_control->link(\'this!\', array(\'order\' => $_column->name, \'sort\' => false)); ?>"><span>Ascending</span></a>
			<a href="<?php echo $_control->link(\'this!\', array(\'order\' => $_column->name, \'sort\' => true)); ?>"><span>Descending</span></a>
		</div><?php endif;';

		$code = '$_column = is_object(%node.word) ? %node.word : $_table[%node.word];' .
		'$_headingChildren = $_column->getLabelPrototype()->getChildren();' .
		'$_headingChildren[0]->href = $table->link(\'this!\', array(\'order\' => $_column->name, \'sort\' => !$_control->sort));' .
		'echo (string)$_column->getLabelPrototype()->addAttributes(%node.array)->startTag(); ' .
		'echo $_column->getLabelPrototype()->getHtml();' . $orderControls;

		if ($node->isEmpty = (substr($node->args, -1) === '/')) {
			$node->setArgs(substr($node->args, 0, -1));
			return $writer->write($code . '$_column->getLabelPrototype()->endTag();');
		}
	}

	/**
	 * Macro table cell
	 * @param \Nette\Latte\MacroNode $node
	 * @param \Nette\Latte\PhpWriter $writer
	 * @return string
	 */
	public function macroTableCell(Latte\MacroNode $node, Latte\PhpWriter $writer)
	{
		$code = '$_column = is_object(%node.word) ? %node.word : $_table[%node.word];' .
		'$originalValue = isset($row[$_column->column]) ? $row[$_column->column] : \'\'; $value = $_column->parse($originalValue, $row);' .
		'$_cellTag = (string)$_column->getCellPrototype()->setHtml($value)->addAttributes(%node.array)';

		if ($node->isEmpty = (substr($node->args, -1) === '/')) {
			$node->setArgs(substr($node->args, 0, -1));
			return $writer->write($code . '; echo $_cellTag;');
		} else {
			return $writer->write($code . '->startTag(); echo $_cellTag;');
		}
	}

	/******************** Table element renderers ********************/

	/**
	 * Render table begin
	 * @param Table $table
	 * @param array $attributes
	 * @return string
	 */
	public static function renderTableBegin(Table $table, array $attributes)
	{
		$element = $table->getElementPrototype();
		$element->addAttributes($attributes);

		return (string)$element->startTag();
	}

	/**
	 * Render table end
	 * @param Table $table
	 * @return string
	 */
	public static function renderTableEnd(Table $table)
	{
		return $table->getElementPrototype()->endTag();
	}

}