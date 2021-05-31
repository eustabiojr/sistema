<?php

#use Estrutura\Bugigangas\Base\Elemento;
#use Estrutura\Bugigangas\Menu\Menu;

class ConstrutorMenu
{
    public static function analisa($arquivo, $tema)
    {
        switch ($tema)
        {
            case 'tema3':
                ob_start();
                $xml = new SimpleXMLElement(file_get_contents($arquivo));
                $menu = new Menu($xml, null, 1, 'treeview-menu', 'treeview', '');
                $menu->class = 'sidebar-menu';
                $menu->id    = 'side-menu';
                $menu->exibe();
                $menu_string = ob_get_clean();
                return $menu_string;
                break;
            default:
                ob_start();
                $xml = new SimpleXMLElement(file_get_contents($arquivo));
                $menu = new Menu($xml, null, 1, 'ml-menu', 'x', 'menu-toggle waves-effect waves-block');
                
                $li = new Elemento('li');
                $li->{'class'} = 'active';
                $menu->adic($li);
                
                $li = new Elemento('li');
                $li->adic('MENU');
                $li->{'class'} = 'header';
                $menu->adic($li);
                
                $menu->class = 'list';
                $menu->style = 'overflow: hidden; width: auto; height: 390px;';
                $menu->exibe();
                $menu_string = ob_get_clean();
                return $menu_string;
                break;
        }
    }
}