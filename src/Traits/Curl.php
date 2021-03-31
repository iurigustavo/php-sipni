<?php

    namespace Sipni\Traits;

    use Sipni\Auth\Authenticate;

    trait Curl
    {
        protected Authenticate $auth;

        public function exec($url, $params = NULL)
        {

            if (sizeof($params) === 0) {
                $params = NULL;
            } else {
                $params = json_encode($params);
            }


            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 0,
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'GET',
                CURLOPT_POSTFIELDS     => $params,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: '.$this->auth->getAccessToken(),
                    'Content-Type: application/json'
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);
            if (!$response) {
                echo "Curl Error : ".curl_error($curl);
            }
            return $response;
        }
    }