<?php

/**
 * Template AUVENCE	 
 *
 * @author mploquin 
 */

// restricted access
defined( '_JEXEC' ) or die;

// init
include_once(JPATH_ROOT . '/templates/' . $this->template . '/php/function.php');
include_once(JPATH_ROOT . '/templates/' . $this->template . '/php/init.php');

?>

<!doctype html>

<!--[if IE 7]> <html class="no-js ie7 oldie" lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if IE 8]> <html class="no-js ie8 oldie" lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?php echo $this->language; ?>"> <!--<![endif]-->

	<!-- head -->
	<head>
		<jdoc:include type="head" />
		<!-- charset -->
		<meta 	charset="utf-8" />
		
		<!-- title -->		
		<title><?php echo $hp ? $app->getCfg('sitename') . ' | Centre National du Jeu' : $this->getTitle(); ;?></title>
		
		<!-- meta -->
		<meta 	name="description"	content="<?php echo $this->getDescription(); ?>" />		 
		<meta 	name="keywords"		content="<?php echo $this->getMetaData('keywords'); ?>" />
				
		<!-- favicon.ico and apple-touch-icon.png in the root directory -->
		<link rel="icon" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/favicon.ico'; ?>" />
		
		<!-- css -->
		<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
		
		<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/normalize.css'; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/layout.css'; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/general.css'; ?>">
                <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/typo.css'; ?>">
                <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/menu.css'; ?>">
                <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/module.css'; ?>">
                <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/content.css'; ?>">
                <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/colors.css'; ?>">
                <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/colors.css'; ?>">
		
                <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl . '/components/com_xmap/assets/css/xmap.css'; ?>">
		<!-- MINIMISER LES CSS A LA FIN DU SITE -->
		<!--<link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/styles.css'; ?>" media="screen">-->
		
		<!-- js -->		
		<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/js/modernizr.js'; ?>"></script>
		<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/js/jquery.js'; ?>"></script>
                <script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/js/swfobject.js'; ?>"></script>
                <script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/js/jquery.bxSlider.min.js'; ?>"></script>                
        
                <!-- analytics -->
        

	</head>
	<!--! head -->

	<!-- body -->
	<body id="page" class="<?php echo implode(' ', $class); ?>">
										
		
		<?php if($this->countModules('absolute')) : ?>
		<!-- absolute -->
		<div id="absolute">					
			<jdoc:include type="modules" name="absolute" />
		</div>					
		<?php endif; ?>
		
		<!-- header -->
		<header>
			
			<!-- container-header-menu -->
			<?php if($this->countModules('header-menu')) : ?>
			<div class="container-header-menu">
			
    			<!-- container -->
    			<div class="container">
    					
    				<!-- header-menu -->
    				<?php if($this->countModules('header-menu')) : ?>
    				<nav id="header-menu" role="navigation">										  			           
    		    		<jdoc:include type="modules" name="header-menu" />			    	
    				</nav>
    				<?php endif; ?>
    				<!--! header-menu -->
    			
    			</div>
    			<!--! container -->
			
			</div>
			<?php endif; ?>
			<!--! container-header-menu -->
			
			<!-- header-navflash -->
            <?php if($this->countModules('header-navflash')) : ?>                               
            <jdoc:include type="modules" name="header-navflash" style="wrap" />                 
            <?php endif; ?>
            <!--! header-navflash -->
                    
            <!-- navigation -->
            <?php if($this->countModules('navigation')) : ?>
            <div id="container-navigation">
                <nav id="navigation" role="navigation">                                                               
                    <jdoc:include type="modules" name="navigation" />                  
                </nav>
            </div>
            <?php endif; ?>
            <!--! navigation -->
				
		</header>
		<!--! header -->
	
		<!-- top -->
		<div id="top">
			
                    <!-- top module -->
                    <?php if($this->countModules('top')) : ?>
                    <div class="container">
                
    			<div class="top">
    				
                            <!-- container -->			
                            <jdoc:include type="modules" name="top" style="wrap"/>
                            <!--! container -->
    				
    			</div>

                    </div>
                    <?php endif; ?>
                    <!--! top module -->
				
                    <!-- slideshow -->						
                    <?php if($this->countModules('slideshow')) : ?>
                    <div class="slideshow">

                            <!-- container -->
                            <div class="container">						
                                    <jdoc:include type="modules" name="slideshow" style="wrap" />					
                            </div>
                            <!--! container -->

                    </div>			
                    <?php endif; ?>
                    <!--! slideshow -->

		</div>
		<!--! top -->
	
		<!-- top2 -->
		<div id="top2">
			
                    <!-- top2 module -->
                    <?php if($this->countModules('top2')) : ?>
                    <div class="container">
    				
                        <!-- container -->			
                        <jdoc:include type="modules" name="top2" style="wrap"/>
                        <!--! container -->

                    </div>
                    <?php endif; ?>
                    <!--! top2 module -->

		</div>
		<!--! top2 -->
			
		<!-- middle -->
		<div id="middle">
			
			<!-- container -->
			<div class="container">
			
				<!-- left -->
				<?php if($this->countModules('left')) : ?>
				<aside id="left">							
					<jdoc:include type="modules" name="left" style="wrap" />							
				</aside>						
				<?php endif; ?>
				<!--! left -->
					
				<!-- content -->
				<div id="content">					
					
					<!-- top content -->
					<?php if ($this->countModules('content-top')) : ?>
					<div id="content-top">
						<jdoc:include type="modules" name="content-top" style="various" />					
					</div>					
					<?php endif; ?>
					<!--! top content -->
					
					<!-- middle content -->
					<div id="content-middle">
						
						<!-- breadcrumb -->						
                        <jdoc:include type="modules" name="breadcrumb" />
						
						<!-- right -->
						<?php if($this->countModules('right') && $_GET['view'] != 'categorie') : ?>
						<aside id="right">							
							<jdoc:include type="modules" name="right" style="wrap" />							
						</aside>						
						<?php endif; ?>
						<!--! right -->
			
						<!-- main content -->
						<div id="main" role="main"<?php if($_GET['view'] == 'categorie') echo ' class="large"'; ?>>
							
							<!-- top main content -->
							<?php if ($this->countModules('main-top')) : ?>
							<div id="main-top">
								<jdoc:include type="modules" name="main-top" style="various" />							
							</div>							
							<?php endif; ?>
							<!--! top main content -->
							
							
							
							<!-- component -->							
							<jdoc:include type="message" />
							<jdoc:include type="component" />
							<!--! component -->
							
							<!-- bottom main content -->
							<?php if ($this->countModules('main-bottom')) : ?>
							<div id="main-bottom">
								<jdoc:include type="modules" name="main-bottom" style="various" />
							</div>		
							<?php endif; ?>
							<!--! bottom main content -->
							
						</div>
						<!--! main content -->
						
					</div>
					<!--! middle content -->
				
					<!-- bottom content -->	
					<?php if ($this->countModules('content-bottom')) : ?>
					<div id="content-bottom">
						<jdoc:include type="modules" name="content-bottom" style="various" />											
					</div>						
					<?php endif; ?>
					<!--! bottom content -->
					
				</div>
				<!--! content -->
			
			</div>
			<!--! container -->
			
		</div>
		<!--! middle -->
	
		<!-- bottom -->
		<div id="bottom">
			
			<!-- container -->
			<div class="container">
			    
			    <!-- various bottom agenda -->                       
                <?php if($this->countModules('bottom-agenda')) : ?>
                <div class="container-agenda">
                
                    <div class="bottom-agenda">
                        <jdoc:include type="modules" name="bottom-agenda" style="various" width="percent"/>
                    
                        <br style="clear: both;" />
                    </div>
                    
                    <div class="bottom-agenda-pub">
                        <jdoc:include type="modules" name="bottom-agenda-pub" style="wrap" />
                    </div>
                
                </div>
                <?php endif; ?>
                <!--! various bottom agenda -->
				
				<!-- bottom actualite -->
				<?php if($this->countModules('bottom-actualite')) : ?>
				<div class="bottom-actualite">			
					<jdoc:include type="modules" name="bottom-actualite" style="wrap"/>
				</div>			
				<?php endif; ?>
				<!--! bottom actualite -->	
				
				<br style="clear: both;" />
			
			</div>
			<!--! container -->
			
		</div>
		<!--! bottom -->
		
		<!-- footer -->				
		<footer id="footer">
			
			<!-- container -->
            <div class="container">
                
    			<!-- footer logo -->
                <nav id="footer-logo">                                           
                    <jdoc:include type="modules" name="footer-logo"/>
                </nav>
                <!--! footer-logo -->
    			
    			<!-- footer menu -->
    			<nav id="footer-menu">												
                    <jdoc:include type="modules" name="footer-menu"/>
    			</nav>
    			<!--! footer-menu -->
			
			</div>
            <!--! container -->
			
		</footer>		
		<!--! footer -->
		
		<!-- js -->
		
		<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/js/jquery.bxSlider.min.js'; ?>"></script>
		<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/js/init.js'; ?>"></script>
		<!-- MINIMISER LES JS A LA FIN DU SITE -->
		<!--<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/js/scripts.js'; ?>"></script>-->
				
		<!-- debug -->
		<jdoc:include type="modules" name="debug" />
		
	</body>
	<!--! body -->

</html>
<!--! document -->
