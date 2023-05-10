<?php
/*
Plugin Name: Manual Uploader
*/

add_shortcode('manual_uploader', 'display_manual_uploader_form');

function display_manual_uploader_form()
{
    global $wpdb;

    $message = '';
    if (isset($_POST['submit'])) {
        $serial_number = sanitize_text_field($_POST['serial_number']);
        $product_code = sanitize_text_field($_POST['product_code']);

        // Check if the serial number and product code are already in the database
        $manual_exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}tblJoin WHERE DateID = %s AND ProductID = %s",
            date('ymd'),
            $product_code
        ));

        if ($manual_exists) {
            $message = 'A manual for this product and date already exists in the database.';
        } else {
            // Save the manual to the database
            $manual_id = uniqid();
            $filename = sanitize_file_name($_FILES['manual_file']['name']);
            $pdf_data = file_get_contents($_FILES['manual_file']['tmp_name']);

            $wpdb->query($wpdb->prepare(
                "INSERT INTO {$wpdb->prefix}tblManuals (ManualID, filename, pdf) VALUES (%s, %s, %s)",
                $manual_id,
                $filename,
                $pdf_data
            ));

            $wpdb->query($wpdb->prepare(
                "INSERT INTO {$wpdb->prefix}tblJoin (DateID, ProductID, ManualID) VALUES (%s, %s, %s)",
                date('ymd'),
                $product_code,
                $manual_id
            ));

            $message = 'Manual uploaded successfully!';
        }
    }

    ob_start();
?>
    <form method="post" enctype="multipart/form-data">
        <label for="serial_number">Serial Number:</label>
        <input type="text" name="serial_number" id="serial_number">
        <br><br>
        <label for="product_code">Product Code:</label>
        <input type="text" name="product_code" id="product_code">
        <br><br>
        <label for="manual_file">Manual File:</label>
        <input type="file" name="manual_file" id="manual_file">
        <br><br>
        <input type="submit" name="submit" value="Upload">
    </form>

    <?php if ($message) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>

<?php
    return ob_get_clean();
}

?>