<?php
require_once plugin_dir_path(__FILE__) . '/database.php';
class Manual
{
    public $manual;
    public $serialNumber;
    public $productCode;
    function __construct($serial_number, $product_code)
    {   
        $db = new Database();
        $this->manual = $db->get_manual_by_serial_number_and_product_code($serial_number, $product_code);
        $this->serialNumber = $serial_number;
        $this->productCode = $product_code;
    }
}
