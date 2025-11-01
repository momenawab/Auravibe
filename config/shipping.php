<?php
/**
 * Shipping Configuration
 *
 * Define shipping costs for different Egyptian governorates
 */

// Shipping prices by governorate (in EGP)
const SHIPPING_PRICES = [
    // Cairo & Giza - Free or low shipping
    'Cairo' => 50,
    'Giza' => 50,
    '6th of October' => 50,
    'New Cairo' => 50,
    'Heliopolis' => 50,
    'Maadi' => 50,
    'Nasr City' => 0,
    'Zamalek' => 0,

    // Alexandria
    'Alexandria' => 70,

    // Delta Governorates
    'Dakahlia' => 70,
    'Damietta' => 70,
    'Sharqia' => 70,
    'Qalyubia' => 40,
    'Kafr El Sheikh' => 70,
    'Gharbia' => 70,
    'Monufia' => 70,
    'Beheira' => 70,
    'Ismailia' => 80,
    'Port Said' => 80,
    'Suez' => 80,

    // Upper Egypt
    'Faiyum' => 80,
    'Beni Suef' => 80,
    'Minya' => 90,
    'Asyut' => 100,
    'Sohag' => 110,
    'Qena' => 120,
    'Aswan' => 130,
    'Luxor' => 120,

    // Sinai & Red Sea
    'North Sinai' => 150,
    'South Sinai' => 140,
    'Red Sea' => 120,

    // Border Governorates
    'Matrouh' => 150,
    'New Valley' => 160
];

/**
 * Get shipping price for a governorate
 *
 * @param string $governorate Governorate name
 * @return int Shipping price in EGP
 */
function getShippingPrice($governorate) {
    if (isset(SHIPPING_PRICES[$governorate])) {
        return SHIPPING_PRICES[$governorate];
    }

    // Default shipping price if governorate not found
    return 50;
}

/**
 * Get all governorates as options for select dropdown
 *
 * @return array Governorates grouped by region
 */
function getGovernorateOptions() {
    return [
        'Cairo & Giza' => [
            'Cairo' => 'Cairo (Free Shipping)',
            'Giza' => 'Giza (Free Shipping)',
            '6th of October' => '6th of October (Free Shipping)',
            'New Cairo' => 'New Cairo (Free Shipping)',
            'Heliopolis' => 'Heliopolis (Free Shipping)',
            'Maadi' => 'Maadi (Free Shipping)',
            'Nasr City' => 'Nasr City (Free Shipping)',
            'Zamalek' => 'Zamalek (Free Shipping)',
        ],
        'Alexandria' => [
            'Alexandria' => 'Alexandria (50 EGP)',
        ],
        'Delta Governorates' => [
            'Qalyubia' => 'Qalyubia (40 EGP)',
            'Monufia' => 'Monufia (50 EGP)',
            'Dakahlia' => 'Dakahlia (60 EGP)',
            'Sharqia' => 'Sharqia (60 EGP)',
            'Gharbia' => 'Gharbia (60 EGP)',
            'Damietta' => 'Damietta (70 EGP)',
            'Kafr El Sheikh' => 'Kafr El Sheikh (70 EGP)',
            'Beheira' => 'Beheira (70 EGP)',
            'Ismailia' => 'Ismailia (80 EGP)',
            'Port Said' => 'Port Said (80 EGP)',
            'Suez' => 'Suez (80 EGP)',
        ],
        'Upper Egypt' => [
            'Faiyum' => 'Faiyum (70 EGP)',
            'Beni Suef' => 'Beni Suef (80 EGP)',
            'Minya' => 'Minya (90 EGP)',
            'Asyut' => 'Asyut (100 EGP)',
            'Sohag' => 'Sohag (110 EGP)',
            'Qena' => 'Qena (120 EGP)',
            'Luxor' => 'Luxor (120 EGP)',
            'Aswan' => 'Aswan (130 EGP)',
        ],
        'Sinai & Red Sea' => [
            'Red Sea' => 'Red Sea (120 EGP)',
            'South Sinai' => 'South Sinai (140 EGP)',
            'North Sinai' => 'North Sinai (150 EGP)',
        ],
        'Border Governorates' => [
            'Matrouh' => 'Matrouh (150 EGP)',
            'New Valley' => 'New Valley (160 EGP)',
        ]
    ];
}

/**
 * Check if shipping is free for a governorate
 *
 * @param string $governorate Governorate name
 * @return bool True if shipping is free
 */
function isFreeShipping($governorate) {
    return getShippingPrice($governorate) === 0;
}
