<?php

namespace App\UserBundle\Exception;

class ValidationException extends \Exception
{
    /**
     * @var string
     */
    private $errorCode;

    /**
     * @var integer
     */
    private $statusCode;

    /**
     * @var array|null
     */
    private $data;

    /**
     * @param string $errorCode
     * @param array|null   $data
     * @param int $statusCode
     * @param string|null   $message
     * @param \Throwable|null $previous
     */
    public function __construct($errorCode, $data = null, $statusCode = 0, $message = null, $previous = null) {
        parent::__construct($message, 0, $previous);

        $this->errorCode = $errorCode;
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param array|null $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getData()
    {
        return $this->data;
    }
}
