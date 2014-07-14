<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class HttpSession extends CHttpSession
{
    public function regenerateID($deleteOldSession = false)
    {
        if(session_id() === '') session_regenerate_id($deleteOldSession);
    }
}
?>
