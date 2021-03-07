<?php
/********************************************************************************************
 * Sistema Agenet
 * Data: 10/04/2020
 ********************************************************************************************/

# Espaço de nomes
namespace Estrutura\BancoDados;

 /**
  * Interface InterfaceGravacao
  */
interface  InterfaceGravacao 
{
    public function doArray($dados);

    public function paraArray();

    public function grava();

    public function carrega($id);

    public function apaga($id = NULL);
} 