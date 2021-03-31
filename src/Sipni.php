<?php

    namespace Sipni;

    use Sipni\Auth\Authenticate;
    use Sipni\Search\eSUSVE;

    session_start();

    class Sipni
    {

        /**
         * @var eSUSVE
         */
        public eSUSVE $eSUSVE;

        /**
         * SIPNI constructor.
         *
         * @param  String  $cert_file
         * @param  String  $cert_password
         * @param  String  $auth_url
         */
        public function __construct(String $cert_file, String $cert_password, String $auth_url = 'https://ehr-auth.saude.gov.br/api/token')
        {
            $auth         = new Authenticate($cert_file, $cert_password, $auth_url);
            $this->eSUSVE = new eSUSVE($auth);
        }

    }