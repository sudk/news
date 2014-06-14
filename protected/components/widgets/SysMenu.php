<?php

/*
 * @author Su DunKuai <sudk@trunkbow.com>
 * @version $Id: SysMenu.php 2597 $
 * @package application.components.widgets
 * @since 1.1.1
 */
class SysMenu extends CWidget {

    public function Menu() {

        $menus = array();

        $sub_menu = array();
        $sub_menu[] = array("title" => "新闻列表", "url" => "./?r=news/news/list", "match" => 'news\/news\/list');
        $sub_menu[] = array("title" => "添加新闻", "url" => "./?r=news/news/new", "match" => 'news\/news\/new');
        if (count($sub_menu))
            $menus['news'] = array("title" => "新闻", "url" => "./?r=news/news/list", "child" => $sub_menu);

        $sub_menu = array();
        $sub_menu[] = array("title" => "操作员列表", "url" => "./?r=operator/operator/list", "match" => 'operator\/operator\/list');
        $sub_menu[] = array("title" => "添加操作员", "url" => "./?r=operator/operator/new", "match" => 'operator\/operator\/new');
        if (count($sub_menu))
            $menus['operator'] = array("title" => "操作员管理", "url" => "./?r=operator/operator/list", "child" => $sub_menu);

        return $menus;
    }

    public function run() {
        return $this->show_system();
    }

    public function show_system() {
        $menus = self::Menu();
        $r = $_REQUEST['r'];
        ?>
        <div id="nav" class="nav">
            <div class="nav-cnt">
                <ul id="main_menu">
                    <?php
                    $i = 1;

                    if (preg_match('/^dboard/', $r))
                        $class = ' class="master current" ';
                    else
                        $class = ' class="master" ';
                    echo '<li id="m' . $i . '"' . $class . '>';
                    echo '<a class="name" href="index.php?r=dboard"><strong>首页</strong></a>';
                    echo '<ul class="subnav"><span style="color:#6a8f00; padding-right:0;">常用功能：</span>';
                    echo '<li><a href="./?r=news/news/new" ><span>添加新闻</span></a></li>';
                    echo '<li><a href="./?r=operator/operator/new" ><span>添加操作员</span></a></li>';
                    echo "</ul></li>";

                    self::showMenu($menus, $r, ++$i);
                    ?>
                </ul>
            </div>
        </div>
        <?php
    }

    public function showMenu($menus, $r, $i) {
        $html_str = "";
        if (count($menus) > 0) {
            foreach ($menus as $id => $menu) {
                $class = ' class="master" ';
                $current_sub = false;
                $sub_html_str = "";
                if (count($menu['child']) > 0) {
                    $sub_html_str = '<ul class="subnav">';
                    foreach ($menu['child'] as $sub_menu) {
                        if ($sub_menu['match'] != '' && self::menuMatch($sub_menu['match'], $r)) {
                            $sub_class = ' class="current" ';
                            $current_sub = true;
                        } else {
                            $sub_class = '';
                        }
                        if ($sub_menu['title'] != '')
                            $sub_html_str.= '<li ' . $sub_class . '><a href="' . $sub_menu['url'] . '"><span>' . $sub_menu['title'] . '</span></a></li>';
                    }
                    //$sub_html_str=substr_replace($sub_html_str,"",-33,28);
                    $sub_html_str.= '</ul>';
                }
                if ($current_sub) {
                    $class = ' class="master current" ';
                }
                $main_html_class = '<li id="m' . $i . '"' . $class . '><a class="name" href="' . $menu['child'][0]['url'] . '"><strong>' . $menu['title'] . '</strong></a>';

                $sub_html_str = $main_html_class . $sub_html_str . "</li>";

                $html_str.=$sub_html_str;
                $i++;
            }
        }
        echo $html_str;
    }

    public function menuMatch($match, $r) {
        if (!$match) {
            return false;
        }
        if (is_array($match)) {
            foreach ($match as $v) {
                if (preg_match('/\b' . $v . '\b/', $r)) {
                    return true;
                }
            }
        } else {
            return preg_match('/\b' . $match . '\b/', $r);
        }
    }

}
