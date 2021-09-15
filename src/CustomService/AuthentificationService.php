<?php
/**
 * Date: 15/09/2021
 * Time: 11:51
 */

/* on aurait également pu utiliser un service provider Symfony */

/* pour ce test,
*  Considérons si bearerToken = P3I06ciZh7fbrWd9wibsCDkzi7_4VLkn alors on est authentifie
* je
 */

namespace App\CustomService;

class AuthentificationService
{
    private static function getBearerToken(){
        $bearerTOken = '';
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }

        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                $bearerTOken = $matches[1]; 
            }   
        }
        
        return $bearerTOken;
    }
    

    public static function isLogged() {
        return (!empty($_ENV['AUTHORIZED_KEY']) ? self::getBearerToken() : '')==($_ENV['AUTHORIZED_KEY'] ?? '' );
    }

}