<?php
namespace Jsonio;
use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Pagination\Paginator;

class JsonResponse extends \Illuminate\Support\Facades\Response
{
	static function ok($data)
	{
		if($data instanceof Paginator)
		{
			return self::answerOkByPager($data);
		}
		elseif($data instanceof Collection)
		{
			return self::answerOk($data->toArray());
		}
		elseif($data instanceof \Eloquent)
		{
			return self::answerOk($data->toArray());
		}
		else
		{
			return self::answerOk($data);
		}
	}

	static function error($http_code, $error_type, $error_message, $headers = [], $options = 0)
	{
		if(is_object($error_message) && $error_message instanceof \Illuminate\Validation\Validator)
		{
			$error_message = $error_message->errors()->first();
		}

		$response = [
			'meta' => [
				'code' => $http_code,
				'error_type' => $error_type,
				'error_message' => $error_message,
			],
			'data' => null,
		];
		return parent::json($response, $http_code, $headers, $options | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	static function error404($message = '', $headers = [], $options = 0)
	{
		$response = [
			'meta' => [
				'code' => 404,
				'error_type' => 'NotFound',
				'error_message' => $message ?: 'Not found',
			],
			'data' => null,
		];
		return parent::json($response, 404, $headers, $options | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	static function answerOkByPager(Paginator $paginator)
	{
		if($paginator->getLastPage() == $paginator->getCurrentPage())
			$next_page = null;
		else
			$next_page = $paginator->getCurrentPage();
		$next_page_url = $next_page ? \Request::url().'?page='.$next_page : null;
		return parent::json(
			[
				'meta' => ['code' => 200],
				'pagination' => [
					'next_url' => $next_page_url,
					'next_page' => $next_page
				],
				'data' => $paginator->getCollection()->toArray(),
			],
			200,
			[],
			JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
		);
	}

	static function answerOk($data)
	{
		$response = [
			'meta' => [
				'code' => 200,
				'error_type' => null,
				'error_message' => null,
			],
			'data' => $data,
		];
		return parent::json($response, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	static function answerOkWithPagination($data, $next_page = null, $next_page_url = null)
	{
		$response = [
			'meta' => [
				'code' => 200,
				'error_type' => null,
				'error_message' => null,
			],
			'data' => $data,
		];
		$response['pagination'] = [
			'next_page' => $next_page,
			'next_url' => $next_page_url
		];
		return parent::json($response, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}
}
