<?php
// CDC J FLOATING BAR 1.1
// Created Jan 01 2012
// Author Robert Trudeau
// @copyright http://www.citeducommerce.com
// @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php if($params->get('nMargin')) : ?>

<style>body {margin-<?php echo $params->get('nMarginPosition'); ?>: <?php echo $params->get('nMargin'); ?>px !important;}</style>
<?php endif ; ?>

<div class="cdc_jfloating_bar" style="position:<?php echo $params->get('moduleposition'); ?>;">
<?php if($params->get('nWidth == ')) : ?>
<div style="width: auto; left: 0; right: 0;">
<?php else : ?>
<div style="width: <?php echo $params->get('nWidth'); ?>; margin-left: auto; margin-right: auto;">
<?php endif; ?>
	<div class="w1" style="background-color:<?php echo $params->get('cdc_fbg'); ?>;">
		<div class="w2"  style="background-color:<?php echo $params->get('cdc_fbg'); ?>;">
			<div class="w3"  style="background-color:<?php echo $params->get('cdc_fbg'); ?>;">




<!-- HOME  -->



<?php if($params->get('urlhome')) : ?>
<?php 
     		$itemid =  $params->get('urlhome');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-home" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL_TITLE_HOME') ?>"></a>  <div class="v-line"></div>
		<?php endif ;?>


			<?php if($params->get('url1')) : ?>
<?php 
     		$itemid =  $params->get('url1');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-url1" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL1_TITLE') ?>"></a>  <div class="v-line"></div>
		<?php endif ;?>
          
            <?php if($params->get('url2')) : ?>
<?php 
     		$itemid =  $params->get('url2');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-url2" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL2_TITLE') ?>"></a>  <div class="v-line"></div>
		<?php endif ;?>
          
            <?php if($params->get('url3')) : ?>
<?php 
     		$itemid =  $params->get('url3');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-url3" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL3_TITLE') ?>"></a> <div class="v-line"></div>
		<?php endif ;?>
            <?php if($params->get('url4')) : ?>
<?php 
     		$itemid =  $params->get('url4');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-url4" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL4_TITLE') ?>"></a> <div class="v-line"></div>
		<?php endif ;?>
           
            <?php if($params->get('url5')) : ?>
<?php 
     		$itemid =  $params->get('url5');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-url5" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL5_TITLE') ?>"></a> <div class="v-line"></div>
		<?php endif ;?>
           
            
            <?php if($params->get('url6')) : ?>
<?php 
     		$itemid =  $params->get('url6');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-url6" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL6_TITLE') ?>"></a>  <div class="v-line"></div>
		<?php endif ;?>
          

<?php if($params->get('url7')) : ?>
<?php 
     		$itemid =  $params->get('url7');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-url7" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL7_TITLE') ?>"></a>
          <div class="v-line"></div>
		<?php endif ;?>
    <!-- END HOME + URL1 to URL7 -->       
	
   




<!-- CONTACT -->

<!-- CONTACT - If vContact and iContact then vContact for visitors and iContact for members-->
 <?php if($params->get('vContacts') && $params->get('iContacts')) : ?>
 <?php if($type == 'login') : ?>
 <?php 
     		$itemid =  $params->get('vContacts');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid);
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-contact" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL_TITLE_CONTACT') ?>"></a> <div class="v-line"></div>
         
		<?php endif ;?>
        
        
        
        <?php if($type == 'logout') : ?>
	<a href="mailto:<?php echo  $params->get('iContacts') ; ?>" class="cdc-contact" title="<?php echo JText::_('CDC_URL_TITLE_CONTACT') ?>"></a>
<?php endif; ?> 
<?php else: ?>

<!-- CONTACT - If only vContact then vContact for both visitors and members-->
<?php if($params->get('vContacts')) : ?>
 <?php if($type == 'login') : ?>
<?php 
     		$itemid =  $params->get('vContacts');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-contact" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL_TITLE_CONTACT') ?>"></a> <div class="v-line"></div>
         
		<?php endif ;?>
 <?php if($type == 'logout') : ?>
 <?php 
     		$itemid =  $params->get('vContacts');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-contact" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL_TITLE_CONTACT') ?>"></a> <div class="v-line"></div>
 <?php endif; ?>
<?php else : ?>


<!-- CONTACT - if only iContact then iContact for both visitor and members-->

<?php if($params->get('iContacts')) : ?>

 <?php if($type == 'login') : ?>
<a href="mailto:<?php echo  $params->get('iContacts') ; ?>" class="cdc-contact" title="<?php echo JText::_('CDC_URL_TITLE_CONTACT') ?>"></a>
		<?php endif ;?>
 <?php if($type == 'logout') : ?>
 <a href="mailto:<?php echo  $params->get('iContacts') ; ?>" class="cdc-contact" title="<?php echo JText::_('CDC_URL_TITLE_CONTACT') ?>"></a>
 
 <?php endif; ?>
<?php endif; ?>
      <?php endif; ?> 
       <?php endif; ?>   
<!-- END CONTACT-->


       




<!-- SOCIAL NETWORKS -->
<?php if($params->get('iTwit')) : ?>
	<a class="cdc-twit" href="<?php echo $params->get('iTwit'); ?>" target="_blank" title="<?php echo JText::_('CDC_URL_TITLE_TWITTER') ?>"></a>
<?php endif; ?>
<?php if($params->get('iFbook')) : ?>
	<a class="cdc-fbook" href="<?php echo $params->get('iFbook'); ?>" target="_blank" title="<?php echo JText::_('CDC_URL_TITLE_FACEBOOK') ?>"></a>
<?php endif; ?>
<?php if($params->get('iLinked')) : ?>
	<a class="cdc-linkedin" href="<?php echo $params->get('iLinked'); ?>" target="_blank" title="<?php echo JText::_('CDC_URL_TITLE_LINKEDIN') ?>"></a>
<?php endif; ?>
<?php if($params->get('iYoutube')) : ?>
	<a class="cdc-youtube" href="<?php echo $params->get('iYoutube'); ?>" target="_blank" title="<?php echo JText::_('CDC_URL_TITLE_YOUTUBE') ?>"></a>
<?php endif; ?>
<?php if($params->get('iMySpace')) : ?>
	<a class="cdc-myspace" href="<?php echo $params->get('iMySpace'); ?>" target="_blank" title="<?php echo JText::_('CDC_URL_TITLE_MYSPACE') ?>"></a>
<?php endif; ?>
<?php if($params->get('iTwit') or $params->get('iFbook') or $params->get('iLinked') or $params->get('iYoutube') or $params->get('iMySpace')) : ?>
<div class="v-line"></div>
<?php else : ?>
<?php endif; ?>
<!-- END SOCIAL NETWORKS -->


<!-- THIRD PARTY EXTENSIONS ICONS LINKS -->
<?php if($type == 'logout') : ?>



 <?php if($params->get('view_profile')) : ?>
<?php 
     		$itemid =  $params->get('view_profile');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="profile" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL5_TITLE') ?>"></a> <div class="v-line"></div>
		<?php endif ;?>

 <?php if($params->get('edit_profile')) : ?>
<?php 
     		$itemid =  $params->get('edit_profile');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="editprofile" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL5_TITLE') ?>"></a> <div class="v-line"></div>
		<?php endif ;?>




<?php if($params->get('jomSocial')) : ?>
	<a class="profile" href="<?php echo JRoute::_( 'index.php?option=com_community&view=profile' ); ?>" target="_self" title="<?php echo JText::_('CDC_URL_TITLE_MYPROFILE') ?>"></a>

	<a class="editprofile" href="<?php echo JRoute::_( 'index.php?option=com_community&view=profile&task=edit' ); ?>" target="_self" title="<?php echo JText::_('CDC_URL_TITLE_EDITPROFILE') ?>"></a>
    
<a class="jomsocialeditdetails" href="<?php echo JRoute::_( 'index.php?option=com_community&view=profile&task=editDetails' ); ?>" title="<?php echo JText::_('CDC_URL_TITLE_EDITPROFILEDETAILS') ?>"></a>
<div class="v-line"></div>
<?php endif; ?>


<?php if($params->get('mTree')) : ?>
    <a class="mTree_adsview" href="<?php echo JRoute::_( 'index.php?option=com_mtree&task=mypage' ); ?>" target="_self" title="<?php echo JText::_('CDC_URL_TITLE_MYADS') ?>"></a>
	<a class="mTree_ad" href="<?php echo JRoute::_( 'index.php?option=com_mtree&task=addlisting&cat_id=0&Itemid=25' ); ?>" target="_self" title="<?php echo JText::_('CDC_URL_TITLE_NEWADS') ?>"></a>
    <div class="v-line"></div>
<?php endif; ?>

<!-- V_CART -->

<?php if($params->get('iCart')) : ?>
<?php
if (isset($_SESSION['cart'])) $cart = $_SESSION['cart'];
$total = 0;
	if (isset($cart)) {
		foreach($cart as $key => $item) {
		$total += $item['quantity'];
	}
};
if ($total > 0) $totalString = '(<strong>'.$total.'</strong> items)';
else $totalString = "Cart is empty";
?>
	<?php if($total > 0) : ?>
	<a class="cdc-cart" href="<?php echo JRoute::_( 'index.php?option=com_virtuemart&page=shop.cart' ); ?>" title="<?php echo JText::_('CDC_URL_TITLE_CARTFULL') ?>"><?php echo $totalString; ?></a>
	<?php else : ?>
	<div class="cdc-cartempty"><?php echo JText::_('CDC_URL_TITLE_CARTEMPTY') ?></div>
    <div class="v-line"></div>
	<?php endif; ?>
<?php endif; ?>
<!-- END V_CART -->





<!-- LOGIN FORM -->
<div class="cdc-loginform">

<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form">
<div>	
     <input type="image" src="<?php echo $ipath ?>tmpl/images/blank.png" name="Submit" class="cdc-outbtn" title="<?php echo JText::_('CDC_URL_TITLE_LOGOUT') ?>" />
     </div>
	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<?php else : ?>


<?php if($params->get('loginpage')) : ?>
<?php 
     		$itemid =  $params->get('loginpage');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); 
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="cdc-url7" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL7_TITLE') ?>"></a>
          <div class="v-line"></div>
		<?php endif ;?>



<?php if($params->get('activelogin')) : ?>
<?php if(JPluginHelper::isEnabled('authentication', 'openid')) :
		$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
		$langScript = 	'var JLanguage = {};'.
						' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
						' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
						' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
						' var modlogin = 1;';
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration( $langScript );
		JHTML::_('script', 'openid.js');
endif; ?>

<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
<div class="ffields">

		<input name="username" type="text" class="uninput" id="modlgn-username" title="username" onfocus="if (this.value == 'Identifiant') this.value = '';" onblur="if (this.value == '') this.value = 'Identifiant';" value="Identifiant" />
		
        <input name="password" type="password" class="pwinput" id="modlgn-passwd" title="password" onfocus="if (this.value == 'motdepasse') this.value = '';" onblur="if (this.value == '') this.value = 'motdepasse';" value="motdepasse" />
	<div class="stayin">
	
<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
		
        <input id="modlgn-remember" type="checkbox" name="remember" value="yes" title="Check this box to stay logged in" />
	
<?php endif; ?>
	</div>
	<input type="image" src="<?php echo $ipath ?>tmpl/images/blank.png" name="Submit" class="fbtn" title="<?php echo JText::_('CDC_URL_TITLE_LOGIN') ?>" />
  
</div>

<!-- CONNEXION BOX-->



	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
	
<?php endif; ?>

<?php if($params->get('registerlinkactive')) : ?>
<?php if($params->get('urlregister')) : ?>
<?php 
     		$itemid =  $params->get('urlregister');
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); //var_dump($menu);die;
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				  ;?>
		 <a class="reg" href="<?php echo $url; ?>" target="_self" title="<?php echo JText::_('CDC_URL_TITLE_SUSCRIBE') ?>"></a>  <div class="v-line"></div>
		
 <?php  else : ?>
	<?php $usersConfig = &JComponentHelper::getParams( 'com_users' );
	if ($usersConfig->get('allowUserRegistration')) : ?>
<a class="reg" href="<?php echo JRoute::_( 'index.php?option=com_users&view=registration' ); ?>" title="<?php echo JText::_('CDC_URL_TITLE_SUSCRIBE') ?>"></a>  
<?php endif; ?>

<?php endif; ?>

<?php endif; ?>



<?php endif; ?>

</div>
<!-- END LOGIN FORM -->

	</div>
		</div>
	</div>
</div>
</div>	
</div>