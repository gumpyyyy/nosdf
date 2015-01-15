<?php

/**
 * @package Widgets
 *
 * @file Statistics.php
 * This file is part of Lygneo.
 *
 * @brief The administration widget.
 *
 * @author Timothée Jaussoin <edhelas@gmail.com>
 * *
 * Copyright (C)2014 Lygneo project
 *
 * See COPYING for licensing information.
 */
 
class Api extends WidgetBase {
    function load()
    {
    }

    function display()
    {
        $this->view->assign(
            'infos',
            $this->__(
                'api.info',
                '<a href="http://api.lygneo.com/" target="_blank">',
                '</a>',
                '<a href="'.$this->route('pods').'">',
                '</a>'));
        
        $json = requestURL(LYGNEO_API.'status', 1, array('uri' => BASE_URI));
        $json = json_decode($json);

        $cd = new \Modl\ConfigDAO();
        $config = $cd->get();

        if(isset($json)) {
            $this->view->assign('json', $json);
            if($json->status == 200) {
                $this->view->assign('unregister', $this->genCallAjax('ajaxUnregister'));
                $this->view->assign('unregister_status', $config->unregister);
            } else {
                $config->unregister = false;
                $cd->set($config);
                $this->view->assign('register', $this->genCallAjax('ajaxRegister'));
            }
        } else {
            $this->view->assign('json', null);
        }
    }

    function ajaxRegister()
    {
        $rewrite = false;
        if(isset($_SERVER['HTTP_MOD_REWRITE']) && $_SERVER['HTTP_MOD_REWRITE']) {
            $rewrite = true;
        } 
        
        $json = requestURL(
            LYGNEO_API.'register',
            1,
            array(
                'uri' => BASE_URI,
                'rewrite' => $rewrite));
        $json = json_decode($json);

        if(isset($json) && $json->status == 200) {
            RPC::call('lygneo_reload_this');
            Notification::appendNotification(t('Configuration updated'));
        }
    }

    function ajaxUnregister()
    {
        $cd = new \Modl\ConfigDAO();
        $config = $cd->get();
        
        $config->unregister = !$config->unregister;
        $cd->set($config);

        RPC::call('lygneo_reload_this');
        RPC::commit();
    }
}
