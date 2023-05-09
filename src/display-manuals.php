<?php
class DisplayManuals
{
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
                <li><strong>Serial Number:</strong> <?php echo $manual->SerialNumber; ?></li>
                <li><strong>Product Code:</strong> <?php echo $manual->ProductCode; ?></li>
                <li><strong>Manual:</strong>
                    <?php $pdf_url = $manual; ?>
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
