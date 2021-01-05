<?php
class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index(){

        if($this->input->post("username")){

            $username = $this->input->post("username");
            $password = $this->input->post("password");

                
            $query = (" SELECT * FROM `users` WHERE user_name = '$username' AND enable = 'Y' AND deleted is null "); 
            $Result = $this->db->query($query);
            $userResult = $Result->row_array();

            $data_user =[];
            if($Result->num_rows() == 0){
                $data_user['message'] = 'Not User';
                echo json_encode($data_user);
            }else{

                $query = (" SELECT * FROM `users` WHERE user_name = '$username' AND user_pass = PASSWORD('".$password."') AND enable = 'Y' AND deleted is null "); 
                $Result = $this->db->query($query);
                $userResult = $Result->row_array();

                if($Result->num_rows() == 0){
                    $data_user['message'] = 'Wrong Password';
                    echo json_encode($data_user);
                }else{
                
                    $row['id']=$userResult['id'];
                    $row['level']=$userResult['level'];
                    $row['user_name']=$userResult['user_name'];
                    $row['first_name']=$userResult['first_name'];
                    $row['last_name']=$userResult['last_name'];
                    $row['email']=$userResult['email'];
                        
                    $update = (" UPDATE users SET last_login = now() WHERE id = ".$row['id']);
                    $this->db->query($update);

                    $data_user['message'] = 'Sucsess';
                    $data_user['data'] = $row;

                    echo json_encode($data_user);
                    
                }
            }


        }//if-post

    }//end f.index


    public function Test_GetUser(){
        $username = 'demo';
        $password = 'demo';

        $query = (" SELECT * FROM `users` WHERE user_name = '$username' AND enable = 'Y' AND deleted is null "); 
        $Result = $this->db->query($query);
        $userResult = $Result->row_array();

        $data_user =[];
        if($Result->num_rows() == 0){
            $data_user['message'] = 'Not User';
            echo json_encode($data_user);
        }else{

            $query = (" SELECT * FROM `users` WHERE user_name = '$username' AND user_pass = PASSWORD('".$password."') AND enable = 'Y' AND deleted is null "); 
            $Result = $this->db->query($query);
            $userResult = $Result->row_array();

            if($Result->num_rows() == 0){
                $data_user['message'] = 'Wrong Password';
                echo json_encode($data_user);
            }else{
            
                $row['id']=$userResult['id'];
                $row['level']=$userResult['level'];
                $row['user_name']=$userResult['user_name'];
                $row['first_name']=$userResult['first_name'];
                $row['last_name']=$userResult['last_name'];
                $row['email']=$userResult['email'];
                    
                $update = (" UPDATE users SET last_login = now() WHERE id = ".$row['id']);
                $this->db->query($update);

                $data_user['message'] = 'Sucsess';
                $data_user['data'] = $row;

                echo json_encode($data_user);
                
            }
        }

    }//end f.Test_GetUser

}//class