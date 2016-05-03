<?php
/**
 * Contains class BaysiaRPCPayment
 *
 * @license MIT
 */


/**
 * A flattened Payment object used by the baysia-rpc API
 * string $master_seed
 * string $tx_json
 * string $tx_type
 * string $account
 * @property string $destination
 * @property BaysiaRPCAmount $amount
 * @property string $currency
 * @property string $value
 * @property string $issuer
 * @property string $dst_tag
 */
class BaysiaRPCPayment extends BaysiaRPCSubmit {
    /**
     * BaysiaRPCPayment constructor.
     * @param array $init
     */
    public function __construct($init = null)
    {
        /*parent::__construct($init);

        if (!isset($init['destination'])) {
            throw new Exception("Field 'Destination' is required in BaysiaRPCPayment");
        } else if (!isset($init['amount'])) {
            throw new Exception("Field 'amount' is required in BaysiaRPCPayment");
        }
        $this->destination = $init['destination'];
        if (!isset($init['value'])) {
            $init['value'] = null;
        } else if (!isset($init['currency'])) {
            $init['currency'] = null;
        } else if (!isset($init['issuer'])) {
            $init['issuer'] = null;
        }
        $this->value = $init['value'];
        $this->currency = $init['currency'];
        $this->issuer = $init['issuer'];
        $this->dst_tag = isset($init['dst_tag']) ? $init['dst_tag'] : null;*/
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
                "Destination" => "",
                "Amount" => [
                    "value" => "",
                    "currency" => "",
                    "issuer" => ""
                ],
                "Fee" => "",
                "Sequence" => "",
                "DestinationTag" => "",// int
                "Path" => ""
            ]
        ];*/
        $array['secret'] = $this->secret;
        $array['tx_json']['TransactionType'] = isset($this->tx_type) ? $this->tx_type : BaysiaRPCAccount::TX_PAYMENT;
        $array['tx_json']['Account'] = $this->account;
        $array['tx_json']['Destination'] = $this->destination;
        $array['tx_json']['Amount'] = $this->amount->toArray();
        if (isset($this->dst_tag)) {
            $array['tx_json']['DestinationTag'] = $this->dst_tag;
        }
        return $array;
    }
}
