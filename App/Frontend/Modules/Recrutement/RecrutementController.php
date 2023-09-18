<?php
	
	/**
	 * Controlleur du Module Recrutement dans Frontend
	 *
	 * @author Voahirana
	 *
	 * @since 25/09/19 
	 */

	namespace App\Frontend\Modules\Recrutement;

	use \Core\BackController;

	require(__DIR__ . "/Models/ManagerModuleRecrutement.php");

	class RecrutementController extends BackController
	{
		/** 
		 * Lister les offres 
		 * 
		 * @return array
		 */
		public function executeListerOffres($parameters)
		{	
			$manager = new \ManagerModuleRecrutement();
			return $manager->voirOffres($parameters);
			// return $manager->listerOffres($parameters);
		}

		/** 
		 * Voir le détail d'une offre 
		 *
		 * @param array $parameters Critères des données à voir 
		 * 
		 * @return objet 
		 */
		public function executeVoirOffre($parameters)
		{
			$manager = new \ManagerModuleRecrutement();
			return $manager->voirOffre($parameters);
		}

	}
