<?php
namespace Rizhou\RequestLog\Http\Controllers;

use Illuminate\Http\Request;
use Rizhou\RequestLog\Models\RequestLog;

class JsLogController
{
    public function collect(Request $request)
    {
        if($request->requestID){
            RequestLog::where('id',$request->requestID)->update([
                'screen_width'=>$request->screenWidth,
                'screen_height'=>$request->screenHeight,
                'timezone'=>$request->timeZone,
            ]);
        }else{
            RequestLog::create([
                'method'      => "GET",
                'url'         => $request->get('url'),
                'ip'          => $request->ip(),
                'ipcountry'   => $request->header('cf-ipcountry', 'unknown'),
                'host'        => $request->get('host'),
                'user_agent'  => $request->userAgent(),
                'referer'     => $request->get('referer'),
                'status_code' => 200,
                'is_js'       => true,
                'language'    => $request->language,
                'screen_width'=>$request->screenWidth,
                'screen_height'=>$request->screenHeight,
                'timezone'=>$request->timeZone,
            ]);
        }

        return response('');
    }
}