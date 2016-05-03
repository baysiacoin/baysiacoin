<?php
/**
 * Contains class BaysiaRPCAccount
 *
 * @license MIT
 */
 
/**
 * Account helper for BaysiaRPC
 */
class BaysiaRPCAccount {
    const METHOD_SUBMIT = 'submit';
    const METHOD_ACCOUNT_INFO = 'account_info';
    const METHOD_ACCOUNT_LINES = 'account_lines';
    const METHOD_ACCOUNT_OFFERS = 'account_offers';
    const TX_PAYMENT = 'Payment';
    const TX_ACCOUNT_MERGE = 'AccountMerge';
    const TX_SET_REGULAR_KEY = 'SetRegularKey';
    const TX_OFFER_CREATE = 'OfferCreate';
    const TX_OFFER_CANCEL = 'OfferCancel';
    const TX_TRUST_SET = 'TrustSet';
    const TX_ACCOUNT_SET = 'AccountSet';
    /**
     * Account's Address (gXXXXXX...)
     * @return string
     */
    public $address;
    
    /**
     * Account's secret
     * @return string
     */
    public $secret;
    
    /**
     * Create a new instance of BaysiaRPCAccount.
     * @param string $address {@see BaysiaRPCAccount::$address}
     * @param string $secret {@see BaysiaRPCAccount::$secret}
     * @return BaysiaRPCAccount
     */
    public function __construct($address, $secret = null) 
    {
        $this->address = $address;
        $this->secret = $secret;
    }
    /**
     * Get an account's existing balances.
     * This includes XRP balance (which does not include a counterparty) and trustline balances.
     * @return BaysiaRPCBalance[]
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     */
    public function getBalances()
    {
        $result = BaysiaRPC::get("v1/accounts/" . $this->address . "/balances");
        return array_map(function($x) { return new BaysiaRPCBalance($x); }, $result["balances"]);
    }
    public function getAccountInfo() {
        $params = [
            "account" => $this->address
        ];
        return BaysiaRPC::post(self::METHOD_ACCOUNT_INFO, $params);
    }
    public function getAccountLines($peer = null) {
        $params = [
            "account" => $this->address,
            "peer" => $peer
        ];
        return BaysiaRPC::post(self::METHOD_ACCOUNT_LINES, $params);
    }
    /**
     * Returns Trustlines for this account.
     * @return BaysiaRPCTrustline[]
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     */
    public function getTrustlines()
    {
        $result = BaysiaRPC::get("v1/accounts/" . $this->address . "/trustlines");
        return array_map(function($x) { return new BaysiaRPCTrustline($x); }, $result["trustlines"]);
    }
    /**
     * Add trustline
     * @param string $currency
     * @param string $gateway
     * @param int $flag
     * @param boolean $remove {true: remove trust line, false: add trust line}
     * @throws Exception if secret is missing from the Account object
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     * @return array
     */
    public function submitTrustSet($currency, $gateway, $flag = BaysiaRPCTrustSet::TF_SET_NO_RIPPLE)
    {
        $this->requireAddress();
        $this->requireSecret();
        $trust_set = new BaysiaRPCTrustSet();
        $trust_set->setSecret($this->secret);
        $trust_set->setTxType(self::TX_TRUST_SET);
        $trust_set->setAccount($this->address);
        if ($flag == BaysiaRPCTrustSet::TF_CLEAR_NO_RIPPLE) {
            $trust_set->setLimitAmount(BaysiaRPCAmount::fromString(BaysiaRPCAmount::MIN_TRUST_AMOUNT . "+{$currency}+{$gateway}"));
        } else {
            $trust_set->setLimitAmount(BaysiaRPCAmount::fromString(BaysiaRPCAmount::MAX_TRUST_AMOUNT . "+{$currency}+{$gateway}"));
        }
        $trust_set->setFlags($flag);

        return BaysiaRPC::post(self::METHOD_SUBMIT, $trust_set->toArray());
    }

    /**
     * Returns a AccountSettings object for this account.
     * @return BaysiaRPCAccountSettings
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     */
    public function getSettings()
    {
        $result = BaysiaRPC::get("v1/accounts/" . $this->address . "/settings");
        $obj = new BaysiaRPCAccountSettings($result["settings"]);
        return $obj;
    }
    
    /**
     * Update this account's settings with a AccountSettings object.
     * @param BaysiaRPCAccountSettings $obj
     * @return null
     * @throws Exception if secret is missing from the Account object
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     */
    public function setSettings($obj)
    {
        $this->requireSecret();
        
        $hash = array();
        $hash["settings"] = $obj->toArray();
        $hash["secret"] = $this->secret;
        
        return BaysiaRPC::post("v1/accounts/" . $this->address . "/settings", $hash);
    }
    
    /**
     * Get notifications.
     *
     * Clients using notifications to monitor their account activity should pay particular attention to the `state` and `result` fields. The `state` field will either be `validated` or `failed` and represents the finalized status of that transaction. The `result` field will be `tesSUCCESS` if the `state` was validated. If the transaction failed, `result` will contain the `rippled` or `ripple-lib` error code.
     *
     * Notifications have `next_notification_url` and `previous_notification_url`'s. Account notifications can be polled by continuously following the `next_notification_url`, and handling the resultant notifications, until the `next_notification_url` is an empty string. This means that there are no new notifications but, as soon as there are, querying the same URL that produced this notification in the first place will return the same notification but with the `next_notification_url` set.
     * @param string $hash
     * @return BaysiaRPCNotification
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     */
    public function getNotification($hash)
    {
        $result = BaysiaRPC::get("v1/accounts/" . $this->address . "/notifications/" . $hash);
        $obj = new BaysiaRPCNotification($result["notification"]);
        return $obj;
    }
    
    /**
     * Returns an individual payment.
     * @param string $hash Payment hash or client resource ID
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     * @return BaysiaRPCPayment
     */
    public function getPayment($hash)
    {
        $result = BaysiaRPC::get("v1/accounts/" . $this->address . "/payments/$hash");
        return new BaysiaRPCPayment($result["payment"]);
    }
    
    /**
     * Query `rippled` for possible payment "paths" through the Ripple Network to deliver the given amount to the specified `destination_account`. If the `destination_amount` issuer is not specified, paths will be returned for all of the issuers from whom the `destination_account` accepts the given currency.
     * @param mixed $destinationAccount [String, BaysiaRPCAccount] destination account
     * @param mixed $destinationAmount [String, BaysiaRPCAmount] destination amount
     * @param string[] $sourceCurrencies an array of source currencies that can be used to constrain the results returned (e.g. `["XRP", "USD+r...", "BTC+r..."]`) Currencies can be denoted by their currency code (e.g. USD) or by their currency code and issuer (e.g. `USD+r...`). If no issuer is specified for a currency other than XRP, the results will be limited to the specified currencies but any issuer for that currency will do.
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     * @return BaysiaRPCPayment[]
     */
    public function findPaymentPaths($destinationAccount, $destinationAmount, $sourceCurrencies = null)
    {
        if ($destinationAccount instanceof BaysiaRPCAccount)
            $destinationAccount = $destinationAccount->address;
            
        if ($destinationAmount instanceof BaysiaRPCAmount)
            $destinationAmount = $destinationAmount->toString();
    
        $uri = "v1/accounts/" . $this->address . "/payments/paths/$destinationAccount/$destinationAmount";
        
        if (!is_null($sourceCurrencies))
        {
            $cur = implode(",", $sourceCurrencies);
            $uri .= "?$cur";
        }
        
        $result = BaysiaRPC::get($uri);
        $data = $result["payments"];
        
        for($i = 0; $i < count($data); $i++)
        {
            $data[$i] = new BaysiaRPCPayment($data[$i]);
        }
        
        return $data;
    }
    
    /**
     * Create a BaysiaRPCPayment object with some field filled.
     * @param BaysiaRPCAccount $destinationAccount destination account
     * @param mixed $destinationAmount [String, BaysiaRPCAmount] destination amount
     * @return BaysiaRPCPayment
     */
    public function submitPayment($destinationAccount, $destinationAmount)
    {
        $this->requireSecret();
        $this->requireAddress();

        if ($destinationAccount instanceof BaysiaRPCAccount)
            $destinationAccount = $destinationAccount->address;
        $payment = new BaysiaRPCPayment();
        $payment->setSecret($this->secret);
        $payment->setTxType(self::TX_PAYMENT);
        $payment->setAccount($this->address);
        $payment->setDestination($destinationAccount);
        $payment->setAmount(BaysiaRPCAmount::fromString($destinationAmount));

        return BaysiaRPC::post(self::METHOD_SUBMIT, $payment->toArray());
    }
    /**
     * Submit a BaysiaRPCOfferCreate object with some field filled.
     * @param string $sellAmount "{$value}+{$currency}+{$issuer}"
     * @param string $buyAmount
     * @return Array
     */
    public function submitOfferCreate($sellAmount, $buyAmount)
    {
        $this->requireSecret();
        $this->requireAddress();

        $offer_create = new BaysiaRPCOfferCreate();
        $offer_create->setSecret($this->secret);
        $offer_create->setTxType(self::TX_OFFER_CREATE);
        $offer_create->setAccount($this->address);
        if (is_array($sellAmount)) {
            if (isset($sellAmount['value']) && !empty($sellAmount['value']) && isset($sellAmount['currency']) && !empty($sellAmount['currency'])) {
                if ($sellAmount['currency'] == BaysiaRPCAmount::BASE_CURRENCY) {
                    $sellAmount['value'] = round($sellAmount['value'], 6);
                } else {
                    $sellAmount['value'] = round($sellAmount['value'], 8);
                }
            }
            $offer_create->setTakerGets(new BaysiaRPCAmount($sellAmount));
        } else if (is_string($sellAmount)) {
            $offer_create->setTakerGets(BaysiaRPCAmount::fromString2($sellAmount));
        }
        if (is_array($buyAmount)) {
            if (isset($buyAmount['value']) && !empty($buyAmount['value']) && isset($buyAmount['currency']) && !empty($buyAmount['currency'])) {
                if ($buyAmount['currency'] == BaysiaRPCAmount::BASE_CURRENCY) {
                    $buyAmount['value'] = floor(round($buyAmount['value'] * 1000000)) / 1000000;
                } else {
                    $buyAmount['value'] = floor(round($buyAmount['value'] * 100000000)) / 100000000;
                }
            }
            $offer_create->setTakerPays(new BaysiaRPCAmount($buyAmount));
        } else if (is_string($buyAmount)) {
            $offer_create->setTakerPays(BaysiaRPCAmount::fromString2($buyAmount));
        }

        return BaysiaRPC::post(self::METHOD_SUBMIT, $offer_create->toArray());
    }

    /**
     * Submit a BaysiaRPCOfferCancel object with some field filled.
     * @param int $sequence
     * @return JSON response
     */
    public function submitOfferCancel($sequence)
    {
        $this->requireSecret();
        $this->requireAddress();

        $offer_cancel = new BaysiaRPCOfferCancel();
        $offer_cancel->setSecret($this->secret);
        $offer_cancel->setTxType(self::TX_OFFER_CANCEL);
        $offer_cancel->setAccount($this->address);
        $offer_cancel->setOfferSequence($sequence);

        return BaysiaRPC::post(self::METHOD_SUBMIT, $offer_cancel->toArray());
    }
    /**
     * Browse historical payments in bulk.
     * @param array $options (Defaults to null) An assoc array with the following options:
     *   * `sourceAccount` [string, BaysiaRPCAccount] If specified, limit the results to payments initiated by a particular account
     *   * `destinationAccount` [string, BaysiaRPCAccount]  If specified, limit the results to payments made to a particular account
     *   * `excludeFailed` [boolean] if set to true, this will return only payment that were successfully validated and written into the Ripple Ledger
     *   * `startLedger` [string] If earliest_first is set to true this will be the index number of the earliest ledger queried, or the most recent one if earliest_first is set to false. Defaults to the first ledger the rippled has in its complete ledger. An error will be returned if this value is outside the rippled's complete ledger set
     *   * `endLedger` [string] If earliest_first is set to true this will be the index number of the most recent ledger queried, or the earliest one if earliest_first is set to false. Defaults to the last ledger the rippled has in its complete ledger. An error will be returned if this value is outside the rippled's complete ledger set
     *   * `earliestFirst` [boolean] Determines the order in which the results should be displayed. Defaults to true
     *   * `resultsPerPage` [int] Limits the number of resources displayed per page. Defaults to 20
     *   * `page` [int] : The page to be displayed. If there are fewer than the results_per_page number displayed, this indicates that this is the last page
     * @throws BaysiaRPCError if BaysiaRPC server returns an error
     * @throws BaysiaRPCProtocolError if protocol is wrong or network is down
     * @return Payment[]
     */
    public function queryPayments($options = null)
    {
        $qs = array();
        $o = array();
        
        if (is_null($options)) $options = array();
        foreach ($options as $k => $v)
            $o[strtolower(str_replace("_", "", $k))] = $v;
        
        if (isset($o["sourceaccount"]))
            $qs['source_account'] = ($o["sourceaccount"] instanceof BaysiaRPCAccount) ? $o["sourceaccount"]->address : $o["sourceaccount"];
            
        if (isset($o["destinationaccount"]))
            $qs['destination_account'] = ($o["destinationaccount"] instanceof BaysiaRPCAccount) ? $o["destinationaccount"]->address : $o["destinationaccount"];
            
        if (isset($o['excludefailed'])) $qs['exclude_failed'] = $o['excludefailed'];
        if (isset($o['startledger'])) $qs['start_ledger'] = $o['startledger'];
        if (isset($o['endledger'])) $qs['end_ledger'] = $o['endledger'];
        if (isset($o['earliestfirst'])) $qs['earliest_first'] = $o['earliestfirst'];
        if (isset($o['resultsperpage'])) $qs['results_per_page'] = $o['resultsperpage'];
        if (isset($o['page'])) $qs['page'] = $o['page'];
        
        if (count($qs) > 0)
            $querystring = "?" . http_build_query($qs);
        else
            $querystring = "";
        
        $uri = "v1/accounts/" . $this->address . "/payments$querystring";
        
        $result = BaysiaRPC::get($uri);
        $data = $result["payments"];
        
        for($i = 0; $i < count($data); $i++)
        {
            $clientResourceId = $data[$i]["client_resource_id"];
            $data[$i] = new BaysiaRPCPayment($data[$i]["payment"]);
            $data[$i]->clientResourceId = $clientResourceId;
        }
        
        return $data;
    }
    public function getAccountOffers() {
        $params = [
            "account" => $this->address,
        ];
        return BaysiaRPC::post(self::METHOD_ACCOUNT_OFFERS, $params);
    }
    /**
     * @internal
     * @throws Exception if secret is missing from the Account object
     */
    private function requireSecret()
    {
        if (is_null($this->secret))
            throw new Exception("Secret is required for this operation.");
    }
    /**
     * @internal
     * @throws Exception if address is missing from the Account object
     */
    private function requireAddress()
    {
        if (is_null($this->address))
            throw new Exception("Address is required for this operation.");
    }
}