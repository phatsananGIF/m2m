<?php
class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){ 
        if($this->input->post("user_id")){
            
            $user_id = $this->input->post("user_id");
            //$user_id = 3;

            $query = (" SELECT sites.* FROM `user_sites`
                        LEFT JOIN `sites` ON (`user_sites`.`site_id` = `sites`.`id`)
                        WHERE `user_id` = '$user_id' 
                    ");

            $queryResult = $this->db->query($query);
            $Result_sites = $queryResult->result_array();


            $data = [];
            $data['status'] = "Error";
            if(count($Result_sites)!=0){
                
                $data['status'] = "Sucsess";
                $total_amounts = 0;
                $total_devices = 0 ;
                $devices_full = 0 ;
                $devices_have_coin = 0 ;
                $devices_no_coin = 0 ;
                $filling_total_coin = [];
                foreach($Result_sites as $key => $value){

                    $query = (" SELECT devices.* ,
                                SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) as coin_count,
                                devices.value*SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) as coin_value, 
                                if(devices.value*SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) > devices.maximum,1,0) AS coin_over_max
                                FROM `devices` LEFT JOIN `devices_input` ON (`devices_input`.`serial` = `devices`.`serial`)
                                WHERE devices.site_id = '".$value['id']."' Group by devices.serial
                            ");

                    $queryResult = $this->db->query($query);
                    $Result_devices = $queryResult->result_array();

                    $total_devices += count($Result_devices);


                    $arr_counting['sum_coin']=0;
                    foreach($Result_devices as $value_result){

                        $arr_counting['sum_coin'] += $value_result['coin_value'];
                        $devices_full += $value_result['coin_over_max'];

                        if($value_result['coin_count'] == 0 ){
                            $devices_no_coin++;
                        }
                        
                    }
                    $filling_total_coin[$key] = $arr_counting['sum_coin'];
                    $Result_sites[$key]['total_coin'] = $arr_counting['sum_coin'];
                    $total_amounts += $arr_counting['sum_coin'];


                }//foreach
                
                arsort($filling_total_coin);

                
                foreach($filling_total_coin as $key => $value){
                    
                    $data['sites'][] = $Result_sites[$key];

                }

                $devices_have_coin = $total_devices - $devices_full - $devices_no_coin;

                
                $query = (" SELECT server_updated FROM `devices_input` ORDER BY `server_updated` DESC LIMIT 1 ");
                $server_updated = $this->db->query($query);
                $Result_server_updated = $server_updated->row_array();

                $data['total_amounts'] = $total_amounts;
                $data['total_devices'] = $total_devices;
                $data['devices_full'] = $devices_full;
                $data['devices_have_coin'] = $devices_have_coin;
                $data['devices_no_coin'] = $devices_no_coin;
                $data['server_updated'] = $Result_server_updated['server_updated'];

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