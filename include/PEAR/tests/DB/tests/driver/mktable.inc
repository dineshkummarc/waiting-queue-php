<?php

/**
 * Creates the <kbd>phptest</kbd> table.
 *
 * Tries to drop the table first, in case it already exists.
 *
 * <pre>
 * CREATE TABLE phptest (
 *   a INTEGER NULL,
 *   b CHAR(40) DEFAULT 'def' NOT NULL,
 *   c VARCHAR(255) NULL,
 *   d VARCHAR(20) NULL)
 * </pre>
 *
 * Need <kbd>NOT NULL</kbd> on <kbd>b</kbd> to test
 * <kbd>DB_PORTABILITY_RTRIM</kbd>.  MS SQL and Sybase trim output from
 * <kbd>VARCHAR</kbd>, but not on <kbd>CHAR</kbd>.
 *
 * Need <kbd>DEFAULT</kbd> value on <kbd>b</kbd> because Oracle considers
 * an empty string to be <kbd>NULL</kbd>.
 *
 * In Oracle, when using placeholders in <kbd>WHERE</kbd> clauses on 
 * <kbd>CHAR</kbd> columns, the column must have <kbd>RTRIM()</kbd> run on
 * the column:
 * <samp>
 *    SELECT * FROM phptest WHERE RTRIM(b) = ?
 * </samp>
 *
 * @package  DB
 * @version $Id: mktable.inc,v 1.12 2004/01/31 01:26:49 danielc Exp $
 * @category Database
 * @internal
 */

/**
 * Establishes the DB object and connects to the database.
 */
require_once './connect.inc';

$dbh->setErrorHandling(PEAR_ERROR_RETURN);
$dbh->query("DROP TABLE phptest");

//$dbh->setErrorHandling(PEAR_ERROR_TRIGGER);
$dbh->setErrorHandling(PEAR_ERROR_CALLBACK, 'debug_die');

switch ($dbh->phptype) {
    case 'odbc':
    case 'ibase':
        $null = '';
        $chrc = 'VARCHAR(255)';
        $chrd = 'VARCHAR(20)';
        break;
    case 'msql':
    case 'ifx':
        // doing this for ifx to keep certain versions happy
        $null = '';
        $chrc = 'CHAR(255)';
        $chrd = 'CHAR(20)';
        break;
    default:
        $null = 'NULL';
        $chrc = 'VARCHAR(255)';
        $chrd = 'VARCHAR(20)';
}

$test_mktable_query = "
    CREATE TABLE phptest (
      a INTEGER $null,
      b CHAR(40) DEFAULT 'def' NOT NULL,
      c $chrc $null,
      d $chrd $null)
";


$dbh->query($test_mktable_query);
$dbh->query("INSERT INTO phptest VALUES(42, 'bing', 'This is a test', '1999-11-21')");

$dbh->setErrorHandling(PEAR_ERROR_RETURN);

?>
