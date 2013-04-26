


window.addEvent('domready', function() 
{
    function delete_item(value, el) {
        var values = value.split(',');
        var tmp_values = new Array();
        var id = el.get('id');
        id = id.substring(id.indexOf('_')+1, id.length);
        for(var i=0 ; i<values.length ; i++) {
            if(values[i] != id && values[i] != '') {
                tmp_values.push(values[i]);
            }
        }
        el.remove();
        return tmp_values.join(',');
    }




function utf8_encode (argString) {
    // Encodes an ISO-8859-1 string to UTF-8  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/utf8_encode    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: sowberry
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman    // +   improved by: Yves Sucaet
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Ulrich
    // +   bugfixed by: Rafal Kukawski
    // *     example 1: utf8_encode('Kevin van Zonneveld');    // *     returns 1: 'Kevin van Zonneveld'
    if (argString === null || typeof argString === "undefined") {
        return "";
    }
     var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
    var utftext = "",
        start, end, stringl = 0;
 
    start = end = 0;    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;
         if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.slice(start, end);            }
            utftext += enc;
            start = end = n + 1;
        }
    } 
    if (end > start) {
        utftext += string.slice(start, stringl);
    }
     return utftext;
}


/*
function utf8_encode(string)
{
	return string;
}
*/
    function add_item(item) {

    	var newvalue = $('add_' + item).value;
        var id = newvalue.substring(newvalue.indexOf('#')+1, newvalue.length);
        var name = newvalue.substring(0, newvalue.indexOf(' - #'));

	var liste_qualite_reference = new Array(

	"Editeur - Distributeur",
	"Imprimerie",
	"Organisme",
	"Licence",
	"Revue",
	"Distributeur",
	"Producteur (licence)",
	"Importateur",
	"Editeur",
	"Fabricant",
	"Marque",
	"Client",
	"Participation",
	"Cr&eacute;ation",
	"D&eacute;veloppement",
	"Cartier","");



	var liste_qualite_auteur = new Array(
				"Auteur",
				"Auteur JDR",
				"Collaboration",
				"Coloriste",
				"Cr&eacute;ation - Edition",
				"Cr&eacute;ateur - Designer",
				"Dessinateur",
				"Directeur de publication",
				"D&eacute;veloppement",
				"R&eacute;alisation Graphique",
				"Historien du jeu",
				"Id&eacute;e",
				"Illustration - graphisme",
				"Licence",
				"Mise en page",
				"Photographe",
				"Pr&eacute;facier",
				"Recherche - Compilation",
				"R&eacute;daction",
				"Sc&eacute;nariste",
				"Syst&egrave;me de jeu",
				"Traducteur",
				"Textes","" );

        if(id && name) 
        {                    
            // on ajoute le nouvel element si il n'existe pas deja
          /*  if(!$(item + '_' + id))*/ {
                var newDiv = new Element('div', {
                    'id': item + '_' + id
                });


                if(item=='auteur')
		{

			var endOfInput ="";
			for (var i=0;i< liste_qualite_auteur.length;i++)
			{
				endOfInput +=  '<option value="'+utf8_encode(liste_qualite_auteur[i])+'">'+liste_qualite_auteur[i]+'</option>';
			} 
			endOfInput +='</select><input type="hidden" name="jform[auteur_qualites_old][]" value="" /> <a class="btn_remove_'+item+'" style="cursor:pointer; color:#c00">X</a><br />';
        		newDiv.innerHTML = '&bull; ' + name + ' - en tant que :  <select name="jform[auteur_qualites][]"  style="float:none" >'+endOfInput ;
		}
                else if(item=='distinction') 
                    newDiv.innerHTML = '&bull; ' + name + ' - Date : <input type="text" name="jform[date_distinctions][]" value="" style="float:none" /> <a class="btn_remove_'+item+'" style="cursor:pointer; color:#c00">X</a><br />';
                else if(item=='document') 
                    newDiv.innerHTML = '&bull; ' + name + ' - Ordre : <input type="text" name="jform[ordres][]" value="" style="float:none" /> <a class="btn_remove_'+item+'" style="cursor:pointer; color:#c00">X</a>&nbsp;&nbsp;<a href="../voirDocument.php?id='+id+'" target="_blank" style="cursor:pointer; color:#c00" onClick="">voir</a><br />';

              else if(item=='motcle') 
		{
                    newDiv.innerHTML = '&bull; ' + name + '<a class="btn_remove_'+item+'" style="cursor:pointer; color:#c00">X</a><br />';
		}

              else if(item=='mecanisme') 
		{
                    newDiv.innerHTML = '&bull; ' + name + '<a class="btn_remove_'+item+'" style="cursor:pointer; color:#c00">X</a><br />';
		}
 

                else 
		{

			var endOfInput ="";
			for (var i=0;i< liste_qualite_reference.length;i++)
			{
				endOfInput +=  '<option value="'+utf8_encode(liste_qualite_reference[i])+'">'+liste_qualite_reference[i]+'</option>';
			} 
			endOfInput +='</select><input type="hidden" name="jform[reference_qualites_old][]" value="" /> <a class="btn_remove_'+item+'" style="cursor:pointer; color:#c00">X</a><br />';
        		newDiv.innerHTML = '&bull; ' + name + ' - en tant que :  <select name="jform[reference_qualites][]"  style="float:none" >'+endOfInput ;
	
/*






newDiv.innerHTML = '&bull; ' + name + ' - Qualit&eacute; : <input type="text" name="jform[reference_qualites][]" value="" style="float:none" /> <a class="btn_remove_'+item+'" style="cursor:pointer; color:#c00">X</a><br />';
*/
		}

        	$('list_'+item+'s').value = $('list_'+item+'s').value + ',' + id;
   
         	newDiv.inject($('list_'+item+'s'), 'before');

        // on dÈfinit la possibilitÈ de supprimer le nouvel element;
                $(item+'_' + id).addEvent('click:relay(a.btn_remove_'+item+')', function(event){
                    $('list_'+item+'s').value = delete_item($('list_'+item+'s').value, $(item+'_' + id));
                });
            }
          //  else alert('Element deja associe');

            // on vide le champ d'autocompletion
            $('add_'+item).value = '';
        }
    }

    /********** AUTEURS **********/ 
    if($('btn_add_auteur')) {

	


        new Ajax.Autocompleter(
        "add_auteur",   // id du champ de formulaire
        "auteurs_propositions",  // id de l'element utilise pour les propositions
        "../autocompletion-get-auteurs.php",  // URL du script c√?t√© serveur
        {
            paramName: 'add_auteur',  // Nom du parametre recu par le script serveur
            minChars: 2   // Nombre de caracteres minimum avant que des appels serveur ne soient effectu√©s
        });
        $('btn_add_auteur').addEvent('click', function(e){
            add_item('auteur');
        });
        $$('.btn_remove_auteur').each(function(el){
            el.addEvent('click', function(event){
                $('list_auteurs').value = delete_item($('list_auteurs').value, el.getParent());
            });
        });
    }
    

    /********** REFERENCES **********/ 
    if($('btn_add_reference')) {
	new Ajax.Autocompleter(
        "add_reference",   // id du champ de formulaire
        "references_propositions",  // id de l'element utilise pour les propositions
        "../autocompletion-get-references.php",  // URL du script c√?t√© serveur
        {
            paramName: 'add_reference',  // Nom du parametre recu par le script serveur
            minChars: 2   // Nombre de caracteres minimum avant que des appels serveur ne soient effectu√©s
        });
        $('btn_add_reference').addEvent('click', function(e){
            add_item('reference');
        });
        $$('.btn_remove_reference').each(function(el){
            el.addEvent('click', function(event){
                $('list_references').value = delete_item($('list_references').value, el.getParent());
            });
        });
    }
    /********** MOTCLES **********/ 
    if($('btn_add_motcle')) {
        new Ajax.Autocompleter(
        "add_motcle",   // id du champ de formulaire
        "motcles_propositions",  // id de l'element utilise pour les propositions
        "../autocompletion-get-motcles.php",  // URL du script c√?t√© serveur
        {
            paramName: 'add_motcle',  // Nom du parametre recu par le script serveur
            minChars: 2   // Nombre de caracteres minimum avant que des appels serveur ne soient effectu√©s
        });
        $('btn_add_motcle').addEvent('click', function(e){
            add_item('motcle');
        });
        $$('.btn_remove_motcle').each(function(el){
            el.addEvent('click', function(event){
                $('list_motcles').value = delete_item($('list_motcles').value, el.getParent());
            });
        });
    }


     /********** MECANISMES **********/ 
    if($('btn_add_mecanisme')) {
        new Ajax.Autocompleter(
        "add_mecanisme",   // id du champ de formulaire
        "mecanismes_propositions",  // id de l'element utilise pour les propositions
        "../autocompletion-get-mecanismes.php",  // URL du script c√?t√© serveur
        {
            paramName: 'add_mecanisme',  // Nom du parametre recu par le script serveur
            minChars: 2   // Nombre de caracteres minimum avant que des appels serveur ne soient effectu√©s
        });
        $('btn_add_mecanisme').addEvent('click', function(e){
            add_item('mecanisme');
        });
        $$('.btn_remove_mecanisme').each(function(el){
            el.addEvent('click', function(event){
                $('list_mecanismes').value = delete_item($('list_mecanismes').value, el.getParent());
            });
        });
    }




    /********** DISTINCTIONS **********/ 
    if($('btn_add_distinction')) {
        new Ajax.Autocompleter(
        "add_distinction",   // id du champ de formulaire
        "distinctions_propositions",  // id de l'element utilise pour les propositions
        "../autocompletion-get-distinctions.php",  // URL du script c√?t√© serveur
        {
            paramName: 'add_distinction',  // Nom du parametre recu par le script serveur
            minChars: 2   // Nombre de caracteres minimum avant que des appels serveur ne soient effectu√©s
        });
        $('btn_add_distinction').addEvent('click', function(e){
            add_item('distinction');
        });
        $$('.btn_remove_distinction').each(function(el){
            el.addEvent('click', function(event){
                $('list_distinctions').value = delete_item($('list_distinctions').value, el.getParent());
            });
        });
    }


    /********** DOCUMENTS **********/ 
    if($('btn_add_document')) {
        new Ajax.Autocompleter(
        "add_document",   // id du champ de formulaire
        "documents_propositions",  // id de l'element utilise pour les propositions
        "../autocompletion-get-documents.php",  // URL du script c√?t√© serveur
        {
            paramName: 'add_document',  // Nom du parametre recu par le script serveur
            minChars: 2   // Nombre de caracteres minimum avant que des appels serveur ne soient effectu√©s
        });
        $('btn_add_document').addEvent('click', function(e){
            add_item('document');
        });
        $$('.btn_remove_document').each(function(el){
            el.addEvent('click', function(event){
                $('list_documents').value = delete_item($('list_documents').value, el.getParent());
            });
        });
    }

});
