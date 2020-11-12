<?php


namespace Btinet\Ringhorn;


class Request
{

    /**
     * @var bool
     */
    public bool $isPostRequest;

    /**
     * @var bool
     */
    public bool $isFormSubmitted;

    /**
     * @var string
     */
    public string $csrf_token;

    /**
     * @var string
     */
    public string $query;

    public function __construct(){

    }

    /**
     * @return bool
     */
    public function isPostRequest()
    {
        $this->isPostRequest = (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST');
        return $this->isPostRequest;
    }

    public function  isFormSubmitted(){
        $token = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        return $this->isFormSubmitted = ($token === $this->csrf_token);
    }

    /**
     * @param $FormFieldName
     * @return mixed
     */
    public function getQuery($FormFieldName)
    {
        $query = filter_input(INPUT_POST, $FormFieldName, FILTER_SANITIZE_SPECIAL_CHARS);
        return $this->query = $query;
    }

}