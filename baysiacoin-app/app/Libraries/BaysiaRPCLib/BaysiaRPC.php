<?php
/**
 * Contains class BaysiaRPC
 *
 * @license MIT
 */

/**
 * Ripple Rest Client for PHP.
 */
class BaysiaRPC {
    const METHOD_CREATE_KEYS = 'create_keys';
    const METHOD_BOOK_OFFERS = 'book_offers';
    const METHOD_SUBSCRIBE = 'subscribe';
    /**
     * @internal
     */
    protected static $endpoint = null;
    /**
     * @internal
     */
    protected static $callback_endpoint = null;
    
    /**
     * @internal
     */
    protected static $client = null;
    
    /**
     * Set endpoint URI
     * @param string $endpoint "http://localhost:5990/"
     */
    public static function setup($endpoint)
    {
        self::$endpoint = preg_replace('`/$`', '', $endpoint);
    }
    /**
     * Set endpoint URI
     * @param string $endpoint "http://localhost:5990/"
     */
    public static function setupCallback($endpoint)
    {
        self::$callback_endpoint = preg_replace('`/$`', '', $endpoint);
    }
    /**
     * Retrieve the details of a transaction in the standard Ripple JSON format. 
     * @param string $hash transaction hash
     * @return array See the Ripple Wiki page on [Transaction Formats](https://ripple.com/wiki/Transactions) for more information.
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     */
    public static function getTransaction($hash)
    {
        $result = self::get("v1/transactions/$hash");
        return $result["transaction"];
    }
    
    /**
     * A simple endpoint that can be used to check if ripple-rest is connected to a rippled and is ready to serve. If used before querying the other endpoints this can be used to centralize the logic to handle if rippled is disconnected from the Ripple Network and unable to process transactions.
     * @return boolean true if `ripple-rest` is ready to serve
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     */
    public static function isServerConnected()
    {
        $result = self::get("v1/server/connected");
        return $result["connected"];
    }
    
    /**
     * Retrieve information about the ripple-rest and connected rippled's current status.
     * @return array https://github.com/ripple/ripple-rest/blob/develop/docs/api-reference.md#get-server-info
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     */
    public static function getServerInfo()
    {
        return self::get("v1/server");
    }
    /**
     * @internal
     */
    private static function getClient()
    {
        if (!is_null(self::$client)) return self::$client;
        
        if (BaysiaRPCClientCURL::isAvailable())
            return self::$client = new BaysiaRPCClientCURL();
          
        if (BaysiaRPCClientURLFileOpen::isAvailable())
            return self::$client = new BaysiaRPCClientURLFileOpen();
            
        throw new BaysiaRPCProtocolError("There is no Client available. Please try install cURL or turn on 'allow_url_fopen'.");
    }
    /**
     * @internal
     */
    private static function wrapError($callback)
    {
        if(is_null(self::$endpoint))
        {
            throw new BaysiaRPCProtocolError("You have to setup BaysiaRPC first.");
        }

        $response = $callback();

        $json = json_decode($response, true);

        if (!empty($json) && isset($json['result'])) {
            $json = $json['result'];
            if (isset($json['status']) && $json['status'] == 'error') {
                $message = isset($json['error']) ? $json['error'] : "";
                $code = isset($json['error_code']) ? $json['error_code'] : 0;
                $detail = isset($json['error_message']) ? $json['error_message'] : "";

                $ex = new BaysiaRPCError($message, $code, $detail);
                $ex->response = $json;
                throw $ex;
            }
        }
        return is_null($json) ? $response : $json;
    }
    /**
     * @internal
     */
    public static function get($uri)
    {
        return self::wrapError(function() use($uri) {
            return self::getClient()->get(self::$endpoint . "/$uri");
        });
    }
    
    /**
     * @external
     */
    public static function post($method, $params = null)
    {
        $body = ['method' => $method];
        if (isset($params)) {
            $body['params'] = [$params];
        }
        return self::wrapError(function() use($body) {
            return self::getClient()->post(self::$endpoint, $body);
        });
    }
    /**
     * generate baysia address, secret key
     * @return account_id, master_seed, master_seed_hex, public_key, public_key_hex
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     */
    public static function createKeys() {
        return self::post(self::METHOD_CREATE_KEYS);
    }

    public static function bookOffers($sell, $buy) {
        if (is_array($sell)) {
            $sell = new BaysiaRPCAmount($sell);
        } else if (is_string($sell)) {
            $sell = BaysiaRPCAmount::fromString2($sell);
        }
        if (is_array($buy)) {
            $buy = new BaysiaRPCAmount($buy);
        } else if (is_string($buy)) {
            $buy = BaysiaRPCAmount::fromString2($buy);
        }
        $params = [
            "taker_gets" => $sell->toArray2(),
            "taker_pays" => $buy->toArray2()
        ];
        return self::post(self::METHOD_BOOK_OFFERS, $params);
    }

    /**
     * @param array $addresses this should be [val1, val2, ...] without specified keys
     * @return mixed
     */
    public static function subscribe(array $addresses) {
        $params = [
            "accounts" => $addresses,
            "url" => self::$callback_endpoint
        ];
        return self::post(self::METHOD_SUBSCRIBE, $params);
    }
}