<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Content extends Api_Controller {
    function __construct() {
        parent::__construct('Content');
        
    }

    public $rules = array(
        
    );

    function index(){
        parent::run();
    }

    
}
