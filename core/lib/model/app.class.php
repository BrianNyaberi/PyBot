<?php

/**

 */
?>
<?php

class app
{

    public static function public_path($v)
    {
        return sfContext::getInstance()->getRequest()->getRelativeUrlRoot() . '/' . $v;
    }

    public static function public_url($v)
    {
        return sfContext::getInstance()->getController()->genUrl($v, true);
    }

    public static function setPageTitle($t, $response)
    {
        return $response->setTitle(sfConfig::get('app_app_short_name') . ' | ' . t::__($t));
    }

    public static function setNotice($text, $sf_user, $type = 'info')
    {
        $sf_user->setFlash('userNotices', array('text' => t::__($text), 'type' => $type));
    }

    public static function strip_tags($v)
    {
        if (sfConfig::get('app_db_strip_tags') == 'on' and is_string($v))
        {
            return trim(strip_tags($v, sfConfig::get('app_db_allowed_tags')));
        }
        else
        {
            return $v;
        }
    }

    public static function truncate_text($text, $length = 64, $truncate_string = '...')
    {
        if (strlen($text) > $length)
        {
            $truncate_text = substr($text, 0, $length - strlen($truncate_string));
            $text = $truncate_text . $truncate_string;
        }

        return $text;
    }

    public static function getPriorityIconsChoices()
    {
        return array('' => t::__('None'),
            'prio_1.png' => 'prio_1.png',
            'prio_2.png' => 'prio_2.png',
            'prio_3.png' => 'prio_3.png',
            'prio_4.png' => 'prio_4.png',
            'prio_5.png' => 'prio_5.png');
    }

    public static function resetCfgDefaultValue($id, $t)
    {
        Doctrine_Query::create()
                ->update($t)
                ->set('default_value', 0)
                ->where('id != ?', $id)
                ->execute();
    }

    public static function getCfgItemIdByName($n, $t)
    {
        if ($item = Doctrine_Core::getTable($t)->createQuery()->addWhere('name=?', $n)->fetchOne())
        {
            return $item->getId();
        }
        else
        {
            return false;
        }
    }

    public static function getProjectCfgItemIdByName($n, $t, $projects_id)
    {
        if ($item = Doctrine_Core::getTable($t)->createQuery()->addWhere('name=?', $n)->addWhere('projects_id=?', $projects_id)->fetchOne())
        {
            return $item->getId();
        }
        else
        {
            return false;
        }
    }

    public static function getStatusGroupsChoices()
    {
        $l = array();
        $l['open'] = t::__('open');
        $l['done'] = t::__('done');
        $l['closed'] = t::__('closed');

        return $l;
    }

    public static function getSchemaByTable($t)
    {
        $itmes = Doctrine_Core::getTable($t)
                ->createQuery()
                ->fetchArray();

        $schema = array();

        foreach ($itmes as $i)
        {
            $schema[$i['name']] = $i['name'];
        }

        return $schema;
    }

    public static function getStatusByGroup($g, $t)
    {
        if (!is_array($g))
        {
            $g = array($g);
        }

        $l = Doctrine_Core::getTable($t)
                ->createQuery('t')
                ->whereIn('t.group', $g)
                ->orderBy('sort_order, name')
                ->fetchArray();

        $s = array();
        foreach ($l as $v)
        {
            $s[] = $v['id'];
        }

        return $s;
    }

    public static function countItemsByTable($t, $projects_id = false)
    {
        switch ($t)
        {
            case 'Versions':
            case 'ProjectsPhases':
            case 'TasksGroups':
                return Doctrine_Core::getTable($t)
                                ->createQuery()
                                ->addWhere('projects_id=?', $projects_id)
                                ->count();
                break;
            default:
                return Doctrine_Core::getTable($t)->count();
                break;
        }
    }

    public static function getDefaultValueByTable($t)
    {
        switch ($t)
        {
            case 'TicketsTypes':
            case 'ProjectsTypes':
                $v = Doctrine_Core::getTable($t)
                        ->createQuery()
                        ->orderBy('sort_order')
                        ->fetchOne();
                break;
            default:
                $v = Doctrine_Core::getTable($t)
                        ->createQuery()
                        ->addWhere('default_value=1')
                        ->fetchOne();
                break;
        }

        if ($v)
        {
            return $v->getId();
        }
        else
        {
            return false;
        }
    }

    public static function getSimpleItemsChoicesByTable($t, $add_empty = false)
    {
        $choices = array();

        if ($add_empty)
        {
            $choices[''] = '';
        }

        $l = Doctrine_Core::getTable($t)
                ->createQuery()
                ->orderBy('sort_order, name')
                ->fetchArray();

        foreach ($l as $v)
        {
            $choices[$v['id']] = $v['name'];
        }

        return $choices;
    }

    public static function getItemsChoicesByTable($t, $add_empty = false, $projects_id = false, $sf_user = false)
    {
        $choices = array();

        if ($add_empty)
        {
            $choices[''] = '';
        }

        switch ($t)
        {
            case 'Versions':
            case 'ProjectsPhases':
            case 'TasksGroups':
                $l = Doctrine_Core::getTable($t)
                        ->createQuery()
                        ->addWhere('projects_id=?', $projects_id)
                        ->orderBy('name')
                        ->fetchArray();

                foreach ($l as $v)
                {
                    $choices[$v['id']] = $v['name'];
                }
                break;
            case 'Users':
                $l = Doctrine_Core::getTable($t)
                        ->createQuery('u')
                        ->leftJoin('u.UsersGroups ug')
                        ->addWhere('u.active=1')
                        ->orderBy('u.users_group_id, u.name')
                        ->fetchArray();

                foreach ($l as $v)
                {
                    $choices[$v['UsersGroups']['name']][$v['id']] = $v['name'];
                }

                break;
            case 'UsersGroups':
                $l = Doctrine_Core::getTable($t)
                        ->createQuery()
                        ->orderBy('name')
                        ->fetchArray();

                foreach ($l as $v)
                {
                    $choices[$v['id']] = $v['name'];
                }
                break;
            case 'TasksStatus':
                $l = Doctrine_Core::getTable($t)
                        ->createQuery('t')
                        ->addWhere('active=1')
                        ->orderBy('t.group desc, sort_order, name')
                        ->fetchArray();

                foreach ($l as $v)
                {
                    $choices[t::__($v['group'])][$v['id']] = $v['name'];
                }
                break;

            default:
                $l = Doctrine_Core::getTable($t)
                        ->createQuery()
                        ->addWhere('active=1')
                        ->orderBy('sort_order, name')
                        ->fetchArray();

                foreach ($l as $v)
                {
                    $choices[$v['id']] = $v['name'];
                }
                break;
        }

        return $choices;
    }

    public static function getObjectName($o)
    {
        if (is_object($o))
        {
            return $o->getName();
        }
        else
        {
            return '';
        }
    }

    public static function getArrayName($o, $k)
    {
        if (isset($o[$k]))
        {
            return $o[$k]['name'];
        }
        else
        {
            return '';
        }
    }

    public static function getObjectNameWithIcon($o)
    {
        if (is_object($o))
        {

            if (strlen($o->getIcon()) > 0)
            {
                return image_tag('icons/p/' . $o->getIcon(), array('title' => $o->getName(), 'absolute' => true));
            }
            else
            {
                return $o->getName();
            }
        }
        else
        {
            return '';
        }
    }

    public static function getArrayNameWithIcon($o, $k)
    {
        if (isset($o[$k]))
        {

            $sort_key = ($o[$k]['sort_order'] > 0 ? $o[$k]['sort_order'] : $o[$k]['id']);

            if (strlen($o[$k]['icon']) > 0)
            {
                return '<span title="' . $sort_key . '"></span>' . image_tag('icons/p/' . $o[$k]['icon'], array('title' => $o[$k]['name'])) . ' ' . $o[$k]['name'] . '';
            }
            else
            {
                return '<span title="' . $sort_key . '"></span>' . $o[$k]['name'];
            }
        }
        else
        {
            return '<span title="0"></span>';
        }
    }

    public static function getObjectNameWithBg($o)
    {
        if (is_object($o))
        {
            if (strlen($o->getBackgroundColor()) > 0)
            {
                return renderBackgroundColorBlock($o->getName(), $o->getBackgroundColor());
            }
            else
            {
                return $o->getName();
            }
        }
        else
        {
            return '';
        }
    }

    public static function getArrayNameWithBg($o, $k)
    {
        if (isset($o[$k]))
        {
            if (strlen($o[$k]['background_color']) > 0)
            {
                return renderBackgroundColorBlock($o[$k]['name'], $o[$k]['background_color']);
            }
            else
            {
                return $o[$k]['name'];
            }
        }
        else
        {
            return '';
        }
    }

    public static function getNameByTableId($t, $id, $s = ', ')
    {
        switch ($t)
        {
            case 'TasksAssignedTo':
            case 'TasksCreatedBy':
            case 'TicketsCreatedBy':
            case 'DiscussionsAssignedTo':
            case 'DiscussionsCreatedBy':
                $t = 'Users';
                break;
        }
        $l = array();
        foreach (explode(',', $id) as $v)
        {
            if ($o = Doctrine_Core::getTable($t)->find($v))
            {
                $l[] = $o->getName();
            }
        }

        return implode($s, $l);
    }

    public static function getFilterMenuStatusItemsByTable($m, $t, $title, $path, $params = false, $selected = array())
    {
        if (!is_array($selected))
            $selected = explode(',', $selected);

        if (count($selected) > 0)
        {
            $ft = 'Preview';
        }
        else
        {
            $ft = '';
        }

        if (app::countItemsByTable($t) > 0)
        {
            $s = array();
            foreach (app::getItemsChoicesByTable($t) as $n => $g)
            {
                if (is_array($g) and count($g) > 0)
                {
                    $gk = array();
                    foreach ($g as $k => $v)
                    {
                        $gk[] = $k;
                    }

                    //$s[] = array('title'=>'<a href="' . url_for($path . '?filter_by[' . $t .']=' . implode(',',$gk) . ($params ? '&' . $params:'')) . '"><b>' . __($n) . '</b></a>');
                    $s[] = array('title' => link_to('<b>' . __($n) . '</b>', $path, array('query_string' => 'filter_by[' . $t . ']=' . implode(',', $gk) . ($params ? '&' . $params : ''))));

                    foreach ($g as $k => $v)
                    {
                        $s[] = array('title' => '<table><tr><td style="padding-right: 10px;"><input class="' . $t . 'Filters' . $ft . ' toggle" name="' . $t . $k . '" id="' . $t . $k . '" value="' . $k . '" ' . (in_array($k, $selected) ? 'checked="checked"' : '') . ' type="checkbox"></td><td>' . link_to($v, $path, array('query_string' => 'filter_by[' . $t . ']=' . $k . ($params ? '&' . $params : ''))) . '</td></table>');
                    }
                }
            }

            $s[] = array('title' => '<form id="filter_by_' . $t . '_form" action="' . url_for($path . ($params ? '?' . $params : '')) . '" method="post">' . input_hidden_tag('filter_by[' . $t . ']', '') . '<table onClick="filter_by_selected(\'' . $t . '\',\'' . $ft . '\')"><tr><td style="padding-right: 10px;">' . image_tag('icons/arrow_up.png') . '</td><td>' . __('Filter by selected') . '</td></table></form>', 'is_hr' => true);

            $m[] = array('title' => __($title), 'submenu' => $s);
        }

        return $m;
    }

    public static function getFilterMenuItemsByTable($m, $t, $title, $path, $params = false, $selected = array(), $sf_user = false)
    {
        if (!is_array($selected))
            $selected = explode(',', $selected);
        if ($params)
        {
            $projects_id = str_replace('projects_id=', '', $params);
        }
        else
        {
            $projects_id = false;
        }

        if (count($selected) > 0)
        {
            $ft = 'Preview';
        }
        else
        {
            $ft = '';
        }

        if (app::countItemsByTable($t, $projects_id) > 0)
        {
            $s = array();
            foreach (app::getItemsChoicesByTable($t, false, $projects_id, $sf_user) as $k => $v)
            {

                $s[] = array('title' => '<table><tr><td style="padding-right: 10px;"><input class="' . $t . 'Filters' . $ft . ' toggle" name="' . $t . $k . '" id="' . $t . $k . '" value="' . $k . '" ' . (in_array($k, $selected) ? 'checked="checked"' : '') . ' type="checkbox"></td><td>' . link_to($v, $path, array('query_string' => 'filter_by[' . $t . ']=' . $k . ($params ? '&' . $params : ''))) . '</td></table>');
            }

            $s[] = array('title' => '<form id="filter_by_' . $t . '_form" action="' . url_for($path . ($params ? '?' . $params : '')) . '" method="post">' . input_hidden_tag('filter_by[' . $t . ']', '') . '<table onClick="filter_by_selected(\'' . $t . '\',\'' . $ft . '\')"><tr><td style="padding-right: 10px;">' . image_tag('icons/arrow_up.png') . '</td><td>' . __('Filter by selected') . '</td></table></form>', 'is_hr' => true);

            $m[] = array('title' => __($title), 'submenu' => $s);
        }

        return $m;
    }

    public static function getFilterMenuUsers($m, $t, $title, $path, $params = false, $selected = array())
    {
        if (!is_array($selected))
            $selected = explode(',', $selected);

        if (count($selected) > 0)
        {
            $ft = 'Preview';
        }
        else
        {
            $ft = '';
        }

        $has_access = '';

        switch ($t)
        {
            case 'TasksAssignedTo': $has_access = 'tasks';
                break;
            case 'TasksCreatedBy': $has_access = 'tasks_insert';
                break;
            case 'TicketsCreatedBy': $has_access = 'tickets_insert';
                break;
            case 'DiscussionsAssignedTo': $has_access = 'discussions';
                break;
            case 'DiscussionsCreatedBy': $has_access = 'discussions_insert';
                break;
        }

        $s = array();
        foreach (Users::getChoices(array(), $has_access) as $n => $g)
        {
            if (count($g) > 0)
            {
                $gk = array();
                foreach ($g as $k => $v)
                {
                    $gk[] = $k;
                }

                $ss = array();
                foreach ($g as $k => $v)
                {
                    $ss[] = array('title' => '<table><tr><td style="padding-right: 10px;"><input class="' . $t . 'Filters toggle' . $ft . '" name="' . $t . $k . '" id="' . $t . $k . '" value="' . $k . '" ' . (in_array($k, $selected) ? 'checked="checked"' : '') . ' type="checkbox"></td><td>' . link_to($v, $path, array('query_string' => 'filter_by[' . $t . ']=' . $k . ($params ? '&' . $params : ''))) . '</td></table>');
                }

                if (count($ss) > 0)
                {
                    $s[] = array('title' => '<a href="' . url_for($path . '?filter_by[' . $t . ']=' . implode(',', $gk) . ($params ? '&' . $params : '')) . '">' . __($n) . '</a>', 'submenu' => $ss);
                }
            }
        }

        $s[] = array('title' => '<form id="filter_by_' . $t . '_form" action="' . url_for($path . ($params ? '?' . $params : '')) . '" method="post">' . input_hidden_tag('filter_by[' . $t . ']', '') . '<table onClick="filter_by_selected(\'' . $t . '\',\'' . $ft . '\')"><tr><td style="padding-right: 10px;">' . image_tag('icons/arrow_up.png') . '</td><td>' . __('Filter by selected') . '</td></table></form>', 'is_hr' => true);

        $m[] = array('title' => __($title), 'submenu' => $s);


        return $m;
    }

    public static function getFilterProjects($m, $path, $params = false, $selected = array(), $sf_user)
    {
        if (!is_array($selected))
            $selected = explode(',', $selected);

        if (count($selected) > 0)
        {
            $ft = 'Preview';
        }
        else
        {
            $ft = '';
        }

        $q = Doctrine_Core::getTable('Projects')->createQuery('p')
                ->leftJoin('p.ProjectsStatus ps')
                ->leftJoin('p.ProjectsTypes pt')
                ->leftJoin('p.Users')
        ;

        if (Users::hasAccess('view_own', 'projects', $sf_user))
        {
            $q->addWhere("find_in_set('" . $sf_user->getAttribute('id') . "',p.team) or p.created_by='" . $sf_user->getAttribute('id') . "'");
        }


        $projects_filter = array();
        $tasks_filter = $sf_user->getAttribute('tasks_filter');
        if (isset($tasks_filter['ProjectsStatus']))
            $projects_filter['ProjectsStatus'] = $tasks_filter['ProjectsStatus'];
        if (isset($tasks_filter['ProjectsTypes']))
            $projects_filter['ProjectsTypes'] = $tasks_filter['ProjectsTypes'];

        $q = Projects::addFiltersToQuery($q, $projects_filter);

        $projects_list = $q->orderBy('p.name')->fetchArray();

        $s = array();
        $t = 'Projects';
        foreach ($projects_list as $v)
        {
            $k = $v['id'];
            $s[] = array('title' => '<table><tr><td style="padding-right: 10px;"><input class="' . $t . 'Filters' . $ft . ' toggle" name="' . $t . $k . '" id="' . $t . $k . '" value="' . $k . '" ' . (in_array($k, $selected) ? 'checked="checked"' : '') . ' type="checkbox"></td><td>' . link_to(app::truncate_text($v['name']), $path, array('query_string' => 'filter_by[' . $t . ']=' . $k . ($params ? '&' . $params : ''))) . '</td></table>');
        }
        $s[] = array('title' => '<form id="filter_by_' . $t . '_form" action="' . url_for($path . ($params ? '?' . $params : '')) . '" method="post">' . input_hidden_tag('filter_by[' . $t . ']', '') . '<table onClick="filter_by_selected(\'' . $t . '\',\'' . $ft . '\')"><tr><td style="padding-right: 10px;">' . image_tag('icons/arrow_up.png') . '</td><td>' . __('Filter by selected') . '</td></table></form>', 'is_hr' => true);
        $m[] = array('title' => __('Projects'), 'submenu' => $s, 'is_hr' => true);

        return $m;
    }

    public static function getDateFormat()
    {
        if (strlen(sfConfig::get('app_custom_short_date_format')) > 0)
        {
            return sfConfig::get('app_custom_short_date_format');
        }
        else
        {
            return 'm/d/Y';
        }
    }

    public static function getDateTimeFormat()
    {
        if (strlen(sfConfig::get('app_custom_logn_date_format')) > 0)
        {
            return sfConfig::get('app_custom_logn_date_format');
        }
        else
        {
            return 'm/d/Y H:i:s';
        }
    }

    public static function getDateTimestamp($date)
    {
        if (strlen($date) > 0)
        {
            $v = date_parse($date);

            return mktime($v['hour'], $v['minute'], $v['second'], $v['month'], $v['day'], $v['year']);
        }
        else
        {
            return '';
        }
    }

    public static function dueDateFormat($date, $is_listing = false)
    {

        if (strlen($date) > 0)
        {
            $v = date_parse($date);

            $date_sec = mktime(0, 0, 0, $v['month'], $v['day'], $v['year']);
            $current_date_sec = time();

            if ($current_date_sec > $date_sec)
            {
                return ($is_listing ? '<span title="' . $date_sec . '"></span>' : '') . str_replace(array('00:00', '00:00:00'), '', '<b>' . app::i18n_date(app::getDateTimeFormat(), $date_sec) . '</b>');
            }
            else
            {
                return ($is_listing ? '<span title="' . $date_sec . '"></span>' : '') . str_replace(array('00:00', '00:00:00'), '', app::i18n_date(app::getDateTimeFormat(), $date_sec));
            }
        }
        else
        {
            return '<span title="0"></span>';
        }
    }

    public static function dateFormat($date, $timestamp = 0, $is_listing = false)
    {
        if ($timestamp > 0)
        {
            return ($is_listing ? '<span title="' . $timestamp . '"></span>' : '') . app::i18n_date(app::getDateFormat(), $timestamp);
        }
        elseif (strlen($date) > 0)
        {
            $v = date_parse($date);
            $timestamp = mktime($v['hour'], $v['minute'], $v['second'], $v['month'], $v['day'], $v['year']);
            return ($is_listing ? '<span title="' . $timestamp . '"></span>' : '') . app::i18n_date(app::getDateFormat(), $timestamp);
        }
        else
        {
            return '<span title="0"></span>';
        }
    }

    public static function dateTimeFormat($date, $timestamp = 0, $is_listing = false)
    {
        if ($timestamp > 0)
        {
            return ($is_listing ? '<span title="' . $timestamp . '"></span>' : '') . str_replace(array('00:00', '00:00:00'), '', app::i18n_date(app::getDateTimeFormat(), $timestamp));
        }
        elseif (strlen($date) > 0)
        {
            $v = date_parse($date);
            $timestamp = mktime($v['hour'], $v['minute'], $v['second'], $v['month'], $v['day'], $v['year']);
            return ($is_listing ? '<span title="' . $timestamp . '"></span>' : '') . str_replace(array('00:00', '00:00:00'), '', app::i18n_date(app::getDateTimeFormat(), $timestamp));
        }
        else
        {
            return '<span title="0"></span>';
        }
    }

    public static function ganttDateFormat($date)
    {
        if (strlen($date) > 0)
        {
            $v = date_parse($date);

            return date('m/d/Y', mktime($v['hour'], $v['minute'], $v['second'], $v['month'], $v['day'], $v['year']));
        }
        else
        {
            return '';
        }
    }

    public static function msProjectDateFormat($date, $time)
    {
        if (strlen($date) > 0)
        {


            return substr($date, 0, 10) . 'T' . $time;
        }
        else
        {
            return date('Y-m-d') . 'T' . $time;
        }
    }

    public static function i18n_date()
    {
        $translate = array(
            "am" => t::__("am"),
            "pm" => t::__("pm"),
            "AM" => t::__("AM"),
            "PM" => t::__("PM"),
            "Monday" => t::__("Monday"),
            "Mon" => t::__("Mon"),
            "Tuesday" => t::__("Tuesday"),
            "Tue" => t::__("Tue"),
            "Wednesday" => t::__("Wednesday"),
            "Wed" => t::__("Wed"),
            "Thursday" => t::__("Thursday"),
            "Thu" => t::__("Thu"),
            "Friday" => t::__("Friday"),
            "Fri" => t::__("Fri"),
            "Saturday" => t::__("Saturday"),
            "Sat" => t::__("Sat"),
            "Sunday" => t::__("Sunday"),
            "Sun" => t::__("Sun"),
            "January" => t::__("January"),
            "Jan" => t::__("Jan"),
            "February" => t::__("February"),
            "Feb" => t::__("Feb"),
            "March" => t::__("March"),
            "Mar" => t::__("Mar"),
            "April" => t::__("April"),
            "Apr" => t::__("Apr"),
            "May" => t::__("May"),
            "June" => t::__("June"),
            "Jun" => t::__("Jun"),
            "July" => t::__("July"),
            "Jul" => t::__("Jul"),
            "August" => t::__("August"),
            "Aug" => t::__("Aug"),
            "September" => t::__("September"),
            "Sep" => t::__("Sep"),
            "October" => t::__("October"),
            "Oct" => t::__("Oct"),
            "November" => t::__("November"),
            "Nov" => t::__("Nov"),
            "December" => t::__("December"),
            "Dec" => t::__("Dec"),
        );


        if (func_num_args() > 1)
        {
            $timestamp = func_get_arg(1);
            return strtr(date(func_get_arg(0), $timestamp), $translate);
        }
        else
        {
            return strtr(date(func_get_arg(0)), $translate);
        }
    }

    public static function image_resize($filename, $filename_small, $resize_image_widht = 50, $resize_image_height = '')
    {
        if (file_exists($filename))
        {
            $image = getimagesize($filename);

            switch ($image[2])
            {
                case 1: $src_img = imagecreatefromgif($filename);
                    break;
                case 2: $src_img = imagecreatefromjpeg($filename);
                    break;
                case 3: $src_img = imagecreatefrompng($filename);
                    break;
            }

            $width = $image[0];
            $height = $image[1];

            if ($resize_image_widht > 0 && $resize_image_height == '')
            {
                $cof = $width / $resize_image_widht;
                $width_small = $resize_image_widht;
                $height_small = $height / $cof;
            }
            elseif ($resize_image_height > 0 && $resize_image_widht == '')
            {
                $height_small = $resize_image_widht;
                $cof = $height / $resize_image_height;
                $width_small = $width / $cof;
            }
            else
            {
                $height_small = $height;
                $width_small = $width;
            }

            $tmp_img_small = imagecreatetruecolor($width_small, $height_small);
            ImageCopyResampled($tmp_img_small, $src_img, 0, 0, 0, 0, $width_small, $height_small, $width, $height);

            @touch($filename_small);
            @chmod($filename_small, 0777);

            switch ($image[2])
            {
                case 1: imagegif($tmp_img_small, $filename_small);
                    break;
                case 2: imagejpeg($tmp_img_small, $filename_small, 100);
                    break;
                case 3: imagepng($tmp_img_small, $filename_small, 9);
                    break;
            }
        }
    }

    static public function getLanguageCodes()
    {
        $dir = dir(sfConfig::get('sf_app_i18n_dir'));
        $codes = array('en');
        while ($file = $dir->read())
        {
            if (is_dir(sfConfig::get('sf_app_i18n_dir') . '/' . $file) and!strstr($file, '.'))
            {
                $codes[] = $file;
            }
        }
        return $codes;
    }

    public static function addSearchQuery($q, $search, $t, $n, $search_by_extrafields)
    {
        if (is_array($search))
        {
            switch ($t)
            {
                case 'ProjectsComments':
                    $bind_field = 'projects_id';
                    break;
                case 'TasksComments':
                    $bind_field = 'tasks_id';
                    break;
                case 'TicketsComments':
                    $bind_field = 'tickets_id';
                    break;
                case 'DiscussionsComments':
                    $bind_field = 'discussions_id';
                    break;
            }

            $query = array();
            $count = 1;
            foreach (explode(' ', trim($search['keywords'])) as $k)
            {
                $query[] = $n . ".id = '" . addslashes($k) . "'";

                $query[] = $n . ".name like '%" . addslashes($k) . "%'";

                if (isset($search['in_description']))
                {
                    $query[] = $n . ".description like '%" . addslashes($k) . "%'";
                }

                if (isset($search['in_comments']))
                {
                    $query[] = "(SELECT COUNT(*) FROM " . $t . " comments" . $count . " WHERE comments" . $count . ".description like '%" . $k . "%' and comments" . $count . "." . $bind_field . "=" . $n . ".id)>0";
                }

                if (isset($search_by_extrafields))
                {
                    foreach ($search_by_extrafields as $efId)
                    {
                        $query[] = '(SELECT COUNT(*) FROM ExtraFieldsList efl' . $efId . ' WHERE  efl' . $efId . '.value like \'%' . addslashes($k) . '%\' AND efl' . $efId . '.bind_id=' . $n . '.id AND efl' . $efId . '.extra_fields_id="' . $efId . '")>0';
                    }
                }

                $count++;
            }

            if (count($query) > 0)
            {
                $q->addWhere(implode(' OR ', $query));
            }
        }

        return $q;
    }

    public static function setListingOrder($module, $order_type, $users_id, $projects_id = 0)
    {

        $o = Doctrine_Core::getTable('UsersListingsOrder')
                ->createQuery()
                ->addWhere('module=?', $module)
                ->addWhere('users_id=?', $users_id)
                ->addWhere(($projects_id > 0 ? 'projects_id=' . $projects_id : 'projects_id is null'))
                ->fetchOne();

        if ($o)
        {
            $o->setOrderType($order_type);
            $o->save();
        }
        else
        {
            $o = new UsersListingsOrder;
            $o->setModule($module);
            $o->setOrderType($order_type);
            $o->setUsersId($users_id);

            if ($projects_id > 0)
            {
                $o->setProjectsId($projects_id);
            }

            $o->save();
        }
    }

    public static function addListingOrder($q, $module, $sf_user, $projects_id = '')
    {
        if ($projects_id == 0)
            $projects_id = '';

        switch ($module)
        {
            case 'projects':
                if ($sf_user->hasAttribute('projects_listing_order'))
                {
                    $q = Projects::getListingOrderByType($q, $sf_user->getAttribute('projects_listing_order'));
                }
                else
                {
                    $q->orderBy('ps.sort_order,p.projects_status_id, p.name');
                }

                break;
            case 'tasks':
                if ($sf_user->hasAttribute('tasks_listing_order' . $projects_id))
                {
                    $q = Tasks::getListingOrderByType($q, $sf_user->getAttribute('tasks_listing_order' . $projects_id));
                }
                else
                {
                    $q->orderBy('ts.group desc, ts.sort_order,LTRIM(ts.name), LTRIM(p.name), LTRIM(t.name)');
                }

                break;
            case 'tickets':
                if ($sf_user->hasAttribute('tickets_listing_order' . $projects_id))
                {
                    $q = Tickets::getListingOrderByType($q, $sf_user->getAttribute('tickets_listing_order' . $projects_id));
                }
                else
                {
                    $q->orderBy(' ts.sort_order,LTRIM(ts.name), LTRIM(p.name), LTRIM(t.name)');
                }

                break;
            case 'discussions':
                if ($sf_user->hasAttribute('discussions_listing_order' . $projects_id))
                {
                    $q = Discussions::getListingOrderByType($q, $sf_user->getAttribute('discussions_listing_order' . $projects_id));
                }
                else
                {
                    $q->orderBy('ds.sort_order,LTRIM(ds.name), LTRIM(p.name), LTRIM(d.name)');
                }

                break;
        }


        return $q;
    }

    public static function getListingOrderTitle($module, $sf_user, $projects_id = 0)
    {
        $order = '';

        switch ($module)
        {
            case 'projects':
                $order = $sf_user->getAttribute('projects_listing_order');
                break;
            case 'tasks':
                $order = $sf_user->getAttribute('tasks_listing_order' . $projects_id);
                break;
            case 'tickets':
                $order = $sf_user->getAttribute('tickets_listing_order' . $projects_id);
                break;
            case 'discussions':
                $order = $sf_user->getAttribute('discussions_listing_order' . $projects_id);

                break;
        }

        if (strlen($order) > 0)
        {
            $t = '';

            switch ($order)
            {
                case 'date_added': $t = 'Date Added';
                    break;
                case 'date_last_commented': $t = 'Date Last Commented';
                    break;
                case 'name': $t = 'Name';
                    break;
                case 'priority': $t = 'Priority';
                    break;
                case 'status': $t = 'Status';
                    break;
                case 'type': $t = 'Type';
                    break;
                case 'group': $t = 'Group';
                    break;
                case 'label': $t = 'Label';
                    break;
            }

            return __($t);
        }
        else
        {
            return __('Status');
        }
    }

    public static function getUsersReportsChoicesByTable($t, $sf_user)
    {
        $reports = Doctrine_Core::getTable($t)
                ->createQuery()
                ->addWhere('users_id=?', $sf_user->getAttribute('id'))
                ->orderBy('name')
                ->fetchArray();

        $choices = array();
        foreach ($reports as $r)
        {
            $choices[$r['id']] = $r['name'];
        }

        return $choices;
    }

    public static function getReportFormFilterByTable($title, $field, $t, $values, $sf_user = false)
    {
        if (app::countItemsByTable($t) > 0)
        {
            if (!is_string($values))
                $values = '';

            return '
        <div class="form-group">
        	<label class="col-md-3 control-label">' . __($title) . '</label>
        	<div class="col-md-9">
        		' . select_tag($field, explode(',', $values), array('choices' => app::getItemsChoicesByTable($t, false, false, $sf_user), 'expanded' => true, 'multiple' => true)) . '
        	</div>
        </div>
      ';
        }
        else
        {
            return '';
        }
    }

    public static function getProjectChoicesByUser($sf_user, $add_empty = false, $module = '', $check_insert = false)
    {
        $q = Doctrine_Core::getTable('Projects')->createQuery('p');

        if (Users::hasAccess('view_own', 'projects', $sf_user))
        {
            $q->addWhere("find_in_set('" . $sf_user->getAttribute('id') . "',p.team)");
        }

        $choices = array();
        if ($add_empty)
        {
            $choices = array('' => '');
        }

        foreach ($q->orderBy('name')->execute() as $p)
        {
            $choices[$p->getId()] = $p->getName();
        }

        return $choices;
    }

    public static function getLastCommentByTable($t, $bind_field, $bind_id)
    {
        $c = Doctrine_Core::getTable($t)->createQuery('t')->leftJoin('t.Users u')
                ->addWhere($bind_field . '=?', $bind_id)
                ->orderBy('t.created_at desc')
                ->fetchOne(array(), Doctrine::HYDRATE_ARRAY);

        $module = strtolower($t[0]) . substr($t, 1);

        if ($c)
        {
            return '<a class="jt" href="#" onClick="return false" rel="' . url_for($module . '/info?id=' . $c['id']) . '" title="' . app::dateTimeFormat($c['created_at']) . '"><sup>' . image_tag('icons/comment_small.png') . $c['Users']['name'] . '</sup></a>';
        }
        else
        {
            return '';
        }
    }

    public static function setCssForEmailContent($html)
    {
        $html = str_replace('<h1>', '<h1 style="' . sfConfig::get('app_email_style_h1') . '">', $html);
        $html = str_replace('<h3>', '<h3 style="' . sfConfig::get('app_email_style_h3') . '">', $html);
        $html = str_replace('<td>', '<td style="' . sfConfig::get('app_email_style_td') . '">', $html);
        $html = str_replace('<th>', '<th style="' . sfConfig::get('app_email_style_th') . '">', $html);
        $html = str_replace('<div>', '<div style="' . sfConfig::get('app_email_style_div') . '">', $html);
        $html = str_replace('<table ', '<table style="' . sfConfig::get('app_email_style_table') . '" ', $html);

        $html = str_replace('id="hide_in_email"', 'style="display: none !important;"', $html);

        return $html;
    }

    public static function backup($file_prefix = '')
    {
        set_time_limit(0);

        $connection = Doctrine_Manager::connection();

        $table_list = array_reverse(app::getTablesList());

        $fp = fopen(sfConfig::get('sf_web_dir') . '/backups/' . $file_prefix . 'backup_' . date('Y-m-d_H-i-s') . '.sql', 'w+');

        foreach (app::getTablesList() as $table)
        {
            fwrite($fp, "DROP TABLE IF EXISTS " . $table . ";\n");
        }

        fwrite($fp, "\n\n");

        foreach ($table_list as $table)
        {
            $query = 'SHOW CREATE TABLE ' . $table;
            $statement = $connection->execute($query);
            //$statement->execute();
            $create_table_query = $statement->fetch(PDO::FETCH_NUM);

            fwrite($fp, str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $create_table_query[1]) . ";\n\n");

            $query = 'SELECT COUNT(*) as total FROM  ' . $table;
            $statement = $connection->execute($query);
            //$statement->execute();
            $table_size = $statement->fetch();
            $table_size = $table_size['total'];

            if ($table_size > 0)
            {
                $columns_null = array();

                $query = 'SHOW COLUMNS FROM  ' . $table;
                $statement = $connection->execute($query);
                //$statement->execute();
                $colums_list = $statement->fetchAll(PDO::FETCH_ASSOC);

                foreach ($colums_list as $column)
                {
                    if ($column['Null'] == 'YES')// and preg_match("/^(\w*int|year)/", $column['Type']))
                    {
                        $columns_null[] = $column['Field'];
                    }
                }

                fwrite($fp, "INSERT IGNORE INTO " . $table . " VALUES");

                $limit = 1000;
                $from = 0;
                $i = 0;

                do
                {
                    $query = 'SELECT * FROM  ' . $table . ' LIMIT ' . $from . ', ' . $limit;
                    $statement = $connection->execute($query);
                    //$statement->execute();
                    $rows_list = $statement->fetchAll(PDO::FETCH_ASSOC);

                    if ($rows_list)
                    {
                        foreach ($rows_list as $row)
                        {
                            $i++;

                            foreach ($row as $k => $v)
                            {
                                if (strlen($v) == 0 and in_array($k, $columns_null))
                                {
                                    $row[$k] = "NULL";
                                }
                                else
                                {
                                    $row[$k] = "'" . addslashes($v) . "'";
                                }
                            }

                            fwrite($fp, ($i > 1 ? "," : "") . "\n(" . implode(",", $row) . ")");
                        }
                    }

                    $from += $limit;
                }
                while ($from < ($table_size + $limit));

                fwrite($fp, ";\n\n");
            }
        }
    }

    public static function getTablesList()
    {
        $core = array(
            'discussions_reports',
            'discussions_comments',
            'discussions',
            'user_reports',
            'tasks_comments',
            'tasks',
            'tickets_reports',
            'tickets_comments',
            'tickets',
            'tasks_groups',
            'versions',
            'projects_phases',
            'projects_reports',
            'projects_comments',
            'projects',
            'attachments',
            'events',
            'departments',
            'users',
            'versions_status',
            'users_groups',
            'tickets_types',
            'tickets_status',
            'tasks_types',
            'tasks_status',
            'tasks_priority',
            'tasks_labels',
            'projects_types',
            'projects_status',
            'phases',
            'phases_status',
            'extra_fields_list',
            'extra_fields',
            'discussions_status',
            'configuration',
        );

        return $core;
    }

    public static function is_image($path)
    {
        if (is_file($path))
        {
            $type = mime_content_type($path);

            if (in_array($type, array('image/jpeg', 'image/png', 'image/gif', 'image/vnd.microsoft.icon', 'image/x-icon')))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    static function db_prepare_html_input($html)
    {
        if (!strlen($html))
            return '';

        $html = preg_replace(['#<script(.*?)>(.*?)</script>#is', '#<script(.*?)>#is'], '', $html);

        $config = HTMLPurifier_Config::createDefault();
        $config->set('Attr.AllowedFrameTargets', array('_blank'));
        //$config->set('HTML.Trusted', true);        
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }
    
    static function print_rr($v)
    {
        echo '<pre>';
        print_r($v);
        echo '</pre>';
    }

}
