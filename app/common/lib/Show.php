<?php
namespace app\common\lib;

class Show 
{
	public static function  success($data = [], $message = "ok",)
	{
	    $result = [
	        'status' => config("status.success"),
	        'message' => $message,
	        'result' => $data
	    ];
	    return json($result);
	}

	public static function error($data = [],  $message = "error", $status = 0)
	{
	    $result = [
	        'status' => $status,
	        'message' => $message,
	        'result' => $data
	    ];
	    return json($result);
	}
}