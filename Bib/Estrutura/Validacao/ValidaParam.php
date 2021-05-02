<?php
/************************************************************************************
 * Sistema
 * 
 * Data: 02/05/2021
 ************************************************************************************/

class ValidaParam 
{
    public function __construct()
    {
        if (isset($_GET['metodo'])) {

            if ($_GET['metodo'] === 'aoEditar') {
                $campo_nome =  ['class' => 'form-control', 'id' => 'inputNome1', 'required' => NULL];
            } else {
                if (empty($_POST['nome'])) {    
                    $campo_nome =  ['class' => 'form-control is-invalid', 'id' => 'inputNome1', 'required' => NULL];
                } else {
                    $campo_nome =  ['class' => 'form-control is-valid', 'id' => 'inputNome1', 'required' => NULL];
                }
            }
    
        } else {
            $campo_nome = ['class' => 'form-control', 'id' => 'inputNome1', 'required' => NULL];
        }
    }
}