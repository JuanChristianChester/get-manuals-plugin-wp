<?php
require_once plugin_dir_path(__FILE__) . '/manuals.php';

class DisplayManuals
{
    public static function display_serial_number_search_form()
    {
        $manual = null;
        $searchError = '';

        if (isset($_POST['submitSearch'])) {
            $serial_number = $_POST['serial_number'];
            $product_code = $_POST['product_code'];
            if (empty($serial_number) || empty($product_code)) {
                $searchError = 'Please enter a both a serial number and product code';
            } else {
                $manual = new Manual($serial_number, $product_code);
            }
        }

        self::display_search_form($searchError);
        self::display_manual_details($manual, $serial_number, $product_code);
    }

    private static function display_search_form($searchError)
    {
?>
        <h2 class="wp-block-heading has-text-align-center">Manuals</h2>
        <p class="has-text-align-center has-medium-font-size">To get the correct manual, please search using your product number and serial number below</p>
        <div class="centered-form">
            <form method="post" action="" class="my-form">
                <?php if (!empty($searchError)) { ?>
                    <p class="error"><?php echo $searchError; ?></p>
                <?php } ?>
                <label for="serial_number">Enter Serial Number:</label>
                <input type="text" name="serial_number" id="serial_number">
                <br><br>
                <label for="product_code">Enter Product Code:</label>
                <input type="text" name="product_code" id="product_code">
                <br><br>
                <input type="submit" name="submitSearch" value="Search">
            </form>
        </div>

        <?php
    }

    private static function display_manual_details($manual, $serial_number, $product_code)
    {
        if ($manual && $manual->manual) {
            $pdf_url = wp_upload_dir()['baseurl'] . '/pdfs/' . $manual->manual;
            $pdf_path = wp_upload_dir()['basedir'] . '/pdfs/' . $manual->manual;
        ?>
            <div class="manual-details-container">
                <h3>Manual Details</h3>
                <p>Please click the Manual link to download it:</p>
                <ul>
                    <li><strong>Serial Number:</strong> <?php echo $serial_number; ?></li>
                    <li><strong>Product Code:</strong> <?php echo $product_code; ?></li>
                    <li>
                        <strong>Manual:</strong>
                        <?php if (file_exists($pdf_path)) { ?>
                            <a href="<?php echo $pdf_url; ?>" target="_blank"><?php echo $manual->manual; ?></a>
                        <?php } else { ?>
                            <span class="no-manual">No manual available.</span>
                        <?php } ?>
                    </li>
                </ul>
            </div>

<?php
        }
    }
}
