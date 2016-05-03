<?php
/**
 * Contains class BaysiaRPCClientCURL
 *
 * @license MIT
 */
 use Illuminate\Support\Facades\Log;
/**
 * A REST Client depends on CURL.
 */
class BaysiaRPCClientCURL extends BaysiaRPCClient {
    /**
     * Perform a HTTP GET request.
     * @param string $uri Request URI.
     * @return string Response body.
     * @throws BaysiaRPCProtocolError if client returns non-200 responses or network problems.
     */
    public function get($uri)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        
        if ($response === FALSE) {  
            $error = curl_error($ch);  
        }
  
        curl_close($ch);
  
        if (isset($error)) {
            throw new BaysiaRPCProtocolError("CURL: $error ($uri)");
        }
        return $response;
    }
    
    /**
     * Perform a HTTP GET request.
     * @param string $uri Request URI.
     * @param mixed $body Request body (JSON string / PHP array).
     * @return string Response body as JSON string.
     * @throws BaysiaRPCProtocolError if client returns non-200 responses or network problems.
     */
    public function post($uri, $body)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_string($body) ? $body : json_encode($body));

        Log::info('------------------------- Baysia Post -------------------------------------');
        Log::info('baysia_request: '.$uri . " " . json_encode($body));

        $response = curl_exec($ch);

        Log::info('------------------------- Baysia Response ---------------------------------');
        Log::info('baysia_response: '. str_replace(array("\r", "\n", "\r\n", "\\"), "", print_r($response, true)));

        if ($response === FALSE) {  
            $error = curl_error($ch);  
        }
  
        curl_close($ch);
  
        if (isset($error)) {
            throw new BaysiaRPCProtocolError("CURL: $error ($uri)");
        }
        return $response;
    }
    
    /**
     * Check if this client can be used.
     * @return boolean
     */
    public static function isAvailable()
    {
        return (bool) function_exists("curl_init");
    }
}