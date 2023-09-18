<?php

    /**
     * Manager du modules Paie du Backend
     *
     * @author Toky
     *
     * @since 20/10/20
     */

	use \Core\DbManager;
    use \Core\View;
    use \Model\ManagerEmploye;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerEntrepriseService;
    use \Model\ManagerCompte;
    use \Model\ManagerEntreprise;
    use \Model\ManagerContratEmploye;
    use \Model\ManagerServicePoste;
    use \Model\ManagerContrat;
    use \Model\ManagerRenouvellement;
    use \Model\ManagerCategorieProfessionnelle;
    use \Model\ManagerStockConge;
    use \Model\ManagerHeureEmploye;
    use \Model\ManagerParametreHeure;
    use \Model\ManagerParametrePaie;
    use \Model\ManagerAvantage;
    use \Model\ManagerAvantageEmploye;
    use \Model\ManagerPresence;
    use \Model\ManagerPointage;
    use \Model\ManagerEntrepriseFerie;
    use \Model\ManagerFichePaie;
    use \Model\ManagerConge;
    use \Model\ManagerDeduction;
    use \Model\ManagerAvance;
    use \Model\ManagerRemboursement;
    use \Model\ManagerParametreAvance;
    use \Model\ManagerMessage;
    use \Model\ManagerAvanceQuinzaine;
    require_once "Lib/Core/PhpDocx.php";

	class ManagerModulePaie extends DbManager
	{
        const STAGE                             = 'stage';
        const MONDAY                            = 'Lundi';
        const TUESDAY                           = 'Mardi';
        const WEDNESDAY                         = 'Mercredi';
        const THURSDAY                          = 'Jeudi';
        const FRIDAY                            = 'Vendredi';
        const SATURDAY                          = 'Samedi';
        const SUNDAY                            = 'Dimanche';
        const USER_ENTREPRISE                   = "entreprise";
        const USER_EMPLOYE                      = "employe";
        const VALIDATED                         = 2;
        const EXPIRED                           = 3;
        const NO                                = 0;
        const YES                               = 1;
        const OK                                = 1;
        const WRONG                             = 0;
        const NOT_YET                           = 0;
        const IMPOSABLE_POUR_TOUS               = 1;
        const NON_IMPOSABLE_POUR_TOUS           = 0;
        const IMPOSABLE_PAR_DEFAUT              = 3;
        const NON_IMPOSABLE_PAR_DEFAUT          = 2;
        const FILTER_GROUP_ALL                  = 1;
        const FILTER_GROUP_SERVICE              = 2;
        const FILTER_GROUP_POSTE                = 3;
        const FILTER_GROUP_EMPLOYE              = 4;
        const NOMBRE_MOIS                       = 12;
        const UNE_ANNEE                         = 12;
        const NOMBRE_SEMAINES                   = 52;
        const COEFFICIENT_PLAFOND_CNAPS         = 8.0;
        const COEFFICIENT_PLAFOND_OSTIE         = 8.0;
        const COEFFICIENT_CNAPS                 = 1;
        const COEFFICIENT_OSTIE                 = 1;
        const MAJORATION_DIMANCHE_OCCASIONNEL   = 1.4;
        const MAJORATION_DIMANCHE_HABITUEL      = 1.5;
        const MAJORATION_FERIE                  = 1.5;
        const MAJORATION_NUIT_OCCASIONNEL       = 1.5;
        const MAJORATION_NUIT_HABITUEL          = 1.3;
        const PREMIERES_HEURES_SUP              = 8.0;
        const MAJORATION_PREMIERES_HEURES_SUP   = 1.3;
        const MAJORATION_RESTES_HEURES_SUP      = 1.5;
        const LIMITE_SUPERIEURE_HEURE_NORMALE   = "22:00:00";
        const LIMITE_INFERIEURE_HEURE_NORMALE   = "05:00:00";
        const POINTAGE_OUVERT                   = 0;
        const POINTAGE_FERME                    = 1;
        const ONE_MINUTE                        = 60;
        const CENT_POUR_CENT                    = 100;
        const POURCENTAGE_IRSA                  = 20;
        const POSTE_INTERNE                     = 0;
        const SECOND_TO_DAY                     = 86400;
        const MATIN                             = 1;
        const APRES_MIDI                        = 2;
        const SOIR                              = 3;
        const DEMI_JOURNEE                      = 0.5;
        const ONE_DAY                           = 1;
        const ONE_MONTH                         = 1;
        const FULL_PRESENCE                     = 30;
        const MOIS_SANS_WEEKEND                 = 24;
        const FICHE_SAVED                       = 1;
        const FICHE_SENT                        = 2;
        const CHAMP_NOM                         = "[NOM]";
        const CHAMP_PRENOM                      = "[PRENOM]";
        const CHAMP_DEBUT                       = "[DEBUT]";
        const CHAMP_FIN                         = "[FIN]";
        const CHAMP_MOIS                        = "[MOIS]";
        const CHAMP_ANNEE                       = "[ANNEE]";
        const CHAMP_SALAIRE_BASE                = "[SALAIRE_DE_BASE]";
        const CHAMP_HEURE_EFFECTIVE             = "[HEURE_EFFECTIVE]";
        const CHAMP_SALAIRE_PRORATA             = "[SALAIRE_AU_PRORATA]";
        const CHAMP_HEURE_SUP                   = "[HEURE_SUP]";
        const CHAMP_MAJORATION_HEURE_SUP        = "[MAJ_HEURE_SUP]";
        const CHAMP_HEURE_NUIT                  = "[HEURE_NUIT]";
        const CHAMP_MAJORATION_HEURE_NUIT       = "[MAJ_HEURE_NUIT]";
        const CHAMP_HEURE_DIMANCHE              = "[HEURE_DIMANCHE]";
        const CHAMP_MAJORATION_HEURE_DIMANCHE   = "[MAJ_HEURE_DIMANCHE]";
        const CHAMP_HEURE_FERIE                 = "[HEURE_FERIE]";
        const CHAMP_MAJORATION_HEURE_FERIE      = "[MAJ_HEURE_FERIE]";
        const CHAMP_RATIO_CNAPS                 = "[RATIO_CNAPS]";
        const CHAMP_DEDUCTION_CNAPS             = "[DEDUCTION_CNAPS]";
        const CHAMP_RATIO_OSTIE                 = "[RATIO_OSTIE]";
        const CHAMP_DEDUCTION_OSTIE             = "[DEDUCTION_OSTIE]";
        const CHAMP_REVENU_IMPOSABLE            = "[REVENU_IMPOSABLE]";
        const CHAMP_IRSA                        = "[IRSA]";
        const CHAMP_QUANTITE_CHARGE             = "[QUANTITE_CHARGE]";
        const CHAMP_DEDUCTION_CHARGE            = "[DEDUCTION_CHARGE]";
        const CHAMP_IRSA_NET                    = "[IRSA_NET]";
        const CHAMP_SALAIRE_NET                 = "[SALAIRE_NET]";
        const CHAMP_SALAIRE_NET_EN_LETTRE       = "[SALAIRE_NET_EN_LETTRE]";
        const CHAMP_SALAIRE_BRUT                = "[SALAIRE_BRUT]";
        const CHAMP_SOLDE_CONGE_DEBUT           = "[SOLDE_CONGE_DEBUT]";
        const CHAMP_SOLDE_CONGE_FIN             = "[SOLDE_CONGE_FIN]";
        const CHAMP_CONGE_PRIS                  = "[CONGE_PRIS]";
        const CHAMP_QUANTITE_ALLOCATION         = "[QUANTITE_ALLOCATION]";
        const CHAMP_PRESENCE                    = "[PRESENCE]";
        const CHAMP_ALLOCATION_CONGE            = "[ALLOCATION_CONGE]";
        const CHAMP_EMBAUCHE                    = "[EMBAUCHE]";
        const CHAMP_POSTE                       = "[POSTE]";
        const TAUX_REMBOURSEMENT                = 30;
        const ALL_DEMANDE                       = 3;
        const ARCHIVED_DEMANDE                  = 4;
        const AVANCE_VALIDATED                  = 2;
        const AVANCE_PROPOSED                   = 1;
        const AVANCE_REJECTED                   = 0;
        const AVANCE_ARCHIVED                   = 0;
        const AVANCE_NOT_ARCHIVED               = 1;
        const REMBOURSEMENT_PAYE                = 1;
        const REMBOURSEMENT_EN_ATTENTE          = 0;
        const TYPE_REQUEST                      = "demande";
        const TYPE_VALIDATED                    = "valide";
        const TYPE_REJECTED                     = "rejete";
        const TYPE_CANCELED                     = "annule";
        const TYPE_INFORMATION                  = "information";
        const SEEN                              = 2;
        const PROPOSED                          = 1;
        const ALL_YEAR                          = 13;
        const OLD_MODE_IRSA                     = 1;
        const NEW_MODE_IRSA                     = 2;
        const ARRONDISSEMENT_VIRGULE            = 1;
        const ARRONDISSEMENT_ENTIER             = 2;

        private $typeArrondissement;

        /**
         * Constructeur
         *
         * @return empty
         */
        public function __construct()
        {
            $manager       = new ManagerParametrePaie();
            $parametrePaie = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $this->typeArrondissement = $parametrePaie->getTypeArrondissement();
        }

        /**
         * Voir l'interface de paie
         *
         * @param array $parameters les critères des données à afficher
         *
         * @return array
         */
        public function voirPaie($parameters)
        {
            if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                $manager        = new ManagerEntreprise();
                $entreprise     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager        = new ManagerParametrePaie();
                $parametrePaie  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $mois           = date('m');
                $annee          = date('Y');
                $mois          -= 1;
                if ($mois == 0) {
                    $mois   = 12;
                    $annee -= 1;
                }
                if ($parametrePaie == null) {
                    $parametrePaie = $manager->ajouter([
                        'idEntreprise'           => $_SESSION['user']['idEntreprise'],
                        'minimumDePerception'    => self::NOT_YET,
                        'chargeFamiliale'        => self::NOT_YET,
                        'salaireMinimumEmbauche' => self::NOT_YET
                    ]);
                }
                return [
                    'entreprise'    => $entreprise,
                    'parametrePaie' => $parametrePaie,
                    'filtres'       => $this->getFiltre($entreprise->getIdEntreprise()),
                    'mois'          => $mois,
                    'annee'         => $annee
                ];
            }
        }

        /**
         * Calculer la durée d'une congée
         *
         * @param object $demande congé à calculer
         *
         * @return float ou int
         */
         public function calculateCongeDuring ($demande)
         {
            // $heure = 0;
            if ((int)$demande->getHeureDebut()==1) {
                if ((int)$demande->getHeureFin()==2) {
                    $heure = 0.5;
                } elseif ((int)$demande->getHeureFin()==3) {
                    $heure = 1;
                }
            } elseif ((int)$demande->getHeureDebut()==2) {
                if ((int)$demande->getHeureFin()==2) {
                    $heure = 1;
                } elseif ((int)$demande->getHeureFin()==3) {
                    $heure = 0.5;
                }
            }
            return (abs(strtotime($demande->getFin()) - strtotime($demande->getDebut()))/ (60*60*24)) + $heure;
        }

        /**
         * Afficher le formulaire d'édition de fiche de paie
         *
         * @param array $parameters quelques paramètres à prendre en compte
         *
         * @return array
         */
        public function afficherFormFichePaie($parameters)
        {
            if (!empty($parameters)) {
                if (empty($parameters['sauvegarder'])) {
                    $manager    = new ManagerEmploye();
                    $employe    = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                    $manager    = new ManagerEntreprise();
                    $entreprise = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                    $manager    = new ManagerFichePaie();
                    $fichePaie  = $manager->chercher([
                        'idEmploye' => $employe->getIdEmploye(),
                        'mois'      => $parameters['mois'],
                        'annee'     => $parameters['annee']
                    ]);
                    $debut      = date($parameters['annee'] . "-" . $parameters['mois'] . "-01");
                    $fin        = date("Y-m-d", mktime(0, 0, 0, ($parameters['mois'] + 1), 0, $parameters['annee']));
                    $maxPresence = $this->getDureeJour(date('Y-m-d', strtotime(date($parameters['annee'] . '-' . $parameters['mois'] . '-01'))), date("Y-m-d", mktime(0, 0, 0, ($parameters['mois'] + 1), 0, $parameters['annee'])));
                    $smm         = $this->getSalaireMensuelMoyen($parameters['idEmploye']);
                    if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                        if ($fichePaie == null) {
                            $manager        = new ManagerParametreHeure();
                            $parametreHeure = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
                            $manager        = new ManagerAvantageEmploye();
                            $avantages      = array();
                            $avantageImposables = $manager->lister([
                                'idEmploye' => $employe->getIdEmploye(),
                                'imposable' => self::YES,
                                'mois'      => $parameters['mois'],
                                'annee'     => $parameters['annee']
                            ]);
                            foreach ($avantageImposables as $avantageImposable) {
                                $manager = new ManagerAvantage();
                                $avantage = $manager->chercher(['idAvantage' => $avantageImposable->getIdAvantage()]);
                                $avantages[$avantageImposable->getIdAvantageEmploye()] = $avantage->getLibelle();
                            }
                            $manager   = new ManagerAvantageEmploye();
                            $avantageNonImposables = $manager->lister([
                                'idEmploye' => $employe->getIdEmploye(),
                                'imposable' => self::NO,
                                'mois'      => $parameters['mois'],
                                'annee'     => $parameters['annee']
                            ]);
                            foreach ($avantageNonImposables as $avantageNonImposable) {
                                $manager  = new ManagerAvantage();
                                $avantage = $manager->chercher(['idAvantage' => $avantageNonImposable->getIdAvantage()]);
                                $avantages[$avantageNonImposable->getIdAvantageEmploye()] = $avantage->getLibelle();
                            }
                            $manager    = new ManagerDeduction();
                            $deductions = $manager->lister([
                                'idEmploye'  => $employe->getIdEmploye(),
                                'mois'       => $parameters['mois'],
                                'annee'      => $parameters['annee']
                            ]);
                            $tableauAvantages = array();
                            $manager          = new ManagerAvantage();
                            $listeAvantages   = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                            $manager          = new ManagerAvantageEmploye();
                            $listeAvantageEmployes = $manager->lister([
                                'idEmploye'        => $parameters['idEmploye'],
                                'mois'             => $parameters['mois'],
                                'annee'            => $parameters['annee']
                            ]);
                            foreach ($listeAvantageEmployes as $avantageEmploye) {
                                $manager = new ManagerAvantage();
                                $tableauAvantages[$avantageEmploye->getIdAvantage()] = $manager->chercher(['idAvantage' => $avantageEmploye->getIdAvantage()]);
                            }
                            if (empty($parameters['salaireDeBase'])) {
                                if ($this->decrypter($employe->getSalaire())) {
                                    $parameters['salaireDeBase'] = $this->decrypter($employe->getSalaire());
                                } else {
                                    $salaire                       = 0;
                                    $allocationConge               = 0;
                                    $majorationHeureSupplementaire = 0;
                                    $majorationHeureNuit           = 0;
                                    $majorationHeureDimanche       = 0;
                                    $majorationHeureFerie          = 0;
                                    $salaireBrut                   = 0;
                                    $deductionCnaps                = 0;
                                    $deductionOstie                = 0;
                                    $revenuImposable               = 0;
                                    $irsa                          = 0;
                                    $irsaNet                       = 0;
                                    $deductionCharge               = 0;
                                    $salaireNet                    = 0;
                                    $salaireNetEnLettre            = "ZERO";
                                }
                            }
                            if (empty($parameters['congePris'])) {
                                /**@changelog 11/04/2022 [OPT] (Lansky) Récuperer le solde de congé du salarié */
                                // Voir le congé a été pris du mois (selon le parametre mois)
                                $manager        = new ManagerConge();
                                $dateObj        = DateTime::createFromFormat('!m', $parameters['mois']);
                                $monthName      = $dateObj->format('F');
                                $tmpCongePris   = $manager->lister([
                                    'idEmploye' => $employe->getIdEmploye(),
                                    'debut'     => 'BETWEEN "' . date("Y-m-d", strtotime("first day of ". $monthName ."")) . '" AND "' .
                                        date("Y-m-d", strtotime("last day of ". $monthName ."")) . '"',
                                    'statut'    => self::VALIDATED
                                ]);
                                /**@changelog 30/05/2022 [OPT] (Lansky) Récupérer le congé au mois porchaine */
                                $nextConge      = $manager->lister([
                                    'idEmploye' => $employe->getIdEmploye(),
                                    'debut'     => '> "' . date("Y-m-d", strtotime("last day of ". $monthName ."")) . '"',
                                    'statut'    => self::VALIDATED
                                ]);
                                if ($tmpCongePris) {
                                    $somme = 0;
                                    foreach ($tmpCongePris as $list) {
                                        $somme += $this->calculateCongeDuring($list);
                                    }
                                    $parameters['congePris']    = $somme;
                                } else {
                                    $parameters['congePris']    = 0;
                                }
                            }
                            if (empty($parameters['soldeCongeFin'])) {
                                $manager    = new ManagerStockConge();
                                $tmpSold    = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'annee' => $parameters['annee']])->getDuree();
                                // Vérifier le congé pris du mois courant (ce mois)
                                $manager    = new ManagerConge();
                                $dateObj    = DateTime::createFromFormat('!m', date('m'));
                                $currentMonth = $dateObj->format('F');
                                $tmpCongePris = $manager->lister([
                                    'idEmploye' => $employe->getIdEmploye(),
                                    'debut'     => 'BETWEEN "' . date("Y-m-d", strtotime("first day of ". $currentMonth ."")) . '" AND "' .
                                        date("Y-m-d", strtotime("last day of ". $currentMonth ."")) . '"',
                                    'statut'    => self::VALIDATED
                                ]);
                                if ($tmpCongePris) {
                                    foreach ($tmpCongePris as $list) {
                                        $tmpSold += $this->calculateCongeDuring($list);
                                    }
                                }
                                if ($tmpSold) {
                                    if ($parameters['annee'] < date('Y')) {
                                        $minus = 2.5 * (12 - $parameters['mois']);
                                    } elseif (intval(date("d")) <= 28 && $parameters['mois'] == date('m')) {
                                        $minus = 0;
                                    } else {
                                        $minus = 2.5 * (date('m') - $parameters['mois'] - 1);
                                        if ($parameters['congePris'] > 0) {
                                            $tmpSold -= $parameters['congePris'];
                                        }
                                    }
                                    if (count($nextConge) > 0) {
                                        foreach ($nextConge as $congeN) {
                                            $tmpSold += $this->calculateCongeDuring($congeN);
                                        }
                                    }

                               //      echo "<pre>";
                               //  var_dump($monthName); 
                               //  var_dump($tmpSold); //
                               // //  deb,  fin pris 
                               //  var_dump($minus); // 
                               //  var_dump($tmpSold - $minus); // 
                               //  exit();
                                    $parameters['soldeCongeFin']    = ($tmpSold - $minus) < 0 ? 0 : $tmpSold - $minus;
                                    $tmpSoldDebut                   = $parameters['soldeCongeFin'] - 2.5 + $parameters['congePris'];
                                    $parameters['soldeCongeDebut']  = $tmpSoldDebut > 0 ? $tmpSoldDebut : 0;
                                }    
                            }
                            if ($parameters['salaireDeBase']) {
                                if ($parametreHeure->getHeureNormale() > 0) {
                                    $salaireHoraire = $this->arrondir($parameters['salaireDeBase'] / $parametreHeure->getHeureNormale(), $this->typeArrondissement);
                                } else {
                                    $salaireHoraire = $this->arrondir($parameters['salaireDeBase'], $this->typeArrondissement);
                                }
                                
                                if (!empty($parameters['presence'])) {
                                    if ($maxPresence > 0) {
                                        $salaire = $this->arrondir(($parameters['presence'] * $parameters['salaireDeBase']) / $maxPresence, $this->typeArrondissement);
                                    } else {
                                        $salaire = $this->arrondir(($parameters['presence'] * $parameters['salaireDeBase']), $this->typeArrondissement);
                                    }
                                    
                                }
                                if (!empty($parameters['quantiteAllocation'])) {
                                    $allocationConge = $this->arrondir($smm * $parameters['quantiteAllocation'] / self::MOIS_SANS_WEEKEND, $this->typeArrondissement);
                                }
                                if (!empty($parameters['quantiteHeureSupplementaire'])) {
                                    $majorationSupplementaire = $this->getMajorationHeureSupplementaire($parameters['idEmploye'], $parameters['quantiteHeureSupplementaire'], $salaireHoraire);
                                }
                                if (!empty($parameters['quantiteHeureNuit'])) {
                                    $majorationNuit = $this->getMajorationHeureNuit($parameters['idEmploye'], $parameters['quantiteHeureNuit'], $salaireHoraire);
                                }
                                if (!empty($parameters['quantiteHeureDimanche'])) {
                                    $majorationDimanche = $this->getMajorationHeureDimanche($parameters['idEmploye'], $parameters['quantiteHeureDimanche'], $salaireHoraire);
                                }
                                if (!empty($parameters['quantiteHeureFerie'])) {
                                    $majorationFerie = $this->getMajorationHeureFerie($parameters['idEmploye'], $parameters['quantiteHeureFerie'], $salaireHoraire);
                                }
                                if (!isset($salaire)) {
                                    $salaire = 0;
                                }
                                if (!isset($allocationConge)) {
                                    $allocationConge = 0;
                                }
                                if (!isset($majorationSupplementaire)) {
                                    $majorationSupplementaire = 0;
                                }
                                if (!isset($majorationNuit)) {
                                    $majorationNuit = 0;
                                }
                                if (!isset($majorationDimanche)) {
                                    $majorationDimanche = 0;
                                }
                                if (!isset($majorationFerie)) {
                                    $majorationFerie = 0;
                                }
                                $salaireBrut  = $salaire;
                                $salaireBrut += $allocationConge;
                                $salaireBrut += $majorationSupplementaire;
                                $salaireBrut += $majorationNuit;
                                $salaireBrut += $majorationDimanche;
                                $salaireBrut += $majorationFerie;
                                foreach ($avantageImposables as $avantage) {
                                    $salaireBrut += ($avantage->getMontant() * $avantage->getRatioImposable()) / self::CENT_POUR_CENT;
                                }
                                $plafond = $this->getPlafondCnaps();
                                if (($salaireBrut / self::CENT_POUR_CENT) > $plafond) {
                                    $deductionCnaps = $this->arrondir($plafond, $this->typeArrondissement);
                                } else {
                                    $deductionCnaps = $this->arrondir($salaireBrut / self::CENT_POUR_CENT, $this->typeArrondissement);
                                }
                                $plafond = $this->getPlafondOstie();
                                if (($salaireBrut / self::CENT_POUR_CENT) > $plafond) {
                                    $deductionOstie = $this->arrondir($plafond, $this->typeArrondissement);
                                } else {
                                    $deductionOstie = $this->arrondir($salaireBrut / self::CENT_POUR_CENT, $this->typeArrondissement);
                                }
                                $revenuImposable = $this->arrondir($salaireBrut - ($deductionCnaps + $deductionOstie), $this->typeArrondissement);
                                $manager = new ManagerParametrePaie();
                                $parametrePaie = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                                if ($parametrePaie->getModeIrsa() == self::NEW_MODE_IRSA) {
                                    $irsa   = $this->calculerIrsaV2($revenuImposable);
                                } else {
                                    $irsa = $this->calculerIrsaV1($revenuImposable);
                                }
                                if ($irsa > $parametrePaie->getMinimumDePerception()) {
                                    $irsa = $this->arrondir($irsa, $this->typeArrondissement);
                                } else {
                                    $irsa = $parametrePaie->getMinimumDePerception();
                                }
                                $quantiteCharge = 0;
                                if (!empty($parameters['quantiteCharge'])) {
                                    $quantiteCharge = $parameters['quantiteCharge'];
                                }
                                $deductionCharge = $quantiteCharge * $parametrePaie->getChargeFamiliale();
                                $irsaNet         = $irsa - $deductionCharge;
                                $salaireNet      = $revenuImposable;
                                $salaireNet     -= $irsaNet;
                                foreach ($avantageNonImposables as $avantage) {
                                    $salaireNet += $avantage->getMontant();
                                }
                                foreach ($deductions as $deduction) {
                                    $salaireNet -= $deduction->getMontant();
                                }
                                $salaireNet = $this->arrondir($salaireNet, $this->typeArrondissement);
                            }
                            if (!isset($parameters['quantiteAllocation'])) {
                                $parameters['quantiteAllocation'] = 0;
                            }
                            if (!isset($parameters['presence'])) {
                                $parameters['presence'] = 0;
                            }
                            if (!isset($parameters['quantiteHeureSupplementaire'])) {
                                $parameters['quantiteHeureSupplementaire'] = 0;
                            }
                            if (!isset($parameters['quantiteHeureNuit'])) {
                                $parameters['quantiteHeureNuit'] = 0;
                            }
                            if (!isset($parameters['quantiteHeureDimanche'])) {
                                $parameters['quantiteHeureDimanche'] = 0;
                            }
                            if (!isset($parameters['quantiteHeureFerie'])) {
                                $parameters['quantiteHeureFerie'] = 0;
                            }
                            if (!isset($parameters['quantiteCharge'])) {
                                $parameters['quantiteCharge'] = 0;
                            }
                            return [
                                'employe'                       => $employe,
                                'entreprise'                    => $entreprise,
                                'mois'                          => $parameters['mois'],
                                'annee'                         => $parameters['annee'],
                                'debut'                         => $debut,
                                'fin'                           => $fin,
                                'parametreHeure'                => $parametreHeure,
                                'avantageImposables'            => $avantageImposables,
                                'avantageNonImposables'         => $avantageNonImposables,
                                'avantages'                     => $avantages,
                                'deductions'                    => $deductions,
                                'poste'                         => $this->getPosteEmploye($employe)->getPoste(),
                                'salaireDeBase'                 => $parameters['salaireDeBase'],
                                'soldeCongeDebut'               => $parameters['soldeCongeDebut'],
                                'soldeCongeFin'                 => $parameters['soldeCongeFin'],
                                'congePris'                     => $parameters['congePris'],
                                'quantiteAllocation'            => $parameters['quantiteAllocation'],
                                'presence'                      => $parameters['presence'],
                                'quantiteHeureSupplementaire'   => $parameters['quantiteHeureSupplementaire'],
                                'quantiteHeureNuit'             => $parameters['quantiteHeureNuit'],
                                'quantiteHeureDimanche'         => $parameters['quantiteHeureDimanche'],
                                'quantiteHeureFerie'            => $parameters['quantiteHeureFerie'],
                                'quantiteCharge'                => $parameters['quantiteCharge'],
                                'deductionCharge'               => $deductionCharge,
                                'salaire'                       => $salaire,
                                'allocationConge'               => $allocationConge,
                                'majorationSupplementaire'      => $majorationSupplementaire,
                                'majorationDimanche'            => $majorationDimanche,
                                'majorationNuit'                => $majorationNuit,
                                'majorationFerie'               => $majorationFerie,
                                'salaireBrut'                   => $salaireBrut,
                                'deductionCnaps'                => $deductionCnaps,
                                'deductionOstie'                => $deductionOstie,
                                'revenuImposable'               => $revenuImposable,
                                'irsa'                          => $irsa,
                                'irsaNet'                       => $irsaNet,
                                'salaireNet'                    => $salaireNet,
                                'salaireNetEnLettre'            => strtoupper($this->ecrireEnLettre($salaireNet)),
                                'listeAvantages'                => $listeAvantages,
                                'listeAvantageEmployes'         => $listeAvantageEmployes,
                                'tableauAvantages'              => $tableauAvantages,
                            ];
                        } else {
                            $_SESSION['info']['warning'] = "La fiche de paie de cet employe pour ce mois est déjà figée";
                            header("Location:" . HOST . "manage/entreprise/detailFichePaie?idEmploye=" . $employe->getIdEmploye() . "&mois=" . $parameters['mois'] . "&annee=" . $parameters['annee']);
                        }
                    } else {
                        $_SESSION['info']['warning'] = "Vous n'avez pas le droit de voir cette page";
                        header("Location:" . HOST . "manage/entreprise/paie");
                    }
                } else {
                    unset($parameters['sauvegarder']);
                    $parameters['statut']          = 1;
                    $parameters['heureEffective']  = $this->explodeHeure($parameters['idEmploye'], $parameters['mois'], $parameters['annee'])['normal'];
                    $this->mettreAJourFichePaie($parameters);
                    header("Location:" . HOST . "manage/entreprise/detailFichePaie?idEmploye=" . $parameters['idEmploye'] . "&mois=" . $parameters['mois'] . "&annee=" . $parameters['annee']);
                    exit();
                }
            }
        }

        /**
         * Afficher l'interface d'avance
         *
         * @param array $parameters les critères des données à afficher
         *
         * @return array
         */
        public function afficherAvance($parameters)
        {
            if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                $manager          = new ManagerEntreprise();
                $entreprise       = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager          = new ManagerParametreAvance();
                $parametreAvance  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                if ($parametreAvance == null) {
                    $parametreAvance = $manager->ajouter([
                        'idEntreprise' => $_SESSION['user']['idEntreprise'],
                        'dureeMax'     => self::UNE_ANNEE,
                        'tauxMax'      => self::TAUX_REMBOURSEMENT
                    ]);
                }
                return [
                    'entreprise'      => $entreprise,
                    'parametreAvance' => $parametreAvance,
                    'filtres'         => $this->getFiltre($entreprise->getIdEntreprise())
                ];
            }
        }

        /**
         * Afficher l'interface de paramètrage des avances
         *
         * @param array $parameters les critères des données à afficher
         *
         * @return array
         */
        public function afficherParametreAvance($parameters)
        {
            if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                $manager          = new ManagerEntreprise();
                $entreprise       = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager          = new ManagerParametreAvance();
                $parametreAvance  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                if ($parametreAvance == null) {
                    $parametreAvance = $manager->ajouter([
                        'idEntreprise' => $_SESSION['user']['idEntreprise'],
                        'dureeMax'     => self::UNE_ANNEE,
                        'tauxMax'      => self::TAUX_REMBOURSEMENT
                    ]);
                }
                return [
                    'parametreAvance' => $parametreAvance
                ];
            }
        }

        /**
         * Afficher l'interface de demande d'avance spéciale
         *
         * @param array $parameters les critères des données à afficher
         *
         * @return array
         */
        public function afficherDemandeAvance($parameters)
        {
            if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                $manager          = new ManagerEntreprise();
                $entreprise       = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager          = new ManagerParametreAvance();
                $parametreAvance  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                if ($parametreAvance == null) {
                    $parametreAvance = $manager->ajouter([
                        'idEntreprise' => $_SESSION['user']['idEntreprise'],
                        'dureeMax'     => self::UNE_ANNEE,
                        'tauxMax'      => self::TAUX_REMBOURSEMENT
                    ]);
                }
                return [
                    'entreprise'      => $entreprise,
                    'parametreAvance' => $parametreAvance,
                    'filtres'         => $this->getFiltre($entreprise->getIdEntreprise())
                ];
            } elseif ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                $manager      = new ManagerEmploye();
                $employe      = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
                $isEligible   = true;
                if ($employe->getAvanceSpeciale() == self::NO || $this->getAvanceEmploye($employe->getIdEmploye()) != false) {
                    $isEligible = false;
                }
                return [
                    'isEligible' => $isEligible
                ];
            }
        }

        /**
         * Afficher l'interface de demande d'avance quinzaine
         *
         * @param array $parameters les critères des données à afficher
         *
         * @return array
         */
        public function afficherDemandeAvanceQuinzaine($parameters)
        {
            if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                $manager          = new ManagerEntreprise();
                $entreprise       = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                return [
                    'entreprise'      => $entreprise,
                    'filtres'         => $this->getFiltre($entreprise->getIdEntreprise())
                ];
            } elseif ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                $manager      = new ManagerEmploye();
                $employe      = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
                $isEligible   = true;
                if ($employe->getAvanceSalaire() == self::NO) {
                    $isEligible = false;
                }
                return [
                    'isEligible' => $isEligible
                ];
            }
        }

        /**
         * Valider une demande d'avance spéciale
         *
         * @param array $parameters les critères des données à valider
         *
         * @return empty
         */
        public function validerDemandeAvance($parameters)
        {
            if (!empty($parameters['idAvance'])) {
                $manager   = new ManagerAvance();
                $avance    = $manager->chercher([
                    'idAvance' => $parameters['idAvance']
                ]);
                if ($avance != null) {
                    $retour = $manager->modifier([
                        'idAvance' => $avance->getIdAvance(),
                        'statut'   => self::AVANCE_VALIDATED
                    ]);
                    if ($retour->getStatut() == self::AVANCE_VALIDATED) {
                        $manager   = new ManagerEmploye();
                        $employe   = $manager->chercher(['idEmploye' => $avance->getIdEmploye()]);
                        $content   = $this->generateMessageContent(self::TYPE_VALIDATED, $avance);
                        $this->sendMessageNotification($employe->getIdCompte(), "Demande d'avance spéciale acceptée", $content);
                        $manager   = new ManagerMessage();
                        $message   = $manager->modifier([
                            'idMessage'  => $avance->getIdMessage(),
                            'statut'     => self::SEEN
                        ]);
                        $this->setPlanningRemboursement($avance->getIdAvance(), $avance->getMoisRemboursement(), $avance->getAnneeRemboursement());
                        $_SESSION['info']['success'] = "La demande d'avance a été validée avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } else {
                    $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                }
            }
        }

        /**
         * Valider une demande d'avance quinzaine
         *
         * @param array $parameters les critères des données à valider
         *
         * @return empty
         */
        public function validerDemandeAvanceQuinzaine($parameters)
        {
            if (!empty($parameters['idAvanceQuinzaine'])) {
                $manager   = new ManagerAvanceQuinzaine();
                $avance    = $manager->chercher([
                    'idAvanceQuinzaine' => $parameters['idAvanceQuinzaine']
                ]);
                if ($avance != null) {
                    $retour = $manager->modifier([
                        'idAvanceQuinzaine' => $avance->getIdAvanceQuinzaine(),
                        'statut'            => self::AVANCE_VALIDATED
                    ]);
                    if ($retour->getStatut() == self::AVANCE_VALIDATED) {
                        $manager   = new ManagerEmploye();
                        $employe   = $manager->chercher(['idEmploye' => $avance->getIdEmploye()]);
                        $content   = $this->generateMessageContent(self::TYPE_VALIDATED, $avance);
                        $this->sendMessageNotification($employe->getIdCompte(), "Demande d'avance quinzaine acceptée", $content);
                        $manager   = new ManagerMessage();
                        $message   = $manager->modifier([
                            'idMessage'  => $avance->getIdMessage(),
                            'statut'     => self::SEEN
                        ]);
                        $manager   = new ManagerDeduction();
                        $deduction = $manager->ajouter([
                            'idEmploye'  => $avance->getIdEmploye(),
                            'mois'       => date('m', strtotime($avance->getDate())),
                            'annee'      => date('Y', strtotime($avance->getDate())),
                            'libelle'    => "Avance quinzaine",
                            'montant'    => $avance->getMontant()
                        ]);
                        $manager = new ManagerAvanceQuinzaine();
                        $manager->modifier([
                            'idAvanceQuinzaine' => $avance->getIdAvanceQuinzaine(),
                            'idDeduction'       => $deduction->getIdDeduction()
                        ]);
                        $_SESSION['info']['success'] = "La demande d'avance quinzaine a été validée avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } else {
                    $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                }
            }
        }

        /**
         * Rejeter une demande d'avance spéciale
         *
         * @param array $parameters les critères des données à valider
         *
         * @return empty
         */
        public function rejeterDemandeAvance($parameters)
        {
            if (!empty($parameters['idAvance'])) {
                $manager   = new ManagerAvance();
                $avance    = $manager->chercher([
                    'idAvance' => $parameters['idAvance']
                ]);
                if ($avance != null) {
                    $retour = $manager->modifier([
                        'idAvance'   => $avance->getIdAvance(),
                        'statut'     => self::AVANCE_REJECTED,
                        'motifRefus' => $parameters['motifRefus']
                    ]);
                    if ($retour->getStatut() == self::AVANCE_REJECTED) {
                        $avance    = $manager->chercher(['idAvance' => $retour->getIdAvance()]);
                        $manager   = new ManagerEmploye();
                        $employe   = $manager->chercher(['idEmploye' => $avance->getIdEmploye()]);
                        $content   = $this->generateMessageContent(self::TYPE_REJECTED, $avance);
                        $this->sendMessageNotification($employe->getIdCompte(), "Demande d'avance spéciale rejetée", $content);
                        $manager   = new ManagerMessage();
                        $message   = $manager->modifier([
                            'idMessage'  => $avance->getIdMessage(),
                            'statut'     => self::SEEN
                        ]);
                        $_SESSION['info']['success'] = "La demande d'avance a été rejetée";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } else {
                    $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                }
            }
        }

        /**
         * Rejeter une demande d'avance quinzaine
         *
         * @param array $parameters les critères des données à valider
         *
         * @return empty
         */
        public function rejeterDemandeAvanceQuinzaine($parameters)
        {
            if (!empty($parameters['idAvanceQuinzaine'])) {
                $manager   = new ManagerAvanceQuinzaine();
                $avance    = $manager->chercher([
                    'idAvanceQuinzaine' => $parameters['idAvanceQuinzaine']
                ]);
                if ($avance != null) {
                    $retour = $manager->modifier([
                        'idAvanceQuinzaine' => $avance->getIdAvance(),
                        'statut'            => self::AVANCE_REJECTED
                    ]);
                    if ($retour->getStatut() == self::AVANCE_REJECTED) {
                        $avance    = $manager->chercher(['idAvanceQuinzaine' => $retour->getIdAvanceQuinzaine()]);
                        $manager   = new ManagerEmploye();
                        $employe   = $manager->chercher(['idEmploye' => $avance->getIdEmploye()]);
                        $content   = $this->generateMessageContent(self::TYPE_REJECTED, $avance);
                        $this->sendMessageNotification($employe->getIdCompte(), "Demande d'avance quinzaine rejetée", $content);
                        $manager   = new ManagerMessage();
                        $message   = $manager->modifier([
                            'idMessage'  => $avance->getIdMessage(),
                            'statut'     => self::SEEN
                        ]);
                        $_SESSION['info']['success'] = "La demande d'avance a été rejetée";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } else {
                    $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                }
            }
        }

        /**
         * Archiver une demande d'avance
         *
         * @param array $$parameters les critères des données à archiver
         *
         * @return empty
         */
        public function archiverDemandeAvance($parameters)
        {
            if (!empty($parameters['idAvance'])) {
                if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                    $manager   = new ManagerAvance();
                    $avance    = $manager->chercher([
                        'idAvance' => $parameters['idAvance']
                    ]);
                    if ($avance != null) {
                        $retour = $manager->modifier([
                            'idAvance'       => $avance->getIdAvance(),
                            'typeDemande'    => self::AVANCE_ARCHIVED,
                        ]);
                        if ($retour->getTypeDemande() == self::AVANCE_ARCHIVED) {
                            $_SESSION['info']['success'] = "La demande d'avance a été archivée";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                    }
                } elseif ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                    $manager   = new ManagerAvance();
                    $avance    = $manager->chercher([
                        'idAvance' => $parameters['idAvance']
                    ]);
                    if ($avance != null) {
                        $retour = $manager->modifier([
                            'idAvance'     => $avance->getIdAvance(),
                            'typeAvance'   => self::AVANCE_ARCHIVED,
                        ]);
                        if ($retour->getTypeAvance() == self::AVANCE_ARCHIVED) {
                            $_SESSION['info']['success'] = "La demande d'avance a été archivée";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                    }
                }
            }
        }

        /**
         * Archiver une demande d'avance quinzaine
         *
         * @param array $$parameters les critères des données à archiver
         *
         * @return empty
         */
        public function archiverDemandeAvanceQuinzaine($parameters)
        {
            if (!empty($parameters['idAvanceQuinzaine'])) {
                if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                    $manager   = new ManagerAvanceQuinzaine();
                    $avance    = $manager->chercher([
                        'idAvanceQuinzaine' => $parameters['idAvanceQuinzaine']
                    ]);
                    if ($avance != null) {
                        $retour = $manager->modifier([
                            'idAvanceQuinzaine' => $avance->getIdAvanceQuinzaine(),
                            'typeDemande'    => self::AVANCE_ARCHIVED,
                        ]);
                        if ($retour->getTypeDemande() == self::AVANCE_ARCHIVED) {
                            $_SESSION['info']['success'] = "La demande d'avance a été archivée";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                    }
                } elseif ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                    $manager   = new ManagerAvanceQuinzaine();
                    $avance    = $manager->chercher([
                        'idAvanceQuinzaine' => $parameters['idAvanceQuinzaine']
                    ]);
                    if ($avance != null) {
                        $retour = $manager->modifier([
                            'idAvanceQuinzaine' => $avance->getIdAvanceQuinzaine(),
                            'typeAvance'        => self::AVANCE_ARCHIVED,
                        ]);
                        if ($retour->getTypeAvance() == self::AVANCE_ARCHIVED) {
                            $_SESSION['info']['success'] = "La demande d'avance a été archivée";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                    }
                }
            }
        }

        /**
         * Restaurer une demande d'avance
         *
         * @param array $$parameters les critères des données à archiver
         *
         * @return empty
         */
        public function restaurerDemandeAvance($parameters)
        {
            if (!empty($parameters['idAvance'])) {
                if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                    $manager   = new ManagerAvance();
                    $avance    = $manager->chercher([
                        'idAvance' => $parameters['idAvance']
                    ]);
                    if ($avance != null) {
                        $retour = $manager->modifier([
                            'idAvance'       => $avance->getIdAvance(),
                            'typeDemande'    => self::AVANCE_NOT_ARCHIVED,
                        ]);
                        if ($retour->getTypeDemande() == self::AVANCE_NOT_ARCHIVED) {
                            $_SESSION['info']['success'] = "La demande d'avance a été restaurée";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                    }
                } elseif ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                    $manager   = new ManagerAvance();
                    $avance    = $manager->chercher([
                        'idAvance' => $parameters['idAvance']
                    ]);
                    if ($avance != null) {
                        $retour = $manager->modifier([
                            'idAvance'     => $avance->getIdAvance(),
                            'typeAvance'   => self::AVANCE_NOT_ARCHIVED,
                        ]);
                        if ($retour->getTypeAvance() == self::AVANCE_NOT_ARCHIVED) {
                            $_SESSION['info']['success'] = "La demande d'avance a été restaurée";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                    }
                }
            }
        }

        /**
         * Restaurer une demande d'avance quinzaine
         *
         * @param array $$parameters les critères des données à archiver
         *
         * @return empty
         */
        public function restaurerDemandeAvanceQuinzaine($parameters)
        {
            if (!empty($parameters['idAvanceQuinzaine'])) {
                if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                    $manager   = new ManagerAvanceQuinzaine();
                    $avance    = $manager->chercher([
                        'idAvanceQuinzaine' => $parameters['idAvanceQuinzaine']
                    ]);
                    if ($avance != null) {
                        $retour = $manager->modifier([
                            'idAvanceQuinzaine' => $avance->getIdAvanceQuinzaine(),
                            'typeDemande'    => self::AVANCE_NOT_ARCHIVED,
                        ]);
                        if ($retour->getTypeDemande() == self::AVANCE_NOT_ARCHIVED) {
                            $_SESSION['info']['success'] = "La demande d'avance a été restaurée";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                    }
                } elseif ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                    $manager   = new ManagerAvanceQuinzaine();
                    $avance    = $manager->chercher([
                        'idAvanceQuinzaine' => $parameters['idAvanceQuinzaine']
                    ]);
                    if ($avance != null) {
                        $retour = $manager->modifier([
                            'idAvanceQuinzaine' => $avance->getIdAvanceQuinzaine(),
                            'typeAvance'       => self::AVANCE_NOT_ARCHIVED,
                        ]);
                        if ($retour->getTypeAvance() == self::AVANCE_NOT_ARCHIVED) {
                            $_SESSION['info']['success'] = "La demande d'avance a été restaurée";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "Demande d'avance introuvable";
                    }
                }
            }
        }

        /**
         * Mettre à jour une demande d'avance spéciale
         *
         * @param array $parameters les critères des données à afficher
         *
         * @return empty
         */
        public function mettreAJourDemandeAvance($parameters)
        {
            if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE || $this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                    $parameters['idEmploye'] = $_SESSION['user']['idEmploye'];
                }
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                if ($this->decrypter($employe->getSalaire()) > self::NO) {
                    if ($employe->getAvanceSpeciale() != self::NO) {
                        if ($this->getAvanceEmploye($employe->getIdEmploye()) == false) {
                            $manager    = new ManagerAvance();
                            $retour     = $manager->ajouter([
                                'idEmploye'          => $employe->getIdEmploye(),
                                'montant'            => $parameters['montant'],
                                'motif'              => $parameters['motif'],
                                'date'               => date('Y-m-d'),
                                'moisRemboursement'  => $parameters['moisRemboursement'],
                                'anneeRemboursement' => $parameters['anneeRemboursement'],
                                'statut'             => self::AVANCE_PROPOSED,
                                'typeAvance'         => self::AVANCE_NOT_ARCHIVED,
                                'typeDemande'        => self::AVANCE_NOT_ARCHIVED
                            ]);
                            if ($retour->getIdAvance() != self::NO) {
                                $content   = $this->generateMessageContent(self::TYPE_REQUEST, $retour);
                                $idMessage = $this->sendMessageNotification($entreprise->getIdCompte(), "Demande d'avance spéciale de la part de " . $employe->getNom() . " " . $employe->getPrenom(), $content);
                                $manager   = new ManagerMessage();
                                $message   = $manager->modifier([
                                    'idMessage' => $idMessage,
                                    'aFaire'    => self::YES
                                ]);
                                $manager   = new ManagerAvance();
                                $manager->modifier([
                                    'idAvance'  => $retour->getIdAvance(),
                                    'idMessage' => $idMessage
                                ]);
                                $_SESSION['info']['success'] = "La demande a été enregistrée avec succès";
                            } else {
                                $_SESSION['info']['danger'] = "Echec lors de l'opération";
                            }
                        } else {
                            if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                                $_SESSION['info']['danger'] = "Vous avez encore une avance en cours de remboursement en ce moment";
                            } elseif ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                                $_SESSION['info']['danger'] = "Cet employé a encore une avance en cours de remboursement en ce moment";
                            }
                        }
                    } else {
                        if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                            $_SESSION['info']['danger'] = "Vous n'avez pas encore le droit d'effectuer cette demande";
                        } elseif ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                            $_SESSION['info']['danger'] = "Cet employé n'a pas encore le droit d'effectuer cette demande";
                        }
                    }
                } else {
                    if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                        $_SESSION['info']['warning'] = "Votre salaire de base n'est pas encore défini";
                    } elseif ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                        $_SESSION['info']['danger'] = "Le salaire de base de cet employé n'est pas encore défini";
                    }
                }
            }
        }

        /**
         * Mettre à jour une demande d'avance quinzaine
         *
         * @param array $parameters les critères des données à afficher
         *
         * @return empty
         */
        public function mettreAJourDemandeAvanceQuinzaine($parameters)
        {
            if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE || $this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                    $parameters['idEmploye'] = $_SESSION['user']['idEmploye'];
                }
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                $manager    = new ManagerAvanceQuinzaine();
                $avances    = $manager->selectionner(
                    ['idEmploye' => $employe->getIdEmploye(),
                    'statut'     => self::VALIDATED],
                    ['date'      => date(date('Y') . '-' . date('m') . '-01')],
                    ['date'      => date("Y-m-d", mktime(0, 0, 0, (date('m') + 1), 0, date('Y')))]
                );
                $manager       = new ManagerParametreAvance();
                $parametre     = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                $employe->setSalaire($this->decrypter($employe->getSalaire()));
                $tauxMensuel   = ($employe->getSalaire() * $parametre->getTauxMax()) / self::CENT_POUR_CENT;
                $maxPossible   = $tauxMensuel;
                $avanceEnCours = $this->getAvanceEmploye($employe->getIdEmploye());
                if ($avanceEnCours != false) {
                    $manager       = new ManagerRemboursement();
                    $remboursement = $manager->chercher([
                        'idAvance' => $avanceEnCours->getIdAvance(),
                        'mois'     => date('m'),
                        'annee'    => date('Y')
                    ]);
                    if ($remboursement != null) {
                        $maxPossible -= $remboursement->getMontant();
                    }
                }
                if ($employe->getSalaire() > self::NO) {
                    if ($employe->getAvanceSalaire() != self::NO) {
                        if (count($avances) == self::NO) {
                            if ($parameters['montant'] <= $maxPossible) {
                                $manager    = new ManagerAvanceQuinzaine();
                                $retour     = $manager->ajouter([
                                    'idEmploye'          => $employe->getIdEmploye(),
                                    'montant'            => $parameters['montant'],
                                    'date'               => date('Y-m-d'),
                                    'statut'             => self::AVANCE_PROPOSED,
                                    'typeAvance'         => self::AVANCE_NOT_ARCHIVED,
                                    'typeDemande'        => self::AVANCE_NOT_ARCHIVED
                                ]);
                                if ($retour->getIdAvanceQuinzaine() != self::NO) {
                                    $content   = $this->generateMessageContent(self::TYPE_REQUEST, $retour);
                                    $idMessage = $this->sendMessageNotification($entreprise->getIdCompte(), "Demande d'avance quinzaine de la part de " . $employe->getNom() . " " . $employe->getPrenom(), $content);
                                    $manager   = new ManagerMessage();
                                    $message   = $manager->modifier([
                                        'idMessage' => $idMessage,
                                        'aFaire'    => self::YES
                                    ]);
                                    $manager   = new ManagerAvanceQuinzaine();
                                    $manager->modifier([
                                        'idAvanceQuinzaine' => $retour->getIdAvanceQuinzaine(),
                                        'idMessage'         => $idMessage
                                    ]);
                                    $_SESSION['info']['success'] = "La demande a été enregistrée avec succès";
                                } else {
                                    $_SESSION['info']['danger'] = "Echec lors de l'opération";
                                }
                            } else {
                                $_SESSION['info']['danger'] = "Le montant saisi dépasse le maximum autorisé <b>[" . number_format($maxPossible, 2) . "]</b> pour ce mois";
                            }
                        } else {
                            if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                                $_SESSION['info']['danger'] = "Une avance quinzaine vous a déjà été accordée pour ce mois";
                            } elseif ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                                $_SESSION['info']['danger'] = "Une avance quinzaine a déjà été accordée à cet employé pour ce mois";
                            }
                        }
                    } else {
                        if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                            $_SESSION['info']['danger'] = "Vous n'avez pas encore le droit d'effectuer cette demande";
                        } elseif ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                            $_SESSION['info']['danger'] = "Cet employé n'a pas encore le droit d'effectuer cette demande";
                        }
                    }
                } else {
                    if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                        $_SESSION['info']['warning'] = "Votre salaire de base n'est pas encore défini";
                    } elseif ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                        $_SESSION['info']['danger'] = "Le salaire de base de cet employé n'est pas encore défini";
                    }
                }
            }
        }

        /**
         * Retirer une demande d'avance spéciale
         *
         * @param array $parameters les critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerDemandeAvance($parameters)
        {
            if (!empty($parameters['idAvance'])) {
                $manager = new ManagerAvance();
                $avance  = $manager->chercher([
                    'idAvance' => $parameters['idAvance']
                ]);
                if ($avance != null) {
                    $retour = $manager->supprimer([
                        'idAvance' => $avance->getIdAvance()
                    ]);
                    if ($retour != false) {
                        $manager    = new ManagerEmploye();
                        $employe    = $manager->chercher(['idEmploye' => $avance->getIdEmploye()]);
                        $manager    = new ManagerEntreprise();
                        $entreprise = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                        $content    = $this->generateMessageContent(self::TYPE_CANCELED, $avance);
                        if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                            $this->sendMessageNotification($entreprise->getIdCompte(), "Demande d'avance spéciale retirée", $content);
                        } elseif ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                            $this->sendMessageNotification($employe->getIdCompte(), "Demande d'avance spéciale retirée", $content);
                        }
                        $manager  = new ManagerMessage();
                        $message  = $manager->chercher(['idMessage' => $avance->getIdMessage()]);
                        if ($message != null) {
                            $manager->modifier([
                                'idMessage' => $message->getIdMessage(),
                                'statut'    => self::SEEN
                            ]);
                        }
                        $_SESSION['info']['success'] = "La demande a été retirée";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } else {
                    $_SESSION['info']['danger'] = "Demande introuvable";
                }
            }
        }

        /**
         * Retirer une demande d'avance quinzaine
         *
         * @param array $parameters les critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerDemandeAvanceQuinzaine($parameters)
        {
            if (!empty($parameters['idAvanceQuinzaine'])) {
                $manager = new ManagerAvanceQuinzaine();
                $avance  = $manager->chercher([
                    'idAvanceQuinzaine' => $parameters['idAvanceQuinzaine']
                ]);
                if ($avance != null) {
                    $retour = $manager->supprimer([
                        'idAvanceQuinzaine' => $avance->getIdAvanceQuinzaine()
                    ]);
                    if ($retour != false) {
                        $manager    = new ManagerEmploye();
                        $employe    = $manager->chercher(['idEmploye' => $avance->getIdEmploye()]);
                        $manager    = new ManagerEntreprise();
                        $entreprise = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                        $content    = $this->generateMessageContent(self::TYPE_CANCELED, $avance);
                        if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                            $this->sendMessageNotification($entreprise->getIdCompte(), "Demande d'avance quinzaine retirée", $content);
                        } elseif ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                            $this->sendMessageNotification($employe->getIdCompte(), "Demande d'avance quinzaine retirée", $content);
                        }
                        $manager  = new ManagerMessage();
                        $message  = $manager->chercher(['idMessage' => $avance->getIdMessage()]);
                        if ($message != null) {
                            $manager->modifier([
                                'idMessage' => $message->getIdMessage(),
                                'statut'    => self::SEEN
                            ]);
                        }
                        $_SESSION['info']['success'] = "La demande a été retirée";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } else {
                    $_SESSION['info']['danger'] = "Demande introuvable";
                }
            }
        }

        /**
         * lister les demandes d'avance spéciale
         *
         * @param array $parameters les critères des données à lister
         *
         * @return empty
         */
        public function listerDemandeAvances($parameters)
        {
            $manager = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $donnees = array();
            if (!empty($parameters['groupe'])) {
                if ($parameters['groupe'] == self::FILTER_GROUP_ALL) {
                    $manager  = new ManagerEmploye();
                    $employes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $donnees  = $this->getDemandeAvances($employes, $parameters['type']);
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_SERVICE) {
                    $manager     = new ManagerEmploye();
                    $allEmployes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $employes    = array();
                    foreach ($allEmployes as $employe) {
                        if ($this->getServiceEmploye($employe) != null && $this->getServiceEmploye($employe)->getIdEntrepriseService() == $parameters['id']) {
                            $employes[] = $employe;
                        }
                    }
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_POSTE) {
                    $manager     = new ManagerEmploye();
                    $allEmployes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $employes    = array();
                    foreach ($allEmployes as $employe) {
                        if ($this->getPosteEmploye($employe) != null && $this->getPosteEmploye($employe)->getIdEntreprisePoste() == $parameters['id']) {
                            $employes[] = $employe;
                        }
                    }
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_EMPLOYE) {
                    $employes   = array();
                    $manager    = new ManagerEmploye();
                    $employes[] = $manager->chercher(['idEmploye' => $parameters['id']]);
                }
            }
            $donnees = $this->getDemandeAvances($employes, $parameters['type']);
            $view = new View("listerDemandeAvances");
            $view->sendWithoutTemplate("Backend", "Paie", $donnees, "");
            exit();
        }

        /**
         * lister les demandes d'avance quinzaine
         *
         * @param array $parameters les critères des données à lister
         *
         * @return empty
         */
        public function listerDemandeAvanceQuinzaines($parameters)
        {
            $manager = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $donnees = array();
            if (!empty($parameters['groupe'])) {
                if ($parameters['groupe'] == self::FILTER_GROUP_ALL) {
                    $manager  = new ManagerEmploye();
                    $employes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $donnees  = $this->getDemandeAvanceQuinzaines($employes, $parameters['type']);
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_SERVICE) {
                    $manager     = new ManagerEmploye();
                    $allEmployes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $employes    = array();
                    foreach ($allEmployes as $employe) {
                        if ($this->getServiceEmploye($employe) != null && $this->getServiceEmploye($employe)->getIdEntrepriseService() == $parameters['id']) {
                            $employes[] = $employe;
                        }
                    }
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_POSTE) {
                    $manager     = new ManagerEmploye();
                    $allEmployes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $employes    = array();
                    foreach ($allEmployes as $employe) {
                        if ($this->getPosteEmploye($employe) != null && $this->getPosteEmploye($employe)->getIdEntreprisePoste() == $parameters['id']) {
                            $employes[] = $employe;
                        }
                    }
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_EMPLOYE) {
                    $employes   = array();
                    $manager    = new ManagerEmploye();
                    $employes[] = $manager->chercher(['idEmploye' => $parameters['id']]);
                }
            }
            $donnees = $this->getDemandeAvanceQuinzaines($employes, $parameters['type']);
            $view = new View("listerDemandeAvanceQuinzaines");
            $view->sendWithoutTemplate("Backend", "Paie", $donnees, "");
            exit();
        }

        /**
         * Récupérer les demandes d'avance d'une liste d'employés
         *
         * @param array $employes une liste d'employé
         * @param int   $type     la statut de la demande
         *
         * @return array
         */
        public function getDemandeAvances($employes, $type)
        {
            $manager     = new ManagerParametreAvance();
            $parametre   = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $resultats   = array();
            $allDemandes = array();
            foreach ($employes as $employe) {
                $manager     = new ManagerAvance();
                $allDemandes = array_merge($allDemandes, $manager->lister(['idEmploye' => $employe->getIdEmploye()]));
            }
            foreach ($allDemandes as $demande) {
                $tmp['remboursements'] = array();
                if ($type == self::ARCHIVED_DEMANDE && $_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE && $demande->getTypeDemande() == self::AVANCE_ARCHIVED) {
                    $tmp['demande']        = $demande;
                    $manager               = new ManagerEmploye();
                    $tmp['employe']        = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                    $manager               = new ManagerRemboursement();
                    $tmp['remboursements'] = $manager->lister(['idAvance' => $demande->getIdAvance()]);
                    $tmp['warning']        = false;
                    $tmp['dureeMois']      = $this->predireMoisRemboursement($demande->getIdAvance());
                    if ($tmp['dureeMois'] > $parametre->getDureeMax()) {
                        $tmp['warning'] = true;
                    }
                    $tmp['parametre']      = $parametre;
                    $resultats[]           = $tmp;
                } elseif ($type == self::ARCHIVED_DEMANDE && $_SESSION['compte']['identifiant'] == self::USER_EMPLOYE && $demande->getTypeAvance() == self::AVANCE_ARCHIVED) {
                    $tmp['demande']        = $demande;
                    $manager               = new ManagerEmploye();
                    $tmp['employe']        = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                    $manager               = new ManagerRemboursement();
                    $tmp['remboursements'] = $manager->lister(['idAvance' => $demande->getIdAvance()]);
                    $tmp['warning']        = false;
                    $tmp['dureeMois']      = $this->predireMoisRemboursement($demande->getIdAvance());
                    if ($tmp['dureeMois'] > $parametre->getDureeMax()) {
                        $tmp['warning']    = true;
                    }
                    $tmp['parametre']      = $parametre;
                    $resultats[]           = $tmp;
                } elseif ($type == self::ALL_DEMANDE || $demande->getStatut() == $type) {
                    if (($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE && $demande->getTypeAvance() == self::AVANCE_NOT_ARCHIVED) || ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE && $demande->getTypeDemande() == self::AVANCE_NOT_ARCHIVED)) {
                        $tmp['demande']        = $demande;
                        $manager               = new ManagerEmploye();
                        $tmp['employe']        = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                        $manager               = new ManagerRemboursement();
                        $tmp['remboursements'] = $manager->lister(['idAvance' => $demande->getIdAvance()]);
                        $tmp['warning']        = false;
                        $tmp['dureeMois']      = $this->predireMoisRemboursement($demande->getIdAvance());
                        if ($tmp['dureeMois'] > $parametre->getDureeMax()) {
                            $tmp['warning']    = true;
                        }
                        $tmp['parametre']      = $parametre;
                        $resultats[]           = $tmp;
                    }
                }
            }
            return $resultats;
        }

        /**
         * Récupérer les demandes d'avance quinzaine d'une liste d'employés
         *
         * @param array $employes une liste d'employé
         * @param int   $type     la statut de la demande
         *
         * @return array
         */
        public function getDemandeAvanceQuinzaines($employes, $type)
        {
            $manager     = new ManagerParametreAvance();
            $parametre   = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $resultats   = array();
            $allDemandes = array();
            foreach ($employes as $employe) {
                $manager     = new ManagerAvanceQuinzaine();
                $allDemandes = array_merge($allDemandes, $manager->lister(['idEmploye' => $employe->getIdEmploye()]));
            }
            foreach ($allDemandes as $demande) {
                $tmp['remboursements'] = array();
                if ($type == self::ARCHIVED_DEMANDE && $_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE && $demande->getTypeDemande() == self::AVANCE_ARCHIVED) {
                    $tmp['demande']        = $demande;
                    $manager               = new ManagerEmploye();
                    $tmp['employe']        = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                    $tmp['warning']        = false;
                    $tmp['parametre']      = $parametre;
                    $resultats[]           = $tmp;
                } elseif ($type == self::ARCHIVED_DEMANDE && $_SESSION['compte']['identifiant'] == self::USER_EMPLOYE && $demande->getTypeAvance() == self::AVANCE_ARCHIVED) {
                    $tmp['demande']        = $demande;
                    $manager               = new ManagerEmploye();
                    $tmp['employe']        = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                    $tmp['warning']        = false;
                    $tmp['parametre']      = $parametre;
                    $resultats[]           = $tmp;
                } elseif ($type == self::ALL_DEMANDE || $demande->getStatut() == $type) {
                    if (($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE && $demande->getTypeAvance() == self::AVANCE_NOT_ARCHIVED) || ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE && $demande->getTypeDemande() == self::AVANCE_NOT_ARCHIVED)) {
                        $tmp['demande']        = $demande;
                        $manager               = new ManagerEmploye();
                        $tmp['employe']        = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                        $tmp['parametre']      = $parametre;
                        $resultats[]           = $tmp;
                    }
                }
            }
            return $resultats;
        }

        /**
         * lister les employés pour la paie
         *
         * @param array $parameters les critères des données à lister
         *
         * @return empty
         */
        public function listerPaies($parameters)
        {
            $manager = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $donnees = array();
            $mois    = date('m');
            $annee   = date('Y');
            $mois   -= 1;
            if ($mois == 0) {
                $mois   = 12;
                $annee -= 1;
            }
            $debut = $annee . '-' . $mois . '-01';
            $fin   = $annee . '-' . $mois . '-01';
            if (!empty($parameters['annee'])) {
                $annee = $parameters['annee'];
            }
            if (!empty($parameters['mois'])) {
                if ($parameters['mois'] == self::ALL_YEAR) {
                    $debut = $annee . '-01-01';
                    $fin   = $annee . '-12-01';
                } else {
                    $debut = $annee . '-' . $parameters['mois'] . '-01';
                    $fin   = $annee . '-' . $parameters['mois'] . '-01';
                }
            }
            if (!empty($parameters['groupe'])) {
                if ($parameters['groupe'] == self::FILTER_GROUP_ALL) {
                    $manager  = new ManagerEmploye();
                    $employes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $donnees  = $this->getPaies($employes, $debut, $fin);
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_SERVICE) {
                    $manager     = new ManagerEmploye();
                    $allEmployes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $employes    = array();
                    foreach ($allEmployes as $employe) {
                        if ($this->getServiceEmploye($employe) != null && $this->getServiceEmploye($employe)->getIdEntrepriseService() == $parameters['id']) {
                            $employes[] = $employe;
                        }
                    }
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_POSTE) {
                    $manager     = new ManagerEmploye();
                    $allEmployes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $employes    = array();
                    foreach ($allEmployes as $employe) {
                        if ($this->getPosteEmploye($employe) != null && $this->getPosteEmploye($employe)->getIdEntreprisePoste() == $parameters['id']) {
                            $employes[] = $employe;
                        }
                    }
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_EMPLOYE) {
                    $employes   = array();
                    $manager    = new ManagerEmploye();
                    $employes[] = $manager->chercher(['idEmploye' => $parameters['id']]);
                }
            }
            $donnees = $this->getPaies($employes, $debut, $fin);
            $view    = new View("listerPaies");
            $view->sendWithoutTemplate("Backend", "Paie", $donnees, "");
            exit();
        }

        /**
         * Récupérer les employés pour la paie
         *
         * @param array $employes une liste d'employés
         *
         * @return array
         */
        private function getPaies($employes, $debut, $fin)
        {
            $resultats = array();
            $tmp['totalBrut']   = 0;
            $tmp['totalNet']    = 0;
            $tmp['totalCnaps']  = 0;
            $tmp['totalOstie']  = 0;
            $tmp['totalIrsa']   = 0;
            foreach ($employes as $employe) {
                $manager = new ManagerContratEmploye();
                $contrat = $manager->chercher([
                    'idEmploye' => $employe->getIdEmploye(),
                    'statut'    => self::VALIDATED
                ]);
                if ($contrat != null) {
                    $manager            = new ManagerServicePoste();
                    $servicePoste       = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                    $manager            = new ManagerCategorieProfessionnelle();
                    $categorie          = $manager->chercher(['idCategorieProfessionnelle' => $servicePoste->getIdCategorieProfessionnelle()]);
                    $safetyCount        = 12;
                    $tmp['salaireBrut'] = 0;
                    $tmp['salaireNet']  = 0;
                    $tmp['cnaps']       = 0;
                    $tmp['ostie']       = 0;
                    $tmp['irsa']        = 0;
                    $condition          = true;
                    $mois               = date('m', strtotime($debut));
                    $annee              = date('Y', strtotime($debut));
                    $lastMonth          = date('m', strtotime($fin));
                    $lastYear           = date('Y', strtotime($fin));
                    while ($safetyCount > 0 && $condition) {
                        $manager      = new ManagerFichePaie();
                        $fichePaie    = $manager->chercher([
                            'idEmploye' => $employe->getIdEmploye(),
                            'mois'      => $mois,
                            'annee'     => $annee
                        ]);
                        if ($fichePaie != null) {
                            $fichePaie           = $this->decrypterFichePaie($fichePaie);
                            $tmp['salaireBrut'] += $fichePaie->getSalaireBrut();
                            $tmp['totalBrut']   += $fichePaie->getSalaireBrut();
                            $tmp['salaireNet']  += $fichePaie->getSalaireNet();
                            $tmp['totalNet']    += $fichePaie->getSalaireNet();
                            $tmp['cnaps']       += $fichePaie->getDeductionCnaps();
                            $tmp['totalCnaps']  += $fichePaie->getDeductionCnaps();
                            $tmp['ostie']       += $fichePaie->getDeductionOstie();
                            $tmp['totalOstie']  += $fichePaie->getDeductionOstie();
                            $tmp['irsa']        += $fichePaie->getIrsaNet();
                            $tmp['totalIrsa']   += $fichePaie->getIrsaNet();
                        }
                        if (date("Y-m", strtotime($annee . '-' . $mois . '-01')) == date("Y-m", strtotime($lastYear . '-' . $lastMonth . '-01'))) {
                            $condition = false;
                        }
                        $mois += 1;
                        $safetyCount -= 1;
                        if ($mois > 12) {
                            $annee += 1;
                            $mois   = 1;
                        }
                    }
                    $tmp['employe']   = $employe;
                    $tmp['categorie'] = $categorie;
                    $resultats[]      = $tmp;
                }
            }
            return $resultats;
        }

        /**
         * lister les employés pour les avances spéciales
         *
         * @param array $parameters les critères des données à lister
         *
         * @return empty
         */
        public function listerAvances($parameters)
        {
            $manager = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $donnees = array();
            if (!empty($parameters['groupe'])) {
                if ($parameters['groupe'] == self::FILTER_GROUP_ALL) {
                    $manager  = new ManagerEmploye();
                    $employes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $donnees  = $this->getAvances($employes);
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_SERVICE) {
                    $manager     = new ManagerEmploye();
                    $allEmployes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $employes    = array();
                    foreach ($allEmployes as $employe) {
                        if ($this->getServiceEmploye($employe) != null && $this->getServiceEmploye($employe)->getIdEntrepriseService() == $parameters['id']) {
                            $employes[] = $employe;
                        }
                    }
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_POSTE) {
                    $manager     = new ManagerEmploye();
                    $allEmployes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $employes    = array();
                    foreach ($allEmployes as $employe) {
                        if ($this->getPosteEmploye($employe) != null && $this->getPosteEmploye($employe)->getIdEntreprisePoste() == $parameters['id']) {
                            $employes[] = $employe;
                        }
                    }
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_EMPLOYE) {
                    $employes   = array();
                    $manager    = new ManagerEmploye();
                    $employes[] = $manager->chercher(['idEmploye' => $parameters['id']]);
                }
            }
            $donnees = $this->getAvances($employes);
            $view = new View("listerAvances");
            $view->sendWithoutTemplate("Backend", "Paie", $donnees, "");
            exit();
        }

        /**
         * Récupérer les employés pour les avances
         *
         * @param array $employes une liste d'employés
         *
         * @return array
         */
        private function getAvances($employes)
        {
            $resultats = array();
            foreach ($employes as $employe) {
                $manager = new ManagerContratEmploye();
                $contrat = $manager->chercher([
                    'idEmploye' => $employe->getIdEmploye(),
                    'statut'    => self::VALIDATED
                ]);
                if ($contrat != null) {
                    $manager = new ManagerServicePoste();
                    $servicePoste = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                    $manager = new ManagerCategorieProfessionnelle();
                    $categorie = $manager->chercher(['idCategorieProfessionnelle' => $servicePoste->getIdCategorieProfessionnelle()]);
                    $tmp['employe']        = $employe;
                    $tmp['categorie']      = $categorie;
                    $tmp['avance']         = $this->getAvanceEmploye($employe->getIdEmploye());
                    $resultats[] = $tmp;
                }
            }
            return $resultats;
        }

        /**
         * Récupérer une avance en cours d'un employé
         *
         * @param int $idEmploye l'identifiant d'un employé
         *
         * @return object
         */
        private function getAvanceEmploye($idEmploye)
        {
            $manager   = new ManagerAvance();
            $avances   = $manager->lister(['idEmploye' => $idEmploye]);
            foreach ($avances as $avance) {
                $manager = new ManagerRemboursement();
                $remboursement = $manager->chercher([
                    'idAvance' => $avance->getIdAvance(),
                    'statut'   => self::NO
                ]);
                if ($remboursement != null) {
                    return $avance;
                }
            }
            return false;
        }

        /**
         * afficher les détails d'avance spéciale d'un employé
         *
         * @param array $parameters les critères des données à voir
         *
         * @return array
         */
        public function afficherDetailAvance($parameters)
        {
            if (!empty($parameters['idEmploye'] || $_SESSION['compte']['identifiant']) == self::USER_EMPLOYE) {
                if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                    $parameters['idEmploye'] = $_SESSION['user']['idEmploye'];
                }
                $manager  = new ManagerEmploye();
                $employe  = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $avance   = $this->getAvanceEmploye($employe->getIdEmploye());
                $remboursements = array();
                if ($avance != false) {
                    $manager        = new ManagerRemboursement();
                    $remboursements = $manager->lister(['idAvance' => $avance->getIdAvance()]);
                }
                return [
                    'employe'        => $employe,
                    'avance'         => $avance,
                    'remboursements' => $remboursements
                ];
            }
        }

        /**
         * afficher les historiques des avances spéciales d'un employé
         *
         * @param array $parameters les critères des données à voir
         *
         * @return array
         */
        public function afficherHistoriqueAvance($parameters)
        {
            if (!empty($parameters['idEmploye']) || $_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                    $parameters['idEmploye'] = $_SESSION['user']['idEmploye'];
                }
                $manager  = new ManagerEmploye();
                $employe  = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $manager  = new ManagerAvance();
                $avances  = $manager->lister([
                    'idEmploye' => $employe->getIdEmploye(),
                    'statut'    => self::VALIDATED
                ]);
                $remboursements = array();
                $isEnCours      = array();
                foreach ($avances as $avance) {
                    $manager = new ManagerRemboursement();
                    $tmpRemboursement = $manager->lister(['idAvance' => $avance->getIdAvance()]);
                    $remboursements[$avance->getIdAvance()] = $tmpRemboursement;
                    $isEnCours[$avance->getIdAvance()] = false;
                    foreach ($tmpRemboursement as $tmp) {
                        if ($tmp->getStatut() == self::NO) {
                            $isEnCours[$avance->getIdAvance()] = true;
                        }
                    }
                }
                return [
                    'employe'        => $employe,
                    'avances'        => $avances,
                    'remboursements' => $remboursements,
                    'isEnCours'      => $isEnCours
                ];
            }
        }

        /**
         * voir les détails de paie d'un employé
         *
         * @param array $parameters les critères des données à voir
         *
         * @return array
         */
        public function voirDetailFichePaie($parameters)
        {
            if (!empty($parameters['idEmploye']) || !empty($_SESSION['user']['idEmploye'])) {
                if (!empty($parameters['idEmploye'])) {
                    $idEmploye = $parameters['idEmploye'];
                } elseif (!empty($_SESSION['user']['idEmploye'])) {
                    $idEmploye = $_SESSION['user']['idEmploye'];
                }
                if (!empty($parameters['mois'])) {
                    $mois = $parameters['mois'];
                } else {
                    $mois = date('m');
                }
                if (!empty($parameters['annee'])) {
                    $annee = $parameters['annee'];
                } else {
                    $annee = date('Y');
                }
                $embauche    = $this->getDateEmbauche($idEmploye);
                $maxPresence = $this->getDureeJour(date($annee . '-' . $mois . '-01'), date("Y-m-d", mktime(0, 0, 0, ($mois + 1), 0, $annee)));
                $debut = date($annee . '-' . $mois . '-01');
                if (strtotime($embauche) > strtotime($debut)) {
                    $debut = $embauche;
                }
                if ($mois == date('m')) {
                    $fin = date('Y-m-d');
                } else {
                    $fin = date("Y-m-d", mktime(0, 0, 0, ($mois + 1), 0, $annee));
                }
                $presence = $this->getPresence($idEmploye, $mois, $annee);
                if ($this->isAllowed($_SESSION['user']['idEntreprise'], $idEmploye) || $_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                    if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                        $parameters['idEmploye'] = $_SESSION['user']['idEmploye'];
                    }
                    $manager    = new ManagerFichePaie();
                    if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                        $fichePaie  = $manager->chercher([
                            'idEmploye' => $parameters['idEmploye'],
                            'mois'      => $mois,
                            'annee'     => $annee,
                            'statut'    => self::FICHE_SENT
                        ]);
                    } elseif ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                        $fichePaie  = $manager->chercher([
                            'idEmploye' => $parameters['idEmploye'],
                            'mois'      => $mois,
                            'annee'     => $annee
                        ]);
                    }
                    $manager    = new ManagerEntreprise();
                    $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $manager    = new ManagerEmploye();
                    $employe    = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                    $employe->setSalaire($this->decrypter($employe->getSalaire()));
                    if ($fichePaie == null && $_SESSION['compte']['identifiant'] != self::USER_EMPLOYE) {
                        $conge      = $this->getConges($employe->getIdEmploye(), $mois, $annee);
                        $congePris  = $conge['congePris'];
                        $quantiteAllocation = $conge['quantiteAllocation'];
                        $soldeCongeFin = $conge['soldeCongeFin'];
                        $soldeCongeDebut = $conge['soldeCongeDebut'];
                        $manager    = new ManagerParametreHeure();
                        $parametreHeure = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
                        if ($parametreHeure == null) {
                            $parametreHeure = $manager->ajouter([
                                'idEmploye'                    => $employe->getIdEmploye(),
                                'heureNormale'                 => self::NOT_YET,
                                'heureSupplementaireActive'    => self::YES,
                                'heureSupplementaireImposable' => self::YES,
                                'heureNuitImposable'           => self::YES,
                                'travailNuitHabituel'          => self::NO,
                                'heureDimancheImposable'       => self::YES,
                                'travailDimancheHabituel'      => self::NO,
                                'heureFerieImposable'          => self::YES
                            ]);
                        }
                        $manager   = new ManagerAvantageEmploye();
                        $avantages = array();
                        $avantageImposables = $manager->lister([
                            'idEmploye' => $employe->getIdEmploye(),
                            'imposable' => self::YES,
                            'mois'      => $mois,
                            'annee'     => $annee
                        ]);
                        foreach ($avantageImposables as $avantageImposable) {
                            $manager = new ManagerAvantage();
                            $avantage = $manager->chercher(['idAvantage' => $avantageImposable->getIdAvantage()]);
                            $avantages[$avantageImposable->getIdAvantageEmploye()] = $avantage->getLibelle();
                        }
                        $manager   = new ManagerAvantageEmploye();
                        $avantageNonImposables = $manager->lister([
                            'idEmploye' => $employe->getIdEmploye(),
                            'imposable' => self::NO,
                            'mois'      => $mois,
                            'annee'     => $annee
                        ]);
                        foreach ($avantageNonImposables as $avantageNonImposable) {
                            $manager = new ManagerAvantage();
                            $avantage = $manager->chercher(['idAvantage' => $avantageNonImposable->getIdAvantage()]);
                            $avantages[$avantageNonImposable->getIdAvantageEmploye()] = $avantage->getLibelle();
                        }
                        $manager    = new ManagerDeduction();
                        $deductions = $manager->lister([
                            'idEmploye'  => $employe->getIdEmploye(),
                            'mois'       => $mois,
                            'annee'      => $annee
                        ]);
                        $salaireNet         = $this->getSalaireNet($parameters['idEmploye'], $mois, $annee);
                        $heures             = $this->explodeHeure($parameters['idEmploye'], $mois, $annee);
                        $AllocationConge    = $this->getAllocationConge($parameters['idEmploye'], $mois, $annee);
                        $poste              = $this->getPosteEmploye($employe);
                        $salaireNetEnLettre = $this->ecrireEnLettre($salaireNet);
                        if (strpos(strtolower($salaireNetEnLettre), "ariary") === false) {
                            $salaireNetEnLettre .= " ariary";
                        }
                        return [
                            'employe'                  => $employe,
                            'mois'                     => $mois,
                            'annee'                    => $annee,
                            'entreprise'               => $entreprise,
                            'poste'                    => $poste->getPoste(),
                            'debut'                    => $debut,
                            'fin'                      => $fin,
                            'soldeCongeDebut'          => $soldeCongeDebut,
                            'soldeCongeFin'            => $soldeCongeFin,
                            'congePris'                => $congePris,
                            'quantiteAllocation'       => $quantiteAllocation,
                            'presence'                 => $presence,
                            'salaireDeBase'            => $employe->getSalaire(),
                            'salaire'                  => $this->getSalaireProrata($parameters['idEmploye'], $mois, $annee),
                            'allocationConge'          => $this->getAllocationConge($parameters['idEmploye'], $mois, $annee),
                            'avantages'                => $avantages,
                            'avantageImposables'       => $avantageImposables,
                            'avantageNonImposables'    => $avantageNonImposables,
                            'deductions'               => $deductions,
                            'heureTotale'              => $heures['total'],
                            'heureSupplementaire'      => $heures['supplementaire'],
                            'majorationSupplementaire' => $heures['majorationSupplementaire'],
                            'heureNuit'                => $heures['nuit'],
                            'majorationNuit'           => $heures['majorationNuit'],
                            'heureDimanche'            => $heures['dimanche'],
                            'majorationDimanche'       => $heures['majorationDimanche'],
                            'heureFerie'               => $heures['ferie'],
                            'majorationFerie'          => $heures['majorationFerie'],
                            'parametreHeure'           => $parametreHeure,
                            'salaireBrut'              => $this->getSalaireBrut($parameters['idEmploye'], $mois, $annee),
                            'cotisationCnaps'          => $this->getCotisationCnaps($parameters['idEmploye'], $mois, $annee),
                            'cotisationOstie'          => $this->getCotisationOstie($parameters['idEmploye'], $mois, $annee),
                            'revenuImposable'          => $this->getRevenuImposable($parameters['idEmploye'], $mois, $annee),
                            'irsa'                     => $this->getIrsa($parameters['idEmploye'], $mois, $annee),
                            'enfants'                  => $employe->getNombreEnfants(),
                            'charge'                   => $this->getCharge($parameters['idEmploye']),
                            'irsaNet'                  => $this->getIrsaNet($parameters['idEmploye'], $mois, $annee),
                            'salaireNet'               => $salaireNet,
                            'salaireNetLettre'         => strtoupper($salaireNetEnLettre),
                            'statut'                   => self::NO
                        ];
                    } elseif ($fichePaie != null) {
                        $fichePaie  = $this->decrypterFichePaie($fichePaie);
                        $manager   = new ManagerAvantageEmploye();
                        $avantages = array();
                        $avantageImposables = $manager->lister([
                            'idEmploye' => $employe->getIdEmploye(),
                            'imposable' => self::YES,
                            'mois'      => $mois,
                            'annee'     => $annee
                        ]);
                        foreach ($avantageImposables as $avantageImposable) {
                            $manager = new ManagerAvantage();
                            $avantage = $manager->chercher(['idAvantage' => $avantageImposable->getIdAvantage()]);
                            $avantages[$avantageImposable->getIdAvantageEmploye()] = $avantage->getLibelle();
                        }
                        $manager   = new ManagerAvantageEmploye();
                        $avantageNonImposables = $manager->lister([
                            'idEmploye' => $employe->getIdEmploye(),
                            'imposable' => self::NO,
                            'mois'      => $mois,
                            'annee'     => $annee
                        ]);
                        $manager    = new ManagerDeduction();
                        $deductions = $manager->lister([
                            'idEmploye'  => $employe->getIdEmploye(),
                            'mois'       => $mois,
                            'annee'      => $annee
                        ]);
                        foreach ($avantageNonImposables as $avantageNonImposable) {
                            $manager = new ManagerAvantage();
                            $avantage = $manager->chercher(['idAvantage' => $avantageNonImposable->getIdAvantage()]);
                            $avantages[$avantageNonImposable->getIdAvantageEmploye()] = $avantage->getLibelle();
                        }
                        $manager = new ManagerParametreHeure();
                        $parametreHeure = $manager->initialiser();
                        $parametreHeure->setHeureSupplementaireActive($fichePaie->getHeureSupplementaireActive());
                        $parametreHeure->setHeureSupplementaireImposable($fichePaie->getHeureSupplementaireImposable());
                        $parametreHeure->setHeureNuitImposable($fichePaie->getHeureNuitImposable());
                        $parametreHeure->setHeureDimancheImposable($fichePaie->getHeureDimancheImposable());
                        $parametreHeure->setHeureFerieImposable($fichePaie->getHeureFerieImposable());
                        $salaireNetEnLettre = $this->ecrireEnLettre($fichePaie->getSalaireNet());
                        if (strpos(strtolower($salaireNetEnLettre), "ariary") === false) {
                            $salaireNetEnLettre .= " ariary";
                        }
                        return [
                            'employe'                    => $employe,
                            'entreprise'                 => $entreprise,
                            'poste'                      => $fichePaie->getPoste(),
                            'mois'                       => $mois,
                            'annee'                      => $annee,
                            'debut'                      => $fichePaie->getDebut(),
                            'fin'                        => $fichePaie->getFin(),
                            'soldeCongeDebut'            => $fichePaie->getSoldeCongeDebut(),
                            'soldeCongeFin'              => $fichePaie->getSoldeCongeFin(),
                            'congePris'                  => $fichePaie->getCongePris(),
                            'quantiteAllocation'         => $fichePaie->getQuantiteAllocation(),
                            'allocationConge'            => $fichePaie->getAllocationConge(),
                            'presence'                   => $fichePaie->getPresence(),
                            'salaireDeBase'              => $fichePaie->getSalaireDeBase(),
                            'salaire'                    => $fichePaie->getSalaire(),
                            'avantages'                  => $avantages,
                            'avantageImposables'         => $avantageImposables,
                            'avantageNonImposables'      => $avantageNonImposables,
                            'deductions'                 => $deductions,
                            'heureTotale'                => $fichePaie->getHeureEffective(),
                            'heureSupplementaire'        => $fichePaie->getQuantiteHeureSupplementaire(),
                            'majorationSupplementaire'   => $fichePaie->getMajorationHeureSupplementaire(),
                            'heureNuit'                  => $fichePaie->getQuantiteHeureNuit(),
                            'majorationNuit'             => $fichePaie->getMajorationHeureNuit(),
                            'heureDimanche'              => $fichePaie->getQuantiteHeureDimanche(),
                            'majorationDimanche'         => $fichePaie->getMajorationHeureDimanche(),
                            'heureFerie'                 => $fichePaie->getQuantiteHeureFerie(),
                            'majorationFerie'            => $fichePaie->getMajorationHeureFerie(),
                            'parametreHeure'             => $parametreHeure,
                            'salaireBrut'                => $fichePaie->getSalaireBrut(),
                            'cotisationCnaps'            => $fichePaie->getDeductionCnaps(),
                            'cotisationOstie'            => $fichePaie->getDeductionOstie(),
                            'revenuImposable'            => $fichePaie->getRevenuImposable(),
                            'irsa'                       => $fichePaie->getIrsa(),
                            'enfants'                    => $fichePaie->getQuantiteCharge(),
                            'charge'                     => $fichePaie->getDeductionCharge(),
                            'irsaNet'                    => $fichePaie->getIrsaNet(),
                            'salaireNet'                 => $fichePaie->getSalaireNet(),
                            'salaireNetLettre'           => strtoupper($salaireNetEnLettre),
                            'statut'                     => $fichePaie->getStatut()
                        ];
                    } else {
                        $poste              = $this->getPosteEmploye($employe);
                        if ($poste) {
                        	$poste       =	$poste->getPoste();
                        }
                        $conge              = $this->getConges($employe->getIdEmploye(), $mois, $annee);
                        $congePris          = $conge['congePris'];
                        $quantiteAllocation = $conge['quantiteAllocation'];
                        $soldeCongeFin      = $conge['soldeCongeFin'];
                        $soldeCongeDebut    = $conge['soldeCongeDebut'];
                        return [
                            'employe'                  => $employe,
                            'entreprise'               => $entreprise,
                            'poste'                    => $poste,
                            'mois'                     => $mois,
                            'annee'                    => $annee,
                            'debut'                    => $debut,
                            'fin'                      => $fin,
                            'soldeCongeDebut'          => $soldeCongeDebut,
                            'soldeCongeFin'            => $soldeCongeFin,
                            'congePris'                => $congePris,
                            'quantiteAllocation'       => $quantiteAllocation,
                            'presence'                 => $presence - $quantiteAllocation,
                            'indisponible'             => true
                        ];
                    }
                } else {
                    $_SESSION['info']['warning'] = "Vous ne pouvez pas accèder à cette page";
                    header("Location:" . HOST . "manage/entreprise/paie");
                }
            }
        }

        /**
         * Enregistrer la fiche de paie d'un employé
         *
         * @param array $parameters les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourFichePaie($parameters)
        {
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                $manager = new ManagerFichePaie();
                $fichePaie = $manager->chercher([
                    'idEmploye' => $parameters['idEmploye'],
                    'mois'      => $parameters['mois'],
                    'annee'     => $parameters['annee']
                ]);
                if ($fichePaie == null) {
                    $retour = $manager->ajouter($parameters);
                    if ($retour->getIdFichePaie() != self::NO) {
                        $fichePaie     = $manager->chercher(['idFichePaie' => $retour->getIdFichePaie()]);
                        $fichePaie     = $this->crypterFichePaie($fichePaie);
                        $manager->modifier($fichePaie->toArray());
                        $avanceEnCours = $this->getAvanceEmploye($parameters['idEmploye']);
                        if ($avanceEnCours != false) {
                            $manager       = new ManagerRemboursement();
                            $remboursement = $manager->chercher([
                                'idAvance' => $avanceEnCours->getIdAvance(),
                                'mois'     => $parameters['mois'],
                                'annee'    => $parameters['annee']
                            ]);
                            if ($remboursement != null) {
                                $manager = new ManagerRemboursement();
                                $manager->modifier([
                                    'idRemboursement' => $remboursement->getIdRemboursement(),
                                    'statut'          => self::YES
                                ]);
                            }
                        }
                        $manager         = new ManagerAvanceQuinzaine();
                        $quinzaineMois   = $manager->selectionner(
                            ['idEmploye' => $parameters['idEmploye'],
                             'statut'    => self::VALIDATED],
                            ['date'      => date(date('Y') . '-' . date('m') . '-01')],
                            ['date'      => date("Y-m-d", mktime(0, 0, 0, (date('m') + 1), 0, date('Y')))]
                        );
                        if (count($quinzaineMois) > self::NO) {
                            $quinzaineEnCours = $quinzaineMois[0];
                            $manager  = new ManagerAvanceQuinzaine();
                            $manager->modifier([
                                'idAvanceQuinzaine' => $quinzaineEnCours,
                                'statut'            => self::YES
                            ]);
                        }
                        $_SESSION['info']['success'] = "La fiche de paie a été figée avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Erreur lors de l'opération";
                    }
                } else {
                    $_SESSION['info']['warning'] = "La fiche de paie de ce mois est déjà figée";
                }
            } else {
                $_SESSION['info']['warning'] = "Vous ne pouvez pas accèder à cette page";
                header("Location:" . HOST . "manage/entreprise/paie");
            }
        }

        /**
         * Envoyer une fiche de paie à un employé
         *
         * @param array $parameters les critères de la fiche de paie à envoyer
         *
         * @return empty
         */
        public function envoyerFichePaie($parameters)
        {
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                $manager = new ManagerFichePaie();
                $fichePaie = $manager->chercher([
                    'idEmploye' => $parameters['idEmploye'],
                    'mois'      => $parameters['mois'],
                    'annee'     => $parameters['annee']
                ]);
                if ($fichePaie != null) {
                    $fichePaie  = $this->decrypterFichePaie($fichePaie);
                    $manager = new ManagerFichePaie();
                    $retour = $manager->modifier([
                        'idFichePaie' => $fichePaie->getIdFichePaie(),
                        'statut'      => self::FICHE_SENT
                    ]);
                    if ($retour->getStatut() == self::FICHE_SENT) {
                         $_SESSION['info']['success'] = "La fiche de paie a été envoyée avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } else {
                    $_SESSION['info']['danger'] = "Fiche de paie introuvable";
                }
            } else {
                $_SESSION['info']['warning'] = "Vous ne pouvez pas accèder à cette page";
                header("Location:" . HOST . "manage/entreprise/paie");
            }
        }

        /**
         * Imprimer une fiche de paie
         *
         * @param array $parameters les critères de la fiche à imprimer
         *
         * @return empty
         */
        public function imprimerFichePaie($parameters)
        {
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye']) || $_SESSION['user']['idEmploye'] == $parameters['idEmploye']) {
                $manager   = new ManagerEmploye();
                $employe   = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $embauche  = $this->getDateEmbauche($parameters['idEmploye']);
                $manager   = new ManagerParametrePaie();
                $parametre = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                $manager   = new ManagerFichePaie();
                $fichePaie = $manager->chercher([
                    'idEmploye' => $parameters['idEmploye'],
                    'mois'      => $parameters['mois'],
                    'annee'     => $parameters['annee']
                ]);
                $manager   = new ManagerAvantage();
                $avantages = $manager->lister([
                    'idEntreprise' => $employe->getIdEntreprise()
                ]);
                $manager    = new ManagerDeduction();
                $deductions = $manager->lister(null);
                $salaireNetEnLettre = $this->ecrireEnLettre($fichePaie->getSalaireNet());
                if (strpos(strtolower($salaireNetEnLettre), "ariary") === false) {
                    $salaireNetEnLettre .= " ariary";
                }
                if ($fichePaie != null) {
                    $fichePaie  = $this->decrypterFichePaie($fichePaie);
                    if (file_exists(DOC_ROOT . "Ressources/fichiers/template/" . $parametre->getTemplate())) {
                        $phpDocx = new PhpDocx(DOC_ROOT . "Ressources/fichiers/template/" . $parametre->getTemplate());
                        $phpDocx->assign(self::CHAMP_NOM, $employe->getNom());
                        $phpDocx->assign(self::CHAMP_PRENOM, $employe->getPrenom());
                        $phpDocx->assign(self::CHAMP_DEBUT, date('d/m/Y', strtotime($fichePaie->getDebut())));
                        $phpDocx->assign(self::CHAMP_FIN, date('d/m/Y', strtotime($fichePaie->getFin())));
                        $phpDocx->assign(self::CHAMP_MOIS, $fichePaie->getMois());
                        $phpDocx->assign(self::CHAMP_ANNEE, $fichePaie->getAnnee());
                        $phpDocx->assign(self::CHAMP_EMBAUCHE, date('d/m/Y', strtotime($embauche)));
                        $phpDocx->assign(self::CHAMP_POSTE, $fichePaie->getPoste());
                        $phpDocx->assign(self::CHAMP_SALAIRE_BASE, number_format($fichePaie->getSalaireDeBase(), 2));
                        $phpDocx->assign(self::CHAMP_SALAIRE_PRORATA, number_format($fichePaie->getSalaire(), 2));
                        $phpDocx->assign(self::CHAMP_HEURE_EFFECTIVE, $fichePaie->getHeureEffective());
                        $phpDocx->assign(self::CHAMP_HEURE_SUP, $fichePaie->getQuantiteHeureSupplementaire());
                        $phpDocx->assign(self::CHAMP_MAJORATION_HEURE_SUP, number_format($fichePaie->getMajorationHeureSupplementaire(), 2));
                        $phpDocx->assign(self::CHAMP_HEURE_NUIT, $fichePaie->getQuantiteHeureNuit());
                        $phpDocx->assign(self::CHAMP_MAJORATION_HEURE_NUIT, number_format($fichePaie->getMajorationHeureNuit(), 2));
                        $phpDocx->assign(self::CHAMP_HEURE_DIMANCHE, $fichePaie->getQuantiteHeureDimanche());
                        $phpDocx->assign(self::CHAMP_MAJORATION_HEURE_DIMANCHE, number_format($fichePaie->getMajorationHeureDimanche(), 2));
                        $phpDocx->assign(self::CHAMP_HEURE_FERIE, $fichePaie->getQuantiteHeureFerie());
                        $phpDocx->assign(self::CHAMP_MAJORATION_HEURE_FERIE, number_format($fichePaie->getMajorationHeureFerie(), 2));
                        $phpDocx->assign(self::CHAMP_RATIO_CNAPS, $fichePaie->getQuantiteCnaps() . '%');
                        $phpDocx->assign(self::CHAMP_DEDUCTION_CNAPS, number_format($fichePaie->getDeductionCnaps(), 2));
                        $phpDocx->assign(self::CHAMP_RATIO_OSTIE, $fichePaie->getQuantiteOstie() . '%');
                        $phpDocx->assign(self::CHAMP_DEDUCTION_OSTIE, number_format($fichePaie->getDeductionOstie(), 2));
                        $phpDocx->assign(self::CHAMP_REVENU_IMPOSABLE, number_format($fichePaie->getRevenuImposable(), 2));
                        $phpDocx->assign(self::CHAMP_IRSA, number_format($fichePaie->getIrsa(), 2));
                        $phpDocx->assign(self::CHAMP_QUANTITE_CHARGE, $fichePaie->getQuantiteCharge());
                        $phpDocx->assign(self::CHAMP_DEDUCTION_CHARGE, number_format($fichePaie->getDeductionCharge(), 2));
                        $phpDocx->assign(self::CHAMP_IRSA_NET, number_format($fichePaie->getIrsaNet(), 2));
                        $phpDocx->assign(self::CHAMP_SALAIRE_NET, number_format($fichePaie->getSalaireNet(), 2));
                        $phpDocx->assign(self::CHAMP_SALAIRE_NET_EN_LETTRE, strtoupper($salaireNetEnLettre));
                        $phpDocx->assign(self::CHAMP_SALAIRE_BRUT, number_format($fichePaie->getSalaireBrut(), 2));
                        $phpDocx->assign(self::CHAMP_SOLDE_CONGE_DEBUT, $fichePaie->getSoldeCongeDebut());
                        $phpDocx->assign(self::CHAMP_SOLDE_CONGE_FIN, $fichePaie->getSoldeCongeFin());
                        $phpDocx->assign(self::CHAMP_CONGE_PRIS, $fichePaie->getCongePris());
                        $phpDocx->assign(self::CHAMP_QUANTITE_ALLOCATION, $fichePaie->getQuantiteAllocation());
                        $phpDocx->assign(self::CHAMP_PRESENCE, $fichePaie->getPresence());
                        $phpDocx->assign(self::CHAMP_ALLOCATION_CONGE, number_format($fichePaie->getAllocationConge(), 2));
                        foreach ($avantages as $avantage) {
                            $libelle         = utf8_decode($avantage->getLibelle());
                            $libelle         = strtr($libelle, utf8_decode('ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ'), 'aaaaaceeeeiiiiooooouuuuyaaaaaaceeeeiiiioooooouuuuyy');
                            $champRatio      = "[RATIO_" . mb_strtoupper(str_replace(" ", "_", $libelle)) . "]";
                            $champ           = "[AVANTAGE_" . mb_strtoupper(str_replace(" ", "_", $libelle)) . "]";
                            $champTotal      = "[TOTAL_" . mb_strtoupper(str_replace(" ", "_", $libelle)) . "]";
                            $manager         = new ManagerAvantageEmploye();
                            $avantageEmploye = $manager->chercher([
                                'idEmploye'  => $parameters['idEmploye'],
                                'mois'       => $parameters['mois'],
                                'annee'      => $parameters['annee'],
                                'idAvantage' => $avantage->getIdAvantage()
                            ]);
                            if ($avantageEmploye != null) {
                                $phpDocx->assign($champRatio, $avantageEmploye->getRatioImposable() . '%');
                                $phpDocx->assign($champ, number_format(($avantageEmploye->getMontant() * $avantageEmploye->getRatioImposable()) / self::CENT_POUR_CENT , 2));
                                $phpDocx->assign($champTotal, $avantageEmploye->getMontant());
                            } else {
                                $phpDocx->assign($champRatio, "-");
                                $phpDocx->assign($champ, "-");
                                $phpDocx->assign($champTotal, "-");
                            }
                        }
                        foreach ($deductions as $deduction) {
                            $libelle         = utf8_decode($deduction->getLibelle());
                            $libelle         = strtr($libelle, utf8_decode('ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ'), 'aaaaaceeeeiiiiooooouuuuyaaaaaaceeeeiiiioooooouuuuyy');
                            $champ           = "[DEDUCTION_" . mb_strtoupper(str_replace(" ", "_", $libelle)) . "]";
                            if ($deduction->getIdEmploye() == $parameters['idEmploye']) {
                                $manager      = new ManagerDeduction();
                                $tmpDeduction = $manager->chercher([
                                    'libelle'     => $deduction->getLibelle(),
                                    'mois'        => $parameters['mois'],
                                    'annee'       => $parameters['annee']
                                ]);
                                if ($tmpDeduction != null) {
                                    $phpDocx->assign($champ, number_format($tmpDeduction->getMontant(), 2));
                                } else {
                                    $phpDocx->assign($champ, '-');
                                }
                            } else {
                                $phpDocx->assign($champ, '-');
                            }
                        }
                        $phpDocx->download("template_fiche_de_paie.docx");
                    } else {
                        $_SESSION['info']['danger'] = "template de fiche de paie introuvable";
                    }
                } else {
                    $_SESSION['info']['danger'] = "Fiche de paie introuvable";
                }
            } else {
                $_SESSION['info']['warning'] = "Vous ne pouvez pas accèder à cette page";
                header("Location:" . HOST . "manage/entreprise/paie");
            }
        }

        /**
         * Retourner le nombre de jours écoulés entre deux dates
         *
         * @param date $debut       la date de début
         * @param int  $heureDebut  l'heure de début du congé
         * @param date $fin         la date de fin du congé
         * @param int  $heureFin    l'heure de fin
         *
         * @return float
         */
        private function getJours($debut, $heureDebut, $fin, $heureFin)
        {
            $secondes = abs(strtotime($debut) - strtotime($fin));
            $resultat =  ($secondes / self::SECOND_TO_DAY) + self::ONE_DAY;
            if ($heureDebut == self::APRES_MIDI) {
                $resultat -= self::DEMI_JOURNEE;
            }
            if ($heureFin == self::APRES_MIDI) {
                $resultat -= self::DEMI_JOURNEE;
            }
            return $resultat;
        }

        /**
         * Retourner le nombre de jours de congé pris dans le mois
         *
         * @param array $conges  une liste de congés
         *
         * @return float
         */
        private function getJourCongeMensuel($conges)
        {
            $jours = 0;
            foreach ($conges as $conge) {
                $jours += $this->getJours($conge->getDebut(), $conge->getHeureDebut(), $conge->getFin(), $conge->getHeureFin());
            }
            return $jours;
        }

        /**
         * Retourner la quantité de l'allocation de congé
         *
         * @param array $conges une liste de congés
         *
         * @return float
         */
        private function getQuantiteAllocation($conges)
        {
            $manager = new ManagerParametrePaie();
            $parametrePaie = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $jours   = 0;
            foreach ($conges as $conge) {
                $duree = $this->getJours($conge->getDebut(), $conge->getHeureDebut(), $conge->getFin(), $conge->getHeureFin());
                if ($duree >= $parametrePaie->getLimiteAllocationConge()) {
                    $jours += $duree;
                }
            }
            return $jours;
        }

        /**
         * voir les paramètres de paie d'un employé
         *
         * @param array $parameters les critères des données à voir
         *
         * @return array
         */
        public function voirParametreFichePaie($parameters)
        {
            if (!empty($parameters['idEmploye'])) {
                if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                    $tableauAvantages = array();
                    $manager          = new ManagerEmploye();
                    $employe          = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                    $manager          = new ManagerAvantage();
                    $avantages        = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $manager          = new ManagerDeduction();
                    $deductions       = $manager->lister([
                        'idEmploye'   => $parameters['idEmploye'],
                        'mois'        => date('m'),
                        'annee'       => date('Y')
                    ]);
                    $manager          = new ManagerAvantageEmploye();
                    $avantageEmployes = $manager->lister([
                        'idEmploye' => $parameters['idEmploye'],
                        'mois'      => date('m'),
                        'annee'     => date('Y')
                    ]);
                    foreach ($avantageEmployes as $avantageEmploye) {
                        $manager = new ManagerAvantage();
                        $tableauAvantages[$avantageEmploye->getIdAvantage()] = $manager->chercher(['idAvantage' => $avantageEmploye->getIdAvantage()]);
                    }
                    $manager          = new ManagerParametreHeure();
                    $parametreHeure   = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
                    if ($parametreHeure == null) {
                        $parametreHeure = $manager->ajouter([
                            'idEmploye'                    => $employe->getIdEmploye(),
                            'heureNormale'                 => self::NOT_YET,
                            'heureSupplementaireActive'    => self::YES,
                            'heureSupplementaireImposable' => self::YES,
                            'heureNuitImposable'           => self::YES,
                            'travailNuitHabituel'          => self::NO,
                            'heureDimancheImposable'       => self::YES,
                            'travailDimancheHabituel'      => self::NO,
                            'heureFerieImposable'          => self::YES
                        ]);
                    }
                    $parametreHeure->setHeureNormale($this->getHeureHebdomadaire($parametreHeure->getHeureNormale()));
                    return [
                        'employe'          => $employe,
                        'avantages'        => $avantages,
                        'avantageEmployes' => $avantageEmployes,
                        'deductions'       => $deductions,
                        'parametreHeure'   => $parametreHeure,
                        'tableauAvantages' => $tableauAvantages
                    ];
                } else {
                    $_SESSION['info']['danger'] = "Vous ne pouvez pas acceder à cette page";
                    header("Location:" . HOST . "manage/entreprise/paie");
                }
            }
        }

        /**
         * Mettre à jour une déduction d'un employé
         *
         * @param array $parameters les critères des données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourDeduction($parameters)
        {
            if (!empty($parameters)) {
                if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                    $mois  = date('m');
                    $annee = date('Y');
                    if (!empty($parameters['mois'])) {
                        $mois = $parameters['mois'];
                    }
                    if (!empty($parameters['annee'])) {
                        $annee = $parameters['annee'];
                    }
                    if (empty($parameters['idDeduction'])) {
                        $manager = new ManagerDeduction();
                        $retour = $manager->ajouter([
                            'idEmploye'   => $parameters['idEmploye'],
                            'mois'        => $mois,
                            'annee'       => $annee,
                            'montant'     => $parameters['montant'],
                            'libelle'     => $parameters['libelle']
                        ]);
                        if ($retour->getIdDeduction() != self::NO) {
                            $_SESSION['info']['success'] = "La déduction a été ajoutée avec succès";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération";
                        }
                    } else {
                        $manager = new ManagerDeduction();
                        $deduction = $manager->chercher([
                            'idDeduction' => $parameters['idDeduction'],
                            'mois'        => $mois,
                            'annee'       => $annee
                        ]);
                        if ($deduction != null) {
                            $manager       = new ManagerRemboursement();
                            $remboursement = $manager->chercher(['idDeduction' => $deduction->getIdDeduction()]);
                            $manager       = new ManagerAvanceQuinzaine();
                            $quinzaine     = $manager->chercher(['idDeduction' => $deduction->getIdDeduction()]);
                            if ($remboursement == null && $quinzaine == null) {
                                $manager = new ManagerDeduction();
                                $retour  = $manager->modifier([
                                    'idDeduction' => $parameters['idDeduction'],
                                    'montant'     => $parameters['montant'],
                                    'libelle'     => $parameters['libelle']
                                ]);
                                if ($retour->getLibelle() == $parameters['libelle'] && $retour->getMontant() == $parameters['montant']) {
                                    $_SESSION['info']['success'] = "La modification a été effectuée avec succès";
                                } else {
                                    $_SESSION['info']['danger'] = "Echec lors de l'opération";
                                }
                            } elseif ($remboursement != null) {
                                $manager        = new ManagerParametreAvance();
                                $parametre      = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                                $manager        = new ManagerEmploye();
                                $employe        = $manager->chercher(['idEmploye' => $deduction->getIdEmploye()]);
                                $employe->setSalaire($this->decrypter($employe->getSalaire()));
                                $tauxMensuel    = ($employe->getSalaire() * $parametre->getTauxMax()) / self::CENT_POUR_CENT;
                                $manager        = new ManagerAvanceQuinzaine();
                                $quinzaineMois  = $manager->selectionner(
                                    ['idEmploye' => $employe->getIdEmploye(),
                                     'statut'    => self::VALIDATED],
                                    ['date'      => date(date('Y') . '-' . date('m') . '-01')],
                                    ['date'      => date("Y-m-d", mktime(0, 0, 0, (date('m') + 1), 0, date('Y')))]
                                );
                                $maxPossible    = $tauxMensuel;
                                if (count($quinzaineMois) > self::NO) {
                                    $quinzaine  = $quinzaineMois[0];
                                    $maxPossible -= $quinzaine->getMontant();
                                }
                                if ($parameters['montant'] <= $maxPossible) {
                                    $manager = new ManagerDeduction();
                                    $retour  = $manager->modifier([
                                        'idDeduction' => $parameters['idDeduction'],
                                        'montant'     => $parameters['montant']
                                    ]);
                                    if ($retour->getMontant() == $parameters['montant']) {
                                        $manager  = new ManagerRemboursement();
                                        $feedback = $manager->modifier([
                                            'idRemboursement' => $remboursement->getIdRemboursement(),
                                            'montant'         => $parameters['montant']
                                        ]);
                                        if ($feedback->getMontant() == $retour->getMontant()) {
                                            $this->resetPlanningRemboursement($remboursement->getIdAvance());
                                            $_SESSION['info']['success'] = "La modification a été effectuée avec succès";
                                        } else {
                                            $_SESSION['info']['danger'] = "Echec lors de la synchronisation";
                                        }
                                    } else {
                                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                                    }
                                } else {
                                    $_SESSION['info']['warning'] = "Le montant saisi dépasse le maximum autorisé <b>[" . $maxPossible . "]</b>";
                                }
                            } elseif ($quinzaine != null) {
                                $manager        = new ManagerParametreAvance();
                                $parametre      = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                                $manager        = new ManagerEmploye();
                                $employe        = $manager->chercher(['idEmploye' => $deduction->getIdEmploye()]);
                                $employe->setSalaire($this->decrypter($employe->getSalaire()));
                                $tauxMensuel    = ($employe->getSalaire() * $parametre->getTauxMax()) / self::CENT_POUR_CENT;
                                $manager        = new managerRemboursement();
                                $avanceEnCours  = $this->getAvanceEmploye($employe->getIdEmploye());
                                $remboursement  = $manager->chercher([
                                    'idAvance' => $avanceEnCours->getIdAvance(),
                                    'mois'     => date('m'),
                                    'annee'    => date('Y')
                                ]);
                                $maxPossible = $tauxMensuel;
                                if ($remboursement != null) {
                                    $maxPossible -= $remboursement->getMontant();
                                }
                                if ($parameters['montant'] <= $maxPossible) {
                                    $manager = new ManagerDeduction();
                                    $retour  = $manager->modifier([
                                        'idDeduction' => $parameters['idDeduction'],
                                        'montant'     => $parameters['montant']
                                    ]);
                                    if ($retour->getMontant() == $parameters['montant']) {
                                        $manager  = new ManagerAvanceQuinzaine();
                                        $feedback = $manager->modifier([
                                            'idAvanceQuinzaine' => $quinzaine->getIdAvanceQuinzaine(),
                                            'montant'           => $parameters['montant']
                                        ]);
                                        if ($feedback->getMontant() == $retour->getMontant()) {
                                            $_SESSION['info']['success'] = "La modification a été effectuée avec succès";
                                        } else {
                                            $_SESSION['info']['danger'] = "Echec lors de la synchronisation";
                                        }
                                    } else {
                                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                                    }
                                } else {
                                    $_SESSION['info']['warning'] = "Le montant saisi dépasse le maximum autorisé <b>[" . $maxPossible . "]</b>";
                                }
                            }
                        }
                    }
                    if (!empty($parameters['page'])) {
                        header("Location:" . HOST . "manage/edit-fichePaie?idEmploye=" . $parameters['idEmploye'] . "&mois=" . $mois . "&annee=" . $annee);
                        exit();
                    }
                } else {
                    $_SESSION['info']['warning'] = "Vous n'êtes pas autorisé à voir cette page";
                    $this->rediriger();
                }
            }
        }

        /**
         * Mettre à jour un avantage d'un employé
         *
         * @param array $parameters les critères des données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourAvantageEmploye($parameters)
        {
            if (!empty($parameters)) {
                if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                    $mois  = date('m');
                    $annee = date('Y');
                    if (!empty($parameters['mois'])) {
                        $mois = $parameters['mois'];
                    }
                    if (!empty($parameters['annee'])) {
                        $annee = $parameters['annee'];
                    }
                    if (empty($parameters['idAvantageEmploye'])) {
                        $manager = new ManagerAvantageEmploye();
                        $avantageEmploye = $manager->chercher([
                            'idAvantage' => $parameters['idAvantage'],
                            'idEmploye'  => $parameters['idEmploye'],
                            'mois'       => $mois,
                            'annee'      => $annee
                        ]);
                        if ($avantageEmploye == null) {
                            if (!empty($parameters['imposable'])) {
                                $parameters['imposable'] = self::NO;
                            } else {
                                $manager = new ManagerAvantage();
                                $avantage = $manager->chercher([
                                    'idAvantage' => $parameters['idAvantage']
                                ]);
                                if ($avantage->getImposable() == self::IMPOSABLE_POUR_TOUS) {
                                    $parameters['imposable'] = self::YES;
                                } elseif ($avantage->getImposable() == self::NON_IMPOSABLE_POUR_TOUS) {
                                    $parameters['imposable'] = self::NO;
                                } else {
                                    $parameters['imposable'] = self::YES;
                                }
                            }
                            $manager = new ManagerAvantageEmploye();
                            $retour = $manager->ajouter([
                                'idEmploye'      => $parameters['idEmploye'],
                                'montant'        => $parameters['montant'],
                                'imposable'      => $parameters['imposable'],
                                'idAvantage'     => $parameters['idAvantage'],
                                'mois'           => $mois,
                                'annee'          => $annee,
                                'ratioImposable' => $avantage->getRatioImposable()
                            ]);
                            if ($retour->getIdAvantageEmploye() != self::NO) {
                                $_SESSION['info']['success'] = "L'avantage a été ajouté avec succès !";
                            } else {
                                $_SESSION['info']['danger'] = "Echec lors de l'opération !";
                            }
                        } else {
                            $_SESSION['info']['warning'] = "Cet employé bénéficie déjà de cet avantage !";
                        }
                    } else {
                        $manager = new ManagerAvantageEmploye();
                        $avantageEmploye = $manager->chercher([
                            'idAvantageEmploye' => $parameters['idAvantageEmploye'],
                            'mois'              => $mois,
                            'annee'             => $annee
                        ]);
                        if ($avantageEmploye != null) {
                            if (!empty($parameters['imposable'])) {
                                $parameters['imposable'] = self::NO;
                            } else {
                                $manager = new ManagerAvantage();
                                $avantage = $manager->chercher([
                                    'idAvantage' => $parameters['idAvantage']
                                ]);
                                if ($avantage->getImposable() == self::IMPOSABLE_POUR_TOUS) {
                                    $parameters['imposable'] = self::YES;
                                } elseif ($avantage->getImposable() == self::NON_IMPOSABLE_POUR_TOUS) {
                                    $parameters['imposable'] = self::NO;
                                } else {
                                    $parameters['imposable'] = self::YES;
                                }
                            }
                            $manager = new ManagerAvantageEmploye();
                            $retour = $manager->modifier([
                                'idAvantageEmploye' => $parameters['idAvantageEmploye'],
                                'montant'           => $parameters['montant'],
                                'idAvantage'        => $parameters['idAvantage'],
                                'imposable'         => $parameters['imposable']
                            ]);
                        }
                    }
                    if (!empty($parameters['page'])) {
                        header("Location:" . HOST . "manage/edit-fichePaie?idEmploye=" . $parameters['idEmploye'] . "&mois=" . $mois . "&annee=" . $annee);
                        exit();
                    }
                } else {
                    $_SESSION['info']['warning'] = "Vous n'avez pas le droit de consulter cette page";
                    $this->rediriger();
                }
            }
        }

        /**
         * Supprimer un avantage d'un employe
         *
         * @param array $parameters les critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerAvantageEmploye($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerAvantageEmploye();
                $avantageEmploye = $manager->chercher(['idAvantageEmploye' => $parameters['idAvantageEmploye']]);
                if ($avantageEmploye != null) {
                    if ($this->isAllowed($_SESSION['user']['idEntreprise'], $avantageEmploye->getIdEmploye())) {
                        $retour = $manager->supprimer(['idAvantageEmploye' => $avantageEmploye->getIdAvantageEmploye()]);
                        if ($retour) {
                            $_SESSION['info']['success'] = "L'avantage a été supprimé avec succès !";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération !";
                        }
                    }
                }
                if (!empty($parameters['mois']) && !empty($parameters['annee'])) {
                    header("Location:" . HOST . "manage/edit-fichePaie?idEmploye=" . $parameters['idEmploye'] . "&mois=" . $parameters['mois'] . "&annee=" . $parameters['annee']);
                    exit();
                }
            }
        }

        /**
         * Supprimer une déduction
         *
         * @param array $parameters les critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerDeduction($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerDeduction();
                $deduction = $manager->chercher(['idDeduction' => $parameters['idDeduction']]);
                if ($deduction != null) {
                    if ($this->isAllowed($_SESSION['user']['idEntreprise'], $deduction->getIdEmploye())) {
                        $retour = $manager->supprimer(['idDeduction' => $deduction->getIdDeduction()]);
                        if ($retour) {
                            $_SESSION['info']['success'] = "La déduction a été supprimée avec succès !";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération !";
                        }
                    }
                }
                if (!empty($parameters['mois']) && !empty($parameters['annee'])) {
                    header("Location:" . HOST . "manage/edit-fichePaie?idEmploye=" . $parameters['idEmploye'] . "&mois=" . $parameters['mois'] . "&annee=" . $parameters['annee']);
                    exit();
                }
            }
        }

        /**
         * Mettre à jour le paramètre des heures de travail d'un employé
         *
         * @param array $parameters les critères des données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourParametreHeure($parameters)
        {
            if (!empty($parameters['idEmploye'])) {
                $manager = new ManagerParametreHeure();
                $parametreHeure = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                if (!empty($parameters['heureNormale'])) {
                    $retour = $manager->modifier([
                        'idParametreHeure'  => $parametreHeure->getIdParametreHeure(),
                        'heureNormale'      => $this->getHeureMensuelle($parameters['heureNormale'])
                    ]);
                    if ($this->getHeureHebdomadaire($retour->getHeureNormale()) == $parameters['heureNormale']) {
                        echo self::OK;
                        exit();
                    } else {
                        echo self::WRONG;
                        exit();
                    }
                } elseif (isset($parameters['heureSupplementaireActive'])) {
                    $retour = $manager->modifier([
                        'idParametreHeure'          => $parametreHeure->getIdParametreHeure(),
                        'heureSupplementaireActive' => $parameters['heureSupplementaireActive']
                    ]);
                    if ($retour->getHeureSupplementaireActive() == $parameters['heureSupplementaireActive']) {
                        echo self::OK;
                        exit();
                    } else {
                        echo self::WRONG;
                        exit();
                    }
                } elseif (isset($parameters['heureSupplementaireImposable'])) {
                    $retour = $manager->modifier([
                        'idParametreHeure'             => $parametreHeure->getIdParametreHeure(),
                        'heureSupplementaireImposable' => $parameters['heureSupplementaireImposable']
                    ]);
                    if ($retour->getHeureSupplementaireImposable() == $parameters['heureSupplementaireImposable']) {
                        echo self::OK;
                        exit();
                    } else {
                        echo self::WRONG;
                        exit();
                    }
                } elseif (isset($parameters['heureNuitImposable'])) {
                    $retour = $manager->modifier([
                        'idParametreHeure'   => $parametreHeure->getIdParametreHeure(),
                        'heureNuitImposable' => $parameters['heureNuitImposable']
                    ]);
                    if ($retour->getHeureNuitImposable() == $parameters['heureNuitImposable']) {
                        echo self::OK;
                        exit();
                    } else {
                        echo self::WRONG;
                        exit();
                    }
                } elseif (isset($parameters['travailNuitHabituel'])) {
                    $retour = $manager->modifier([
                        'idParametreHeure'    => $parametreHeure->getIdParametreHeure(),
                        'travailNuitHabituel' => $parameters['travailNuitHabituel']
                    ]);
                    if ($retour->getTravailNuitHabituel() == $parameters['travailNuitHabituel']) {
                        echo self::OK;
                        exit();
                    } else {
                        echo self::WRONG;
                        exit();
                    }
                } elseif (isset($parameters['heureDimancheImposable'])) {
                    $retour = $manager->modifier([
                        'idParametreHeure'       => $parametreHeure->getIdParametreHeure(),
                        'heureDimancheImposable' => $parameters['heureDimancheImposable']
                    ]);
                    if ($retour->getHeureDimancheImposable() == $parameters['heureDimancheImposable']) {
                        echo self::OK;
                        exit();
                    } else {
                        echo self::WRONG;
                        exit();
                    }
                } elseif (isset($parameters['travailDimancheHabituel'])) {
                    $retour = $manager->modifier([
                        'idParametreHeure'        => $parametreHeure->getIdParametreHeure(),
                        'travailDimancheHabituel' => $parameters['travailDimancheHabituel']
                    ]);
                    if ($retour->getTravailDimancheHabituel() == $parameters['travailDimancheHabituel']) {
                        echo self::OK;
                        exit();
                    } else {
                        echo self::WRONG;
                        exit();
                    }
                } elseif (isset($parameters['heureFerieImposable'])) {
                    $retour = $manager->modifier([
                        'idParametreHeure'    => $parametreHeure->getIdParametreHeure(),
                        'heureFerieImposable' => $parameters['heureFerieImposable']
                    ]);
                    if ($retour->getHeureFerieImposable() == $parameters['heureFerieImposable']) {
                        echo self::OK;
                        exit();
                    } else {
                        echo self::WRONG;
                        exit();
                    }
                }
            }
        }


        /**
         * Voir les paramètres du module paie
         *
         * @param array $parameters les critères des données à afficher
         *
         * @return array
         */
        public function voirParametrePaie($parameters)
        {
            if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                $manager        = new ManagerEntreprise();
                $entreprise     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager        = new ManagerParametrePaie();
                $parametrePaie  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                if ($parametrePaie == null) {
                    $parametrePaie = $manager->ajouter([
                        'idEntreprise' => $_SESSION['user']['idEntreprise'],
                        'minimumDePerception' => self::NOT_YET,
                        'chargeFamiliale' => self::NOT_YET,
                        'salaireMinimumEmbauche' => self::NOT_YET
                    ]);
                }
                return [
                    'entreprise' => $entreprise,
                    'parametrePaie' => $parametrePaie
                ];
            }
        }

        /**
         * Voir les avantages de paie d'une entreprise
         *
         * @param array $parameters les critères des données à afficher
         *
         * @return array
         */
        public function voirAvantagePaie($parameters)
        {
            if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                $manager        = new ManagerEntreprise();
                $entreprise     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager        = new ManagerParametrePaie();
                $parametrePaie  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                if ($parametrePaie == null) {
                    $parametrePaie = $manager->ajouter([
                        'idEntreprise' => $_SESSION['user']['idEntreprise'],
                        'minimumDePerception' => self::NOT_YET,
                        'chargeFamiliale' => self::NOT_YET,
                        'salaireMinimumEmbauche' => self::NOT_YET
                    ]);
                }
                return [
                    'entreprise' => $entreprise,
                    'parametrePaie' => $parametrePaie
                ];
            }
        }

        /**
         * Lister les avantages de paie dans une entreprise
         *
         * @param array $parameters critères des données à afficher
         *
         * @return array
         */
        public function listerAvantagePaies($parameters)
        {
            if (!empty($parameters)) {
                $manager     = new ManagerAvantage();
                if ($parameters['statut'] == "") {
                    $donnees = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                } else {
                    $donnees = $manager->lister([
                        'idEntreprise' => $_SESSION['user']['idEntreprise'],
                        'imposable'    => $parameters['statut']
                    ]);
                }
                $view = new View("listerAvantagePaies");
                $view->sendWithoutTemplate("Backend", "Paie", $donnees, "");
                exit();
            }
        }

        /**
         * Récupérer un avantage d'une entreprise
         *
         * @param array $parameters critères des données à récupérer
         *
         * @return empty
         */
        public function getAvantage($parameters)
        {
            if (!empty($parameters)) {
                $manager  = new ManagerAvantage();
                $avantage = $manager->chercher(['idAvantage' => $parameters['idAvantage']]);
                if ($avantage != null) {
                    echo json_encode($avantage->toArray());
                } else {
                    echo null;
                }
                exit();
            }
        }

        /**
         * Mettre à jour un avantage dans une entreprise
         *
         * @param array $parameters critères des données à mettre à jour
         *
         * @return array
         */
        public function mettreAJourAvantage($parameters)
        {
            if (!empty($parameters)) {
                if (empty($parameters['idAvantage'])) {
                    $manager = new ManagerAvantage();
                    $retour  = $manager->ajouter([
                        'idEntreprise'   => $_SESSION['user']['idEntreprise'],
                        'libelle'        => $parameters['libelle'],
                        'imposable'      => $parameters['imposable'],
                        'ratioImposable' => $parameters['ratioImposable']
                    ]);
                    if ($retour->getIdAvantage() != self::NO) {
                        $_SESSION['info']['success'] = "L'avantage a été ajouté avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } else {
                    $manager = new ManagerAvantage();
                    $retour  = $manager->modifier([
                        'idAvantage'     => $parameters['idAvantage'],
                        'libelle'        => $parameters['libelle'],
                        'imposable'      => $parameters['imposable'],
                        'ratioImposable' => $parameters['ratioImposable']
                    ]);
                    if ($retour->getLibelle() == $parameters['libelle'] && $retour->getImposable() == $parameters['imposable'] && $retour->getRatioImposable() == $parameters['ratioImposable']) {
                        $manager = new ManagerAvantageEmploye();
                        $avantageEmployes = $manager->lister([
                            'idAvantage'  => $parameters['idAvantage'],
                            'mois'        => date('m'),
                            'annee'       => date('Y')
                        ]);
                        foreach ($avantageEmployes as $avantageEmploye) {
                            $manager->modifier([
                                'idAvantageEmploye' => $avantageEmploye->getIdAvantageEmploye(),
                                'ratioImposable'    => $parameters['ratioImposable']
                            ]);
                        }
                        $_SESSION['info']['success'] = "L'avantage a été modifié avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                }
            }
        }

        /**
         * Supprimer un avantage dans une entreprise
         *
         * @param array $parameters critères des données à supprimer
         *
         * @return array
         */
        public function supprimerAvantage($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerAvantage();
                $retour  = $manager->supprimer(['idAvantage' => $parameters['idAvantage']]);
                if (!$retour) {
                    $_SESSION['info']['danger'] = "Echec lors de l'opération";
                } else {
                    $_SESSION['info']['success'] = "L'avantage a été supprimé avec succès";
                }
            }
        }

        /**
         * Mettre à jour un paramètre d'avance
         *
         * @param array $parameters les critères des données à mettre à jour
         *
         * @return array
         */
        public function mettreAJourParametreAvance($parameters)
        {
            $manager   = new ManagerParametreAvance();
            $parametre = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if ($parametre == null) {
                $parametre = $manager->ajouter([
                    'idEntreprise' => $_SESSION['user']['idEntreprise'],
                    'dureeMax'     => self::UNE_ANNEE,
                    'tauxMax'      => self::TAUX_REMBOURSEMENT
                ]);
            }
            if (!empty($parameters['dureeMax'])) {
                $retour = $manager->modifier([
                    'idParametreAvance' => $parametre->getIdParametreAvance(),
                    'dureeMax'          => $parameters['dureeMax']
                ]);
                if ($retour->getDureeMax() == $parameters['dureeMax']) {
                    $_SESSION['info']['success'] = "La modification a été effectuée avec succès";
                } else {
                    $_SESSION['info']['danger'] = "Echec lors de l'opération";
                }
            } elseif (!empty($parameters['tauxMax'])) {
                $retour = $manager->modifier([
                    'idParametreAvance' => $parametre->getIdParametreAvance(),
                    'tauxMax'           => $parameters['tauxMax']
                ]);
                if ($retour->getTauxMax() == $parameters['tauxMax']) {
                    $_SESSION['info']['success'] = "La modification a été effectuée avec succès";
                } else {
                    $_SESSION['info']['danger'] = "Echec lors de l'opération";
                }
            }
        }

        /**
         * Mettre à jour un paramètre de paie
         *
         * @param array $parameters les critères des données à mettre à jour
         *
         * @return array
         */
        public function mettreAJourParametrePaie($parameters)
        {
            $manager = new ManagerParametrePaie();
            $parametrePaie = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if ($parametrePaie == null) {
                $parametrePaie = $manager->ajouter([
                    'idEntreprise'           => $_SESSION['user']['idEntreprise'],
                    'minimumDePerception'    => self::NOT_YET,
                    'chargeFamiliale'        => self::NOT_YET,
                    'salaireMinimumEmbauche' => self::NOT_YET,
                    'template'               => "aucun fichier"
                ]);
            }
            if (!empty($parameters['save'])) {
                $targetDir   = DOC_ROOT . "Ressources/fichiers/template/";
                $targetFile  = $targetDir . basename($_FILES["fileToUpload"]["name"]);
                $fileType    = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
                $isUploadOk  = true;
                if (file_exists($targetFile)) {
                    $i = 1;
                    $fileName = str_replace($targetDir, '', $targetFile);
                    $fileName = explode('.', $fileName);
                    $tmpTargetFile = $targetDir . '' . $fileName[0] . '' . $i . '.' . $fileName[1];
                    while (file_exists($tmpTargetFile)) {
                        $i++;
                        $fileName = str_replace($targetDir, '', $targetFile);
                        $fileName = explode('.', $fileName);
                        $tmpTargetFile = $targetDir . '' . $fileName[0] . '' . $i . '.' . $fileName[1];
                    }
                    $targetFile = $targetDir . '' . $fileName[0] . '' . $i . '.' . $fileName[1];
                }
                if (strtolower($fileType) != 'docx') {
                    $isUploadOk = false;
                }
                if ($isUploadOk == true) {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                        $fileName = $targetDir . '' . $parameters['fichier'];
                        if (file_exists($fileName)) {
                            unlink($fileName);
                        }
                        $manager->modifier([
                            'idParametrePaie' => $parametrePaie->getIdParametrePaie(),
                            'template'        => str_replace($targetDir, '', $targetFile)
                        ]);
                        $_SESSION['info']['success'] = "Modification terminée avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Erreur lors de l'upload du fichier";
                    }
                } else {
                    $_SESSION['info']['warning'] = "Seuls les fichiers .docx sont autorisés";
                }
            } elseif (!empty($parameters['download'])) {
                if (file_exists(DOC_ROOT . "Ressources/fichiers/template/" . $parametrePaie->getTemplate())) {
                    $phpDocx = new PhpDocx(DOC_ROOT . "Ressources/fichiers/template/" . $parametrePaie->getTemplate());
                    $phpDocx->download($parameters['fichier']);
                } else {
                    $_SESSION['info']['danger'] = "Fichier introuvable";
                }
            } else {
                if (!empty($parameters['minimumDePerception']) && !empty($parameters['modeIrsa']) && !empty($parameters['seuilImposition'])) {
                    $retour = $manager->modifier([
                        'idParametrePaie'     => $parametrePaie->getIdParametrePaie(),
                        'minimumDePerception' => $parameters['minimumDePerception'],
                        'modeIrsa'            => $parameters['modeIrsa'],
                        'seuilImposition'     => $parameters['seuilImposition']
                    ]);
                    if ($retour->getMinimumDePerception() == $parameters['minimumDePerception'] && $retour->getModeIrsa() == $parameters['modeIrsa'] && $retour->getSeuilImposition() == $parameters['seuilImposition']) {
                        $_SESSION['info']['success'] = "Modification terminée avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } elseif (!empty($parameters['salaireMinimumEmbauche'])) {
                    $retour = $manager->modifier([
                        'idParametrePaie' => $parametrePaie->getIdParametrePaie(),
                        'salaireMinimumEmbauche' => $parameters['salaireMinimumEmbauche']
                    ]);
                    if ($retour->getSalaireMinimumEmbauche() == $parameters['salaireMinimumEmbauche']) {
                        $_SESSION['info']['success'] = "Modification terminée avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } elseif (!empty($parameters['chargeFamiliale'])) {
                    $retour = $manager->modifier([
                        'idParametrePaie' => $parametrePaie->getIdParametrePaie(),
                        'chargeFamiliale' => $parameters['chargeFamiliale']
                    ]);
                    if ($retour->getChargeFamiliale() == $parameters['chargeFamiliale']) {
                        $_SESSION['info']['success'] = "Modification terminée avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } elseif (!empty($parameters['limiteAllocationConge'])) {
                    $retour = $manager->modifier([
                        'idParametrePaie'       => $parametrePaie->getIdParametrePaie(),
                        'limiteAllocationConge' => $parameters['limiteAllocationConge']
                    ]);
                    if ($retour->getLimiteAllocationConge() == $parameters['limiteAllocationConge']) {
                        $_SESSION['info']['success'] = "Modification terminée avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                } elseif (!empty($parameters['typeArrondissement'])) {
                    $retour = $manager->modifier([
                        'idParametrePaie'    => $parametrePaie->getIdParametrePaie(),
                        'typeArrondissement' => $parameters['typeArrondissement']
                    ]);
                    if ($retour->getTypeArrondissement() == $parameters['typeArrondissement']) {
                        $_SESSION['info']['success'] = "Modification terminée avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                }
            }
        }

        /**
         * Disséquer les heures d'un employé dans leur catégorie correspondante
         *
         * @param int $mois      le mois travaillé
         * @param int $annee     l'année concernée
         * @param int $idEmploye l'identifiant de l'employé
         *
         * @return array
         */
        private function explodeHeure($idEmploye, $mois, $annee)
        {
            $debut     = date($annee . '-' . $mois . '-01');
            $fin       = date("Y-m-d", mktime(0, 0, 0, ($mois + 1), 0, $annee));
            $manager   = new ManagerEmploye();
            $employe   = $manager->chercher(['idEmploye' => $idEmploye]);
            $manager   = new ManagerPresence();
            $presences = $manager->selectionner(
                ['idEmploye' => $employe->getIdEmploye()],
                ['date'      => $debut],
                ['date'      => $fin]
            );
            $heures['normal']         = 0;
            $heures['nuit']           = 0;
            $heures['dimanche']       = 0;
            $heures['ferie']          = 0;
            $heures['supplementaire'] = 0;
            $heures['total']          = 0;
            foreach ($presences as $presence) {
                $manager = new ManagerPointage();
                $pointages = $manager->lister([
                    'idPresence' => $presence->getIdPresence(),
                    'statut'     => self::POINTAGE_FERME
                ]);
                $jour = $this->getDayLetter(date("D", strtotime($presence->getDate())));
                if ($this->isFerie($presence->getDate())) {
                    $heures['ferie'] += $this->getHeurePointage($pointages)['jour'];
                    $heures['nuit']  += $this->getHeurePointage($pointages)['nuit'];
                    $heures['total'] += $this->getHeurePointage($pointages)['jour'] + $this->getHeurePointage($pointages)['nuit'];
                } elseif ($jour == self::SUNDAY) {
                    $heures['dimanche'] += $this->getHeurePointage($pointages)['jour'];
                    $heures['nuit']     += $this->getHeurePointage($pointages)['nuit'];
                    $heures['total']    += $this->getHeurePointage($pointages)['jour'] + $this->getHeurePointage($pointages)['nuit'];
                } else {
                    $heures['nuit']  += $this->getHeurePointage($pointages)['nuit'];
                    $heures['total'] += $this->getHeurePointage($pointages)['jour'] + $this->getHeurePointage($pointages)['nuit'];
                }
            }
            $manager                  = new ManagerParametreHeure();
            $parametreHeure           = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
            $salaireHoraire           = $this->getSalaireHoraire($employe->getIdEmploye());
            $tmp                      = $this->getDuree($heures['ferie']);
            $heures['ferie']          = round($tmp['hour'] + ($tmp['minute'] / self::ONE_MINUTE), 2);
            $heures['majorationFerie'] = $this->arrondir(($heures['ferie'] * $salaireHoraire * self::MAJORATION_FERIE), $this->typeArrondissement);
            $tmp                      = $this->getDuree($heures['nuit']);
            $heures['nuit']           = round($tmp['hour'] + ($tmp['minute'] / self::ONE_MINUTE), 2);
            if ($parametreHeure->getTravailNuitHabituel() == self::YES) {
                $heures['majorationNuit'] = $this->arrondir(($heures['nuit'] * $salaireHoraire * self::MAJORATION_NUIT_HABITUEL), $this->typeArrondissement);
            } else {
                $heures['majorationNuit'] = $this->arrondir(($heures['nuit'] * $salaireHoraire * self::MAJORATION_NUIT_OCCASIONNEL), $this->typeArrondissement);
            }
            $tmp                      = $this->getDuree($heures['dimanche']);
            $heures['dimanche']       = round($tmp['hour'] + ($tmp['minute'] / self::ONE_MINUTE), 2);
            if ($parametreHeure->getTravailDimancheHabituel() == self::YES) {
                $heures['majorationDimanche'] = $this->arrondir(($heures['dimanche'] * $salaireHoraire * self::MAJORATION_DIMANCHE_HABITUEL), $this->typeArrondissement);
            } else {
                $heures['majorationDimanche'] = $this->arrondir(($heures['dimanche'] * $salaireHoraire * self::MAJORATION_DIMANCHE_OCCASIONNEL), $this->typeArrondissement);
            }
            $tmp                      = $this->getDuree($heures['total']);
            $heures['total']          = round($tmp['hour'] + ($tmp['minute'] / self::ONE_MINUTE), 2);
            if ($parametreHeure->getHeureSupplementaireActive() == self::YES) {
                $heures['supplementaire'] = (($heures['total'] - $parametreHeure->getHeureNormale()) > 0) ? ($heures['total'] - $parametreHeure->getHeureNormale()) : 0;
                if ($heures['supplementaire'] > self::PREMIERES_HEURES_SUP) {
                    $heures['majorationSupplementaire'] = $this->arrondir((self::PREMIERES_HEURES_SUP * $salaireHoraire * self::MAJORATION_PREMIERES_HEURES_SUP), $this->typeArrondissement);
                    $heures['majorationSupplementaire'] += $this->arrondir((($heures['supplementaire'] - self::PREMIERES_HEURES_SUP) * $salaireHoraire * self::MAJORATION_RESTES_HEURES_SUP), $this->typeArrondissement);
                } else {
                    $heures['majorationSupplementaire'] = $this->arrondir(($heures['supplementaire'] * $salaireHoraire * self::MAJORATION_PREMIERES_HEURES_SUP), $this->typeArrondissement);
                }
            }
            $heures['normal'] = $heures['total'] - ($heures['ferie'] + $heures['nuit'] + $heures['dimanche']);
            return $heures;
        }

        /**
         * Calculer la majoration de travail de dimanche
         *
         * @param int   $idEmploye      l'identifiant du salarié
         * @param float $quantite       la quantite en heure
         * @param float $salaireHoraire le salaire horaire du salarié
         *
         * @return float
         */
        private function getMajorationHeureDimanche($idEmploye, $quantite, $salaireHoraire)
        {
            $manager         = new ManagerParametreHeure();
            $parametreHeure  = $manager->chercher(['idEmploye' => $idEmploye]);
            if ($parametreHeure->getTravailDimancheHabituel() == self::YES) {
                $majoration = $this->arrondir(($quantite * $salaireHoraire * self::MAJORATION_DIMANCHE_HABITUEL), $this->typeArrondissement);
            } else {
                $majoration = $this->arrondir(($quantite * $salaireHoraire * self::MAJORATION_DIMANCHE_OCCASIONNEL), $this->typeArrondissement);
            }
            return $majoration;
        }

        /**
         * Calculer la majoration de travail de nuit
         *
         * @param int   $idEmploye      l'identifiant du salarié
         * @param float $quantite       la quantite en heure
         * @param float $salaireHoraire le salaire horaire du salarié
         *
         * @return float
         */
        private function getMajorationHeureNuit($idEmploye, $quantite, $salaireHoraire)
        {
            $manager         = new ManagerParametreHeure();
            $parametreHeure  = $manager->chercher(['idEmploye' => $idEmploye]);
            if ($parametreHeure->getTravailNuitHabituel() == self::YES) {
                $majoration = $this->arrondir(($quantite * $salaireHoraire * self::MAJORATION_NUIT_HABITUEL), $this->typeArrondissement);
            } else {
                $majoration = $this->arrondir(($quantite * $salaireHoraire * self::MAJORATION_NUIT_OCCASIONNEL), $this->typeArrondissement);
            }
            return $majoration;
        }

        /**
         * Calculer la majoration de travail pendant un férié
         *
         * @param int   $idEmploye      l'identifiant du salarié
         * @param float $quantite       la quantité en heure
         * @param float $salaireHoraire le salaire horaire du salarié
         *
         * @return float
         */
        private function getMajorationHeureFerie($idEmploye, $quantite, $salaireHoraire)
        {
            $majoration = $this->arrondir(($quantite * $salaireHoraire * self::MAJORATION_FERIE), $this->typeArrondissement);
            return $majoration;
        }

        /**
         * Calculer la majoration de travail supplémentaire
         *
         * @param int   $idEmploye      l'identifiant du salarié
         * @param float $quantite       la quantité en heure
         * @param float $salaireHoraire le salaire horaire du salarié
         *
         * @return float
         */
        private function getMajorationHeureSupplementaire($idEmploye, $quantite, $salaireHoraire)
        {
            if ($quantite > self::PREMIERES_HEURES_SUP) {
                $majoration = $this->arrondir((self::PREMIERES_HEURES_SUP * $salaireHoraire * self::MAJORATION_PREMIERES_HEURES_SUP), $this->typeArrondissement);
                $majoration += $this->arrondir((($quantite - self::PREMIERES_HEURES_SUP) * $salaireHoraire * self::MAJORATION_RESTES_HEURES_SUP), $this->typeArrondissement);
            } else {
                $majoration = $this->arrondir(($quantite * $salaireHoraire * self::MAJORATION_PREMIERES_HEURES_SUP), $this->typeArrondissement);
            }
            return $majoration;
        }

        /**
         * Récupérer le salaire horaire d'un employé
         *
         * @param int $idEmploye l'identifiant de l'employé
         *
         * @return float
         */
        private function getSalaireHoraire($idEmploye)
        {
            $manager   = new ManagerEmploye();
            $employe   = $manager->chercher(['idEmploye' => $idEmploye]);
            $manager   = new ManagerParametreHeure();
            $parametreHeure = $manager->chercher(['idEmploye' => $idEmploye]);
            if ($parametreHeure->getHeureNormale() != self::NO) {
                return $this->arrondir($this->decrypter($employe->getSalaire()) / $parametreHeure->getHeureNormale(), $this->typeArrondissement);
            } else {
                $_SESSION['info']['warning'] = "L'heure hebdomadaire n'est pas définie !";
                return self::NO;
            }
        }

        /**
         * Calculer le SMM
         *
         * @param int $idEmploye l'identifiant de l'employe
         *
         * @return float
         */
        private function getSalaireMensuelMoyen($idEmploye)
        {
            $manager = new ManagerEmploye();
            $employe = $manager->chercher(['idEmploye' => $idEmploye]);
            $plage = self::UNE_ANNEE;
            $somme = 0;
            $currentDate = date(date('Y') . '-' . date('m') . '-01');
            $currentDate = date('Y-m-d', strtotime($currentDate . "- 1 Months"));
            $coefficient = 0;
            while ($plage > 0) {
                $mois = date('m', strtotime($currentDate));
                $annee = date('Y', strtotime($currentDate));
                $manager = new ManagerFichePaie();
                $fichePaie = $manager->chercher([
                    'idEmploye' => $idEmploye,
                    'mois'      => $mois,
                    'annee'     => $annee
                ]);
                if ($fichePaie != null) {
                    $fichePaie  = $this->decrypterFichePaie($fichePaie);
                    $somme += $fichePaie->getSalaireBrut();
                    $coefficient++;
                }
                $plage--;
                $currentDate = date('Y-m-d', strtotime($currentDate . "- 1 Months"));
            }
            if ($coefficient == 0) {
                return $this->arrondir($this->decrypter($employe->getSalaire()), $this->typeArrondissement);
            } else {
                return $this->arrondir(($somme / $coefficient), $this->typeArrondissement);
            }
        }

        /**
         * Calculer Allocation de congé
         *
         * @param int $idEmploye l'identifiant de l'employé
         * @param int $mois      le mois en question
         * @param int $annee     l'année du mois en question
         *
         * @return float
         */
        private function getAllocationConge($idEmploye, $mois, $annee)
        {
            $smm       = $this->getSalaireMensuelMoyen($idEmploye);
            $conge     = $this->getConges($idEmploye, $mois, $annee);
            $quantite  = $conge['quantiteAllocation'];
            return $this->arrondir($smm * $quantite / self::MOIS_SANS_WEEKEND, $this->typeArrondissement);
        }

        /**
         * @param int $idEmploye l'identifiant de l'employé
         * @param int $mois      le mois en question
         * @param int $annee     l'année du mois en question
         *
         * @return float
         */
        private function getIndemniteCompensatriceDeConge($idEmploye, $mois, $annee)
        {
            $smm         = $this->getSalaireMensuelMoyen($idEmploye);
            $manager     = new ManagerStockConge();
            $stockConge  = $manager->chercher(['idEmploye' => $idEmploye]);
            $manager     = new ManagerConge();
            $conges      = $manager->selectionner(
                ['idEmploye' => $idEmploye,
                 'statut'    => self::VALIDATED],
                ['debut'     => date('Y-m-d')],
                []
            );
            foreach ($conges as $conge) {
                $stockConge += $this->getJours($conge->getDebut(), $conge->getHeureDebut(), $conge->getFin(), $conge->getHeureFin());
            }
            return $this->arrondir($smm * $stockConge / self::MOIS_SANS_WEEKEND, $this->typeArrondissement);
        }

        /**
         * Séparer les heures de journée et de nuit
         *
         * @param array $pointages une liste de pointage
         *
         * @return array
         */
        private function getHeurePointage($pointages)
        {
            $heures = array();
            $heures['jour'] = 0;
            $heures['nuit'] = 0;
            foreach ($pointages as $pointage) {
                if (strtotime($pointage->getDebut()) >= strtotime(self::LIMITE_INFERIEURE_HEURE_NORMALE) && strtotime($pointage->getFin()) <= strtotime(self::LIMITE_SUPERIEURE_HEURE_NORMALE)) {
                    $heures['jour'] += abs(strtotime($pointage->getFin()) - strtotime($pointage->getDebut()));
                } elseif (strtotime(self::LIMITE_INFERIEURE_HEURE_NORMALE) >= strtotime($pointage->getDebut()) && strtotime(self::LIMITE_INFERIEURE_HEURE_NORMALE) <= strtotime($pointage->getFin())) {
                    $heures['nuit'] += abs(strtotime($pointage->getDebut()) - strtotime(self::LIMITE_INFERIEURE_HEURE_NORMALE));
                    $heures['jour'] += abs(strtotime($pointage->getFin()) - strtotime(self::LIMITE_INFERIEURE_HEURE_NORMALE));
                } elseif (strtotime(self::LIMITE_SUPERIEURE_HEURE_NORMALE) >= strtotime($pointage->getDebut()) && strtotime(self::LIMITE_SUPERIEURE_HEURE_NORMALE) <= strtotime($pointage->getFin())) {
                    $heures['jour'] += abs(strtotime($pointage->getDebut()) - strtotime(self::LIMITE_SUPERIEURE_HEURE_NORMALE));
                    $heures['nuit'] += abs(strtotime($pointage->getFin()) - strtotime(self::LIMITE_SUPERIEURE_HEURE_NORMALE));
                }
            }
            return $heures;
        }

        /**
         * Récupérer les congés d'un employé
         *
         * @param int $idEmploye l'identifiant de l'employé
         * @param int $mois      le mois en question
         * @param int $annee     l'année du mois en question
         *
         * @return array
         */
        private function getConges($idEmploye, $mois, $annee)
        {
            $debut = date($annee . '-' . $mois . '-01');
            if ($mois == date('m')) {
                $fin = date('Y-m-d');
            } else {
                $fin = date("Y-m-d", mktime(0, 0, 0, ($mois + 1), 0, $annee));
            }
            $manager    = new ManagerConge();
            $conges     = $manager->selectionner(
                ['idEmploye'  => $idEmploye,
                'statut'      => self::VALIDATED],
                ['debut'      => $debut],
                ['debut'      => $fin]
            );
            $congePris                      = $this->getJourCongeMensuel($conges);
            $quantiteAllocation             = $this->getQuantiteAllocation($conges);
            $manager                        = new ManagerStockConge();
            $soldeCongeFin                  = $manager->chercher(['idEmploye' => $idEmploye])->getDuree();
            $soldeCongeDebut                = $soldeCongeFin + $congePris;
            $resultat['congePris']          = $congePris;
            $resultat['quantiteAllocation'] = $quantiteAllocation;
            $resultat['soldeCongeDebut']    = $soldeCongeDebut;
            $resultat['soldeCongeFin']      = $soldeCongeFin;
            return $resultat;
        }

        /**
         * Calculer le salaire au prorata
         *
         * @param int $idEmploye l'identifiant de l'employé
         * @param int $mois      le mois en question
         * @param int $annee     l'année du mois en question
         *
         * @return float
         */
        private function getSalaireProrata($idEmploye, $mois, $annee)
        {
            $maxPresence = $this->getDureeJour(date('Y-m-d', strtotime(date($annee . '-' . $mois . '-01'))), date("Y-m-d", mktime(0, 0, 0, ($mois + 1), 0, $annee)));
            $presence    = $this->getPresence($idEmploye, $mois, $annee);
            $manager     = new ManagerEmploye();
            $employe     = $manager->chercher(['idEmploye' => $idEmploye]);
            return  $this->decrypter($employe->getSalaire()) ? $this->arrondir(($presence * $this->decrypter($employe->getSalaire())) / $maxPresence, $this->typeArrondissement) : 0;
        }

        /**
         * Prendre le nombre de jours pour le calcul au prorata des salaire
         *
         * @param int $idEmploye l'identifiant de l'employé
         * @param int $mois      le mois en question
         * @param int $annee     l'année du mois en question
         *
         * @return int
         */
        private function getPresence($idEmploye, $mois, $annee)
        {
            $embauche    = $this->getDateEmbauche($idEmploye);
            $debut       = date($annee . '-' . $mois . '-01');
            if (strtotime($embauche) > strtotime($debut)) {
                $debut = $embauche;
            }
            if ($mois == date('m') && $annee == date('Y')) {
                $fin = date('Y-m-d');
                $presence = $this->getDureeJour($debut, $fin);
            } else {
                $fin = date("Y-m-d", mktime(0, 0, 0, ($mois + 1), 0, $annee));
                $presence = $this->getDureeJour($debut, $fin);
            }
            $manager     = new ManagerEmploye();
            $employe     = $manager->chercher(['idEmploye' => $idEmploye]);
            $manager     = new ManagerParametrePaie();
            $parametre   = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
            $congeAlloue = $this->estEnConge($employe, $debut);
            $deductionPresence = 0;
            if ($congeAlloue != false && $congeAlloue->getDebut() != $debut) {
                if ($this->getJours($debut, self::MATIN, $congeAlloue->getFin(), $congeAlloue->getHeureFin()) >= $parametre->getLimiteAllocationConge()) {
                    $deductionPresence = $this->getJours($debut, self::MATIN, $congeAlloue->getFin(), $congeAlloue->getHeureFin());
                }
            }
            $conge       = $this->getConges($idEmploye, $mois, $annee);
            $presence    = $presence - $conge['quantiteAllocation'];
            $presence    = $presence - $deductionPresence;
            $presence    = ($presence >= 0) ? $presence : 0;
            return $presence;
        }

        /**
         * Vérifier si un employé a un congé à une date donnée
         *
         * @param object $employe  l'employé
         * @param date   $date     la date
         *
         * @return array|false
         */
        private function estEnConge($employe, $date)
        {
            $manager = new ManagerConge();
            $conges  = $manager->lister(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
            foreach ($conges as $conge) {
                if (strtotime($date) >= strtotime($conge->getDebut()) && strtotime($date) <= strtotime($conge->getFin())) {
                    return $conge;
                }
            }
            return false;
        }

        /**
         * Calculer le plafond CNAPS
         *
         * @return float
         */
        private function getPlafondCnaps()
        {
            $manager = new ManagerParametrePaie();
            $parametrePaie = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $resultat = ($parametrePaie->getSalaireMinimumEmbauche() * self::COEFFICIENT_PLAFOND_CNAPS) / self::CENT_POUR_CENT;
            return $resultat;
        }

        /**
         * Calculer la cotisation CNAPS
         *
         * @param int $idEmloye l'identifiant de l'employé
         * @param int $mois     le mois en question
         * @param int $annee    l'année du mois en question
         *
         * @return float
         */
        private function getCotisationCnaps($idEmploye, $mois, $annee)
        {
            $plafond = $this->getPlafondCnaps();
            $salaireBrut = $this->getSalaireBrut($idEmploye, $mois, $annee);
            if (($salaireBrut / self::CENT_POUR_CENT) > $plafond) {
                return $this->arrondir($plafond, $this->typeArrondissement);
            } else {
                return $this->arrondir($salaireBrut / self::CENT_POUR_CENT, $this->typeArrondissement);
            }
        }

        /**
         * Calculer le plafond OSTIE
         *
         * @return float
         */
        private function getPlafondOstie()
        {
            $manager = new ManagerParametrePaie();
            $parametrePaie = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $resultat = ($parametrePaie->getSalaireMinimumEmbauche() * self::COEFFICIENT_PLAFOND_OSTIE) / self::CENT_POUR_CENT;
            return $resultat;
        }

        /**
         * Calculer la cotisation OSTIE
         *
         * @param int $idEmloye l'identifiant de l'employé
         * @param int $mois     le mois en question
         * @param int $annee    l'année du mois en question
         *
         * @return float
         */
        private function getCotisationOstie($idEmploye, $mois, $annee)
        {
            $plafond = $this->getPlafondOstie();
            $salaireBrut = $this->getSalaireBrut($idEmploye, $mois, $annee);
            if (($salaireBrut / self::CENT_POUR_CENT) > $plafond) {
                return $this->arrondir($plafond, $this->typeArrondissement);
            } else {
                return $this->arrondir($salaireBrut / self::CENT_POUR_CENT, $this->typeArrondissement);
            }
        }

        /**
         * Calculer le salaire brut d'un employé pendant un mois de donné
         *
         * @param int $idEmloye l'identifiant de l'employé
         * @param int $mois     le mois en question
         * @param int $annee    l'année du mois en question
         *
         * @return float
         */
        private function getSalaireBrut($idEmploye, $mois, $annee)
        {
            $manager = new ManagerEmploye();
            $employe = $manager->chercher(['idEmploye' => $idEmploye]);
            $manager = new ManagerAvantageEmploye();
            $avantageEmployes = $manager->lister([
                'idEmploye' => $idEmploye,
                'mois'      => $mois,
                'annee'     => $annee
            ]);
            $manager = new ManagerParametreHeure();
            $parametreHeure = $manager->chercher(['idEmploye' => $idEmploye]);
            $heures = $this->explodeHeure($idEmploye, $mois, $annee);
            $salaireBrut = $this->getSalaireProrata($idEmploye, $mois, $annee);
            $salaireBrut += $this->getAllocationConge($idEmploye, $mois, $annee);
            if ($parametreHeure->getHeureSupplementaireImposable() == self::YES) {
                $salaireBrut += $heures['majorationSupplementaire'];
            }
            if ($parametreHeure->getHeureNuitImposable() == self::YES) {
                $salaireBrut += $heures['majorationNuit'];
            }
            if ($parametreHeure->getHeureFerieImposable() == self::YES) {
                $salaireBrut += $heures['majorationFerie'];
            }
            if ($parametreHeure->getHeureDimancheImposable() == self::YES) {
                $salaireBrut += $heures['majorationDimanche'];
            }
            foreach ($avantageEmployes as $avantage) {
                if ($avantage->getImposable() == self::YES) {
                    $salaireBrut += ($avantage->getMontant() * $avantage->getRatioImposable()) / self::CENT_POUR_CENT;
                }
            }
            return $this->arrondir($salaireBrut, $this->typeArrondissement);
        }

        /**
         * Calculer le revenu imposable
         *
         * @param int $idEmloye l'identifiant de l'employé
         * @param int $mois     le mois en question
         * @param int $annee    l'année du mois en question
         *
         * @return float
         */
        private function getRevenuImposable($idEmploye, $mois, $annee)
        {
            $salaireBrut = $this->getSalaireBrut($idEmploye, $mois, $annee);
            $cnaps       = $this->getCotisationCnaps($idEmploye, $mois, $annee);
            $ostie       = $this->getCotisationOstie($idEmploye, $mois, $annee);
            return $salaireBrut - ($cnaps + $ostie);
        }

        /**
         * Calculer l'IRSA
         *
         * @param int $idEmloye l'identifiant de l'employé
         * @param int $mois     le mois en question
         * @param int $annee    l'année du mois en question
         *
         * @return float
         */
        private function getIrsa($idEmploye, $mois, $annee)
        {
            $manager = new ManagerParametrePaie();
            $parametrePaie = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $revenuImposable = $this->getRevenuImposable($idEmploye, $mois, $annee);
            if ($parametrePaie->getModeIrsa() == self::NEW_MODE_IRSA) {
                return $this->calculerIrsaV2($revenuImposable);
            } elseif ($parametrePaie->getModeIrsa() == self::OLD_MODE_IRSA) {
                return $this->calculerIrsaV1($resteImposable);
            } else {
                return 0;
            }
        }

        /**
         * Ancien calcul de l'irsa
         *
         * @param int $idEmloye l'identifiant de l'employé
         * @param int $mois     le mois en question
         * @param int $annee    l'année du mois en question
         *
         * @return float
         */
        protected function calculerIrsaV1($revenuImposable)
        {
            $manager = new ManagerParametrePaie();
            $parametrePaie = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $irsa = (($revenuImposable - $parametrePaie->getSeuilImposition()) * self::POURCENTAGE_IRSA) / self::CENT_POUR_CENT;
            if ($irsa > $parametrePaie->getMinimumDePerception()) {
                return $this->arrondir($irsa, $this->typeArrondissement);
            } else {
                return $parametrePaie->getMinimumDePerception();
            }
        }

        /**
         * Nouveau calcul de l'irsa
         *
         * @param int $idEmloye l'identifiant de l'employé
         * @param int $mois     le mois en question
         * @param int $annee    l'année du mois en question
         *
         * @return float
         */
        protected function calculerIrsaV2($revenuImposable)
        {
            $manager         = new ManagerParametrePaie();
            $parametrePaie   = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $resteImposable  = $revenuImposable;
            $irsa            = 0;
            if ($resteImposable > 600000) {
                $imposable       = $resteImposable - 600000;
                $irsa           += $this->arrondir(($imposable * 20) / 100, $this->typeArrondissement);
                $resteImposable -= $imposable;
            }
            if ($resteImposable > 500000) {
                $imposable       = $resteImposable - 500000;
                $irsa           += $this->arrondir(($imposable * 15) / 100, $this->typeArrondissement);
                $resteImposable -= $imposable;
            }
            if ($resteImposable > 400000) {
                $imposable       = $resteImposable - 400000;
                $irsa           += $this->arrondir(($imposable * 10) / 100, $this->typeArrondissement);
                $resteImposable -= $imposable;
            }
            if ($resteImposable > 350000) {
                $imposable       = $resteImposable - 350000;
                $irsa           += $this->arrondir(($imposable * 5) / 100, $this->typeArrondissement);
                $resteImposable -= $imposable;
            }
            if ($resteImposable > 0 && $irsa < $parametrePaie->getMinimumDePerception()) {
                $irsa           = $this->arrondir($parametrePaie->getMinimumDePerception(), $this->typeArrondissement);
            }
            return $this->arrondir($irsa, $this->typeArrondissement);
        }

        /**
         * Calculer la charge d'un employé
         *
         * @param int $idEmploye l'identifiant de l'employé en question
         *
         * @return float
         */
        private function getCharge($idEmploye)
        {
            $manager = new ManagerParametrePaie();
            $parametrePaie = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager = new ManagerEmploye();
            $employe = $manager->chercher(['idEmploye' => $idEmploye]);
            return $this->arrondir($employe->getNombreEnfants() * $parametrePaie->getChargeFamiliale(), $this->typeArrondissement);
        }

        /**
         * Calculer l'IRSA Net
         *
         * @param int $idEmloye l'identifiant de l'employé
         * @param int $mois     le mois en question
         * @param int $annee    l'année du mois en question
         *
         * @return float
         */
        private function getIrsaNet($idEmploye, $mois, $annee)
        {
            $manager = new ManagerParametrePaie();
            $parametrePaie = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $irsaNet = $this->getIrsa($idEmploye, $mois, $annee) - $this->getCharge($idEmploye);
            if ($irsaNet > $parametrePaie->getMinimumDePerception()) {
                return $this->arrondir($irsaNet, $this->typeArrondissement);
            } else {
                return $parametrePaie->getMinimumDePerception();
            }
        }

        /**
         * Calculer plafond avance spéciale
         *
         * @param int $idEmploye  l'identifiant de l'employé
         *
         * @return float
         */
        private function getPlafondAvanceSpeciale($idEmploye)
        {
            $manager       = new ManagerParametreAvance();
            $parametre     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager       = new ManagerEmploye();
            $employe       = $manager->chercher(['idEmploye' => $idEmploye]);
            $employe->setSalaire($this->decrypter($employe->getSalaire()));
            $tauxMensuel   = ($employe->getSalaire() * $parametre->getTauxMax()) / self::CENT_POUR_CENT;
            $plafondAvance = $tauxMensuel * $parametre->getDureeMax();
            return $plafondAvance;
        }

        /**
         * Recalculer le planning de remboursement
         *
         * @param int $idAvance  l'identifiant de l'avance
         *
         * @return empty
         */
        private function resetPlanningRemboursement($idAvance)
        {
            $mois           = date('m');
            $annee          = date('Y');
            $manager        = new ManagerAvance();
            $avance         = $manager->chercher(['idAvance' => $idAvance]);
            $manager        = new ManagerRemboursement();
            $remboursements = $manager->lister(['idAvance' => $idAvance]);
            $manager        = new ManagerParametreAvance();
            $parametre      = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager        = new ManagerEmploye();
            $employe        = $manager->chercher(['idEmploye' => $avance->getIdEmploye()]);
            $employe->setSalaire($this->decrypter($employe->getSalaire()));
            $tauxMensuel    = ($employe->getSalaire() * $parametre->getTauxMax()) / self::CENT_POUR_CENT;
            $montant        = $avance->getMontant();
            foreach ($remboursements as $remboursement) {
                if ($remboursement->getStatut() == self::YES || ($remboursement->getMois() == date('m') && $remboursement->getAnnee() == date('Y'))) {
                    $montant -= $remboursement->getMontant();
                } else {
                    $manager       = new ManagerRemboursement();
                    $manager->supprimer([
                        'idRemboursement' => $remboursement->getIdRemboursement()
                    ]);
                    $manager       = new ManagerDeduction();
                    $manager->supprimer([
                        'idDeduction' => $remboursement->getIdDeduction()
                    ]);
                }
            }
            $mois++;
            if ($mois > 12) {
                $mois = 1;
                $annee += 1;
            }
            $tauxMensuel = ($tauxMensuel > $montant) ? $montant : $tauxMensuel;
            while ($montant > self::NO) {
                $manager        = new ManagerDeduction();
                $deduction      = $manager->ajouter([
                    'idEmploye' => $employe->getIdEmploye(),
                    'libelle'   => "Avance spéciale",
                    'montant'   => $tauxMensuel,
                    'mois'      => $mois,
                    'annee'     => $annee

                ]);
                $manager       = new ManagerRemboursement();
                $remboursement = $manager->ajouter([
                    'idAvance'    => $avance->getIdAvance(),
                    'montant'     => $tauxMensuel,
                    'statut'      => self::NO,
                    'mois'        => $mois,
                    'annee'       => $annee,
                    'idDeduction' => $deduction->getIdDeduction()
                ]);
                $montant -= $tauxMensuel;
                $tauxMensuel = ($tauxMensuel > $montant) ? $montant : $tauxMensuel;
                $mois++;
                if ($mois > 12) {
                    $mois = 1;
                    $annee += 1;
                }
            }
        }

        /**
         * Prédire la durée de mois de remboursement
         *
         * @param int $idAvance  l'identifiant de l'avance
         *
         * @return int
         */
        private function predireMoisRemboursement($idAvance)
        {
            $manager       = new ManagerAvance();
            $avance        = $manager->chercher(['idAvance' => $idAvance]);
            $manager       = new ManagerEmploye();
            $employe       = $manager->chercher(['idEmploye' => $avance->getIdEmploye()]);
            $manager       = new ManagerParametreAvance();
            $parametre     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $employe->setSalaire($this->decrypter($employe->getSalaire()));
            $tauxMensuel   = ($employe->getSalaire() * $parametre->getTauxMax()) / self::CENT_POUR_CENT;
            $duree         = 0;
            $montant       = $avance->getMontant();
            while ($montant > self::NO) {
                $duree += self::ONE_MONTH;
                $montant -= $tauxMensuel;
            }
            return $duree;
        }

        /**
         * Etablir le planning de remboursement
         *
         * @param int $idAvance l'identifiant de l'avance
         * @param int $mois     le mois de début de remboursement
         * @param int $annee    l'année d mois en question
         *
         * @return empty
         */
        private function setPlanningRemboursement($idAvance, $mois, $annee)
        {
            $manager       = new ManagerAvance();
            $avance        = $manager->chercher(['idAvance' => $idAvance]);
            $manager       = new ManagerParametreAvance();
            $parametre     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager       = new ManagerEmploye();
            $employe       = $manager->chercher(['idEmploye' => $avance->getIdEmploye()]);
            $employe->setSalaire($this->decrypter($employe->getSalaire()));
            $tauxMensuel   = ($employe->getSalaire() * $parametre->getTauxMax()) / self::CENT_POUR_CENT;
            $montant       = $avance->getMontant();
            $tauxMensuel   = ($tauxMensuel > $montant) ? $montant : $tauxMensuel;
            while ($montant > self::NO) {
                $manager       = new ManagerAvanceQuinzaine();
                $quinzaines    = $manager->selectionner(
                    ['idEmploye' => $employe->getIdEmploye(),
                    'statut'     => self::VALIDATED],
                    ['date'      => date($annee . '-' . $mois . '-01')],
                    ['date'      => date("Y-m-d", mktime(0, 0, 0, ($mois + 1), 0, $annee))]
                );
                $deductionQuinzaine = 0;
                if (count($quinzaines) > self::NO) {
                    $deductionQuinzaine = $quinzaines[0]->getMontant();
                }
                $manager        = new ManagerDeduction();
                $deduction      = $manager->ajouter([
                    'idEmploye' => $employe->getIdEmploye(),
                    'libelle'   => "Avance spéciale",
                    'montant'   => (($tauxMensuel - $deductionQuinzaine) >= 0) ? ($tauxMensuel - $deductionQuinzaine) : 0,
                    'mois'      => $mois,
                    'annee'     => $annee
                ]);
                $manager       = new ManagerRemboursement();
                $remboursement = $manager->ajouter([
                    'idAvance'    => $avance->getIdAvance(),
                    'montant'     => (($tauxMensuel - $deductionQuinzaine) >= 0) ? ($tauxMensuel - $deductionQuinzaine) : 0,
                    'statut'      => self::NO,
                    'mois'        => $mois,
                    'annee'       => $annee,
                    'idDeduction' => $deduction->getIdDeduction()
                ]);
                $montant -= (($tauxMensuel - $deductionQuinzaine) >= 0) ? ($tauxMensuel - $deductionQuinzaine) : 0;
                $tauxMensuel = ($tauxMensuel > $montant) ? $montant : $tauxMensuel;
                $mois++;
                if ($mois > 12) {
                    $mois = 1;
                    $annee += 1;
                }
            }
        }

        /**
         * Calculer le salaire net
         *
         * @param int $idEmloye l'identifiant de l'employé
         * @param int $mois     le mois en question
         * @param int $annee    l'année du mois en question
         *
         * @return float
         */
        private function getSalaireNet($idEmploye, $mois, $annee)
        {
            $salaireNet       = $this->getRevenuImposable($idEmploye, $mois, $annee) - $this->getIrsaNet($idEmploye, $mois, $annee);
            $manager          = new ManagerParametreHeure();
            $parametreHeure   = $manager->chercher(['idEmploye' => $idEmploye]);
            $manager          = new ManagerAvantageEmploye();
            $avantageEmployes = $manager->lister([
                'idEmploye' => $idEmploye,
                'mois'      => $mois,
                'annee'     => $annee
            ]);
            $manager          = new ManagerDeduction();
            $deductions       = $manager->lister([
                'idEmploye' => $idEmploye,
                'mois'      => $mois,
                'annee'     => $annee
            ]);
            $heures           = $this->explodeHeure($idEmploye, $mois, $annee);
            if ($parametreHeure->getHeureSupplementaireImposable() == self::NO) {
                $salaireNet += $heures['majorationSupplementaire'];
            }
            if ($parametreHeure->getHeureNuitImposable() == self::NO) {
                $salaireNet += $heures['majorationNuit'];
            }
            if ($parametreHeure->getHeureFerieImposable() == self::NO) {
                $salaireNet += $heures['majorationFerie'];
            }
            if ($parametreHeure->getHeureDimancheImposable() == self::NO) {
                $salaireNet += $heures['majorationDimanche'];
            }
            foreach ($avantageEmployes as $avantage) {
                if ($avantage->getImposable() == self::NO) {
                    $salaireNet += $avantage->getMontant();
                }
            }
            foreach ($deductions as $deduction) {
                $salaireNet -= $deduction->getMontant();
            }
            return $this->arrondir($salaireNet, $this->typeArrondissement);
        }


        /**
         * Récupérer heure et minute à partir d'une durée en seconde
         *
         * @param int $seconde durée en seconde
         *
         * @return array
         */
        private function getDuree($seconde)
        {
            $tmp              = $seconde;
            $retour['time']   = floor($seconde / 1);
            $retour['second'] = $tmp % 60;
            $tmp              = floor(($tmp - $retour['second']) / 60);
            $retour['minute'] = $tmp % 60;
            $tmp              = floor(($tmp - $retour['minute']) / 60);
            $retour['hour']   = $tmp;
            return $retour;
        }

        /**
         * Vérifier si une date est un jour férié
         *
         * @param date $date la date en question
         *
         * @return bool
         */
        private function isFerie($date)
        {
            $manager         = new ManagerEntrepriseFerie();
            $entrepriseFerie = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise'], 'date' => $date]);
            if ($entrepriseFerie != null) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Calculer les heures mensuelles
         *
         * @param float $heureHebdomadaire les heures hebdomadaires
         *
         * @return float
         */
        private function getHeureMensuelle($heureHebdomadaire)
        {
            return number_format(($heureHebdomadaire * self::NOMBRE_SEMAINES) / self::NOMBRE_MOIS, 2);
        }

        /**
         * Calculer les heures hebdomaires
         *
         * @param float $heureMensuelle les heures mensuelles
         *
         * @return int
         */
        private function getHeureHebdomadaire($heureMensuelle)
        {
            return ceil(($heureMensuelle * self::NOMBRE_MOIS) / self::NOMBRE_SEMAINES);
        }

        /**
         * Récupérer les données pour le filtre
         *
         * @param int $idEntreprise l'identifiant de l'entreprise
         *
         * @return array
         */
        private function getFiltre($idEntreprise)
        {
            $manager               = new ManagerEntreprise();
            $entreprise            = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager               = new ManagerEntreprisePoste();
            $postes                = $manager->lister([
                'idEntreprise'     => $entreprise->getIdEntreprise(),
                'statut'           => self::POSTE_INTERNE
            ]);
            $manager               = new ManagerEntrepriseService();
            $services              = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $manager               = new ManagerEmploye();
            $employes              = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $manager               = new ManagerCategorieProfessionnelle();
            $categories            = $manager->lister();
            $manager               = new ManagerContrat();
            $contrats              = $manager->lister(['offreUniquement' => self::NO]);
            $filtres               = array();
            $filtres['services']   = $services;
            $filtres['postes']     = $postes;
            $filtres['employes']   = $employes;
            $filtres['contrats']   = $contrats;
            $filtres['categories'] = $categories;
            return $filtres;
        }

        /**
         * Obtenir la date d'embauche d'un employé
         *
         * @param int $idEmploye l'identfiant de l'employé
         *
         * @return date
         */
        private function getDateEmbauche($idEmploye)
        {
            $manager          = new ManagerContratEmploye();
            $contratEmploye   = $manager->chercher(['idEmploye' => $idEmploye, 'statut' => self::VALIDATED]);
            if ($contratEmploye == null) {
                $contratEmployes = $manager->lister(['idEmploye' => $idEmploye, 'statut' => self::EXPIRED]);
                $contratEmploye  = isset($contratEmployes[0]) ? $contratEmployes[0] : null;
            }
            if ($contratEmploye != null) {
                $manager          = new ManagerContrat();
                $typeContrat      = $manager->chercher(['idContrat' => $contratEmploye->getType()]);
                if (strtolower($typeContrat->getDesignation()) == strtolower(self::STAGE)) {
                    return $contratEmploye->getDateDebut();
                } else {
                    while ($contratEmploye->getPrecedent() != self::NO) {
                        $manager          = new ManagerContratEmploye();
                        $tmp              = $manager->chercher(['idContratEmploye' => $contratEmploye->getPrecedent()]);
                        $manager          = new ManagerContrat();
                        $typeContrat      = $manager->chercher(['idContrat' => $tmp->getType()]);
                        if (strtolower($typeContrat->getDesignation()) == strtolower(self::STAGE)) {
                            break;
                        } else {
                            $contratEmploye = $tmp;
                        }
                    }
                    return $contratEmploye->getDateDebut();
                }
            } else {
                return null;
            }
        }

        /**
         * Retourner le poste d'un employé
         *
         * @param object $employe
         *
         * @return object
         */
        private function getPosteEmploye($employe)
        {
            $manager  = new ManagerContratEmploye();
            $contrat  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
            if ($contrat != null) {
                $manager  = new ManagerServicePoste();
                $servicePoste = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                $manager      = new ManagerEntreprisePoste();
                $poste = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                return $poste;
            }  else {
                return false;
            }
        }

        /**
         * Retourner le service d'un employé
         *
         * @param object $employe
         *
         * @return object
         */
        private function getServiceEmploye($employe)
        {
            $manager  = new ManagerContratEmploye();
            $contrat  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
            if ($contrat != null) {
                $manager  = new ManagerServicePoste();
                $servicePoste = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                $manager      = new ManagerEntrepriseService();
                $service = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                return $service;
            } else {
                return false;
            }
        }

        /**
         * Retourner le type de contrat d'un employé
         *
         * @param object $employe
         *
         * @return object
         */
        private function getTypeContratEmploye($employe)
        {
            $manager  = new ManagerContratEmploye();
            $contrat  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
            if ($contrat != null) {
                $manager  = new ManagerContrat();
                $type     = $manager->chercher(['idContrat' => $contrat->getType()]);
                return $type;
            } else {
                return null;
            }
        }

        /**
         * Retourner la catégorie professionnelle d'un employé
         *
         * @param object $employe
         *
         * @return object
         */
        private function getCategorieEmploye($employe)
        {
            $manager  = new ManagerContratEmploye();
            $contrat  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
            if ($contrat != null) {
                $manager  = new ManagerServicePoste();
                $servicePoste = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                $manager      = new ManagerCategorieProfessionnelle();
                $categorie    = $manager->chercher(['idCategorieProfessionnelle' => $servicePoste->getIdCategorieProfessionnelle()]);
                return $categorie;
            }  else {
                return false;
            }
        }

        /**
         * Envoyer un message de notification à un utilisateur
         *
         * @param int    $idCompte l'identifiant de l'utilisateur
         * @param string $objet    l'objet du message
         * @param string $contenu  le contenu du message
         *
         * @return int
         */
        private function sendMessageNotification($idCompte, $objet, $contenu)
        {
            $today    = date('Y-m-d');
            $manager  = new ManagerMessage();
            $message  = $manager->ajouter([
                'idCompte' => $idCompte,
                'objet'    => $objet,
                'contenu'  => $contenu,
                'date'     => $today,
                'statut'   => self::PROPOSED
            ]);
            return $message->getIdMessage();
        }

        /**
         * Générer un contenu de message de notification
         *
         * @param string  $type   le type de notification
         * @param object  $object l'objet en question
         *
         * @return string
         */
        private function generateMessageContent($type, $object)
        {
            $avance = $object;
            $manager  = new ManagerEmploye();
            $demandeur = $manager->chercher(['idEmploye' => $avance->getIdEmploye()]);
            $content = "<p>Bonjour, </p>";
            $classe  = get_class($object);
            $lien    = explode("\\", $classe)[1];
            if ($type == self::TYPE_REQUEST) {
                $content .= "<p>" .
                            "Vous avez une <a href='" . HOST . "manage/entreprise/demande" . $lien . "'>validation de demande d'avance</a> à effectuer de la part de <span class='titre'>" . $demandeur->getNom() . " " . $demandeur->getPrenom() . "</span> ." .
                            "</p>";
            } elseif ($type == self::TYPE_VALIDATED) {
                $content .= "<p>" .
                            "Votre <a href='" . HOST . "manage/employe/demande" . $lien . "'>demande d'avance</a> " .
                            "envoyée le " . date('d/m/Y', strtotime($avance->getDate())) . " a été validée" .
                            "</p>";
            } elseif ($type == self::TYPE_REJECTED) {
                $content .= "<p>" .
                            "Votre <a href='" . HOST . "manage/employe/demande" . $lien . "'>demande d'avance</a> " .
                            "envoyée le " . date('d/m/Y', strtotime($avance->getDate())) . " a été refusée " .
                            "en raison de <span class='titre'>'" . $avance->getMotifRefus() ."'<span class='titre'>" .
                            "</p>";
            } elseif ($type == self::TYPE_CANCELED) {
                if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                    $content .= "<p>" .
                            "Nous vous informons que " .
                            $demandeur->getCivilite() . " " . $demandeur->getNom() . " " . $demandeur->getPrenom() .
                            " a retiré sa <a href='" . HOST . "manage/entreprise/demande" . $lien . "'>demande d'avance</a> " .
                            "envoyée le " . date('d/m/Y', strtotime($avance->getDate())) .
                            "</p>";
                } elseif ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                    $content .= "<p>" .
                            "Nous vous informons que" .
                            " votre <a href='" . HOST . "manage/employe/demande" . $lien . "'>demande d'avance</a> " .
                            "envoyée le " . date('d/m/Y', strtotime($avance->getDate())) . " a été retirée" .
                            "</p>";
                }
            }
            return $content;
        }

        /**
         * Contrôler les requêtes GET
         *
         * @param int $idEntreprise l'identifiant de l'entreprise
         * @param int $idEmploye l'identifiant de l'employé
         *
         * @return bool
         */
        private function isAllowed($idEntreprise, $idEmploye)
        {
            if ($_SESSION['compte']['identifiant'] == "entreprise") {
                $manager = new ManagerEmploye();
                $employe = $manager->chercher(['idEntreprise' => $idEntreprise, 'idEmploye' => $idEmploye]);
                if ($employe != null) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($_SESSION['compte']['identifiant'] == "employe") {
                if ($idEmploye == $_SESSION['user']['idEmploye']) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        /**
         * Redirection si une requête GET n'est pas autorisée
         *
         * @return empty
         */
        private function rediriger()
        {
            if ($_SESSION['compte']['identifiant'] == "entreprise") {
                header("Location:" . HOST . "manage/entreprise/dashboard");
            } elseif ($_SESSION['compte']['identifiant'] == "employe") {
                header("Location:" . HOST . "manage/employe/dashboard");
            }
        }

        /**
         * Convertir un jour en jour entier en français
         *
         * @param string $day le jour en 3 lettres et en anglais
         *
         * @return string
         */
        private function getDayLetter($day)
        {
            $days['Mon'] = self::MONDAY;
            $days['Tue'] = self::TUESDAY;
            $days['Wed'] = self::WEDNESDAY;
            $days['Thu'] = self::THURSDAY;
            $days['Fri'] = self::FRIDAY;
            $days['Sat'] = self::SATURDAY;
            $days['Sun'] = self::SUNDAY;
            return $days[$day];
        }

        /**
         * Convertir un entier en mois
         *
         * @param int $month
         *
         * @return string
         */
        public static function getMonthLetter($month)
        {
            $month  = intval($month);
            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            return $months[$month - 1];
        }

        /**
         * Calculer la différence entre 2 dates
         *
         * @param date $date1 date1
         * @param date $date2 date2
         *
         * @return int nombre de jours
         */
        private function getDureeJour($date1, $date2)
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
            return intval($retour['day'] + 1);
        }

        /**
         * Crypter la Fiche de Paie
         *
         * @param object $fichePaie la fiche de paie à crypter
         *
         * @return $object
         */
        private function crypterFichePaie($fichePaie)
        {
            $fichePaie->setSalaireDeBase($this->crypter($fichePaie->getSalaireDeBase()));
            $fichePaie->setMajorationHeureSupplementaire($this->crypter($fichePaie->getMajorationHeureSupplementaire()));
            $fichePaie->setMajorationHeureNuit($this->crypter($fichePaie->getMajorationHeureNuit()));
            $fichePaie->setMajorationHeureDimanche($this->crypter($fichePaie->getMajorationHeureDimanche()));
            $fichePaie->setMajorationHeureFerie($this->crypter($fichePaie->getMajorationHeureFerie()));
            $fichePaie->setSalaire($this->crypter($fichePaie->getSalaire()));
            $fichePaie->setSalaireBrut($this->crypter($fichePaie->getSalaireBrut()));
            $fichePaie->setSalaireNet($this->crypter($fichePaie->getSalaireNet()));
            $fichePaie->setDeductionCnaps($this->crypter($fichePaie->getDeductionCnaps()));
            $fichePaie->setDeductionOstie($this->crypter($fichePaie->getDeductionOstie()));
            $fichePaie->setDeductionCharge($this->crypter($fichePaie->getDeductionCharge()));
            $fichePaie->setRevenuImposable($this->crypter($fichePaie->getRevenuImposable()));
            $fichePaie->setAllocationConge($this->crypter($fichePaie->getAllocationConge()));
            $fichePaie->setIrsa($this->crypter($fichePaie->getIrsa()));
            $fichePaie->setIrsaNet($this->crypter($fichePaie->getIrsaNet()));
            return $fichePaie;
        }

        /**
         * Dérypter la Fiche de Paie
         *
         * @param object $fichePaie la fiche de paie à décrypter
         *
         * @return $object
         */
        private function decrypterFichePaie($fichePaie)
        {
            $fichePaie->setSalaireDeBase(floatval($this->decrypter($fichePaie->getSalaireDeBase())));
            $fichePaie->setMajorationHeureSupplementaire(floatval($this->decrypter($fichePaie->getMajorationHeureSupplementaire())));
            $fichePaie->setMajorationHeureNuit(floatval($this->decrypter($fichePaie->getMajorationHeureNuit())));
            $fichePaie->setMajorationHeureDimanche(floatval($this->decrypter($fichePaie->getMajorationHeureDimanche())));
            $fichePaie->setMajorationHeureFerie(floatval($this->decrypter($fichePaie->getMajorationHeureFerie())));
            $fichePaie->setSalaire(floatval($this->decrypter($fichePaie->getSalaire())));
            $fichePaie->setSalaireBrut(floatval($this->decrypter($fichePaie->getSalaireBrut())));
            $fichePaie->setSalaireNet(floatval($this->decrypter($fichePaie->getSalaireNet())));
            $fichePaie->setDeductionCnaps(floatval($this->decrypter($fichePaie->getDeductionCnaps())));
            $fichePaie->setDeductionOstie(floatval($this->decrypter($fichePaie->getDeductionOstie())));
            $fichePaie->setDeductionCharge(floatval($this->decrypter($fichePaie->getDeductionCharge())));
            $fichePaie->setRevenuImposable(floatval($this->decrypter($fichePaie->getRevenuImposable())));
            $fichePaie->setAllocationConge(floatval($this->decrypter($fichePaie->getAllocationConge())));
            $fichePaie->setIrsa(floatval($this->decrypter($fichePaie->getIrsa())));
            $fichePaie->setIrsaNet(floatval($this->decrypter($fichePaie->getIrsaNet())));
            return $fichePaie;
        }

        /**
         * Crypter des informations
         *
         * @param string $mot le mot à crypter
         *
         * @return string
         */
        private function crypter($mot)
        {
            if ($_SESSION['compte']['identifiant'] == "entreprise") {
                $cle = KEY . '' . $_SESSION['compte']['idCompte'];
            } elseif ($_SESSION['compte']['identifiant'] == "employe") {
                $manager = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager = new ManagerCompte();
                $compte = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                $cle = KEY . '' . $compte->getIdCompte();
            } else {
                return $mot;
            }
            return openssl_encrypt($mot, "AES-128-ECB" , $cle);
        }

        /**
         * Décrypter des informations
         *
         * @param string $mot le mot à crypter
         *
         * @return string
         */
        private function decrypter($mot)
        {
            if ($_SESSION['compte']['identifiant'] == "entreprise") {
                $cle = KEY . '' . $_SESSION['compte']['idCompte'];
            } elseif ($_SESSION['compte']['identifiant'] == "employe") {
                $manager = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager = new ManagerCompte();
                $compte = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                $cle = KEY . '' . $compte->getIdCompte();
            } else {
                return $mot;
            }
            return openssl_decrypt($mot, "AES-128-ECB" , $cle);
        }

        /**
         * Ecrire un chiffre passé en paramètre en lettre
         *
         * @param float $chiffre le chiffre
         *
         * @return string
         */
        private function ecrireEnLettre($chiffre)
        {
            $convert = explode('.', $chiffre);
            if (isset($convert[1]) && $convert[1] != '') {
                return $this->ecrireEnLettre($convert[0]). ' ariary ' . $this->ecrireEnLettre($convert[1]) ;
            }
            if ($chiffre < 0) {
                return 'moins '. $this->ecrireEnLettre(-$chiffre);
            }
            if ($chiffre == 0) {
                return 'zero';
            } elseif ($chiffre < 17) {
                switch ($chiffre) {
                    case 1: return 'un';
                    case 2: return 'deux';
                    case 3: return 'trois';
                    case 4: return 'quatre';
                    case 5: return 'cinq';
                    case 6: return 'six';
                    case 7: return 'sept';
                    case 8: return 'huit';
                    case 9: return 'neuf';
                    case 10: return 'dix';
                    case 11: return 'onze';
                    case 12: return 'douze';
                    case 13: return 'treize';
                    case 14: return 'quatorze';
                    case 15: return 'quinze';
                    case 16: return 'seize';
                }
            } elseif ($chiffre < 20) {
                return 'dix-'. $this->ecrireEnLettre($chiffre - 10);
            } elseif ($chiffre < 100) {
                if ($chiffre % 10 == 0) {
                    switch ($chiffre) {
                        case 20: return 'vingt';
                        case 30: return 'trente';
                        case 40: return 'quarante';
                        case 50: return 'cinquante';
                        case 60: return 'soixante';
                        case 70: return 'soixante-dix';
                        case 80: return 'quatre-vingt';
                        case 90: return 'quatre-vingt-dix';
                    }
                } elseif (substr($chiffre, -1) == 1) {
                    if( ((int)($chiffre / 10) * 10) < 70 ) {
                        return $this->ecrireEnLettre((int)($chiffre / 10) * 10) . '-et-un';
                    } elseif ($chiffre == 71) {
                        return 'soixante-et-onze';
                    } elseif ($chiffre == 81) {
                        return 'quatre-vingt-un';
                    } elseif ($chiffre == 91) {
                        return 'quatre-vingt-onze';
                    }
                } elseif ($chiffre < 70) {
                    return $this->ecrireEnLettre($chiffre - $chiffre % 10) . '-' . $this->ecrireEnLettre($chiffre % 10);
                } elseif ($chiffre < 80) {
                    return $this->ecrireEnLettre(60) . '-' . $this->ecrireEnLettre($chiffre % 20);
                } else{
                    return $this->ecrireEnLettre(80) . '-' . $this->ecrireEnLettre($chiffre % 20);
                }
            } elseif ($chiffre == 100) {
                return 'cent';
            } elseif ($chiffre < 200) {
                $lettre = $this->ecrireEnLettre(100);
                if ($chiffre % 100 != 0) {
                    $lettre .= ' ' . $this->ecrireEnLettre($chiffre % 100);
                }
                return $lettre;
            } elseif ($chiffre < 1000) {
                $lettre = $this->ecrireEnLettre((int)($chiffre / 100)) . ' ' . $this->ecrireEnLettre(100);
                if ($chiffre % 100 != 0) {
                    $lettre .= ' ' . $this->ecrireEnLettre($chiffre % 100);
                }
                return $lettre;
            } elseif ($chiffre == 1000) {
                return 'mille';
            } elseif ($chiffre < 2000) {
                $lettre = $this->ecrireEnLettre(1000);
                if ($chiffre % 1000 != 0) {
                    $lettre .= ' ' . $this->ecrireEnLettre($chiffre % 1000);
                }
                return $lettre;
            } elseif ($chiffre < 1000000) {
                $lettre = $this->ecrireEnLettre((int)($chiffre / 1000)) . ' ' . $this->ecrireEnLettre(1000);
                if ($chiffre % 1000 != 0) {
                    $lettre .= ' ' . $this->ecrireEnLettre($chiffre % 1000);
                }
                return $lettre;
            }
            elseif ($chiffre == 1000000) {
                return 'millions';
            }
            elseif ($chiffre < 2000000) {
                $lettre = 'un million';
                if ($chiffre % 1000000 != 0) {
                    $lettre .= ' ' . $this->ecrireEnLettre($chiffre % 1000000);
                }
                return $lettre;
            }
            elseif ($chiffre < 1000000000) {
                $lettre = $this->ecrireEnLettre((int)($chiffre / 1000000)) . ' ' . $this->ecrireEnLettre(1000000);
                if ($chiffre % 1000000 != 0) {
                    $lettre .= ' ' . $this->ecrireEnLettre($chiffre % 1000000);
                }
                return $lettre;
            }
        }

        /**
         * Arrondir en fonction de la configuration
         *
         * @param float $somme  la somme à arrondir
         * @param int   $type   le type d'arrondissement
         *
         * @return float
         */
        private function arrondir($somme, $type)
        {
            switch ($type) {
                case self::ARRONDISSEMENT_VIRGULE :
                    return round($somme, 2);
                    break;
                case self::ARRONDISSEMENT_ENTIER :
                    return round($somme);
                    break;
                default:
                    return 0;
                    break;
            }
        }
	}
