<?php

namespace App\Http\Response;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Validator;

class ApiResponse extends Response
{

    protected $apiContentType = 'application/json';

    protected $apiStatus = true;

    protected $apiHttpStatus = 200;

    protected $apiMeta = null;

    protected $apiData = null;

    public static function withRawJson(array $data)
    {
        $response = new static();
        $response->header('Content-Type', $response->getApiContentType());
        $response->setStatusCode(self::HTTP_OK);
        $response->setContent(json_encode($data));
        return $response;
    }

    public static function withJson($data, $meta = null)
    {
        $response = new static();
        $response->header('Content-Type', $response->getApiContentType());
        $response->setStatusCode(self::HTTP_OK);
        $response->setContent(json_encode([
            'http_status' => self::HTTP_OK,
            'status' => true,
            'data' => $data,
            'meta' => $meta,
            'msg' => null,
        ]));
        return $response;
    }

    public static function withMessage($message, $httpStatus = self::HTTP_OK, $data = null)
    {
        if ($message instanceof Validator) {
            $message = $message->errors()->all();
            $message = implode(' ', $message);
        }
        $response = new static();
        $response->header('Content-Type', $response->getApiContentType());
        $response->setStatusCode($httpStatus);
        $response->setContent(json_encode([
            'http_status' => $httpStatus,
            'status' => $httpStatus == self::HTTP_OK ? true : false,
            'data' => $data,
            'meta' => null,
            'msg' => $message,
        ]));
        return $response;
    }

    public static function withResultState($result, $successData = null, $failData = null)
    {
        if ($result['state']) {
            return self::withMessage($result['message'], self::HTTP_OK, $successData);
        } else {
            return self::withMessage($result['message'], self::HTTP_BAD_REQUEST, $failData);
        }
    }

    public static function withError($message, $code = self::HTTP_INTERNAL_SERVER_ERROR)
    {
        return self::withMessage($message, $code);
    }

    public static function withWarning($message, $code = self::HTTP_BAD_REQUEST)
    {
        return self::withMessage($message, $code);
    }

    public static function withSuccess($message, $code = self::HTTP_OK)
    {
        return self::withMessage($message, $code);
    }

    public static function withList($objects, $formatClass = null, $formatMethod = null, $appends = null)
    {
        $response = new static();
        $response->header('Content-Type', $response->getApiContentType());

        $list = $meta = [];
        if ($objects instanceof LengthAwarePaginator
            || $objects instanceof Paginator
        ) {
            $meta["total"] = $objects->total();
            $meta["per_page"] = $objects->perPage();
            $meta["current_page"] = $objects->currentPage();
            $meta["last_page"] = $objects->lastPage();
            $meta["next_page_url"] = $objects->nextPageUrl();
            $meta["prev_page_url"] = $objects->previousPageUrl();
        }

        if ($formatClass && $formatMethod && (is_array($objects) || is_object($objects))) {
            foreach ($objects as $object) {
                $list[] = $formatClass::$formatMethod($object, $appends);
            }
        } elseif ($formatClass instanceof \Closure && (is_array($objects) || is_object($objects))) {
            foreach ($objects as $object) {
                $list[] = $formatClass($object, $appends);
            }
        }

        $response->setStatusCode(self::HTTP_OK);
        $response->setContent(json_encode([
            'http_status' => self::HTTP_OK,
            'status' => true,
            'data' => $list,
            'meta' => $meta,
            'msg' => '',
        ]));

        return $response;
    }

    public static function withObject($object, $formatClass = null, $formatMethod = null, $appends = null)
    {
        $response = new static();
        $response->header('Content-Type', $response->getApiContentType());

        $data = $object;
        if ($formatClass && $formatMethod && is_object($object)) {
            $data = $formatClass::$formatMethod($object, $appends);
        } elseif ($formatClass instanceof \Closure && is_object($object)) {
            $data = $formatClass($object, $appends);
        }
        $response->setStatusCode(self::HTTP_OK);
        $response->setContent(json_encode([
            'http_status' => self::HTTP_OK,
            'status' => true,
            'data' => $data,
            'meta' => null,
            'msg' => '',
        ]));

        return $response;
    }

    public function send()
    {
        //@todo log request/response to mongo db
        return parent::send(); // TODO: Change the autogenerated stub
    }

    /**
     * @return string
     */
    public function getApiContentType()
    {
        return $this->apiContentType;
    }

    /**
     * @param string $apiContentType
     */
    public function setApiContentType($apiContentType)
    {
        $this->apiContentType = $apiContentType;
    }

    /**
     * @return boolean
     */
    public function getApiStatus()
    {
        return $this->apiStatus;
    }

    /**
     * @param boolean $apiStatus
     */
    public function setApiStatus($apiStatus)
    {
        $this->apiStatus = $apiStatus;
    }

    /**
     * @return int
     */
    public function getApiHttpStatus()
    {
        return $this->apiHttpStatus;
    }

    /**
     * @param int $apiHttpStatus
     */
    public function setApiHttpStatus($apiHttpStatus)
    {
        $this->apiHttpStatus = $apiHttpStatus;
    }

    /**
     * @return null
     */
    public function getApiMeta()
    {
        return $this->apiMeta;
    }

    /**
     * @param null $apiMeta
     */
    public function setApiMeta($apiMeta)
    {
        $this->apiMeta = $apiMeta;
    }

    /**
     * @return null
     */
    public function getApiData()
    {
        return $this->apiData;
    }

    /**
     * @param null $apiData
     */
    public function setApiData($apiData)
    {
        $this->apiData = $apiData;
    }

}
