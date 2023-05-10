<?php
require_once plugin_dir_path(__FILE__) . '/database.php';
class tables
{
    //this class is used to create the tables in the database and to drop them using the Database class
    private $db;
    function __construct()
    {
        $this->db = new Database();
    }
    function drop_tables(){
        $this->db::drop_tables();
    }
    function create_tables(){
        $this->db::create_tables();
    }
}
