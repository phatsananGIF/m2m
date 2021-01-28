<?php
class Clear_coin_from_device extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){
        if($this->input->post("device_id")){

            $device_id = $this->input->post("device_id");
            $user_id = $this->input->post("user_id");

            //$device_id = 1;

            $update = (" UPDATE `devices_input` 
                        Left Join devices on devices_input.serial = devices.serial
                        SET devices_input.clear_coin_updated = now(),
                        devices_input.user_id = $user_id
                        WHERE devices.id = '$device_id' " );

            if( $this->db->query($update) ){
                $data['message'] =  'Sucsess';
            }else{
                $data['message'] =  'error';
            }

            echo json_encode($data);
        }
    
    }//end f.index

}//class        