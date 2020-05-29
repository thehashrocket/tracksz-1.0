<!DOCTYPE html>
<html>

<head>
    <title></title>
</head>

<body>

    <?php

    if (isset($pdf_data) && !empty($pdf_data)) {
        foreach ($pdf_data as $key_data => $val_data) {
    ?>
            <table class='table' id='custom_tbl' border="2" width="100%" style="border-collapse: collapse;">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td>Order </td>
                        <td>SKU/ASIN/UPC</td>
                        <td>Location</td>
                        <td>Category</td>
                        <td>Price</td>
                        <td>QTY</td>
                    </tr>
                    <tr>
                        <td>Barcode </td>
                        <td>Description</td>
                        <td></td>
                        <td>Condition</td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Note</td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td><img src="" /></td>
                        <td><?php echo $val_data['ProductSKU']; ?></td>
                        <td><?php echo $val_data['ProductISBN']; ?><br>
                            <?php echo $val_data['ProductDescription']; ?><br>
                            <?php echo $val_data['BillingCity'] . " ," . $val_data['BillingState']; ?>
                        </td>
                        <td>Hardcore<br>
                            <?php echo $val_data['ProductBuyerNote'] . " - " . $val_data['ProductCondition']; ?><br></td>
                        <td><?php echo $val_data['ProductPrice']; ?></td>
                        <td><?php echo $val_data['ProductQty']; ?></td>
                    </tr>

                </tbody>
            </table>
            <br><br><br><br><br><br>
        <?php } // Loops Ends
    } else { ?>
        <h1>No Records found</h1>
    <?php } ?>
</body>

</html>