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
	use \Model\ManagerStockConge;

	const PRESENCE_YES  = 1;
	const PRESENCE_NO   = 0; 
	const COMPTE_ACTIVE = "active";
	const STOCK_MENSUEL = 2.5;
	const UNE_ANNEE     = 1;
	const DEUX_ANNEES   = 2;

	creerNouveauStockConge();

	/**
	 * Créer une ligne de stock de congé de chaque employe de chaque entreprise
	 *
	 * @return empty
	 */
	function creerNouveauStockConge()
	{
		$annee = date('Y');
		$entreprises = getEntreprises();
		foreach ($entreprises as $entreprise) {
			$employes = getEmployes($entreprise);
			foreach ($employes as $employe) {
				$manager = new ManagerStockConge();
				$stock   = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'annee' => $annee]);
				$anneeMoinsUn  = intval($annee) - UNE_ANNEE;
				$anneeMoinDeux = intval($annee) - DEUX_ANNEES;
				$stockMoinsUn  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'annee' => $anneeMoinsUn]);
				$duree         = 0;
				if ($stockMoinsUn != null) {
					$duree          += $stockMoinsUn->getDuree();
					$stockMoinsDeux  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'annee' => $anneeMoinsDeux]);
					if ($stockMoinsDeux != null) {
						$duree   += $stockMoinsUn->getDuree();
					}
				}
				if ($stock == null) {
					$stock = $manager->ajouter([
						'idEmploye'  => $employe->getIdEmploye(),
						'annee'      => $annee,
						'duree'      => $duree       
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