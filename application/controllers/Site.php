<?php
class Site extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){ 
        if($this->input->post("user_id")){
            
            $user_id = $this->input->post("user_id");
            //$user_id = 3;

            $query = (" SELECT sites.* FROM `user_sites`
                        LEFT JOIN `sites` ON (`user_sites`.`site_id` = `sites`.`id`)
                        WHERE `user_id` = '$user_id' AND sites.deleted is null
                    ");

            $queryResult = $this->db->query($query);
            $Result_sites = $queryResult->result_array();


            $data = [];
            $data['status'] = "Error";
            if(count($Result_sites)!=0){

                $data['status'] = "Sucsess";
                $filling_total_coin = [];
                foreach($Result_sites as $key => $value){

                    $query = (" SELECT devices.* ,
                                SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) as coin_count,
                                devices.value*SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) as coin_value, 
                                if(devices.value*SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) > devices.maximum,1,0) AS coin_over_max
                                FROM `devices` LEFT JOIN `devices_input` ON (`devices_input`.`serial` = `devices`.`serial`)
                                WHERE devices.site_id = '".$value['id']."' AND devices.deleted is null Group by devices.serial
                            ");

                    $queryResult = $this->db->query($query);
                    $Result_devices = $queryResult->result_array();


                    $arr_counting['sum_coin']=0;
                    $arr_counting['sum_maximum']=0;
                    foreach($Result_devices as $value_result){

                        $arr_counting['sum_coin'] += $value_result['coin_value'];
                        $arr_counting['sum_maximum'] += $value_result['maximum'];
                        
                    }
                    $filling_total_coin[$key] = $arr_counting['sum_coin'];

                    $Result_sites[$key]['total_coin'] = $arr_counting['sum_coin'];
                    $Result_sites[$key]['total_maximum'] = $arr_counting['sum_maximum'];


                }//foreach

                arsort($filling_total_coin);

                foreach($filling_total_coin as $key => $value){
                    
                    $data['sites'][] = $Result_sites[$key];

                }
                
                
            }
            
            echo json_encode($data);
            
            /*
            echo '<pre>';
            print_r($data);
            echo  '</pre>';
            */


        }//if-post

    }//end f.index

}//class