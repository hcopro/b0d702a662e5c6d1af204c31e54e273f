<?php

	/**
	 * Contrôleur du Module Barometre dans Backend
	 *
	 * @author Lansky
	 *
	 * @since 10/07/2021 
	 */

	namespace App\Backend\Modules\Barometre;

	use \Core\BackController;
    use \Model\ManagerModuleBarometre;

	require(__DIR__ . "/Model/ManagerModuleBarometre.php");

	class BarometreController extends BackController
	{		
		/**
		 * Lister les données d'une table
		 *
		 * @param array $parameters Les critères des données à lister
		 *
		 * @return objet
		*/
		public function executeLister($parameters)
		{
			$url        = explode('/', $_GET['page']);
			$methodName = "";
			if (strstr($_GET['page'], '_')) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", end($url))));
        	} else {
        		$methodName = ucfirst(end($url));
        	}
			$manager = new \ManagerModuleBarometre();
			$method  = "lister" . $methodName;
			return $manager->$method($parameters);
		}

		/**
		 * Ajouter une ligne de données dans une table
		 *
		 * @param array $parameters Les données à ajouter
		 * @return empty
		*/
		public function executeAjout($parameters)
		{
			$referer 	= $_SERVER['HTTP_REFERER'];
			$refererArr	= explode(HOST . '/', $referer);
			$path 		= end($refererArr);
			$url        = explode('-', $_GET['page']);
			$methodName = "";
			$redirect 	= "";
			if (strstr(end($url), "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst(end($url));
			}
			if ($url[1] == 'barometre') {
				$redirect = '';
			} else {
				$redirect = "/" . $url[1];
			}
			$manager  = new \ManagerModuleBarometre();
			$method   = "ajout" . $methodName;
			$result   = $manager->$method($parameters);
			$this->sentHeader($path, $redirect);
		}
		
		/**
		 * Exécuter l'envoi d'un baromètre
		 *
		 * @param array $parameters liste des employés à envoyer du baromètre
		 *
		 * @return empty
		*/
		public function executeEnvoyer($parameters)
		{
			$url   		= explode('-', $_GET['page']);
			$methodName = "";
			$path 		= "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager  = new \ManagerModuleBarometre();
			$method   = "envoyer" . $methodName;
			$redirect = end(explode($_SERVER['HTTP_HOST'] . '/', $_SERVER['HTTP_REFERER']));
			$result   = $manager->$method($parameters);
			$this->sentHeader($path, $redirect);
		}

		/**
		 * Exécuter la réponse d'un baromètre via employé
		 *
		 * @param array $parameters le baromètre à répondre
		 *
		 * @return empty
		*/
		public function executeAnswer($parameters)
		{
			$url   		= explode('-', $_GET['page']);
			$methodName = "";
			$path 		= '';
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager  = new \ManagerModuleBarometre();
			$method   = "answer" . $methodName;
			$redirect = "employe/" . $url[1] . "?reply=NO";
			$result   = $manager->$method($parameters);
			$this->sentHeader($path, $redirect);
		}

		/**
		 * Exécuter la récpération d'un baromètre
		 *
		 * @param array $parameters ID du baromètre à récupérer
		 *
		 * @return empty
		*/
		public function executeJson($parameters)
		{			
			$method   = "getBarometer";
			$manager  = new \ManagerModuleBarometre();
			return $manager->$method($parameters);
		}

		/**
		 * Dessiner graphiquement un baromètre
		 *
		 * @param array $parameters Les données à dessiner
		 * @return empty
		*/
		public function executeDraw($parameters)
		{
			if (isset($parameters["offset"])) {
				$_SESSION['filters']['offset'] = $parameters["offset"];
			}
			if (!isset($_SERVER['HTTP_REFERER'])){
				$_SERVER['HTTP_REFERER'] = HOST . "manage/barometre_list";
			}
			$url        = explode('_', $_GET['page']);
			$referer 	= $_SERVER['HTTP_REFERER'];
			$pathArr 	= explode(HOST, $referer);
			$path 		= end($pathArr);
			$methodName = ucfirst($url[1]);
			$redirect 	= "/" . $url[1];
			$manager  	= new \ManagerModuleBarometre();
			$method   	= "draw" . $methodName;
			$result   	= $manager->$method($parameters);
			$this->sentHeader($path, $redirect);
		}

		/**
		 * Exécuter la récpération d'un baromètre
		 *
		 * @param array $parameters ID du baromètre à récupérer
		 *
		 * @return empty
		*/
		public function executeExport($parameters)
		{
			$method   = "exportToCsv";
			$manager  = new \ManagerModuleBarometre();	
			$manager->$method($parameters);
		}

		/**
		 * Exécuter la récpération d'un baromètre
		 *
		 * @param string $path 		Le chemin de l'url
		 * @param string $redirect 	La redirection de la page
		 *
		 * @return empty
		*/
		private function sentHeader($path=null, $redirect=null)
		{
			if (!headers_sent()) {
			    header("Location: " . HOST . $path . $redirect);
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
			}
		}
	}