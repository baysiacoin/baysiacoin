<?php
/**
 * Contains class BaysiaRPCClient
 *
 * @license MIT
 */
 
/**
 * Defines the interface for a REST client.
 */
abstract class BaysiaRPCClient {
    /**
     * Set the User-Agent for client.
     * @return string User-Agent string.
     */
    public $useragent = "BaysiaRPC-PHPClient/0.1";
    /**
     * Perform a HTTP GET request.
     * @param string $uri Request URI.
     * @return string Response body.
     * @throws BaysiaRPCProtocolError if client returns non-200 responses or network problems.
     */
    public abstract function get($uri);
    
    /**
     * Perform a HTTP GET request.
     * @param string $uri Request URI.
     * @param mixed $body Request body (JSON string / PHP array).
     * @return string Response body as JSON string.
     * @throws BaysiaRPCProtocolError if client returns non-200 responses or network problems.
     */
    public abstract function post($uri, $body);
}