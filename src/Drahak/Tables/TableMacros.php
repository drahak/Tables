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
			'$table = $_table = $control = $_control = (is_object(%node.word) ? %node.word : $_control[%node.word]);' .
			'Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = $table[\'filterForm\'], is_array(%node.array) ? %node.array : array());' .
			'Drahak\Tables\TableMacros::renderTableBegin($table, %node.array);',

			'Drahak\Tables\TableMacros::renderTableEnd($_table);' .
			'Nette\Latte\Macros\FormMacros::renderFormEnd($_form);'
		);
		$macros->addMacro('th', array($macros, 'macroTableHeading'), '$_column->getLabelPrototype()->endTag();');
		$macros->addMacro('td', array($macros, 'macroTableCell'), '$_column->getCellPrototype()->endTag();');
		$macros->addMacro('tdControls', array($macros, 'macroControls'));
	}

	/******************** Table macros ********************/

	public function macroTableHeading(Latte\MacroNode $node, Latte\PhpWriter $writer)
	{
		$code = '$_column = is_object(%node.word) ? %node.word : $_table[%node.word]; $label = $_column->getLabelPrototype();' .
		'$_headingChildren = $_column->getLabelPrototype()->getChildren();' .
		'$_headingChildren[0]->href = $table->link(\'this!\', array(\'order\' => $_column->name, \'sort\' => !$_control->sort));' .
		'echo (string)$label->addAttributes(%node.array)->startTag(); ' .
		'echo $_column->isSortable() ? $label->getHtml() : $label->getText();';

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
		'$originalValue = isset($row[$_column->column]) ? $row[$_column->column] : \'\'; $value = $_column->render($originalValue, $row);' .
		'if ($value instanceof \Nette\Utils\Html && (isset($value->src) || isset($value->href))) $value->src = $basePath . \'/\' . $value->src;' .
		'$_column->getCellPrototype()->setHtml($value)->addAttributes(%node.array);' .
		'$_cellTag = (string)$_column->getCellPrototype()';

		if ($node->isEmpty = (substr($node->args, -1) === '/')) {
			$node->setArgs(substr($node->args, 0, -1));
			return $writer->write($code . '; echo $_cellTag;');
		} else {
			return $writer->write($code . '->startTag(); echo $_cellTag;');
		}
	}

	/**
	 * Table heading controls
	 * @param \Nette\Latte\MacroNode $node
	 * @param \Nette\Latte\PhpWriter $writer
	 * @return string
	 */
	public function macroControls(Latte\MacroNode $node, Latte\PhpWriter $writer)
	{
		$code = '$_column = is_object(%node.word) ? %node.word : $_table[%node.word]; if ($_column->isSortable()) : ?>
		<td>
			<div class="order">
				<a href="<?php echo $_control->link(\'this!\', array(\'order\' => $_column->name, \'sort\' => false)); ?>"><span>Ascending</span></a>
				<a href="<?php echo $_control->link(\'this!\', array(\'order\' => $_column->name, \'sort\' => true)); ?>"><span>Descending</span></a>
			</div>
			<?php echo $_form[$_column->getColumn()]->getControl()->addAttributes(%node.array); ?>
		</td>
		<?php endif;';

		if ($node->isEmpty = (substr($node->args, -1) === '/')) {
			$node->setArgs(substr($node->args, 0, -1));
		}

		return $writer->write($code);
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

		echo (string)$element->startTag();
	}

	/**
	 * Render table end
	 * @param Table $table
	 * @return string
	 */
	public static function renderTableEnd(Table $table)
	{
		echo $table->getElementPrototype()->endTag();
	}

}