<?php
/*
* The MIT License (MIT)
* 
* Copyright (c) 2013 Iztok Svetik
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
* -----------------------------------------------------------------------------
* @author   Iztok Svetik
* @website  http://www.isd.si
* @github   https://github.com/iztoksvetik
*/

if (!defined('_PS_VERSION_'))
	exit;

class SendyNewsletter extends Module
{
	private $list;
	private $installation;
	private $setup;

	public function __construct()
	{
		$this->name = 'sendynewsletter';
	    $this->tab = 'front_office_features';
	    $this->version = '1.0';
	    $this->author = 'Iztok Svetik - isd.si';
	    $this->need_instance = 0;
	    $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.5.9'); 

	    
	 
	    $this->displayName = $this->l('Sendy Newsletter Block');
	    $this->description = $this->l('Adds a block that imports subscribers directly to your Sendy list.');
	 
	    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	 
	 	if (Configuration::get('SENDYNEWSLETTER_INSTALLATION')) {
	 		$this->installation = Configuration::get('SENDYNEWSLETTER_INSTALLATION');
	 	}
	 	if (Configuration::get('SENDYNEWSLETTER_LIST')) {
	 		$this->list = Configuration::get('SENDYNEWSLETTER_LIST');

	 	}
	    if (!isset($this->installation) || !isset($this->list)) {
	    	$this->warning = $this->l('You need to set installation url and list before using this module');
	    	$this->setup = false;
	    }
	    else {
	    	$this->setup = true;
	    }

	    parent::__construct();
	}

	public function install()
	{
		if (Shop::isFeatureActive()) {
		  Shop::setContext(Shop::CONTEXT_ALL);
		}

		return parent::install() 
		&& $this->registerHook('leftColumn')
		&& $this->registerHook('header')
		&& Configuration::updateValue('SENDYNEWSLETTER_IP', false)
		&& Configuration::updateValue('SENDYNEWSLETTER_IPVALUE', '')
		&& Configuration::updateValue('SENDYNEWSLETTER_NAME', false)
		&& Configuration::updateValue('SENDYNEWSLETTER_NAMEREQ', false);
	}

	public function uninstall()
	{
		return parent::uninstall()
		&& Configuration::deleteByName('SENDYNEWSLETTER_INSTALLATION')
		&& Configuration::deleteByName('SENDYNEWSLETTER_LIST')
		&& Configuration::deleteByName('SENDYNEWSLETTER_IP')
		&& Configuration::deleteByName('SENDYNEWSLETTER_IPVALUE')
		&& Configuration::deleteByName('SENDYNEWSLETTER_NAME')
		&& Configuration::deleteByName('SENDYNEWSLETTER_NAMEREQ');
	}

	public function hookDisplayLeftColumn($params)
	{
		$this->context->controller->addJS($this->_path.'views/js/sendynewsletter.js');
		$sendy = array(
      		'url' 		=> Configuration::get('SENDYNEWSLETTER_INSTALLATION'),
			'list' 		=> Configuration::get('SENDYNEWSLETTER_LIST'),
			'ip' 		=> (int)Configuration::get('SENDYNEWSLETTER_IP'),
			'ipval'		=> $_SERVER["REMOTE_ADDR"],
			'ipfield'	=> Configuration::get('SENDYNEWSLETTER_IPVALUE'),
			'name' 		=> (int)Configuration::get('SENDYNEWSLETTER_NAME'),
			'namereq' 	=> (int)Configuration::get('SENDYNEWSLETTER_NAMEREQ')
      	);
		$this->context->smarty->assign(array(
				'sendynews' => $sendy
			));
		if ($this->setup) {
			return $this->display(__FILE__, 'sendynewsletter.tpl');
		}
	}

	public function hookDisplayRightColumn($params)
	{
		return $this->hookDisplayLeftColumn($params);
	}
	
	public function hookDisplayHeader($params)
	{
		$this->context->controller->addCSS($this->_path.'views/css/sendynewsletter.css', 'all');
	}

	public function getContent()
  {
      $output = null;
   
      if (Tools::isSubmit('submit'.$this->name))
      {
          $installation = Tools::getValue('SENDYNEWSLETTER_INSTALLATION');
          $list = Tools::getValue('SENDYNEWSLETTER_LIST');
          $ip = (int)Tools::getValue('SENDYNEWSLETTER_IP');
          $ip_var = Tools::getValue('SENDYNEWSLETTER_IPVALUE');
          $name = (int)Tools::getValue('SENDYNEWSLETTER_NAME');
          $name_req = (int)Tools::getValue('SENDYNEWSLETTER_NAMEREQ');
          
          if (!$installation  || empty($installation) || !Validate::isAbsoluteUrl($installation)) {
              $output .= $this->displayError( $this->l('Invalid installation url'));
          }
          if (!$list  || empty($list) || !Validate::isGenericName($list)) {
              $output .= $this->displayError( $this->l('Invalid list'));
          }
          if ($ip == 1)
          {
              if (!$ip_var  || empty($ip_var) || !Validate::isGenericName($ip_var)) {
              	$output .= $this->displayError( $this->l('Invalid ip custom field value'));
              }
          }

          if ($output == null)
          {
          	  Configuration::updateValue('SENDYNEWSLETTER_INSTALLATION', $installation);
          	  Configuration::updateValue('SENDYNEWSLETTER_LIST', $list);
          	  Configuration::updateValue('SENDYNEWSLETTER_IP', $ip);
              Configuration::updateValue('SENDYNEWSLETTER_IPVALUE', $ip_var);
              Configuration::updateValue('SENDYNEWSLETTER_NAME', $name);
              Configuration::updateValue('SENDYNEWSLETTER_NAMEREQ', $name_req);
              $output .= $this->displayConfirmation($this->l('Settings updated'));
          }
      }
      
      return $output.$this->displayForm();
  }

  public function displayForm()
  {
    // Get default Language
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
     
    // Init Fields form array
    $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $this->l('Sendy Newsletter Settings'),
            'image' => '../modules/sendynewsletter/logo.gif' 
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $this->l('Installation'),
                'name' => 'SENDYNEWSLETTER_INSTALLATION',
                'desc' => $this->l('Url address of your sendy installation eg "http://your_sendy_installation"'),
                'size' => 30,
                'required' => true
            ),
            array(
                'type' => 'text',
                'label' => $this->l('List'),
                'name' => 'SENDYNEWSLETTER_LIST',
                'desc' => $this->l('The list id you want to subscribe a user to. This encrypted & hashed id can be found under "View all lists" section named "ID"'),
                'size' => 30,
                'required' => true
            ),
            array(
                'type' => 'radio',
                'label' => $this->l('Capture user IP'),
                'name' => 'SENDYNEWSLETTER_IP',
                'desc' => $this->l('You might want to store subscribers IP address as it might be required of you by the local law.'),
                'is_bool' => true,
                'class' => 't',
                'values' => array(
                  array(
                    'id' => 'ip_on',
                    'value' => 1,
                    'label' => $this->l('Enabled')
                  ),
                  array(
                    'id' => 'ip_off',
                    'value' => 0,
                    'label' => $this->l('Disabled')
                  )
                )
            ),
            array(
                'type' => 'text',
                'label' => $this->l('IP value'),
                'name' => 'SENDYNEWSLETTER_IPVALUE',
                'desc' => $this->l('If you want to store subscibers IP address you will need to create a new custom field in your list. Input the name of that field here exactly, "IP" is not the same as "ip".'),
                'size' => 20
            ),
            array(
                'type' => 'radio',
                'label' => $this->l('Subscribers Name'),
                'name' => 'SENDYNEWSLETTER_NAME',
                'desc' => $this->l('If checked the subscribe block will also have a field for subscriber\'s name.'),
                'is_bool' => true,
                'class' => 't',
                'values' => array(
                  array(
                    'id' => 'name_on',
                    'value' => 1,
                    'label' => $this->l('Enabled')
                  ),
                  array(
                    'id' => 'name_off',
                    'value' => 0,
                    'label' => $this->l('Disabled')
                  )
                )
            ),
            array(
                'type' => 'radio',
                'label' => $this->l('Name field required'),
                'name' => 'SENDYNEWSLETTER_NAMEREQ',
                'desc' => $this->l('If checked subscribers name will be required.'),
                'is_bool' => true,
                'class' => 't',
                'values' => array(
                  array(
                    'id' => 'namereq_on',
                    'value' => 1,
                    'label' => $this->l('Enabled')
                  ),
                  array(
                    'id' => 'namereq_off',
                    'value' => 0,
                    'label' => $this->l('Disabled')
                  )
                )
            )
        ),
        'submit' => array(
            'title' => $this->l('Save'),
            'class' => 'button'
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
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
            '&token='.$helper->token,
        ),
        'back' => array(
            'href' => AdminController::$currentIndex.'&token='.$helper->token,
            'desc' => $this->l('Back to list')
        )
    );
     
    // Load current value
    $helper->fields_value = array(
      	'SENDYNEWSLETTER_INSTALLATION' 	=> Configuration::get('SENDYNEWSLETTER_INSTALLATION'),
		'SENDYNEWSLETTER_LIST' 			=> Configuration::get('SENDYNEWSLETTER_LIST'),
		'SENDYNEWSLETTER_IP' 			=> Configuration::get('SENDYNEWSLETTER_IP'),
		'SENDYNEWSLETTER_IPVALUE' 		=> Configuration::get('SENDYNEWSLETTER_IPVALUE'),
		'SENDYNEWSLETTER_NAME' 			=> Configuration::get('SENDYNEWSLETTER_NAME'),
		'SENDYNEWSLETTER_NAMEREQ' 		=> Configuration::get('SENDYNEWSLETTER_NAMEREQ')
      );
     
    return $helper->generateForm($fields_form);
  }
}
