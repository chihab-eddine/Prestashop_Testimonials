<?php

/**
 * 2007-2021 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2021 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once("classes/testimonialClass.php");

class Testimonials extends Module
{

    public function __construct()
    {
        $this->name = 'testimonials';
        $this->tab = 'front_office_features';
        $this->version = '1.0.2';
        $this->author = 'Chihab-eddine Etthamry';
        $this->bootstrap = true;
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->trans('testimonials', [], 'Modules.Testimonials.Testimonials');

        $this->description = $this->trans('Testimonials', [], 'Modules.Testimonials.Testimonials');

        $this->confirmUninstall = $this->trans('Are you Sure ?', [], 'Modules.Testimonials.Testimonials');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }



    public function createTabs()
    {
        $idParent = (int) Tab::getIdFromClassName('AdminTestimonials');
        if (empty($idParent)) {
            $parent_tab = new Tab();
            $parent_tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                $parent_tab->name[$lang['id_lang']] = $this->trans('Testimonials Module', [], 'Modules.Testimonials.Testimonials');
            }
            $parent_tab->class_name = 'AdminTestimonials';
            $parent_tab->id_parent = 0;
            $parent_tab->module = $this->name;
            $parent_tab->icon = 'library_books';
            $parent_tab->add();
        }

        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Testimonial', [], 'Modules.Testimonials.Testimonials');
        }
        $tab->class_name = 'AdminTestimonial';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminTestimonials');
        $tab->module = $this->name;
        $tab->icon = 'library_books';
        $tab->add();

        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Testimonial', [], 'Modules.Testimonials.Testimonials');
        }
        $tab->class_name = 'AdminTestimonial';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminTestimonial');
        $tab->module = $this->name;
        $tab->add();

        return true;
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install() &&
            $this->createTabs() &&
            $this->registerHook('displayCustomerAccount') &&
            $this->registerHook('displayHome') &&
            $this->registerHook('Header');
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }
    public function uninstall()
    {

        include(dirname(__FILE__) . '/sql/uninstall.php');
        return parent::uninstall();
    }



    public function hookDisplayCustomerAccount()
    {

        $this->context->smarty->assign([

            'testomonial_url' => Context::getContext()->link->getModuleLink($this->name, 'view', [], true)
        ]);
        return $this->display(__FILE__, 'views/templates/hook/testimonial_costumer_account.tpl');
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path . '/views/css/testimonials.css');
        $this->context->controller->addJS($this->_path . '/views/js/app.js');
    }
    public function hookDisplayHome()
    {

        $datas = TestimonialClass::getTestimonialByStatus();
        $this->context->smarty->assign([

            'datas' => $datas,
            'testomonial_url' => Context::getContext()->link->getModuleLink($this->name, 'view', [], true),

        ]);
        return $this->display(__FILE__, 'views/templates/hook/display_home_testimonials.tpl');
    }
}
