<?php

/**
 * @package Widgets
 *
 * @file AdminDB.php
 * This file is part of Lygneo.
 *
 * @brief The DB Administration widget
 *
 * @author Jaussoin Timothée <edhelas@lygneo.com>

 * Copyright (C)2014 Lygneo project
 *
 * See COPYING for licensing information.
 */
 
class AdminDB extends WidgetBase
{
    function load() {

    }

    public function ajaxUpdateDatabase()
    {
        $md = \modl\Modl::getInstance();
        $md->check(true);
        RPC::call('lygneo_reload_this');
    }

    function display()
    {
        $md = \modl\Modl::getInstance();
        $infos = $md->check();
        
        $errors = '';

        $this->view->assign('infos', $infos); 
        $this->view->assign('db_update', $this->genCallAjax('ajaxUpdateDatabase')
            ."this.className='button color orange icon loading'; setTimeout(function() {location.reload(false)}, 1000);");
        try {
            $md->connect();
        } catch(Exception $e) {
            $errors = $e->getMessage();
        }

        if(file_exists(DOCUMENT_ROOT.'/config/db.inc.php')) {
            require DOCUMENT_ROOT.'/config/db.inc.php';
        }

        $supported = $md->getSupportedDatabases();
        
        $this->view->assign('connected', $md->_connected);
        $this->view->assign('conf', $conf);
        $this->view->assign('dbtype', $supported[$conf['type']]);
        $this->view->assign('errors', $errors);
    }
}
