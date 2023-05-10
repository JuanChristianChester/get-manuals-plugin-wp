<?php
class Database
{
  public $wpdb;
  function __construct()
  {
    global $wpdb;
    $this->wpdb = $wpdb;
  }
  function get_manual_by_serial_number_and_product_code($serial_number, $product_code)
  {
    // Define table names with prefix
    $table_name_date = $this->wpdb->prefix . 'tblDate';
    $table_name_product = $this->wpdb->prefix . 'tblProduct';
    $table_name_manuals = $this->wpdb->prefix . 'tblManuals';
    $table_name_join = $this->wpdb->prefix . 'tblJoin';

    // Build SQL query with dynamic table names
    $sql = "SELECT m.filename 
        FROM $table_name_manuals AS m
        INNER JOIN $table_name_join AS j ON m.ManualID = j.ManualID
        INNER JOIN $table_name_date AS d ON j.DateID = d.DateID
        INNER JOIN $table_name_product AS p ON j.ProductID = p.ProductID
        WHERE j.DateID = %s AND j.ProductID = %s";

    // Prepare the SQL query with values
    $prepared_sql = $this->wpdb->prepare($sql, $serial_number, $product_code);

    // Execute the query and get the result
    $manual = $this->wpdb->get_var($prepared_sql);

    if (!$manual) {
      return false;
    }

    return $manual;
  }
  function create_tables()
  {
    // Drop tables if they exist
    $this->wpdb->query("DROP TABLE IF EXISTS {$this->wpdb->prefix}tblJoin");
    $this->wpdb->query("DROP TABLE IF EXISTS {$this->wpdb->prefix}tblManuals");
    $this->wpdb->query("DROP TABLE IF EXISTS {$this->wpdb->prefix}tblProduct");
    $this->wpdb->query("DROP TABLE IF EXISTS {$this->wpdb->prefix}tblDate");

    // Create tables if they don't exist
    $this->wpdb->query("CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}tblDate (
      DateID varchar(6) PRIMARY KEY,
      Date date
    )");

    $this->wpdb->query("CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}tblProduct (
      ProductID VARCHAR(30) PRIMARY KEY,
      ProductName VARCHAR(255),
      ProductDescription TEXT
    )");

    $this->wpdb->query("CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}tblManuals (
      ManualID varchar(15) PRIMARY KEY,
      filename VARCHAR(255),
      pdf LONGBLOB
    )");

    $this->wpdb->query("CREATE TABLE IF NOT EXISTS {$this->wpdb->prefix}tblJoin (
      DateID VARCHAR(6),
      ProductID VARCHAR(30),
      ManualID varchar(15),
      PRIMARY KEY (DateID, ProductID, ManualID)
    )");

    // Insert data
    $this->wpdb->query("INSERT INTO {$this->wpdb->prefix}tblDate (DateID, Date)
    VALUES
      ('010501', '2001-05-01'),
      ('011201', '2001-12-01'),
      ('020501', '2002-05-01')");

    $this->wpdb->query("INSERT INTO {$this->wpdb->prefix}tblProduct (ProductID, ProductName, ProductDescription)
    VALUES
      ('C08-001-001-01-1-1', 'Air 5 Oxygen & Carbon Dioxide', '0-100% O2 + 0-5% CO2 ambient monitor with diffusion sensor (surface mount)'),
      ('C05-35-02', 'Air 5 Hypobaric Oxygen & Pressure', '0-40% O2 & 10-1300mbar (A) (Panel Mount)'),
      ('SAT-35-14', 'Air 5 Converter', 'USB to RS485 Converter')");

    $this->wpdb->query("INSERT INTO {$this->wpdb->prefix}tblManuals (ManualID, filename, pdf) 
    VALUES 
  ('MAN-0001', 'Man1.pdf', NULL), 
  ('MAN-0002', 'Man2.pdf', NULL)");

    $this->wpdb->query("INSERT INTO {$this->wpdb->prefix}tblJoin (DateID, ProductID, ManualID)
    VALUES
  ('010501', 'C08-001-001-01-1-1', 'MAN-0001'),
  ('020501', 'C05-35-02', 'MAN-0002')");
  }
  function drop_tables()
  {
    $this->wpdb->query("DROP TABLE IF EXISTS {$this->wpdb->prefix}tblJoin");
    $this->wpdb->query("DROP TABLE IF EXISTS {$this->wpdb->prefix}tblManuals");
    $this->wpdb->query("DROP TABLE IF EXISTS {$this->wpdb->prefix}tblProduct");
    $this->wpdb->query("DROP TABLE IF EXISTS {$this->wpdb->prefix}tblDate");
  }
}
