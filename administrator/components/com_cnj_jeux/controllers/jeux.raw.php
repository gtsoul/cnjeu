<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Jeux list controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @since		1.6
 */
class Cnj_jeuxControllerJeux extends JController
{
	/**
	 * @var		string	The context for persistent state.
	 * @since	1.6
	 */
	protected $context = 'com_cnj_jeux.jeux';

	/**
	 * Proxy for getModel.
	 *
	 * @param	string	$name	The name of the model.
	 * @param	string	$prefix	The prefix for the model class name.
	 *
	 * @return	JModel
	 * @since	1.6
	 */
	public function getModel($name = 'Jeux', $prefix = 'Cnj_jeuxModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	/**
	 * Display method for the raw jeu data.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 * @todo	This should be done as a view, not here!
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// Get the document object.
		$document	= JFactory::getDocument();
		$vName		= 'jeux';
		$vFormat	= 'raw';

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat)) {
			// Get the model for the view.
			$model = $this->getModel($vName);

			// Load the filter state.
			$app = JFactory::getApplication();

			//$type = $app->getUserState($this->context.'.filter.type');
			//$model->setState('filter.type', $type);

			// Load the filter state.
                        $titre = $app->getUserState($this->context.'.filter.titre');
                        $model->setState('filter.titre', $titre);

                        $auteur = $app->getUserState($this->context.'.filter.auteur');
                        $model->setState('filter.auteur', $auteur);

                        $reference = $app->getUserState($this->context.'.filter.reference');
                        $model->setState('filter.reference', $reference);

                        $date_parution_debut = $app->getUserState($this->context.'.filter.date_parution_debut');
                        $model->setState('filter.date_parution_debut', $date_parution_debut);

                        $date_parution_fin = $app->getUserState($this->context.'.filter.date_parution_fin');
                        $model->setState('filter.date_parution_fin', $date_parution_fin);

                        $motcle = $app->getUserState($this->context.'.filter.motcle');
                        $model->setState('filter.motcle', $motcle);

                        $mecanisme = $app->getUserState($this->context.'.filter.mecanisme');
                        $model->setState('filter.mecanisme', $mecanisme);

                        $type = $app->getUserState($this->context.'.filter.type');
                        $model->setState('filter.type', $type);

                        $tri_1 = $app->getUserState($this->context.'.filter.tri_1');
                        $model->setState('filter.tri_1', $tri_1);

                        $tri_2 = $app->getUserState($this->context.'.filter.tri_2');
                        $model->setState('filter.tri_2', $tri_2);

                        $tri_3 = $app->getUserState($this->context.'.filter.tri_3');
                        $model->setState('filter.tri_3', $tri_3);

                        $tri_4 = $app->getUserState($this->context.'.filter.tri_4');
                        $model->setState('filter.tri_4', $tri_4);

                        // Load the parameters.
                        $params = JComponentHelper::getParams('com_cnj_jeux');
                        $model->setState('params', $params);

			$form = JRequest::getVar('jform');
			$model->setState('basename', $form['basename']);

			// Push the model into the view (as default).
			$view->setModel($model, true);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}
}
