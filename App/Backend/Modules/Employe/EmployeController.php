<?php
	
	/**
	 * Controlleur du Module Employe dans Backend
	 *
	 * @author Voahirana
	 *
	 * @since 13/03/20 
	 */

	namespace App\Backend\Modules\Employe;

	use \Core\BackController;
	
    //use \Model\ManagerEmploye;

	require(__DIR__ . "/Model/ManagerModuleEmploye.php");

	class EmployeController extends BackController
	{
		/** 
		 * Lister les données
		 *
		 * @return array
		 */
		public function executeLister()
		{
			$url        = explode('/', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleEmploye();
			$method  = "lister" . $methodName;
			return $manager->$method();
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
			$manager = new \ManagerModuleEmploye();
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
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleEmploye();
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
			if ($url[1] == "employe") {
				$params = $this->generateParameters($parameters, $url);
	            // Redirection 
	            if ($_SESSION['compte']['identifiant'] == "entreprise") {	            	
	            	$redirect = $url[1] . "s";
	            } else {
	            	$redirect = "employe/dashboard";
	            }
			} elseif ($url[1] == "compte_banque") {
				$redirect = "employes";
			} else {
				$redirect = $url[1] . "s";
			}
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}			
			$manager  = new \ManagerModuleEmploye();
			$method   = "mettreAJour" . $methodName;
			$result   = $manager->$method($params);
			header("Location:" . HOST . "manage/" . $redirect);
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
			if ($url[1] == "banque") {
				$redirect = "manage/banques";
			} elseif ($url[1] == "categorieProfessionnelle") {
				$redirect = "manage/categorieProfessionnelles";
			} elseif ($url[1] == "employe" || $url[1] == "compte_banque") {
				if ($_SESSION['compte']['identifiant'] == "entreprise") {
					$redirect = "manage/employes";
				} else {
					$redirect = "manage/employe/dashboard";
				}
			} 
			$_SESSION['info']['danger'] = "Enregistrement annulée";
			header("Location:" . HOST . $redirect);
			exit();
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
			$manager = new \ManagerModuleEmploye();
			$method  = "voirDetail" . ucfirst($url[1]); 
			return $manager->$method($parameters);
		}

		/**
		 * Supprime une catégorie professionnelle
		 *
		 * @param array $parameters les données à supprimer
		 *
		 * @return object
		 */
		public function executeDeleteCategorieProfessionnelle($parameters)
		{
			$manager = new \ManagerModuleEmploye();
			return $manager->deleteCategorieProfessionnelle($parameters);
		}

		/**
		 * Retourner les postes dans un service
		 *
		 * @param array $parameters les données à supprimer
		 *
		 * @return array
		 */
		public function executeGetPostes($parameters)
		{
			$manager = new \ManagerModuleEmploye();
			return $manager->getPostes($parameters);
			exit();
		}

		/**
		 * Envoyer des informations à des utilisateurs
		 *
		 * @param array $parameters les données à envoyer
		 *
		 * @return empty
		 */
		public function executeSend($parameters)
		{
			$url        = explode('-', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleEmploye();
			$method  = "send" . $methodName;
			$manager->$method($parameters);
			header("Location:" . HOST . "manage/employe?idEmploye=" . $parameters['idEmploye']);
			exit();
		}

		/**
		 * Tester
		 *
		 * @param array $parameters les critères
		 *
		 * @return empty
		 */
		public function executeHasSubordonne($parameters)
		{
			$manager = new \ManagerModuleEmploye();
			$manager->hasSubordonne($parameters);
			exit();
		}

		/**
		 * Générer les paramètres
		 *
		 * @param array $response Les données à générer
		 * @param array $url Le url de la page courante
		 *
		 * @return array
		 */
		private function generateParameters($response, $url)
		{
			// Mise à jour de photo
			if (!empty($_FILES['photo']['name'])) { 
				if (!empty($response['photo'])) {
					$filePhoto = DOC_ROOT. 'Ressources/images/' . $url[1] . 's/' . $response['photo'];
					if (file_exists($filePhoto)) {
						unlink($filePhoto);
					} 
				}
            	$photoName = "photo_" . time() . ".png";
				$target    = DOC_ROOT. 'Ressources/images/' . $url[1] . 's/' . basename($_FILES['photo']['name']);
                move_uploaded_file( $_FILES['photo']['tmp_name'], $target);
                rename($target, DOC_ROOT. 'Ressources/images/' . $url[1] . 's/' . $photoName);
                $response['photo'] = $photoName;
            } elseif (isset($response['photo']) && $response['photo'] != "") {
            	$photoName  = "photo_" . time() . ".png";
            	$tmpFile    = DOC_ROOT. 'Ressources/images/candidats/' . $response['photo'];
				$uploadFile = DOC_ROOT. 'Ressources/images/' . $url[1] . 's/' . $response['photo'];
				if (file_exists($tmpFile)) {
					copy($tmpFile, $uploadFile);
	                rename($uploadFile, DOC_ROOT. 'Ressources/images/' . $url[1] . 's/' . $photoName);
	                $response['photo'] = $photoName;
				}					
            }
             // Mise à jour du CIN
            if (!empty($_FILES['cin']['name'])) { 
            	if (!empty($response['cin'])) {
					$fileCin = DOC_ROOT. 'Ressources/fichiers/cin/' . $response['cin'];
					if (file_exists($fileCin)) {
						unlink($fileCin);
					} 
				}
            	$cinName = "cin_" . time() . ".pdf";
				$target = DOC_ROOT. 'Ressources/fichiers/cin/' . basename($_FILES['cin']['name']);
				if (file_exists($_FILES['cin']['tmp_name'])) {
					move_uploaded_file( $_FILES['cin']['tmp_name'], $target);
	                rename($target, DOC_ROOT. 'Ressources/fichiers/cin/' . $cinName);
	                $response['cin'] = $cinName;
				} 
            }
             // Mise à jour du résidence
            if (!empty($_FILES['residence']['name'])) { 
            	if (!empty($response['residence'])) {
					$fileResidence = DOC_ROOT. 'Ressources/fichiers/residences/' . $response['residence'];
					if (file_exists($fileResidence)) {
						unlink($fileResidence);
					} 
				}
            	$residenceName = "residence_" . time() . ".pdf";
				$target = DOC_ROOT. 'Ressources/fichiers/residences/' . basename($_FILES['residence']['name']);
				if (file_exists($_FILES['residence']['tmp_name'])) {
					move_uploaded_file( $_FILES['residence']['tmp_name'], $target);
	                rename($target, DOC_ROOT. 'Ressources/fichiers/residences/' . $residenceName);
	                $response['residence'] = $residenceName;
				} 
            }
            // Mise à jour du bulletin
            if (!empty($_FILES['bulletin']['name'])) { 
            	if (!empty($response['bulletin'])) {
					$fileBulletin = DOC_ROOT. 'Ressources/fichiers/bulletins/' . $response['bulletin'];
					if (file_exists($fileBulletin)) {
						unlink($fileBulletin);
					} 
				}
            	$bulletinName = "bulletin_" . time() . ".pdf";
				$target = DOC_ROOT. 'Ressources/fichiers/bulletins/' . basename($_FILES['bulletin']['name']);
				if (file_exists($_FILES['bulletin']['tmp_name'])) {
					move_uploaded_file( $_FILES['bulletin']['tmp_name'], $target);
	                rename($target, DOC_ROOT. 'Ressources/fichiers/bulletins/' . $bulletinName);
	                $response['bulletin'] = $bulletinName;
				} 
            }
            // Mise à jour du CV
            if (!empty($_FILES['cv']['name'])) { 
            	if (!empty($response['cv'])) {
					$fileCv = DOC_ROOT. 'Ressources/fichiers/cv/' . $response['cv'];
					if (file_exists($fileCv)) {
						unlink($fileCv);
					} 
				}
            	$cvName = "cv_" . time() . ".pdf";
				$target = DOC_ROOT. 'Ressources/fichiers/cv/' . basename($_FILES['cv']['name']);
				if (file_exists($_FILES['cv']['tmp_name'])) {
					move_uploaded_file( $_FILES['cv']['tmp_name'], $target);
	                rename($target, DOC_ROOT. 'Ressources/fichiers/cv/' . $cvName);
	                $response['lettreMotivation'] = $cvName;
				} 
            }
            // Mise à jour du lettre de motivation 
            if (!empty($_FILES['lettreMotivation']['name'])) { 
            	if (!empty($response['lettreMotivation'])) {
					$fileLettreMotivation = DOC_ROOT. 'Ressources/fichiers/motivations/' . $response['lettreMotivation'];
					if (file_exists($fileLettreMotivation)) {
						unlink($fileLettreMotivation);
					} 
				}
            	$lettreMotivationName = "lettreMotivation_" . time() . ".pdf";
				$target               = DOC_ROOT. 'Ressources/fichiers/motivations/' . basename($_FILES['lettreMotivation']['name']);
				if (file_exists($_FILES['lettreMotivation']['tmp_name'])) {
					move_uploaded_file( $_FILES['lettreMotivation']['tmp_name'], $target);
	                rename($target, DOC_ROOT. 'Ressources/fichiers/motivations/' . $lettreMotivationName);
	                $response['lettreMotivation'] = $lettreMotivationName;
				} 
            }
            // Mise à jour des autres dossiers
            $folders = "";
            if (!empty($_FILES['autreDossier']['name'])) { 
            	$fileCount = count($_FILES['autreDossier']['name']);
            	for ($i = 0; $i < $fileCount ; $i++) {
					if ($_FILES['autreDossier']['name'][$i] != "") {
						$folderName = "dossier" . $i . "_" . time();
						$target     = DOC_ROOT. 'Ressources/fichiers/dossiers/' . basename($_FILES['autreDossier']['name'][$i]);
	                	if ($_FILES['autreDossier']['tmp_name'][$i]) {
	                		move_uploaded_file($_FILES['autreDossier']['tmp_name'][$i], $target);
			                rename($target, DOC_ROOT. 'Ressources/fichiers/dossiers/' . $folderName);
			                $folders .= $folderName . "/";
	                	}
					}
            	}
            	$response['autreDossier'] = $folders;
            }
            return $response;
		}

		/**
		 * Importer en fichier CSV les employes de l'entreprise
		 *
		 * @param array $parameters les critères
		 *
		 * @return empty
		 */
		public function executeImport($parameters)
		{
			$url     	= explode('-', $_GET['page']);
			// Redirection 
        	$redirect 	= $url[1] . "s";
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager  = new \ManagerModuleEmploye();
			$method   = "importer" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
		}

		/**
		 * Exporter le model  en fichier CSV les employes de l'entreprise
		 *
		 * @param array $parameters les critères
		 *
		 * @return empty
		 */
		public function executeExportxlx($parameters)
		{
			$url     	= explode('-', $_GET['page']);
			// Redirection 
        	$redirect 	= "employes";
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager  = new \ManagerModuleEmploye();
			$method   = "exporter" . $methodName;
			$result   = $manager->$method($parameters);
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
		}

		/**
		 * Convertisser chiffre en lettre
		 * 
		 * @changelog 2023-05-24 [EVOL] (Lansky) Ajout de la méthode
		 *
		 * @param array $parameters les critères
		 *
		 * @return empty
		 */
		public function executeGetLetterJson($parameters)
		{
			$manager  = new \ManagerModuleEmploye();
			$method   = "getLetterJson";
			$result   = $manager->$method($parameters);
			exit();
		}

	}
