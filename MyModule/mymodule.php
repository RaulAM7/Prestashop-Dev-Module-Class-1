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
        $this->bootstrap = true; // Indica si las templates que usa estan pensadas para las Bootstrap Prestashpo tools
        
        parent::__construct();
        
        $this->displayName = $this->trans('Mi Modulo', [], 'Modules.Mymodule.Admin');
        $this->description = $this->trans('Este modulo tiene una descripcion');
        $this->confirmUninstall = $this->trans('¿Estás seguro de que deseas desinstalar el módulo?');
    }

    public function install()
    {
        if ( !parent::install() )
        {
            return false;
        }
        // Añadimos configuraciones predeterminadas
        $defaultConfigurations = [
            'NEW_MODULE_CONFIG' => "value"
        ];
        foreach ($defaultConfigurations as $key => $value)
        {
            if (!Configuration::updateValue($key, $value))
            {
                return false;
            }
        }
        // Añadimos Hooks 
        $hooks = ['displayHome'];
        foreach ($hooks as $hook)
        {
            if (!$this->registerHook($hook))
            {
                return false;
            }
        }
    }
    public function uninstall()
    {
        if(!parent::uninstall())
        {
            return false;
        }
        foreach($defaultConfigurations as $configuration)
        {
            if (!Configuration::deleteByName($configuration))
            {
                return false;
            }
        }
    }

    // PÁGINA DE CONFIGURACIÓN DEL BACK OFFICE
    public function getContent()
    {
        $output = '';

        // Procesar el formulario si se ha enviado

        if (Tools::isSubmit('submit'.$this->name)) // Comprueba si se ha enviado el formulario con el botón submitMyModule
        {
            $custom_setting = Tools::getValue('MY_CUSTOM_SETTING'); // Recupera el valor enviado en el formmulario
            Configuration::updateValue('MY_CUSTOM_SETTING', $custom_setting); // Guarda el valor en la base de datos
            $output .= $this->displayConfirmation($this->trans('Configuración actualizada.')); // Mensaje de éxito
        }

        return $output . $this->$this->renderForm();
    }















    // Metodos de Hooks del module
    // Hook basico
    public function hookDisplayHome()
    {
        return $this->display(__FILE__,'views/templates/hook/displayHome.tpl');
    }

    // Metodo getContent()
    
}
