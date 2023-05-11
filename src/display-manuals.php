<?php
require_once plugin_dir_path(__FILE__) . '/manuals.php';

class DisplayManuals
{
    public static function display_serial_number_search_form()
    {
        $manual = null;

        if (isset($_POST['submit'])) {
            $serial_number = $_POST['serial_number'];
            $product_code = $_POST['product_code'];
            $manual = new Manual($serial_number, $product_code);
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

        <?php if ($manual->manual) { ?>
            <h3>Manual Details</h3>
            <p>Please click the Manual link to download it:</p>
            <ul>
                <li><strong>Serial Number:</strong> <?php echo $serial_number; ?></li>
                <li><strong>Product Code:</strong> <?php echo $product_code; ?></li>
                <li><strong>Manual:</strong>
                    <?php $pdf_url = wp_upload_dir()['baseurl'] . '/pdfs/' . $manual->manual; ?>
                    <?php $pdf_path = wp_upload_dir()['basedir'] . '/pdfs/' . $manual->manual; ?>

                    <?php if (file_exists($pdf_path)) { ?>
                        <a href="<?php echo $pdf_url; ?>" target="_blank"><?php echo $manual->manual; ?></a>
                    <?php } else { ?>
                        No manual available.
                    <?php } ?>
                </li>
            </ul>
        <?php } ?>

<?php
    }
}
