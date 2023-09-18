<?php
	
	/**
	 * Contrôleur du Module Formation dans Backend
	 *
	 * @author Toky
	 *
	 * @since 15/09/2020 
	 */

	namespace App\Backend\Modules\Formation;

	use \Core\BackController;

	require(__DIR__ . "/Model/ManagerModuleFormation.php");

	class FormationController extends BackController
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
			$manager = new \ManagerModuleFormation();
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
			$manager = new \ManagerModuleFormation();
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
			if ($url[1] == 'formation') {
				$redirect = 'entreprise/formation';
			} elseif ($url[1] == 'employeFormation') {
				$redirect = 'employe/formationDisponible';
			} elseif ($url[1] == 'formateur') {
				$redirect = 'entreprise/formateur';
			} elseif ($url[1] == 'themeFormation' && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = 'entreprise/catalogueFormation';
			} elseif ($url[1] == 'themeFormation' && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = 'employe/suggestionFormation';
			} elseif ($url[1] == 'offreFormation') {
				$redirect = 'entreprise/offreFormation?idThemeFormation=' . $parameters['idThemeFormation'];
			} elseif ($url[1] == 'formationProfessionnelle') {
				$redirect = 'entreprise/formation';
			} elseif ($url[1] == 'evaluationFormation') {
				$redirect = 'employe/' . $parameters['page'];
			} else {
				$redirect = $url[1] . "s";
			}
			$manager  = new \ManagerModuleFormation();
			$method   = "mettreAJour" . $methodName;
			$result   = $manager->$method($parameters);
			if ($url[1] != "domaineFormateur") {
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
			if ($url[1] == 'formation') {
				$redirect = 'entreprise/formation';
			} elseif ($url[1] == 'themeFormation' && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = 'entreprise/catalogueFormation';
			} elseif ($url[1] == 'themeFormation' && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = 'employe/suggestionFormation';
			} elseif ($url[1] == 'formateur') {
				$redirect = 'entreprise/formateur';
			} elseif ($url[1] == 'employeFormation') {
				$redirect = 'employe/formationDisponible';
			} else {
				$redirect = $url[1] . "s";
			}
			$manager  = new \ManagerModuleFormation();
			$method   = "supprimer" . $methodName;
			$result   = $manager->$method($parameters);
			if ($url[1] != "domaineFormateur" && $url[1] != "offreFormateur") {
				header("Location : " . HOST . "manage/" . $redirect);
			}
			exit();
		}

		/**
		 * archiver une ligne dans une table
		 * 
		 * @param array $parameters critères des données à archiver
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
			if ($url[1] == 'formation') {
				$redirect = 'entreprise/formation';
			} elseif ($url[1] == 'formateur') {
				$redirect = 'entreprise/formateur';
			} elseif ($url[1] == "demandeFormation") {
				$redirect = 'employe/validationFormation';
			} else {
				$redirect = $url[1] . "s";
			}
			$manager  = new \ManagerModuleFormation();
			$method   = "archiver" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
		}

		/**
		 * restaurer une ligne dans une table
		 * 
		 * @param array $parameters critères des données à archiver
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
			if ($url[1] == 'formation') {
				$redirect = 'entreprise/formation';
			} elseif ($url[1] == 'formateur') {
				$redirect = 'entreprise/formateur';
			} elseif ($url[1] == "demandeFormation") {
				$redirect = 'employe/validationFormation';
			} else {
				$redirect = $url[1] . "s";
			}
			$manager  = new \ManagerModuleFormation();
			$method   = "restaurer" . $methodName;
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
         * Voir les données 
         *
         * @param array $parameters les données à afficher
         *
         * @return array
         */
        public function executeVoir($parameters)
        {
        	$url        = explode('/', $_GET['page']);
        	if ($url[2] == "formation" && $url[1] == "entreprise") {
        		$methodName = "formation";
        	} elseif ($url[2] == "formation" && $url[1] == "employe") {
        		$methodName = "suiviFormation";
        	} else {
        		$methodName = $url[2];
        	}
        	$method     = "voir" . ucwords($methodName);
        	$manager    = new \ManagerModuleFormation();
        	return  $manager->$method($parameters);
        }

        /**
         * Récupérer des données de thème
         *
         * @param array $parameters critères du thème à récupérer
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
			$manager  = new \ManagerModuleFormation();
			$method   = "get" . $methodName;
			$result   = $manager->$method($parameters);
        }

        /**
         * Valider une ligne dans la base
         *
         * @param array $parameters critères des données à valider
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
			$redirect = $url[1] . "s";
			if ($url[1] == "demandeFormation") {
				$redirect = "employe/validationFormation";
			}
			$manager  = new \ManagerModuleFormation();
			$method   = "valider" . $methodName;
			$result   = $manager->$method($parameters);
			if ($url[1] != "offreFormation") {
				header("Location : " . HOST . "manage/" . $redirect);
			}
			exit();
        }

        /**
         * Refuser une ligne dans la base
         *
         * @param array $parameters critères des données à valider
         *
         * @return empty
         */
        public function executeRefuser($parameters)
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$redirect = $url[1] . "s";
			if ($url[1] == "demandeFormation") {
				$redirect = "employe/validationFormation";
			}
			$manager  = new \ManagerModuleFormation();
			$method   = "refuser" . $methodName;
			$result   = $manager->$method($parameters);
			if ($url[1] != "offreFormation") {
				header("Location : " . HOST . "manage/" . $redirect);
			}
			exit();
        }

        /**
         * Participer à une entité
         *
         * @param array $parameters critères des données à modifier
         *
         * @return empty
         */
        public function executeParticiper($parameters)
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$redirect = $url[1] . "s";
			if ($url[1] == "formationProfessionnelle") {
				$redirect = "employe/formationDisponible";	
			}
			$manager  = new \ManagerModuleFormation();
			$method   = "participer" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        }

        /**
         * Envoyer un rappel d'évaluation
         *
         * @param array $parameters critères du rappel
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
			$redirect = $url[1] . "s";
			if ($url[1] == "evaluationFormation") {
				$redirect = "entreprise/formation";	
			}
			$manager  = new \ManagerModuleFormation();
			$method   = "rappeler" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        }

        /**
         * Reprendre une offre de formation
         *
         * @param array $parameters critères de l'offre
         *
         * @return empty
         */
        public function executeReprendre($parameters)
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$redirect = $url[1] . "s";
			if ($url[1] == "offreFormation") {
				$redirect = "entreprise/formation";	
			}
			$manager  = new \ManagerModuleFormation();
			$method   = "reprendre" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        }

        /**
         * Annuler une ligne dans la base
         *
         * @param array $parameters critères des données à valider
         *
         * @return empty
         */
        public function executeAnnuler($parameters)
        {
        	$url         = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$redirect = $url[1] . "s";
			if ($url[1] == "demandeFormation") {
				$redirect = "employe/formationDisponible";	
			}
			$manager  = new \ManagerModuleFormation();
			$method   = "annuler" . $methodName;
			$result   = $manager->$method($parameters);
			if ($url[1] != "offreFormation") {
				header("Location : " . HOST . "manage/" . $redirect);
			}
			exit();
        }
	}


