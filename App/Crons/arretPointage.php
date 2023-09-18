#!/usr/bin/php
<?php
		require_once(__DIR__ . '/../../Web/SplClassLoader.php');

		$entityLoader = new SplClassLoader('Entity', __DIR__ . '/../../Lib/Vendors');
		$entityLoader->register();
		$modelLoader = new SplClassLoader('Model', __DIR__ . '/../../Lib/Vendors');
		$modelLoader->register();
		$coreLoader = new SplClassLoader('Core', __DIR__ . '/../../Lib');
		$coreLoader->register();

		use \Core\DbManager;
		use \Model\ManagerEntreprise;
		use \Model\ManagerCompte;
		use \Model\ManagerEmploye;
		use \Model\ManagerPresence;
		use \Model\ManagerPointage;
		use \Model\ManagerTache;
		use \Model\ManagerParametrePointage;

		const PRESENCE_YES    = 1;
		const PRESENCE_NO     = 0; 
		const OPEN_POINTING   = 0;
		const CLOSED_POINTING = 1;
		const COMPTE_ACTIVE   = "active";

		fermerPointage();

		/**
		 * Fermer le pointage des employés de chaque entreprise
		 *
		 * @return empty
		 */
		function fermerPointage()
		{
			$today   = date('Y-m-d', strtotime('+3 hour', strtotime(gmdate('Y-m-d'))));
			$entreprises = getEntreprises();
			foreach ($entreprises as $entreprise) {
				$manager   = new ManagerParametrePointage();
				$parametre = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
				/** @changelog 29/11/2022 [EVOL] (Lansky) Ajouter un paramètre  du pointage par defaut lors création du compte */
                if (!$parametre) {
                    $parametre = $manager->ajouter([
                        'arretActive'   => 0,
                        'heureArret'    => '18:00:00',
                        'idEntreprise'  => $entreprise->getIdEntreprise(),
                        'heure_debut'   => '08:00:00',
                        'is_fixed_time' => 'YES'
                    ]);
                }
				if ($parametre != null) {
					if ($parametre->getArretActive()) {
						$employes = getEmployes($entreprise);
						foreach ($employes as $employe) {
							$manager = new ManagerPresence();
							$presence = $manager->chercher([
								'idEmploye' => $employe->getIdEmploye(),
								'date'      => $today,
								'statut'    => PRESENCE_YES
							]);
							if ($presence != null) {
								$manager  = new ManagerPointage();
								$pointage = $manager->chercher([
									'idPresence' => $presence->getIdPresence(),
									'statut'     => OPEN_POINTING
								]);
								if ($pointage != null) {
									$manager->modifier([
										'idPointage' => $pointage->getIdPointage(),
										'fin'        => strtotime($pointage->getDebut()) >= strtotime($parametre->getHeureArret())? $pointage->getDebut() : $parametre->getHeureArret(),
										'statut'     => CLOSED_POINTING
									]);
								}
							}
						}
					}
				}
			}
		}

		/**
		 * Prendre toutes les entreprises ayant un compte activé
		 *
		 * @return array
		 */
		function getEntreprises()
		{
			$manager        = new ManagerEntreprise();
			$allEntreprises = $manager->lister();
			$entreprises    = array();
			foreach ($allEntreprises as $entreprise) {
				$manager = new ManagerCompte();
				$compte  = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
				if ($compte->getStatut() == COMPTE_ACTIVE) {
					$entreprises[] = $entreprise; 
				}
			}
			return $entreprises;
		}

		/**
		 * Prendre tous les employés d'une entreprise
		 *
		 * @param object $entreprise L'entreprise où travaillent les employés
		 *
		 * @return array
		 */
		function getEmployes($entreprise)
		{
			$manager  = new ManagerEmploye();
			$employes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
			return $employes;
		}

		/**
		 * Ecrire dans un fichier 
		 *
		 * @param string $fichier le nom de fichier
		 * @param string $contenu le contenu du fichier
		 *
		 * @return empty
		 */ 
		function write($fichier, $contenu)
		{
			$file = fopen($fichier, 'w');
			fwrite($file, $contenu);
			fclose($file);
		}

	   /**
		* Ecriture dans un fichier de log dans \em_misc::getSpecifPath() . "logs/debug.log"
		*
		* @param string $logMessage
		*
		* @return null
		*/
		function writeLog($var)
		{
			ob_start();
			$msg = ob_get_clean();
			file_put_contents(
				CRON_DIR  . "debug.log",
				date("Y-m-d H:i:s") . " : " . $msg . "\n",
				FILE_APPEND
			);
		}
?>