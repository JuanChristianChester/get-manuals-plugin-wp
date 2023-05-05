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

/// Function to display the search form and manual details
function display_serial_number_search_form()
{
    global $wpdb;
    $manual = null;
    $error = '';

    if (isset($_POST['submit'])) {
        $serial_number = $_POST['serial_number'];
        $model = $_POST['model'];
        $manual = get_manual_by_serial_number($serial_number, $model);
        if (!$manual) {
            $error = 'Serial number not found. Please enter a new one.';
        }
    }
?>
    <form method="post" action="">
        <label for="serial_number">Enter Serial Number:</label>
        <input type="text" name="serial_number" id="serial_number">
        <label for ="model">Enter Model:</label>
        <input type="text" name="model" id="model">
        <input type="submit" name="submit" value="Search">
    </form>

    <?php if ($error) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>

    <?php if ($manual) { ?>
        <h3>Manual Details</h3>
        <ul>
            <li><strong>Serial Number:</strong> <?php echo $manual->SerialNumber; ?></li>
            <li><strong>Manual:</strong> <a href="<?php echo $manual->Manual; ?>" target="_blank"><?php echo $manual->Manual; ?></a></li>
        </ul>
    <?php } ?>
<?php
}


//Function to search the DB for the serial number and return the manual
function get_manual_by_serial_number($date, $model)
{
    global $wpdb;
    $table_name = 'tblManuals';

    //get the manual from the database using the serial number and model
    $manual = $wpdb->get_row("SELECT m.pdf
    FROM $table_name m
    INNER JOIN tblJoin j ON m.ManualID = j.ManualID
    INNER JOIN tblDate d ON j.DateID = d.DateID
    WHERE d.Date = '$date'
      AND j.ProductID = '$model';
    ");
    return $manual;
}

// hook
register_activation_hook(__FILE__, 'my_plugin_activate');
function my_plugin_activate()
{ 
  global $wpdb;
  //get sql file and store it as a string
  $sql = file_get_contents( plugin_dir_path( __FILE__ ) . 'sql/tblManuals.sql');
  //replace the prefix with the wordpress prefix
  $sql = str_replace('wp_', $wpdb->prefix, $sql);
  //split the sql file into separate queries
  $sql = explode(';', $sql);
  //require the upgrade file
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  //run each query
  foreach($sql as $query){
    $wpdb->query($query);
  }
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'my_plugin_deactivate');
function my_plugin_deactivate()
{
    // Code to be executed on plugin deactivation
}

?>