# Consulta ao DATASUS SIPNI

Classe de ajuda para consulta ao SI-PNI do DataSUS

## Material de Apoio
- [Guia de apoio no consumo de dados 
   utilizando a API do SIPNI](https://mobileapps.saude.gov.br/portal-servicos/files/f3bd659c8c8ae3ee966e575fde27eb58/240280657277e78b7277af7bdf52d473_5oe1zi7x9.pdf) [Documentação da API]

## Pré-requisitos

- PHP >= 7.4


## Instalação


### Executar

- executar `composer require iurigustavo/php-sipni`git 

### Converter chave formato *.pfx para *.pem
executar `openssl pkcs12 -in filename.pfx -out cert.pem`

### Utilização
```
    use Sipni\Sipni;

    $cert_file     = "\\keys\\cert.pem"; // Caminho absoluto
    $cert_password = "123456789";
    $portfolio = "capitais-imunizacao-covid-ro-porto-velho";

    $app = new Sipni($cert_file, $cert_password);

    $retorno = $app->eSUSVE->search($portfolio)
                           ->size(10)
                           ->sort('document_id')
                           ->get();
    var_dump($retorno); // Visualizar o OBJ
```


## Problemas, Perguntas e Pull Requests
Você pode relatar problemas ou fazer perguntas na [issues section](https://github.com/iurigustavo/php-sipni/issues). Por favor, comece seu problema com `PROBLEMA:` e sua pergunta com `PERGUNTA:` no assunto.

Se você tiver alguma dúvida, é recomendável pesquisar e verificar os problemas encerrados primeiro.

Para enviar um Pull Request, por favor criar um fork deste repositório, crie um novo branch e envie seu código novo/atualizado lá. Em seguida, abra uma solicitação pull de sua nova branch. Consulte [este guia](https://help.github.com/articles/about-pull-requests/) para obter mais informações. 

Ao enviar uma solicitação pull, leve as próximas notas em consideração:
- Verifique se o Pull Request não introduz um grande rebaixamento na qualidade do código.
