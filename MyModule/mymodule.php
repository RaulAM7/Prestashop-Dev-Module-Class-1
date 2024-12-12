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

    // Método renderForm() -> Formulario del FrontEnd del BO con el que interactua el user para configurar el modulo
    
    public function renderForm()
    {
        // Definicion de los campos del formulario
        $fields_form = [
                'form' => [
                    'legend' => [
                        $this->trans('Configuración de Mi Módulo'),
                    'icon' => 'icon-cogs', // ESTO PODEMOS CAMBIARLO?
                    ],
                    'input' => [
                        [
                            'type'=> 'text', // Tipo de campo
                            'label' => $this->trans('Ajuste Personalizado'), // Etiqueta del input
                            'name' => 'MY_CUYSTOM_SETTING', // Nombre tecnico del campo
                            'required' => true, // Es olbigatorio
                        ],
                    ]
                ],

                'submit' => [
                    'title' => $this->trans('Guardar'), // Texto del botón de guardar
                ],

            ];

        //  Configuracion del helperForm
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules'); // Securitry Token
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name ;// Currnet index URL
        $helper->fileds_value['MY_CUYSTOM_SETTING'] = Configuration::get('MY_CUYSTOM_SETTING'); // Valor actual
        $helper->submit_action = 'submit'.$this->name; // Nombre del voton de accion
        
        return $helper->generateForm([$fields_form]); // Genera el formulario en HTML el Front End del BO

        }






    // Método getContent() -> Recupera la informacion del Form y ejecuta la Configuracion del Modulo
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
