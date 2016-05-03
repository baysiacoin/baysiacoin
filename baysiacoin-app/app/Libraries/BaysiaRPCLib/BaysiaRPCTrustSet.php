<?php
/**
 * Contains class BaysiaRPCPayment
 *
 * @license MIT
 */


/**
 * A flattened Payment object used by the baysia-rpc API
 * string $secret
 * string $tx_json
 * string $tx_type
 * string $account
 * @property BaysiaRPCAmount $limit_amount
 * @property string fee
 * @property int flags
 * @property int sequence
 */
class BaysiaRPCTrustSet extends BaysiaRPCSubmit {
    const TF_SET_AUTH = 65536;
    const TF_SET_NO_RIPPLE = 131072;
    const TF_CLEAR_NO_RIPPLE = 262144;
    /**
     * BaysiaRPCPayment constructor.
     * @param array $init
     */
    public function __construct($init = null)
    {

    }

    /**
     * @return BaysiaRPCAmount
     */
    public function getLimitAmount()
    {
        return $this->limit_amount;
    }

    /**
     * @param BaysiaRPCAmount $limit_amount
     */
    public function setLimitAmount($limit_amount)
    {
        $this->limit_amount = $limit_amount;
    }

    /**
     * @return string
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @param string $fee
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
    }

    /**
     * @return int
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param int $flags
     */
    public function setFlags($flags)
    {
        $this->flags = $flags;
    }

    /**
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param int $sequence
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    /**
     * this to array
     */
    public function toArray() {
        /*$array = [
            "secret" => "",
            "tx_json" => [
                "TransactionType" => "Payment",
                "Account" => "",
                "LimitAmount" => [
                    "value" => "",
                    "currency" => "",
                    "issuer" => ""
                ],
                "Fee" => "",
                "Flags" => 131072
                "Sequence" => ""
            ]
        ];*/
        $array['secret'] = $this->secret;
        $array['tx_json']['TransactionType'] = isset($this->tx_type) ? $this->tx_type : BaysiaRPCAccount::TX_TRUST_SET;
        $array['tx_json']['Account'] = $this->account;
        $array['tx_json']['LimitAmount'] = $this->limit_amount->toArray();
        if (isset($this->fee)) {
            $array['tx_json']['Fee'] = $this->fee;
        }
        $array['tx_json']['Flags'] = isset($this->flags) ? $this->flags : self::TF_SET_NO_RIPPLE;
        if (isset($this->sequence)) {
            $array['tx_json']['Sequence'] = $this->sequence;
        }
        return $array;
    }
}
