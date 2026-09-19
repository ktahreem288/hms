<?php
require_once("../../Includes/config.php"); 
require_once("../../Includes/session.php"); 
if ($logged == false) {
    header("Location:../../login.php");
    exit;
}
ini_set('display_errors', 1);

Class Action {
    private $db;

    public function __construct() {
        ob_start();
        global $con;
        $this->db = $con;
    }

    function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
        ob_end_flush();
    }

    function save_user(){
        extract($_POST);
        
        $id = isset($id) ? (int)$id : 0;
        $name = $this->db->real_escape_string($name ?? '');
        $username = $this->db->real_escape_string($username ?? '');
        $email = $this->db->real_escape_string($email ?? '');
        $flat_id = $this->db->real_escape_string($flat_id ?? '');
        $phone = $this->db->real_escape_string($phone ?? '');
        $current_accommodation = $this->db->real_escape_string($current_accommodation ?? 'Owner');
        $tenant_name = $this->db->real_escape_string($tenant_name ?? '');
        $tenant_contact = $this->db->real_escape_string($tenant_contact ?? '');
        $tenant_start_date = $this->db->real_escape_string($tenant_start_date ?? '');
        $tenant_end_date = $this->db->real_escape_string($tenant_end_date ?? '');
        $owner_start_date = $this->db->real_escape_string($owner_start_date ?? '');
        $parking_allotted = $this->db->real_escape_string($parking_allotted ?? 'NO');
        $currentUser = 'Admin';
        $member_role = isset($member_role) ? (int)$member_role : 0;
        $is_active = isset($is_active) && (int)$is_active === 1 ? 1 : 0;

        // Construct complete data string based on database schema
        $data = " name = '$name' ";
        $data .= ", username = '$username' ";
        $data .= ", email = '$email' ";
        $data .= ", flat_id = '$flat_id' ";
        $data .= ", phone = '$phone' ";
        $data .= ", member_role = '$member_role' ";
        $data .= ", is_active = '$is_active' ";
        $data .= ", current_accommodation = '$current_accommodation' ";
        $data .= ", tenant_name = '$tenant_name' ";
        $data .= ", tenant_contact = '$tenant_contact' ";
        $data .= ", tenant_start_date = " . ($tenant_start_date !== '' ? "'$tenant_start_date'" : "NULL");
        $data .= ", tenant_end_date = " . ($tenant_end_date !== '' ? "'$tenant_end_date'" : "NULL");
        $data .= ", owner_start_date = " . ($owner_start_date !== '' ? "'$owner_start_date'" : "NULL");
        $data .= ", parking_allotted = '$parking_allotted' ";

        // Append password only if provided
        if(!empty($password)){
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $data .= ", password = '$hashed_password' ";
        }

        // Check for duplicate email
        $chk = $this->db->query("SELECT * FROM member WHERE email = '$email' AND id != '$id'")->num_rows;
        if($chk > 0){
            return 2;
        }

        $usernameCheck = $this->db->query("SELECT * FROM member WHERE username = '$username' AND id != '$id'")->num_rows;
        if($usernameCheck > 0){
            return 2;
        }

        if(empty($id)){
            // Insert new record with current timestamp matching phpMyAdmin structure
            $data .= ", created_at = NOW(), added_by = '$currentUser' ";
            $save = $this->db->query("INSERT INTO member SET ".$data);
        } else {
            // Update existing record
            $data .= ", updated_on = NOW(), updated_by = '$currentUser' ";
            $save = $this->db->query("UPDATE member SET ".$data." WHERE id = ".$id);
        }

        if($save){
            return 1;
        }
        return 0;
    }

    function delete_user(){
        extract($_POST);
        $id = (int)$id;
        $delete = $this->db->query("UPDATE member SET is_active = 0 WHERE id = ".$id);
        
        if($delete)
            return 1;
        return 0;
    }                                         
}