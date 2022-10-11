 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

     <!-- Sidebar - Brand -->
     <a class="sidebar-brand d-flex align-items-center justify-content-center">
         <div class="sidebar-brand-icon">
             <img src="assets/img/smk.png" width="55px">
         </div>
         <div class="sidebar-brand-text mx-3">AIN</div>
     </a>

     <!-- Divider -->
     <hr class="sidebar-divider">

     <!-- Query Menu-->
     <?php
        $role_id = $this->session->userdata('role_id');
        $queryMenu = "SELECT `users_menu`.`id`, `menu`
                     FROM `users_menu` JOIN `users_access_menu`
                       ON `users_menu`.`id` = `users_access_menu`.`menu_id`
                    WHERE `users_access_menu`.`role_id` = $role_id
                 ORDER BY `users_access_menu`.`menu_id` ASC 
";
        $menu = $this->db->query($queryMenu)->result_array();
        ?>

     <!-- LOOPING MENU -->
     <?php foreach ($menu as $m) : ?>
         <div class="sidebar-heading">
             <?= $m['menu']; ?>
         </div>

         <!-- SIAPKAN SUB MENU SESUAI MENU -->
         <?php
            $menuId = $m['id'];
            $querySubMenu = "SELECT *
                       FROM `users_sub_menu` JOIN `users_menu`
                         ON `users_sub_menu`.`menu_id` = `users_menu`.`id`
                      WHERE `users_sub_menu`.`menu_id` = $menuId
                        AND `users_sub_menu`.`is_active` = 1 ";

            $subMenu = $this->db->query($querySubMenu)->result_array();
            ?>

         <?php foreach ($subMenu as $sm) : ?>
             <?php if ($judul == $sm['title']) : ?>
                 <li class="nav-item active">
                 <?php else : ?>
                 <li class="nav-item">
                 <?php endif; ?>
                 <a class="nav-link pb-0" href="<?= base_url($sm['url']); ?>">
                     <i class="<?= $sm['icon']; ?>"></i>
                     <span><?= $sm['title']; ?></span></a>
                 </li>




             <?php endforeach; ?>
             <hr class="sidebar-divider mt-3">
         <?php endforeach; ?>


         <li class="nav-item">
             <a class="nav-link" href="<?= base_url('auth/logout'); ?>">
                 <i class="fas fa-fw fa-sign-out-alt"></i>
                 <span>Logout</span></a>
         </li>




 </ul>
 <!-- End of Sidebar -->