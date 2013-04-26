<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'alfcontact.cancel' || document.formvalidator.isValid(document.id('alfcontact-form'))) {
			Joomla.submitform(task);
		}
		else {
            var msg = new Array();
            msg.push('Invalid input, please verify again!');
            //if($('email').hasClass('invalid')){
            //    msg.push('<?php echo JText::_('COM_ALFCONTACT_ERROR_INVALID_EMAIL')?>');
            //}
            alert (msg.join('\n'));
        }
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_alfcontact&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="alfcontact-form" class="form-validate">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
            <legend>
				<?php echo JText::_( 'COM_ALFCONTACT_ALFCONTACT_DETAILS' ); ?>
			</legend>
            <ul class="adminformlist">
				<?php foreach($this->form->getFieldset() as $field): ?>
                    <li><?php echo $field->label;echo $field->input;?></li>
				<?php endforeach; ?>
            </ul>
        </fieldset>
	</div>

    <input type="hidden" name="task" value="alfcontact.edit" />
    <?php echo JHtml::_('form.token'); ?>
 </form>
