<?php
/**
 * Date: 25/09/2021
 * Time: 11:51
 */

namespace App\CustomService;


class RapidApiService
{
    const URL = 'https://imdb8.p.rapidapi.com/auto-complete';
    const HOST = 'imdb8.p.rapidapi.com';
    const KEY = '754237601amsh09f935cdb5ffcbfp1d2043jsn05080faa1d70';
    

    public static function getUrlImage($title)
    {

        $urlImage = '';

        if ($title!='') {

            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => SELF::URL."?q=".urlencode($title),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "x-rapidapi-host: ".SELF::HOST,
                    "x-rapidapi-key: ".SELF::KEY
                ],
            ]);

            $response = curl_exec($curl);
            $status = curl_error($curl);
            
            if (!$status) {
                return self::processReturnApi($response); 
            }
            curl_close($curl);

        }

        return $urlImage;

    } 
    
    private static function processReturnApi($data) {

        $url ='';

        if (empty($data)) return $url;
        $data = json_decode($data);
        if (empty($data)) return $url;
        if (empty($data->d)) return $url;
        if (empty($data->d[0])) return $url;
        if (empty($data->d[0]->v)) return $url;
        if (empty($data->d[0]->v[0])) return $url;
       
        if (empty($data->d[0]->v[0])) return $url;
        if (empty($data->d[0]->v[0]->i)) return $url;

        $url = $data->d[0]->v[0]->i->imageUrl ?? '';
        
        return $url;
    }   

 

}