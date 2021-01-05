<?php
class Clear_coin_from_site extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){
        if($this->input->post("site_id")){

            $site_id = $this->input->post("site_id");
            //$site_id = 1;

            $update = (" UPDATE `devices_input` 
                        Left Join devices on devices_input.serial = devices.serial
                        Left Join sites on devices.site_id = sites.id
                        SET devices_input.clear_coin_updated = now(),
                        devices_input.server_updated = now()
                        WHERE sites.id = '$site_id' " );

            if( $this->db->query($update) ){
                $data['message'] =  'Sucsess';
            }else{
                $data['message'] =  'error';
            }

            echo json_encode($data);
        }
    
    }//end f.index

}//class        