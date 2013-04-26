<form action="index.php?option=com_chronoforms&chronoform=Contact&amp;event=submit" name="contact" id="form-contact" method="post" onSubmit="return check_form();">
	
    <h2><span class="subheading-category">Nous contacter</span></h2>
    <input type="hidden" name="dateinscription" value="<?php echo date("Y-m-d H:i:s"); ?>" />
    
  	<fieldset>
  		<legend>Votre identité</legend>
    	<ul>
    		<li><label>Civilité<span class="asterique">*</span></label> 
            <select name="civilite" id="civilite" data-validators="required">
            	<option value=""></option>
                <option value="Madame">Madame</option>
                <option value="Mademoiselle">Mademoiselle</option>
                <option value="Monsieur">Monsieur</option>
            </select></li>
    		<li><label for="nom">Nom<span class="asterique">*</span></label><input type="text" name="nom" id="nom" class="chp" maxlength="100" data-validators="required" /></li>
            <li><label for="prenom">Prénom<span class="asterique">*</span></label><input type="text" name="prenom" id="prenom" class="chp" maxlength="100" data-validators="required" /></li>
            <li><label for="adresse">Adresse</label><input type="text" name="adresse" id="adresse" class="chp" /></li>
            <li><label for="cp">Code postal</label><input type="text" name="cp" id="cp" class="chp" /></li>
            <li><label for="ville">Ville</label><input type="text" name="ville" id="ville" class="chp" /></li>
            <li><label for="email">Adresse mail<span class="asterique">*</span></label><input type="text" name="email" id="email" class="chp" maxlength="100" data-validators="required validate-email" /></li>
            <li><label for="tel">Téléphone<span class="asterique">*</span></label><input type="text" name="tel" id="tel" class="chp" maxlength="20" data-validators="required" /></li>
    	</ul>
  	</fieldset>
    
    <fieldset>
  		<legend>Votre société</legend>
    	<ul>
    		<li><label for="raison_sociale">Raison sociale</label><input type="text" name="raison_sociale" id="raison_sociale" class="chp" /></li>
            <li><label for="fonction">Fonction</label><input type="text" name="fonction" id="fonction" class="chp" /></li>
            <li><label for="adresse_societe">Adresse</label><input type="text" name="adresse_societe" id="adresse_societe" class="chp" /></li>
            <li><label for="cp_societe">Code postal</label><input type="text" name="cp_societe" id="cp_societe" class="chp" /></li>
            <li><label for="ville_societe">Ville</label><input type="text" name="ville_societe" id="ville_societe" class="chp" /></li>
    	</ul>
  	</fieldset>
    
    <fieldset>
  		<legend>Votre message</legend>
    	<ul>
    		<li><textarea name="message" id="message" rows="5" cols="20" data-validators="required"></textarea></li>
            <li>J'accepte de recevoir des informations par mail du groupe Auvence :<span class="asterique">*</span><br />
            <input type="radio" name="optin" value="1" /> Oui <input type="radio" name="optin" value="0" /> Non</li>
            <li><button type="submit" name="submit">Valider</button></li>
            <li><span class="asterique">*Mentions obligatoires</span></li>
    	</ul>
  	</fieldset>
	
</form>

<script>
	window.addEvent('domready', function() {
		Locale.use('fr-FR');
		new Form.Validator.Inline('chronoform_contact');
	})
</script>