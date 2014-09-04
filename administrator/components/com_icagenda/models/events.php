<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright   Copyright (c)2012-2013 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @version     3.1.10 2013-09-11
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of iCagenda records.
 */
class iCagendaModelevents extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
				'id', 'a.id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created', 'a.created',
				'title', 'a.title',
				'username', 'a.username',
				'email', 'a.email',
				'category', 'category',
				'image', 'a.image',
				'file', 'a.file',
				'next', 'a.next',
				'place', 'a.place',
				'city', 'a.city',
				'country', 'a.country',
				'desc', 'a.desc',
				'params', 'a.params',

                'location', 'a.location',

            );
        }

        parent::__construct($config);
    }


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

                //Filter (dropdown) category
                $state = $this->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '', 'string');

                $this->setState('filter.category', $state);

                //Filter (dropdown) upcoming
                $upcomingevt = $this->getUserStateFromRequest($this->context.'.filter.upcoming', 'filter_upcoming', '', 'string');

                $this->setState('filter.upcoming', $upcomingevt);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_icagenda');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.title', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__icagenda_events` AS a');


		// Join over the language
		$query->select('l.title AS language_title')
			->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join the category
		$query->select('c.title AS category');
		$query->join('LEFT', '#__icagenda_category AS c ON c.id=a.catid');

		// Join over the users for the author.
		$query->select('ua.name AS author_name, ua.username AS author_username')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');


		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = '.(int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}


		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('( a.title LIKE '.$search.'  OR  a.username LIKE '.$search.'  OR  a.created LIKE '.$search.'  OR  a.id LIKE '.$search.'  OR  a.email LIKE '.$search.'  OR  a.file LIKE '.$search.'  OR  a.place LIKE '.$search.'  OR  a.city LIKE '.$search.'  OR  a.country LIKE '.$search.'  OR  a.coordinate LIKE '.$search.'  OR  a.desc LIKE '.$search.'  OR  a.params LIKE '.$search.' OR  c.title LIKE '.$search.'   )');
			}
		}


                // Filter category
                $category= $db->escape($this->getState('filter.category'));
                if (!empty($category)) {
                        $query->where('(a.catid='.$category.')');
                }


                // Filter Upcoming Dates
                $upcoming= $db->escape($this->getState('filter.upcoming'));
				$today=time();
				$datenext=strtotime('a.next');
                if (!empty($upcoming)) {
                        $query->where('('.$datenext.'>'.$upcoming.')');
                }




		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
		    $query->order($db->escape($orderCol.' '.$orderDirn));
        }

		return $query;
	}


}
