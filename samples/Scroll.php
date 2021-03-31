<?php


    require_once '..\vendor\autoload.php';

    use Sipni\Sipni;


    $cert_file     = "D:\\Desenvolvimento\\PHP\\sipni\\key\\123456789.pem";
    $cert_password = "123456789";

    $app = new Sipni($cert_file, $cert_password);

    $retorno = $app->eSUSVE->search('capitais-imunizacao-covid-ro-porto-velho')
                           ->sort('document_id')
                           ->filter('paciente_cpf', '25720036865')
                           ->scroll();

    var_dump($retorno);