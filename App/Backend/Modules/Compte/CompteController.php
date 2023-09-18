<?php

	/**
	 * Controlleur du Module Compte dans Backend
	 *
	 * @author Voahirana
	 *
	 * @since 30/09/19 
	*/
	namespace App\Backend\Modules\Compte;
	use \Core\BackController;
	
    use \Model\ManagerCompte;
    use \Model\ManagerCandidat;
	require(__DIR__ . "/Model/ManagerModuleCompte.php");
	class CompteController extends BackController
	{		
		/** 
		 * Lister les données
		 *
		 * @param array $parameters critères des données à lister
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
			$manager = new \ManagerModuleCompte();
			$method  = "lister" . $methodName;
			return $manager->$method($parameters);
		}

		/** 
		 * Voir le profil de l'utilisateur connecté
		 *
		 * @return object
		*/
		public function executeVoirProfil()
		{
			$url     = explode('/', $_GET['page']);
			$manager = new \ManagerModuleCompte();
			$method  = "voirProfil" . ucfirst($url[1]);
			return $manager->$method();
		}

		/** 
		 * Voir les détails d'un utilisateur
		 *
		 * @param array $parameters L'utilisateur concerné
		 *
		 * @return object
		*/
		public function executeVoirDetail($parameters)
		{
			$url     = explode('/', $_GET['page']);
			$manager = new \ManagerModuleCompte();
			$method  = "voirDetail" . ucfirst($url[1]); 
			return $manager->$method($parameters);
		}

		/** 
		 * Afficher les formulaires d'un utilisateur
		 *
		 * @param array $parameters Les donnée a récupérer
		 *
		 * @return Object
		*/
		public function executeAfficherForm($parameters)
		{
			$url     = explode('-', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleCompte();
			$method  = "afficherForm" . $methodName;
			return $manager->$method($parameters);
		}

		/** 
		 * Mettre à jour une ligne dans une table
		 *
		 * @param array $parameters Les données à insérer ou modifier 
		 *
		 * @return empty
		*/
		public function executeMettreAJour($parameters)
		{
			$url     = explode('-', $_GET['page']);
			$method  = "";
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleCompte();			
			if ($url[1] == "candidat" || $url[1] == "superadmin" || $url[1] == "logiciel") {
				// $field    = "photo";
				$tmpFiles = $_FILES;
				reset($tmpFiles);
				$field    = key($tmpFiles);
			} else if ($url[1] == "entreprise") {
				$field    = "logo";
			}
			if (!empty($_FILES[$field]['name'])) { 
				if (!empty($parameters[$field])) {
					$fileField = DOC_ROOT. 'Ressources/images/' . $url[1] . 's/' . $parameters[$field];
					if (file_exists($fileField)) {
						unlink($fileField);
					} 
				}
				$fieldName = $field . "_" . time() . ".png";
                $target             = DOC_ROOT. 'Ressources/images/' . $url[1] . 's/' . basename($_FILES[$field]['name']);
                if (!file_exists(DOC_ROOT. 'Ressources/images/' . $url[1] . 's/')) {
	                mkdir(DOC_ROOT. 'Ressources/images/' . $url[1] . 's/', 0777, true);
	            }
                move_uploaded_file( $_FILES[$field]['tmp_name'], $target);
                rename($target, DOC_ROOT. 'Ressources/images/' . $url[1] . 's/' . $fieldName);
                $parameters[$field] = $fieldName;
            }
            if (isset($parameters['qualite'])) {
            	unset($parameters['qualite']);
            }
			if (reset($parameters) == '') {
				$method = "ajouter" . $methodName;
				$manager->$method($parameters);
				$_SESSION['info']['success'] = "Enregistrement avec succès ;)";
				if ($url[1] == "email_contact") {
					header("Location:" . HOST . "manage/emails_contacts");
				} elseif ($url[1] == "logiciel") {
					header("Location:" . HOST . "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard");
					
				} else {
					header("Location:" . HOST . "manage/" . $url[1] . "s");
				}
			} else {
				$method = "modifier" . $methodName;
				$manager->$method($parameters);
				$_SESSION['info']['success'] = "Modification avec succès ;)";
				if ($url[1] == "email_contact") {
					header("Location:" . HOST . "manage/emails_contacts");
				} else {
					// header("Location:" . HOST . "manage/" . $url[1] . "/dashboard");
					header("Location:" . HOST . "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard" );
				}
			}
			exit();
		}	

		/** 
		 * Mettre à jour une la colonne "publique" de la table candidat
		 *
		 * @param array $parameters Les données à mettre à jour		 *
		 * @return empty
		*/
		public function executeMettreAJourPubliqueCandidat($parameters)
		{
			$manager = new \ManagerModuleCompte();			
			$manager->modifierPublique($parameters);
			header("Location:" . HOST . "manage/candidat/dashboard");
			exit();
		}	

		/** 
		 * Modifier le statut d'une compte
		 *
		 * @param array $parameters Le compte concerné
		 *
		 * @return empty
		*/
		public function executeModifierCompte($parameters)
		{
			$url     = explode('-', $_GET['page']);
			$manager = new ManagerCompte();
			if ($url[1] == "compte") {
				if (isset($parameters['login'])) {
					$parameters['login'] = "LIKE %" . $parameters['login'] . "%";
				}
				$compte  = $manager->chercher($parameters);
				if ($url[0] == "manage/archive") {
					if ($compte->getStatut() == "archive") {
						$parameters['statut'] = "active";
					} else {
						$parameters['statut'] = "archive";
					}
				} else {
					if ($compte->getStatut() == "desactive" || $compte->getStatut() == "archive") {
						$parameters['statut'] = "active";
					} else if ($compte->getStatut() == "active") {
						$parameters['statut'] = "desactive";
					}
				}
				$manager = new \ManagerModuleCompte();
				$manager->modifierCompte($compte, $parameters);
				header("Location:" . $_SERVER['HTTP_REFERER']);
			} else {
				if ($_SESSION['compte']['login'] === $parameters['login']) {
					$_SESSION['info']['warning'] = "Utilisez le différent pseudonyme qu'auparavant";
					header("Location:" . $_SERVER['HTTP_REFERER']);
				} else {
					$compte  = $manager->chercher(['login' => "LIKE %" . $parameters['login'] . "%"]);
					if (empty($compte)) {
						$manager->modifier($parameters);
						$_SESSION['compte']['login'] = $parameters['login'];
						$_SESSION['info']['success'] = "Pseudo modifié avec succès";
						header("Location:" . HOST . "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard");
					} else {
						$_SESSION['info']['warning'] = "Pseudo existe déjà";
						header("Location:" . $_SERVER['HTTP_REFERER']);
					}
				}
			}
			exit();
		}

		/** 
		 * Modifier le mot de passe
		 *
		 * @param array $parameters L'utilisateur concerné
		 *
		 * @return empty
		*/
		public function executeModifierPassword($parameters)
		{
			$manager = new ManagerCompte();
			$compte  = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
			if (count($parameters) != 1) {
				if ($compte->getMotDePasse() == md5($parameters['ancienMotDePasse'])) {
					if ($parameters['motDePasse'] == $parameters['confirmation']) {
						$parameters['motDePasse'] = md5($parameters['motDePasse']);
						unset($parameters['confirmation']); 
						unset($parameters['ancienMotDePasse']);
						$manager->modifier($parameters);
						$_SESSION['info']['success'] = "Mot de passe modifié avec succès";
					} else {
						$_SESSION['info']['warning'] = "Confirmation nouveau mot de passe incorrect :/";
					}
				} else {
					$_SESSION['info']['danger'] = "Ancien mot de passe incorrect !";
				}
			}
			header("Location:" . HOST . "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard");
			exit();
		}

		/** 
		 * Supprimer une ligne dans une table
		 *
		 * @param array $parameters Données 
		 *
		 * @return empty
		*/
		public function executeSupprimer($parameters)
		{
			$url        = explode('-', $_GET['page']);
			$method     = "";
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager  = new \ManagerModuleCompte();
			$method   = "supprimer" . $methodName;
			$resultat = $manager->$method($parameters);
			if ($url[1] == "email_contact") {
				header("Location:" . HOST . "manage/emails_contacts");
			} else {
				$redirect = $_SERVER["HTTP_REFERER"] == HOST . "manage/candidat/dashboard" ? $_SESSION['compte']['identifiant'] . "/dashboard" : $url[1] . "s";
				header("Location:" . HOST . "manage/" . $redirect);
			}
			exit();
		}

		/** 
		 * Afficher le form de personnalité
		 *
		 * @param array $parameters Données à afficher
		 *
		 * @return array
		*/
		public function executeAfficherFormPersonnalite($parameters)
		{
			$resultats     = array();
			$manager       = new \ManagerModuleCompte();
			$personnalites = $manager->listerPersonnalites();
			if (isset($parameters)) {
				$candidat  = $manager->chercherCandidat($parameters);
				$resultats = [
					'candidat' => $candidat, 
					'personnalites' => $personnalites
				];
			}
			return $resultats;
		}

		/**
		 * Afficher le formulaire de configuration
		 *
		 * @param array $parameters Données à afficher
		 *
		 * @return array
		*/
		public function executeAfficherFormConfiguration($parameters)
		{
			$url = explode('-', $_GET['page']);
			if (!empty(($url[1])) && $url[1] == 'alerte') {
				$parameters['page'] = 'alerte';
			} elseif (!empty(($url[1])) && $url[1] == 'document') {
				$parameters['page'] = 'document';
			}
			$manager = new \ManagerModuleCompte();
			$methode  = "afficherFormConfiguration";
			return $manager->$methode($parameters);
		}

		/**
		 * Afficher la boite aux lettres
		 *
		 * @param array $parameters les données à afficher
		 *
		 * @return array
		*/
		public function executeVoirMessage($parameters)
		{
			$manager = new \ManagerModuleCompte();
			$methode  = "voirMessage";
			return $manager->$methode($parameters);
		}

		/**
		 * Marquer un message comme lu
		 *
		 * @param array $parameters critères du message à marquer
		 *
		 * @return empty
		*/
		public function executeMarquerMessage($parameters)
		{
			$manager = new \ManagerModuleCompte();
			$methode  = "marquerMessage";
			$manager->$methode($parameters);
			header("Location:" . HOST . "manage/show-messages");
		}

		/**
		 * Archiver un message
		 *
		 * @param array $parameters critères du message à archiver
		 *
		 * @return empty
		*/
		public function executeArchiverMessage($parameters)
		{
			$manager = new \ManagerModuleCompte();
			$methode  = "archiverMessage";
			$manager->$methode($parameters);
			header("Location:" . HOST . "manage/show-messages");
		}

		/**
		 * Restaurer un message
		 *
		 * @param array $parameters critères du message à restaurer
		 *
		 * @return empty
		*/
		public function executeRestaurerMessage($parameters)
		{
			$manager = new \ManagerModuleCompte();
			$methode  = "restaurerMessage";
			$manager->$methode($parameters);
			header("Location:" . HOST . "manage/show-messages");
		}

		/**
		 * Vérifier les nouveaux messages
		 *
		 * @param array $parameters les critères des données à vérifier
		 *
		 * @return string
		*/
		public function executeVerifierNouveauMessage($parameters)
		{
			$manager = new \ManagerModuleCompte();
			$methode  = "verifierNouveauMessage";
			return $manager->$methode($parameters);
		}

		/** 
		 * Créer un comtpe
		 *
		 * @param array $parameters Les données à créer
		 *
		 * @return empty
		*/
		public function executeCreerCompte($parameters)
		{
			$manager = new ManagerCompte();
			if (count($parameters) != 1) {
				$login = $manager->chercher(['login' => "LIKE %" . $parameters['login'] . "%"]);
				if (empty($login)) {
					if ($parameters['identifiant'] == 'candidat' || $parameters['identifiant'] == "employe") {
						$parameters['statut'] = 'active';
					} 
					$_SESSION['variable'] = [
						"identifiant" => $parameters['identifiant'],
						"login"       => $parameters['login'],
						"motDePasse"  => $parameters['motDePasse'],
						"statut"      => $parameters['statut']
					];
					header("Location:" . HOST . "manage/register-" . $_SESSION['variable']['identifiant']);
					exit();
				} else {
					$_SESSION['info']['warning'] = "Pseudo existe déjà";
					if ($_SESSION['compte']['identifiant']) {
						header("Location:" . HOST . "manage/employes");
						exit();
					}
				}
			}
		}

		/**
		 * Voir l'organigramme de l'entreprise
		 *
		 * @param array $parameters les données à afficher
		 *
		 * @return array
		*/
		public function executeVoirOrganigramme($parameters)
		{
			$manager = new \ManagerModuleCompte();
			return $manager->voirOrganigramme($parameters);
		}

		/**
		 * Récupérer de données dans une table
		 *
		 * @param array $parameters les données à récupérer
		 *
		 * @return empty
		*/
		public function executeGet($parameters)
		{
			$url        = explode('-', $_GET['page']);
			$method     = "";
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$method   = "get" . $methodName;
			$manager  = new \ManagerModuleCompte();
			$resultat = $manager->$method($parameters);
		}

		// changelog BILL 
		/**
		 * ny ataony
		 *
		 * @param array $parameters les données à afficher
		 *
		 * @return array
		*/
		public function executeCreateLangue($parameters)
		{
			$manager 	= new \ManagerModuleCompte();
			$resultat 	= $manager->ajouterLangue($parameters);
			if (!headers_sent()) {
			    header("Location:" .HOST. "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard");
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
			}
		}

		public function executeCreateExperienceCandidat($parameters)
		{
			$manager 	= new \ManagerModuleCompte();
			$resultat 	= $manager->ajouterExperience($parameters);
			if (!headers_sent()) {
			    header("Location:" .HOST. "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard");
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
			}
		}

		public function executeCreateFormationCandidat($parameters)
		{
			$manager 	= new \ManagerModuleCompte();
			$resultat 	= $manager->ajouterFormation($parameters);
			if (!headers_sent()) {
			    header("Location:" .HOST. "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard");
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
			}
		}

		public function executeCreateLogiciel($parameters)
		{
			$manager 	= new \ManagerModuleCompte();
			$resultat 	= $manager->ajouterLogiciel($parameters);
			if (!headers_sent()) {
			    header("Location:" .HOST. "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard");
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
			}
		}

		public function executeCreateCentreInteret($parameters)
		{
			$manager 	= new \ManagerModuleCompte();
			$resultat 	= $manager->ajouterCentreInteret($parameters);
			if (!headers_sent()) {
			    header("Location:" .HOST. "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard");
			    exit();
			} else {
				echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
			}
		}

		public function executePhotoUpdate($parameters)
		{
			$url 		= explode('-', $_GET['page']);
			$manager 	= new \ManagerModuleCompte();			
			if ($_SESSION['compte']['identifiant'] === "candidat" || $_SESSION['compte']['identifiant'] === "superadmin") {
				$field	= "photo";
			} else if ($url[1] === "entreprise") {
				$field	= "logo";
			}
			$place = 'profils';
			if (!empty($parameters['_'])) {
				$tmpName = explode('.', str_replace('C:\\fakepath\\','', $parameters['photo']));
				$tmpName = $tmpName[0];
				if (!empty($parameters[$field])) {
					$fileField = DOC_ROOT. 'Ressources/images/' . $_SESSION['compte']['identifiant'] . 's/';
					if (!file_exists($fileField)) {
		                mkdir($fileField, 0777, true);
					}
					$fieldName 	= $field . "_" . $parameters['_'] . ".png";
	                $target		= DOC_ROOT. 'Ressources/images/' . $_SESSION['compte']['identifiant'] . 's/' . basename(str_replace('C:\\fakepath\\','', $parameters['photo']));
	                move_uploaded_file($parameters['_'], $target);
	                rename($target,  $fileField . $fieldName);
	                $parameters[$field] = $fieldName;
				}
            }
            $resultat = $manager->modifierCandidat($parameters);
			return;
		}

		public function executeCreerCV()
		{
			$url     	= explode('/', $_GET['page']);
			$manager 	= new \ManagerModuleCompte();
			$method		= ucfirst($url[1]);
			return $manager->$method();
		}
		
		public function executeMettreAJourPhotoCouverture($parameters)
		{
			$manager = new \ManagerModuleCompte();
			$manager->modifierPhotoCouverture($parameters);
			header("Location:" . HOST . "manage/candidat/dashboard");
			exit();
		}
	}
