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
		use \Model\ManagerTache;

		const PRESENCE_YES  = 1;
		const PRESENCE_NO   = 0; 
		const COMPTE_ACTIVE = "active";

		creerPresence();


		/**
		 * Créer la présence du jour de chaque employe de chaque entreprise
		 *
		 * @return empty
		 */
		function creerPresence()
		{
			$entreprises 	= getEntreprises();
			 $today   		= date('Y-m-d');
			foreach ($entreprises as $entreprise) {
				$employes = getEmployes($entreprise);
				foreach ($employes as $employe) {
					$manager 	= new ManagerPresence();
					$presence 	= $manager->chercher([
						'idEmploye' => $employe->getIdEmploye(),
						'date'      => $today
					]);
					if ($presence == null) {
						$manager->ajouter([
							'idEmploye' => $employe->getIdEmploye(),
							'date'      => $today,
							'statut'    => PRESENCE_NO
						]);
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
?>