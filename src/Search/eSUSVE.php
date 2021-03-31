<?php

    namespace Sipni\Search;

    use Sipni\Auth\Authenticate;
    use Sipni\Traits\Curl;
    use Sipni\Traits\ElasticSearch;

    class eSUSVE extends ElasticSearch
    {

        /**
         * eSUSVE constructor.
         *
         * @param  Authenticate  $authenticate
         */
        public function __construct(Authenticate $authenticate)
        {
            $this->auth = $authenticate;
        }

        /**
         * @param          $portfolio
         * @param  string  $url
         *
         * @return $this
         */
        public function search($portfolio, $url = 'https://servicos-es.saude.gov.br/e-SUSVE/'): self
        {
            $this->auth->generateAccessCode();
            $this->url = $url.$portfolio.'/_search';
            return $this;
        }

    }