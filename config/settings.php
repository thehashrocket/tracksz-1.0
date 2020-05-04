<?php

declare(strict_types=1);

return [
    'system_status' => 'dev',
    'company_name'  => 'Tracksz',
    'company_moto'  => 'Tracksz Inventory and Order Tracking',
    'company_phone' => '(314) 390-1696',
    'company_url'   => 'http://localhost:8000',
    'web_dir'       => '/var/www/aTrackzMain/framework/public/',
    // form country/state
    'default_country' => 223,
    'default_zone'    => 3648,
    'migration_folder' => '/config/migrations/',
    'SlmQueue' => 'slm_queue.global.php.dist',
    // default page list rows
    'page_rows' => '30',

    // default login remember me time, three weeks
    'default_remember' => 1814400,
    // currencies used in multiple locations
    'currency'  => [
        'USD'   => 'US Dollar',
        'AUD'   => 'Australian Dollar',
        'GBP'   => 'British Pound',
        'CAD'   => 'Canadian Dollar',
    ],
    // Link types saved in separate table
    'link_types' => [
        'WebSite'   => 1,
        'FaceBook'  => 2,
        'Instagram' => 3,
        'Twitter'   => 4,
        'Pinterest' => 5,
    ],
    // Link types with link id as key for easy name look up
    'link_types_reverse' => [
        1   => 'WebSite',
        2   => 'FaceBook',
        3   => 'Instagram',
        4   => 'Twitter',
        5   => 'Pinterest',
    ],
    'market_stores' => [
        'amazon', 'ebay', 'flipkart'
    ],

    'market_place_map' => [
        'Chrislands.com' => [
            'Author' => 'Name', 'ISBN' => 'ProdId', 'Price' => 'BasePrice', 'Book condition' => 'ProdCondition', 'Quantity' => 'Qty',
            'AddtionalData' => [
                'Seller ID' => 'Seller',
                "Title" => "Title",
                "Illustrator" => "Illustrator",
                "Book size" => "BookSize",
                "Jacket condition" => "JacketCondition",
                "Binding" => "Binding",
                "Book TYPE" => "BookType",
                "Publisher" => "Publisher",
                "Publish place" => "PublishPlace",
                "Publish DATE" => "PublishDate",
                "Edition" => "Edition",
                "Inscription" => "Inscription",
                "DESCRIPTION" => "Description",
                "Image" => "Image",
                "Category 1" => "Category1",
                "Category 2" => "Category2",
                "Category 3" => "Category3",
                "Category 4" => "Category4",
                "Category 5" => "Category5",
                "Keyword 1" => "Keyword1",
                "Keyword 2" => "Keyword2",
                "Keyword 3" => "Keyword3",
                "Keyword 4" => "Keyword4",
                "Keyword 5" => "Keyword5",
                "Keyword 6" => "Keyword6",
                "Keyword 7" => "Keyword7",
                "Keyword 8" => "Keyword8",
                "Keyword 9" => "Keyword9",
                "Weight" => "Weight",
                "Featured Item" => "FeaturedItem"
            ]
        ],
        'UIEEFile' => [
            'UR' => 'SKU',
            'BN' => 'ProdId',
            'TI' => 'Name',
            'CN' => 'ProdCondition',
            'NT' => 'Notes',
            'PR' => 'BasePrice',
            'CO' => 'Qty',
            'AddtionalData' => [
                'AA' => 'Author',
                'WT' => 'Weight',
                'XA' => '4',
                'XB' => '1',
                'XC' => 'BO',
                'XD' => 'S',
                'KE' => 'Keywords',
                'MT' => 'Category',
                'PU' => 'Publisher',
                'DP' => 'DatePublished',
                'ED' => 'Edition',
                'BD' => 'Binding'
            ]
        ],

    ],
    'market_attributes' => [
        'productNameInput' => [
            'amazon' => 'name',
            'ebay' => 'prod_name',
            'flipkart' => 'flip_prod_name',
            'Chrislands.com' => 'Author'
        ],
        'productSKUInput' => [
            'amazon' => 'sku',
            'ebay' => 'prod_sku',
            'flipkart' => 'flip_prod_sku',
            'Chrislands.com' => ''
        ],
        'productIdInput' => [
            'amazon' => 'id',
            'ebay' => 'prod_id',
            'flipkart' => 'flip_prod_id',
            'Chrislands.com' => 'ISBN'
        ],
        'productBasePriceInput' => [
            'amazon' => 'price',
            'ebay' => 'prod_price',
            'flipkart' => 'flip_prod_price',
            'Chrislands.com' => 'Price'
        ],
        'productCondition' => [
            'amazon' => 'condition',
            'ebay' => 'prod_condition',
            'flipkart' => 'flip_prod_condition',
            'Chrislands.com' => 'Book condition'
        ],
        'productActive' => [
            'amazon' => 'active',
            'ebay' => 'prod_active',
            'flipkart' => 'flip_prod_active',
            'Chrislands.com' => ''
        ],
        'productinternationalShip' => [
            'amazon' => 'internation_shipping',
            'ebay' => 'prod_internation_shipping',
            'flipkart' => 'flip_prod_internation_shipping',
            'Chrislands.com' => ''
        ],
        'productExpectedShip' => [
            'amazon' => 'expected_shipping',
            'ebay' => 'prod_internation_shipping',
            'flipkart' => 'flip_prod_internation_shipping',
            'Chrislands.com' => ''
        ],
        'productTitleeBayInput' => [
            'amazon' => 'title',
            'ebay' => 'prod_ebay_title',
            'flipkart' => 'flip_prod_ebay_title',
            'Chrislands.com' => ''
        ],
        'productQtyInput' => [
            'amazon' => 'qty',
            'ebay' => 'prod_qty',
            'flipkart' => 'flip_prod_qty',
            'Chrislands.com' => 'Quantity'
        ],
        'productNote' => [
            'amazon' => 'note',
            'ebay' => 'prod_note',
            'flipkart' => 'flip_prod_note',
            'Chrislands.com' => ''
        ]
    ],
    'asset_path' => '/public/assets/',
    'image_path' => '/public/assets/images/',
    // Market Places
    'market_places' => [
        'AbeBooks', 'Alibris', 'Amazon', 'Amazon Europe', 'Barnes and Noble',
        'Biblio',
        'Chrislands.com',
        'eBay',
        'eCampus',
        'TextbookRush.com',
        'TextbookX',
        'Valore'
    ],

    // Market Price
    'market_price' => [
        'USD' => 'US Dollars',
        'CAD' => 'Canadian Dollars',
        'GBP' => 'British Pounds',
        'EUR' => 'Euros',
        'MXN' => 'Mexican Pesos',
    ]


];
