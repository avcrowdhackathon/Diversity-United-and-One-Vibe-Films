<?php

class MailChimp2
{
    private $api_key;
    private $api_endpoint  = 'https://<dc>.api.mailchimp.com/3.0';
    
    public  $verify_ssl    = true; 

    private $last_error    = '';
    private $last_response = array();

    public function __construct($api_key)
    {
        $this->api_key = $api_key;

        list(, $datacentre)  = explode('-', $this->api_key);
        $this->api_endpoint  = str_replace('<dc>', $datacentre, $this->api_endpoint);

        $this->last_response = array('headers'=>null, 'body'=>null);
    }

    public function new_batch($batch_id=null)
    {
        return new Batch($this, $batch_id);
    }

    public function subscriberHash($email)
    {
        return md5(strtolower($email));
    }

    public function getLastError()
    {
        if ($this->last_error) return $this->last_error;
        return false;
    }

    public function getLastResponse()
    {
        return $this->last_response;
    }

    public function getLastRequest()
    {
        return $this->last_request;
    }

    public function delete($method, $args=array(), $timeout=10)
    {
        return $this->makeRequest('delete', $method, $args, $timeout);
    }


    public function get($method, $args=array(), $timeout=10)
    {
        $url = $this->api_endpoint.'/'.$method;
        $args = array(
            'headers' => 'Authorization: apikey '.$this->api_key,

        );

        $request = wp_remote_get($url, $args);
        return $this->formatResponse($request);
    }


    public function patch($method, $args=array(), $timeout=10)
    {
        return $this->makeRequest('patch', $method, $args, $timeout);
    }

    public function post($method, $args=array(), $timeout=10)
    {
        $url = $this->api_endpoint.'/'.$method;
        $body = $args;
        $args = array(
            'headers' => 'Authorization: apikey '.$this->api_key,
            'body' => json_encode($body)
        );
        $request = wp_remote_post($url, $args);
        return $this->formatResponse($request);
    }

    public function put($method, $args=array(), $timeout=10)
    {
        return $this->makeRequest('put', $method, $args, $timeout);
    }

    private function formatResponse($response)
    {
        $this->last_response = $response;

        if (!empty($response['body'])) {

            $d = json_decode($response['body'], true);
            
            if (isset($d['status']) && $d['status']!='200' && isset($d['detail'])) {
                $this->last_error = sprintf('%d: %s', $d['status'], $d['detail']);
            }
            
            return $d;
        }

        return false;
    }
}