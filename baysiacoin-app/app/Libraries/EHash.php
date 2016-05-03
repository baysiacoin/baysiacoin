<?php 
/**
 * Hash Function
 * 
 * @author NewFund
 * @since  2015/01/02
 */
define("EHASH_ENCRYPTION_KEY", "!eter@#nal$%^live&*");

/**
 * Returns an encrypted & utf8-encoded
 */
function ehash_encrypt($pure_string, $encryption_key) {
    
	if (empty($pure_string)) {
        return '';
    }
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = base64_encode(mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, $pure_string, MCRYPT_MODE_ECB, $iv));
    
    return $encrypted_string;
}

/**
 * Returns decrypted original string
 */
function ehash_decrypt($encrypted_string, $encryption_key) {
    
	if (empty($encrypted_string)) {
        return '';
    }
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = base64_decode($encrypted_string);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    
    return $decrypted_string;
}
function generate_random_code($length=40) {

    $string = '';
    // You can define your own characters here.
    $characters = "0123456789ABCDEFHJKLMNPRTVWXYZabcdefghijklmnopqrstuvwxyz";

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters)-1)];
    }

    return $string;

}