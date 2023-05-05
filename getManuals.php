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
        $manual = get_manual_by_serial_number($serial_number);
        if (!$manual) {
            $error = 'Serial number not found. Please enter a new one.';
        }
    }
?>
    <form method="post" action="">
        <label for="serial_number">Enter Serial Number:</label>
        <input type="text" name="serial_number" id="serial_number">
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
function get_manual_by_serial_number($serial_number)
{
    global $wpdb;
    $table_name = 'tblManuals';

    $manual = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE SerialNumber = %s",
        $serial_number
    ));

    return $manual;
}





// Activation hook
register_activation_hook(__FILE__, 'my_plugin_activate');
function my_plugin_activate()
{
    // Code to be executed on plugin activation
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'my_plugin_deactivate');
function my_plugin_deactivate()
{
    // Code to be executed on plugin deactivation
}

?>