<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.framework');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

?>
<div class="item-page<?php echo $this->pageclass_sfx?>">

	<h2>Contact</h2>
    <h3>Un lieu de rencontre et de vie</h3>

<?php
	
$app = JFactory::getApplication();
// get some menu parameters
$menu = $app->getMenu()->getActive();
$this->defcontact = (int)$menu->params->get('defcontact');
$this->showpageheading = (int)$menu->params->get('show_page_heading', 1);
$this->pageheading = $menu->params->get('page_heading');
//Escape strings for HTML output
$this->pageclass_sfx = htmlspecialchars($menu->params->get('pageclass_sfx'));

$user = JFactory::getUser();
$document = JFactory::getDocument();
$params = JComponentHelper::getParams( 'com_alfcontact' );
	
$emailto_id = 99;
$title = $params->get('title', '');
$header = $params->get('header', '');
$footer = $params->get('footer', '');
$copyme = $params->get('copytome', 1);
$autouser = $params->get('autouser', 1);
$captcha = $params->get('captcha', 0);
$captchatype = $params->get('captchatype', 1);
$captchatheme = $params->get('captchatheme', 'red');
$captchalng = $params->get('captchalng', 'en');
$publickey = $params->get('publickey', '');
$privatekey = $params->get('privatekey', '');
$captchas_user = $params->get('captchas_user', 'demo');
$captchas_key = $params->get('captchas_key', 'secret');
$captchas_alphabet = $params->get('captchas_alphabet', 'abcdefghijklmnopqrstuvwxyz');
$captchas_chars = $params->get('captchas_chars', '6');
$captchas_width = $params->get('captchas_width', '240');
$captchas_height = $params->get('captchas_height', '80');
$captchas_color = $params->get('captchas_color', '000000');
$captchas_audiolink = $params->get('captchas_audiolink', 0);
$captchas_audiolng = $params->get('captchas_audiolng', 'en');
$clientSideValidation = $params->get('clientSideValidation');
        
$myPath = 'components/com_alfcontact/views/alfcontact/tmpl';
if ($params->get('useComponentCSS')) {
	switch ($params->get('cssTheme')) {
		case 'basic':
            $document->addStyleSheet("$myPath/base.css");
            break;
        case 'html5clean':
            $document->addStyleSheet("$myPath/base.css");
            $document->addStyleSheet("$myPath/advanced.css");
            $document->addStyleSheet("$myPath/silver.css");
            break;
        case 'html5cleanRed':
            $document->addStyleSheet("$myPath/base.css");
            $document->addStyleSheet("$myPath/advanced.css");
            $document->addStyleSheet("$myPath/red.css");
			break;
        case 'html5cleanGreen':
            $document->addStyleSheet("$myPath/base.css");
			$document->addStyleSheet("$myPath/advanced.css");
            $document->addStyleSheet("$myPath/green.css");
            break;
        case 'html5cleanBlue':
            $document->addStyleSheet("$myPath/base.css");
            $document->addStyleSheet("$myPath/advanced.css");
            $document->addStyleSheet("$myPath/blue.css");
            break;
        case 'custom':
            $document->addStyleSheet("index.php?option=com_alfcontact&view=customcss");
			break;
    }
}
     
$params->get('useBaseCSS') &&
    $document->addStyleSheet("$myPath/base.css");
$params->get('useAdvancedCSS') &&
        $document->addStyleSheet("$myPath/advanced.css");
	
$document->addScript("$myPath/contact-form.js");
        
$captchas_random_path = JPATH_SITE . '/tmp/captchasnet-random-strings';
	
if ($captchatype == 1){
	require_once(JPATH_COMPONENT_SITE . '/captchasdotnet.php');
	$captchas = new CaptchasDotNet ($captchas_user, $captchas_key, $captchas_random_path, '3600',
                               $captchas_alphabet, $captchas_chars, $captchas_width, $captchas_height, $captchas_color);
	$audiolink = $captchas->audio_url();
	$audiolink = $audiolink	. '&language=' . $captchas_audiolng ;
}?>






<script language="javascript" type="text/javascript">
	<!--
	var RecaptchaOptions = {theme : '<?php echo $captchatheme; ?>', lang : '<?php echo $captchalng; ?>'};
    var ALFContact = {validate: <?php echo $clientSideValidation ? 'true' : 'false' ?>};
	//-->
</script>

<?php if ($this->showpageheading) : ?>
	<h2 class="contentheading">
	<?php echo $this->escape($this->pageheading); ?>
	</h2>
<?php endif; ?>

<?php if ($title != '') : ?>
    <h2 class="contentheading"><a href=""><?php echo $title; ?></a></h2>
<?php endif; ?>
	<p><?php echo $header; ?></br></p>
    <form action="<?php echo JRoute::_('index.php?option=com_alfcontact'); ?>" method="post" name="adminForm" id="contact-form"<?php echo $clientSideValidation ? 'class="form-validate"' : '' ?>>
    <table>
      	<tr>
        	<td style="font-weight:bold"><label for="name"><?php echo JText::_('COM_ALFCONTACT_FORM_FIRSTNAME') ?></label></td>
        	<td style="font-weight:bold"><label for="email"><?php echo JText::_('COM_ALFCONTACT_FORM_FROM') ?></label></td>
      	</tr>
      	<tr>
          	<td><input class="required" name="firstname" id="firstname" type="text" size="30" value="Votre prénom"/></td>
    		<td><input class="required" name="name" id="name" type="text" size="30" value="Votre nom"/></td>
        </tr>
        <tr>
            <td style="font-weight:bold"><label for="email"><?php echo JText::_('COM_ALFCONTACT_FORM_EMAIL') ?></label></td>
    		<td style="font-weight:bold"><label for="name"><?php echo JText::_('COM_ALFCONTACT_FORM_TELEPHONE') ?></label></td>
        </tr>
      	<tr>
          	<td><input class="required validate-email" name="email" id="email" type="text" size="30" value="Votre e-mail"/></td>
    		<td><input class="" name="telephone" id="telephone" type="text" size="30" value="Votre téléphone"/></td>
        </tr>
        <tr>
            <td style="font-weight:bold" colspan="2"><label for="email"><?php echo JText::_('COM_ALFCONTACT_FORM_SUBJECT') ?></label></td>
        </tr>
      	<tr>
          	<td colspan="2">
          		<select name="emailid" id="emailid">
                	<?php foreach ($this->items as $i => $item) { ?>
                    	<?php if ($item->id == $this->defcontact) { ?>
                            <option value="<?php echo $item->id . ',' . $item->extra . ',' . $item->extra2 . ',' . $item->defsubject; ?>" selected="selected"><?php echo $item->name; ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $item->id . ',' . $item->extra . ',' . $item->extra2 . ',' . $item->defsubject; ?>" ><?php echo $item->name; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
			</td>
        </tr>
        <tr>
            <td style="font-weight:bold" colspan="2"><label for="email"><?php echo JText::_('COM_ALFCONTACT_FORM_MESSAGE') ?></label></td>
        </tr>
      	<tr>
          	<td colspan="2"><textarea class="required" name="message" id="contact-form-message" cols="72" rows="10" ><?php echo isset($this->message) ? $this->message : ''; ?></textarea></td>
        </tr>
    	<tr>
          	<td colspan="2" style="text-align:left">
    			<input type="checkbox" name="copy" id="copy"<?php echo (isset($this->copy) && $this->copy) ? ' checked=""' : '' ?> / style="width:16px;line-height:2em;padding:0;margin:3px;vertical-align:middle;border:none"><label for="copy"><span><?php echo JText::_('COM_ALFCONTACT_FORM_COPYTOME') ?></span></label></td>
        </tr>
                <tr>
                    <td></td>
                    <td><button class="button">
                <?php echo JText::_('COM_ALFCONTACT_FORM_SEND'); ?>
            </button></td>
                </tr>
    
    
    
    </table>
    </br></br></br></br>
    <span style="font-size:8.5px">(* : les champs marqu&eacute;s d'un asterisque sont obligatoires)</span>
    <!--
    <hr /><hr /><hr />
    <table>
        <tr>
            <th><label for="name"><?php echo JText::_('COM_ALFCONTACT_FORM_FROM') ?></label></th>
            <td>
				<?php if (!$autouser OR ($autouser AND !$user->name)) { ?>
					<input class="required" name="name" id="name" type="text" size="30" value="<?php echo isset($this->name) ? $this->name : ''; ?>"/>
				<?php } else { ?>
					<span><?php echo $user->name; ?></span>
					<input type="hidden" name="name" id="name" value= "<?php echo $user->name; ?>" /> 
				<?php } ?>
			</td>
        </tr>
        <tr>
            <th><label for="email"><?php echo JText::_('COM_ALFCONTACT_FORM_EMAIL') ?></label></th>
            <td>
                <?php if (!$autouser OR ($autouser AND !$user->email)) { ?>
                    <input class="required validate-email" name="email" id="email" type="text" size="30" value="<?php echo isset($this->email) ? $this->email : ''; ?>"/>
                <?php } else { ?>
                    <span><?php echo $user->email; ?></span>
                    <input type="hidden" name="email" id="email" value= "<?php echo $user->email; ?>" />
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><hr /></td>
        </tr>
        <tr>
            <th><label for="emailid"><?php echo JText::_('COM_ALFCONTACT_FORM_TO') ?></label></th>
            <td>
                <?php if (count($this->items) > 1) { ?>
                    <select name="emailid" id="emailid">
                        <?php foreach ($this->items as $i => $item) { ?>
                            <?php if ($item->id == $this->defcontact) { ?>
                                <option value="<?php echo $item->id . ',' . $item->extra . ',' . $item->extra2 . ',' . $item->defsubject; ?>" selected="selected"><?php echo $item->name; ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $item->id . ',' . $item->extra . ',' . $item->extra2 . ',' . $item->defsubject; ?>" ><?php echo $item->name; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                <?php } else { ?>
                    <?php if (count($this->items) == 0) { ?>
                        <span><?php echo $app->getCfg('fromname'); ?></span>
                        <input type="hidden" name="emailid" id="emailid" value="99,," />
                    <?php } else { ?>
                        <span><?php echo $this->items[0]->name; ?></span>
                        <input type="hidden" id="emailid" name="emailid" value="<?php echo $this->items[0]->id . ',' . $this->items[0]->extra . ',' . $this->items[0]->extra2 . ',' . $this->items[0]->defsubject; ?>" />
                    <?php } ?>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th><label for="subject"><?php echo JText::_('COM_ALFCONTACT_FORM_SUBJECT') ?></label></th>
            <td><input class="required" name="subject" id="subject" type="text" value="<?php echo isset($this->subject) ? $this->subject : ''; ?>"/></td>
        </tr>
        <tr>
            <th><label for="contact-form-message"><?php echo JText::_('COM_ALFCONTACT_FORM_MESSAGE') ?></label></th>
            <td><textarea class="required" name="message" id="contact-form-message" cols="50" rows="10" ><?php echo isset($this->message) ? $this->message : ''; ?></textarea></td>
        </tr>
        <tr id="extrarow">
            <th><label for="extravalue"><span id="extraname">empty:</span></label></th>
            <td><input name="extravalue" id="extravalue" type="text" size="30" value="<?php echo isset($this->extravalue) ? $this->extravalue : ''; ?>"/></td>
        </tr>
        <tr id="extra2row">
            <th><label for="extra2value"><span id="extra2name">empty:</span></label></th>
            <td><input name="extra2value" id="extra2value" type="text" size="30" value="<?php echo isset($this->extra2value) ? $this->extra2value : ''; ?>"/></td>
        </tr>
        <?php if ($copyme == 1) { ?>
        <tr>
            <th><input type="checkbox" name="copy" id="copy"<?php echo (isset($this->copy) && $this->copy) ? ' checked=""' : '' ?> /></th>
            <td><label for="copy"><span><?php echo JText::_('COM_ALFCONTACT_FORM_COPYTOME') ?></span></label></td>        </tr>
        <?php } ?>
	<?php			
            if (($captcha == 1) OR (($captcha == 2) AND (!$user->name))) { 
                if ($captchatype == 0)  {
                    require_once(JPATH_COMPONENT_SITE . '/recaptchalib.php');
					$use_ssl = false;
					if ((isset($_SERVER['HTTPS']) &&	($_SERVER['HTTPS'] == 'on')) ||	getenv('SSL_PROTOCOL_VERSION')){
						$use_ssl = true;
					}?>
                <tr>
                    <td></td>
                    <td><?php echo recaptcha_get_html($publickey, $use_ssl); ?></td>
                </tr>
                <?php } else { ?>
                <tr>
                    <td><input type="hidden" name="captchas_random" id="captchas_random" value="<?php echo $captchas->random(); ?>" /></td>
                    <td><?php
                        echo $captchas->image(); 
                        if ($captchas_audiolink == 1) { ?> 
                            <br />
                            <a href="<?php echo $audiolink; ?>"><?php echo JText::_('COM_ALFCONTACT_CAPTCHAS_SPELLING')?></a>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="captchas_entry"><?php echo JText::_('COM_ALFCONTACT_CAPTCHAS_VERIFICATION')?></label></th>
                    <td><input type="text" name="captchas_entry" class="required" id="captchas_entry" /></td>
                </tr>
                <?php
                }
            } ?>
        </table>
        <p>
            <button class="button">
                <?php echo JText::_('COM_ALFCONTACT_FORM_SEND'); ?>
            </button>
        </p>-->
        <input type="hidden" name="option" value="com_alfcontact" />
        <input type="hidden" name="task" value="sendemail" />
        <input type="hidden" name="emailto_id" id="emailto_id" value= "<?php echo $emailto_id; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </form> 
    <div><p><?php echo $footer; ?></p></div>
</div>  
