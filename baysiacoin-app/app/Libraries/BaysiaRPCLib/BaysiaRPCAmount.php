<?php
/**
 * Contains class BaysiaRPCAmount
 *
 * @license MIT
 */


/**
 * An Amount on the Ripple Protocol, used also for XRP in the ripple-rest API
 * @property string $value The quantity of the currency, denoted as a string to retain floating point precision
 * @property string $currency (Currency) The currency expressed as a three-character code
 * @property string $issuer (`/^$|^r[1-9A-HJ-NP-Za-km-z]{25,33}$/`) The Ripple account address of the currency's issuer or gateway, or an empty string if the currency is XRP
 * @property string $counterparty (`/^$|^r[1-9A-HJ-NP-Za-km-z]{25,33}$/`) The Ripple account address of the currency's issuer or gateway, or an empty string if the currency is XRP

 */
class BaysiaRPCAmount extends BaysiaRPCObject {
    /**
     * @global
     */
    const BASE_CURRENCY = 'BSC';
    /**
     * @global
     */
    const BASE_AMOUNT_OFFSET = 1000000;
    /**
     * @global
     */
    const MIN_TRUST_AMOUNT = 0;
    /**
     * @global
     */
    const MAX_TRUST_AMOUNT = 1000000000;
    /**
     * @global
     */
    const ACTIVE_AMOUNT = 0.016;
    /**
     * @internal
     */
    protected static $__properties = array(
        "value" => "Value", 
        "currency" => "Currency", 
        "issuer" => "Issuer"
    );
    
    /**
     * Pattern Rule for field `BaysiaRPCAmount::$issuer`
     * @see BaysiaRPCAmount::$issuer
     * @see BaysiaRPCAmount::setIssuer
     * @see BaysiaRPCAmount::getIssuer
     */
    const PATTERN_ISSUER = "^$|^g[1-9A-HJ-NP-Za-km-z]{25,33}$";
    /**
     * @internal
     */
    protected $__data = array();
    
    /**
     * @internal
     */
    public function __set($name, $value)
    {
        if (isset(self::$__properties[strtolower($name)]))
        {
            $key = "set" . self::$__properties[strtolower($name)];
            return $this->$key($value);
        }
        else
        {
            return $this->__data[$name] = $value;
        }
    }

    /**
     * @internal
     */
    public function __get($name)
    {
        if (isset(self::$__properties[strtolower($name)]))
        {
            $key = "get" . self::$__properties[strtolower($name)];
            return $this->$key();
        }
        else
        {
            if (array_key_exists($name, $this->__data)) {
                return $this->data[$name];
            }

            $trace = debug_backtrace();
            trigger_error(
                'Undefined property via __get(): ' . $name .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line'],
                E_USER_NOTICE);
            return null;
        }
    }

    /**
     * @internal
     */
    public function __isset($name)
    {
        if (isset(self::$__properties[strtolower($name)]))
        {
            return true;
        }
        return isset($this->__data[$name]);
    }

    /**
     * @internal
     */
    public function __unset($name)
    {
        if (isset(self::$__properties[strtolower($name)]))
        {
            $key = "set" . self::$__properties[strtolower($name)];
            return $this->$key(null);
        }
        unset($this->__data[$name]);
    }
    
    /**
     * Create a new instance of BaysiaRPCAmount.
     * @param array $data (defaults to `null`) PHP Array (result of `json_decode($json, true)`)
     * @return BaysiaRPCAmount
     */
    public function __construct($data = null) 
    {
        if (is_array($data))
        {
            foreach($data as $name => $value)
            {
                if (isset(self::$__properties[strtolower($name)]))
                {
                    $key = "init" . self::$__properties[strtolower($name)];
                    $this->$key($value);
                }
                else
                {
                    $this->__data[$name] = $value;
                }
            }
        }
    }
    
    
    /**
     * @internal
     */
    protected $_Value;
    
    /**
     * The quantity of the currency, denoted as a string to retain floating point precision
     * @see BaysiaRPCAmount::$value
     * @see BaysiaRPCAmount::setValue
     * @return string 
     */
    public function getValue() {
        return $this->_Value;
    }
    
    /**
     * The quantity of the currency, denoted as a string to retain floating point precision
     * @see BaysiaRPCAmount::$value
     * @see BaysiaRPCAmount::getValue
     * @param string $value 
     * @return null
     */
    public function setValue($value) {
        try {
            if (!self::_checkString($value)) throw new Exception("");
            $this->_Value = self::_fromString($value);
        } catch(Exception $e) {
            throw new Exception("Cannot convert " . ((string)$value) . " to " . "string");
        }
    }
    
    /**
     * @internal
     */
    protected function initValue($value) {
        $this->_Value = self::_fromString($value);
    }
    
    /**
     * @internal
     */
    protected $_Currency;
    
    /**
     * The currency expressed as a three-character code
     * @see BaysiaRPCAmount::$currency
     * @see BaysiaRPCAmount::setCurrency
     * @return string (Currency) 
     */
    public function getCurrency() {
        return $this->_Currency;
    }
    
    /**
     * The currency expressed as a three-character code
     * @see BaysiaRPCAmount::$currency
     * @see BaysiaRPCAmount::getCurrency
     * @param string $value (Currency) 
     * @return null
     */
    public function setCurrency($value) {
        try {
            if (!self::_checkCurrency($value)) throw new Exception("");
            $this->_Currency = self::_fromCurrency($value);
        } catch(Exception $e) {
            throw new Exception("Cannot convert " . ((string)$value) . " to " . "string");
        }
    }
    
    /**
     * @internal
     */
    protected function initCurrency($value) {
        $this->_Currency = self::_fromCurrency($value);
    }
    
    /**
     * @internal
     */
    protected $_Issuer;
    
    /**
     * The Ripple account address of the currency's issuer or gateway, or an empty string if the currency is XRP
     * @see BaysiaRPCAmount::$issuer
     * @see BaysiaRPCAmount::setIssuer
     * @return string (`/^$|^r[1-9A-HJ-NP-Za-km-z]{25,33}$/`) 
     */
    public function getIssuer() {
        return $this->_Issuer;
    }
    
    /**
     * The Ripple account address of the currency's issuer or gateway, or an empty string if the currency is XRP
     * @see BaysiaRPCAmount::$issuer
     * @see BaysiaRPCAmount::getIssuer
     * @param string $value (`/^$|^r[1-9A-HJ-NP-Za-km-z]{25,33}$/`) 
     * @return null
     */
    public function setIssuer($value) {
        try {
            if (!self::_checkStringPattern($value, self::PATTERN_ISSUER)) throw new Exception("");
            $this->_Issuer = self::_fromStringPattern($value, self::PATTERN_ISSUER);
        } catch(Exception $e) {
            throw new Exception("Cannot convert " . ((string)$value) . " to " . "string");
        }
    }
    
    /**
     * @internal
     */
    protected function initIssuer($value) {
        $this->_Issuer = self::_fromStringPattern($value, self::PATTERN_ISSUER);
    }

    /**
     * Convert this object to PHP native Array for serializing to JSON.
     * @return array
     * @throws Exception
     * @throw Exception
     */
    public function toArray()
    {
        $value = self::_toFloat($this->_Value);
        if (is_null($value))
            throw new Exception("Field 'value' is required in BaysiaRPCAmount");
        $currency = self::_toCurrency($this->_Currency);
        if (is_null($currency) || $currency == self::BASE_CURRENCY) {
            $value *= self::BASE_AMOUNT_OFFSET;
            return "{$value}";
        }
        $issuer = self::_toStringPattern($this->_Issuer, self::PATTERN_ISSUER);
        /*
         * consist array to return
         */
        $array['value'] = "{$value}";
        $array['currency'] = $currency;
        if (!is_null($issuer)) {
            $array['issuer'] = $issuer;
        }
        return $array;
    }
    /**
     * Convert this object to PHP native Array for serializing to JSON.
     * @return array
     * @throws Exception
     * @throw Exception
     */
    public function toArray2()
    {
        $currency = self::_toCurrency($this->_Currency);
        if (is_null($currency)) {
            $currency = self::BASE_CURRENCY;
        }
        $issuer = self::_toStringPattern($this->_Issuer, self::PATTERN_ISSUER);
        /*
         * consist array to return
         */
        $array['currency'] = $currency;
        if (!is_null($issuer)) {
            $array['issuer'] = $issuer;
        }
        return $array;
    }

    /**
     * Converts to a string.
     * @return string
     */
    public function toString()
    {
        $str = ((string)$this->value);
        $str .= "+" . ((string)$this->currency);
        if (strlen($this->issuer) > 0)
          $str .= "+" . ((string)$this->issuer);
          
        return $str;
    }

    /**
     * Convert a string to BaysiaRPCAmount
     * @param string $s a String like "1+XRP" or "1+USD+r..."
     * @return BaysiaRPCAmount
     */
    public static function fromString($s)
    {
        $arr = explode("+", $s);
        return new BaysiaRPCAmount([
            "value" => $arr[0],
            "currency" => isset($arr[1]) ? $arr[1] : self::BASE_CURRENCY,
            "issuer" => isset($arr[2]) ? $arr[2] : null
        ]);
    }
    public static function fromString2($s)
    {
        $arr = explode("+", $s);
        return new BaysiaRPCAmount([
            "value" => null,
            "currency" => isset($arr[0]) ? $arr[0] : self::BASE_CURRENCY,
            "issuer" => isset($arr[1]) ? $arr[1] : null
        ]);
    }
}
