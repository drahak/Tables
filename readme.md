Drahak\Tables addon
===================

Drahak\Tables Nette framework addon for simple tables creation.

1. Setup table
--------------

	protected function createComponentAccountsTable()
	{
		$table = new Table;
		$table->setDataSource(new DefaultDataSource($this->context->connection->table('account')));

		$table->addText('id', '#');
		$table->addText('username', 'Jméno');
		$table->addText('email', 'E-mail');
		$table->addDateTime('joindate', 'Registrován', 'd/m/Y H:i:s');
		$table->addDateTime('last_login', 'Poslední přihlášení', 'd/m/Y H:i:s');

		return $table;
	}

2. Render table
---------------
You can use control `{control accountsTable}` or custom renderer just like in Nette\Forms:

	{table accountsTable, class => 'table'}
		<thead>
			<tr>
				{th id, class => 'column-id' /}
				{th username, class => 'column-name' /}
				{th email /}
				{th joindate /}
				{th last_login /}
			</tr>
		</thead>

		<tbody>
			<tr n:foreach="$table->rows as $row">
				{td id /}
				{td username /}
				{td email}
					<a href="mailto:{$value}">{$value}</a>
				{/td}
				{td joindate}
					{$originalValue|date:'d.m.Y'}
				{/td}
				{td last_login}
					{if $originalValue == '-0001-11-30 00:00:00'}
						<i>Nikdy</i>
					{else}
						{$originalValue|date:'d.m.Y'}
					{/if}
				{/td}
			</tr>
		</tbody>
    {/table}

Note that macros can be auto-closed by adding slash '/' at the end of macro. In this case will be used default column renderer.

Columns
-------
All columns are implementation of `Drahak\Tables\Columns\IColumn`. Base implementation of this interface is abstract class `Drahak\Tables\Columns\Column` and derivates `Drahak\Tables\Column\TextColumn` and `Drahak\Tables\Column\DateTimeColumn`.

You can add columns to the table by calling methods `Table#addText`, `Table#addDateTime` or appended it to the table `$table[] = new TextColumn('my_column', 'My Column');`. Columns are components.

Installing
----------