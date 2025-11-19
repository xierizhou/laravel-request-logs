<?php

namespace Rizhou\RequestLog;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class RequestLogData
{
    const VERSION = "1.0.0";

    protected Request $request;
    protected Response $response;
    protected array $domainPatterns = [];
    protected array $pathPatterns = [];
    protected array $logMethods = [];
    protected string $path;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;


        $config = config('request-log');

        // 缓存域名模式
        $this->domainPatterns = $config['ignore_domains'] ?? [];

        // 缓存路径模式
        $this->pathPatterns = $config['ignore_paths'] ?? [];

        // 缓存方法
        $this->logMethods = $config['log_methods'] ?? ['GET', 'POST'];

        $this->path = '/' . ltrim($this->request->path(), '/');
    }

    /**
     * 返回日志数组
     */
    public function toArray(): array
    {
        return [
            'method'      => $this->request->method(),
            'url'         => $this->path,
            'ip'          => $this->request->ip(),
            'ipcountry'   => $this->request->header('cf-ipcountry', 'unknown'),
            'host'        => $this->request->getHost(),
            'user_agent'  => $this->request->userAgent(),
            'input'       => $this->request->all(),
            'status_code' => $this->response->getStatusCode(),
            'referer'     => $this->request->headers->get('referer'),
            'language'    => $this->request->headers->get('accept-language'),
            'is_ajax'     => $this->request->ajax(),
        ];
    }

    /**
     * 判断请求是否需要记录
     */
    public function shouldLog(): bool
    {
        $host = $this->request->getHost();
        $path = $this->path;

        //忽略域名
        foreach ($this->domainPatterns as $pattern) {

            if ($pattern && $this->matchDomain($host, $pattern)) {

                return false;
            }
        }

        //忽略路径
        foreach ($this->pathPatterns as $pattern) {

            if ($pattern && $this->matchPath($path, $pattern)) {
                return false;
            }
        }


        //方法过滤
        if (!in_array($this->request->method(), $this->logMethods, true)) {
            return false;
        }

        //可扩展闭包规则
        if ($rule = config('request-log.custom_rule', null)) {
            if (is_callable($rule) && !$rule($this->request)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 域名匹配，支持 * 通配符
     */
    protected function matchDomain(string $host, string $pattern): bool
    {
        // 精确匹配
        if ($host === $pattern) return true;

        // *.example.com
        /*if (str_starts_with($pattern, '*')) {
            $suffix = substr($pattern, 1); // 去掉 *
            return str_ends_with($host, $suffix);
        }*/

        return false;
    }

    /**
     * 路径匹配，支持前缀 *
     */
    protected function matchPath(string $path, string $pattern): bool
    {

        // 精确匹配
        if ($path === $pattern) return true;
        // /admin/* 前缀匹配
        if (str_ends_with($pattern, '*')) {
            $prefix = rtrim($pattern, '*');
            return str_starts_with($path, $prefix);
        }

        return false;
    }



}