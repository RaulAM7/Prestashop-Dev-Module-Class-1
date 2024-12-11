<?php

// Thew constant test -> Chequea si hay una version de Prestashop presexistente
if (!defined('PS_VERSION')) {
    exit;
}

// Extendemos de MyModule

class Mymodule extends ModuleCore
{
    public function __construct()
    {
        $this->name = "MyModule";
        $this->tab = 'front_office_features'; // Categoria donde se mostrara en el Back Office
        $this->version = '1.0.0';
        $this->author = 'raulAM7';
        $this->need_instance = 0; // Indica si se carga la class entera del modulo cuando se muestra en la Page Modules del BO
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => '8.99.99',
        ];
        $this->bootsrap = true; // Indica si las templates que usa estan pensadas para las Bootstrap Prestashpo tools
        
        parent::__construct();
        
        $this->displayName = $this->trans('Mi Modulo', [], 'Modules.Mymodule.Admin');
        $this->description = $this->trans('Este modulo tiene una descripcion');
        $this->confirmUnistall = $this->trans('¿Estás seguro de que deseas desinstalar el módulo?')
    }
}
