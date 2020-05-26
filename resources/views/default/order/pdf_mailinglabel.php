<!DOCTYPE html>
<html>

<head>
    <title></title>
</head>

<body>
    <table class='table' id='custom_tbl' border="2" width="100%" style="border-collapse: collapse;">
        <thead>
        </thead>
        <tbody>
            <?php if (isset($pdf_data) && !empty($pdf_data)) { ?>
                <?php foreach (array_chunk($pdf_data, 3) as $row_key => $row_val) { ?>
                    <tr>
                        <?php foreach ($row_val as $val_pdf) { ?>
                            <td scope='col'>
                                <?php echo $val_pdf['ShippingName'] . "<br>"; ?>
                                <?php echo $val_pdf['ShippingAddress1'] . "<br>"; ?>
                                <?php echo $val_pdf['ShippingAddress2'] . "<br>"; ?>
                                <?php echo $val_pdf['ShippingAddress3'] . "<br>"; ?>
                                <?php echo $val_pdf['ShippingCity'] . "," . $val_pdf['ShippingState'] . "<br>"; ?>
                                <?php echo $val_pdf['ShippingCountry']; ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>No Records Found</tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>