<?php
/**
 * Contains class BaysiaRPCError
 *
 * @license MIT
 */
 
/**
 * Throws if BaysiaRPC server returns an error.
 */
class BaysiaRPCError extends Exception {
    /*
     * BaysiaRPC Error Message
     */
    private $error_message;
    /*
     * BaysiaRPC Response
     */
    public $response;
    /*
     * return BaysiaRPCError
     */
    public function __construct($error, $error_code = null, $error_message = null) {
        parent::__construct($error, $error_code);
        $this->error_message = $error_message;
    }
    /*
     * return BaysiaRPC Error Message
     */
    public function getErrorMessage() {
        return $this->error_message;
    }
}