<?php

// 常用图片后缀
const IMAGE_EXT  = 'png,jpg,jpeg,gif,bmp,ico,svg';
// 常用图片mime
const IMAGE_MIME = 'image/png,image/jpeg,image/gif,image/bmp,image/ico,image/svg';
// 常用视频后缀
const VIDEO_EXT = 'mp4,mov,mpg,mpeg,rmvb,avi,rm,mkv,flv,wmv';
// 常用音频后缀
const AUDIO_EXT = 'mp4,wav,mid,flac,ape,m4a,ogg,mid';
// 常用文本后缀
const TEXT_EXT = 'txt,doc,docx,xls,xlsx,ppt,pptx,pdf,md,xml';
// 常用压缩文件后缀
const ARCHIVE_EXT = 'rar,zip,tar,gz,7z,bz2,cab,iso';

return [
    // 默认磁盘
    'default'          => env('filesystem.driver', 'local'),
    // 磁盘列表
    'disks'            => [
        'local'        => [
            'type' => 'local',
            'root' => app()->getRuntimePath() . 'uploads',
        ],
        'public'       => [
            // 磁盘类型
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'public/uploads',
            // 磁盘路径对应的外部URL路径
            'url'        => '/uploads',
            // 可见性
            'visibility' => 'public',
            'validate'   => [
                'image' => [
                    'fileSize:10485760',// 10MB
                    'fileExt:' . IMAGE_EXT,
                    'fileMime:' . IMAGE_MIME,
                ],
                'video' => [
                    'fileSize:209715200',// 200MB
                    'fileExt:' . VIDEO_EXT,
                ],
                'file'  => [
                    'fileSize:419430400',// 50MB
                    'fileExt:' . IMAGE_EXT . ',' . VIDEO_EXT . ',' . AUDIO_EXT . ',' . ARCHIVE_EXT . ',' . TEXT_EXT
                ]
            ],
        ],

        // 后台导入配置
        'admin_import' => [
            // 磁盘类型
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'import',
            // 磁盘路径对应的外部URL路径
            'url'        => '',
            // 可见性
            'visibility' => 'private',
            'validate'   => [
                'file' => ['fileSize:2048000', 'fileExt:xlsx']
            ],
        ],
        // 更多的磁盘配置信息
    ],
    // 表单内是否真实删除文件
    'form_true_delete' => false,
];
