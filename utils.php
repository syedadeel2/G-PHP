<?php

namespace Helpers;

require_once 'repos/application.php';
require_once 'repos/domain_whitelist.php';

use Repositories\ApplicationRepository;
use Repositories\DomainWhitelistRepository;

class Utils
{

    /**
     * Encrypt the string
     *
     * @param string $str
     * @return void
     */
    public static function encryptText($str)
    {
        // Store the cipher method
        $ciphering = "AES-128-CTR";

        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1104567891011121';

        // Store the encryption key
        $encryption_key = self::generateKey();

        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt($str, $ciphering, $encryption_key, $options, $encryption_iv);

        return $encryption;
    }

    /**
     * Decrypt the encrypted string
     *
     * @param string $encryption
     * @return void
     */
    public static function decryptText($encryption)
    {
        // Store the cipher method
        $ciphering = "AES-128-CTR";

        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Non-NULL Initialization Vector for decryption
        $decryption_iv = '1104567891011121';

        // Store the decryption key
        $decryption_key = self::generateKey();

        // Use openssl_decrypt() function to decrypt the data
        $decryption = openssl_decrypt($encryption, $ciphering, $decryption_key, $options, $decryption_iv);

        return $decryption;
    }

    /**
     * Generate the Unqiue key based on server information
     *
     * @return void
     */
    public static function generateKey()
    {
        return openssl_digest(php_uname() . "-" . $_SERVER["REMOTE_ADDR"], 'MD5', true);
    }

    /**
     * Validate the headers to enable the security for incoming request.
     *
     * @param boolean $isAdminHeaders
     * @return bool
     */
    public static function validateHeaders(bool $isAdminHeaders = false): bool
    {

        $headers = getallheaders();
        $ip = $_SERVER["REMOTE_ADDR"];
        $app_repo = new ApplicationRepository();
        $app_domain_repo = new DomainWhitelistRepository();
        $app = null;

        // Validate the allowed methods
        // =======================================================================
        $allowed_methods = ["GET", "POST", "DELETE", "OPTIONS", "PUT"];

        if (!in_array($_SERVER['REQUEST_METHOD'], $allowed_methods)) {

            http_response_code(406);
            echo $_SERVER['REQUEST_METHOD'] . " method not allowed";
            return false;

        }
        // =======================================================================

        // Validate the accept type
        // =======================================================================
        if (!isset($headers["Accept"])) {

            http_response_code(406);
            echo "Accept header is missing";
            return false;

        } else if (isset($headers["Accept"]) && $headers["Accept"] !== "application/json") {

            http_response_code(406);
            echo "Only application/json is allowed for Accept header";
            return false;

        }
        // =======================================================================

        // Validate the x-api-key if its admin calls
        // =======================================================================
        if ($isAdminHeaders == true) {

            if (!isset($headers["x-api-key"])) {

                http_response_code(406);
                echo "x-api-key header is missing";
                return false;

            } else if (empty($headers["x-api-key"])) {

                http_response_code(406);
                echo "x-api-key value is missing";
                return false;

            } else if (Utils::decryptText($headers["x-api-key"]) !== "G-PHP-BaaS-$ip") {

                http_response_code(401);
                echo "x-api-key is not valid";
                return false;

            }

        }
        // =======================================================================

        // Validate the g-api-key for generic calls
        // =======================================================================
        if ($isAdminHeaders == false) {

            if (!isset($headers["G-Api-Key"])) {

                http_response_code(406);
                echo "g-api-key header is missing";
                return false;

            } else if (empty($headers["G-Api-Key"])) {

                http_response_code(406);
                echo "g-api-key value is missing";
                return false;

            } else if (!empty($headers["G-Api-Key"])) {

                $key = $headers["G-Api-Key"];
                $app = $app_repo->getByKey($key);

                if ($app == null) {
                    http_response_code(401);
                    echo "g-api-key is not valid";
                    return false;
                }

            }

        }
        // =======================================================================

        // Validate the origin for generic calls if application is found and if there any
        // whitelisted enabled
        // =======================================================================
        if ($isAdminHeaders == false) {

            $whitelists = $app_domain_repo->getByApplicationId($app->id);

            if ($whitelists != null && sizeof($whitelists) > 0) {

                $domains = array_column($whitelists, "domain");
                $ips = array_column($whitelists, "ip_address");

                var_dump($domains);

                if (isset($_SERVER['HTTP_ORIGIN']) && !in_array($_SERVER['HTTP_ORIGIN'], $domains)) {
                    http_response_code(401);
                    echo "origin is not whitelisted.";
                    return false;
                }

            }

        }
        // =======================================================================

        return true;
    }

    /**
     * Returns a GUIDv4 string
     *
     * Uses the best cryptographically secure method
     * for all supported pltforms with fallback to an older,
     * less secure version.
     *
     * @param bool $trim
     * @return string
     */
    public static function GUIDv4($trim = true)
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true) {
                return trim(com_create_guid(), '{}');
            } else {
                return com_create_guid();
            }

        }

        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        // Fallback (PHP 4.2+)
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $lbrace = $trim ? "" : chr(123); // "{"
        $rbrace = $trim ? "" : chr(125); // "}"
        $guidv4 = $lbrace .
            substr($charid, 0, 8) . $hyphen .
            substr($charid, 8, 4) . $hyphen .
            substr($charid, 12, 4) . $hyphen .
            substr($charid, 16, 4) . $hyphen .
            substr($charid, 20, 12) .
            $rbrace;
        return $guidv4;
    }

    /**
     * Generates the .htaccess file with all the configuration needed for application.
     */
    public static function generateHtAccess()
    {

        // open template file for reading and store the content to variable
        $fp_template = fopen($_SERVER["DOCUMENT_ROOT"] . '/htaccess.template', 'r');
        $template = fread($fp_template, filesize($_SERVER["DOCUMENT_ROOT"] . "/htaccess.template"));
        fclose($fp_template);

        $app_rewrites = "";
        // get all the registered applications
        $app_repo = new ApplicationRepository();
        $app_listings = $app_repo->getAll();

        $app_domain_repo = new DomainWhitelistRepository();
        foreach ($app_listings as $app) {

            $app_rewrites .= "    # app = $app->name" . "\n";
            // if application have any whitelisting
            $app_whitelists = $app_domain_repo->getByApplicationId($app->id);
            if ($app_whitelists != null) {
                $isOr = sizeof($app_whitelists) > 0 ? "[OR]" : "";
                $cnt = 0;
                foreach ($app_whitelists as $listing) {

                    // dont add [OR] in last condition
                    if ($cnt == sizeof($app_whitelists) - 1) $isOr = "";

                    if (!empty($listing->domain)) {

                        $app_rewrites .= "    RewriteCond %{HTTP_HOST} =$listing->domain $isOr" . "\n";

                    } else if (!empty($listing->ip_address)) {

                        $escaped_ip = str_replace(".", "\.", $listing->ip_address);
                        $app_rewrites .= "    RewriteCond %{REMOTE_ADDR} =^$escaped_ip$ $isOr" . "\n";

                    }

                    $cnt++;
                }
            }

            $app_rewrites .= "    RewriteRule /api/$app->app_api_slug/(.*)$ api/generic/api.php [QSA,NC,L]" . "\n\r";

        }

        $template = str_replace("{app_rewrites}", $app_rewrites, $template);

        // write the final template to .htaccess file and close it.
        $fp = fopen($_SERVER["DOCUMENT_ROOT"] . '/.htaccess', 'w+');
        if ($fp) {
            fwrite($fp, $template);
            fclose($fp);
        }
    }
}
