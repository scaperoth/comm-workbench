<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ApiHelper_Gadgets extends CHtml {
// Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */

    Const APPLICATION_ID = 'ASCCPE';

    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';

    const LOGIN_ERROR = "You have insufficient permissions to continue";

    

}