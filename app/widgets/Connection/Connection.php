<?php

/**
 * @package Widgets
 *
 * @file Connection.php
 * This file is part of Lygneo.
 * 
 * @version 1.0

 * Copyright (C)2013 Lygneo project
 * 
 * See COPYING for licensing information.
 */

class Connection extends WidgetBase
{
    function load()
    {
        $this->addcss('connection.css');
        $this->registerEvent('connection', 'onConnection');
    }
    
    function ajaxSetPresence()
    {

    }
    
    function onConnection($value)
    {
        if($value >= 10) {
            $value = floor(($value-10)/10);

            if($value == 0)
                RPC::call('lygneo_fill', 'countdown', '');
            else {
                RPC::call('lygneo_fill', 'countdown', $this->__('please_wait') . ' ' . $value);
            }
        } else
            RPC::commit();
    }
}

?>
