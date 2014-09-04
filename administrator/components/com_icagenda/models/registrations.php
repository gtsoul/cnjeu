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
 * @version     3.1.9 2013-09-04
 * @since		2.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of iCagenda records.
 */
class iCagendaModelregistrations extends JModelList
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
                'userid', 'userid',
                'name', 'name',
                'username', 'username',
                'email', 'email',
                'phone', 'phone',
                'event', 'event',
				'date', 'a.date',
				'people', 'a.people',
				'notes', 'a.notes',
				'created_by', 'created_by',

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

		// Load the parameters.
		$params = JComponentHelper::getParams('com_icagenda');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'asc');
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
		$query->from('`#__icagenda_registration` AS a');


                // Join over the users for the checked out user.
                $query->select('u.username AS username');
                $query->join('LEFT', '#__users AS u ON u.id=a.userid');

				// Join over the users for the checked out user.
                $query->select('e.title AS event, e.created_by AS created_by');
                $query->join('LEFT', '#__icagenda_events AS e ON e.id=a.eventid');


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
				if(version_compare(JVERSION, '3.0', 'lt')) {
					$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
 				} else {
					$search = $db->Quote('%'.$db->escape($search, true).'%');
				}
               $query->where('(u.username LIKE '.$search.'  OR  a.name LIKE '.$search.'  OR  a.userid LIKE '.$search.'  OR  a.email LIKE '.$search.'  OR  a.phone LIKE '.$search.'  OR  a.date LIKE '.$search.'  OR  a.period LIKE '.$search.'  OR  a.people LIKE '.$search.'  OR  a.notes LIKE '.$search.'  OR  e.title LIKE '.$search.' )');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
	if(version_compare(JVERSION, '3.0', 'lt')) {
		    $query->order($db->getEscaped($orderCol.' '.$orderDirn));
	} else {
		    $query->order($db->escape($orderCol.' '.$orderDirn));
	}
        }

		return $query;
	}
}
