<?php declare(strict_types = 1);

return [
    
    // Tax Classes
    'tax_class' => [
        1   => 'Product',
        2   => 'Service',
        3   => 'Not Taxable',
    ],
    // Inventory Status
    'stock_status' => [
        1   => 'Out of Stock',
        2   => '2-3 Days',
        3   => '7-10 Days',
        4   => 'In Stock',
        5   => 'Pre-Order',
    ],
    // Weight Class
    'weight_class' => [
        1   => ['Title' => 'Kilogram', 'Unit' => 'kg', 'Value' => 1.00000000],
        2   => ['Title' => 'Gram', 'Unit' => 'g', 'Value' => 1000.00000000],
        3   => ['Title' => 'Ounce', 'Unit' => 'oz', 'Value' => 35.27400000],
        4   => ['Title' => 'Pound', 'Unit' => 'lb', 'Value' => 2.20460000],
    ],
    // Length Class
    'length_class' => [
        1   => ['Title' => 'Inch', 'Unit' => 'in', 'Value' => 0.39370100],
        2   => ['Title' => 'Centimeter', 'Unit' => 'cm', 'Value' => 1.00000000],
        3   => ['Title' => 'Milimeter', 'Unit' => 'mm', 'Value' => 10.00000000],
    ],
    // Conditions
    'conditions' => [
        1   => 'New',
        2   => 'Used',
        3   => 'Renewed',
        4   => 'Used - Like New',
        5   => 'Used - Very Good',
        6   => 'Used - Good',
        7   => 'Used - Acceptable',
    ],

];
