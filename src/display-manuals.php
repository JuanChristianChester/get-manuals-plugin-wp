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

        <?php if (!$manual->manual) { ?>
            <p>Serial number and/or product code not found. Please enter valid ones.</p>
        <?php } ?>

        <?php if ($manual->manual) { ?>
            <h3>Manual Details</h3>
            <ul>
                <li><strong>Serial Number:</strong> <?php echo $manual->serialNumber; ?></li>
                <li><strong>Product Code:</strong> <?php echo $manual->productCode; ?></li>
                <li><strong>Manual:</strong>
                    <?php $pdf_url = $manual->manual; ?>
                    <?php if ($pdf_url) { ?>
                        <a href="https://2126669.linux.studentwebserver.co.uk/SATSystems/wp-content/uploads/2023/05/<?php echo $pdf_url; ?>" target="_blank"><?php echo $pdf_url; ?></a>
                    <?php } else { ?>
                        No manual available.
                    <?php } ?>
                </li>
            </ul>
        <?php } ?>
<?php
    }
}