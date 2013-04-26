/*
 * @package Component jVoteSystem for Joomla! 1.5 - 2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @author Johannes Me�mer
 * @copyright (C) 2010- Johannes Me�mer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
jVSQuery(document).ready(function($){
	$('table.accesstable td.col2').click(function() {
		if(joomla15) {
			var action = $(this).attr("action");
			var group  = $(this).attr("group");
			var option = $('#' + action + ' option[group="' + group + '"]');
			var state  = option.attr('selected') ? false : true; //false, wenn selected
			var value  = parseInt(option.attr('value'));
			
			$('table.accesstable td[action="' + action + '"]').each(function() {
				var val = parseInt($(this).attr('group'));
				if (val == value) 						setAccessTable(this, state, val);//angeklicktes Element �ndern
				else if (val < value && state == false)	setAccessTable(this, state, val);//alle niedrigeren Gruppen verbieten
				else if (val > value && state == true)	setAccessTable(this, state, val);//alle h�heren Gruppen erlauben
			});
		} else {
			setAccessTable(this);// bei J17 einfach nur togglen
		}
	});
});

function setAccessTable(el, override, group) {$=jVSQuery;
	if(group == undefined) var group = parseInt($(el).attr("group")); //group ausm Argument �bernehmen oder holen
	
	var action = $(el).attr("action");
	var option = $('#' + action + ' option[group="' + group + '"]');
	
	if ( ((joomla15 && group == 25) || (!joomla15 && group == 8)) && option.attr('selected') != undefined) return; //STOP wenn Super-User
	
	if (joomla15) {//J15 Stuff
		$(el).children("a").attr('class',"icon-16-"+( override ? "allow" : "deny" ));
		option.attr('selected', override);
	}
	else {//J17 Stuff
		$(el).children("a").toggleClass("icon-16-allow icon-16-deny");
		option.attr('selected', option.attr('selected') ? false : true);
	}

}

function loadAssistant(el, base, view, params, intface) {
	if(intface == undefined) var intface = "administrator";
	var content = base + "/components/com_jvotesystem/assistant/index.php?interface=" + intface + "&view=" + view;
	if(params != undefined) content = content + params;
	if(joomla15) {
		jVSQuery(el).attr('rel', "{handler: 'iframe', closeBtn: false, closable: false, size: {x: 820, y: 650}, onOpen: function(){jVSQuery('#sbox-btn-close').remove();jVSQuery('object').hide();}, onClose: function() {jVSQuery('object').show();}}").attr('href',content);
		SqueezeBox.fromElement(el);
	} else {
		SqueezeBox.open(content, {handler: 'iframe', closeBtn: false, closable: false, size: {x: 820, y: 650}, onOpen: function(){jVSQuery('#sbox-btn-close').remove();jVSQuery('object').hide();}, onClose: function() {jVSQuery('object').show();}});	
	}
}