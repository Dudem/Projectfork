<?php
/**
 * @package      Projectfork
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


jimport('joomla.application.component.view');


class ProjectforkViewProjects extends JView
{
    function display()
    {
        $app    = JFactory::getApplication();
        $doc    = JFactory::getDocument();
        $params = $app->getParams();

        $feedEmail = (@$app->getCfg('feed_email')) ? $app->getCfg('feed_email') : 'author';
        $siteEmail = $app->getCfg('mailfrom');

        // Get some data from the model
        JRequest::setVar('limit', $app->getCfg('feed_limit'));
        $rows = $this->get('Items');

        $doc->link = htmlspecialchars(JFactory::getURI()->toString());


        foreach($rows as $row)
        {
            // Strip html from feed item title
            $title = $this->escape($row->title);
            $title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

            // URL link to item
            $link = JRoute::_(ProjectforkHelperRoute::getDashboardRoute($item->slug));

            // Strip html from feed item description text
            $description = ($params->get('feed_summary', 0) ? $row->description : '');
            $author      = $row->author_name;
            @$date       = ($row->created ? date('r', strtotime($row->created)) : '');

            // load individual item creator class
            $item = new JFeedItem();
            $item->title       = $title;
            $item->link        = $link;
            $item->description = $description;
            $item->date        = $date;
            $item->author      = $author;
            $item->authorEmail = ($feedEmail == 'site') ? $siteEmail : $row->author_email;

            // loads item info into rss array
            $doc->addItem($item);
        }
    }
}
