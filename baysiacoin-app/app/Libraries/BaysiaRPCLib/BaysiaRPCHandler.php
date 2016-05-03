<?php

/**
 * Created by PhpStorm.
 * User: Kihm
 * Date: 2015-10-16
 */
class BaysiaRPCHandler {
    /**
     * Server URL (http://stellar.org:9002")
     */
    public static $server_endpoint = "http://baysia.asia:9002";
    /**
     * Json RPC Endpoint URL ("http://baysiacoin.asia:80/money/receive-message")
     */
//    public static $json_callback_endpoint = "/money/receive-message";
    public static $json_callback_endpoint = "https://baysiacoin.asia/money/receive-message";
    /**
     * Wallet Address ("g...")
     */
    public $address;
    /**
     * Secret Key ("s...")
     */
    public $secret;
    /**
     * Create a new instance of BaysiaRPCHandler.
     * @return BaysiaRPCHandler
     */
    public function __construct() {
        BaysiaRPC::setup(self::$server_endpoint);
        BaysiaRPC::setupCallback(url(self::$json_callback_endpoint));
    }

    /**
     * @return array
     */
    public static function doCreateKeys() {        
        return BaysiaRPC::createKeys();
    }
    /*
     * @param string $src_address
     * @param string $src_secret
     * @param string $currency
     * @param string $gateway
     */
    public static function doTrustSet($address, $secret, $currency, $gateway, $remove = false) {
        $account = new BaysiaRPCAccount($address, $secret);
        $result = false;
        try {
            if (!isset($gateway))
                throw new Exception('Gateway can not be null.');
            if (!$remove)
                $res = $account->submitTrustSet($currency, $gateway);//"JPY", "gxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
            else
                $res = $account->submitTrustSet($currency, $gateway, BaysiaRPCTrustSet::TF_CLEAR_NO_RIPPLE);//"JPY", "gxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
            if (isset($res['engine_result']) && $res['engine_result'] == 'tesSUCCESS') {
                $result = true;
            }
            $message = isset($res['engine_result_message']) ? $res['engine_result_message'] : "";
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return [$result, $message];
    }

    /**
     * @param $address
     * @param $currs
     * @param null $peer
     * @return array<float>
     * @throws Exception
     */
    public static function getCustomBalances($address, $currs, $peer = null) {
        $filtered = [];
        list($balances) = self::getBalances($address, $peer);
        if (is_array($currs)) {
            foreach ($currs as $key) {
                if (!isset($balances[$key])) $balances[$key] = null;
                $filtered[$key] = is_null($balances[$key]) ? null : (float) $balances[$key];
            }
            return $filtered;
        } else if (is_string($currs)) {
            if (!isset($balances[$currs])) $balances[$currs] = null;
            return is_null($balances[$currs]) ? null : (float) $balances[$currs];
        }
        return $balances;
    }
    public static function getBalances($address, $peer = null) {
        $account = new BaysiaRPCAccount($address);
        $balance = [];
        $message = "";
        try {
            $res = $account->getAccountInfo();
            if (isset($res['account_data']) && isset($res['account_data']['Balance'])) {
                $balance[BaysiaRPCAmount::BASE_CURRENCY] = $res['account_data']['Balance'] / BaysiaRPCAmount::BASE_AMOUNT_OFFSET;
            } else
                throw new Exception("Fail to get Balance.");
            $res = $account->getAccountLines($peer);
            if (isset($res['lines'])) {
                foreach ($res['lines'] as $line) {
                    if (isset($balance[$line['currency']])) {
                        $balance[$line['currency']] += $line['balance'];
                    } else
                        $balance[$line['currency']] = $line['balance'];
                }
            } else
                throw new Exception("Fail to get acocunt lines.");

        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return [$balance, $message];
    }
    public static function getRawBalances($address, $base = true, $peer = null) {
        $account = new BaysiaRPCAccount($address);
        $res = [];
        $message = "";
        try {
            if ($base) {// get balance of base currency
                $res = $account->getAccountInfo();
            } else {// get balance of non-base currency
                $res = $account->getAccountLines($peer);
            }
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return [$res, $message];
    }
    public static function getCustomLimitAmount($address, $curr, $peer = null) {
        $account = new BaysiaRPCAccount($address);
        try {
            $res = $account->getAccountLines($peer);
            if (isset($res['lines'])) {
                foreach ($res['lines'] as $line) {
                    if (isset($line['currency']) && $line['currency'] == $curr) {
                        return (float) $line['limit'];
                    }
                }
            } else
                throw new Exception("Fail to get acocunt lines.");
            return null;
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return null;
    }
    public static function getOffers($sell, $buy) {
        try {
            $offer_list = [];
            $result = BaysiaRPC::bookOffers($sell, $buy);

            if (isset($result['offers']) && !empty($result['offers'])) {
                foreach ($result['offers'] as $offer) {
                    if (is_array($offer['TakerGets'])) {
                        $sell_value = $offer['TakerGets']['value'];
                    } else if (is_string($offer['TakerGets'])) {
                        // if currency is the base currency BASE, value should be divided by 1000000
                        $sell_value = $offer['TakerGets'] / BaysiaRPCAmount::BASE_AMOUNT_OFFSET;
                        $quality = $offer['quality'] * BaysiaRPCAmount::BASE_AMOUNT_OFFSET;
                    }
                    if (is_array($offer['TakerPays'])) {
                        $buy_value = $offer['TakerPays']['value'];
                    } else if (is_string($offer['TakerPays'])) {
                        // if currency is the base currency BASE, value should be divided by 100000
                        $buy_value = $offer['TakerPays'] / BaysiaRPCAmount::BASE_AMOUNT_OFFSET;
                        $quality = $offer['quality'] / BaysiaRPCAmount::BASE_AMOUNT_OFFSET;
                    }
                    array_push($offer_list, ['sell' => $sell_value, 'buy' => $buy_value, 'rate' => isset($quality) ? $quality : $offer['quality']]);
                }
            }
            return $offer_list;
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
    public static function getRawOffers($sell, $buy) {
        $message = "";
        $result = [];
        try {
            $result =  BaysiaRPC::bookOffers($sell, $buy);
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return [$result, $message];
    }
    public static function getRawAccountOffers($address) {
        $message = "";
        $result = [];
        try {
            $account = new BaysiaRPCAccount($address);
            $result =  $account->getAccountOffers();
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return [$result, $message];
    }
    public static function doActive($src_address, $src_secret, $dst_address) {
        return self::doPayment($src_address, $src_secret, $dst_address, BaysiaRPCAmount::ACTIVE_AMOUNT);
    }
    /*
     * @param string $src_address
     * @param string $src_secret
     * @param string $dst_address
     * @param string $amount '$value+$currency+$issuer'
     */
    public static function doPayment($src_address, $src_secret, $dst_address, $amount) {
        if ($amount instanceof BaysiaRPCAmount) {
            if (empty($amount->value)) {
                return [false, 'The amount value should be over 0.'];
            } else if (empty($amount->currency)) {
                $amount->currency = BaysiaRPCAmount::BASE_CURRENCY;
            } else if (empty($amount->issuer)) {
                $amount->issuer = null;
            }
            $amount = "{$amount->value}+{$amount->currency}+{$amount->issuer}";
        } else if (is_array($amount)) {
            if (!isset($amount['value']) || empty($amount['value'])) {
                return [false, 'The amount value should be over 0.'];
            } else if (!isset($amount['currency']) || empty($amount['currency'])) {
                $amount['currency'] = BaysiaRPCAmount::BASE_CURRENCY;
            } else if (!isset($amount['issuer']) || empty($amount['issuer'])) {
                $amount['issuer'] = null;
            }
            $amount = "{$amount['value']}+{$amount['currency']}+{$amount['issuer']}";
        }
        $account = new BaysiaRPCAccount($src_address, $src_secret);
        $result = false;
        try {
            $res = $account->submitPayment($dst_address, $amount);//"gxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx", "5.2+JPY+gxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
            if (isset($res['engine_result']) && $res['engine_result'] == 'tesSUCCESS') {
                $result = true;
            }
            $message = isset($res['engine_result_message']) ? $res['engine_result_message'] : "";
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return [$result, $message];
    }

    /*
     * @param string $address
     * @param string $secret
     * @param string $sellAmount '$value+$currency+$issuer'
     * @param string $buyAmount '$value+$currency+$issuer'
     */
    public static function doCreateOffer($address, $secret, $sellAmount, $buyAmount) {
        $account = new BaysiaRPCAccount($address, $secret);
        $result = false;
        try {
//            $res = $account->submitOfferCreate("{$sellAmount['amount']}+{$sellAmount['currency']}+{$sellAmount['issuer']}", "{$buyAmount['amount']}+{$buyAmount['currency']}+{$buyAmount['issuer']}");//"5.2+JPY+gxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
            $res = $account->submitOfferCreate($sellAmount, $buyAmount);//"5.2+JPY+gxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" or array(v, c, i)
            if (isset($res['engine_result']) && $res['engine_result'] == 'tesSUCCESS') {
                $result = true;
            }
            $message = isset($res['engine_result_message']) ? $res['engine_result_message'] : "";
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $ex) {
            $message = $ex->getMessage();
        } catch (Exception $ex) {
            $message = $ex->getMessage();
        }
        return [$result, $message];
    }
    /*
     * @param string $address
     * @param string $secret
     * @param int sequence
     * @return array
     */
    public static function doCancelOffer($address, $secret, $sequence) {
        $account = new BaysiaRPCAccount($address, $secret);
        $result = false;
        try {
            $res = $account->submitOfferCancel($sequence);
            if (isset($res['engine_result']) && $res['engine_result'] == 'tesSUCCESS') {
                $result = true;
            }
            $message = isset($res['engine_result_message']) ? $res['engine_result_message'] : "";
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return [$result, $message];
    }
    public static function doSubscribe($addresses) {
        if (is_array($addresses)) {
            // note! this is laravel framework helper used.
            $addresses = array_flatten($addresses);
        }
        $result = false;
        try {
            $res = BaysiaRPC::subscribe($addresses);
            print_r($res);exit;
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }

    /**
     * @param $base ['currency' => , 'issuer' => ]
     * @param $counter ['currency' => , 'issuer' => ]
     * @param boolean $type true means SELL PRICE, false means BUY PRICE ( base : counter )
     * rates = $counter / $base
     * if sell case, should be a bit smaller price than the quality
     * if buy case, should be a bit larger price than the quality
     * @return float
     */
    public static function getMarketRate($base, $counter, $type = true) {
        try {
            if ($type) {
                $offers = self::getOffers($counter, $base);

                $rate =  $offers[0]['sell'] / $offers[0]['buy'];
            } else {
                $offers = self::getOffers($base, $counter);
                $rate =  $offers[0]['buy'] / $offers[0]['sell'];
            }
            /*foreach ($offers as $offer) {
                print_r($offer);print_r('<br>');
            }
            exit;
            print_r($rate);exit;*/
            return $rate;
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }

    public static function getTrustCurrencies($address) {
        $account = new BaysiaRPCAccount($address);
        $currencies = [];
        try {
            $res = $account->getAccountLines();
            if (isset($res['lines'])) {
                foreach ($res['lines'] as $line) {
                    $currencies[] = $line['currency'];
                }
            } else
                throw new Exception("Fail to get acocunt lines.");
        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return $currencies;
    }
    public function doTransaction() {
        //var_dump(BaysiaRPC::isServerConnected());
        //var_dump(BaysiaRPC::getServerInfo());
        //var_dump(BaysiaRPC::createUUID());

        $account = new BaysiaRPCAccount("gxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx", "sxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");
        //var_dump($account->getBalances());
        //var_dump($account->getSettings());
        //var_dump($account->getNotification("DD9F40516152090612B12F1CCD5A88828AEA8813FEBD56D9D6B39ED918F4CCCA"));

        $payment = $account->submitPayment("gxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx", "1+JPY+gxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");
//        self::doSubscribe(["gxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx", "sxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"]);
        try {

        } catch (BaysiaRPCError $e) {
            $message = $e->getErrorMessage();//$message = $e->getMessage();
        } catch (BaysiaRPCProtocolError $e) {
            $message = $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
}