<?php
namespace Rizhou\RequestLog;
class Helpers
{
    const VERSION = '1.0.2';

    /**
     * 根据User Agent提取出设备类型
     *
     * @param $userAgent
     * @return string
     */
    public static function detectDevice($userAgent) {
        $ua = strtolower($userAgent);

        $map = [
            'iphone' => 'iPhone',
            'ipad' => 'iPad',
            'android' => 'Android',
            'macintosh' => 'Mac',
            'mac os' => 'Mac',
            'windows' => 'Windows',
        ];

        foreach ($map as $keyword => $name) {
            if (str_contains($ua, $keyword)) {
                return $name;
            }
        }

        return 'other';
    }


    /**
     * 根据User Agent提取出爬虫类型
     *
     * @param $userAgent
     * @return string|null
     */
    public static function detectCrawler(string $userAgent): ?string
    {
        // 全部转小写，提高比较速度
        $ua = strtolower($userAgent);

        // 按出现频率从高到低排序，提高命中速度（小优化）
        static $bots = [
            // 👉 Google 核心爬虫
            'googlebot',               // Google 搜索主爬虫
            'adsbot-google',           // Google Ads 质量评估爬虫
            'storebot-google',         // Google Store bot

            // 👉 Google 验证、审查类
            'google-site-verification',  // Google Site Verification
            'google-page-speed',         // PageSpeed Insights
            'pagespeed',                 // 用于检测性能
            'google-inspectiontool',     // Search Console URL 检查工具

            // 👉 Google Other
            'googleother',


            // 👉 其他常见爬虫（按出现率排序）
            'bingbot',
            'baiduspider',
            'yandex',
            'sogou',
            'duckduckbot',
            'slurp',
            'ahrefsbot',
            'semrushbot',
            'mj12bot',
            'dotbot',
            'facebookexternalhit',
            'facebot',
            'exabot',
            'ia_archiver',
        ];

        foreach ($bots as $name) {
            if (str_contains($ua, $name)) {
                return $name;
            }
        }

        return 'other';
    }

    /**
     * 根据User Agent提取出浏览器类型
     *
     * @param $userAgent
     * @return string|null
     */
    public static function detectBrowser($userAgent)
    {

        $ua = strtolower($userAgent);

        $map = [
            'chrome' => 'chrome',
            'safari' => 'safari',
            'firefox' => 'firefox',
            'fxios' => 'firefox',
            'edge' => 'edge',
            'msie' => 'msie', //IE 10
            'trident' => 'trident', //IE 11
            'opera' => 'opera',
            'opr' => 'opera',
        ];

        foreach ($map as $keyword => $name) {
            if (str_contains($ua, $keyword)) {
                return $name;
            }
        }


        return 'other';
    }


    /**
     * 引入js
     *
     * @return string
     */
    public static function requireJs(){
        $path = '/vendor/request-log/stat.min.js';

        // 使用 Laravel 的 asset() 生成 URL
        $url = asset($path);

        $version = self::VERSION;

        $host = base64_encode(request()->getSchemeAndHttpHost());

        return '<script src="' . $url . '?v=' . $version . '" data-verify="'.$host.'"></script>';
    }

}