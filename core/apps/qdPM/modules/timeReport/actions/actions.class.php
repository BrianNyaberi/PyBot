<?php

/**
 * qdPM
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@qdPM.net so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade qdPM to newer
 * versions in the future. If you wish to customize qdPM for your
 * needs please refer to http://www.qdPM.net for more information.
 *
 * @copyright  Copyright (c) 2009  Sergey Kharchishin and Kym Romanets (http://www.qdpm.net)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
?>
<?php

/**
 * timeReport actions.
 *
 * @package    sf_sandbox
 * @subpackage timeReport
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class timeReportActions extends sfActions
{

    protected function checkProjectsAccess($projects)
    {
        Projects::checkViewOwnAccess($this, $this->getUser(), $projects);
    }

    protected function checkTasksAccess($access, $tasks = false, $projects = false)
    {
        if ($projects)
        {
            Users::checkAccess($this, $access, 'tasks', $this->getUser(), $projects->getId());
            if ($tasks)
            {
                Tasks::checkViewOwnAccess($this, $this->getUser(), $tasks, $projects);
            }
        } else
        {
            Users::checkAccess($this, $access, 'tasks', $this->getUser());
        }
    }

    protected function add_pid($request, $sep = '?')
    {
        if ((int) $request->getParameter('projects_id') > 0)
        {
            return $sep . 'projects_id=' . $request->getParameter('projects_id');
        } else
        {
            return '';
        }
    }

    protected function get_pid($request)
    {
        return ((int) $request->getParameter('projects_id') > 0 ? $request->getParameter('projects_id') : '');
    }

    public function executeIndex(sfWebRequest $request)
    {
        if (!$this->getUser()->hasCredential('reports_access_time'))
        {
            $this->redirect('accessForbidden/index');
        }

        if ($request->hasParameter('projects_id'))
        {
            $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?', $request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));

            $this->checkProjectsAccess($this->projects);
            $this->checkTasksAccess('view', false, $this->projects);
        } else
        {
            $this->checkTasksAccess('view');
        }

        if (!$this->getUser()->hasAttribute('time_report_filter' . $this->get_pid($request)))
        {
            $this->getUser()->setAttribute('time_report_filter' . $this->get_pid($request), Tasks::getDefaultFilter($request, $this->getUser(), 'timeReport'));
        }

        $this->filter_by = $this->getUser()->getAttribute('time_report_filter' . $this->get_pid($request));

        if ($fb = $request->getParameter('filter_by'))
        {
            foreach ($fb as $k => $v)
            {
                $this->filter_by[$k] = $v;
            }
            $this->getUser()->setAttribute('time_report_filter' . $this->get_pid($request), $this->filter_by);

            $this->redirect('timeReport/index' . $this->add_pid($request));
        }

        if ($request->hasParameter('remove_filter'))
        {
            unset($this->filter_by[$request->getParameter('remove_filter')]);
            $this->getUser()->setAttribute('time_report_filter' . $this->get_pid($request), $this->filter_by);

            $this->redirect('timeReport/index' . $this->add_pid($request));
        }


        app::setPageTitle('Users Time Report', $this->getResponse());

        $this->tasks_comments = $this->getTasksComments($request);
    }

    public function executeMyTimeReport(sfWebRequest $request)
    {
        if (!$this->getUser()->hasCredential('reports_access_time_personal'))
        {
            $this->redirect('accessForbidden/index');
        }

        $this->checkTasksAccess('view');

        if (!$this->getUser()->hasAttribute('my_time_report_filter'))
        {
            $this->getUser()->setAttribute('my_time_report_filter', Tasks::getDefaultFilter($request, $this->getUser(), 'timeReport'));
        }

        $this->filter_by = $this->getUser()->getAttribute('my_time_report_filter');

        if ($fb = $request->getParameter('filter_by'))
        {
            foreach ($fb as $k => $v)
            {
                $this->filter_by[$k] = $v;
            }
            $this->getUser()->setAttribute('my_time_report_filter', $this->filter_by);

            $this->redirect('timeReport/myTimeReport');
        }

        if ($request->hasParameter('remove_filter'))
        {
            unset($this->filter_by[$request->getParameter('remove_filter')]);
            $this->getUser()->setAttribute('my_time_report_filter', $this->filter_by);

            $this->redirect('timeReport/myTimeReport');
        }

        $this->filter_by['CommentCreatedBy'] = $this->getUser()->getAttribute('id');
        $this->getUser()->setAttribute('my_time_report_filter', $this->filter_by);

        app::setPageTitle('My Time Report', $this->getResponse());

        $this->tasks_comments = $this->getTasksComments($request, 'my_time_report_filter');

        $this->setTemplate('index');
    }

    public function executeSaveFilter(sfWebRequest $request)
    {
        $this->setTemplate('saveFilter', 'app');
    }

    public function executeDoSaveFilter(sfWebRequest $request)
    {
        Tasks::saveTasksFilter($request, $this->getUser()->getAttribute('time_report_filter' . $this->get_pid($request)), $this->getUser(), 'timeReport');

        $this->getUser()->setFlash('userNotices', t::__('Filter Saved'));
        $this->redirect('timeReport/index' . $this->add_pid($request));
    }

    protected function getTasksComments(sfWebRequest $request, $filter_name = 'time_report_filter')
    {
        $taks_ids = array();

        $filter_by = $this->getUser()->getAttribute($filter_name . $this->get_pid($request));

        $comment_created_by = false;
        $comment_created_from = date('Y-m-d');
        $comment_created_to = '';


        if (isset($filter_by['CommentCreatedBy']))
        {
            $comment_created_by = $filter_by['CommentCreatedBy'];
            unset($filter_by['CommentCreatedBy']);
        }
        if (isset($filter_by['CommentCreatedFrom']))
        {
            $comment_created_from = $filter_by['CommentCreatedFrom'];
            unset($filter_by['CommentCreatedFrom']);
        }
        if (isset($filter_by['CommentCreatedTo']))
        {
            $comment_created_to = $filter_by['CommentCreatedTo'];
            unset($filter_by['CommentCreatedTo']);
        }

        unset($filter_by['TimeDiscrepancy']);

        if (count($filter_by) > 0
                or Users::hasAccess('view_own', 'projects', $this->getUser())
                or Users::hasAccess('view_own', 'tasks', $this->getUser())
                or ($request->getParameter('projects_id') > 0 and Users::hasAccess('view_own', 'tasks', $this->getUser(), $request->getParameter('projects_id')))
                or ($request->getParameter('projects_id') > 0)
        )
        {
            $taks_ids[] = 0;
            foreach ($this->getTasks($request, $filter_name) as $t)
            {
                $taks_ids[] = $t['id'];
            }
        }

        $q = Doctrine_Core::getTable('TasksComments')
                ->createQuery('tc')
                ->leftJoin('tc.Tasks t')
                ->leftJoin('tc.Users u')
                ->addWhere('tc.worked_hours>0');

        if (count($taks_ids))
        {
            $q->whereIn('tc.tasks_id', $taks_ids);
        }

        if ($request->getParameter('projects_id') > 0)
        {
            //$q->addWhere('t.projects_id=?',$request->getParameter('projects_id'));
        }

        if ($comment_created_by > 0)
        {
            $q->addWhere('tc.created_by=?', (int)$comment_created_by);
        }

        if (strlen($comment_created_from) > 0 and strtotime($comment_created_from))
        {
            $q->addWhere('date_format(tc.created_at,"%Y-%m-%d")>="' . $comment_created_from . '"');
        }

        if (strlen($comment_created_to) > 0 and strtotime($comment_created_to))
        {
            $q->addWhere('date_format(tc.created_at,"%Y-%m-%d")<="' . $comment_created_to . '"');
        }



        return $q->orderBy('tc.created_at')->execute();
    }

    protected function getTasks(sfWebRequest $request, $filter_name)
    {
        $q = Doctrine_Core::getTable('Tasks')->createQuery('t')
                ->leftJoin('t.TasksPriority tp')
                ->leftJoin('t.TasksStatus ts')
                ->leftJoin('t.TasksLabels tl')
                ->leftJoin('t.TasksTypes tt')
                ->leftJoin('t.TasksGroups tg')
                ->leftJoin('t.ProjectsPhases pp')
                ->leftJoin('t.Versions v')
                ->leftJoin('t.Projects p')
                ->leftJoin('t.Users');

        if ($request->hasParameter('projects_id'))
        {
            $q->addWhere('projects_id=?', $request->getParameter('projects_id'));

            if (Users::hasAccess('view_own', 'tasks', $this->getUser(), $request->getParameter('projects_id')))
            {
                $q->addWhere("find_in_set('" . (int)$this->getUser()->getAttribute('id') . "',t.assigned_to) or t.created_by='" . (int)$this->getUser()->getAttribute('id') . "'");
            }
        } 
        else
        {
            if (Users::hasAccess('view_own', 'projects', $this->getUser()))
            {
                $q->addWhere("find_in_set('" . (int)$this->getUser()->getAttribute('id') . "',team) or p.created_by='" . (int)$this->getUser()->getAttribute('id') . "'");
            }

            if (Users::hasAccess('view_own', 'tasks', $this->getUser()))
            {
                $q->addWhere("find_in_set('" . (int)$this->getUser()->getAttribute('id') . "',t.assigned_to) or t.created_by='" . (int)$this->getUser()->getAttribute('id') . "'");
            }
        }

        $q = Tasks::addFiltersToQuery($q, $this->getUser()->getAttribute($filter_name . ((int)$request->getParameter('projects_id') > 0 ? (int)$request->getParameter('projects_id') : '')));

        return $q->fetchArray();
    }

    public function executeExport(sfWebRequest $request)
    {
        $separator = "\t";
        $format = $request->getParameter('format');
        $filename = $request->getParameter('filename');
                
        if(!$export = json_decode($request->getParameter('export')))
        {
            $export = [];
        }
        
        header("Content-type: Application/octet-stream");
        header("Content-disposition: attachment; filename=" . $filename . "." . $format);
        header("Pragma: no-cache");
        header("Expires: 0");

        foreach ($export as $line)
        {
            foreach ($line as $k => $v)
            {
                $line[$k] = str_replace(array("\n\r", "\r", "\n", $separator), ' ', $line[$k]);
            }

            if ($format == 'csv')
            {
                echo chr(0xFF) . chr(0xFE) . mb_convert_encoding(implode($separator, $line) . "\n", 'UTF-16LE', 'UTF-8');
            } else
            {
                echo implode($separator, $line) . "\n";
            }
        }

        exit();
    }

}
