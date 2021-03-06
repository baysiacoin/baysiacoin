<?php
/**
 * Contains class BaysiaRPCClientURLFileOpen
 *
 * @license MIT
 */
 
/**
 * A REST Client depends on `allow_url_fopen=true` in `php.ini`.
 */
class BaysiaRPCClientURLFileOpen extends BaysiaRPCClient {
    /**
     * Perform a HTTP GET request.
     * @param string $uri Request URI.
     * @return string Response body.
     * @throws BaysiaRPCProtocolError if client returns non-200 responses or network problems.
     */
    public function get($uri)
    {
        try {
            $options = array(  
                'http' => array(  
                    'header' => "User-Agent: " . $this->useragent . "\r\n". 
                                "\r\n" ,
                )  
            );
            $context = stream_context_create($options);
            $body = file_get_contents($uri, false, $context);  
            
            return $body;
        }
        catch (Exception $e)
        {
            throw new BaysiaRPCProtocolError($e->getMessage(), $e->getCode(), $e);
        }
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
        try {
            $content = is_string($body) ? $body : json_encode($body);
            $options = array(  
                'http' => array(  
                    'header' => "User-Agent: " . $this->useragent . "\r\n" . 
                                "Content-Type: application/json\r\n" . 
                                "Content-Length: " . strlen($content) . 
                                "\r\n",
                    'method' => "POST",
                    'content' => $content
                )  
            );
            $context = stream_context_create($options);
            $body = file_get_contents($uri, false, $context);  
            
            return $body;
        }
        catch (Exception $e)
        {
            throw new BaysiaRPCProtocolError($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Check if this client can be used.
     * @return boolean
     */
    public static function isAvailable()
    {
        return (bool) ini_get("allow_url_fopen");
    }
}