<?php
namespace Rizhou\RequestLog;
class Helpers
{
    const VERSION = '1.0.1';

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

        foreach ($map as $key => $name) {
            if (strpos($ua, $key) !== false) return $name;
        }

        return 'Other';
    }


    /**
     * 根据User Agent提取出爬虫类型
     *
     * @param $userAgent
     * @return string|null
     */
    public static function detectCrawler($userAgent)
    {
        $pattern = '/googlebot|bingbot|slurp|baiduspider|yandex|duckduckbot|sogou|exabot|ahrefsbot|mj12bot|semrushbot|dotbot|facebot|ia_archiver|facebookexternalhit/i';

        if (preg_match($pattern, $userAgent, $matches)) {
            return strtolower($matches[0]);
        }
        return null;
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