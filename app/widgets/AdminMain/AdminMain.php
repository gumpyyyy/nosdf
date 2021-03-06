<?php

/**
 * @package Widgets
 *
 * @file AdminMain.php
 * This file is part of Lygneo.
 *
 * @brief The main configuration on Lygneo
 *
 * @author Jaussoin Timothée <edhelas@lygneo.com>

 * Copyright (C)2014 Lygneo project
 *
 * See COPYING for licensing information.
 */
 
class AdminMain extends WidgetBase
{
    function load() {
        $this->addjs('admin.js');

        $form = $_POST;
        $cd = new \Modl\ConfigDAO();
        $config = $cd->get();

        if(isset($form)) {
            if(isset($form['password'])
            && $form['password'] != '' && $form['repassword'] != ''
            && $form['password'] == $form['repassword']) {
                $form['password'] = sha1($form['password']);
            } else {
                $form['password'] = $config->password;
            }

            unset($form['repassword']);
            foreach($form as $key => $value) {
                $config->$key = $value;
            }
            $cd->set($config);
            
            //set timezone
            if(isset($form['timezone'])) {
                date_default_timezone_set($form['timezone']);
            }
        }
    }

    public function testBosh($url)
    {
        return requestURL($url, 1);
    }

    public function date($timezone)
    {
        $t = new DateTimeZone($timezone);
        $c = new DateTime(null, $t);
        $current_time = $c->format('D M j Y G:i:s');
        return $current_time;
    }

    function display()
    {
        $cd = new \Modl\ConfigDAO();
        $config = $cd->get();
        
        $this->view->assign('conf', $cd->get());
        $this->view->assign('logs',
            array(
                0 => t('Empty'),
                1 => t('Syslog'),
                2 => t('Syslog and Files'))
        );
        $this->view->assign('envs',
            array(
                'development' => 'Development',
                'production'  => 'Production')
        );

        $this->view->assign('bosh_info4',
            $this->__('bosh.info4', '<a href="http://wiki.lygneo.com/en:install">', '</a>'));

        $json = requestURL(LYGNEO_API.'boshs', 1);
        $json = json_decode($json);

        if(isset($json)) {
            $this->view->assign('boshs', $json);
        }
        
        $this->view->assign('timezones', getTimezoneList());
        $this->view->assign('langs', loadLangArray());
    }
}
