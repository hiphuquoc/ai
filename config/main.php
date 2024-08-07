<?php

return [
    'author_name'           => env('APP_NAME'),
    'founder_name'          => env('APP_NAME'),
    'founder_address'       => 'Viet Nam',
    'founding'              => '2024-05-30',
    'company_name'          => env('APP_NAME'),
    'hotline'               => '0869.112.676',
    'email'                 => 'topgirl.com.vn@gmail.com',
    'address'               => 'Viet Nam',
    'company_description'   => 'Giới thiệu dịch vụ',
    // 'logo_750x460'          => 'public/images/upload/trang-diem-750.webp',
    'logo_main'             => 'images/upload/logo-type-manager-upload.webp',
    'contacts'          => [
        [
            'type'      => 'customer service',
            'phone'     => '0869112676'
        ],
        [
            'type'      => 'technical support',
            'phone'     => '0869112676'
        ],
        [
            'type'      => 'sales',
            'phone'     => '0869112676'
        ]
    ],
    'products'          => [
        [
            'type'      => 'Product',
            'product'   => 'Thương mại điện tử'
        ]
    ],
    'socials'           => [
        'https://facebook.com/topgirlcomvn',
        'https://twitter.com/topgirlcomvn',
        'https://pinterest.com/topgirlcomvn',
        'https://youtube.com/topgirlcomvn'
    ],
    'storage'   => [
        'contentPage'       => 'public/contents/pages/',
        'contentBlog'       => 'public/contents/blogs/',
        'contentCategory'   => 'public/contents/categories/',
        'enContentCategory' => 'public/contents/enCategories/',
        'enContentPage'     => 'public/contents/enPages/'
    ],
    'google_cloud_storage' => [
        'default_domain'    => 'https://'.env('GOOGLE_CLOUD_STORAGE_BUCKET').'.storage.googleapis.com/',
        'wallpapers'        => 'wallpapers/',
        'sources'           => 'sources/',
        'freeWallpapers'    => 'freewallpapers/',
    ],
    'filter'    => [
        'price' => [
            [
                'name'  => 'Nhỏ hơn 100,000đ',
                'min'   => '0',
                'max'   => '100000'
            ],
            [
                'name'  => 'Từ 100,000đ - 200,000đ',
                'min'   => '100000',
                'max'   => '200000'
            ],
            [
                'name'  => 'Từ 200,000đ - 350,000đ',
                'min'   => '200000',
                'max'   => '350000'
            ],
            [
                'name'  => 'Từ 350,000đ - 500,000đ',
                'min'   => '350000',
                'max'   => '500000'
            ],
            [
                'name'  => 'Từ 500,000đ - 1,000,000đ',
                'min'   => '500000',
                'max'   => '1000000'
            ],
            [
                'name'  => 'Trên 1,000,000đ',
                'min'   => '1000000',
                'max'   => '9999999999999999999999'
            ]
        ]
    ],
    'view_by' => [
        [
            'icon'      => '<i class="fa-solid fa-gift"></i>',
            'key'       => 'each_set'
        ],
        [
            'icon'      => '<i class="fa-regular fa-image"></i>',
            'key'       => 'each_image'
        ]
    ],
    'cache'     => [
        'extension'     => 'html',
        'folderSave'    => 'public/caches/',
    ],
    'main.password_user_default' => 'hitourVN@mk123',
    'category_type' => [
        [
            'key' => 'category_info',
            'key_filter_language'   => 'filter_by_themes',
            'name' => 'Chủ đề'
        ],
        [
            'key' => 'style_info',
            'key_filter_language'   => 'filter_by_styles',
            'name' => 'Phong cách'
        ],
        [
            'key' => 'event_info',
            'key_filter_language'   => 'filter_by_events',
            'name' => 'Sự kiện'
        ]
    ],
    'sort_type' => [
        [
            'icon'      => '<i class="fa-solid fa-star"></i>',
            'key'       => 'propose',
        ],
        [
            'icon'      => '<i class="fa-solid fa-heart"></i>',
            'key'       => 'favourite',
        ],
        [
            'icon'      => '<i class="fa-solid fa-arrow-down"></i>',
            'key'       => 'newest',
        ],
        [
            'icon'      => '<i class="fa-solid fa-arrow-up"></i>',
            'key'       => 'oldest',
        ]
    ],
    'feeling_type'  => [
        [
            'icon'          => 'storage/images/svg/icon-vomit-2.svg',
            'icon_unactive' => 'storage/images/svg/icon-vomit-2-unactive.svg',
            'key'           => 'vomit',
            'name'          => 'Ói',
            'en_name'       => 'Vomit'
        ],
        [
            'icon'      => 'storage/images/svg/icon-notLike-2.svg',
            'icon_unactive' => 'storage/images/svg/icon-notLike-2-unactive.svg',
            'key'       => 'notlike',
            'name'      => 'Không thích',
            'en_name'   => 'Not like'
        ],
        [
            'icon'      => 'storage/images/svg/icon-haha-2.svg',
            'icon_unactive' => 'storage/images/svg/icon-haha-2-unactive.svg',
            'key'       => 'haha',
            'name'      => 'Haha',
            'en_name'   => 'Haha'
        ],
        [
            'icon'      => 'storage/images/svg/icon-heart-2.svg',
            'icon_unactive' => 'storage/images/svg/icon-heart-2-unactive.svg',
            'key'       => 'heart',
            'name'      => 'Thả tim',
            'en_name'   => 'Heart'
        ]
    ],
    'auto_fill' => [
        'alt'   => [
            'vi'    => 'Ảnh cô gái xinh đẹp',
            'en'    => 'Beautiful girl photo'
        ],
        'slug'  => [
            'vi'    => 'tag-anh-gai-xinh',
            'en'    => 'tag-photo-beautiful-girl'
        ]
    ],
    'url_free_wallpaper_category'   => [
        "anh-gai-xinh-mien-phi",
        "beautiful-girls-free-photos",
        "photos-de-belles-filles-gratuites",
        "fotos-de-chicas-guapas-gratis",
        "zh-beautiful-girls-free-photos",
        "ru-beautiful-girls-free-photos",
        "ja-beautiful-girls-free-photos",
        "ko-beautiful-girls-free-photos",
        "hi-beautiful-girls-free-photos",
        "foto-gadis-cantik-gratis",
    ],
    'url_confirm_page'   => [
        'xac-nhan',
        'confirm',
        'commande',
        'confirmacion',
        'zh-confirm',
        'ru-confirm',
        'ja-confirm',
        'ko-confirm',
        'hi-confirm',
        'konfirmasi',
    ],
    'url_cart_page'   => [
        'gio-hang',
        'shopping-cart',
        'panier',
        'carro-de-compras',
        'zh-shopping-cart',
        'ru-shopping-cart',
        'ja-shopping-cart',
        'ko-shopping-cart',
        'hi-shopping-cart',
        'keranjang-belanja',
    ],
    'tool_translate'    => [
        'ai', 'google_translate'
    ],
    'ai_version'    => [
        'gpt-3.5-turbo-1106', 'gpt-4o'
    ],
];