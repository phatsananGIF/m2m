<?php
class Detail_store extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){
        if($this->input->post("site_id")){
            
            $site_id = $this->input->post("site_id");
            //$site_id = 1;

            $query = (" SELECT * FROM `sites` WHERE `id` = '$site_id' AND deleted is null");
            $Result = $this->db->query($query);
            $siteResult = $Result->row_array();

            

            $query = (" SELECT devices.* , SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) as coin_count,
                        devices.value*SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) as coin_value,

                        if( ROUND( ( devices.value*SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) ) / devices.maximum ,2)  >1, 1, 
                        ROUND( ( devices.value*SUM( if(devices_input.clear_coin_updated is null,devices_input.coin,0) ) ) / devices.maximum ,2))  as percent_progress,

                        (SELECT devices_input.status  FROM `devices_input` WHERE `devices_input`.`serial` =  `devices`.`serial`
                        ORDER by devices_input.updated DESC LIMIT 1) as status,

                        (SELECT devices_input.updated FROM `devices_input` WHERE `devices_input`.`serial` =  `devices`.`serial`
                        ORDER by devices_input.updated DESC LIMIT 1) as date_updated

                        FROM `devices` LEFT JOIN `devices_input` ON (`devices_input`.`serial` = `devices`.`serial`)
                        WHERE devices.site_id = '$site_id' Group by devices.serial
                    ");

            $queryResult = $this->db->query($query); 
            $devicesResult = $queryResult->result_array();

            $data = [];
            $data['status'] = "Error";
            if(count($devicesResult)!=0){

                $data['status'] = "Sucsess";
                $filling_total_coin = [];
                $total_coin = 0;
                foreach($devicesResult as $key => $value){
                    $filling_total_coin[$key] = $value['coin_value'];
                    $total_coin += $value['coin_value'];
                }

                arsort($filling_total_coin);

                foreach($filling_total_coin as $key => $value){
                    
                    $data['devices'][] = $devicesResult[$key];

                }

                $data['site'] = $siteResult;
                $data['site']['total_coin'] = $total_coin;
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