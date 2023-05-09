<?php
class Database
{
    function get_manual_by_serial_number_and_product_code($serial_number, $product_code)
    {
        global $wpdb;
        // Define table names with prefix
        $table_name_date = $wpdb->prefix . 'tblDate';
        $table_name_product = $wpdb->prefix . 'tblProduct';
        $table_name_manuals = $wpdb->prefix . 'tblManuals';
        $table_name_join = $wpdb->prefix . 'tblJoin';

        // Build SQL query with dynamic table names
        $sql = "SELECT m.filename 
        FROM $table_name_manuals AS m
        INNER JOIN $table_name_join AS j ON m.ManualID = j.ManualID
        INNER JOIN $table_name_date AS d ON j.DateID = d.DateID
        INNER JOIN $table_name_product AS p ON j.ProductID = p.ProductID
        WHERE j.DateID = %s AND j.ProductID = %s";

        // Prepare the SQL query with values
        $prepared_sql = $wpdb->prepare($sql, $serial_number, $product_code);

        // Execute the query and get the result
        $manual = $wpdb->get_var($prepared_sql);

        if (!$manual) {
            return false;
        }

        return $manual;
    }
    function create_tables()
    {
        global $wpdb;
        // Drop tables if they exist
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblJoin");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblManuals");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblProduct");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblDate");

        // Create tables if they don't exist
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tblDate (
      DateID varchar(6) PRIMARY KEY,
      Date date
    )");

        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tblProduct (
      ProductID VARCHAR(30) PRIMARY KEY,
      ProductName VARCHAR(255),
      ProductDescription TEXT
    )");

        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tblManuals (
      ManualID varchar(15) PRIMARY KEY,
      filename VARCHAR(255),
      pdf LONGBLOB
    )");

        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tblJoin (
      DateID VARCHAR(6),
      ProductID VARCHAR(30),
      ManualID varchar(15),
      PRIMARY KEY (DateID, ProductID, ManualID)
    )");

        // Insert data
        $wpdb->query("INSERT INTO {$wpdb->prefix}tblDate (DateID, Date)
    VALUES
      ('010501', '2001-05-01'),
      ('011201', '2001-12-01'),
      ('020501', '2002-05-01')");

        $wpdb->query("INSERT INTO {$wpdb->prefix}tblProduct (ProductID, ProductName, ProductDescription)
    VALUES
      ('C08-001-001-01-1-1', 'Air 5 Oxygen & Carbon Dioxide', '0-100% O2 + 0-5% CO2 ambient monitor with diffusion sensor (surface mount)'),
      ('C05-35-02', 'Air 5 Hypobaric Oxygen & Pressure', '0-40% O2 & 10-1300mbar (A) (Panel Mount)'),
      ('SAT-35-14', 'Air 5 Converter', 'USB to RS485 Converter')");

        // $url1 = 'https://2126669.linux.studentwebserver.co.uk/SATSystems/wp-content/uploads/2023/05/Man1.pdf';
        // $url2 = 'https://2126669.linux.studentwebserver.co.uk/SATSystems/wp-content/uploads/2023/05/Man2.pdf';

        // $file1 = file_get_contents($url1);
        // $file2 = file_get_contents($url2);

        $wpdb->query("INSERT INTO {$wpdb->prefix}tblManuals (ManualID, filename, pdf) 
    VALUES 
  ('MAN-0001', 'Man1.pdf', NULL), 
  ('MAN-0002', 'Man2.pdf', NULL)");

        $wpdb->query("INSERT INTO {$wpdb->prefix}tblJoin (DateID, ProductID, ManualID)
    VALUES
  ('010501', 'C08-001-001-01-1-1', 'MAN-0001'),
  ('020501', 'C05-35-02', 'MAN-0002')");
    }
}
