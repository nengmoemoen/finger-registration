<?php

class Curl {

    private $options;
    private $headers;

    public function __construct() {
        $this->options = [];
        $this->headers = [];
    }

    public function setOption($options) {
        $this->options = $options;
        return $this;
    }

    public function setHeader($headers) {
        $this->headers = $headers;
        return $this;
    }


    public function request($url, $method = 'GET', $params = []) {
        $response = NULL;
        $curl = curl_init();
        // DEFAULT OPTIONS
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        // POSTS
        if($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        }
        // CUSTOM OPTIONS
        if(sizeof($this->options) > 0) {
            foreach($this->options as $k => $v) {
                $key = str_replace('_', '', $k);
                $key = strtoupper($key);

                curl_setopt($curl, constant('CURLOPT_'.$key), $v);
            }
        }

        // HEADERS
        if(sizeof($this->headers) > 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        }

        $response = curl_exec($curl);
        $errno  = curl_errno($curl);
        $err  = curl_error($curl);
        curl_close($curl);
        
        if(0 !== $errno || !empty($err)) {
            throw new Exception("Error Code: $errno \r\nError: $err");
            return;
        }
        return $response;
    }

   
}

?>