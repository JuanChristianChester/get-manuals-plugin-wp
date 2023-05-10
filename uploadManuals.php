<?php
/*
Plugin Name: Manual Uploader
*/

// Register shortcode
add_shortcode('manual_uploader', 'display_manual_uploader_form');

// Function to display the upload form
function display_manual_uploader_form()
{
    global $wpdb;

    // Initialize variables
    $error = '';
    $success = '';

    // Handle form submission
    if (isset($_POST['submit'])) {
        $file = $_FILES['file'];
        $manual_id = $_POST['manual_id'];
        $filename = $file['name'];
        $filetype = $file['type'];
        $filecontent = file_get_contents($file['tmp_name']);
        $filesize = $file['size'];

        // Check if manual ID already exists in the database
        $existing_manual = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}tblManuals WHERE ManualID = %s", $manual_id));
        if ($existing_manual) {
            $error = 'Manual ID already exists. Please choose a different one.';
        } else {
            // Insert manual into database
            $result = $wpdb->insert("{$wpdb->prefix}tblManuals", array(
                'ManualID' => $manual_id,
                'filename' => $filename,
                'pdf' => $filecontent
            ), array('%s', '%s', '%s'));

            // Check if insert was successful
            if ($result) {
                $success = 'Manual uploaded successfully.';
            } else {
                $error = 'Error uploading manual.';
            }
        }
    }

    // Display form
    ob_start();
?>
    <form method="post" enctype="multipart/form-data">
        <label for="manual_id">Manual ID:</label>
        <input type="text" name="manual_id" id="manual_id">
        <br><br>
        <label for="file">Upload Manual:</label>
        <input type="file" name="file" id="file">
        <br><br>
        <input type="submit" name="submit" value="Upload">
    </form>

    <?php if ($error) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>

    <?php if ($success) { ?>
        <p><?php echo $success; ?></p>
    <?php } ?>
<?php
    return ob_get_clean();
}
?>