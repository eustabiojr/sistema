<?php
namespace Matematica\EstrategiaTraducao;

/**
 * Interface estrategia de tradução
 * 
 * @author Adrean Boyadzhiev (netforce) <adrean.boyadzhiev@gmail.com>
 */
interface InterfaceEstrategiaTraducao
{
    public function traduz(array $fichas);
}