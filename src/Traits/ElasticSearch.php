<?php


    namespace Sipni\Traits;

    use stdClass;

    class ElasticSearch
    {
        use Curl;

        protected string $url;

        private array $filters      = [];
        private array $ranges       = [];
        private array $sort         = [];
        private int   $size         = 10;
        private array $params       = [];
        private array $search_after = [];


        /**
         * Filtro de coluna
         *
         * @param $term
         * @param $value
         *
         * @return $this
         */
        public function filter($term, $value): self
        {
            $this->filters = [$term => $value];
            return $this;
        }

        /**
         * Filtro de coluna do tipo Range
         *
         * @param $params
         *
         * @return $this
         */
        public function range($params): self
        {
            $this->ranges = $params;
            return $this;
        }

        /**
         * Tamanho de registros para retornar, limite 10000
         *
         * @param  int  $size
         *
         * @return $this
         */
        public function size($size = 10): self
        {
            $this->size = $size;
            return $this;
        }

        /**
         * Ordenação por campo
         *
         * @param          $param
         * @param  string  $order
         *
         * @return $this
         */
        public function sort($param, $order = 'asc'): self
        {
            $this->sort[] = [$param => $order];
            return $this;
        }

        /**
         * Search_After, faz a consulta a após registro enviado usando como base o campo "sort", não pode utilizar junto do scroll
         *
         * @param  array  $params
         *
         * @return $this
         */
        public function search_after(array $params)
        {
            $this->search_after = $params;
            return $this;
        }

        /**
         * Retorna registros sem paginação utilizando o limite máximo
         *
         * @return stdClass
         */
        public function get(): ?stdClass
        {
            $this->buildParams();
            $response = json_decode($this->exec($this->url, $this->params));
            $this->clearParams();
            return $response;
        }

        private function buildParams()
        {
            $params = [];

            if (sizeof($this->filters) > 0) {
                $params['query']['term'] = $this->filters;
            }

            if (sizeof($this->ranges) > 0) {
                $params['query']['range'] = $this->ranges;
            }

            if (sizeof($this->sort) > 0) {
                $params['sort'] = $this->sort;
            }

            if (sizeof($this->search_after) > 0) {
                $params['search_after'] = $this->search_after;
            }

            $params['size'] = $this->size;

            $this->params = $params;
        }

        private function clearParams()
        {
            $this->filters      = [];
            $this->ranges       = [];
            $this->sort         = [];
            $this->size         = 10;
            $this->params       = [];
            $this->search_after = [];
            $this->url;
        }

        /**
         * Utilizado para retornar grandes quantidades de registros
         *
         * @param  string  $scrollTime
         *
         * @param  int     $size
         *
         * @return stdClass
         */
        public function scroll($scrollTime = '5m', $size = 10000): ?stdClass
        {
            $this->buildParams();
            unset($this->params['search_after']);
            $this->params['size']   = $size;
            $this->params['scroll'] = $scrollTime;
            $hitsList               = [];
            $concluiuExecucao       = FALSE;

            while ($concluiuExecucao == FALSE) {
                $response    = json_decode($this->exec($this->url, $this->params));
                $scrollId    = $response->data->_scroll_id;
                $filtroTotal = $response->data->hits->total->value;

                foreach ($response->data->hits->hits as $data) {
                    $hitsList[] = $data;
                }

                $total_retorno            = sizeof($response->data->hits->hits);
                $this->params['size']     = $filtroTotal > $size ? $size : $filtroTotal;
                $this->params['scrollId'] = $scrollId;

                if ($total_retorno == 0) {
                    $concluiuExecucao = TRUE;
                }
            }
            $response->data->hits->hits = $hitsList;

            $this->clearParams();

            return $response;
        }

        /**
         * @return int
         */
        public function count(): ?int
        {
            $this->url = str_replace('_search', '_count', $this->url);
            $this->buildParams();
            unset($this->params['sort']);
            unset($this->params['size']);
            $response = json_decode($this->exec($this->url, $this->params))->data->count;
            $this->clearParams();
            return $response;
        }

    }