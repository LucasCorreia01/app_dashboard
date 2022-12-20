<?php

class Dashboard {

    public $data_inicio;
    public $data_fim;
    public $numeroVendas;
    public $totalVendas;
    public $clientesAtivos;
    public $clientesInativos;
    public $totalReclamacoes;
    public $totalElogios;
    public $totalSugestoes;

    public function __get($attr){
        return $this->$attr;
    }

    public function __set($attr, $valor){
        $this->$attr = $valor;
        return $this;
    }

}

//Classe de conexao

class Conexao {
    
    private  $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';


    public function conectar() {
        try {

            $conexao = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                "$this->user",   
                "$this->pass"
            );

            $conexao->exec('set charset set utf8');

            return $conexao;


        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}


//classe (model)

class Bd {


    private $conexao;
    private $dashboard;

    public function __construct(Conexao $conexao, Dashboard $dashboard){
        $this->conexao = $conexao->conectar();
        $this->dashboard = $dashboard;

    }

    public function getNumeroVendas(){
        $query ='SELECT 
                    COUNT(*) as numeroVendas 
                from 
                    tb_vendas 
                WHERE 
                    data_venda BETWEEN :data_inicio AND :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->numeroVendas;

    }

    public function getTotalVendas(){
        $query ='SELECT 
                    SUM(total) as totalDeVendas
                from 
                    tb_vendas 
                WHERE 
                    data_venda BETWEEN :data_inicio AND :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->totalDeVendas;

    }

    public function getClientesAtivos(){
        $query ='SELECT 
                    COUNT(*) as clientesAtivos
                from 
                    tb_clientes 
                WHERE 
                    cliente_ativo = 1';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->clientesAtivos;

    }

    public function getClientesInativos(){
        $query ='SELECT 
                    COUNT(*) as clientesInativos
                from 
                    tb_clientes 
                WHERE 
                    cliente_ativo = 0';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->clientesInativos;

    }

    public function getTotalReclamacoes(){
        $query ='SELECT 
                    COUNT(*) as totalReclamacoes
                from 
                    tb_contato 
                WHERE 
                    tipo_contato = 1';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->totalReclamacoes;

    }

    public function getTotalElogios(){
        $query ='SELECT 
                    COUNT(*) as totalElogios
                from 
                    tb_contato 
                WHERE 
                    tipo_contato = 2';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->totalElogios;

    }

    public function getTotalSugestoes(){
        $query ='SELECT 
                    COUNT(*) as totalSugestoes
                from 
                    tb_contato 
                WHERE 
                    tipo_contato = 3';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->totalSugestoes;

    }

}


// lógica do script
$dashboard = new Dashboard();

$conexao = new Conexao();

$competencia = explode('-',$_GET['competencia']);

$ano = $competencia[0];
$mes = $competencia[1];

$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

$dashboard->__set('data_inicio', $ano.'-'.$mes.'-01');
$dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dias_do_mes);

$bd = new Bd($conexao, $dashboard);

$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
$dashboard->__set('totalVendas', $bd->getTotalVendas());
$dashboard->__set('clientesAtivos', $bd->getClientesAtivos());
$dashboard->__set('clientesInativos', $bd->getClientesInativos());
$dashboard->__set('totalReclamacoes', $bd->getTotalReclamacoes());
$dashboard->__set('totalElogios', $bd->getTotalElogios());
$dashboard->__set('totalSugestoes', $bd->getTotalSugestoes());

print_r($dashboard);

// echo '<pre>';
echo json_encode($dashboard);
// echo '</pre>';








?>
