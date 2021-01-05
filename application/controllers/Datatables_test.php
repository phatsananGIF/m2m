<?php
class Datatables_test extends CI_Controller {

    function __construct() {      
        parent::__construct();
    
    }
    
    public function index(){


        $this->load->view('datatables_test_view');
 
   

    }//end f.index

}