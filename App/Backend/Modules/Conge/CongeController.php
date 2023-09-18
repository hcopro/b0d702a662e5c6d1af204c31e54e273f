<?php
	
	/**
	 * Contrôleur du Module congé dans Backend
	 *
	 * @author Toky
	 *
	 * @since 19/08/2020 
	*/

	namespace App\Backend\Modules\Conge;

	use \Core\BackController;

	require(__DIR__ . "/Model/ManagerModuleConge.php");

	class CongeController extends BackController
	{		
		/**
		 * Lister Les données d'une table
		 *
		 * @param array $parameters Les critères des données à lister
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
			$manager = new \ManagerModuleConge();
			$method  = "lister" . $methodName;
			return $manager->$method($parameters);
		}

		/**
		 * Afficher Formulaire de modification ou d'ajout d'une table
		 *
		 * @param array $parameters Les données à affficher
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
			$manager = new \ManagerModuleConge();
			$method  = "afficherForm" . $methodName;
			return $manager->$method($parameters);
		}

		/**
		 * Mettre A jour une table
		 *
		 * @param array $parameters Les données à mettre à mettre jour
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
			if ($url[1] == "demandeConge" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/conge";
			} elseif ($url[1] == "demandeConge" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/validation";
			} elseif ($url[1] == "employeConge" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/pointage/dashboard";
			} elseif ($url[1] == "stockConge") {
				$redirect = "entreprise/conge";
			} else {
				$redirect = $url[1] . "s";
			}
			$manager  = new \ManagerModuleConge();
			$method   = "mettreAJour" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
		}

		/**
         * Récupérer une ligne dans une table
         *
         * @param array $parameters Critères de la ligne à récupérer
         *
         * @return empty
        */
        public function executeGet($parameters)
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager  = new \ManagerModuleConge();
			$method   = "get" . $methodName;
			$result   = $manager->$method($parameters);
        }

		/**
		 * Supprimer une ligne dans une table
		 * 
		 * @param array $parameters Critères des données à supprimer
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
			if ($url[1] == "demandeConge" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/conge";
			} else {
				$redirect = $url[1] . "s";
			}
			$manager  = new \ManagerModuleConge();
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
		 * Voir le planning de congé
		 *
		 * @param array $parameters Les critères des données à afficher
		 *
		 * @return array
		*/
		public function executeVoirPLanning($parameters)
		{
			$url        = explode('/', $_GET['page']);
			$manager 	= new \ManagerModuleConge();
			$method  	= "voir" . ucwords($url[2]);
			return $manager->$method($parameters);
		}

		/**
		 * Voir la configuration des tâches planifiées
		 *
		 * @param array $parameters Les critères des données à afficher
		 *
		 * @return array
		*/
		public function executeVoirTachePlanifiee($parameters)
		{
			$manager     = new \ManagerModuleConge();
			return $manager->voirTachePlanifiee($parameters);
		}

		/**
         * Voir les données du planning
         *
         * @param array $parameters Les données à afficher
         *
         * @return array
        */
        public function executeVoir($parameters)
        {
        	$url        = explode('/', $_GET['page']);
        	if (array_key_exists(2, $url)) {
	        	if ($url[2] == "conge" && $url[1] == "employe") {
	        		$methodName = "congeEmploye";
	        	} elseif ($url[2] == "validation" && $url[1] == "employe") {
	        		$methodName = "validationEmploye";
	        	} elseif ($url[2] == "validation" && $url[1] == "entreprise") {
	        		$methodName = "validationEntreprise";
	        	} elseif ($url[2] == "conge" && $url[1] == "entreprise") {
	        		$methodName = "congeEntreprise";
	        	} elseif ($url[2] == "conge" && $url[1] == "parametre") {
	        		$methodName = "parametreConge";
	        	} else {
	        		$methodName = $url[2];
	        	}
        	} else {
        		if (end($url) == "historiqueConge" && isset($parameters['idEmploye'])) {
	        		$methodName = end($url);
	        	} 
        	}
        	$method     = "voir" . ucwords($methodName);
        	$manager    = new \ManagerModuleConge();
        	return  $manager->$method($parameters);
        }

        /**
         * Voir les congés disponibles
         *
         * @param array $parameters Critères des données à vérifier
         *
         * @return empty
        */
        public function executeVerifierCongeDisponible($parameters)
        {
        	$manager = new \ManagerModuleConge();
        	$manager->verifierCongeDisponible($parameters);
        	exit();
        }

        /**
         * Valider une demande
         *
         * @param array $parameters Critères de la demande à valider
         *
         * @return empty
        */
        public function executeValider($parameters) 
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			if ($url[1] == "demandeConge" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/conge";
			} elseif ($url[1] == "validationConge" || $url[1] == "revalidationConge" || $url[1] == "annulationConge") {
				$redirect = $_SESSION['compte']['identifiant'] . "/validation";
			} else {
				$redirect = $url['1'] . 's';
			}
			$manager  = new \ManagerModuleConge();
			$method   = "valider" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        } 

        /**
         * Rejeter une demande
         *
         * @param array $parameters Critères de la table à rejeter
         *
         * @return empty
        */
        public function executeRejeter($parameters) 
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			if ($url[1] == "demandeConge" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/conge";
			} elseif ($url[1] == "validationConge" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/validation";
			} elseif ($url[1] == "validationConge" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/validation";
			} else {
				$redirect = $url['1'] . 's';
			}
			$manager  = new \ManagerModuleConge();
			$method   = "rejeter" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        } 

        /**
         * Archiver une table
         *
         * @param array $parameters Critères de la table à archiver
         *
         * @return empty
        */
        public function executeArchiver($parameters) 
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			if ($url[1] == "demandeConge" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/conge";
			} elseif ($url[1] == "validationConge" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/validation";
			} elseif ($url[1] == "validationConge" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/validation";
			} else {
				$redirect = $url['1'] . 's';
			}
			$manager  = new \ManagerModuleConge();
			$method   = "archiver" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        } 

        /**
         * Restaurer une table
         *
         * @param array $parameters Critères de la table à restaurer
         *
         * @return empty
        */
        public function executeRestaurer($parameters) 
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			if ($url[1] == "demandeConge" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/conge";
			} elseif ($url[1] == "validationConge" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/validation";
			} elseif ($url[1] == "validationConge" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/validation";
			} else {
				$redirect = $url['1'] . 's';
			}
			$manager  = new \ManagerModuleConge();
			$method   = "restaurer" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        } 

        /**
         * Rappeler une demande de congé
         *
         * @param array $parameters Critères de la demande
         *
         * @return empty
        */
        public function executeRappeler($parameters)
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			if ($url[1] == "demandeConge" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/conge";
			} else {
				$redirect = $url['1'] . 's';
			}
			$manager  = new \ManagerModuleConge();
			$method   = "rappeler" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        }

        /**
         * Activer des données
         *
         * @param array $parameters Critères des données à activer
         *
         * @return empty
        */
        public function executeActiver($parameters) 
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$redirect = $url['1'] . 's';
			$manager  = new \ManagerModuleConge();
			$method   = "activer" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        } 

        /**
         * Desactiver des données
         *
         * @param array $parameters Critères des données à activer
         *
         * @return empty
        */
        public function executeDesactiver($parameters) 
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$redirect = $url['1'] . 's';
			$manager  = new \ManagerModuleConge();
			$method   = "desactiver" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        } 

        /**
         * Modifier un attribut d'une ligne
         *
         * @param array $parameters Critères des données à modifier
         *
         * @return empty
        */
        public function executeChanger($parameters)
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$redirect = $url['1'] . 's';
			$manager  = new \ManagerModuleConge();
			$method   = "changer" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        }

        /** @changelog 09/03/2022 [EVOL] (Lansky) Ajout annulation de demande de congé qui a été valider */
        /**
         * Annuler une demande de congé de salarié
         *
         * @param array $parameters Critères de donnée à annuler
         *
         * @return empty
        */
        public function executeAnnuler($parameters)
        {
        	$url         = explode('-', $_GET['page']);
			$methodName = ucfirst(end($url));
			$redirect = $_SESSION['compte']['identifiant'];
			$manager  = new \ManagerModuleConge();
			$method   = "annuler" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect . "/conge");
			exit();
        }
	}