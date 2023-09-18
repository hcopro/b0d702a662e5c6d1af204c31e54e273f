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
	use \Model\ManagerParametreConge;
	use \Model\ManagerContratEmploye;
	use \Model\ManagerContrat;

	const PRESENCE_YES      = 1;
	const PRESENCE_NO       = 0; 
	const COMPTE_ACTIVE     = "active";
	const STOCK_MENSUEL     = 2.5;
	const STOCK_ANNUEL     	= 30;
	const CALCUL_PAR_MOIS   = 0;
	const CALCUL_AU_PRORATA = 1;
	const CALCUL_PAR_AN   	= 2;
	const VALIDATED         = 2;
	const STAGE             = "stage";
	const NO                = 0;
	const ONE_MONTH         = 28;
	const ONE_YEAR         	= 12;
	const NUMBER_DAY        = 30;

	incrementerStockCongeMensuel();

	/**
	 * Incrémenter les stocks de congé de chaque employe de chaque entreprise
	 *
	 * @return empty
	 */
	function incrementerStockCongeMensuel()
	{
		$annee 			= date('Y');
		$entreprises 	= getEntreprises();
		foreach ($entreprises as $entreprise) {
			$manager  	= new ManagerParametreConge();
			$parametre 	= $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
			$employes 	= getEmployes($entreprise);
			foreach ($employes as $employe) {
				$manager 	= new ManagerStockConge();
				$stock   	= $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'annee' => $annee]);
				$debut 		= getDateEmbauche($employe->getIdEmploye());
				if ($stock != null) {
					$dureeSolde = $stock->getDuree();
					if ($debut != null) {
						$dureeContrat = getDuree($debut, date('Y-m-d'));
						if ($parametre->getCalcul() == CALCUL_PAR_MOIS) {
							if ($dureeContrat['day'] >= ONE_MONTH) {
								$dureeSolde += STOCK_MENSUEL;
							}
						} elseif ($parametre->getCalcul() == CALCUL_AU_PRORATA) {
							if ($dureeContrat['day'] >= ONE_MONTH) {
								$dureeSolde += STOCK_MENSUEL;
							} else {
								$dureeSolde += ($dureeContrat['day'] * STOCK_MENSUEL) / ONE_MONTH; 
							}
						} elseif ($parametre->getCalcul() == CALCUL_PAR_AN && date('m') == 12) {
							if ($dureeContrat['day'] >= ONE_MONTH) {
								$dureeSolde += STOCK_ANNUEL;
							}
						}
					}
					$manager->modifier([
						'idStockConge' => $stock->getIdStockConge(),
						'duree'        => number_format($dureeSolde, 2)
					]);
				} else {
					if ($debut != null) {
						$dureeContrat 	= getDuree($debut, date('Y-m-d'));
						if ($parametre->getCalcul() == CALCUL_PAR_MOIS) {
							if ($dureeContrat['day'] >= ONE_MONTH) {
								$dureeSolde = STOCK_MENSUEL;
							}
						} elseif ($parametre->getCalcul() == CALCUL_AU_PRORATA) {
							if ($dureeContrat['day'] >= ONE_MONTH) {
								$dureeSolde = STOCK_MENSUEL;
							} else {
								$dureeSolde = ($dureeContrat['day'] * STOCK_MENSUEL) / ONE_MONTH;
							}
						} elseif ($parametre->getCalcul() == CALCUL_PAR_AN && date('m') == 12) {
							if ($dureeContrat['day'] >= ONE_MONTH) {
								$start 		= new DateTime("now");
								$end 		= new DateTime("+" . $dureeContrat['day'] . " days");
								$diff 		= date_diff($start, $end);
								$dureeSolde	= $months < 12 ? ($diff->m * STOCK_MENSUEL) + ($diff->d > 0 ? (($diff->d * STOCK_MENSUEL) / NUMBER_DAY) : 0) : STOCK_ANNUEL;
							}
						}
					}
					$manager = new ManagerStockConge();
					$manager->ajouter([
						'duree'        	=> number_format($dureeSolde, 2),
						'annee' 		=> $annee
					]);
				}
			}
		}
	}

	/**
     * Obtenir la date d'embauche d'un employé
     *
     * @param int $idEmploye l'identfiant de l'employé
     *
     * @return date
     */
    function getDateEmbauche($idEmploye)
    {
        $manager          = new ManagerContratEmploye();
        $contratEmploye   = $manager->chercher(['idEmploye' => $idEmploye, 'statut' => VALIDATED]);
        if ($contratEmploye != null) {
            $manager          = new ManagerContrat();
            $typeContrat      = $manager->chercher(['idContrat' => $contratEmploye->getType()]);
            if (strtolower($typeContrat->getDesignation()) == strtolower(STAGE)) {
                return $contratEmploye->getDateDebut();
            } else {
                while ($contratEmploye->getPrecedent() != NO) {
                    $manager          = new ManagerContratEmploye();
                    $tmp              = $manager->chercher(['idContratEmploye' => $contratEmploye->getPrecedent()]);
                    $manager          = new ManagerContrat();
                    $typeContrat      = $manager->chercher(['idContrat' => $tmp->getType()]);
                    if (strtolower($typeContrat->getDesignation()) == strtolower(STAGE)) {
                        break;
                    } else {
                        $contratEmploye = $tmp;
                    }
                }
                return $contratEmploye->getDateDebut();
            }
        }
        return null;
    }

    /**
     * Calculer la différence entre 2 dates
     *
     * @param date $date1 date1
     * @param date $date2 date2
     *
     * @return array
     */
    function getDuree($date1, $date2)
    {
        $difference       = abs(strtotime($date2) - strtotime($date1));
        $retour           = array();
        $tmp              = $difference;
        $retour['second'] = $tmp % 60;
        $tmp              = floor(($tmp - $retour['second']) / 60);
        $retour['minute'] = $tmp % 60;
        $tmp              = floor(($tmp - $retour['minute']) / 60);
        $retour['hour']   = $tmp % 24;
        $tmp              = floor(($tmp - $retour['hour'])  / 24);
        $retour['day']    = $tmp;
        return $retour;
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