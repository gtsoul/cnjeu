<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5-2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @author Johannes Me�mer
 * @copyright (C) 2010- Johannes Me�mer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

function jVoteSystemBuildRoute(&$query)
{
	$segments = array();

	if(isset($query['view']))
	{
		$segments[] = $query['view'];
		unset($query['view']);
	}
	
	if(isset($query['cat']))
	{
		$segments[] = $query['cat'];
		unset($query['cat']);
	};
	
	if(isset($query['alias']))
	{
		$segments[] = $query['alias'];
		unset($query['alias']);
	};

	if(isset($query['id']))
	{
		$segments[] = $query['id'];
		unset($query['id']);
	};

	if(isset($query['task']))
	{
		$segments[] = $query['task'];
		unset($query['task']);
	};
	
	if(isset($query['aid']))
	{
		$segments[] = $query['aid'];
		unset($query['aid']);
	};
	
	if(isset($query['cid']))
	{
		$segments[] = $query['cid'];
		unset($query['cid']);
	};
	
	if(isset($query['hash']))
	{
		$segments[] = $query['hash'];
		unset($query['hash']);
	};

	return $segments;
}

function jVoteSystemParseRoute($segments)
{
	$vars = array();
	
	//Handle View and Identifier
	switch($segments[0])
	{
		case 'poll':
		{
			if(isset($segments[1])) {
				$vars['alias'] = str_replace(":", "-", $segments[1]);
			}
			$vars['view'] = 'poll';
			if(isset($segments[2])) $vars['aid'] = $segments[2];
			if(isset($segments[3])) $vars['cid'] = $segments[3];
		} break;
		
		case 'tasks':
		{
			$vars['hash'] = $segments[1];
			$vars['view'] = 'tasks';

		} break;
		
		case 'ajax':
		{
			$vars['task'] = $segments[1];
			$vars['view'] = 'ajaxjson';

		} break;
		
		case 'polls':
		{
			$vars['view'] = 'polls';
			if(isset($segments[1])) {
				$vars['cat'] = str_replace(":", "-", $segments[1]);
			
				if(isset($segments[2])) {
					$vars['alias'] = str_replace(":", "-", $segments[2]);
					$vars['view'] = 'poll';
					if(isset($segments[3])) $vars['aid'] = $segments[3];
					if(isset($segments[4])) $vars['cid'] = $segments[4];
				}
			}
		} break;

		default:
			$vars['view'] = 'polls';
			if(isset($segments[0])) {
				$vars['cat'] = str_replace(":", "-", $segments[0]);
				
				if(isset($segments[1])) {
					$vars['alias'] = str_replace(":", "-", $segments[1]);
					$vars['view'] = 'poll';
					if(isset($segments[2])) $vars['aid'] = $segments[2];
					if(isset($segments[3])) $vars['cid'] = $segments[3]; 
				}
			}
		break;

	}
	
	return $vars;
}
?>