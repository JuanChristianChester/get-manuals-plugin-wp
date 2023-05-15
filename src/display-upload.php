<?php
/*
Plugin Name: Manual Uploader
*/
require_once plugin_dir_path(__FILE__) . 'database.php';
add_shortcode('manual_uploader', 'display_manual_uploader_form');
class DisplayUpload
{
    public $error = "";
    public $serial_number;
    public $product_code;
    public function display_manual_uploader()
    {
        global $wpdb;
        $success = false;
        $pdf_file = null;
        if (isset($_POST['submit'])) {
            // Get form data
            $this->serial_number = sanitize_text_field($_POST['serial_number']);
            $this->product_code = sanitize_text_field($_POST['product_code']);
            $pdf_file = $_FILES['pdf_file'];
            // Validate form data
            if (!$this->serial_number || !$this->product_code) {
                $this->error = 'Please enter both a serial number and product code';
            } elseif (!$pdf_file || $pdf_file['error'] !== UPLOAD_ERR_OK) {
                $this->error = 'Please select a PDF file to upload';
            } else {
                $success = Database::save_pdf($pdf_file, $wpdb, $this->serial_number, $this->product_code);
            }
        }
?>
        <?php if ($success) { ?>
            <p>Manual uploaded successfully.</p>
            <button onclick="window.location.href='upload-manuals'">Click here to upload another manual</button>
        <?php } else { ?>
            <p><?php echo ($this->error) ?></p>
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
<?php
        }
    }
}
