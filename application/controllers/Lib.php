<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lib extends CI_Controller {
    function __construct() {
        parent::__construct();
        if (REQUEST == "external") {
            return;
        } 
    }
    public function index()
    {
        if (REQUEST == "external") {
            return;
        } 
    }
    public function kcfinder($a=''){
        include APPPATH . '../lib/kcfinder/browse.php';
    }
}

