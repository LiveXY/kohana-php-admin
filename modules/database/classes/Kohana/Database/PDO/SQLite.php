<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * This driver allows you to use SQLite database
 * Based on PDO
 *
 * @package          SQLite
 * @author           Dmitriy Zhavoronkov
 * @copyright    (c) 2014 Dmitriy Zhavoronkov
 * @license          http://kohanaframework.org/license
 */
class Kohana_Database_PDO_SQLite extends Kohana_Database_PDO
{
    public function list_columns($table, $like = null, $add_prefix = true)
    {
        // Quote the table name
        $table = ($add_prefix === true) ? $this->quote_table($table) : $table;

        $result = $this->query(Database::SELECT, 'PRAGMA table_info(' . $table . ')', false);

        $count   = 0;
        $columns = array();

        foreach ($result as $row)
        {
            list($type) = $this->_parse_type(strtolower($row['type']));

            $column = ($type == 'text') ? array('type' => 'string') : $this->datatype($type);

            $column['column_name']      = $row['name'];
            $column['column_default']   = $row['dflt_value'];
            $column['data_type']        = $type;
            $column['is_nullable']      = ($row['notnull'] == '1');
            $column['ordinal_position'] = ++$count;

            $columns[$row['name']] = $column;
        }

        return $columns;
    }
}