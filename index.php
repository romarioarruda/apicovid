<?php
require_once 'vendor/autoload.php';
require_once 'src/config/config.php';

$dadosCovid = new dadosCovidController;
$newsCovid  = new ultimasNoticiasController;

//Endpoints que pegam dados
Flight::route('GET /dados', array($dadosCovid, 'getDadosCovid'));
Flight::route('GET /dados/totais', array($dadosCovid, 'getCovidTotais'));
Flight::route('GET /dados/recuperados', array($dadosCovid, 'getRecuperados'));
Flight::route('GET /dados/obitos', array($dadosCovid, 'getObitos'));

//Endpoints de execução de varredura
Flight::route('GET /dados/ultimas-noticias', array($newsCovid, 'execUltimasNoticias'));

//Mapeando rota que não tem nada.
Flight::map('notFound', function(){
    echo '<h1>Rota sem conteúdo</h1>';
});

Flight::start();
