<?php
/**
 * Contains class BaysiaRPCSubmit
 *
 * @license MIT
 */


/**
 * A flattened Submit object used by the ripple-rest API
 * @property string $secret
 * @property string $tx_json
 * @property string $tx_type
 * @property string $account
 */
class BaysiaRPCSubmit extends BaysiaRPCObject {
   /* private $param = [
        "secret" => "",
        "tx_json" => [
            "TransactionType" => "",
            "Account" => "",
        ]
    ];*/
    public function __construct($init = null) {
        /*if (!isset($init['tx_type'])) {
            throw new Exception("Field 'TransactionType' is required in BaysiaRPCPayment");
        } else if (!isset($init['account'])) {
            throw new Exception("Field 'Account' is required in BaysiaRPCPayment");
        } else if (!isset($init['secret'])) {
            throw new Exception("Field 'secret' is required in BaysiaRPCPayment");
        }*/
        $this->tx_type = isset($init['tx_type']) ? $init['tx_type'] : null;
        $this->account = isset($init['account']) ? $init['account'] : null ;
        $this->secret = isset($init['secret']) ? $init['secret'] : null;
    }
    /**
     * @return string
     */
    public function getTxJson()
    {
        return $this->tx_json;
    }

    /**
     * @param string $tx_json
     */
    public function setTxJson($tx_json)
    {
        $this->tx_json = $tx_json;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getTxType()
    {
        return $this->tx_type;
    }

    /**
     * @param string $tx_type
     */
    public function setTxType($tx_type)
    {
        $this->tx_type = $tx_type;
    }

    /**
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param string $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    public function toArray() {
        $array = [];

        if (is_null($this->secret))
            throw new Exception("Field 'Secret' is required in BaysiaRPCPayment");

        $array['secret'] = self::_toString($this->secret);

        if (is_null($this->account))
            throw new Exception("Field 'Account' is required in BaysiaRPCPayment");

        $array['tx_json']['Account'] = self::_toRippleAddress($this->account);

        return $array;
    }
}
