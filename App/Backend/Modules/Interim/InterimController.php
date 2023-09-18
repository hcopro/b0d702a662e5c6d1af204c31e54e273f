<?php
	
	/**
	 * Contrôleur du Module Interim dans Backend
	 *
	 * @author Toky
	 *
	 * @since 10/09/2020 
	 */

	namespace App\Backend\Modules\Interim;

	use \Core\BackController;

	require(__DIR__ . "/Model/ManagerModuleInterim.php");

	class InterimController extends BackController
	{		
		/**
		 * lister les données d'une table
		 *
		 * @param array $parameters les critères des données à lister
		 *
		 * @return array|empty
		 */
		public function executeLister($parameters)
		{
			$url        = explode('/', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleInterim();
			$method  = "lister" . $methodName;
			return $manager->$method($parameters);
		}

		/**
		 * afficher formulaire de modification ou d'ajout d'une table
		 *
		 * @param array $parameters les données à affficher
		 *
		 * @return object
		 */
		public function executeAfficherForm($parameters)
		{
			$url        = explode('-', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleInterim();
			$method  = "afficherForm" . $methodName;
			return $manager->$method($parameters);
		}
	}