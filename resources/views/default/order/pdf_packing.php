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
                        <td>
                            <div class="main_packing">
                                <div class="main_packing_left">
                                    <h3>Order: <?php echo $val_data['OrderId']; ?></h3>
                                    <p>(<?php echo $val_data['MarketplaceName'];
                                        echo "Order: #" . $val_data['OrderId']; ?>)</p>
                                    <h4><?php echo "Order Date: &nbsp;" . $val_data['OrderDate']; ?></h4>
                                    <p><?php echo "<b>Shipping Method: </b>&nbsp;" . $val_data['ShippingMethod']; ?></p>
                                </div>
                                <div class="main_packing_right">

                                </div>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><img src="<?php echo \App\Library\Config::get('company_url') . '/assets/images/code39.PNG'; ?>" alt="smile.jpg" />&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Selling and Buying</b></td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>Ship To</b></td>
                        <td colspan="2"><b>Bill To</b></td>
                    </tr>
                    <tr>
                        <td colspan="3"><b><?php echo $val_data['ShippingName']; ?></b><br>
                            <?php echo $val_data['ShippingAddress1']; ?><br>
                            <?php echo $val_data['ShippingAddress2']; ?><br>
                            <?php echo $val_data['ShippingAddress3']; ?><br>
                            <?php echo $val_data['ShippingCity'] . "," . $val_data['ShippingState']; ?><br>
                            <?php echo $val_data['ShippingCountry']; ?><br>
                            <?php echo $val_data['ShippingPhone']; ?></td>
                        <td colspan="2"><b><?php echo $val_data['ShippingName']; ?></b><br>
                            <?php echo $val_data['ShippingAddress1']; ?><br>
                            <?php echo $val_data['ShippingAddress2']; ?><br>
                            <?php echo $val_data['ShippingAddress3']; ?><br>
                            <?php echo $val_data['ShippingCity'] . "," . $val_data['ShippingState']; ?><br>
                            <?php echo $val_data['ShippingCountry']; ?><br>
                            <?php echo $val_data['ShippingPhone']; ?></td>
                    </tr>
                    <tr>
                        <td><b>QTY</b></td>
                        <td><b>ISBN/UPC</b></td>
                        <td><b>Condition</b></td>
                        <td width="30%"><b>Description</b></td>
                        <td><b>Media</b></td>
                    </tr>
                    <tr>
                        <td><?php echo $val_data['ProductQty']; ?></td>
                        <td><?php echo $val_data['ProductISBN']; ?></td>
                        <td><?php echo $val_data['ProductCondition']; ?></td>
                        <td width="30%"><?php echo $val_data['ProductDescription']; ?></td>
                        <td>Hardcover</td>
                    </tr>

                    <tr>
                        <td colspan="5"><b>SKU : </b><?php echo $val_data['ProductSKU']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Location : </b><?php echo $val_data['ProductSKU']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Note : </b><?php echo $val_data['ProductDescription']; ?></td>
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