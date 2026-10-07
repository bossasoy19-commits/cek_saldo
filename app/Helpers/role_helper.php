<?php


use App\Models\ModuleModel;
use App\Models\UserPrivModel;

if (!function_exists('generate_menu')) {
    function generate_menu($groupID)
    {
        $uri = service('uri');
        $segment1 = $uri->getSegment(1);
        $html = '';
        $config = new \Config\Additional();

        $UserPriveModel = new UserPrivModel();
        $dt_menu = $UserPriveModel->select('m.menu,m.moduleGroup, m.url, tbl_userpriv.*')
            ->where('groupID', $groupID)
            ->where('allowed', '1')
            ->where('m.displayInSidebar', 'Y')
            ->join('tbl_module m', 'm.moduleID = tbl_userpriv.moduleID')
            ->orderBy('m.moduleGroup')
            ->findAll();

        // d($menu);
        $grupModul = $config->group_module;
        if ($grupModul) {
            foreach ($grupModul as $idx => $module) {
                $filtered_menu = array_filter($dt_menu, function ($item) use ($idx) {
                    return $item['moduleGroup'] == $idx;
                });
                $arr_group_menu = array_column($filtered_menu, 'moduleGroup', 'url');
                // dd($arr_group_menu);
                if ($filtered_menu) {
                    $html .= '<li class="nav-item ' . (isset($arr_group_menu[$segment1]) ? ($module[2] == $arr_group_menu[$segment1] ? 'menu-open' : '') : '') . '">
                     <a href="#" class="nav-link ' . (isset($arr_group_menu[$segment1]) ? ($module[2] == $arr_group_menu[$segment1] ? 'active' : '') : '') . '">
                         <i class="nav-icon ' . $module[1] . ' "></i>
                         <p>
                             ' . $module[0]  . '
                             <i class="nav-arrow bi bi-chevron-right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview" style="margin-left:10px">';
                    foreach ($filtered_menu as $menu) {
                        $menu_url = $menu['url'];
                        $html .= '<li class="nav-item">
                             <a href="' . base_url($menu_url) . '" class="nav-link ' . ($menu_url == $segment1 ? 'active' : '') . '">
                                 <i class="nav-icon bi bi-circle"></i>
                                 <p>' . $menu['menu'] . '</p>
                             </a>
                         </li>';
                    }


                    $html .= '</ul></li>';
                }
            }
        }



        return $html;
    }
}

if (!function_exists('action_access')) {
    function action_access($url = '', $groupID = '')
    {

        if (!trim($url) || !trim($groupID)) {
            return false;
        }
        $UserPriveModel = new UserPrivModel();
        $dt_menu = $UserPriveModel
            ->join('tbl_module m', 'm.moduleID = tbl_userpriv.moduleID')
            ->where('m.url', $url)
            ->where('tbl_userpriv.groupID', $groupID)
            ->where('tbl_userpriv.allowed', 1)
            ->findAll(1);

        if ($dt_menu) {
            return  $allow = $dt_menu[0]['allowed'];
        } else {
            return false;
        }
    }
}
