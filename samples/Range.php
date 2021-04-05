<?php


    require_once '..\vendor\autoload.php';

    use Sipni\Sipni;


    $cert_file     = "D:\\Desenvolvimento\\PHP\\sipni\\key\\123456789.pem";
    $cert_password = "123456789";

    $app = new Sipni($cert_file, $cert_password);

    $retorno = $app->eSUSVE->search('capitais-imunizacao-covid-ro-porto-velho')
                           ->range(["data_importacao_rnds" => ["gt" => "2021-04-04 15:14:09"]])
                           ->scroll();

    var_dump($retorno);