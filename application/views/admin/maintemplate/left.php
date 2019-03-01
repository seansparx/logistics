<?php
    $current_url = $this->uri->segment(1).'/'.$this->uri->segment(2);
    $current = $this->uri->segment(1);
?>

<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-closed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>

            <?php
                $left_menu = left_menu();
                
                if(is_array($left_menu['main_menu'])){
                    
                    foreach($left_menu['main_menu'] as $menu) {

                        ?>
                        <li class="nav-item start <?php if(str_replace(site_url(), "", current_url()) == $menu->route_url){ ?>active open<?php }?>">
                            <a href="<?php echo (trim($menu->route_url) != '#') ? site_url($menu->route_url) : 'javascript:void(0);'; ?>" class="nav-link nav-toggle">
                                <i class="<?php echo $menu->icon; ?>"></i>
                                <span class="title"><?php echo ucfirst($menu->display_name); ?></span>
                                <span class="selected"></span>
                                <span class="arrow open"></span>
                            </a>
                            <?php 
                                if(isset($left_menu['sub_menu'][$menu->id]) && count($left_menu['sub_menu'][$menu->id] > 0)) {
                                    ?>
                                    <ul class="sub-menu">
                                        <?php 
                                            foreach($left_menu['sub_menu'][$menu->id] as $sub_menu) {
                                                $url = explode("/", $sub_menu->route_url);
                                                ?>
                                                    <li class="nav-item start <?php if($current_url == $url[0].'/'.$url[1]){ ?>active open<?php }?>">
                                                        <a href="<?php echo (trim($sub_menu->route_url) != '#') ? site_url($sub_menu->route_url) : 'javascript:void(0);'; ?>" class="nav-link">
                                                            <i class="<?php echo $sub_menu->icon; ?>"></i>
                                                            <span class="title"><?php echo ucfirst($sub_menu->display_name); ?></span>
                                                            <span class="selected"></span>
                                                        </a>
                                                    </li>
                                                <?php
                                            }
                                        ?>
                                    </ul>    
                                    <?php
                                }
                            ?>
                        </li>
                        <?php
                    }
                }
            ?>
        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR -->
