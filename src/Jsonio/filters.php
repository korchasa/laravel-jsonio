<?php

Route::filter('jsonio.body2params', function()
{
	if(!$post_body = file_get_contents("php://input"))
		return;
	$data = json_decode($post_body, $assoc = true);

	if(!$data)
		Log::warning('Not json in post body.', [$post_body]);
	else
		Request::instance()->query->add($data);
});

Route::filter('jsonio.log', function($route, $request, $response)
{
	$request_id = \Jsonio\JsonRequest::getRequestId();
	$response_code = $response->getData()->meta->code;
	$error_type = $response->getData()->meta->error_type;
	$error_message = $response->getData()->meta->error_message;
	$line = 'Response #'.$request_id.' '.$response_code;
	if(200 != $response_code)
		$line .= ' ['.$error_type.'] '.$error_message;
	Log::info($line, $request->all());
});

Route::filter('jsonio.log_request', function($route, $request)
{
	$request_id = \Jsonio\JsonRequest::getRequestId();
	$line = 'Request #'.$request_id.' '.Request::method().' '.Request::url();
	Log::info($line, $request->all());
});
