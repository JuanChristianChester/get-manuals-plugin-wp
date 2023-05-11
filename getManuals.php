<?php
/*
Plugin Name: Get Manuals
Plugin URI: https://example.com/my-serial-number-plugin
Description: A plugin that allows users to search for manuals using serial numbers.
Version: 1.0
Authors: George P & Juan C
Author URI:
License: GPL2
*/

// Shortcode to display the search form
add_shortcode('serial_number_search_form', 'display_serial_number_search_form');

function display_serial_number_search_form()
{
    $manual = null;
    $error = '';

    if (isset($_POST['submit'])) {
        $serial_number = $_POST['serial_number'];
        $product_code = $_POST['product_code'];
        $manual = get_manual_by_serial_number_and_product_code($serial_number, $product_code);
        if (!$manual) {
            $error = 'Serial number and/or product code not found. Please enter valid ones.';
        }
    }
?>
    <form method="post" action="">
        <label for="serial_number">Enter Serial Number:</label>
        <input type="text" name="serial_number" id="serial_number">
        <br><br>
        <label for="product_code">Enter Product Code:</label>
        <input type="text" name="product_code" id="product_code">
        <br><br>
        <input type="submit" name="submit" value="Search">
    </form>

    <?php if ($error) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>

    <?php if ($manual) { ?>
        <h3>Manual Details</h3>
        <ul>
            <li><strong>Serial Number:</strong> <?php echo $serial_number; ?></li>
            <li><strong>Product Code:</strong> <?php echo $product_code; ?></li>
            <li><strong>Manual:</strong>
                <?php $pdf_url = wp_upload_dir()['baseurl'] . '/pdfs/' . $manual; ?>
                <?php $pdf_path = wp_upload_dir()['basedir'] . '/pdfs/' . $manual; ?>

                <?php if (file_exists($pdf_path)) { ?>
                    <a href="<?php echo $pdf_url; ?>" target="_blank"><?php echo $manual; ?></a>
                <?php } else { ?>
                    No manual available.
                <?php } ?>
            </li>
        </ul>
    <?php } ?>

<?php
}

//Function to search the DB for the serial number and return the manual
function get_manual_by_serial_number_and_product_code($serial_number, $product_code)
{
    global $wpdb;
    // Define table names with prefix
    $table_name_date = $wpdb->prefix . 'tblDate';
    $table_name_manuals = $wpdb->prefix . 'tblManuals';
    $table_name_join = $wpdb->prefix . 'tblJoin';

    // Build SQL query with dynamic table names
    $sql = "SELECT m.filename
        FROM $table_name_manuals AS m
        INNER JOIN $table_name_join AS j ON m.ManualID = j.ManualID
        INNER JOIN $table_name_date AS d ON j.DateID = d.DateID
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
// activation hook

register_activation_hook(__FILE__, 'my_plugin_activate');
function my_plugin_activate()
{
    global $wpdb;

    // Drop tables if they exist
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblJoin");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblManuals");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tblDate");

    // Create tables if they don't exist
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tblDate (
  DateID varchar(6) PRIMARY KEY,
  Date date
)");

    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tblManuals (
  ManualID varchar(15) PRIMARY KEY,
  filename VARCHAR(255)
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
    ('020501', '2002-05-01')
");

    $wpdb->query("INSERT INTO {$wpdb->prefix}tblManuals (ManualID, filename) 
  VALUES 
    ('MAN-0001', 'Man1.pdf'), 
    ('MAN-0002', 'Man2.pdf')
");

    $wpdb->query("INSERT INTO {$wpdb->prefix}tblJoin (DateID, ProductID, ManualID)
  VALUES
    ('010501', 'C08-001-001-01-1-1', 'MAN-0001'),
    ('020501', 'C05-35-02', 'MAN-0002')
");
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'my_plugin_deactivate');
function my_plugin_deactivate()
{
    // Code to be executed on plugin deactivation
}
// Uninstall hook
register_uninstall_hook(__FILE__, 'my_plugin_uninstall');
function my_plugin_uninstall()
{
    // Code to be executed on plugin uninstallation
}
?>