<?php

return [
    'file_max_size' => env('NC_FILE_MAX_SIZE', 2048),
    'file_extensions' => env('NC_FILE_EXTENSIONS', 'jpeg,bmp,png,jpg,gif,svg,avif,webm,mp4,pdf,mov,csv,xlsx,xls,m4a,mp3,wav,wma,aac'),
    'image_max_size' => env('NC_IMAGE_MAX_SIZE', 2048),
    'image_extensions' => env('NC_IMAGE_MAX_EXTENSIONS', 'jpeg,bmp,png,jpg,gif,svg,avif,webm'),
    'image_max_width' => env('NC_IMAGE_MAX_WIDTH', 1024),
    'image_max_height' => env('NC_IMAGE_MAX_HEIGHT'),
    'image_thumbnail' => env('NC_IMAGE_THUMBNAIL', false),
    'image_thumbnail_width' => env('NC_IMAGE_THUMBNAIL_WIDTH', 200),
    'auth_guard' => env('NC_AUTH_GUARD', 'api'),
];
