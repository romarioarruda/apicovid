<?php

class dadosCovidController {

    public function getDadosCovid() {
        header("Access-Control-Allow-Origin: *");
        
        $filtro_uf = Flight::request()->query->uf;

        return $filtro_uf ? $this->getCovidFiltroUF($filtro_uf) : $this->getCovidAll();
    }


    public function getCovidTotais() {
        header("Access-Control-Allow-Origin: *");
        
        $totais = CovidAcumulados::getOne(['order' => 'ORDER BY last_updated DESC']);

        if(!$totais) return Flight::json(array('totais' => []));

        $dados = [
            'casos_acumulado' =>$totais->casos_acumulado,
            'obitos_acumulado' => $totais->obitos_acumulado
        ];

        return Flight::json(array('totais' => $dados));
    }


    public function getCovidFiltroUF($uf) {
        $result = Covid::getOne(['uf' => addslashes($uf)]);
        
        if(!$result) return Flight::json(array('casos' => []));

        $dados = [
            'id_registro' => $result->id_registro,
            'uf' =>$result->uf,
            'casos_acumulado' => $result->casos_acumulado,
            'obitos_acumulado' => $result->obitos_acumulado,
            'last_updated' => $result->last_updated
        ];

        return Flight::json(array('casos' => $dados));
    }


    public function getCovidAll(){
        $result =  Covid::get();
        $dados  = [];

        if(!$result) return Flight::json(array('casos' => $dados));
        
        foreach($result as $chave => $valor) {
            $dados[] = [
                'id_registro' => $valor->id_registro,
                'uf' =>$valor->uf,
                'casos_acumulado' => $valor->casos_acumulado,
                'obitos_acumulado' => $valor->obitos_acumulado,
                'last_updated' => $valor->last_updated
            ];
        }
        return Flight::json(array('casos' => $dados));
    }
 

    public function getRecuperados() {
        header("Access-Control-Allow-Origin: *");

        $data_ini = Flight::request()->query->data_ini;
        $data_fim = Flight::request()->query->data_fim;

        $data_ini_default = date('Y-m-01');
        $data_fim_default = date('Y-m-t');

        $ini = $data_ini ? $data_ini : $data_ini_default;
        $fim = $data_fim ? $data_fim : $data_fim_default;

        $this->getRecuperadosEntreData($ini, $fim);
    }

    public function verificaFormatoDeDataValido($data_ini, $data_fim) {
        if(!preg_match("#\d{4}-\d+-\d+#is", $data_ini)) {
            return Flight::json(array('erro' => 'Data inicial não reconhecida, utilize o formato: '.date('Y-m-d')));
        }

        if(!empty($data_fim) && !preg_match("#\d{4}-\d+-\d+#is", $data_fim)) {
            return Flight::json(array('erro' => 'Data final não reconhecida, utilize o formato: '.date('Y-m-d')));
        }
        return true;
    }


    public function recuperadosUmResultadoPorData($data){
        $result = CovidRecuperados::getOne(["raw" => "last_updated LIKE '%{$data}%'"]);

        if(!$result) return Flight::json(array('recuperados' => []));

        $dados = [
            'id_registro' => $result->id_registro,
            'novos' => $result->novos,
            'recuperados' => $result->recuperados,
            'acompanhamento' => $result->acompanhamento,
            'last_updated' => $result->last_updated
        ];
        return Flight::json(array('recuperados' => $dados));
    }


    public function retornaRecuperadosEntreDatas($data_ini, $data_fim){
        $dados = [];
        $result = CovidRecuperados::get(["raw" => "last_updated BETWEEN '{$data_ini} 00:00:00' AND '{$data_fim} 23:59:59'"]);
        if(!$result) return Flight::json(array('recuperados' => []));
        foreach($result as $chave => $valor) {
            $dados[] = [
                'id_registro' => $valor->id_registro,
                'novos' => $valor->novos,
                'recuperados' => $valor->recuperados,
                'acompanhamento' => $valor->acompanhamento,
                'last_updated' => $valor->last_updated
            ];
        }
        return Flight::json(array('recuperados' => $dados));
    }


    public function getRecuperadosEntreData($data_ini, $data_fim){
        $validacaoData = $this->verificaFormatoDeDataValido($data_ini, $data_fim);

        if(!$validacaoData) return false;

        $this->retornaRecuperadosEntreDatas($data_ini, $data_fim);
    }


    public function getRecuperadosAll() {
        $dados = [];
        $result = CovidRecuperados::get();

        if(!$result) return Flight::json(array('recuperados' => $dados));
        
        foreach($result as $chave => $valor) {
            $dados[] = [
                'id_registro' => $valor->id_registro,
                'novos' => $valor->novos,
                'recuperados' => $valor->recuperados,
                'acompanhamento' => $valor->acompanhamento,
                'last_updated' => $valor->last_updated
            ];
        }
        return Flight::json(array('recuperados' => $dados));
    }


    public function getObitos() {
        header("Access-Control-Allow-Origin: *");

        $data_ini = Flight::request()->query->data_ini;
        $data_fim = Flight::request()->query->data_fim;

        $data_ini_default = date('Y-m-01');
        $data_fim_default = date('Y-m-t');

        $ini = $data_ini ? $data_ini : $data_ini_default;
        $fim = $data_fim ? $data_fim : $data_fim_default;

        $this->getObitosEntreData($ini, $fim);
    }


    public function getObitosEntreData($data_ini, $data_fim){
        $validacaoData = $this->verificaFormatoDeDataValido($data_ini, $data_fim);

        if(!$validacaoData) return false;
        
        $this->retornaObitosEntreDatas($data_ini, $data_fim);
    }


    public function getObitosAll(){
        $dados  = [];
        $result = CovidObitos::get();

        if(!$result) return Flight::json(array('obitos' => $dados));
        
        foreach($result as $chave => $valor) {
            $dados[] = [
                'id_registro' => $valor->id_registro,
                'novos' => $valor->novos,
                'last_updated' => $valor->last_updated
            ];
        }
        return Flight::json(array('obitos' => $dados));
    }


    public function obitosUmResultadoPorData($data_ini){
        $result = CovidObitos::getOne(["raw" => "last_updated LIKE '%{$data_ini}%'"]);

        if(!$result) return Flight::json(array('obitos' => []));

        $dados = [
            'id_registro' => $result->id_registro,
            'novos' => $result->novos,
            'last_updated' => $result->last_updated
        ];
        return Flight::json(array('obitos' => $dados));
    }


    public function retornaObitosEntreDatas($data_ini, $data_fim){
        $dados  = [];
        $result = CovidObitos::get(["raw" => "last_updated BETWEEN '{$data_ini} 00:00:00' AND '{$data_fim} 23:59:59'"]);
        
        if(!$result) return Flight::json(array('obitos' => []));

        foreach($result as $chave => $valor) {
            $dados[] = [
                'id_registro' => $valor->id_registro,
                'novos' => $valor->novos,
                'last_updated' => $valor->last_updated
            ];
        }
        return Flight::json(array('obitos' => $dados));
    }
}
