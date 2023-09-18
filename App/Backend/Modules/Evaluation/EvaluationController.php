<?php

	/**
	 * Contrôleur du Module Evaluation dans Backend
	 *
	 * @author Lansky
	 *
	 * @since 10/07/2021 
	 */

	namespace App\Backend\Modules\Evaluation;

	use \Core\BackController;
    use \Model\ManagerEvaluation;

	require(__DIR__ . "/Model/ManagerModuleEvaluation.php");

	class EvaluationController extends BackController
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
			if (!isset($url[3])) {
				$url[3] = "";
			}
			if (strstr($url[3], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[3])));
			} else {
				if (empty($url[3])) {
					unset($url[3]);
				}
				if ($url[2] == "evaluation") {
	        		if (isset($url[3])) {
	        			if ($url[3] == "archive") {
			        		$methodName = ucfirst($url[2]) . '' . ucfirst($url[3]);
	        			} else {
			        		$methodName = ucfirst($url[3]);
	        			}
	        		} else {
	        			$methodName = "Evaluation";
	        		}
	        	} elseif (strstr($url[2], "_")) {
					$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[2])));
	        	} elseif (strstr($_GET['page'], '_')) {
					$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", end($url))));
	        	} else {
	        		$methodName = ucfirst(end($url));
	        	}
			}
			$manager = new \ManagerModuleEvaluation();
			$method  = "lister" . $methodName;
			return $manager->$method($parameters);
		}

		/**
		 * Afficher formulaire de modification ou d'ajout d'une table
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
			$manager = new \ManagerModuleEvaluation();
			$method  = "afficherForm" . $methodName;
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
			$path 		= end(explode($_SERVER['HTTP_HOST'] . '/', $_SERVER['HTTP_REFERER']));
			$url        = explode('-', $_GET['page']);
			$methodName = "";
			$redirect 	= "";
			if (strstr($url[1], "_")) {
				if (explode('_', $url[1])[0] == 'categorie') {
					$methodName = 'Questionnaire';
					$url[1] 	.= "?idCategorie=".$parameters['idCategorie'].'&questionnaire';
				} else {
					$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
					$path = "manage/" . explode('/', $_GET['page'])[1];
				}
			} else {
					$methodName = ucfirst($url[1]);
			}	
			if ($url[1] == 'evaluation' || $url[1] == 'barometre') {
				$redirect = '';
			} else {
				$redirect = "/" . $url[1];
			}
			$manager  = new \ManagerModuleEvaluation();
			$method   = "ajout" . $methodName;
			$result   = $manager->$method($parameters);
			if (!headers_sent()) {
			    header("Location: " . HOST . $path . $redirect);
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
			}
		}

		/**
		 * Dessiner graphiquement une évaluation
		 *
		 * @param array $parameters Les données à dessiner
		 * @return empty
		*/
		public function executeDraw($parameters)
		{
			$url        = explode('_', $_GET['page']);
			$path 		= end(explode($_SERVER['HTTP_HOST'] . '/', $_SERVER['HTTP_REFERER']));
			$methodName = ucfirst($url[1]);
			$redirect = "/" . $url[1];
			$manager  = new \ManagerModuleEvaluation();
			$method   = "draw" . $methodName;
			// echo "<pre>";
			// var_dump($redirect);
			// var_dump($method);
			// var_dump($path);
			// exit();
			$result   = $manager->$method($parameters);
			if (!headers_sent()) {
			    header("Location: " . HOST . $path . $redirect);
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
			}
		}

		/**
		 * Importer un fichier csv
		 *
		 * @param array $parameters
		 * @return empty
		*/
		public function executeUpload($parameters)
		{ 
			$url        = explode('_', $_GET['page']);
			$path 		= "manage/" . explode('/', $_GET['page'])[1] . "/evaluation/";
			$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", end(explode('/',$_GET['page'])))));
			$redirect = end(explode('/', $_SERVER['HTTP_REFERER']));
			if (strstr($redirect, '_')) {
				$path 		= substr($path, 0,strlen($path)-1);
				$redirect 	= strstr($redirect, '_');
			}
			$manager  = new \ManagerModuleEvaluation();
			$method   = "upload" . $methodName;
			$result   = $manager->$method($parameters);
			if (!headers_sent()) {
			   header("Location: " . HOST . $path . $redirect);
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$redirect\">\r\n";
			}
		}

		/**
		 * Mettre à jour une table
		 *
		 * @param array $parameters Les données à mettre jour
		 *
		 * @return empty
		*/
		public function executeMettreAJour($parameters)
		{
			$url        = explode('-', $_GET['page']);
			$methodName = "";
			$path 		= "manage/entreprise/evaluation/";
			$redirect 	= "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
				$path 	= "manage/entreprise/";
			} else {
				$methodName = ucfirst($url[1]);
			}
			if ($url[1] == 'evaluation') {
				$redirect 	=  explode('/', $url[0])[1] . '/evaluation';
				$path 		= 'manage/';
			} else {
				if (isset($parameters['redirect'])) {
					foreach ($parameters as $key => $value) {
						if ($key == 'redirect') {
							$redirect = $value;
							array_shift($parameters);
						} elseif(!empty($value)) {
							$redirect .= '?' . $key . '=' . $value;
							array_shift($parameters);
						} else {
							$redirect .= '&' . $key;
							array_shift($parameters);
							break;
						}
					}
				} else {
					$redirect = $url[1];
				}
			}
			$manager  = new \ManagerModuleEvaluation();
			$method   = "mettreAJour" . $methodName;
			$result   = $manager->$method($parameters);
			if (!headers_sent()) {
			    header("Location: " . HOST . $path . $redirect);
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
			}
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
			if ($url[1] == 'evaluation') {
				if ((explode('/', $url[0])[1] == 'entreprise' || explode('/', $url[0])[1] == 'employe')
					&& end(explode('/',$_SERVER['HTTP_REFERER'])) == 'archive') {
						$redirect = explode('/', $url[0])[1] . '/evaluation/archive';
				} else {
					$redirect = 'entreprise/evaluation';
				}
				$path = 'manage/';
				
			} elseif (strstr($url[1], "_") && explode("_",$url[1])[0] == 'evaluation') {
				$path 		= 'manage/entreprise/';
				$redirect 	= $url[1];
			} else {
				if (isset($parameters['redirect'])) {
					foreach ($parameters as $key => $value) {
						if ($key == 'redirect') {
							$redirect = $value;
						} elseif(!empty($value)) {
							$redirect .= '?' . $key . '=' . $value;
						} else {
							$redirect .= '&' . $key;
							break;
						}
					}
				} else {
					$redirect = $url[1];
				}
				$path = "manage/entreprise/evaluation/";
			}
			$manager  = new \ManagerModuleEvaluation();
			$method   = "supprimer" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location : " . HOST . $path . $redirect);
			exit();
		}

		/**
		 * Archiver une ligne dans une table
		 * 
		 * @param array $parameters Critères des données à archiver
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
			if ($url[1] == 'evaluation') {
				$redirect 	= explode('/', $url[0])[1] . '/evaluation';
				$path 		=  'manage/';
			} elseif (strstr($url[1], "_") && explode("_",$url[1])[0] == 'evaluation') {
				$path = 'manage/entreprise/';
				$redirect = $url[1];
			} else {
				if (isset($parameters['redirect'])) {
					foreach ($parameters as $key => $value) {
						if ($key == 'redirect') {
							$redirect = $value;
						} elseif(!empty($value)) {
							$redirect .= '?' . $key . '=' . $value;
						} else {
							$redirect .= '&' . $key;
							break;
						}
					}
				} else {
					$redirect = $url[1];
				}
				$path = "manage/entreprise/evaluation/";
			}
			$manager  = new \ManagerModuleEvaluation();
			$method   = "archiver" . $methodName;
			$result   = $manager->$method($parameters);
			if (!headers_sent()) {
			    header("Location: " . HOST . $path . $redirect);
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
			}
		}

		/**
		 * Restaurer une ligne dans une table d'évaluation
		 * 
		 * @param array $parameters Critères des données à restaurer
		 *
		 * @return empty
		*/
		public function executeRestaurer($parameters)
		{
			$url  = explode('-', $_GET['page']);
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			if ($url[1] == 'evaluation') {
				$redirect 	= explode('/', $url[0])[1] . '/evaluation/archive';
				$path 		=  'manage/';
			} elseif (strstr($url[1], "_") && explode("_",$url[1])[0] == 'evaluation') {
				$path = 'manage/entreprise/';
				$redirect = $url[1];
			} else {
				$redirect = $url[1];
				$path = "manage/entreprise/evaluation/";
			}
			$manager  = new \ManagerModuleEvaluation();
			$method   = "restaurer" . $methodName;
			$result   = $manager->$method($parameters);
			if (!headers_sent()) {
			    header("Location: " . HOST . $path . $redirect);
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
			}
		}

		/** 
		 * Annuler un enregistrement 
		 *
		 * @return empty
		*/
		public function executeCancelSave()
		{	
			$url 		= explode('-', $_GET['page']);
			$redirect 	= $url[1];
			header("Location : " . HOST . "manage/entreprise/evaluation/" . $redirect);
			exit();
		}

		/**
         * Voir les données 
         *
         * @param array $parameters Les données à afficher pour voir en détail
         *
         * @return objet
        */
        public function executeVoir($parameters)
        {
        	$url        = explode('/', $_GET['page']);
        	if ($url[1] == "entreprise" || $url[1] == "employe") {
	        	if ($url[2] == "evaluation") {
	        		if (isset($url[3])) {
			        	if (strstr($url[3], "_")) {
							$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[3])));
						} else {
			        		if (isset($parameters)) {
			        			$methodName = explode('&',$_SERVER['REQUEST_URI'])[1];
			        		} else {
				        		$methodName = $url[3];
			        		}        		
						}
	        		}  else {
	        			$methodName = "evaluation";
	        		}
	        	} elseif (strstr($url[2], "_")) {
	        		if (strstr($url[2], "-")) {
						$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", explode("-",$url[2])[1])));

	        		} else {
						$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[2])));
	        		}
	        	} elseif (strstr($url[2], "-")) {
	        		$methodName = explode('-',$url[2])[1];
	        	}
        	}
        	$method     = "voir" . ucwords($methodName);
        	$manager    = new \ManagerModuleEvaluation();
        	return  $manager->$method($parameters);
        }

        /**
         * Retourner les données sur l'évaluation
         *
         * @param array $parameters Les données à afficher
         *
         * @return objet
        */
        public function executeGetEvaluation($parameters)
        {
        	$method     = "getEvaluation";
        	$manager    = new \ManagerModuleEvaluation();
        	return  $manager->$method($parameters);
        }

        /**
         * Retourner les données sur la dimension
         *
         * @param array $parameters Les données à afficher sur le template
         *
         * @return array
        */
        public function executeGetJson($parameters)
        {
        	$method = "getSousCategorie";
        	$manager    = new \ManagerModuleEvaluation();
        	return  $manager->$method($parameters);
        }

        /**
         * Retourner les données sur la dimension
         *
         * @param array $parameters Les données à afficher sur le template
         *
         * @return objet
        */
        public function executegetCategories($parameters)
        {
        	$method     = "getCategories";
        	$manager    = new \ManagerModuleEvaluation();
        	return  $manager->$method($parameters);
        }

        /**
         * Retourner les données sur l'évaluation
         *
         * @param array $parameters Les données à récuperer
         *
         * @return objet
        */
        public function executeGetEvaluationArray($parameters)
        {
        	$method     = "getEvaluationArray";
        	$manager    = new \ManagerModuleEvaluation();
        	return  $manager->$method($parameters);
        }

         /**
         * Retourner les données sur la questionnaire
         *
         * @param array $parameters Les données à afficher sur le template
         *
         * @return objet
        */
        public function executeGetQuestion($parameters)
        {
        	$method     = "getQuestionnaire";
        	$manager    = new \ManagerModuleEvaluation();
        	return $manager->$method($parameters);
        }

         /**
         * Retourner les données sur la questionnaire json
         *
         * @param array $parameters Les données à afficher sur le template
         *
         * @return objet
        */
        public function executeQuestionAjax($parameters)
        {
        	$method     = "getQuestion";
        	$manager    = new \ManagerModuleEvaluation();
        	return $manager->$method($parameters);
        }
        
        /**
         * Retourner données du sousCategorie
         *
         * @param array $categories Array contenant l'Id du categorie 
         *
         * @return objet
        */
        public function executeGetSousCategoriePublique($categories)
        {
        	$method     	= "getPublicSousCategories";
        	$manager    	= new \ManagerModuleEvaluation();
        	return $manager->$method($categories['idParent']);
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
			$url   = explode('-', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}			
			$manager  = new \ManagerModuleEvaluation();
			$method   = "envoyer" . $methodName;
			$redirect = end(explode($_SERVER['HTTP_HOST'] . '/', $_SERVER['HTTP_REFERER']));
			$result   = $manager->$method($parameters);
			header("Location:" . HOST . $redirect);
			exit();
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
			$url   = explode('-', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}			
			$manager  = new \ManagerModuleEvaluation();
			$method   = "answer" . $methodName;
			$redirect = "employe/" . $url[1];
			$result   = $manager->$method($parameters);
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
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
			$manager  = new \ManagerModuleEvaluation();
			return $manager->$method($parameters);
		}
	}