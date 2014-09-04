<?php
/**
 *	iCagenda
 *----------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright	Copyright (C) 2012-2013 JOOMLIC - All rights reserved.

 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jooml!C - http://www.joomlic.com
 *
 * @update		2013-05-28
 * @version		2.1.14
 *----------------------------------------------------------------------------
*/

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

class JFormFieldModal_icfile extends JFormField
{
	public $type = 'modal_icfile';


	protected static $initialised = false;

	/**
	 * Method to get the field input markup for a media selector.
	 * Use attributes to identify specific created_by and asset_id fields
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$assetField = $this->element['asset_field'] ? (string) $this->element['asset_field'] : 'asset_id';
		$authorField = $this->element['created_by_field'] ? (string) $this->element['created_by_field'] : 'created_by';
		$asset = $this->form->getValue($assetField) ? $this->form->getValue($assetField) : (string) $this->element['asset_id'];
		if ($asset == '')
		{
			$asset = JRequest::getCmd('option');
		}

		$link = (string) $this->element['link'];
		if (!self::$initialised)
		{

			// Load the modal behavior script.
			JHtml::_('behavior.modal');

			// Build the script.
			$script = array();
			$script[] = '	function jInsertFieldValue(value, id) {';
			$script[] = '		var old_id = document.id(id).value;';
			$script[] = '		if (old_id != id) {';
			$script[] = '			var elem = document.id(id)';
			$script[] = '			elem.value = value;';
			$script[] = '			elem.fireEvent("change");';
			$script[] = '		}';
			$script[] = '	}';

			// Add the script to the document head.
			JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

			self::$initialised = true;
		}

		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->element['accept'] ? ' accept="' . (string) $this->element['accept'] . '"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// The text field.
		if ($this->value == NULL) {
			$html[] = '<span>';
			$html[] = '	<input type="file" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . ' readonly="readonly"' . $attr . ' />';
			$html[] = '</span>';
		} else {
			$html[] = '<span>';
			$html[] = '	<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . ' readonly="readonly"' . $attr . ' />';
			$html[] = '</span>';
		}


		$folder = 'icagenda_doc';
		// The button.
//		$html[] = '<div class="button2-left">';
//		$html[] = '	<div class="blank">';
//		$html[] = '		<a class="modal" title="' . JText::_('JLIB_FORM_BUTTON_SELECT') . '"' . ' href="'
//			. ($this->element['readonly'] ? ''
//			: ($link ? $link
//				: 'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=' . $asset . '&amp;author='
//				. $this->form->getValue($authorField)) . '&amp;fieldid=' . $this->id . '&amp;folder=' . $folder) . '"'
//			. ' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
//		$html[] = JText::_('JLIB_FORM_BUTTON_SELECT') . '</a>';
//		$html[] = '	</div>';
//		$html[] = '</div>';

		if ($this->value == NULL) {
		$html[] = '<div class="button2-left">';
		$html[] = '	<div class="blank">';
		$html[] = '		<a title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
		$html[] = 'document.id(\'' . $this->id . '\').value=\'\';';
		$html[] = 'document.id(\'' . $this->id . '\').fireEvent(\'change\');';
		$html[] = 'return false;';
		$html[] = '">';
		$html[] = JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
		$html[] = '</div>';
		$html[] = '</div>';
		} else {
		$html[] = '<div class="button2-left">';
		$html[] = '	<div class="blank">';
		$html[] = '		<a title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
		$html[] = 'document.id(\'' . $this->id . '\').value=\'\';';
		$html[] = 'document.id(\'' . $this->id . '\').fireEvent(\'change\');';
		$html[] = 'return false;';
		$html[] = '">';
		$html[] = JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
		$html[] = '</div>';
		$html[] = '</div>';
		}

		return implode("\n", $html);







	}
}
