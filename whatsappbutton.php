<?php
if (!defined('_PS_VERSION_'))
{
    exit();
}

class whatsappButton extends Module
{
    public function __construct()
    {
        $this->name = 'whatsappbutton';
        $this->tab= 'front_office_features';
        $this->version= '1.0.0';
        $this->author = 'Edisson Reinozo - edzzn.com';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap  = true;

        parent::__construct();

        $this->displayName = $this->l('Whatsapp Button');
        $this->description = $this->l('Adds a Whatsapp Message button to the store');

        $this->confirmuUnistall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('WHATSAPPBUTTON_PHONENUMBER'))
            $this->warning = $this->l('No number provided');
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name))
        {
            $whatsapp_phone_number = strval(Tools::getValue('WHATSAPPBUTTON_PHONENUMBER'));

            if (!$whatsapp_phone_number ||
                empty($whatsapp_phone_number) ||
                !Validate::isGenericName($whatsapp_phone_number)
            )
                $output .= $this->displayError($this->l('Invalid Phone number'));

            else
            {
                Configuration::updateValue('WHATSAPPBUTTON_PHONENUMBER', $whatsapp_phone_number);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $output.$this->displayForm();
    }

    public function displayForm(){
        // Get default language
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Phone Number'),
                    'desc' => $this->l('The full phone number in international format. Omit any zeroes, brackets or dashes when adding the phone number in international format'),
                    'name' => 'WHATSAPPBUTTON_PHONENUMBER',
                    'size' => 20,
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex,'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['WHATSAPPBUTTON_PHONENUMBER'] = Configuration::get('WHATSAPPBUTTON_PHONENUMBER');

        return $helper->generateForm($fields_form);
    }

    public function hookDisplayLeftColumn($params)
    {
        $this->context->smarty->assign(
            array(
                'whatsapp_phone_number' => Configuration::get('WHATSAPPBUTTON_PHONENUMBER'),
                'whatsappbutton_link' => $this->context->link->getModuleLink('whatsappbutton', 'display')
            )
        );
        return $this->display(__FILE__, 'whatsappbutton.tpl');
    }

    public function hookDisplayRightColumn($params)
    {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/whatsappbutton.css', 'all');
    }

    public function install()
    {
        if(Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        if (!parent::install() ||
            !$this->registerHook('leftColumn') ||
            !$this->registerHook('header') ||
            !$this->registerHook('productAdditionalInfo')
        )
            return false;

        return true;
    }

    public function uninstall()
    {
        IF(!parent::uninstall() ||
            !Configuration::deleteByName('WHATSAPPBUTTON_PHONENUMBER')
        )
            return false;

        return true;
    }
}