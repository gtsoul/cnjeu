/**
 * @package Component jVoteSystem for Joomla! 1.5-2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @authors Johannes Meßmer, Andreas Fischer
 * @copyright (C) 2010 - 2012 Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

function jVSloadSqueezebox(el, link, width, height) {
	if(width == undefined) var width = 820;
	if(height == undefined) var height = 650;
	if(joomla15) {
		jQuery(el).attr('rel', "{handler: 'iframe', closeBtn: false, closable: false, size: {x: " + width + ", y: " + height + "}, onOpen: function(){jQuery('#sbox-btn-close').remove();jQuery('object').hide();}, onClose: function() {jQuery('object').show();}}").attr('href',link);
		SqueezeBox.fromElement(el);
	} else {
		SqueezeBox.open(link, {handler: 'iframe', closeBtn: false, closable: false, size: {x: width, y: height}, onOpen: function(){jQuery('#sbox-btn-close').remove();jQuery('object').hide();}, onClose: function() {jQuery('object').show();}});	
	}
}