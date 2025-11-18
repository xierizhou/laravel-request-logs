<?php
namespace Rizhou\RequestLog\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Rizhou\RequestLog\Models\RequestLog;
use Rizhou\RequestLog\RequestLogData;

class RequestLogger
{
    public function handle($request, Closure $next)
    {

        $response = $next($request);
        try {
            $logData = new RequestLogData($request, $response);

            $requestId = 0;
            if ($logData->shouldLog()) {
                $model = RequestLog::create($logData->toArray());
                $requestId = $model->id;
                $this->queueCookie( 'x_request_should', 'yes');
            } else {
                $this->queueCookie( 'x_request_should', 'no');
            }

            $this->queueCookie( 'x_request_id', $requestId);
        }catch (\Exception $e){
            Log::error('RequestLog error: ' . $e->getMessage());
        }


        return $response;
    }

    /**
     * 封装统一写 Cookie
     */
    protected function queueCookie(string $name, $value, int $minutes = 60)
    {

        setcookie($name, $value, [
                'expires' => time() + $minutes * 60,
                'path' => '/',
                'httponly' => false, // JS 可读
                'samesite' => 'Lax',
            ]
        );
    }
}
