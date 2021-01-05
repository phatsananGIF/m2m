<?php
class Devices_input extends CI_Controller {

    public function __construct(){
        parent::__construct();
    }

	public function index(){

        if($this->input->get() != null){
            $serial = $this->input->get("serial");
            $coin = $this->input->get("coin");

            $data_input = array(
                'serial' => $serial,
                'coin' => $coin,
                'updated' => date('Y-m-d H:i:s')
            );

            $this->db->insert('devices_input', $data_input);

        }

        $query = ("SELECT * FROM devices_input ORDER BY `updated` DESC ");
        $Result = $this->db->query($query);
        $result_devices_input = $Result->result_array();

        $data['result_devices_input'] = $result_devices_input;

        $this->load->view('Devices_input/devices_input_view',$data);
        

    }//end f.index


    public function add($serial = '', $coin = '', $timestam = '', $status = '', $temp =''){

        if($serial != '' && $coin != '' && $timestam != '' && $status != '' && $temp != ''){

            $datestam =  date("Y-m-d H:i:s", $timestam);
            
            $data_input = array(
                'serial' => $serial,
                'coin' => $coin,
                'status' => $status,
                'temp' => $temp,
                'num_timestam' => $timestam,
                'updated' => $datestam,
                'server_updated' => date("Y-m-d H:i:s")
            );

            $this->db->insert('devices_input', $data_input);
            
            echo"ok";
            
        }else{
            echo"data is null";
        }

    }//end f.add


    public function view_data_input($serial = '', $get_start_date = '', $get_end_date = ''){

        if($serial != '' && $get_start_date != '' && $get_end_date != ''){

            $start_date = $get_start_date;
            $end_date = $get_end_date;

            $start_date_query = str_replace("T"," ",$start_date).":00";
            $end_date_query = str_replace("T"," ",$end_date).":59";

            $query = ("SELECT * FROM devices_input WHERE serial = '$serial' 
                        AND (updated BETWEEN '$start_date_query' AND '$end_date_query')
                        ORDER BY `updated` DESC ");
            $Result = $this->db->query($query);
            $result_devices_input = $Result->result_array();


        }else{

            $query = ("SELECT * FROM devices_input ORDER BY `updated` DESC ");
            $Result = $this->db->query($query);
            $result_devices_input = $Result->result_array();

        }

        $data['result_devices_input'] = $result_devices_input;

        $this->load->view('Devices_input/devices_input_view',$data);


    }//end f.view_data_input



    public function view_count_data_input($serial = '', $get_start_date = '', $get_end_date = ''){

        $d = strtotime("-1 day");
        $start_date = date("Y-m-d\TH:i", $d);
        $end_date = date("Y-m-d\TH:i");
        $start_date_query = date("Y-m-d H:i", $d).":00";
        $end_date_query = date("Y-m-d H:i").":59";

        if($this->input->post("btsearch")!=null){
            
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');

            $start_date_query = str_replace("T"," ",$start_date).":00";
            $end_date_query = str_replace("T"," ",$end_date).":59";

            if($this->input->post("btsearch")=='Delete'){
                echo 'Delete';

                $querydelete = (" DELETE FROM devices_input WHERE (updated BETWEEN '$start_date_query' AND '$end_date_query') ");
                $this->db->query($querydelete);

            }

        }elseif($serial != '' && $get_start_date != '' && $get_end_date != ''){

            $start_date = $get_start_date;
            $end_date = $get_end_date;

            $start_date_query = str_replace("T"," ",$start_date).":00";
            $end_date_query = str_replace("T"," ",$end_date).":59";

            $querydelete = (" DELETE FROM devices_input WHERE serial ='$serial' AND (updated BETWEEN '$start_date_query' AND '$end_date_query') ");
            $this->db->query($querydelete);


        }

        $query = ("SELECT * FROM devices_input WHERE (updated BETWEEN '$start_date_query' AND '$end_date_query') ");

        $devices_input = $this->db->query($query);
        $devices_input = $devices_input->result_array();

        $device_data =[];
        
        foreach($devices_input as $device){

            $device_data[ $device['serial'] ]['serial'] =  $device['serial'];

            if(!isset($device_data[ $device['serial'] ]['coin']))$device_data[ $device['serial'] ]['coin']=0;
            $device_data[ $device['serial'] ]['coin'] += $device['coin'];

            if(!isset($device_data[ $device['serial'] ]['updated']))$device_data[ $device['serial'] ]['updated']='0000-00-00 00:00:00';
            if ($device['updated'] > $device_data[ $device['serial'] ]['updated']){
                $device_data[ $device['serial'] ]['status'] =  $device['status'];
                $device_data[ $device['serial'] ]['temp'] =  $device['temp'];
                $device_data[ $device['serial'] ]['num_timestam'] =  $device['num_timestam'];
                $device_data[ $device['serial'] ]['updated'] =  $device['updated'];
                $device_data[ $device['serial'] ]['server_updated'] =  $device['server_updated'];
            }

        }
        
        
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['device_data'] = $device_data;


        $this->load->view('Devices_input/count_input_view',$data);


    }//end f.view_count_data_input

    
    
    
}
