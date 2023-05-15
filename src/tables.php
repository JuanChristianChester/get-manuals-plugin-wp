<?php
require_once plugin_dir_path(__FILE__) . '/database.php';
class tables
{
    //this class is used to create the tables in the database and to drop them using the Database class

    static function drop_tables()
    {
        Database::drop_tables();
    }
    static function create_tables()
    {
        Database::create_tables();
    }
}
