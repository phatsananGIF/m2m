<?php
class Chart extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){
        
        if($this->input->post("user_id")){
            
            $user_id = $this->input->post("user_id");
            $tabSelect = $this->input->post("tabSelect");
            
            //$user_id = 3;
            //$tabSelect = "Days";

            $query_sites = (" SELECT sites.* FROM `user_sites`
                        LEFT JOIN `sites` ON (`user_sites`.`site_id` = `sites`.`id`)
                        WHERE `user_id` = '$user_id' AND sites.deleted is null
                    ");

            $queryResult = $this->db->query($query_sites);
            $Result_sites = $queryResult->result_array();

            $data = [];
            $data['status'] = "Error";
            if(count($Result_sites)!=0){
                $data['status'] = "Sucsess";

                foreach($Result_sites as $key => $value){

                    switch ($tabSelect) {
                        case "Days":
                            $Result_sites[$key]['maxy'] = 500.0;
                            $Result_sites[$key]['interval'] = 100.0;

                            $query = (" SELECT  devices_input.serial, devices.site_id, devices_input.updated, devices_input.server_updated,
                                        SUM( devices_input.coin ) as coin_count, SUM( devices.value* devices_input.coin ) as coin_value,
                                        
                                        date(devices_input.updated) as dt 

                                        FROM devices_input
                                        LEFT JOIN devices ON (devices.serial = devices_input.serial)

                                        WHERE devices.site_id = '".$value['id']."'
                                        AND devices_input.updated between  date(now() - INTERVAL 6 DAY) AND   date(now())

                                        GROUP  BY date(devices_input.updated)

                                        ORDER BY updated asc 
                                    ");
                            break;

                        case "Weeks":
                            $Result_sites[$key]['maxy'] = 5000.0;
                            $Result_sites[$key]['interval'] = 1000.0;

                            $query = (" SELECT  devices_input.serial, devices.site_id, devices_input.updated, devices_input.server_updated,
                                        SUM( devices_input.coin ) as coin_count, SUM( devices.value* devices_input.coin ) as coin_value,
                                        
                                        week(devices_input.updated) as dt 
                                        
                                        FROM devices_input
                                        LEFT JOIN devices ON (devices.serial = devices_input.serial)
                                        
                                        WHERE devices.site_id = '".$value['id']."'
                                        AND week(devices_input.updated) between (week(now())-3) AND week(now())
                                        
                                        GROUP  BY week(devices_input.updated)
                                        
                                        ORDER BY updated asc
                                    ");
                            break;

                        case "Months":
                            $Result_sites[$key]['maxy'] = 10000.0;
                            $Result_sites[$key]['interval'] = 2000.0;

                            $query = ("SELECT  devices_input.serial, devices.site_id, devices_input.updated, devices_input.server_updated,
                                        SUM( devices_input.coin ) as coin_count, SUM( devices.value* devices_input.coin ) as coin_value,
                                        
                                        monthname(devices_input.updated) as dt 
                                        
                                        FROM devices_input
                                        LEFT JOIN devices ON (devices.serial = devices_input.serial)
                                        
                                        WHERE devices.site_id = '".$value['id']."'
                                        AND year(devices_input.updated) = year(now()) AND month(devices_input.updated) between (month(now())-3) AND month(now())
                                        
                                        GROUP  BY month(devices_input.updated)
                                        
                                        ORDER BY updated asc
                            
                                    ");
                            break;

                        default:
                            $Result_sites[$key]['maxy'] = 500.0;
                            $Result_sites[$key]['interval'] = 100.0;

                            $query = (" SELECT  devices_input.serial, devices.site_id, devices_input.updated, devices_input.server_updated,
                                        SUM( devices_input.coin ) as coin_count, SUM( devices.value* devices_input.coin ) as coin_value,
                                        
                                        date(devices_input.updated) as dt 

                                        FROM devices_input
                                        LEFT JOIN devices ON (devices.serial = devices_input.serial)

                                        WHERE devices.site_id = '".$value['id']."'
                                        AND devices_input.updated between  date(now() - INTERVAL 6 DAY) AND   date(now())

                                        GROUP  BY date(devices_input.updated)

                                        ORDER BY updated asc 
                                    ");
                    }


                    $query_devices_input = $this->db->query($query);
                    $Result_devices_input = $query_devices_input->result_array();

                    $Result_sites[$key]['data_Chart'] = $Result_devices_input;



                }

                $data['sites'] = $Result_sites;
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