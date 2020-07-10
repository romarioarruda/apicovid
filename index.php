<?php
require_once 'vendor/autoload.php';
require_once 'src/config/config.php';

$dadosCovid = new dadosCovidController;
$varreduraCovid = new execVarreduraCovidController;
$newsCovid  = new ultimasNoticiasController;

//Endpoints que pegam dados
Flight::route('GET /apicovid', array($dadosCovid, 'getDadosCovid'));
Flight::route('GET /apicovid/totais', array($dadosCovid, 'getCovidTotais'));
Flight::route('GET /apicovid/recuperados', array($dadosCovid, 'getRecuperados'));
Flight::route('GET /apicovid/obitos', array($dadosCovid, 'getObitos'));

//Endpoints de execução de varredura
Flight::route('GET /apicovid/atualizar-casos', array($varreduraCovid, 'execVarreduraApiGoverno'));
Flight::route('GET /apicovid/atualizar-acumulados', array($varreduraCovid, 'execTotalAcumulado'));
Flight::route('GET /apicovid/ultimas-noticias', array($newsCovid, 'execUltimasNoticias'));

//Mapeando rota que não tem nada.
Flight::map('notFound', function(){
    echo '<h1>Rota sem conteúdo</h1>';
});

Flight::start();