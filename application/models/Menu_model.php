<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function getSubMenu()
    {
        $query = "SELECT `users_sub_menu`.*, `users_menu`.`menu`
        FROM `users_sub_menu` JOIN `users_menu`
        ON `users_sub_menu`.`menu_id` = `users_menu`.`id`
        ";
        return $this->db->query($query)->result_array();
    }
}
