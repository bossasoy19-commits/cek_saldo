<?php


use App\Models\ModuleModel;
use App\Models\UserPrivModel;
use CodeIgniter\HTTP\SiteURI;

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
                if ($filtered_menu) {
                    $html .= '<li class="nav-item ">
                         <a data-bs-toggle="collapse" href="#module' . $idx . '">
                             <i class="' . $module[1] . '"></i>
                             <p>' . $module[0] . '</p>
                             <span class="caret"></span>
                         </a>
                         <div class="collapse" id="module' . $idx . '">
                             <ul class="nav nav-collapse">';
                    foreach ($filtered_menu as $menu) {
                        $html .= '
                                 <li>
                                     <a href="' . base_url($menu['url']) . '">
                                         <span class="sub-item">' . $menu['menu'] . $segment1  . '</span>
                                     </a>
                                 </li>
                             ';
                    }


                    $html .= '</ul> </div> </li>';
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
