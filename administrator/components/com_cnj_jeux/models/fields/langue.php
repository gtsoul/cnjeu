<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Supports an HTML select list of motcles
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class JFormFieldLangue extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Langue';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */        
	protected function getInput()
	{
$html[] = '<select aria-invalid="false" class="" title="" id="jform_langue" name="jform[langue]">';
($this->value == "") ? 			$html[]='<option title="" value=""></option>'               			:$html[]='<option title="" value=""></option>' ;
($this->value == "Français") ?  	$html[]='<option title="" value="Français">Français<option>'			:$html[]='<option title="" value="Français">Fançais</option>' ;
($this->value == "Espagnol") ?		$html[]='<option title="" value="Espagnol">Espagnol</option>'			:$html[]='<option title="" value="Espagnol">Espagnol</option>' ;
($this->value == "Italien") ?   	$html[]='<option title="" value="Italien">Italien</option>'			:$html[]='<option title="" value="Italien">Italien</option>' ;
($this->value == "Anglo-Américain") ? 	$html[]='<option title="" value="Anglais-Américain">Anglais-Américain</option>'	:$html[]='<option title="" value="Anglais-Américain">Anglo-Américain</option>' ;
($this->value == "Néerlandais") ?  	$html[]='<option title="" value="Néerlandais">Néerlandais</option>'		:$html[]='<option title="" value="Néerlandais">Néerlandais</option>' ;
($this->value == "Allemand") ? 		$html[]='<option title="" value="Allemand">Allemand</option>'			:              $html[]='<option title="" value=Allemand"">Allemand</option>' ;

$html[] = '</select></li><li title="">';
$html[] = '(valeur initale : '.$this->value.')';

/*
<input type="text" id="add_motcle" name="add_motcle" value="" size="60" style="float:none" />
                    <div id="motcles_propositions" class="autocomplete"></div>
                    <a id="btn_add_motcle" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_ADD_MOTCLE') . '</a>&nbsp;&nbsp;<a href="javascript:newMotcle()" style="cursor:pointer">' . JText::_('COM_CNJ_JEUX_NEW_MOTCLE') . '</a><br /><br />';
            
                $values = $list = '';
	
                if(is_array($this->value)) 
		{                    
			foreach($this->value as $value) {
                        $values .= $value->id_motcle . ',';

                        $html[] = '<div id="motcle_' . $value->id_motcle . '">&bull; ' . $value->motcle . ' <a class="btn_remove_motcle" style="cursor:pointer; color:#c00" >X</a><br /></div>';

                    }
                    $html[] = '<input type="hidden" id="list_motcles" name="' . $this->name . '" value="' . substr($values,0,-1) . '">';
                }
  */             
	 
                return implode($html);
	}
}
