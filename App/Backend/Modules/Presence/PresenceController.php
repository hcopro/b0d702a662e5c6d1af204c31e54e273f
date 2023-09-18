<?php
	
	/**
	 * Contrôleur du Module présence dans Backend
	 *
	 * @author Toky
	 *
	 * @since 14/07/2020 
	*/

	namespace App\Backend\Modules\Presence;

	use \Core\BackController;
    use \Model\ManagerJourFerie;
    use \Model\ManagerEntrepriseFerie;

	require(__DIR__ . "/Model/ManagerModulePresence.php");

	class PresenceController extends BackController
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
			$manager = new \ManagerModulePresence();
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
			$manager = new \ManagerModulePresence();
			$method  = "afficherForm" . $methodName;
			return $manager->$method($parameters);
		}

		/**
		 * mettre A jour une table
		 *
		 * @param array $parameters les données à mettre à mettre jour
		 *
		 * @return empty
		*/
		public function executeMettreAJour($parameters)
		{
			$url        = explode('-', $_GET['page']);
			$methodName = "";
			$redirect = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			if ($url[1] == "tache") {
				$redirect = "employe/pointage/dashboard";
			} elseif ($url[1] == "pointage" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/pointage/dashboard";
			} elseif ($url[1] == "parametrePointage" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/pointage/dashboard";
			} elseif ($url[1] == "employePermission" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/pointage/dashboard";
			} elseif ($url[1] == "employePermission" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/permission/dashboard";
			} elseif ($url[1] == "employeRepos" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/repos/dashboard";
			} elseif ($url[1] == "employeRepos" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/pointage/dashboard";
			} elseif ($url[1] == "entrepriseFerie" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/jourFerie";
			} elseif ($url[1] == "entreprisePermission" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/parametre/permission";
			} else {
				$redirect = $url[1] . "s";
			}
			$manager  = new \ManagerModulePresence();
			$method   = "mettreAJour" . $methodName;
			$result   = $manager->$method($parameters);
			if (!headers_sent()) {
				header("Location : " . HOST . "manage/" . $redirect);
			}
			exit();
		}

		/**
		 * supprimer une ligne dans une table
		 * 
		 * @param array $parameters critères des données à supprimer
		 *
		 * @return empty
		*/
		public function executeSupprimer($parameters)
		{
			$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			if ($url[1] == "tache") {
				$redirect = "employe/pointage/dashboard";
			} elseif ($url[1] == "entrepriseFerie") {
				$redirect = "entreprise/jourFerie";
			} elseif ($url[1] == "demandeRepos" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/repos/dashboard";
			} elseif ($url[1] == "demandeRepos" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/pointage/dashboard";
			} elseif ($url[1] == "demandePermission" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/permission/dashboard";
			} elseif ($url[1] == "demandePermission" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/pointage/dashboard";
			} else {
				$redirect = $url[1] . "s";
			}
			$manager  = new \ManagerModulePresence();
			$method   = "supprimer" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
		}

		/** 
		 * Annuler un enregistrement 
		 *
		 * @return empty
		*/
		public function executeCancelSave()
		{	
			$url = explode('-', $_GET['page']);
			$redirect = $url[1] . "s";
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
		}

		/**
		 * Incrementer la valeur d'une table
		 *
		 * @param array $parameters les données à incrémenter
		 *
		 * @return empty
		*/
		public function executeIncrement($parameters)
		{
			$url = explode('-', $_GET['page']);
			$manager = new \ManagerModulePresence();
			$redirect = "";
			if ($url[1] == "permission" || $url[1] == "dureeMaxPermission" || $url[1] == "dureeMaxRepos") {
				$redirect = "entreprise/parametre/permission";
			}
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$method = 'incrementer' . $methodName;
			$manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
		}

		/**
		 * Décrementer la valeur d'une table
		 *
		 * @param array $parameters les données à décrémenter
		 *
		 * @return empty
		*/
		public function executeDecrement($parameters)
		{
			$url = explode('-', $_GET['page']);
			$manager = new \ManagerModulePresence();
			$redirect = "";
			if ($url[1] == "permission" || $url[1] == "dureeMaxPermission" || $url[1] == "dureeMaxRepos") {
				$redirect = "entreprise/parametre/permission";
			}
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$method = 'decrementer' . $methodName;
			$manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
		}

		/**
		 * Valider une table
		 *
		 * @param array $parameters les données à valider
		 *
		 * @return empty
		*/
		public function executeValider($parameters)
		{
			$url = explode('-', $_GET['page']);
			$manager = new \ManagerModulePresence();
			$redirect = "";
			if ($url[1] == "demandePermission") {
				$redirect = $_SESSION['compte']['identifiant'] . "/permission/dashboard";
			} elseif ($url[1] == "demandeRepos") {
				$redirect = $_SESSION['compte']['identifiant'] . "/repos/dashboard";
			}
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$method = 'valider' . $methodName;
			$manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
		}

		/**
		 * rejeter une table
		 *
		 * @param array $parameters les données à rejeter
		 *
		 * @return empty
		*/
		public function executeRejeter($parameters)
		{
			$url = explode('-', $_GET['page']);
			$manager = new \ManagerModulePresence();
			$redirect = "";
			if ($url[1] == "demandePermission") {
				$redirect = $_SESSION['compte']['identifiant'] . "/permission/dashboard";
			} elseif ($url[1] == "demandeRepos") {
				$redirect = $_SESSION['compte']['identifiant'] . "/repos/dashboard";
			}
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$method = 'rejeter' . $methodName;
			$manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
		}

		/**
		 * Voir le tableau de bord du temps de travail
		 *
		 * @param array $parameters les données à afficher 
		 *
		 * @return array
		*/
		public function executeVoirTemps($parameters)
		{
			$url = explode('/', $_GET['page']);
			$methodName = '';
			if ($url[1] == 'employe' || $url[1] == 'update-tache') {
				$methodName = 'Employe';
				if (array_key_exists(2, $url)) {
					if ($url[2] == 'retard' || $url[2] == 'permission') {
						$parameters['page'] = $url[2];
					}
				}
			} elseif ($url[1] == 'entreprise') {
				$methodName 		= 'Entreprise';
				$parameters['page'] = $url[2];
			}
			$method = 'voirTemps' . $methodName;
			$manager  = new \ManagerModulePresence();
			return $manager->$method($parameters);
		}

		/**
		 * Voir le tableau de bord de suivi
		 *
		 * @param array $parameters les données à afficher 
		 *
		 * @return array
		*/
		public function executeVoirSuivi($parameters)
		{
			$url = explode('/', $_GET['page']);
			$methodName = '';
			if ($url[1] == 'employe') {
				$methodName = 'Employe';
			} elseif ($url[1] == 'entreprise') {
				$methodName = 'Entreprise';
			}
			$method   = 'voirSuivi' . $methodName;
			$manager  = new \ManagerModulePresence();
			return $manager->$method($parameters);
		}

		/**
		 * Récupérer les permissions possibles pour un employé
		 *
		 * @param array $parameters critère de l'employé
		 *
		 * @return array
		*/
		public function executeGetPermissionPossibles($parameters)
		{
			$manager = new \ManagerModulePresence();
			$manager->getPermissionPossibles($parameters);
			exit();
		}

		/**
		 * Récupérer les repos possibles pour un employé
		 *
		 * @param array $parameters critère de l'employé
		 *
		 * @return array
		*/
		public function executeGetReposPossibles($parameters)
		{
			$manager = new \ManagerModulePresence();
			$manager->getReposPossibles($parameters);
			exit();
		}

		/**
		 * Récupérer les repos possibles pour un employé
		 *
		 * @param array $parameters critère de l'employé
		 *
		 * @return array
		*/
		public function executeGetWorkTimer($parameters)
		{
			$manager 	= new \ManagerModulePresence();
			$method 	= 'getWorkTimer';
			return $manager->$method($parameters);
		}

		/**
		 * 
		 *
		 * @param array $parameters critère de l'employé
		 *
		 * @return array
		*/
		public function executeTrackingTask($parameters)
		{
			$manager 	= new \ManagerModulePresence();
			$method 	= 'getTrackingTask';
			return $manager->$method($parameters);
		}

		/**
		 * 
		 *
		 * @param array $parameters critère de l'employé
		 *
		 * @return array
		*/
		public function executeMySubordinate($parameters)
		{
			$manager 	= new \ManagerModulePresence();
			$method 	= 'getMySubordinate';
			return $manager->$method($parameters);
		}

        /**@changelog 2022-12-28 [EVOL] (Lansky) Ajouter une fonctionnalité du planning => shift */
		/**
		 * Voir le tableau de bord de shift
		 *
		 * @param array $parameters les données à afficher 
		 *
		 * @return array
		*/
		public function executeVoirShift($parameters)
		{
			$url = explode('/', $_GET['page']);
			$method   = 'voirShift';
			$manager  = new \ManagerModulePresence();
			return $manager->$method($parameters);
		}

		/**
		 * Arrêter le pointage lors POWER Off client au cas d'oublie
		 *
		 * @changelog 2023-06-15 [EVOL] (Lansky) Ajout de la méthode
		 * 
		 * @param array $parameters  
		 *
		 * @return array
		*/
		public function executePowerOff($parameters)
		{
			// $url = explode('/', $_GET['page']);
			$method   = 'powerOff';
			$manager  = new \ManagerModulePresence();
			return $manager->$method($parameters);
		}

		/**
		 * Ajouter nouveau planning de shift
		 *
		 * @changelog 2023-06-30 [EVOL] (Lansky) Ajout de la méthode
		 * 
		 * @param array $parameters  
		 *
		 * @return array
		*/
		public function executeShiftJson($parameters)
		{
			$url 		= explode('/', $_GET['page']);
			$endUrl 	= end($url);
			$method 	= lcfirst(str_replace(" ", "", ucwords(str_replace("-", " ", $endUrl))));
			$manager  	= new \ManagerModulePresence();
			return $manager->$method($parameters);
		}

		/**
		 * Récupérer la liste des salariés de l'entreprise
		 *
		 * @changelog 2023-07-12 [EVOL] (Lansky) Ajout de la méthode
		 * 
		 * @param array $parameters
		 *
		 * @return array
		*/
		public function executeGetAllworkerJson($parameters)
		{
			$url 		= explode('/', $_GET['page']);
			$method 	= end($url);
			$manager  	= new \ManagerModulePresence();
			return $manager->$method($parameters);
		}

		/**
		 * Exporter un fichier excel
		 *
		 * @changelog 2023-09-11 [EVOL] (Lansky) Ajout de la méthode
		 * 
		 * @param array $parameters
		 *
		 * @return array
		*/
		public function executeExport($parameters)
		{
			$url 		= explode('/', $_GET['page']);
			$method 	= lcfirst(str_replace(" ", "", ucwords(str_replace("-", " ", end($url)))));
			$manager  	= new \ManagerModulePresence();
			return $manager->$method($parameters);
		}
	}
