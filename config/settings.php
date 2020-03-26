<?php declare(strict_types = 1);

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
'asset_path' => '/public/assets/',
    'image_path' => '/public/assets/images/',
    // Market Places
    'market_places' => ['AbeBooks','Alibris','Amazon','Amazon Europe','Barnes and Noble',
    'Biblio',
    'Chrislands.com',
    'eBay',
    'eCampus',
    'TextbookRush.com',
    'TextbookX',
    'Valore'
],

      // Market Price
      'market_price' => ['USD' =>'US Dollars',
       'CAD' => 'Canadian Dollars',
       'GBP' => 'British Pounds',
       'EUR' => 'Euros',
       'MXN' => 'Mexican Pesos',
      ]
];
