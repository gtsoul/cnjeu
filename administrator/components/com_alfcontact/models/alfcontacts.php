<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * AlfContactList Model
 */
class AlfContactModelAlfContacts extends JModelList
{
    //Overriding the constructor to enable sorting
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])){
			$config['filter_fields'] = array(
				'name',
				'ordering',
				'published',
				'access'
			);
		}
		
		parent::__construct($config);
	}
	
	public function getItems()
	{
		$items = parent::getItems();
		foreach ($items as &$item){
			$item->url = 'index.php?option=com_alfcontact&amp;task=alfcontact.edit&amp;id=' . $item->id;
		}
		
		return $items;
	}
	
	public function getListQuery()
	{
		$user = JFactory::getUser();
		
		// Create a new query object.         
        $query = parent::getListQuery();
				
		$query->select('a.*, g.title AS access_level');
		$query->from('#__alfcontact AS a');
		$query->leftJoin('#__viewlevels AS g ON g.id = a.access');
	
		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('a.access = '.(int) $access);
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
		    $groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN ('.$groups.')');
		}
		
		$published = $this->getState('filter.published');
		
		if ($published == '') {
			$query->where('(published = 1 OR published = 0)');
		} else if ($published != '*'){
			$published = (int) $published;
			$query->where("published = '{$published}'");
		}
		
		$search = $this->getState('filter.search');
		$db =$this->getDbo();
		
		if (!empty($search)) {
			$search = '%' . $db->getEscaped($search, true) . '%';
			
			$field_searches = "name LIKE '{$search}' OR email LIKE '{$search}'";
			
			$query->where($field_searches);				
		}
 		
		// Column ordering
		$orderCol = $this->getState('list.ordering');
		$orderDirn = $this->getState('list.direction');
		
		if ($orderCol != '') {
			$query->order($db->getEscaped($orderCol.' '.$orderDirn));
		}
		
		return $query;
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$accessId = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);
		
		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published');
		$this->setState('filter.published', $published);
		
		parent::populateState('ordering', 'asc');
	}
}
