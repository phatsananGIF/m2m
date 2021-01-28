<?php
class List_Devices extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){
        if($this->input->post("user_id")){
            
            $user_id = $this->input->post("user_id");
            //$user_id = 2;

            $query = (" SELECT devices.* , sites.site_name, SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) as coin_count,
                        devices.value*SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) as coin_value,

                        if(devices.value*SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) > devices.maximum,1,0) AS coin_over_max,

                        if( ROUND( ( devices.value*SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) ) / devices.maximum ,2)  >1, 1, 
                        ROUND( ( devices.value*SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) ) / devices.maximum ,2))  as percent_progress,

                        (SELECT devices_input.status  FROM `devices_input` WHERE `devices_input`.`serial` =  `devices`.`serial`
                        ORDER by devices_input.updated DESC LIMIT 1) as status,

                        (SELECT devices_input.updated FROM `devices_input` WHERE `devices_input`.`serial` =  `devices`.`serial`
                        ORDER by devices_input.updated DESC LIMIT 1) as date_updated

                        FROM `devices` 
                        LEFT JOIN `devices_input` ON (`devices_input`.`serial` = `devices`.`serial`)
                        LEFT JOIN `sites` ON (`sites`.`id` = `devices`.`site_id`)
                        LEFT JOIN `user_sites` ON (`user_sites`.`site_id` = `sites`.`id`)
                        WHERE user_sites.user_id = '$user_id' AND devices.deleted is null Group by devices.serial
                    ");

            $queryResult = $this->db->query($query); 
            $devicesResult = $queryResult->result_array();

            $data = [];
            $data['status'] = "Error";
            if(count($devicesResult)!=0){

                $data['status'] = "Sucsess";
                $filling_total_coin = [];
                foreach($devicesResult as $key => $value){
                    if($value['coin_count'] == null)$devicesResult[$key]['coin_count'] = "0";
                    if($value['coin_value'] == null)$devicesResult[$key]['coin_value'] = "0";
                    if($value['percent_progress'] == null)$devicesResult[$key]['percent_progress'] = "0.0";
                    if($value['status'] == null)$devicesResult[$key]['status'] = "0";
                    if($value['date_updated'] == null)$devicesResult[$key]['date_updated'] = "0000-00-00 00:00:00";

                    $filling_total_coin[$key] = $value['coin_value'];
                    
                }

                arsort($filling_total_coin);

                foreach($filling_total_coin as $key => $value){
                    
                    $data['devices'][] = $devicesResult[$key];

                }

                
            }

            echo json_encode($data);
            
        /*
            echo '<pre>';
            print_r($data);
            echo  '</pre>';
        */

        }

        
    
    }//end f.index

}//class        