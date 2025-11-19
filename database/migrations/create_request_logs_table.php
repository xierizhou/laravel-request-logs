<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestLogsTable extends Migration
{
    public function up()
    {
        Schema::create(config('request-log.table', 'request_logs'), function (Blueprint $table) {
            // 指定 MyISAM 引擎
            $table->engine = 'MyISAM';

            $table->id();

            // 请求信息
            $table->string('method', 10)->index();
            $table->string('url', 191)->index();
            $table->string('host', 100)->index();
            $table->ipAddress('ip')->index();
            $table->string('ipcountry')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device',100)->nullable()->index();
            $table->string('crawler',100)->nullable()->index();
            $table->string('browser',100)->nullable()->index();
            $table->string('referer',191)->nullable();
            $table->boolean('is_ajax')->default(false)->index(); //true=ajax请求

            // 输入参数（JSON 格式）
            $table->json('input')->nullable();

            // 响应状态码
            $table->smallInteger('status_code')->nullable()->index();

            // 其它数据
            $table->boolean('is_js')->default(false); //false=服务器记录 true=JS记录
            $table->smallInteger('screen_width')->nullable(); //设备宽度
            $table->smallInteger('screen_height')->nullable(); //设备高度
            $table->string('language')->nullable(); //浏览器语言
            $table->string('timezone')->nullable(); //时区

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('request-log.table', 'request_logs'));
    }
};
