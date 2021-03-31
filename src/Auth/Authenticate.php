<?php

    namespace Sipni\Auth;

    class Authenticate
    {
        /**
         * @var string|null
         */
        private ?string $access_token;
        /**
         * @var String
         */
        private String $cert_file;
        /**
         * @var String
         */
        private String $cert_password;
        /**
         * @var String
         */
        private String $auth_url;

        /**
         * Authenticate constructor.
         *
         * @param  String  $cert_file
         * @param  String  $cert_password
         * @param  String  $auth_url
         */
        public function __construct(String $cert_file, String $cert_password, String $auth_url)
        {
            $this->cert_file     = $cert_file;
            $this->cert_password = $cert_password;
            $this->auth_url      = $auth_url;
        }


        public function generateAccessCode()
        {

            if (!$this->validateToken()) {
                $curl = curl_init();

                // Variables
                $apiUrl       = $this->auth_url;
                $certFile     = $this->cert_file;                                // Private Cert
                $certPassword = $this->cert_password;                            // Cert Password


                $apiHeader = [];

                // $header Content Length
                $apiHeader[] = 'Content-length: 0';

                // $header Content Type
                $apiHeader[] = 'Content-type: application/json';


                // cURL Options
                curl_setopt_array($curl, [
                    CURLOPT_URL            => $apiUrl,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_ENCODING       => '',
                    CURLOPT_MAXREDIRS      => 10,
                    CURLOPT_TIMEOUT        => 0,
                    CURLOPT_FOLLOWLOCATION => TRUE,
                    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST  => 'GET',

                    CURLOPT_USERPWD       => $certPassword,
                    CURLOPT_SSLCERTTYPE   => 'PEM',
                    CURLOPT_SSLCERT       => $certFile,
                    CURLOPT_SSLCERTPASSWD => $certPassword
                ]);

                $response = curl_exec($curl);

                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }
                if (isset($error_msg)) {
                    echo "Curl Error : ".$error_msg;
                    exit;
                }
                curl_close($curl);
                $json                        = json_decode($response);
                $_SESSION['ds_access_token'] = $json->access_token;
                $_SESSION['ds_expires_in']   = $json->expires_in;
                $this->access_token          = $json->access_token;
            }
        }

        private function validateToken(): bool
        {
            if (!isset($_SESSION['ds_access_token'])) {
                $this->access_token = NULL;
                return FALSE;
            }
            if (isset($_SESSION['ds_expires_in'])) {
                if (time() - $_SESSION['ds_expires_in'] > $_SESSION['ds_expires_in']) {
                    unset($_SESSION['ds_access_token']);
                    unset($_SESSION['ds_expires_in']);
                    $this->access_token = NULL;
                    return FALSE;
                }
            }
            return TRUE;
        }

        /**
         * @return string
         */
        public function getAccessToken(): ?string
        {
            return $this->access_token;
        }

    }