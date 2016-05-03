<?php
/**
 * Contains class BaysiaRPCOfferCreate
 *
 * @license MIT
 */


/**
 * A flattened Payment object used by the baysia-rpc API
 * string $secret
 * string $tx_json
 * string $tx_type
 * string $account
 * @property BaysiaRPCAmount $taker_gets
 * @property BaysiaRPCAmount $taker_pays
 * @property int offer_sequence
 * @property string fee
 * @property int sequence
 */
class BaysiaRPCOfferCreate extends BaysiaRPCSubmit {
    /**
     * BaysiaRPCOfferCreate constructor.
     * @param array $init
     */
    public function __construct($init = null)
    {

    }

    /**
     * @return BaysiaRPCAmount
     */
    public function getTakerGets()
    {
        return $this->taker_gets;
    }

    /**
     * @param BaysiaRPCAmount $taker_gets
     */
    public function setTakerGets($taker_gets)
    {
        $this->taker_gets = $taker_gets;
    }

    /**
     * @return BaysiaRPCAmount
     */
    public function getTakerPays()
    {
        return $this->taker_pays;
    }

    /**
     * @param BaysiaRPCAmount $taker_pays
     */
    public function setTakerPays($taker_pays)
    {
        $this->taker_pays = $taker_pays;
    }

    /**
     * @return int
     */
    public function getOfferSequence()
    {
        return $this->offer_sequence;
    }

    /**
     * @param int $offer_sequence
     */
    public function setOfferSequence($offer_sequence)
    {
        $this->offer_sequence = $offer_sequence;
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
                "TransactionType" => "OfferCreate",
                "Account" => "",
                "TakerGets" => [
                    "value" => "",
                    "currency" => "",
                    "issuer" => ""
                ],
                "TakerPays" => [
                    "value" => "",
                    "currency" => "",
                    "issuer" => ""
                ],
                "OfferSequence" => "",
                "Fee" => "",
                "Sequence" => ""
            ]
        ];*/
        $array['secret'] = $this->secret;
        $array['tx_json']['TransactionType'] = isset($this->tx_type) ? $this->tx_type : BaysiaRPCAccount::TX_OFFER_CREATE;
        $array['tx_json']['Account'] = $this->account;
        $array['tx_json']['TakerGets'] = $this->taker_gets->toArray();
        $array['tx_json']['TakerPays'] = $this->taker_pays->toArray();
        if (isset($this->offer_sequence)) {
            $array['tx_json']['OfferSequence'] = $this->offer_sequence;
        }
        if (isset($this->fee)) {
            $array['tx_json']['Fee'] = $this->fee;
        }
        if (isset($this->sequence)) {
            $array['tx_json']['Sequence'] = $this->sequence;
        }
        return $array;
    }
}
