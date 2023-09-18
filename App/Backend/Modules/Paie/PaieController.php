<?php
	
	/**
	 * Controlleur du Module Paie dans Backend
	 *
	 * @author Toky
	 *
	 * @since 20/10/20 
	 */

	namespace App\Backend\Modules\Paie;

	use \Core\BackController;

	require(__DIR__ . "/Model/ManagerModulePaie.php");

	class PaieController extends BackController
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
			$manager = new \ManagerModulePaie();
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
			$manager = new \ManagerModulePaie();
			$method  = "voir" . $methodName;
			return $manager->$method($parameters);
		}

		/** 
		 * Afficher l'interface
		 *
		 * @return array
		 */
		public function executeAfficher($parameters)
		{
			$url        = explode('/', $_GET['page']);
			$methodName = "";
			if (strstr($url[2], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[2])));
			} else {
				$methodName = ucfirst($url[2]);
			}
			$manager = new \ManagerModulePaie();
			$method  = "afficher" . $methodName;
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
			$manager = new \ManagerModulePaie();
			$method  = "afficherForm" . $methodName;
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
			if ($url[1] == "parametrePaie") {
				$redirect = "entreprise/parametrePaie";
			} elseif ($url[1] == "avantage") {
				$redirect = "entreprise/avantagePaie";
			} elseif ($url[1] == "avantageEmploye") {
				$redirect = "entreprise/parametreFichePaie?idEmploye=" . $parameters['idEmploye'];
			} elseif ($url[1] == "deduction") { 
				$redirect = "entreprise/parametreFichePaie?idEmploye=" . $parameters['idEmploye'];
			} elseif ($url[1] == "fichePaie") {
				$redirect = "entreprise/detailFichePaie?idEmploye=" . $parameters['idEmploye'] . "&mois=" . $parameters['mois'] . "&annee=" . $parameters['annee'];
			} elseif ($url[1] == "parametreAvance") {
				$redirect = "entreprise/parametreAvance";
			} elseif ($url[1] == "demandeAvance") {
				$redirect = $_SESSION['compte']['identifiant'] . "/demandeAvance";
			} elseif ($url[1] == "demandeAvanceQuinzaine") {
				$redirect = $_SESSION['compte']['identifiant'] . "/demandeAvanceQuinzaine";
			} else {
				$redirect = $url[1] . "s";
			}
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}			
			$manager  = new \ManagerModulePaie();
			$method   = "mettreAJour" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
		}

		/** 
		 * Supprimer une ligne dans une table
		 *
		 * @param array $parameters critères des données à supprimer
		 *
		 * @return empty
		 */
		public function executeSupprimer($parameters)
		{
			$url     = explode('-', $_GET['page']);
			if ($url[1] == "avantage") {
				$redirect = "entreprise/avantagePaie";
			} elseif ($url[1] == "avantageEmploye") {
				$redirect = "entreprise/parametreFichePaie?idEmploye=" . $parameters['idEmploye'];
			} elseif ($url[1] == "deduction") {
				$redirect = "entreprise/parametreFichePaie?idEmploye=" . $parameters['idEmploye'];
			} elseif ($url[1] == "demandeAvance") {
 				$redirect = $_SESSION['compte']['identifiant'] . "/demandeAvance";
			} elseif ($url[1] == "demandeAvanceQuinzaine") {
				$redirect = $_SESSION['compte']['identifiant'] . "/demandeAvanceQuinzaine";
			} else {
				$redirect = $url[1] . "s";
			}
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}			
			$manager  = new \ManagerModulePaie();
			$method   = "supprimer" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
		}

		/** 
		 * Récupérer une ligne dans une table
		 *
		 * @param array $parameters critères des données à supprimer
		 *
		 * @return empty
		 */
		public function executeGet($parameters)
		{
			$url     = explode('-', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}			
			$manager  = new \ManagerModulePaie();
			$method   = "get" . $methodName;
			$result   = $manager->$method($parameters);
			exit();
		}

		/**
		 * Exécuter l'envoi d'une fiche de paie
		 *
		 * @param array $parameters critères de la fiche à envoyer
		 *
		 * @return empty
		 */
		public function executeEnvoyer($parameters)
		{
			$url   = explode('-', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}			
			$manager  = new \ManagerModulePaie();
			$method   = "envoyer" . $methodName;
			$result   = $manager->$method($parameters);
			$redirect = "entreprise/detailFichePaie?idEmploye=" . $parameters['idEmploye'] . "&mois=" . $parameters['mois'] . "&annee=" . $parameters['annee'];
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
		}

		/**
		 * Exécuter l'impression d'une fiche de paie
		 *
		 * @param array $parameters critères de la fiche à imprimer
		 *
		 * @return empty
		 */
		public function executeImprimer($parameters)
		{
			$url   = explode('-', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}			
			$manager  = new \ManagerModulePaie();
			$method   = "imprimer" . $methodName;
			$result   = $manager->$method($parameters);
			$redirect = "entreprise/detailFichePaie?idEmploye=" . $parameters['idEmploye'] . "&mois=" . $parameters['mois'] . "&annee=" . $parameters['annee'];
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
		}

		/**
         * Valider une demande
         *
         * @param array $parameters critères de la demande à valider
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
			if ($url[1] == "demandeAvance" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/demandeAvance";
			} elseif ($url[1] == "demandeAvanceQuinzaine" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/demandeAvanceQuinzaine";
			} else {
				$redirect = $url['1'] . 's';
			}
			$manager  = new \ManagerModulePaie();
			$method   = "valider" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        } 

        /**
         * rejeter une demande
         *
         * @param array $parameters critères de la table à rejeter
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
			if ($url[1] == "demandeAvance" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/demandeAvance";
			} elseif ($url[1] == "demandeAvanceQuinzaine" && $_SESSION['compte']['identifiant'] == "entreprise") {
				$redirect = "entreprise/demandeAvanceQuinzaine";
			} else {
				$redirect = $url['1'] . 's';
			}
			$manager  = new \ManagerModulePaie();
			$method   = "rejeter" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        }  

        /**
         * archiver une demande
         *
         * @param array $parameters critères de la table à rejeter
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
			if ($url[1] == "demandeAvance") {
				$redirect = $_SESSION['compte']['identifiant'] . "/demandeAvance";
			} elseif ($url[1] == "demandeAvanceQuinzaine") {
				$redirect = $_SESSION['compte']['identifiant'] . "/demandeAvanceQuinzaine";
			} else {
				$redirect = $url['1'] . 's';
			}
			$manager  = new \ManagerModulePaie();
			$method   = "archiver" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        }  

        /**
         * restaurer une demande
         *
         * @param array $parameters critères de la table à rejeter
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
			if ($url[1] == "demandeAvance") {
				$redirect = $_SESSION['compte']['identifiant'] . "/demandeAvance";
			} elseif ($url[1] == "demandeAvanceQuinzaine") {
				$redirect = $_SESSION['compte']['identifiant'] . "/demandeAvanceQuinzaine";
			} else {
				$redirect = $url['1'] . 's';
			}
			$manager  = new \ManagerModulePaie();
			$method   = "restaurer" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        }  

        /**
         * Rappeler une demande de congé
         *
         * @param array $parameters critères de la demande
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
			if ($url[1] == "demandeAvance" && $_SESSION['compte']['identifiant'] == "employe") {
				$redirect = "employe/demandeAvance";
			} else {
				$redirect = $url['1'] . 's';
			}
			$manager  = new \ManagerModulePaie();
			$method   = "rappeler" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . "manage/" . $redirect);
			exit();
        }
	}
