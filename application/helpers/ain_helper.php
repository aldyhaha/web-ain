<?php

function is_logged_in()
{

    $ci = get_instance();
    if (!$ci->session->userdata('email')) {
        $ci->session->set_flashdata('message', '<div class="alert alert-danger
        " role="alert">Harus Login Terlebuh Dahulu</div>');
        redirect('auth');
    } else {
        $role_id = $ci->session->userdata('role_id');
        $menu = $ci->uri->segment(1);

        $queryMenu = $ci->db->get_where('users_menu', ['menu' => $menu])->row_array();
        $menu_id = $queryMenu['id'];

        $usersAccess = $ci->db->get_where('users_access_menu', ['role_id' => $role_id, 'menu_id' => $menu_id]);
        if ($usersAccess->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}


function check_access($role_id, $menu_id)
{
    $ci = get_instance();

    $ci->db->where('role_id', $role_id);
    $ci->db->where('menu_id', $menu_id);
    $result = $ci->db->get('users_access_menu');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}