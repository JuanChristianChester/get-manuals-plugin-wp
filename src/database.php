<?php
class Database
{
  public static function get_manual($serial_number, $product_code)
  {
    global $wpdb;
    $prepared_sql = $wpdb->prepare(self::build_sql_query($wpdb), $serial_number, $product_code);

    $manual = $wpdb->get_var($prepared_sql);

    return $manual ?: false;
  }

  public static function create_tables()
  {
    global $wpdb;
    self::drop_tables($wpdb);

    self::create_tblDate($wpdb);
    self::create_tblManuals($wpdb);
    self::create_tblJoin($wpdb);

    self::insert_data($wpdb);
  }
  private static function get_table_names($wpdb)
  {

    $table_name_date = $wpdb->prefix . 'tblDate';
    $table_name_manuals = $wpdb->prefix . 'tblManuals';
    $table_name_join = $wpdb->prefix . 'tblJoin';

    return [
      'date' => $table_name_date,
      'manuals' => $table_name_manuals,
      'join' => $table_name_join,
    ];
  }

  private static function build_sql_query($wpdb)
  {
    $table_names = self::get_table_names($wpdb);
    $sql = "SELECT m.filename 
        FROM " . $table_names['manuals'] . " AS m
        INNER JOIN " . $table_names['join'] . " AS j ON m.ManualID = j.ManualID
        INNER JOIN " . $table_names['date'] . " AS d ON j.DateID = d.DateID
        WHERE j.DateID = %s AND j.ProductID = %s";

    return $sql;
  }

  private static function create_tblDate($wpdb)
  {

    $wpdb->query("CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "tblDate (
      DateID varchar(6) PRIMARY KEY,
      Date date
    )");
  }

  private static function create_tblManuals($wpdb)
  {

    $wpdb->query("CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "tblManuals (
      ManualID varchar(15) PRIMARY KEY,
      filename VARCHAR(255))");
  }

  private static function create_tblJoin($wpdb)
  {

    $wpdb->query("CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "tblJoin (
      DateID VARCHAR(6),
      ProductID VARCHAR(30),
      ManualID varchar(15),
      PRIMARY KEY (DateID, ProductID, ManualID)
    )");
  }

  private static function insert_data($wpdb)
  {

    $wpdb->query("INSERT INTO {$wpdb->prefix}tblDate (DateID, Date)
    VALUES
      ('010501', '2001-05-01'),
      ('011201', '2001-12-01'),
      ('020501', '2002-05-01')");

    $wpdb->query("INSERT INTO {$wpdb->prefix}tblManuals (ManualID, filename) 
    VALUES 
      ('MAN-0001', 'Man1.pdf'), 
      ('MAN-0002', 'Man2.pdf')");


    $wpdb->query("INSERT INTO {$wpdb->prefix}tblJoin (DateID, ProductID, ManualID)
    VALUES
  ('010501', 'C08-001-001-01-1-1', 'MAN-0001'),
  ('020501', 'C05-35-02', 'MAN-0002')");
  }

  public static function drop_tables()
  {
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblJoin");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblManuals");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblDate");
  }
}
