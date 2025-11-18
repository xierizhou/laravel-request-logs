<?php
namespace Rizhou\RequestLog\Models;

use Illuminate\Database\Eloquent\Model;
use Rizhou\RequestLog\Helpers;


class RequestLog extends Model
{
    /**
     * 动态读取配置表名
     */
    protected $table;

    protected $fillable = [
        'method', 'url', 'ip', 'ipcountry', 'host', 'user_agent', 'input', 'status_code','referer','is_js', 'language', 'screen_width', 'screen_height', 'timezone',
        'device', 'crawler'
    ];

    protected $casts = [
        'input' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // 读取配置
        $this->table = config('request-log.table', 'request_logs');
    }

    /**
     * 自动填充字段
     *
     * @return void
     */
    protected static function booted()
    {

        static::creating(function ($log) {
            // 根据 user_agent 填充 device
            $log->device = Helpers::detectDevice($log->user_agent);
            // 根据 user_agent 填充 crawler
            $log->crawler = Helpers::detectCrawler($log->user_agent);
        });
    }

}
