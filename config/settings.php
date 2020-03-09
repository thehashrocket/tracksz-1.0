<?php declare (strict_types = 1);

return [
    'system_status' => 'dev',
    'company_name' => 'Tracksz',
    'company_moto' => 'Tracksz Inventory and Order Tracking',
    'company_phone' => '(314) 390-1696',
    'company_url' => 'http://localhost:8000',
    'web_dir' => 'D:\xampp\htdocs\tracksz\public',

    // form country/state
    'default_country' => 223,
    'default_zone' => 3648,

    // default login remember me time, three weeks
    'default_remember' => 1814400,

    // database migration folder location - from framework root
    // If used adjest location from the depth you are at
    'migration_folder' => '/config/migrations/',

    // currencies used in multiple locations
    'currency' => [
        'USD' => 'US Dollar',
        'AUD' => 'Australian Dollar',
        'GBP' => 'British Pound',
        'CAD' => 'Canadian Dollar',
    ],

    // Link types saved in separate table
    'link_types' => [
        'WebSite' => 1,
        'FaceBook' => 2,
        'Instagram' => 3,
        'Twitter' => 4,
        'Pinterest' => 5,
    ],

    // Link types with link id as key for easy name look up
    'link_types_reverse' => [
        1 => 'WebSite',
        2 => 'FaceBook',
        3 => 'Instagram',
        4 => 'Twitter',
        5 => 'Pinterest',
    ],
    'market_stores' => [
       'amazon','ebay','flipkart'
    ],
    'market_attributes' => [
        'productNameInput' => [
            'amazon' => 'name',
            'ebay' => 'prod_name',
            'flipkart' => 'flip_prod_name'
        ],
        'productSKUInput' => [
            'amazon' => 'sku',
            'ebay' => 'prod_sku',
            'flipkart' => 'flip_prod_sku'
        ],
        'productIdInput' => [
            'amazon' => 'id',
            'ebay' => 'prod_id',
            'flipkart' => 'flip_prod_id'
        ],
        'productBasePriceInput' => [
            'amazon' => 'price',
            'ebay' => 'prod_price',
            'flipkart' => 'flip_prod_price'
        ],
        'productCondition' => [
            'amazon' => 'condition',
            'ebay' => 'prod_condition',
            'flipkart' => 'flip_prod_condition'
        ],
        'productActive' => [
            'amazon' => 'active',
            'ebay' => 'prod_active',
            'flipkart' => 'flip_prod_active'
        ],
        'productinternationalShip' => [
            'amazon' => 'internation_shipping',
            'ebay' => 'prod_internation_shipping',
            'flipkart' => 'flip_prod_internation_shipping'
        ],
        'productExpectedShip' => [
            'amazon' => 'expected_shipping',
            'ebay' => 'prod_internation_shipping',
            'flipkart' => 'flip_prod_internation_shipping'
        ],
        'productTitleeBayInput' => [
            'amazon' => 'title',
            'ebay' => 'prod_ebay_title',
            'flipkart' => 'flip_prod_ebay_title'
        ],
        'productQtyInput' => [
            'amazon' => 'qty',
            'ebay' => 'prod_qty',
            'flipkart' => 'flip_prod_qty'
        ],
        'productNote' => [
            'amazon' => 'note',
            'ebay' => 'prod_note',
            'flipkart' => 'flip_prod_note'
        ]
    ],
];
