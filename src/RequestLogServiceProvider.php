<?php
namespace Rizhou\RequestLog;

use Illuminate\Support\ServiceProvider;
use Rizhou\RequestLog\Middleware\RequestLogger;
class RequestLogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 发布配置
        $this->publishes([
            __DIR__ . '/../config/request-log.php' => config_path('request-log.php'),
        ], 'config');

        // 发布 Migration
        if (!class_exists('CreateRequestLogsTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_request_logs_table.php' => database_path('migrations/' . date('Y_m_d_His') . '_create_request_logs_table.php')
            ], 'migrations');
        }

        // publish public assets (stat.js)
        $this->publishes([
            __DIR__.'/../public/stat.min.js' => public_path('vendor/request-log/stat.min.js'),
        ], 'request-log-assets');


        $router = $this->app['router'];

        // 注册路由中间件别名
        $router->aliasMiddleware(config('request-log.middleware_alias', 'request.log'),RequestLogger::class);

        //注册Js路由
        $router->group([
            'middleware' => 'api',
            'namespace' => 'Rizhou\RequestLog\Http\Controllers',
        ], function ($router) {
            $router->post('_log_col', 'JsLogController@collect');
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/request-log.php', 'request-log');

        require_once __DIR__ . '/_helpers.php';
    }



}
