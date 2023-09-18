<?php
	
	/**
	 * Desc: Controlleur du Module Compte dans Frontend
	 *
	 * @author Voahirana
	 *
	 * @since 26/09/19 
	 */

	namespace App\Frontend\Modules\Compte;

	use \Core\BackController;
	use \Core\PublicFonctions;

    use \Model\ManagerCompte;
    use \Model\ManagerParametrePointage;
    use \Model\ManagerEmploye;
    use \Model\ManagerEmailContact;
    use \Model\ManagerEntreprise;
    use \Model\ManagerRetard;
    use \Model\ManagerPresence;
    use \Model\ManagerJourFerie;

    require(__DIR__ . "/Model/ManagerModuleCompte.php");
    
	class CompteController extends BackController
	{

		/** 
		 * Desc: se connecter 
		 *
		 * @param array $parameters Desc: Les données postées
		 *
		 * @return empty
		*/
		public function executeConnecter($parameters)
		{	
			$userManager 				= "";
			$user        				= "";
			$userIp      				= isset($parameters['userIp']) ? $parameters['userIp'] : '';
			$manager     				= new ManagerCompte();
			$today   					= date('Y-m-d', strtotime('+3 hour', strtotime(gmdate('Y-m-d'))));
			$currentTime 				= date('H:i:s', strtotime('+2 hours'));
			$managerParametrePointage 	= new ManagerParametrePointage();
			if (isset($parameters['userIp'])) {
				\array_splice($parameters, 2, 1); // Delete column userIp of array $parameters
			}
			if (!empty($parameters)) {
				$parameters['motDePasse'] 	= md5($parameters['motDePasse']);
				// $compte 					= $manager->chercher($parameters);
				$compte 					= $manager->chercher([
					'login' 		=> "LIKE %" . $parameters['login'] . "%",
					'motDePasse' 	=> $parameters['motDePasse']
				]);
				echo "<pre>";
				// var_dump($parameters);
				// 
				if (!empty($compte)) {
             		/**@changelog 05/09/2022 [OPT] (Lansky) Changer le contenu de la dernière connexion d'un utilisateur et créer un historique de conexion enregister dans la base pour la sécurité d'un compte */
					$history 	= null;
					$loginTime 	= date("Y-m-d H:i:s", strtotime('+2 hours'));
					if ($compte->getLastLoggedin()) {
						$tabHistory = array();
						if($compte->getHistory()) {
							$tabHistory = $compte->getHistory();
						}
						$tabHistory[] 	= array('login' => $loginTime, 'logout' => $compte->getLastLoggedout(), 'userAgent' => $_SERVER['HTTP_USER_AGENT'], 'userIp' => $_SERVER['REMOTE_ADDR']);
						$history 		= serialize($tabHistory);
					}
					$compte = $manager->modifier(['idCompte' => $compte->getIdCompte(), 'last_loggedin' => $loginTime, 'history' => $history]);
					$compte = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
					if ($compte->getIdentifiant() == 'employe') {
						$manager 		= new ManagerEmploye();
						// Récupère l'identification de l'entreprise
						$employe 		= $manager->chercher(['idCompte' => $compte->getIdCompte()]);
            			$paramsPointing = $managerParametrePointage->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
						$accept			= $this->locateUser($userIp, $employe->getIdEntreprise());
						if (!$accept && $paramsPointing->getReceiveNotif() == 1) {
							$manager = new ManagerEntreprise();
							$this->sendNotifEntreprise($manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]) , $employe);
						}
						/** @changelog 11/10/2022 [EVOL] (Lansky) Vérifier si le salarié est en retard */
						$manager 		= new ManagerPresence();
                       	$dayPresence 	= $manager->chercher([
                       		'idEmploye' => $employe->getIdEmploye(),
                       		'date' 		=> $today
                       	]);
                       	if (is_null($dayPresence)) {
                       		$manager->ajouter([
	                       		'idEmploye' => $employe->getIdEmploye(),
	                       		'date' 		=> $today,
	                       		'statut' 	=> 0
	                       	]);
                       		$dayPresence = $manager->chercher([
	                       		'idEmploye' => $employe->getIdEmploye(),
	                       		'date' 		=> $today
	                       	]);
                       	}
                        if ($paramsPointing && $dayPresence->getStatut() == 0) {
                        	if ($paramsPointing->getIsFixedTime() == 'YES') {
	                            if (strtotime($currentTime) > strtotime($paramsPointing->getHeureDebut())) {
	                               $manager = new ManagerRetard();
	                               $retard 	= $manager->chercher([
	                               		'id_employe' 	=> $employe->getIdEmploye(),
	                               		'id_presence' 	=> $dayPresence->getIdPresence(),
	                               		'date' 			=> 'LIKE %' . $today . '%'
	                               ]);
	                               $during 	= date('H:i:s', abs(strtotime($currentTime) - strtotime($paramsPointing->getHeureDebut())));
	                               $during 	= date('H:i:s', strtotime($during) + strtotime('01:00:00'));
	                               if (is_null($retard) && !$this->inPlanning($employe, $today)) {
		                               	$retard = $manager->ajouter([
		                               		'during' 		=> $during,
		                               		'date' 			=> date('Y-m-d H:i:s', strtotime('+3 hour', strtotime(gmdate('Y-m-d H:i:s')))),
		                               		'is_retrieved' 	=> 'NO',
		                               		'id_employe' 	=> $employe->getIdEmploye(),
		                               		'id_presence' 	=> $dayPresence->getIdPresence()
		                               	]);
	                               }
	                            }

                            } else {
                            	// À reflechir rehf miovahova ny ora fidirana d ato no verifiena
                            }
                        }
					}
					if ($compte->getStatut() == "active") {
						// si compte activé
						$userManager        = "\Model\Manager" . ucfirst($compte->getIdentifiant());
						$manager            = new $userManager();
						$user               = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
						$_SESSION['compte'] = $compte->toArray();
						$_SESSION['user']   = $user->toArray();
						if (isset($_SERVER['HTTP_REFERER'])) {
							header("Location:" . $_SERVER['HTTP_REFERER']);
							
						} else {
							header("Location:" . HOST . "manage/" . $compte->getIdentifiant() . "/dashboard");
						}
						exit();
		            } else {
		            	$_SESSION['info']['danger'] = "Votre compte est actuellement désactivé ";
		            	// $_SESSION['info']['danger'] = "Votre compte n'est pas encore activé ";
		            }
	            } else {
	            	$compte 					= $manager->chercher(['login' 		=> "LIKE %" . $parameters['login'] . "%"]);
	            var_dump($compte);
	            	
	            	$errorText = $compte ? "Mot de passe incorrect !" : "Pseudo incorrect !";
	            	$_SESSION['info']['danger'] = $errorText;
	            }
				exit;
			}
		}

		/** @changelog 07/02/2022 [EVOL] (Lansky) Localiser l'utilisateur lors tentative de connexion */
		/** 
		 * Localiser l'utilisateur
		 *
		 * @param array $userIp Les données concernant l'utilisateur connecté
		 * @param int $idEntreprise Identification de l'entreprise de l'utilisateur connecté
		 *
		 * @return bool
		*/
		private function locateUser($userIp, $idEntreprise)
		{
		   	// Insérer l'addresse IP dans la session d'utilisateur connecter
		   	$_SESSION['ip_address']	= $userIp;
		   	$manager 	= new ManagerParametrePointage();
		   	$ips 		= $manager->chercher(['idEntreprise' => $idEntreprise]);
		   	if ($ips == null) {
		   		$ips = $manager->initialiser();
		   	}
		   	if ($ips->getListIp()) {
			   	foreach ($ips->getListIp() as $ip) {
			   		$response 	= in_array($userIp,$ip, true);
			   		if ($response) { break; }
			   	}
		   	} else {
		   		$response = false;
		   	}
		   	return $response;
		}
		/** @changelog 09/02/2022 [EVOL] (Lansky) */
		/**
         * Envoyer une notification à l'entreprise lors d'une pointage distanciée
         * 
         * @param objet $entreprise 
         * @param objet $employe 
         *
         * @return empty
        */
		private function sendNotifEntreprise ($entreprise, $employe) {
			setlocale(LC_TIME, "it_IT");
			setlocale(LC_TIME, "fr_FR.UTF-8");
			$manager 	= new ManagerEmailContact();
			$to         = $entreprise->getEmail();
            $headers[]  = "MIME-Version: 1.0";
            $headers[]  = 'Content-type: text/html; charset=iso-8859-1';
            $headers[]  = 'From: ' . $manager->chercher(['type' => 'information'])->getEmail();
            $subject    = "Tentative de se connecter";
            $message    = "<html><body>
                            <div class='container'>
                                <label>Bonjour ,  </label><br><br>
                                <label> Nous vous informons que : " . $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() . " a tenté(e) de se connecter à son compte alors que ce salarié n'est pas encore au sein de votre société en ce moment.
                                 </label><br>
                                <label>Adresse IP: " . $_SERVER['REMOTE_ADDR'] . "</label><br>
                                <label>Apparail utilisée: " . $_SERVER['HTTP_USER_AGENT'] . "</label><br>
                                 <br><br>
                                <label>Cordialement, </label>
                                <br><br><br>
                                <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                            </div>
                        </body></html>";
            $mail = mail($to, $subject, $message, implode("\r\n", $headers));
		}

		/** @changelog 08/02/2022 [EVOL] (Lansky)  */
		/** 
		 *
		 * @param int $int Last indice 
		 *
		 * @return SESSION
		*/
	  	private function last_login_is_recent() {
	    	$recent_limit = 60 * 60 * 24 * 1; // 1 day
	    	if(!isset($_SESSION['last_login'])) { return false; }
	    	return (($_SESSION['last_login'] + $recent_limit) >= time());
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
					if ($parameters['identifiant'] != 'candidat') {
						$parameters['statut'] = 'desactive';
					}
					$_SESSION['variable'] = [
						"identifiant" 	=> $parameters['identifiant'],
						"login"       	=> $parameters['login'],
						"motDePasse"  	=> $parameters['motDePasse'],
						"statut"      	=> $parameters['statut'],
						"nomCreateur"	=> isset($parameters['nomCreateur']) ? $parameters['nomCreateur'] : null,
						"mailCreateur" 	=> isset($parameters['mailCreateur']) ? $parameters['mailCreateur'] : null,
						"contactRapide" => isset($parameters['contactRapide']) ? $parameters['contactRapide'] : null
					];
					header("Location:" . HOST . "register-" . $_SESSION['variable']['identifiant']);
					exit();
				} else {
					$_SESSION['info']['warning'] = "Pseudo existe déjà";
				}
				
			}
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
			$manager = new \ManagerModuleCompte();
			$method  = "afficherForm" . ucwords($url[1]);
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
			$manager = new \ManagerModuleCompte();
			if ($url[1] == "candidat") {
				$field = "photo";
			} else {
				$field = "logo";
			}
			if (!empty($_FILES[$field]['name'])) { 
                $target             = DOC_ROOT. 'Ressources/images/' . $url[1] . 's/' . basename($_FILES[$field]['name']);
                move_uploaded_file( $_FILES[$field]['tmp_name'], $target);
                $parameters[$field] = basename($_FILES[$field]['name']);
            }
            $method   = "ajouter" . ucwords($url[1]);
			$customer = $manager->$method($parameters);
			if (is_object($customer)) {
				$msg = $this->getMessage($customer);
				if (!empty($msg)) {
					extract($msg);
					$manager->sendEmail($customer->getEmail(), $subject, $message);
				}
				$_SESSION['variable']['email'] = $customer->getEmail();
				$_SESSION['info']['success'] = "Vous êtes maintenant inscrit &#x1F609;";
				header("Location:" . HOST . "connexion");
				exit();
			} else {
				$_SESSION['info']['danger'] = "Une erreur s'est produite. Veuillez remplir le formulaire correctement &#x1F612; ! ";
			}
		}

		/** 
		 * Message envoyé par un superadmin après inscription
		 *
		 * @param object $customer le client qu'on va inscrire
		 *
		 * @return array 
		*/
		private function getMessage($customer)
		{
			$resultat = array();
			$manager  = new ManagerCompte();
			$compte   = $manager->chercher(['idCompte' => $customer->getIdCompte()]);
			if ($compte->getIdentifiant() == "candidat") {
				$resultat['message'] = "<html><body>
											<div class='container'>
												<label>Bonjour " . ucwords($customer->getPrenom()) . ", </label><br><br>
												<label>Bienvenu sur notre nouvelle plateforme.</label><br><br>
												<label>
												  Votre compte candidat vous permettra de recevoir et d’être suggéré automatiquement<br> à toutes les offres d’emploi en adéquation avec votre profil.<br>
												  Nous vous invitons à bien remplir votre profil pour plus de chance.
												</label><br><br>
												<label>Ci-après votre login et mot de passe :</label><br><br>
												<strong>Login : </strong> " . $compte->getLogin() . "<br>
												<strong>Mot de passe : </strong> " . $_SESSION['variable']['motDePasse'] . "<br><br>
												<label>Lien d'accès sur votre compte : <a href='" . HOST . "connexion'>" . HOST . "</a></label><br><br>
												<label>Cordialement, </label><br><br>
												<label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
											</div>
										</body></html>";
				$resultat['subject'] = "Ouverture de votre compte candidat_Human Cart'Office";
			} else if ($compte->getIdentifiant() == "entreprise") {
				$resultat['message'] = "<html><body>
											<div class='container'>
												<label>Bonjour " . strtoupper($customer->getNom()) . ", </label><br><br>
												<label>Human Cart’Office vous souhaite la bienvenue sur sa nouvelle plateforme de gestion.</label><br><br>
												<label>
												  Votre compte entreprise vous permettra de gérer vos colaborateurs ainsi que leurs congés .
												</label><br><br>
												<label>Ci-après votre login et mot de passe :</label><br><br>
												<strong>Login : </strong> " . $compte->getLogin() . "<br>
												<strong>Mot de passe : </strong> " . $_SESSION['variable']['motDePasse'] . "<br><br>
												<label>Lien d'accès sur votre compte : <a href='" . HOST . "connexion'>" . HOST . "</a></label><br><br>
												<label>Cordialement, </label><br><br>
												<label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
											</div>
										</body></html>";
				$resultat['subject'] = "Ouverture de votre compte entreprise_Human Cart'Office";
				$manager = new \ManagerModuleCompte();
				$manager->notifierApp($customer);
			} 
			unset($_SESSION['variable']);
			return $resultat;
		}

		/** 
		 * Se déconnecter 
		 *
		 * @param array $parameters Données passées en paramètres
		 *
		 * @return empty
		*/
		public function executeDeconnecter($parameters)
		{
			if (isset($_SESSION['compte']['idCompte'])) {
				$manager 					= new ManagerCompte();
				$compte 					= $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
				$lastLogOut 				= date("Y-m-d H:i:s", strtotime('+2 hours'));
				if ($compte) {
					$histories 					= $compte->getHistory();
					$key 						= count($histories) - 1;
					$histories[$key]['logout'] 	= $lastLogOut;
					$manager->modifier(['idCompte' => $compte->getIdCompte(), 'last_loggedout' => $lastLogOut, 'history' => serialize($histories)]);
				}
				session_destroy();
			}
			header("Location:" . HOST . "accueil");
		}

		/** 
		 * Annuler une inscription 
		 *
		 * @param array $parameters Données à supprimer
		 *
		 * @return empty
		*/
		public function executeCancelCompte($parameters)
		{	
			unset($_SESSION['variable']); 
			$_SESSION['info']['danger'] = "inscription annulée";
			header("Location:" . HOST . "accueil");
			exit();
		}

		/** 
		 * Envoyer le message sur le formulaire de contact  
		 *
		 * @param array $parameters les messages à envoyer
		 *
		 * @return empty
		*/
		public function executeSendMessage($parameters)
		{	
			$manager = new \ManagerModuleCompte();
			$manager->sendMessage($parameters);
			header("Location:" . HOST . "accueil#contact");
			exit();
		}
		
 		/**@changelog 01/12/2022 [OPT] (Lansky) Vérifier si le planning du salarié est en congé, ou en repos, ou en permission, ou jour férié */
		/** 
		 * Vérifier si le planning du salarié est en congé, ou en repos, ou en permission, ou jour férié
		 *
		 * @param object $employe 	L'identifiant du salarié connecté
		 * @param date $date 		La date
		 *
		 * @return bool
		*/
		private function inPlanning ($employe, $date)
		{	
			$manager    = new ManagerJourFerie();
            $jourFerie = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise(), 'date' => $date]);
            $isweekEnd = date('l', strtotime($date)) == 'Saturday' || date('l', strtotime($date)) == 'Sunday' ? true : false;
			return PublicFonctions::estEnConge($employe, $date) || PublicFonctions::estEnPermission($employe, $date) || PublicFonctions::estEnRepos($employe, $date) || $jourFerie || $isweekEnd ? true : false;
		}
	}
