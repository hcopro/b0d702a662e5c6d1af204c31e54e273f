<?php
	
	/**
	 * Controlleur du Module Contrat dans Backend
	 *
	 * @author Toky
	 *
	 * @since 07/09/20 
	 */

	namespace App\Backend\Modules\Contrat;

	use \Core\BackController;

	require(__DIR__ . "/Model/ManagerModuleContrat.php");

	class ContratController extends BackController
	{
		/** 
		 * Lister les données
		 *
		 * @return array
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
			$manager = new \ManagerModuleContrat();
			$method  = "lister" . $methodName;
			return $manager->$method($parameters);
		}

		/** 
		 * Afficher l'interface
		 *
		 * @return array
		 */
		public function executeVoir($parameters)
		{
			$url        = explode('/', $_GET['page']);
			$methodName = "";
			if (strstr($url[2], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[2])));
			} else {
				$methodName = ucfirst($url[2]);
			}
			if ($url[2] == "templateContrat") {
				$parameters['page'] = "document";
			} elseif ($url[2] == "parametreContrat") {
				$parameters['page'] = "alerte";
			}
			$manager = new \ManagerModuleContrat();
			$method  = "voir" . $methodName;
			return $manager->$method($parameters);
		}

		/** 
		 * Afficher les formulaires 
		 *
		 * @param array $parameters Les donnée a récupérer
		 *
		 * @return Object
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
			$manager = new \ManagerModuleContrat();
			$method  = "afficherForm" . $methodName;
			return $manager->$method($parameters);

		}

		/** 
		 * Afficher les formulaires de modification
		 *
		 * @param array $parameters Les donnée a récupérer
		 *
		 * @return Object
		 */
		public function executeAfficherFormUpdate($parameters)
		{
			$url        = explode('-', $_GET['page']);
			$methodName = "";
			if ($url[1] == "historiqueContrat") {
				$parameters['page'] = "historique";
				$methodName         = "ContratEmploye";
			} else {
				if (strstr($url[1], "_")) {
					$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
				} else {
					$methodName = ucfirst($url[1]);
				}
			}
			$manager = new \ManagerModuleContrat();
			$method  = "afficherFormUpdate" . $methodName;
			return $manager->$method($parameters);
		}

		/** 
		 * Mettre à jour une ligne dans une table
		 *
		 * @param array $parameters Desc: Données 
		 *
		 * @return empty
		 */
		public function executeMettreAJour($parameters)
		{
			$url     = explode('-', $_GET['page']);
			if ($url[1] == "contratEmploye") {
				$redirect = "update-contratEmploye?idEmploye=".$parameters['idEmploye'];
			} elseif ($url[1] == "renouvellement") {
				$redirect = "update-contratEmploye?idEmploye=".$parameters['idEmploye'];
			} else {
				$redirect = $url[1] . "s";
			}
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}			
			$manager  = new \ManagerModuleContrat();
			$method   = "mettreAJour" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
		}

		/**
		 * Mettre à jour les configurations
		 *
		 * @param array $parameters Données à mettre à jour
		 *
		 * @return empty
		 */
		public function executeMettreAJourConfiguration($parameters)
		{
			$manager = new \ManagerModuleContrat();
			$methode = "mettreAJourConfiguration";
			$manager->$methode($parameters);
		}

		/** 
		 * Voir les détails d'une table
		 *
		 * @param array $parameters la table concernée
		 *
		 * @return object
		 */
		public function executeVoirDetail($parameters)
		{
			$url     = explode('/', $_GET['page']);
			$manager = new \ManagerModuleContrat();
			$method  = "voirDetail" . ucfirst($url[1]); 
			return $manager->$method($parameters);
		}

		/**
		 * Imprimer contrat
		 *
		 * @param array $parameters les données à inclure dans le documant à imprimer
		 *
		 * @return object
		 */
		public function executeImprimerContrat($parameters)
		{
			$manager = new \ManagerModuleContrat();
			return $manager->imprimerContrat($parameters);
		}

		/**
		 * afficher le contrat de l'utilisateur
		 *
		 * @param array $parameters les critères du contrat
		 *
		 * @return object
		 */
		public function executeAfficherMonContrat($parameters)
		{
			$url = explode("-", $_GET['page']);
			if ($url[1] == "monContrat") {
				$parameters['page'] = "contrat";
			} elseif ($url[1] == "monHistoriqueContrat") {
				$parameters['page'] = "historique";
			}
			$parameters['idEmploye'] = $_SESSION['user']['idEmploye'];
			$manager = new \ManagerModuleContrat();
			return $manager->afficherFormUpdateContratEmploye($parameters);
		}

		/**
		 * Supprimer un contrat d'un employé
		 *
		 * @param array $parameters les données à supprimer
		 *
		 * @return object
		 */
		public function executeDeleteContratEmploye($parameters)
		{
			$manager = new \ManagerModuleContrat();
			return $manager->deleteContratEmploye($parameters);
		}

		/**
		 * Switcher Activation des rappels automatiques
		 *
		 * @param array $parameters les données nécessaires
		 *
		 * @return empty
		 */
		public function executeSwitcherAlerte($parameters)
		{
			$manager = new \ManagerModuleContrat();
			return $manager->switcherAlerte($parameters);
		} 

	}
