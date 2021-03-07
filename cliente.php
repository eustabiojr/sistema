<?php
/********************************************************************************************
 * Sistema - Cliente
 * 
 * Autor: Eustábio Júnior
 * Data: 07/03/2021
 ********************************************************************************************/

$localizacao = 'http://localhost/sistema/rest.php'; 
$parametros = [];
$parametros['classe'] = 'ServicosPessoa';
$parametros['classe'] = 'obtDados';
$parametros['id'] = '1';

$url = $localizacao . '?' . http_build_query($parametros);
var_dump(json_decode(file_get_contents($url)));
