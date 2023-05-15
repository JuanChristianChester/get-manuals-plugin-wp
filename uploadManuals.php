<?php
/*
Plugin Name: Manual Uploader
*/

add_shortcode('manual_uploader', 'display_manual_uploader_form');

function display_manual_uploader_form()
{
    global $wpdb;
    $error = '';
    $success = false;

    if (isset($_POST['submit'])) {
        // Get the last manual ID from the database
        $last_manual_id = $wpdb->get_var("SELECT ManualID FROM {$wpdb->prefix}tblManuals ORDER BY ManualID DESC LIMIT 1");

        // Increment the last manual ID to get the new manual ID
        $new_manual_id = 'MAN-' . str_pad((intval(substr($last_manual_id, 4)) + 1), 4, '0', STR_PAD_LEFT);

        // Get form data
        $serial_number = sanitize_text_field($_POST['serial_number']);
        $product_code = sanitize_text_field($_POST['product_code']);
        $pdf_file = $_FILES['pdf_file'];

        // Validate form data
        if (!$serial_number || !$product_code) {
            $error = 'Please enter serial number and product code';
        } elseif (!$pdf_file || $pdf_file['error'] !== UPLOAD_ERR_OK) {
            $error = 'Please select a PDF file to upload';
        } else {

            // Save PDF file to wp-content/uploads/pdfs folder
            $upload_dir = wp_upload_dir();
            $target_dir = $upload_dir['basedir'] . '/pdfs';
            $file_name = $pdf_file['name'];
            $target_file = $target_dir . '/' . $file_name;


            if (move_uploaded_file($pdf_file['tmp_name'], $target_file)) {
                // Insert manual into database
                $wpdb->query(
                    $wpdb->prepare(
                        "INSERT INTO {$wpdb->prefix}tblManuals (ManualID, filename, pdf) VALUES (%s, %s, %s)",
                        $new_manual_id,
                        $file_name,
                        $target_file
                    )
                );

                // Insert join data into database
                $wpdb->query(
                    $wpdb->prepare(
                        "INSERT INTO {$wpdb->prefix}tblJoin (DateID, ProductID, ManualID) VALUES (%s, %s, %s)",
                        $serial_number,
                        $product_code,
                        $new_manual_id
                    )
                );

                // Insert into tblDate
                $date_id = $serial_number;
                $date = date('Y-m-d', strtotime('20' . substr($date_id, 0, 2) . '-' . substr($date_id, 2, 2) . '-' . substr($date_id, 4, 2)));

                $insert_date = $wpdb->insert("{$wpdb->prefix}tblDate", array(
                    'DateID' => $date_id,
                    'Date' => $date
                ));

                $success = true;
            } else {
                $error = 'Failed to save PDF file';
            }
        }
    }
?>
    <?php if ($success) { ?>
        <p>Manual uploaded successfully.</p>
    <?php } else { ?>
        <?php if ($error) { ?>
            <p><?php echo $error; ?></p>
        <?php } ?>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="serial_number">Serial Number:</label>
            <input type="text" name="serial_number" id="serial_number">
            <br><br>
            <label for="product_code">Product Code:</label>
            <input type="text" name="product_code" id="product_code">
            <br><br>
            <label for="pdf_file">Select PDF File:</label>
            <input type="file" name="pdf_file" id="pdf_file">
            <br><br>
            <input type="submit" name="submit" value="Upload">
        </form>
    <?php } ?>
<?php
}

?>