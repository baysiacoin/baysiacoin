<?php namespace App\Libraries;
/**
 * author Joyce Kim
 * consist of common functions
 */
class Common
{
    /**
     * @param string $string
     * @return string
     */
    function g_postNumberSplit($string)
    {
        if (!empty($string)) {
            return substr($string, 0, 3) . '-' . substr($string, -4);
        } else {
            return '';
        }
    }

    /**
     * 生年月日のハイフン区分        20050801->2005-08-01
     *
     * @param $date
     * @return $date
     */
    function g_birthdaySplit($date)
    {
        if (!empty($date)) {
            if (strlen($date) <= 8) {
                $year = substr($date, 0, 4);
                $month = substr($date, 4, 2);
                $day = substr($date, 6, 2);
            }
            $date = $year . "-" . $month . "-" . $day;
            return $date;
        } else {
            return '0000-00-00';
        }
    }

    /**
     * 文字列を指定の長さずつスペースで区切りして改行させる。
     *
     * @param string $string
     * @param integer $lineLen
     * @return string
     */
    function g_splitBySpace($string, $lineLen)
    {
        $string = mb_convert_kana($string, 'S', 'utf-8');
        $array = explode('　', $string);
        $count = 0;
        $result = '';

        if (empty($array)) {
            return '';
        }

        foreach ($array as $key => $value) {
            if ($count + mb_strlen($value, 'utf-8') > $lineLen) {
                if (empty($result)) {
                    $result .= ($value . "\n");
                    $count = 0;
                } else {
                    $result .= ("\n" . $value);
                    $count = mb_strlen($value, 'utf-8');
                }
            } else {
                $result .= ($count == 0 ? $value : '　' . $value);
                $count += ($count == 0 ? mb_strlen($value, 'utf-8') : 1 + mb_strlen($value, 'utf-8'));
            }
        }
        return $result;
    }

    /**
     * レコード配列を指定のフィールドをキーにとして
     * 再整理する。
     *
     * array(array('id'=>1, 'name'=>'abc'), array('id'=>3, 'name'=>'xyz')) =>
     * => array(1 => array('id'=>1, 'name'=>'abc'), 3 => array('id'=>3, 'name'=>'xyz'))
     *
     * @param array $data
     * @param string $keyField
     * @return array
     */
    static function g_makeArrayIDKey($data, $idField = 'id')
    {
        $result = array();

        foreach ($data as $record) {
            if (!isset($record[$idField])) {
                continue;
            }
            $result[$record[$idField]] = $record;
        }

        return $result;
    }

    /**
     * 配列の要素を取得する。
     * (参照：g_getArrayValue とほぼ同じ)
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function g_getValue($array, $key, $default)
    {
        $key = (string)$key;
        if (isset($array[$key])) {
            return $array[$key];
        }
        return $default;
    }

    /**
     * 連想（Associated）配列から指定のフィールド値の配列を取得する。
     *
     * @access public
     * @param array $records
     * @param string $valueField
     * @param string $keyField
     * @return array
     */
    function g_fetchOneField($records, $valueField, $keyField = '')
    {
        $result = array();
        if (empty($keyField)) {
            foreach ($records as $record) {
                $result[] = $record[$valueField];
            }
            $result = array_unique($result);
        } else {
            foreach ($records as $record) {
                $result[$record[$keyField]] = $record[$valueField];
            }
        }

        return $result;
    }

    /**
     * 現在の時間を取得する。
     *
     * @return float : Unix timestamp
     */
    function g_getCurrentTime()
    {
        return microtime(true);
    }

    /**
     * fetch browser name from user agent
     * @return string
     */
    static function fetchBrowser() {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
            return 'Internet explorer';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
            return 'Internet explorer';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
            return 'Mozilla Firefox';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
            return 'Google Chrome';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
            return "Opera Mini";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
            return "Opera";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
            return "Safari";
        else
            return "Unknown";
    }
}