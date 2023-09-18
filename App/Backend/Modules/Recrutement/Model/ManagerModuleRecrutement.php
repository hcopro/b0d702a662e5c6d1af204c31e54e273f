<?php
    
    /**
     * Manager du modules Recrutement Backend
     *
     * @author Voahirana 
     *
     * @since 09/10/19 
    */
    use \Core\DbManager;
    use \Core\PublicFonctions;
    use \Core\View;
    use \Model\ManagerCandidat;
    use \Model\ManagerCompte;
    use \Model\ManagerEntreprise;
    use \Model\ManagerSousDomaine;
    use \Model\ManagerDomaine;
    use \Model\ManagerExperience;
    use \Model\ManagerFormation;
    use \Model\ManagerDiplome;
    use \Model\ManagerContrat;
    use \Model\ManagerNiveauExperience;
    use \Model\ManagerNiveauEtude;
    use \Model\ManagerPersonnalite;
    use \Model\ManagerOffre;
    use \Model\ManagerCandidature;
    use \Model\ManagerEntretien;    
    use \Model\ManagerNiveauEntretien;    
    use \Model\ManagerEmailContact;   
    use \Model\ManagerInterlocuteur;   
    use \Model\ManagerInterlocuteurNiveauEntretien;
    use \Model\ManagerService;
    use \Model\ManagerPoste;
    use \Model\ManagerEntrepriseService;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerServicePoste;
    use \Model\ManagerMission;
    use \Model\ManagerTestCandidate;
    use \Model\ManagerTestCognitive;
    use \Model\ManagerTestPersonality;
    use \Model\ManagerTestClassification;
    use \Model\ManagerTestQuestion;
    use \Model\ManagerAttribution;
    use \Model\ManagerAfficheEntreprise;
    use \Model\ManagerLogiciel;
    use \Model\ManagerCommentaire;
    use \Model\ManagerLangue;
    use \Model\ManagerCentreInteret;
    use \Model\ManagerEmploye;
    require_once "Lib/Core/PhpDocx.php";
    class ManagerModuleRecrutement extends DbManager
    {
        
        const NO                        = 0;
        const FILTER_GROUP_ALL          = 1;
        const FILTER_GROUP_POSTE        = 2;
        const FILTER_GROUP_CANDIDAT     = 3;
        const FILTER_GROUP_OFFRE        = 4;
        const FILTER_GROUP_DOMAIN       = 5;
        const FILTER_GROUP_SUB_DOMAIN   = 6;
        const MAX_INTERVAL              = 3;
        const TODAY                     = 1;
        const TOMORROW                  = 2;
        const YESTERDAY                 = 3;
        const THIS_WEEK                 = 4;
        const NEXT_WEEK                 = 5;
        const LAST_WEEK                 = 6;
        const THIS_MONTH                = 7;
        const NEXT_MONTH                = 8;
        const LAST_MONTH                = 9;
        const FILTER_STATUS_ALL         = 0;
        const FILTER_STATUS_WAIT        = 1;
        const FILTER_STATUS_VALID       = 2;
        const FILTER_STATUS_REFUSE      = 3;
        const FILTER_STATUS_REJECT      = 4;
        const FILTER_STATUS_SEND        = 5;
        const MATCHING                  = 70;

        /** 
         * Lister les expériences 
         * 
         * @return array
        */
        public function listerDomaines()
        {
            $manager   = new ManagerDomaine(); 
            return $manager->lister();
        }

        /** 
         * Lister les expériences 
         * 
         * @return array
        */
        public function listerExperiences()
        {
            $function   = new PublicFonctions();
            $resultats  = $function->getCandidateExperience();
            /*$resultats     = array();
            $tabExperience = array();
            $manager       = new ManagerCandidat();
            $candidat      = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $manager       = new ManagerExperience(); 
                $experiences   = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                if (!empty($experiences)) {
                    foreach ($experiences as $experience) {
                        $manager         = new ManagerSousDomaine(); 
                        $sousDomaine     = $manager->chercher(['idSousDomaine' => $experience->getIdSousDomaine()]);
                        $tabExperience[] = [
                            'experience'  => $experience, 
                            'sousDomaine' => $sousDomaine
                        ];                      
                    }
                    $resultats = [
                        'candidat'    => $candidat, 
                        'experiences' => $tabExperience
                    ];
                }
            }*/
            return $resultats;
        } 

        /** 
         * Lister les formations 
         * 
         * @return array
        */
        public function listerFormations()
        {
            $function   = new PublicFonctions();
            $resultats  = $function->getCandidateFormation();
            /*$resultats  = array();
            $manager    = new ManagerCandidat();
            $candidat   = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $manager    = new ManagerFormation(); 
                $formations = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                if (!empty($formations)) {
                    foreach ($formations as $formation) {
                        $manager         = new ManagerSousDomaine(); 
                        $sousDomaine     = $manager->chercher(['idSousDomaine' => $formation->getIdSousDomaine()]);
                        $tabFormation[]  = [
                            'formation'   => $formation, 
                            'sousDomaine' => $sousDomaine
                        ];
                    }
                    $resultats = [
                        'candidat'   => $candidat, 
                        'formations' => $tabFormation
                    ];
                }
            }*/
            return $resultats;
        }

        /** 
         * Lister les diplômes 
         * 
         * @return array 
        */
        public function listerDiplomes()
        {
            $function   = new PublicFonctions();
            $resultats  = $function->getCandidateDiplome();
            /*$resultats = array();
            $manager   = new ManagerCandidat();
            $candidat  = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $manager   = new ManagerDiplome(); 
                $diplomes  = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                if (!empty($diplomes)) {
                    foreach ($diplomes as $diplome) {
                        $manager      = new ManagerDomaine(); 
                        $domaine      = $manager->chercher(['idDomaine' => $diplome->getIdDomaine()]);
                        $manager      = new ManagerNiveauEtude();
                        $niveauEtude  = $manager->chercher(['idNiveauEtude' => $diplome->getIdNiveauEtude()]);
                        $tabDiplome[] = [
                            'diplome'     => $diplome, 
                            'domaine'     => $domaine, 
                            'niveauEtude' => $niveauEtude
                        ];
                    }
                    $resultats = [
                        'candidat' => $candidat, 
                        'diplomes' => $tabDiplome
                    ];
                }
            }*/
            return $resultats;
        } 

        /** 
         * Lister les niveaux expériences 
         * 
         * @return array
        */
        public function listerNiveauxExperiences()
        {
            $manager = new ManagerNiveauExperience();
            return $manager->lister();
        }

        /** 
         * Lister les niveaux d'études 
         * 
         * @return array
        */
        public function listerNiveauxEtudes()
        {
            $manager = new ManagerNiveauEtude();
            return $manager->lister();
        }

        /** 
         * Lister les contrats 
         * 
         * @return array
        */
        public function listerContrats()
        {
            $manager = new ManagerContrat();
            return $manager->lister(); 
        }

        /** 
         * Lister les personnalités 
         * 
         * @return array
        */
        public function listerPersonnalites()
        {
            $manager = new ManagerPersonnalite();
            return $manager->lister();
        }

        /** 
         * Lister les services 
         * 
         * @return array
        */
        public function listerServices()
        {
            $manager = new ManagerService();
            return $manager->lister();
        }

        /** 
         * Lister les postes 
         * 
         * @return array
        */
        public function listerPostes()
        {
            $manager = new ManagerPoste();
            return $manager->lister();
        }

        /** 
         * Lister les offres d'une entreprise
         *
         * @return array
        */
        public function listerOffres()
        {
            $tabOffre   = array();
            $resultats  = array();
            if (array_key_exists('addMenuUser',$_SESSION['compte'])) {
                if ($_SESSION['compte']['addMenuUser'] === 'YES') {
                    $chearcheArray = array('idEntreprise' => $_SESSION['user']['idEntreprise']);
                } else {
                    return;
                }
            } else {
                $chearcheArray = array('idCompte' => $_SESSION['compte']['idCompte']);
            }
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher($chearcheArray);
            if (!empty($entreprise)) {
                $manager    = new ManagerOffre();
                $offres     = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if (!empty($offres)) {
                    foreach ($offres as $offre) {
                        $manager     = new ManagerSousDomaine();
                        $sousDomaine = $manager->chercher(['idSousDomaine' => $offre->getIdSousDomaine()]);
                        $manager     = new ManagerContrat();
                        $contrat     = $manager->chercher(['idContrat' => $offre->getIdContrat()]);
                        $manager     = new ManagerEntreprisePoste();
                        $poste       = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                        $tabOffre[]  = [
                            'poste'       => $poste,
                            'offre'       => $offre, 
                            'sousDomaine' => $sousDomaine, 
                            'contrat'     => $contrat
                        ];                        
                    } 
                    $resultats = [
                        'entreprise' => $entreprise, 
                        'offres'     => $tabOffre
                    ];
                }
            }
            return $resultats;
        }

        /** 
         * Lister les candidatures d'un candidat 
         * 
         * @return array
        */
        public function listerCandidatures()
        {
            $resultats                = array();
            $parameters               = array();
            if (array_key_exists('addMenuUser',$_SESSION['compte'])) {
                if ($_SESSION['compte']['addMenuUser'] === 'YES') {
                    $chearcheArray = array('idEntreprise' => $_SESSION['user']['idEntreprise']);
                } else {
                    return;
                }
            } else {
                $chearcheArray = array('idCompte' => $_SESSION['compte']['idCompte']);
            }
            $manager                  = new ManagerCandidat();
            $candidat                 = $manager->chercher($chearcheArray);
            if (!empty($candidat)) {
                  $parameters['idCandidat'] = $candidat->getIdCandidat();
                if (!empty($parameters)) {
                    $manager      = new ManagerCandidature();
                    $candidatures = $manager->lister($parameters);
                    if (!empty($candidatures)) {
                        foreach ($candidatures as $candidature) {
                            $manager         = new ManagerOffre();
                            $offre           = $manager->chercher(['idOffre' => $candidature->getIdOffre()]);
                            $manager         = new ManagerEntreprisePoste();
                            $poste           = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                            $manager         = new ManagerEntreprise();
                            $entreprise      = $manager->chercher(['idEntreprise' => $offre->getIdEntreprise()]);
                            $manager         = new ManagerCandidat();
                            $candidat        = $manager->chercher(['idCandidat' => $candidature->getIdCandidat()]);
                            $manager         = new ManagerEntretien();
                            $entretien       = $manager->chercher(['idCandidature' => $candidature->getIdCandidature()]);
                            $manager         = new ManagerNiveauEntretien();
                            $niveauEntretien = "";
                            $interlocuteurs  = "";
                            if (!empty($entretien)) {
                                $niveauEntretien = $manager->chercher(['idNiveauEntretien' => $entretien->getIdNiveauEntretien()]);
                                if (!empty($niveauEntretien)) {
                                    $manager        = new ManagerInterlocuteur();
                                    $interlocuteurs = $manager->lister(['idNiveauEntretien' => $niveauEntretien->getIdNiveauEntretien()]);
                                }
                            }
                            $resultats[]     = [
                                'candidature'     => $candidature, 
                                'entretien'       => $entretien,
                                'offre'           => $offre, 
                                'entreprise'      => $entreprise,
                                'candidat'        => $candidat,
                                'niveauEntretien' => $niveauEntretien,
                                'interlocuteurs'  => $interlocuteurs,
                                'poste'           => $poste
                            ];
                        }
                    }
                }       
            }  
            return $resultats;
        }

        /** @changelog 22/12/2021 [EVOL] (Lansky) Lister les candidatures d'une entreprise selon le filtre*/
        /** 
         * Lister les candidatures d'une entreprise
         * 
         * @return array
        */
        public function listerCandidaturesEntreprise($parameters)
        {
            /*
                $manager        = new ManagerEntreprisePoste();
                $filters['postesFilter']        = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager        = new ManagerDomaine();
                $filters['domainesFilter']      = $manager->lister([]);
                $manager        = new ManagerOffre();
                $filters['offresFilter']        = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager        = new ManagerSousDomaine();
                $filters['sousDomainesFilter']  = $manager->lister([]);
                foreach ($filters['offresFilter'] as $key => $value) {
                    $value->setIdSousDomaine($manager->chercher(['idSousDomaine' => $value->getIdSousDomaine()]));
                }
                $manager        = new ManagerCandidature();
                $candidaturesFilter             = $manager->lister(['id_entreprise' => $_SESSION['user']['idEntreprise']]);
                $manager        = new ManagerCandidat();
                $viewFilter     = isset($parameters);
                $tmpId          = array();
                $user           = $_SESSION['compte']['identifiant'];
                $user           = $user === 'employe' && $_SESSION['compte']['addMenuUser'] === 'YES' ? $user : 'entreprise';
                $filtreStatut   = NULL;
                foreach ($candidaturesFilter as $candidatureFilter) {
                    $addList    = false;
                    if (!empty($tmpId)) {
                        for ($i = 0; $i < count($tmpId); $i++) {
                            if ($tmpId[$i] != $candidatureFilter->getIdCandidat()) {
                                $addList = true;
                            } else {
                                $addList = false;
                                break;
                            }
                        }
                    } else {
                        $tmpId[] = $candidatureFilter->getIdCandidat();
                        $addList = true;
                    }
                    if ($addList) {
                        $tmpId[]                        = $candidatureFilter->getIdCandidat();
                        $filters['candidatsFilter'][]   = $manager->chercher(['idCandidat' => $candidatureFilter->getIdCandidat()]);
                    }
                }
                if ($viewFilter) {
                    extract($parameters);
                    $resultats      = array();
                    $candidatures   = array();
                    $manager        = new ManagerCandidature();
                    // Filtrer la liste de la candidature par rapport à l'offre soit disant poste, domaine, sous-domaine, ainsi que le/la candidat(e). 
                    switch ($groupe) {
                        case self::FILTER_GROUP_ALL:
                            $candidatures   = $manager->lister(['id_entreprise' => $_SESSION['user']['idEntreprise']]);
                            break;
                        
                        case self::FILTER_GROUP_POSTE:
                            $manager        = new ManagerOffre();
                            $getOffre       = $manager->lister([
                                'idEntreprisePoste' => $id,
                                'idEntreprise'      => $_SESSION['user']['idEntreprise']
                            ]);
                            $manager        = new ManagerCandidature();
                            foreach ($getOffre as $value) {
                                $res        = $manager->lister([
                                    'id_entreprise' => $_SESSION['user']['idEntreprise'],
                                    'idOffre'       => $value->getIdOffre()
                                ]) ;
                                if (!empty($res)) {
                                    foreach ($res as $response) {
                                        $candidatures[] = $response;
                                    }
                                }
                            }
                            break;
                        
                        case self::FILTER_GROUP_CANDIDAT:
                            $candidatures   = $manager->lister(['id_entreprise' => $_SESSION['user']['idEntreprise'], 'idCandidat' => $id]);
                            break;
                        
                        case self::FILTER_GROUP_OFFRE:
                            $candidatures   = $manager->lister(['id_entreprise' => $_SESSION['user']['idEntreprise'], 'idOffre' => $id]);
                            break;
                        
                        case self::FILTER_GROUP_DOMAIN:
                            $manager            = new ManagerSousDomaine();
                            $getSubDomanaine    = $manager->lister(['idDomaine' => $id]);
                            $manager            = new ManagerOffre();
                            foreach ($getSubDomanaine as $subDom) {
                                $tmpOffre       = $manager->lister([
                                    'idSousDomaine' => $subDom->getIdSousDomaine(),
                                    'idEntreprise'  => $_SESSION['user']['idEntreprise']
                                ]);
                                if (!empty($tmpOffre)) {
                                    $manager    = new ManagerCandidature();
                                    foreach ($tmpOffre as $valueOffre) {
                                        $res    = $manager->lister([
                                            'id_entreprise' => $_SESSION['user']['idEntreprise'],
                                            'idOffre'       => $valueOffre->getIdOffre()
                                        ]) ;
                                        if (!empty($res)) {
                                            foreach ($res as $response) {
                                                $candidatures[] = $response;
                                            }
                                        }
                                    }
                                }
                            }
                            break;
                        
                        case self::FILTER_GROUP_SUB_DOMAIN:
                            $manager        = new ManagerOffre();
                            $getOffre       = $manager->lister(['idSousDomaine' => $id, 'idEntreprise' => $_SESSION['user']['idEntreprise']]);
                            $manager        = new ManagerCandidature();
                            foreach ($getOffre as $value) {
                                $res        = $manager->lister([
                                    'id_entreprise' => $_SESSION['user']['idEntreprise'],
                                    'idOffre'       => $value->getIdOffre()
                                ]) ;
                                if (!empty($res)) {
                                    foreach ($res as $response) {
                                        $candidatures[] = $response;
                                    }
                                }
                            }
                            break;
                        
                        default:
                            break;
                    }
                    // Filtrer par rapport à la date de postule
                    $listFilterDate = array();
                    if (!empty($candidatures)) {
                        if ($mois) {
                            $listFilterDate = $this->FilterDataByMonth($candidatures, date('Y'), $mois);
                        } elseif ($debut && $fin) {
                            $temporary = explode('/', $debut);
                            $debut = $temporary[2].'-'.$temporary[1].'-'.$temporary[0];
                            $temporary = explode('/', $fin);
                            $fin = $temporary[2].'-'.$temporary[1].'-'.$temporary[0];
                            $listFilterDate = $this->FilterDataByDate($candidatures, $debut, $fin);
                        } elseif ($periode) {
                            switch ($periode) {
                                case self::TODAY :
                                    foreach ($candidatures as $candidate) {
                                        if ($candidate->getDateCandidature() == date('Y-m-d')) {
                                            $listFilterDate[] = $candidate;
                                        }
                                    }
                                    break;
                                case self::TOMORROW :
                                    $date = new DateTime();
                                    $date->add(new DateInterval("P1D"));
                                    $listFilterDate = $this->FilterDataByDate($candidatures, $date->format('Y-m-d'));
                                    break;
                                case self::YESTERDAY :
                                    $date = new DateTime();
                                    $date->sub(new DateInterval("P1D"));
                                    $listFilterDate = $this->FilterDataByDate($candidatures, $date->format('Y-m-d'));
                                    break;
                                case self::LAST_WEEK :
                                    $date = new DateTime();
                                    $arrayDate = [
                                        '0' => date('Y-m-d', strtotime('Monday last week'.$date->format('Y-m-d').'')),
                                        '1' => date('Y-m-d', strtotime('Tuesday last week'.$date->format('Y-m-d').'')),
                                        '2' => date('Y-m-d', strtotime('Wednesday last week'.$date->format('Y-m-d').'')),
                                        '3' => date('Y-m-d', strtotime('Thursday last week'.$date->format('Y-m-d').'')),
                                        '4' => date('Y-m-d', strtotime('Friday last week'.$date->format('Y-m-d').'')),
                                        '5' => date('Y-m-d', strtotime('Saturday last week'.$date->format('Y-m-d').'')),
                                        '6' => date('Y-m-d', strtotime('Sunday last week'.$date->format('Y-m-d').''))
                                    ];
                                    $listFilterDate = $this->FilterDataByDate($candidatures, $arrayDate);
                                    break;
                                case self::THIS_WEEK :
                                    $date = new DateTime();
                                    $arrayDate = [
                                        '0' => date('Y-m-d', strtotime('Monday this week'.$date->format('Y-m-d').'')),
                                        '1' => date('Y-m-d', strtotime('Tuesday this week'.$date->format('Y-m-d').'')),
                                        '2' => date('Y-m-d', strtotime('Wednesday this week'.$date->format('Y-m-d').'')),
                                        '3' => date('Y-m-d', strtotime('Thursday this week'.$date->format('Y-m-d').'')),
                                        '4' => date('Y-m-d', strtotime('Friday this week'.$date->format('Y-m-d').'')),
                                        '5' => date('Y-m-d', strtotime('Saturday this week'.$date->format('Y-m-d').'')),
                                        '6' => date('Y-m-d', strtotime('Sunday this week'.$date->format('Y-m-d').'')),
                                    ];
                                    $listFilterDate = $this->FilterDataByDate($candidatures, $arrayDate);
                                    break;
                                case self::NEXT_WEEK :
                                    $date = new DateTime();
                                    $arrayDate = [
                                        '0' => date('Y-m-d', strtotime('Monday next week'.$date->format('Y-m-d').'')),
                                        '1' => date('Y-m-d', strtotime('Tuesday next week'.$date->format('Y-m-d').'')),
                                        '2' => date('Y-m-d', strtotime('Wednesday next week'.$date->format('Y-m-d').'')),
                                        '3' => date('Y-m-d', strtotime('Thursday next week'.$date->format('Y-m-d').'')),
                                        '4' => date('Y-m-d', strtotime('Friday next week'.$date->format('Y-m-d').'')),
                                        '5' => date('Y-m-d', strtotime('Saturday next week'.$date->format('Y-m-d').'')),
                                        '6' => date('Y-m-d', strtotime('Sunday next week'.$date->format('Y-m-d').'')),
                                    ];
                                    $listFilterDate = $this->FilterDataByDate($candidatures, $arrayDate);
                                    break;
                                case self::LAST_MONTH :
                                    if (date('m') - 1 == 0) {
                                        $month = 12;
                                        $year = date('Y') - 1;
                                    } else {
                                        $month = date('m') - 1;
                                        $year = date('Y');
                                    }
                                    $listFilterDate = $this->FilterDataByMonth($candidatures, $year,$month);
                                    break;
                                case self::THIS_MONTH :
                                    $listFilterDate = $this->FilterDataByMonth($candidatures, date('Y'), date('m'));
                                    break;
                                case self::NEXT_MONTH :
                                    if (date('m') + 1 > 12) {
                                        $month = 1;
                                        $year = date('Y') + 1;
                                    } else {
                                        $month = date('m') + 1;
                                        $year = date('Y');
                                    }
                                    $listFilterDate = $this->FilterDataByMonth($candidatures, $year, $month);
                                    break;
                                
                                default:
                                    // code...
                                    break;
                            }
                        }
                        $candidatures = $listFilterDate;
                    }
                    // Filtrer par rapport au statut de la candidature
                    $manager    = new ManagerEntretien();
                    $tmpStatus  = array();
                    switch ($statut) {
                        case self::FILTER_STATUS_ALL :
                            break;
                        case self::FILTER_STATUS_WAIT :
                            $filtreStatut     = 'en attente';
                            break;
                        case self::FILTER_STATUS_VALID :
                            $filtreStatut     = 'accepte';
                            break;
                        case self::FILTER_STATUS_REFUSE :
                            $filtreStatut     = 'refuse';
                            break;
                        case self::FILTER_STATUS_REJECT :
                            $filtreStatut     = 'rejete';
                            break;
                        
                        case self::FILTER_STATUS_SEND :
                            $filtreStatut     = 'envoye';
                            break;
                        
                        default:
                            break;
                    }
                    if ($filtreStatut && !empty($candidatures)) {
                        foreach ($candidatures as $candidature) {
                            if ($candidature->getStatut() === $filtreStatut) {
                                $tmpStatus[] = $candidature;
                            }
                        }
                        $candidatures = $tmpStatus;
                    }
                    $resultats['view'] = array('viewFilter' => $viewFilter );
                    if (!empty($candidatures)) {
                        foreach ($candidatures as $candidature) {
                            $manager         = new ManagerOffre();
                            $offre           = $manager->chercher(['idOffre' => $candidature->getIdOffre()]);
                            $manager         = new ManagerEntreprisePoste();
                            $poste           = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                            $manager         = new ManagerEntreprise();
                            $entreprise      = $manager->chercher(['idEntreprise' => $offre->getIdEntreprise()]);
                            $manager         = new ManagerCandidat();
                            $candidat        = $manager->chercher(['idCandidat' => $candidature->getIdCandidat()]);
                            $manager         = new ManagerEntretien();
                            $entretien       = $manager->chercher(['idCandidature' => $candidature->getIdCandidature()]);
                            $manager         = new ManagerNiveauEntretien();
                            $niveauEntretien = "";
                            $interlocuteurs  = "";
                            $candidature->setDateCandidature($this->writeDate($candidature->getDateCandidature()));
                            if (!empty($entretien)) {
                                $niveauEntretien = $manager->chercher(['idNiveauEntretien' => $entretien->getIdNiveauEntretien()]);
                                if (!empty($niveauEntretien)) {
                                    $manager        = new ManagerInterlocuteur();
                                    $interlocuteurs = $manager->lister(['idNiveauEntretien' => $niveauEntretien->getIdNiveauEntretien()]);
                                }
                            }
                            $resultats['view'][]  = [
                                'candidature'       => $candidature, 
                                'entretien'         => $entretien,
                                'offre'             => $offre,
                                'entreprise'        => $entreprise,
                                'candidat'          => $candidat,
                                'niveauEntretien'   => $niveauEntretien,
                                'interlocuteurs'    => $interlocuteurs,
                                'poste'             => $poste
                            ];
                        }
                    }
                        
                        
                // echo "<pre>";
                // var_dump($user);
                // var_dump($resultats);
                // var_dump($parameters);
                // var_dump($_SESSION);
                // exit;
                    $view   = new View("lister");
                    $view->sendWithoutTemplate("Backend", "Recrutement", $resultats, $user);
                    exit();
                } else {
                    return  [
                        'domainesFilter'        => $filters['domainesFilter'], 
                        'sousDomainesFilter'    => $filters['sousDomainesFilter'],
                        'offresFilter'          => $filters['offresFilter'], 
                        'postesFilter'          => $filters['postesFilter'],
                        'candidatsFilter'       => array_key_exists('candidatsFilter', $filters) ? $filters['candidatsFilter'] : array()
                    ];
                }
            */
            $resultats  = array();
            $tabOffre   = array(); 
            $manager    = new ManagerOffre();
            if(isset($parameters['idOffre'])) {
                $offres = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise'],'idOffre' => $parameters['idOffre']]);
            } else {
                $offres = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            }
            $isAddMenu = array_key_exists('addMenuUser', $_SESSION['compte']) ? $_SESSION['compte']['addMenuUser'] : 'NO';
            if (!empty($offres)) {
                foreach ($offres as $offre) {
                    $candidatures   = array();
                    $tabCandidature = array(); 
                    $manager        = new ManagerEntreprisePoste();
                    $poste          = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                    if ($_SESSION['compte']['identifiant'] === "employe" && $isAddMenu === "NO") {
                        $manager        = new ManagerAttribution();
                        $attributions   = $manager->lister([
                            'idEntreprise'  => $_SESSION['user']['idEntreprise'], 
                            'idEmploye'     => $_SESSION['user']['idEmploye'],
                            'idOffre'       => $offre->getIdOffre()
                        ]);
                        $manager        = new ManagerCandidature();
                        if (!empty($attributions)) {
                            foreach ($attributions as $attribution) {
                                $value   = $manager->chercher([
                                    'idCandidature' => $attribution->getIdCandidature(), 
                                    'idCandidat'    => $attribution->getIdCandidat(),
                                    'idOffre'       => $attribution->getIdOffre(),
                                    'statut'        => $attribution->getStatut()
                                ]);
                                if ($value) {
                                    $candidatures[] = $value;
                                }
                            }
                        }
                    } elseif ($_SESSION['compte']['identifiant'] === "entreprise" || $_SESSION['compte']['identifiant'] === "admin" || ($isAddMenu === "YES" && $_SESSION['compte']['identifiant'] === "employe")) {
                        $manager        = new ManagerCandidature();
                        $candidatures   = $manager->lister(['idOffre' => $offre->getIdOffre()]);
                    }
                    if (!empty($candidatures)) { 
                        foreach ($candidatures as $candidature) {
                            if (!empty($candidature)) {
                                $tabCandidat    = array();
                                $tabFormation   = array();
                                $manager        = new ManagerCandidat();
                                $candidat       = $manager->chercher(['idCandidat' => $candidature->getIdCandidat()]);
                                if (!empty($candidat)) {
                                    $manager        = new ManagerLangue();
                                    $langue         = $manager->lister(['id_candidat'       => $candidat->getIdCandidat()]);
                                    $manager        = new ManagerLogiciel();
                                    $logiciel       = $manager->lister(['id_candidat'       => $candidat->getIdCandidat()]);
                                    $manager        = new ManagerExperience();
                                    $experience     = $manager->lister(['idCandidat'        => $candidat->getIdCandidat()]);
                                    $manager        = new ManagerCentreInteret();
                                    $centreInteret  = $manager->lister(['id_candidat'       => $candidat->getIdCandidat()]);
                                    $manager        = new ManagerFormation();
                                    $formations     = $manager->lister(['idCandidat'        => $candidat->getIdCandidat()]);
                                    if (!empty($formations)) {
                                        foreach ($formations as $formation) {
                                            $manager        = new ManagerSousDomaine();
                                            $sousDomaine    = $manager->chercher(['idSousDomaine' => $formation->getIdSousDomaine()]);
                                            $tabFormation[] = [
                                                'formation'     => $formation,
                                                'sousDomaine'   => $sousDomaine,
                                            ];
                                        }
                                    }
                                    $tabCandidat[]  = [
                                        'langues'           => $langue,
                                        'formations'        => $tabFormation,
                                        'experiences'       => $experience,
                                        'logiciels'         => $logiciel,
                                        'centreInterets'    => $centreInteret
                                    ];
                                }
                                $manager            = new ManagerEntretien();
                                $entretien          = $manager->chercher(['idCandidature' => $candidature->getIdCandidature()]);
                               
                                $tabCandidature[]   = [
                                    'candidature'   => $candidature,
                                    'competence'    => $tabCandidat,
                                    'candidat'      => $candidat,
                                    'entretien'     => $entretien
                                ];
                            }
                        } 
                    }
                    $tabOffre[] = [
                        'offre'         => $offre,
                        'poste'         => $poste,
                        'candidatures'  => $tabCandidature 
                    ];
                }
                $resultats          = [
                    'offres'        => $this->listerLesOffres(),
                    'candidatForm'  => count($tabOffre) > 0 ? $this->getCandidatCv($tabOffre) : array(),
                    'cardsData'     => count($tabOffre) > 0 ? $this->cardCandidate($tabOffre) : array(),
                    'employes'      => $this->listerEmploye()
                ];
            }
            return $resultats; 
        }

        /** 
         * Filtrer un tableau par rapport au mois
         * 
         * @param array $data Données à filtrer
         * @param int $month Mois filtre
         * @param int $year année filtre
         * 
         * @return array
        */
        private function FilterDataByMonth ($data, $year, $month) {
            $filters = array();
            foreach ($data as $donnee) {
                if (explode('-',$donnee->getDateCandidature())[1] == $month && explode('-',$donnee->getDateCandidature())[0] == $year) {
                    $filters[] = $donnee;
                }
            }
            return $filters;
        }

        /** 
         * Filtrer un tableau par rapport à la date
         * 
         * @param array $data Données à filtrer
         * @param date $date Date filtre
         * @param date $fin Date interval fin filtre
         * 
         * @return array
        */
        private function FilterDataByDate ($data, $date, $fin = null) {
            $filters = array();
            if (is_array($date)) {
                $fin = end($date); 
                $date = $date[0]; 
            }
            foreach ($data as $donnee) {
                if ($fin) {
                    if ($donnee->getDateCandidature() >= $date && $donnee->getDateCandidature() <= $fin) {
                        $filters[] = $donnee;
                    }
                } else {
                    if ($donnee->getDateCandidature() == $date) {
                        $filters[] = $donnee;
                    }
                }
            }
            return $filters;
        }

        /** 
         * Lister les candidatures d'une offre
         * 
         * @param array $parameters critères des données à lister
         * 
         * @return array
        */
        public function listerOffreCandidatures($parameters)
        {
            $resultats  = array();
            $manager      = new ManagerCandidature();
            $candidatures = $manager->lister($parameters);
            if (!empty($candidatures)) {
                foreach ($candidatures as $candidature) {
                    $manager     = new ManagerEntretien();
                    $entretien   = $manager->chercher(['idCandidature' => $candidature->getIdCandidature()]);
                    $manager     = new ManagerCandidat();
                    $candidat    = $manager->chercher(['idCandidat' => $candidature->getIdCandidat()]);
                    $profils     = $manager->specifierProfilCandidat(['idCandidat' => $candidature->getIdCandidat()]);
                    $manager     = new ManagerOffre();
                    $offre       = $manager->chercher($parameters);
                    $profilOffre = $manager->specifierProfilOffre(['idOffre' => $parameters['idOffre']]);
                    if (!empty($profils)) {
                        $experienceCandidat = array();
                        $diplomeCandidat    = array();
                        $tabCandidat        = [ 
                            'candidat'    => $candidat, 
                            'experience'  => $experienceCandidat,
                            'diplome'     => $diplomeCandidat
                        ];
                        extract($profils);
                        if (!empty($experiences)) {
                            foreach ($experiences as $experience) {
                                if ($experience['sousDomaine'] == $profilOffre['experience']['sousDomaine'] && $experience['annee'] >= $profilOffre['experience']['annee'] ) {
                                    $experienceCandidat = $experience;
                                    break;
                                }
                            }
                        }
                        /*if (!empty($diplomes)) {
                            foreach ($diplomes as $diplome) {
                                if ($diplome['domaine'] == $profilOffre['diplome']['domaine'] && $diplome['niveau'] >= $profilOffre['diplome']['niveau']) {
                                    $diplomeCandidat = $diplome;
                                    break;
                                } else if (!empty($experienceCandidat) && $diplome['domaine'] == $profilOffre['diplome']['domaine']) {
                                    $diplomeCandidat = $diplome;
                                    break;
                                }
                            }
                        }*/
                        if (!empty($formations)) {
                            foreach ($formations as $formation) {
                                if ($formation['domaine'] == $profilOffre['formation']['domaine'] && $formation['niveau'] >= $profilOffre['formation']['niveau']) {
                                    $formationCandidat = $formation;
                                    break;
                                } else if (!empty($experienceCandidat) && $formation['domaine'] == $profilOffre['formation']['domaine']) {
                                    $diplomeCandidat = $formation;
                                    break;
                                }
                            }
                        }
                        if (!empty($experienceCandidat) || !empty($diplomeCandidat)) {
                            $tabCandidat = [
                                'candidat'    => $candidat, 
                                'experience'  => $experienceCandidat,
                                'formation'   => $formationCandidat
                                // 'diplome'     => $diplomeCandidat
                            ];
                        }
                    }
                    $resultats[] = [
                        'candidat'    => $tabCandidat, 
                        'candidature' => $candidature,
                        'entretien'   => $entretien
                    ];
                }
            }
            return $resultats;
        } 

        /** 
         * Lister les entretiens 
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
        */
        public function listerEntretiens($parameters)
        {
            $resultats         = array();
            if (array_key_exists('addMenuUser',$_SESSION['compte'])) {
                if ($_SESSION['compte']['addMenuUser'] === 'YES') {
                    $chearcheArray = array('idEntreprise' => $_SESSION['user']['idEntreprise']);
                } else {
                    return;
                }
            } else {
                $chearcheArray = array('idCompte' => $_SESSION['compte']['idCompte']);
            }
            $manager           = new ManagerEntreprise();
            $entreprise        = $manager->chercher($chearcheArray);
            if (!empty($entreprise)) {
                $manager           = new ManagerNiveauEntretien();
                $niveauxEntretiens = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                $minOrdre          = $manager->getMinOrdre($entreprise->getIdEntreprise());
                extract($minOrdre);
                if (!empty($niveauxEntretiens) && !isset($parameters['idNiveauEntretien'])) {
                    foreach ($niveauxEntretiens as $niveauEntretien) {
                        if ($niveauEntretien->getOrdre() == $minOrdre) {
                            $parameters['idNiveauEntretien'] = $niveauEntretien->getIdNiveauEntretien();
                        }
                    }
                }
                if (isset($parameters['idOffre'])) {
                    $resultats = [
                        'niveauxEntretiens' => $niveauxEntretiens,
                        'entretiens'        => $this->listerEntretiensParOffre($parameters)
                    ];
                } else {
                    $resultats = [
                        'niveauxEntretiens' => $niveauxEntretiens,
                        'entretiens'        => $this->listerEntretiensParEntreprise($parameters)
                    ];
                }
            }
            return $resultats;
        }

        /** 
         * Lister les entretiens par offre
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
        */
        private function listerEntretiensParOffre($parameters)
        {
            $resultats    = array();
            $manager      = new ManagerOffre();
            $offre        = $manager->chercher(['idOffre' => $parameters['idOffre']]);
            $engagedPost  = $manager->getEngagedPoste(['idOffre' => $parameters['idOffre']]);
            $manager      = new ManagerEntretien();
            $entretiens   = $manager->lister(['idNiveauEntretien' => $parameters['idNiveauEntretien']]);
            if (!empty($entretiens)) {
                foreach ($entretiens as $entretien) {
                    $manager     = new ManagerCandidature();
                    $candidature = $manager->chercher(['idCandidature' => $entretien->getIdCandidature()]);
                    if (!empty($candidature) && $offre->getIdOffre() == $candidature->getIdOffre()) {
                        $manager     = new ManagerCandidat();
                        $candidat    = $manager->chercher(['idCandidat' => $candidature->getIdCandidat()]);
                        $manager     = new ManagerEntreprisePoste();
                        $poste       = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                        $resultats[] = [
                            'poste'       => $poste,
                            'engagedPost' => $engagedPost,
                            'offre'       => $offre,
                            'candidat'    => $candidat,
                            'entretien'   => $entretien
                        ];
                    }                            
                }
            }
            return $resultats;
        }

        /** 
         * Lister les entretiens d'une entreprise
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
        */
        private function listerEntretiensParEntreprise($parameters)
        {
            $resultats         = array();
            if (array_key_exists('addMenuUser',$_SESSION['compte'])) {
                if ($_SESSION['compte']['addMenuUser'] === 'YES') {
                    $chearcheArray = array('idEntreprise' => $_SESSION['user']['idEntreprise']);
                } else {
                    return;
                }
            } else {
                $chearcheArray = array('idCompte' => $_SESSION['compte']['idCompte']);
            }
            $manager           = new ManagerEntreprise();
            $entreprise        = $manager->chercher($chearcheArray);
            if (!empty($entreprise)) {
                $manager           = new ManagerOffre();
                $offres            = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if (!empty($offres)) {
                    foreach ($offres as $offre) {
                        $manager      = new ManagerCandidature();
                        $candidatures = $manager->lister(['idOffre' => $offre->getIdOffre()]);
                        if (!empty($candidatures)) {
                            foreach ($candidatures as $candidature) {
                                $manager   = new ManagerCandidat();
                                $candidat  = $manager->chercher(['idCandidat' => $candidature->getIdCandidat()]);
                                $manager   = new ManagerEntretien();
                                $entretien = $manager->chercher(['idCandidature' => $candidature->getIdCandidature(), 'idNiveauEntretien' => $parameters['idNiveauEntretien']]);
                                if (!empty($entretien)) {
                                    $manager     = new ManagerOffre();
                                    $engagedPost = $manager->getEngagedPoste(['idOffre' => $offre->getIdOffre()]);
                                    $manager     = new ManagerEntreprisePoste();
                                    $poste       = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                                    $resultats[] = [
                                        'poste'       => $poste,
                                        'engagedPost' => $engagedPost,
                                        'offre'       => $offre,
                                        'candidat'    => $candidat,
                                        'entretien'   => $entretien
                                    ];
                                }
                            }
                        }
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les niveaux entretiens
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
        */
        public function listerNiveauxEntretiens()
        {
            $resultats  = array();
            if (array_key_exists('addMenuUser',$_SESSION['compte'])) {
                if ($_SESSION['compte']['addMenuUser'] === 'YES') {
                    $chearcheArray = array('idEntreprise' => $_SESSION['user']['idEntreprise']);
                } else {
                    return;
                }
            } else {
                $chearcheArray = array('idCompte' => $_SESSION['compte']['idCompte']);
            }
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher($chearcheArray);
            if (!empty($entreprise)) {
                $manager           = new ManagerNiveauEntretien();
                $niveauxEntretiens = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if (!empty($niveauxEntretiens)) {
                    foreach ($niveauxEntretiens as $niveauEntretien) {
                        $resultats[] = $niveauEntretien;
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les services d'une entreprise
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
        */
        public function listerEntrepriseServices()
        {
            $manager        = new ManagerService();
            $allServices    = $manager->lister();
            $services       = array();
            $manager        = new ManagerEntreprise();
            $entreprise     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $verified = false;
            if (!empty($entreprise)) {
                if ($_SESSION['compte']['identifiant'] == 'entreprise') {
                    $verified = true;
                } else {
                    if (array_key_exists('addMenuUser', $_SESSION['compte'])) {
                        if ($_SESSION['compte']['addMenuUser'] == 'YES') {
                            $verified = true;
                        }
                    }
                }
            }
            if ($verified) {
                $manager    = new ManagerEntrepriseService();
                $services   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            }
            return [
                "entreprise"  => $entreprise,
                "services"    => $services,
                "allServices" => $allServices
            ];
        }

        /** 
         * Lister les postes d'une entreprise
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
        */
        public function listerEntreprisePostes()
        {
            $postes     = array();
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($entreprise)) {
                $manager  = new ManagerEntreprisePoste();
                if ($_SESSION['compte']['identifiant'] == 'entreprise') {
                    $postes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                } else {
                    if ($_SESSION['compte']['addMenuUser'] == 'YES') {
                        $postes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    } elseif (array_key_exists('isSuperior', $_SESSION)) {
                        if (isset($_SESSION['isSuperior']['posteList'])) {
                            foreach ($_SESSION['isSuperior']['posteList'] as $id) {
                                $postes[] = $manager->chercher(['idEntreprisePoste' => $id]);
                            }
                        }
                    }
                } 
            }
            return $postes;
        }

        /** 
         * Lister les offres suggérés à un candidat
         *
         * @return array
        */
        public function listerSuggestOffres()
        {
            /*$resultats      = Array();
            $manager        = new ManagerCandidat();
            $candidat       = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $profilCandidat = $manager->specifierProfilCandidat(['idCandidat' => $candidat->getIdCandidat()]);
                extract($profilCandidat);
                $managerOffre = new ManagerOffre();
                $offres       = $managerOffre->lister(null);
                if (!empty($offres)) {
                    foreach ($offres as $offre) {
                        $manager = new ManagerCandidature();
                        $candidature = $manager->chercher(['idOffre' => $offre->getIdOffre(), 'idCandidat' => $candidat->getIdCandidat()]);
                        if (empty($candidature)) {
                            if ($offre->getDateLimite() >= date('Y-m-d')) {
                                $profilOffre = $managerOffre->specifierProfilOffre(['idOffre' => $offre->getIdOffre()]);
                                if (!empty($experiences)) {
                                    foreach ($experiences as $experience) {
                                        if ($profilOffre['experience']['sousDomaine'] == $experience['sousDomaine'] && $profilOffre['experience']['annee'] <= $experience['annee']) {
                                            echo "test"; exit();
                                            $resultats[] = $this->voirDetailOffre(['idOffre' => $offre->getIdOffre()]);
                                        }
                                    }
                                } else if (!empty($diplomes)) {
                                    foreach ($diplomes as $diplome) {
                                        if ($profilOffre['diplome']['domaine'] == $diplome['domaine'] && $profilOffre['diplome']['niveau'] <= $diplome['niveau']) {
                                            $resultats[] = $this->voirDetailOffre(['idOffre' => $offre->getIdOffre()]);
                                        }
                                    }
                                }
                            }
                        }
                        
                    }
                }                
                return $this->trierOffres($candidat, $resultats);
            }
            return "";*/
            $resultats      = Array();
            $manager        = new ManagerCandidat();
            $candidat       = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $profilCandidat = $manager->specifierProfilCandidat(['idCandidat' => $candidat->getIdCandidat()]);
                extract($profilCandidat);
                $managerOffre   = new ManagerOffre();
                $offres         = $managerOffre->lister(null);
                if (!empty($offres)) {
                    foreach ($offres as $offre) {
                        $manager        = new ManagerCandidature();
                        $candidature    = $manager->chercher(['idOffre' => $offre->getIdOffre(), 'idCandidat' => $candidat->getIdCandidat()]);
                        if (empty($candidature)) {
                            if ($offre->getDateLimite() >= date('Y-m-d')) {
                                $profilOffre = $managerOffre->specifierProfilOffre(['idOffre' => $offre->getIdOffre()]);
                                if (!empty($experiences)) {
                                    foreach ($experiences as $experience) {
                                        if ($profilOffre['experience']['poste'] == $experience['poste'] && $profilOffre['experience']['annee'] <= $experience['annee']) {
                                            $resultats[] = $this->voirDetailOffre(['idOffre' => $offre->getIdOffre()]);
                                        }
                                    }
                                } 
                                if (!empty($formations)) {
                                    foreach ($formations as $formation) {
                                        if ($profilOffre['formation']['domaine'] == $formation['domaine'] && $profilOffre['formation']['niveau'] <= $formation['niveau']) {
                                            $resultats[] = $this->voirDetailOffre(['idOffre' => $offre->getIdOffre()]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $result     = $this->trierOffres($candidat, $resultats);
                $responses  = [];
                foreach ($result as $vals) {
                    if (!empty($vals["offre"])){
                        $responses[] = $vals["offre"];
                    }
                }
            }
            return $responses;
        }

        /**
         * Trier les offres correspondantes à un candidat
         *
         * @param object $candidat Le candidat concerné
         * @param array $offre Les offres correspondantes
         *
         * @return array
        */
        public function trierOffres($candidat, $offres)
        {
            $resultats = array();
            $manager   = new ManagerCandidat();
            $tabPerso  = $manager->getPersonnalitesCandidat(['idCandidat' => $candidat->getIdCandidat()]);
            if (!empty($offres)) {
                foreach ($offres as $offre) {
                    $manager       = new ManagerOffre();
                    $personnalites = $manager->getPersonnalitesOffre(['idOffre' => $offre['offre']->getIdOffre()]);
                    $length        = count(array_intersect($tabPerso, $personnalites));
                    $resultats[]   = [
                        'priori'      => $length, 
                        'offre'       => $offre
                    ];
                }
            }
            if (!empty($resultats)) {
                // Tri d'un tableau à partir d'une de ses clés
                foreach ($resultats as $key => $row) {
                    $priori[$key]  = $row['priori'];
                }            
                array_multisort($priori, SORT_DESC, $resultats);
            }
            return $resultats;
        }

        /** 
         * Lister les candidats suggérés à une offre
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
        */
        public function listerSuggestCandidats($parameters)
        {
            $resultats    = array();
            $manager      = new ManagerOffre();
            $offre        = $manager->chercher($parameters);
            $profilOffre  = $manager->specifierProfilOffre(['idOffre' => $parameters['idOffre']]);
            $manager      = new ManagerCompte();
            $compteActifs = $manager->lister(['identifiant' => 'candidat', 'statut' => 'active']);
            if (!empty($compteActifs) && !empty($offre)) {
                foreach ($compteActifs as $actif) {
                    $manager         = new ManagerCandidat();
                    $candidat        = $manager->chercher(['idCompte' => $actif->getIdCompte()]);
                    $profilsCandidat = $manager->specifierProfilCandidat(['idCandidat' => $candidat->getIdCandidat()]);
                    $manager         = new ManagerCandidature();
                    $candidature     = $manager->chercher(['idCandidat' => $candidat->getIdCandidat(), 'idOffre' => $offre->getIdOffre()]);
                    if (!empty($profilsCandidat) && $candidat->getPublique() == 1 && empty($candidature)) {
                        $experienceCandidat = "";
                        $formationCandidat  = "";
                        // $diplomeCandidat    = "";
                        extract($profilsCandidat);
                        if (!empty($experiences)) {
                            foreach ($experiences as $experience) {
                                $manager = new ManagerEntreprisePoste();
                                $poste   = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                                if ($experience['poste'] == $poste->getPoste() && $experience['annee'] >= $profilOffre['experience']['annee'] ) {
                                // if ($experience['sousDomaine'] == $profilOffre['experience']['sousDomaine'] /*&& $experience['annee'] >= $profilOffre['experience']['annee']*/ ) {
                                    $experienceCandidat = $experience;
                                    break;
                                }
                            }
                        }
                        if (!empty($formations)) {
                            foreach ($formations as $formation) {
                                if ($formation['domaine'] == $profilOffre['formation']['domaine'] && $formation['niveau'] >= $profilOffre['formation']['niveau']) {
                                    $formationCandidat = $formation;
                                    break;
                                } else if (!empty($experienceCandidat) && $formation['domaine'] == $profilOffre['formation']['domaine']) {
                                    $formationCandidat = $formation;
                                    break;
                                }
                            }
                        } 
                        if (!empty($experienceCandidat) || !empty($formationCandidat)) {
                            $resultats[] = [
                                'candidat'      => $candidat, 
                                'experience'    => $experienceCandidat,
                                'formation'     => $formationCandidat
                                // 'diplome'    => $diplomeCandidat
                            ];
                        }
                    }
                }
            }
            return $this->trierCandidats($offre, $resultats);
        }

        /**
         * Trier les candidats correspondants à une offre
         * 
         * @param objet $offre L'offre concerné
         * @param array $candidats Les candidats correspondants
         *
         * @return array
        */
        public function trierCandidats($offre, $candidats)
        {
            $resultats = array();
            $manager   = new ManagerCandidat();
            if (!empty($candidats)) { 
                foreach ($candidats as $candidat) {
                    $length = $candidat['formation']['niveau'];
                    if (!empty($candidat['experience'])) {
                        $niveauExperience = (int)$candidat['experience']['annee'];
                     } else {
                        $niveauExperience = 0;
                     }
                    $resultats[] = [
                        'priori'   => $length, 
                        'candidat' => $candidat, 
                        'niveau'   => $niveauExperience
                    ];
                }
            } 
            if (!empty($resultats)) {
                // Tri d'un tableau à partir d'une de ses clés
                foreach ($resultats as $key => $row) {
                    $priori[$key] = $row['priori'];
                    $niveau[$key] = $row['niveau'];
                }
                $filtre = array_filter($niveau);
                if (!empty($filtre)) {
                    array_multisort($niveau, SORT_DESC, $resultats);
                } else {
                    array_multisort($priori, SORT_DESC, $resultats);
                }
                
            }
            return $resultats;
        }

        /** 
         * Lister les interlocuteurs
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
        */
        public function listerInterlocuteurs()
        {
            $resultats      = array();
            $manager        = new ManagerInterlocuteur();
            return $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
        }

        /** 
         * Afficher la formulaire d'un domaine
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Objet
        */
        public function afficherFormDomaine($parameters)
        {
            $manager = new ManagerDomaine();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        }

        /** 
         * Afficher la formulaire d'un sous domaine
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Objet
        */
        public function afficherFormSousdomaine($parameters)
        {
            $manager = new ManagerSousDomaine();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            }
        }

        /** 
         * Afficher la formulaire d'une experience
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return array
        */
        public function afficherFormExperience($parameters)
        {
            $manager  = new ManagerCandidat();
            $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager  = new ManagerExperience();
            if (isset($parameters)) {
                $experience = $manager->chercher($parameters);
            } else {
                $experience = $manager->initialiser();
            }
            $manager      = new ManagerSousDomaine();
            $sousDomaines = $manager->lister(null);
            $manager      = new ManagerDomaine();
            $domaines     = $manager->lister(null);
            return [
                'candidat'     => $candidat, 
                'experience'   => $experience, 
                'sousDomaines' => $sousDomaines,
                'domaines'     => $domaines
            ];
        }

        /** 
         * Afficher la formulaire d'une formation
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return array
        */
        public function afficherFormFormation($parameters)
        {
            $manager  = new ManagerCandidat();
            $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager  = new ManagerFormation();
            if (isset($parameters)) {
                $formation = $manager->chercher($parameters);
            } else {
                $formation = $manager->initialiser();
            }
            $manager      = new ManagerSousDomaine();
            $sousDomaines = $manager->lister(null);
            $manager      = new ManagerDomaine;
            $domaines     = $manager->lister(null);
            return [
                'candidat'     => $candidat, 
                'formation'    => $formation, 
                'sousDomaines' => $sousDomaines,
                'domaines'     => $domaines
            ];
        }

        /** 
         * Afficher la formulaire d'une diplôme
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return array
        */
        public function afficherFormDiplome($parameters)
        {
            $manager  = new ManagerCandidat();
            $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager  = new ManagerDiplome();
            if (isset($parameters)) {
                $diplome = $manager->chercher($parameters);
            } else {
                $diplome = $manager->initialiser();
            }
            $manager       = new ManagerDomaine();
            $domaines      = $manager->lister();
            $manager       = new ManagerNiveauEtude();
            $niveauxEtudes = $manager->lister();
            return [
                'candidat'      => $candidat, 
                'diplome'       => $diplome, 
                'domaines'      => $domaines, 
                'niveauxEtudes' => $niveauxEtudes];
        }

        /** 
         * Afficher la formulaire d'un niveau d'expérience
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return object
        */
        public function afficherFormNiveauExperience($parameters)
        {
            $manager  = new ManagerNiveauExperience();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        }

        /** 
         * Afficher la formulaire d'un niveau d'étude
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
        */
        public function afficherFormNiveauEtude($parameters)
        {
            $manager  = new ManagerNiveauEtude();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        }

        /** 
         * Afficher la formulaire d'un contrat
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
        */
        public function afficherFormContrat($parameters)
        {
            $manager  = new ManagerContrat();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        }

        /** 
         * Afficher la formulaire d'une personnalite
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
        */
        public function afficherFormPersonnalite($parameters)
        {
            $manager  = new ManagerPersonnalite();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        }

        /** 
         * Afficher la formulaire d'un service
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
        */
        public function afficherFormService($parameters)
        {
            $manager  = new ManagerService();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        }

        /** 
         * Afficher la formulaire d'un service d'une entreprise
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
        */
        public function afficherFormEntrepriseService($parameters)
        {
            $resultat    = array();
            $donnees     = array();
            $manager     = new ManagerService();
            $allServices = $manager->lister();
            $services    = array();
            $manager     = new ManagerEntreprise();
            $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager     = new ManagerEntrepriseService();
            $action      = $parameters['action'];
            unset($parameters['action']);
            if (!empty($entreprise)) {
                if (isset($parameters)) {
                    $entrepriseService = $manager->chercher($parameters);
                    if ($entrepriseService != null && $action != null) {
                        echo json_encode($entrepriseService->toArray());
                        exit();
                    }
                } else {
                    $entrepriseService = $manager->initialiser();
                } 
                $resultat = [
                    "entreprise"        => $entreprise,
                    "allServices"       => $allServices,
                    "entrepriseService" => $entrepriseService
                ];
            }
            return $resultat;
        }

        /** 
         * Afficher la formulaire d'un poste d'une entreprise
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
        */
        public function afficherFormEntreprisePoste($parameters)
        {
            $manager            = new ManagerPoste();
            $allPostes          = $manager->lister();
            $manager            = new ManagerEntreprise();
            $entreprise         = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager            = new ManagerNiveauEtude();
            $niveauxEtudes      = $manager->lister();
            $manager            = new ManagerNiveauExperience();
            $niveauxExperiences = $manager->lister();
            $manager            = new ManagerEntreprisePoste();
            $postes             = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (isset($parameters)) {
                $entreprisePoste = $manager->chercher($parameters);
            } else {
                $entreprisePoste = $manager->initialiser();
            } 
            $manager            = new ManagerMission();
            $missions           = $manager->lister(['idEntreprisePoste' => $entreprisePoste->getIdEntreprisePoste()]);
            return [
                "entreprise"         => $entreprise,
                "allPostes"          => $allPostes,
                "postes"             => $postes,
                "missions"           => $missions,
                "niveauxEtudes"      => $niveauxEtudes,
                "entreprisePoste"    => $entreprisePoste,
                "niveauxExperiences" => $niveauxExperiences
            ];
        }

        /** 
         * Afficher la formulaire d'un poste d'un service
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
        */
        public function afficherFormServicePoste($parameters)
        {
            $manager = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $action     = $parameters['action'];
            unset($parameters['action']);          
            $manager = new ManagerServicePoste();
            if (isset($parameters)) {
                $servicePoste = $manager->chercher($parameters);
                if ($servicePoste != null && $action != null) {
                    echo json_encode($servicePoste->toArray());
                    exit();
                }
            } else {
                $servicePoste = $manager->initialiser();
            } 
            $manager   = new ManagerEntreprisePoste();
            $allPostes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $entreprisePoste = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
            return [
                "entreprisePoste" => $entreprisePoste,
                "servicePoste"    => $servicePoste,
                "allPostes"       => $allPostes
            ];
        } 

        /** 
         * Afficher la formulaire d'un poste
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
        */
        public function afficherFormPoste($parameters)
        {
            $manager  = new ManagerPoste();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        }

        /** 
         * Afficher la formulaire d'une offre
         *
         * @param array $parameters Les données à récupérer
         * 
         * @return array 
        */
        public function afficherFormOffre($parameters)
        {
            if (array_key_exists('addMenuUser',$_SESSION['compte'])) {
                if ($_SESSION['compte']['addMenuUser'] === 'YES') {
                    $chearcheArray = array('idEntreprise' => $_SESSION['user']['idEntreprise']);
                } else {
                    return;
                }
            } else {
                $chearcheArray = array('idCompte' => $_SESSION['compte']['idCompte']);
            }
            $manager                    = new ManagerEntreprise();
            $entreprise                 = $manager->chercher($chearcheArray);
            $manager                    = new ManagerOffre();
            if (isset($parameters)) {
                $offre = $manager->chercher($parameters);
            } else {
                $offre = $manager->initialiser();
            }
             $manager            = new ManagerSousDomaine();
            $sousDomaines       = $manager->lister(null);
            $manager            = new ManagerDomaine();
            $domaines           = $manager->lister();
            $manager            = new ManagerContrat();
            $contrats           = $manager->lister();
            /*$manager            = new ManagerNiveauExperience();
            $niveauxExperiences = $manager->lister();
            $manager            = new ManagerNiveauEtude();
            $niveauxEtudes      = $manager->lister();*/
            $manager            = new ManagerPersonnalite();
            $personnalites      = $manager->lister();
            $manager            = new ManagerEntreprisePoste();
            $postes             = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            return [ 
                'entreprise'         => $entreprise, 
                'offre'              => $offre, 
                'sousDomaines'       => $sousDomaines,
                'domaines'           => $domaines, 
                'contrats'           => $contrats, 
                /*'niveauxExperiences' => $niveauxExperiences, 
                'niveauxEtudes'      => $niveauxEtudes,*/
                'personnalites'      => $personnalites,
                'postes'             => $postes
            ];          
        }

        /** 
         * Afficher la formulaire d'une entretien
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Objet
        */
        public function afficherFormEntretien($parameters)
        {
            $resultat          = array();
            $entretien         = "";
            if (array_key_exists('addMenuUser',$_SESSION['compte'])) {
                if ($_SESSION['compte']['addMenuUser'] === 'YES') {
                    $chearcheArray = array('idEntreprise' => $_SESSION['user']['idEntreprise']);
                } else {
                    return;
                }
            } else {
                $chearcheArray = array('idCompte' => $_SESSION['compte']['idCompte']);
            }
            $manager           = new ManagerEntreprise();
            $entreprise        = $manager->chercher($chearcheArray);
            $manager           = new ManagerNiveauEntretien();
            $niveauxEntretiens = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $minOrdre          = $manager->getMinOrdre($entreprise->getIdEntreprise());
            extract($minOrdre);
            $niveauEntretien   = $manager->chercher(['ordre' => $minOrdre]);
            if (!empty($niveauEntretien)) {
                    $_SESSION['variable']['idNiveauEntretien'] = $niveauEntretien->getIdNiveauEntretien();
            }
            $manager = new ManagerEntretien();
            if (isset($parameters['idEntretien'])) {
                $entretien = $manager->chercher($parameters);
            } else {
                if (isset($parameters['idCandidature'])) {
                    $_SESSION['variable']['idCandidature'] = $parameters['idCandidature'];
                }
                $entretien = $manager->initialiser();
            } 
            if (!empty($niveauxEntretiens)) {
                $resultat = [
                    "niveauxEntretiens" => $niveauxEntretiens,
                    "entretien"         => $entretien
                ];
            }
            return $resultat;
        }

        /** 
         * Afficher la formulaire d'un niveau entretien
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return array
        */
        public function afficherFormNiveauEntretien($parameters)
        {
            $niveauEntretien = "";
            $manager         = new ManagerEntreprise();
            $entreprise      = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]); 
            $manager         = new ManagerNiveauEntretien();
            if (isset($parameters)) {
                $niveauEntretien = $manager->chercher($parameters);
            } else {
                $niveauEntretien = $manager->initialiser();
            } 
            return [
                    'entreprise'      => $entreprise,
                    'niveauEntretien' => $niveauEntretien
            ];
        } 

        /** 
         * Afficher la formulaire d'un interlocuteur
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Objet
        */
        public function afficherFormInterlocuteur($parameters)
        {
            $manager = new ManagerInterlocuteur();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            }
        } 

        /** 
         * Mettre à jour un domaine
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourDomaine($parameters)
        {
            $manager = new ManagerDomaine();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Mettre à jour un sous domaine
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourSousdomaine($parameters)
        {
            $manager = new ManagerSousDomaine();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Vérifier l'éxistance du parametre sous domaine
         * 
         * @param array $parameters Les paramètres à vérifier
         *
         * @return array 
        */
        private function verifierParamsSousDomaine($parameters)
        {
            $sousDomaine = "";
            $idDomaine   = "";
            if ($parameters['idSousDomaine'] == "autre" && $parameters['nomSousDomaine'] != "") {
                $domaine   = "";
                $idDomaine = $parameters['idDomaine'];
                if ($idDomaine == "autre" && $parameters['nomDomaine'] != "") {
                    $manager = new ManagerDomaine();
                    $domaine = $manager->chercher(['nomDomaine' => $parameters['nomDomaine']]);
                    if (empty($domaine)) {
                        $domaine = $manager->ajouter(['nomDomaine' => $parameters['nomDomaine']]);
                    }
                    $idDomaine = $domaine->getIdDomaine();
                }
                $manager     = new ManagerSousDomaine();
                $sousDomaine = $manager->chercher(['nomSousDomaine' => $parameters['nomSousDomaine']]);
                if (empty($sousDomaine)) {
                    $sousDomaine = $manager->ajouter(['idDomaine'      => $idDomaine, 
                                                      'nomSousDomaine' => $parameters['nomSousDomaine']]
                                                    );
                }
                $parameters['idSousDomaine'] = $sousDomaine->getIdSousDomaine();         
            }             
            unset($parameters['nomSousDomaine']);
            unset($parameters['idDomaine']);
            unset($parameters['nomDomaine']);
            return $parameters;
        }

        /** 
         * Mettre à jour une experience
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourExperience($parameters)
        {
            $parameters = $this->verifierParamsSousDomaine($parameters);
            $manager    = new ManagerExperience();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour une formation
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourFormation($parameters)
        {
            $parameters = $this->verifierParamsSousDomaine($parameters);
            $manager    = new ManagerFormation();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Vérifier l'éxistance du parametre domaine
         * 
         * @param array $parameters Les paramètres à vérifier
         *
         * @return array 
        */
        private function verifierParamsDomaine($parameters)
        {
            $domaine = "";
            if ($parameters['idDomaine'] == "autre" && $parameters['nomDomaine'] != "") {
                $manager = new ManagerDomaine();
                $domaine = $manager->chercher(['nomDomaine' => $parameters['nomDomaine']]);
                if (empty($domaine)) {
                    $domaine = $manager->ajouter(['nomDomaine' => $parameters['nomDomaine']]);
                }
                $parameters['idDomaine'] = $domaine->getIdDomaine();    
            }
            unset($parameters['nomDomaine']);
            return $parameters;
        }

        /** 
         * Vérifier l'éxistance du parametre d'un niveau d'étude
         * 
         * @param array $parameters Les paramètres à vérifier
         *
         * @return array 
        */
        private function verifierParamsNiveauEtude($parameters)
        {
            $niveauEtude = "";
            if ($parameters['idNiveauEtude'] == "autre" && $parameters['niveau'] != "") {
                $manager = new ManagerNiveauEtude();
                $niveauEtude = $manager->chercher(['niveau' => $parameters['niveau']]);
                if (empty($niveauEtude)) {
                    $niveauEtude = $manager->ajouter(['niveau' => $parameters['niveau']]);
                }
                $parameters['idNiveauEtude'] = $niveauEtude->getIdNiveauEtude();      
            }
            unset($parameters['niveau']);
            return $parameters;
        }

        /** 
         * Vérifier l'éxistance du parametre contrat
         * 
         * @param array $parameters Les paramètres à vérifier
         *
         * @return array 
        */
        private function verifierParamsContrat($parameters)
        {
            $contrat = "";
            if ($parameters['idContrat'] == "autre" && $parameters['designation'] != "") {
                $manager = new ManagerContrat();
                $contrat = $manager->chercher(['designation' => $parameters['designation']]);
                if (empty($contrat)) {
                    $contrat = $manager->ajouter(['designation' => $parameters['designation']]);
                }
                $parameters['idContrat'] = $contrat->getIdContrat();      
            }
            unset($parameters['designation']);
            return $parameters;
        }

        /** 
         * Vérifier l'éxistance du parametre d'un niveau d'expérience
         * 
         * @param array $parameters Les paramètres à vérifier
         *
         * @return array 
        */
        private function verifierParamsNiveauExperience($parameters)
        {
            $niveauExperience = "";
            if ($parameters['idNiveauExperience'] == "autre" && $parameters['niveauExperience'] != "") {
                $manager = new ManagerNiveauExperience();
                $niveauExperience = $manager->chercher(['niveau' => $parameters['niveauExperience']]);
                if (empty($niveauExperience)) {
                    if (is_numeric($parameters['niveauExperience']) && $parameters['niveauExperience'] != "0") {
                        $parameters['niveauExperience'] = $parameters['niveauExperience'] . " ans";
                    }
                    $niveauExperience = $manager->ajouter(['niveau' => $parameters['niveauExperience']]);
                }
                $parameters['idNiveauExperience'] = $niveauExperience->getIdNiveauExperience();      
            }
            unset($parameters['niveauExperience']);
            return $parameters;
        }

        /** 
         * Mettre à jour un diplôme
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourDiplome($parameters)
        {
            $parameters = $this->verifierParamsDomaine($parameters);
            $parameters = $this->verifierParamsNiveauEtude($parameters);
            $manager = new ManagerDiplome();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Mettre à jour un niveau d'expérience
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourNiveauExperience($parameters)
        {
            $manager = new ManagerNiveauExperience();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Mettre à jour un niveau d'étude
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourNiveauEtude($parameters)
        {
            $manager = new ManagerNiveauEtude();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Mettre à jour un contrat
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourContrat($parameters)
        {
            $manager = new ManagerContrat();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Mettre à jour une personnalite
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourPersonnalite($parameters)
        {
            $manager = new ManagerPersonnalite();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Mettre à jour un service
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourService($parameters)
        {
            $parameters['nomService'] = strtolower($parameters['nomService']);
            $manager = new ManagerService();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour un poste
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourPoste($parameters)
        {
            $parameters['nomPoste'] = strtolower($parameters['nomPoste']);
            $manager = new ManagerPoste();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Mettre à jour une offre
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourOffre($parameters)
        {
            $parameters = $this->verifierParamsSousDomaine($parameters);
            $parameters = $this->verifierParamsContrat($parameters); 
            /*$parameters = $this->verifierParamsNiveauExperience($parameters);
            $parameters = $this->verifierParamsNiveauEtude($parameters);*/
            if (isset($parameters['autreQualite'])) {
                unset($parameters['autreQualite']);
            }
            $manager = new ManagerEntreprisePoste();
            $poste = $manager->chercher(['idEntreprisePoste' => $parameters['idEntreprisePoste']]);
            $parameters['idNiveauEtude'] = $poste->getIdNiveauEtude();
            $parameters['idNiveauExperience'] = $poste->getAnneeExperienceExterne();
            if (!empty($parameters['autrePersonnalite'])) {
                $qualites = explode("_", $parameters['autrePersonnalite']);
                foreach ($qualites as $qualite) {
                    if (!empty($qualite)) {
                        $manager = new ManagerPersonnalite();
                        $search  = $manager->chercher(['qualite' => $qualite]);
                        if (empty($search)) {
                            $manager->ajouter(['qualite' => ucfirst($qualite)]);
                        }
                    }
                }
            }
            unset($parameters['autrePersonnalite']);
            unset($parameters['qualite']);
            $referer    = $_SERVER["HTTP_REFERER"];
            $notice     =  explode('/', $referer);
            $folderPath = DOC_ROOT. 'Ressources/images/offre/';
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            } 
            foreach($_FILES as $field => $value){
                if(!empty($_FILES[$field]['name'])){
                    $fieldName  = $field . "_" . time() . ".png";
                    $target     = DOC_ROOT. 'Ressources/images/offre' . basename($_FILES[$field]['name']);
                    move_uploaded_file( $_FILES[$field]['tmp_name'], $target);
                    rename($target, $folderPath . $fieldName);
                    $parameters[$field] = $fieldName;
                    $parameters['couvertureOffre'] = str_replace("C:\\fakepath\\" . $_FILES[$field]['name'], $fieldName, $parameters['couvertureOffre']);
                }
            }
            $manager = new ManagerOffre();
            if (reset($parameters) == "") {
                $this->listerSuggestOffresCandidats($parameters);
                return $manager->ajouter($parameters);
            } else {
                // $this->listerSuggestOffresCandidats($parameters);
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour un entretien
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourEntretien($parameters)
        {
            $entretien = "";
            $action    = "";
            $manager   = new ManagerEntretien();
            if (reset($parameters) == "") {
                if (isset($parameters['date'])) {
                    $date = DateTime::createFromFormat("d/m/Y", $parameters['date']);
                    $formatted_date = $date->format("Y-m-d");
                }
                $parameters['date']  = $formatted_date;
                $action               = "ajouter";
                $entretien = $manager->ajouter($parameters);
                $_SESSION['info']['success'] = "Entretien fixé avec succès";
            } else {
                $action    = "modifier";
                $entretien = $manager->chercher(['idEntretien' => $parameters['idEntretien']]);
                if (!empty($parameters['idNiveauEntretien'])/* && $entretien->getIdNiveauEntretien() != $parameters['idNiveauEntretien']*/) {
                    if (isset($parameters['date'])) {
                        $date = DateTime::createFromFormat("d/m/Y", $parameters['date']);
                        $formatted_date = $date->format("Y-m-d");
                    }
                    $parameters['statut']  = "en attente";
                    $parameters['date']  = $formatted_date;
                    // $parameters['nbFois'] += 1;
                }
                $manager->modifier($parameters);
                $entretien = $manager->chercher(['idEntretien' => $parameters['idEntretien']]);
            }
            $this->envoyerEmailEntretien($entretien, $action);
            return $entretien;
        } 

        private function getInterlocuteurs($parameters)
        {
            $interlocuteurs = array();
            $manager = new ManagerInterlocuteurNiveauEntretien();
            $responses = $manager->lister($parameters);
            if (!empty($responses)) {
                foreach ($responses as $response) {
                    $manager = new ManagerInterlocuteur();
                    $interlocuteurs[] = $manager->chercher(['idInterlocuteur' => $response->getIdInterlocuteur()]);
                }
            }
            return $interlocuteurs;
        }

        /** 
         * Envoyer un email sur un entretien 
         *
         * @param object $entretien la candidature concernée
         * @param string $action l'action faite sur l'entretien
         *
         * @return empty
        */
        private function envoyerEmailEntretien($entretien, $action)
        {
            $to             = "";
            $subject        = "";
            $message        = "";
            $headers        = "";
            $nbFois         = "";
            $manager        = new ManagerCandidature();
            $candidature    = $manager->chercher(["idCandidature" => $entretien->getIdCandidature()]);
            $manager        = new ManagerNiveauEntretien();
            $nivEntretien   = $manager->chercher(["idNiveauEntretien" => $entretien->getIdNiveauEntretien()]);
            if ($nivEntretien) {
                $interlocuteurs = $this->getInterlocuteurs(['idNiveauEntretien' => $nivEntretien->getIdNiveauEntretien()]);
            }
            $manager        = new ManagerCandidat();
            $candidat       = $manager->chercher(["idCandidat" => $candidature->getIdCandidat()]);
            $manager        = new ManagerOffre();
            $offre          = $manager->chercher(["idOffre" => $candidature->getIdOffre()]);
            $manager        = new ManagerEntreprise();
            $entreprise     = $manager->chercher(["idEntreprise" => $offre->getIdEntreprise()]);
            $manager        = new ManagerEmailContact();
            $emails         = $manager->lister();
            $cvCandidat     = $this->voirDetailCvCandidat(["idCandidat" => $candidat->getIdCandidat()]);
            if ($entretien->getStatut() == "en attente") {
                if (!empty($emails)) {
                    if ($entretien->getNbFois() > 1) {
                        $nbFois = $entretien->getNbFois() ."ème";
                        $texte  = "Nous avons le plaisir de vous informer que votre premier entretien s'est bien passé";
                    } else {
                        $nbFois = $entretien->getNbFois() . "er";
                        $texte  = "Nous avons le plaisir de vous informer que votre qualification s'est bien passé.";
                    }
                    $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                    $dateEntretien = explode('-', $entretien->getDate());
                    $index = (int)$dateEntretien[1] - 1;
                    $date = $dateEntretien[2] . " " . $months[$index] . " " . $dateEntretien[0] ;
                    foreach ($emails as $email) {
                        if ($email->getType() == "information") {                            
                            if ($_GET['page'] != "manage/cancel-entretien") {
                                $subject = "Réponse d'une candidature envoyée";
                                $message = "<html><body>
                                                <div class='container'>
                                                    <label>Bonjour " . ucwords($candidat->getPrenom()) . ", </label><br>
                                                    <label></label><br> " . $texte . " <br><br>
                                                    <label>
                                                        La société " . $entreprise->getNom() . " vous donne un rendez-vous pour un " . $nbFois . " entretien ce " . $date . " prochain à " . $entretien->getHeure() . "<br> au " .
                                                        $entretien->getLieu() . ".
                                                    </label><br><br>";
                                if (!empty($interlocuteurs)) {
                                    foreach ($interlocuteurs as $interlocuteur) {
                                        $collaborateurs .= $interlocuteur->getCivilite() . " " . ucwords($interlocuteur->getNom()) . ", ";
                                    }
                                    $message .= "<label>
                                                    Votre entretien sera avec " . substr($collaborateurs, 0, -2) . "
                                                </label><br><br>";
                                }
                                $message .= "<label>Cordialement, </label><br><br>
                                            <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label></div></body></html>";
                                $to = $candidat->getEmail();                            
                                $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                                mail($to, $subject, $message, $headers);
                                if (!empty($interlocuteurs)) {
                                    $subject = "Information pour un entretien";
                                    $headers = "Content-type: text/html" . "\r\n" . "From: " . $entreprise->getEmail();
                                    foreach ($interlocuteurs as $interlocuteur) {
                                        $to      = $interlocuteur->getEmail();
                                        $message = "<html><body>
                                                    <div class='container'>
                                                        <label>Bonjour</label><br><br>
                                                        <label>" .
                                                            $interlocuteur->getCivilite() . " " . ucwords($interlocuteur->getNom()) . ", on vous informe que vous recevrez un entretien <br>
                                                            avec " . $candidat->getCivilite() . " " . strtoupper($candidat->getNom()) . " " . ucwords($candidat->getPrenom()) . " ce " .
                                                            date("d/m/Y", strtotime(str_replace("-", "/", $entretien->getDate()))) . " à " . $entretien->getHeure() . ".
                                                        </label><br><br>
                                                        <label>
                                                          Merci de noter votre entretien.
                                                        </label><br>
                                                        <label>Cordialement, </label><br><br>
                                                    </div>
                                                </body></html>";
                                        mail($to, $subject, $message, $headers);
                                    }
                                }
                            } else {
                                $subject = "Information sur un entretien";
                                $message = "<html><body>
                                                <div class='container'>
                                                    <label>Bonjour " . ucwords($candidat->getPrenom()) . ", </label><br><br>
                                                    <label>
                                                      Nous avons le plaisir de vous informer que le statut de votre entretien du " . date("d/m/Y", strtotime(str_replace("-", "/", $entretien->getDate()))) . " au  " .
                                                        $entretien->getLieu() . " <br> a été modifié par la société " . $entreprise->getNom() . "
                                                    </label><br><br>
                                                    <label>
                                                        Pour plus d'information, veuillez-vous connecter sur le site en cliquant <a href='" . HOST . "/connexion'>ici</a>
                                                    </label><br><br>
                                                    <label>Cordialement, </label><br><br>
                                                    <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                            </div></body></html>";
                                $to = $candidat->getEmail();                            
                                $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                                mail($to, $subject, $message, $headers);
                            }
                            
                        }
                    }
                }
            }
            else if ($entretien->getStatut() == "valide") {
                if (!empty($emails)) {
                    foreach ($emails as $email) {
                        if ($email->getType() == "information") {
                            $to = $candidat->getEmail();
                            $subject = "Réponse d'une entretien passée";
                            $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                            $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour " . ucwords($candidat->getPrenom()) . ", </label><br><br>
                                                <label>Bienvenu sur notre nouvelle plateforme.</label><br><br>
                                                <label>
                                                  Nous avons le plaisir de vous informer que votre entretien du " . date("d/m/Y", strtotime(str_replace("-", "/", $entretien->getDate()))) . " au <br>" .
                                                  $entretien->getLieu() . " a été validée par la société " . $entreprise->getNom() . "
                                                </label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label></div>
                                        </body></html>";
                            mail($to, $subject, $message, $headers);
                        }
                    }
                }
            }
            else if ($entretien->getStatut() == "rejete") {
                if (!empty($emails)) {
                    foreach ($emails as $email) {
                        if ($email->getType() == "information") {
                            $to = $candidat->getEmail();
                            $subject = "Réponse d'une entretien passée";
                            $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                            $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour " . ucwords($candidat->getPrenom()) . ", </label><br><br>
                                                <label>Bienvenu sur notre nouvelle plateforme.</label><br><br>
                                                <label>
                                                  Nous avons le regret de vous informer que votre entretien passée le " . date("d/m/Y", strtotime(str_replace("-", "/", $entretien->getDate()))) . " au <br>" .
                                                  $entretien->getLieu() . " a été rejetée par la société " . $entreprise->getNom() . "
                                                </label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label></div>
                                        </body></html>";
                            mail($to, $subject, $message, $headers);
                        }
                    }
                }
            }
        }

        /** 
         * Mettre à jour un niveau d'entretien
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourNiveauEntretien($parameters)
        {
            $manager = new ManagerNiveauEntretien();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour un service d'une entreprise
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourEntrepriseService($parameters)
        {
            unset($parameters['nomService']);
            $parameters['service'] = strtolower($parameters['service']);
            $manager = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($entreprise)) {
                $manager = new ManagerEntrepriseService();
                $exist = $manager->chercher(['service' => $parameters['service'], 'idEntreprise' => $entreprise->getIdEntreprise()]);
                if (empty($exist)) {
                    if (reset($parameters) == "") {
                        $manager->ajouter($parameters);
                        $_SESSION['info']['success'] = "Service ajouté avec succès";
                    } else {
                        $manager->modifier($parameters);
                        $_SESSION['info']['success'] = "Service modifié avec succès";
                    }
                } else {
                    $_SESSION['info']['danger'] = ucfirst($parameters['service']) . " est déjà dans la liste";
                }
            }
        } 

        /** 
         * Mise à jour de la table mission
         * 
         * @param array $idEntreprisePoste le poste des missions 
         * @param array $missions les missions 
         *
         * @return empty
        */
        private function mettreAjourMission($idEntreprisePoste, $missions)
        {
            $missions = explode('_', $missions);
            $manager = new ManagerMission();
            $tabNewMission = array();
            $currentMissions = $manager->lister(['idEntreprisePoste' => (int)$idEntreprisePoste]);
            if (!empty($missions)) {
                foreach ($missions as $mission) {
                    if (!empty($mission)) {
                        $mission = explode("/", $mission);
                        $parameters = [
                            'idMission'         => $mission[0],
                            'idEntreprisePoste' => (int)$idEntreprisePoste,
                            'description'       => $mission[1],
                            'niveau'            => $mission[2]
                        ];
                        $exist   = $manager->chercher(['idMission' => $mission[0]]);
                        if (empty($exist)) {
                            $manager->ajouter($parameters);
                        } else {
                            $manager->modifier($parameters);
                        }
                        $tabNewMission[] = $parameters['idMission'];
                    }
                }
            }
            if (!empty($currentMissions) && !empty($tabNewMission)) {
               foreach ($currentMissions as $currentMission) {
                    if (!in_array($currentMission->getIdMission(), $tabNewMission)) {
                       $manager->supprimer(['idMission' => $currentMission->getIdMission()]);
                   }
               }
           } else if (empty($tabNewMission) && !empty($currentMissions)) {
                foreach ($currentMissions as $currentMission) {
                    $manager->supprimer(['idMission' => $currentMission->getIdMission()]);
                }
           }        
        }

        /** 
         * Mettre à jour un poste d'une entreprise
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourEntreprisePoste($parameters)
        {
            $missions = $parameters['input_mission'];
            unset($parameters['nomPoste']);
            unset($parameters['input_mission']);
            if ($parameters['idNiveauEtude'] == "autre" && $parameters['niveau'] != "") {
                $manager = new ManagerNiveauEtude();
                $niveauEtude   = $manager->chercher(['niveau' => $parameters['niveau']]);
                if (empty($niveauEtude)) {
                    $niveauEtude = $manager->ajouter(['niveau' => $parameters['niveau']]);
                }
                $parameters['idNiveauEtude'] = $niveauEtude->getIdNiveauEtude();
            }
            if ($parameters['anneeExperienceInterne'] == "autre" && $parameters['niveauExperienceInterne'] != "") {
                $manager = new ManagerNiveauExperience();
                $niveauExperience = $manager->chercher(['niveau' => $parameters['niveauExperienceInterne']]);
                if (empty($niveauExperience)) {
                    $niveauExperience = $manager->ajouter(['niveau' => $parameters['niveauExperienceInterne']]);
                }
                 $parameters['anneeExperienceInterne'] = $niveauExperience->getIdNiveauExperience();
            }
            if ($parameters['anneeExperienceExterne'] == "autre" && $parameters['niveauExperienceExterne'] != "") {
                $manager = new ManagerNiveauExperience();
                $niveauExperience = $manager->chercher(['niveau' => $parameters['niveauExperienceExterne']]);
                if (empty($niveauExperience)) {
                    $niveauExperience = $manager->ajouter(['niveau' => $parameters['niveauExperienceExterne']]);
                }
                $parameters['anneeExperienceExterne'] = $niveauExperience->getIdNiveauExperience();
            }
            unset($parameters['niveauExperienceInterne']);
            unset($parameters['niveauExperienceExterne']);
            $parameters['poste'] = ucfirst($parameters['poste']);
            $manager = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($entreprise)) {
                $manager = new ManagerEntreprisePoste();
                if (reset($parameters) == "") {
                    $entreprisePoste = $manager->ajouter($parameters);
                    $_SESSION['info']['success'] = "Poste ajouté avec succès";
                } else {
                    $entreprisePoste = $manager->modifier($parameters);
                    $_SESSION['info']['success'] = "Poste modifié avec succès";
                }
            }
            $this->mettreAjourMission($entreprisePoste->getIdEntreprisePoste(), $missions);
            return $entreprisePoste;
        }

        /** 
         * Modifier le niveau d'entretien 
         * 
         * @param array $parameters Les données à modifier
         *
         * @return Object
        */
        public function modifierNiveauEntretien($parameters)
        {
            $resultat          = "";
            $manager           = new ManagerEntretien();
            $entretien         = $manager->chercher($parameters);
            $manager           = new ManagerEntreprise();
            $entreprise        = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager           = new ManagerNiveauEntretien();
            $niveauxEntretiens = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $nivEntretien      = $manager->chercher(['idNiveauEntretien' => $entretien->getIdNiveauEntretien()]);
            if (!empty($niveauxEntretiens)) {
                foreach ($niveauxEntretiens as $niveauEntretien) {
                    if ($nivEntretien->getOrdre() < $niveauEntretien->getOrdre()) {
                        $resultat = $niveauEntretien; break;
                    }
                }
            }
            return $resultat;
        }

        /** 
         * Mettre à jour un interlocuteur
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourInterlocuteur($parameters)
        {
            $manager = new ManagerInterlocuteur();
            if (reset($parameters) == "") {
                $interlocuteur = $manager->ajouter($parameters);
                if (isset($_SESSION['variable']['idNiveauEntretien'])) {
                    $attributes['idInterlocuteur'] = (int)$interlocuteur;
                    $attributes['idNiveauEntretien'] = $_SESSION['variable']['idNiveauEntretien'];
                    $manager = new ManagerInterlocuteurNiveauEntretien();
                    $manager->ajouter($attributes);
                }
            } else {
                $manager->modifier($parameters);
            }
        }

        /** 
         * Mettre à jour un interlocuteur d'un niveau d'entretien
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourInterlocuteurNiveauEntretien($parameters)
        {
            $parameters['idNiveauEntretien'] = $_SESSION['variable']['idNiveauEntretien'];
            $manager = new ManagerInterlocuteurNiveauEntretien();
            $data = $manager->chercher($parameters);
            if (empty($data)) {
                $manager->ajouter($parameters);
                $_SESSION['info']['success'] = "Interlocuteur ajouté avec succès";
            } else {
                $manager = new ManagerInterlocuteur();
                $interlocuteur = $manager->chercher(['idInterlocuteur' => $data->getIdInterlocuteur()]);
                $_SESSION['info']['danger'] = ucwords($interlocuteur->getNom()) . " est déjà dans la liste";
            }
        }

        /** 
         * Mettre à jour un poste d'un service
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourServicePoste($parameters)
        {
            $parameters['idEntrepriseService'] = $_SESSION['variable']['idEntrepriseService'];
            $manager = new ManagerServicePoste();
            $exist = $manager->chercher([
                'idEntrepriseService' => $parameters['idEntrepriseService'], 
                'idEntreprisePoste' => $parameters['idEntreprisePoste'],
                'idCategorieProfessionnelle' => self::NO
            ]);
            if (empty($exist)) {
                if (reset($parameters) == "") {
                    $manager->ajouter($parameters);
                    $_SESSION['info']['success'] = "Poste ajouté avec succès";
                } else {
                    $manager->modifier($parameters);
                    $_SESSION['info']['success'] = "Poste modifié avec succès";
                }
            } else {
                $_SESSION['info']['danger'] = "Le poste est déjà dans la liste";
            }
            
        }

        /** 
         * Voir le détail d'un domaine 
         * 
         * @param array $parameters Critères des données à voir 
         * 
         * @return array 
        */
        public function voirDetailDomaine($parameters)
        {
            $resultat       = array(); 
            $tabSousDomaine = array();
            $manager        = new ManagerDomaine();
            $domaine        = $manager->chercher($parameters);
            if (!empty($domaine)) {
                $manager = new ManagerSousDomaine();
                $sousDomaines = $manager->lister(['idDomaine' => $domaine->getIdDomaine()]);
                if (!empty($sousDomaines)) {
                    foreach ($sousDomaines as $sousDomaine) {
                        $tabSousDomaine[] = $sousDomaine;
                    }
                    $resultat = [
                        'domaine'      => $domaine, 
                        'sousDomaines' => $tabSousDomaine
                    ];
                } else {
                    $resultat = ['domaine' => $domaine];
                }
            }
            return $resultat;
        }

        /** 
         * Voir le détail d'une offre 
         * 
         * @param array $parameters Critères des données à voir  
         * 
         * @return array 
        */
        public function voirDetailOffre($parameters)
        {
            $resultat    = array(); 
            $manager     = new ManagerOffre();
            $offre       = $manager->chercher($parameters);
            $candidature = "";
            if (!empty($offre)) {
                $manager          = new ManagerEntreprise();
                $entreprise       = $manager->chercher(['idEntreprise' => $offre->getIdEntreprise()]);
                $manager          = new ManagerSousDomaine();
                $sousDomaine      = $manager->chercher(['idSousDomaine' => $offre->getIdSousDomaine()]);
                $manager          = new ManagerDomaine();
                $domaine          = $manager->chercher(['idDomaine' => $sousDomaine->getIdDomaine()]);
                $manager          = new ManagerContrat();
                $contrat          = $manager->chercher(['idContrat' => $offre->getIdContrat()]);
                $manager          = new ManagerNiveauExperience();
                $niveauExperience = $manager->chercher(['idNiveauExperience' => $offre->getIdNiveauExperience()]);
                $manager          = new ManagerNiveauEtude();
                $niveauEtude      = $manager->chercher(['idNiveauEtude' => $offre->getIdNiveauEtude()]);
                $manager          = new ManagerEntreprisePoste();
                $poste            = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                $manager          = new ManagerMission();
                $missions         = $manager->lister(['idEntreprisePoste' => $poste->getIdEntreprisePoste()]);
                $manager          = new ManagerCandidature();
                if ($_SESSION['compte']['identifiant'] == "candidat") {
                    $manager = new ManagerCandidat();
                    $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
                    $manager  = new ManagerCandidature();
                    $candidature = $manager->chercher(['idOffre'    => $offre->getIdOffre(),
                                                       'idCandidat' => $candidat->getIdCandidat()]);
                }
                $resultat         = [  
                    'offre'            => $offre, 
                    'entreprise'       => $entreprise, 
                    'sousDomaine'      => $sousDomaine, 
                    'domaine'          => $domaine, 
                    'contrat'          => $contrat, 
                    'niveauExperience' => $niveauExperience, 
                    'niveauEtude'      => $niveauEtude,
                    'candidature'      => $candidature,
                    'poste'            => $poste,
                    'missions'         => $missions
                ];
            }
            return $resultat;
        }

        /** 
         * Voir le détail d'un candidat suggéré 
         * 
         * @param array $parameters Critères des données à voir
         * 
         * @return array 
        */
        public function voirDetailCvCandidat($parameters)
        {
            $resultat = array(); 
            $candidat = "";
            $manager  = new ManagerCandidat();
            if (isset($parameters)) {
                $candidat = $manager->chercher($parameters);
            } else {
                $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            }
            if (!empty($candidat)) {
                $tabDiplome    = array();
                $tabFormation  = array();
                $tabExperience = array();
                $manager       = new ManagerFormation();
                $formations    = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                $manager       = new ManagerExperience();
                $experiences   = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                $manager       = new ManagerLogiciel();
                $logiciels     = $manager->lister(['id_candidat' => $candidat->getIdCandidat()]);
                $manager       = new ManagerLangue();
                $langues       = $manager->lister(['id_candidat' => $candidat->getIdCandidat()]);
                $manager       = new ManagerCentreInteret();
                $centreInterets   = $manager->lister(['id_candidat' => $candidat->getIdCandidat()]);
                if (!empty($formations)) {
                    foreach ($formations as $formation) {
                        $manager        = new ManagerSousDomaine();
                        $sousDomaine    = $manager->chercher(['idSousDomaine' => $formation->getIdSousDomaine()]);
                        $manager        = new ManagerNiveauEtude();
                        $niveauEtude    = $manager->chercher(['idNiveauEtude' => $formation->getIdNiveauEtude()]);
                        $tabFormation[] = [
                            'formation'   => $formation, 
                            'sousDomaine' => $sousDomaine,
                            'niveauEtude' => $niveauEtude
                        ];
                    }
                }
                $resultats = [
                    'candidat'          => $candidat, 
                    'formations'        => $tabFormation,
                    'experiences'       => $experiences,
                    'langues'           => $langues,
                    'logiciels'         => $logiciels,
                    'centreInterets'    => $centreInterets
              ];
            }
            return $resultats;
        }

        /** 
         * Voir le détail d'un niveau d'entretien 
         * 
         * @param array $parameters Critères des données à voir 
         * 
         * @return array 
        */
        public function voirDetailNiveauEntretien($parameters)
        {
            $resultat                   = array(); 
            $interlocuteursNivEntretien = "";
            $interlocuteurs             = array();
            $manager                    = new ManagerInterlocuteur();
            $allIntelocuteurs           = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager                    = new ManagerNiveauEntretien();
            $nivEntretien               = $manager->chercher($parameters);
            if (!empty($nivEntretien)) {
                $manager = new ManagerInterlocuteurNiveauEntretien();
                $interlocuteursNivEntretien = $manager->lister(['idNiveauEntretien' => $nivEntretien->getIdNiveauEntretien()]);
                if (!empty($interlocuteursNivEntretien)) {
                    foreach ($interlocuteursNivEntretien as $interlocuteurNivEntretien) {
                        $manager = new ManagerInterlocuteur();
                        $interlocuteur = $manager->chercher(['idInterlocuteur' => $interlocuteurNivEntretien->getIdInterlocuteur()]);
                        if (!empty($interlocuteur)) {
                            $interlocuteurs[] = [
                                'interlocuteur'            => $interlocuteur,
                                'interlocuteurNivEntretien' => $interlocuteurNivEntretien
                            ];
                        }
                    }
                }
                $resultat = [
                    "niveauEntretien"  => $nivEntretien,
                    "interlocuteurs"   => $interlocuteurs,
                    "allIntelocuteurs" => $allIntelocuteurs
                ];
            }
            return $resultat;
        }

        /** 
         * Voir le détail d'un service d'une entreprise
         * 
         * @param array $parameters Critères des données à voir 
         * 
         * @return array 
        */
        public function voirDetailEntrepriseService($parameters)
        {
            $resultat   = "";
            $tabPoste   = array();
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]); 
            $manager   = new ManagerEntreprisePoste();
            $allPostes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $manager = new ManagerEntrepriseService();
            $entrepriseService = $manager->chercher($parameters);
            if (!empty($entrepriseService)) {
                $manager = new ManagerServicePoste();
                $servicePostes = $manager->lister([
                    'idEntrepriseService' => $entrepriseService->getIdEntrepriseService(), 
                    'idCategorieProfessionnelle' => self::NO
                ]);
                if (!empty($servicePostes)) {
                    foreach ($servicePostes as $servicePoste) {
                        $manager = new ManagerEntreprisePoste();
                        $tabPoste[] = [
                            "entreprisePoste"  => $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]),
                            "servicePoste"     => $servicePoste
                        ];
                    }
                }
                $resultat = [
                    "entrepriseService" => $entrepriseService,
                    "entreprisePostes"  => $tabPoste,
                    "allPostes"         => $allPostes
                ];  
            } 
            return $resultat;
        }

        /** 
         * Voir le détail d'un poste d'une entreprise
         * 
         * @param array $parameters Critères des données à voir 
         * 
         * @return array 
        */
        public function voirDetailEntreprisePoste($parameters)
        {
            $resultat           = array();
            $tabMission         = array();
            $manager            = new ManagerEntreprisePoste();
            $entreprisePoste    = $manager->chercher($parameters);
            if (!empty($entreprisePoste)) {
                $evolution              = $manager->chercher(['idEntreprisePoste' => $entreprisePoste->getEvolution()]);
                $continuite             = $manager->chercher(['idEntreprisePoste' => $entreprisePoste->getContinuite()]);
                $manager                = new ManagerNiveauEtude();
                $niveauEtude            = $manager->chercher(['idNiveauEtude' => $entreprisePoste->getIdNiveauEtude()]);
                $manager                = new ManagerNiveauExperience();
                $anneeExperienceInterne = $manager->chercher(['idNiveauExperience' => $entreprisePoste->getAnneeExperienceInterne()]);
                $anneeExperienceExterne = $manager->chercher(['idNiveauExperience' => $entreprisePoste->getAnneeExperienceExterne()]);
                $manager                = new ManagerMission();
                $missions               = $manager->lister(['idEntreprisePoste' => $entreprisePoste->getIdEntreprisePoste()]);
                $resultat               = [
                    'missions'               => $missions,
                    'evolution'              => $evolution,
                    'continuite'             => $continuite,
                    'niveauEtude'            => $niveauEtude,
                    'entreprisePoste'        => $entreprisePoste,
                    'anneeExperienceInterne' => $anneeExperienceInterne,
                    'anneeExperienceExterne' => $anneeExperienceExterne
                ];
            }
            return $resultat;
        }

        /** 
         * Ajouter une candidature
         *
         * @return Object
        */
        public function mettreAJourCandidature($parameters)
        {
            $candidature    = "";
            $idCandidature  = "";
            $action         = "";
            $manager        = new ManagerCandidat();
            $candidat       = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $parameters['idCandidat'] = $candidat->getIdCandidat();
            }
            if (isset($parameters['idCandidature'])) {
                $manager        = new ManagerCandidature();   
                $manager->modifier($parameters);
                $candidature    = $manager->chercher(["idCandidature" => $parameters['idCandidature']]);
                $action = "modifier";
            } else {
                $manager                        = new ManagerOffre();
                // $parameters['id_entreprise']    = $manager->chercher(['idOffre'=>$parameters['idOffre']])->getIdEntreprise();
                $parameters['dateCandidature']  = date('Y-m-d');
                if ($_SESSION['compte']['identifiant'] == "candidat") {
                    $parameters['statut']       = "envoye";
                } else {
                    $parameters['statut']       = "qualification";
                }
                $manager                        = new ManagerCandidature();
                $manager->ajouter($parameters);
                $idCandidature                  = $manager->chercherDernierId();
                extract($idCandidature);
                $candidature                    = $manager->chercher(["idCandidature" => $id]);
                $action                         = "ajouter";
            }
            return $this->envoyerEmailCandidature($candidature, $action);
        } 

        /** 
         * Envoyer un email sur la candidature 
         *
         * @param object $candidature la candidature concernée
         * @param string $action l'action faite sur la candidature
         *
         * @return empty
        */
        private function envoyerEmailCandidature($candidature, $action)
        {   
            $subject    = "";
            $to         = "";
            $message    = "";
            $headers    = "";  
            $manager    = new ManagerCandidat();
            $candidat   = $manager->chercher(["idCandidat" => $candidature->getIdCandidat()]);
            $manager    = new ManagerOffre();
            $offre      = $manager->chercher(["idOffre" => $candidature->getIdOffre()]);
            $manager    = new ManagerEntreprisePoste();
            $poste      = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(["idEntreprise" => $offre->getIdEntreprise()]);
            $manager    = new ManagerEmailContact();
            $emails     = $manager->lister();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                    if ($email->getType() == "information") {
                        // if ($action == "ajouter") {
                        if ($candidature->getStatut() == "envoye") {
                            $to      = $candidat->getEmail();
                            $subject = "Candidature postée";
                            $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour " . strtoupper($candidat->getCivilite()). " " . strtoupper($candidat->getNom()) . " " . ucwords($candidat->getPrenom()) . " ,</label><br><br>
                                                <label>Votre candidature a bien été envoyée pour le poste de " . ucfirst($poste->getPoste()) .".</label><br><br>
                                                <br> " . ucfirst($entreprise->getNom()) .".</label><br><br>
                                                <label>Vous pouvez constater les détails sur notre site en cliquant <a href='" . HOST . "/connexion'>ici</a></label><br><br>
                                                <label>nous vous souhaitons une bonne continuation .</label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                            </div>
                                        </body></html>";
                            mail($to, $subject, $message, $headers);
                            $to      = $entreprise->getEmail();
                            $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour,</label><br><br>
                                                <label>Nous informons que " . strtoupper($candidat->getNom()) . " " . ucwords($candidat->getPrenom()) . " a postulé pour le poste de
                                                <br> " . ucfirst($poste->getPoste()) .".</label><br><br>
                                                <label>Pour visualiser sa candidature, veuillez vous connecter sur votre compte en cliquant <a href='" . HOST . "/connexion'>ici</a></label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                            </div>
                                        </body></html>";
                        } elseif ($candidature->getStatut() == "refuse") {
                            $to      = $candidat->getEmail();
                            $subject = "Réponse d'une candidature envoyée";
                            $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour " . strtoupper($candidat->getCivilite()). " " . ucwords($candidat->getPrenom()) . ",</label><br><br>
                                                <label>Nous avons le regret de vous informer que votre candidature n'est pas retenue pour poste de " .
                                                    ucfirst($poste->getPoste()) . " <br>
                                                par la société " . $entreprise->getNom() . "</label><br><br>
                                                <label>
                                                    L'ensemble de l'équipe " . $entreprise->getNom() . " vous souhaite un meilleur avenir professionnel. 
                                                </label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                            </div>
                                        </body></html>";
                        } elseif ($candidature->getStatut() == "accepte") {
                            $to      = $candidat->getEmail();
                            $subject = "Réponse d'un entretien passé";
                            $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour " . strtoupper($candidat->getCivilite()). " " . ucwords($candidat->getPrenom()) . ",</label><br><br>
                                                <label>Nous avons le plaisir de vous informer que votre entretien s'est bien passé <br>
                                                </label><br><br>
                                                <label>
                                                    Vous allez recevoir un email ou un appel de la part de l'entreprise " . $entreprise->getNom() . " <br> pour le poste " 
                                                    . ucfirst($poste->getPoste()) . " 
                                                </label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                            </div>
                                        </body></html>";
                        } elseif ($candidature->getStatut() == "qualification") {
                            $to      = $candidat->getEmail();
                            $subject = "Qualification";
                            $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour " . strtoupper($candidat->getCivilite()). " " .ucwords($candidat->getPrenom()) . ",</label><br><br>
                                                <label>Nous vous informons que vous allez recevoir un appel de la part de la société ". $entreprise->getNom() .", restez joignable <br>
                                                </label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                            </div>
                                        </body></html>";
                        }
                        mail($to, $subject, $message, $headers);
                    }
                }
            }
        }

        /** 
         * Supprimer un Offre 
         * 
         * @param array $parameters Critères des données à supprimer
         * 
         * @return empty
        */
        public function supprimerOffre($parameters)
        {
            $manager     = new ManagerCandidature();
            $candidature = $manager->chercher($parameters);
            if (empty($candidature)) {
                $manager = new ManagerOffre();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer cette offre ';
            }
        }

        /** 
         * Supprimer un domaine 
         * 
         * @param array $parameters Critères des données à supprimer
         * 
         * @return empty
        */
        public function supprimerDomaine($parameters)
        {
            $manager     = new ManagerDomaine();
            $domaine     = $manager->chercher($parameters);
            $manager     = new ManagerSousDomaine();
            $sousDomaine = $manager->chercher(['idDomaine' => $domaine->getIdDomaine()]);
            $manager     = new ManagerDiplome();
            $diplome     = $manager->chercher(['idDomaine' => $domaine->getIdDomaine()]);
            if (empty($sousDomaine) && empty($diplome)) {
                $manager = new ManagerDomaine();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer le domaine "' . $domaine->getNomDomaine() . '"';
            }
        }

        /** 
         * Supprimer un sous domaine 
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerSousdomaine($parameters)
        {
            $manager     = new ManagerSousDomaine();
            $sousDomaine = $manager->chercher($parameters);
            $manager     = new ManagerFormation();
            $formation   = $manager->chercher(['idSousDomaine' => $sousDomaine->getIdSousDomaine()]);
            $manager     = new ManagerExperience();
            $experience  = $manager->chercher(['idSousDomaine' => $sousDomaine->getIdSousDomaine()]);
            $manager     = new ManagerOffre();
            $offre       = $manager->chercher(['idSousDomaine' => $sousDomaine->getIdSousDomaine()]);
            if (empty($formation) && empty($experience) && empty($offre)) {
                $manager = new ManagerSousDomaine();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer le sous domaine "' . $sousDomaine->getNomSousDomaine() . '"';
            }
        }

        /** 
         * Supprimer une personnalité
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerPersonnalite($parameters)
        {
            $existe       = false;
            $manager      = new ManagerPersonnalite();
            $personnalite = $manager->chercher($parameters);
            $manager      = new ManagerCandidat();
            $candidats    = $manager->lister();
            $manager      = new ManagerOffre(); 
            $offres       = $manager->lister();
            if (!empty($candidats) || !empty($offres)) {
                foreach ($candidats as $candidat) {
                    if (strstr($candidat->getPersonnalite(), $personnalite->getQualite())) {
                        $existe = true;
                        break;
                    }
                }
                foreach ($offres as $offre) {
                    if (strstr($offre->getPersonnalite(), $personnalite->getQualite())) {
                        $existe = true;
                        break;
                    }
                }
            }
            if (!$existe) {
                $manager = new ManagerPersonnalite();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer la personnalité "' . $personnalite->getQualite() . '"';
            }
        }

        /** 
         * Supprimer un contrat
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerContrat($parameters)
        {
            $manager = new ManagerContrat();
            $contrat = $manager->chercher($parameters);
            $manager = new ManagerOffre();
            $offre   = $manager->chercher(['idContrat' => $contrat->getIdContrat()]);
            if (empty($offre)) {
                $manager = new ManagerContrat();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer le contrat "' . $contrat->getDesignation() . '"';
            }
        }

        /**
         * Supprimer un poste
         *
         * @param array $parameters critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerPoste($parameters)
        {
            $manager = new ManagerPoste();
            $poste   = $manager->chercher(['idPoste' => $parameters['idPoste']]);
            if ($poste != null && $poste != "") {
                $retour = $manager->supprimer(['idPoste' => $parameters['idPoste']]);
                if ($retour != false) {
                    $_SESSION['info']['success'] = "Suppression avec succès";
                } else {
                    $_SESSION['info']['success'] = "Echec lors de l'opération";
                }
            }
        }

        /** 
         * Supprimer un niveau d'expérience
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerNiveauExperience($parameters)
        {
            $manager          = new ManagerNiveauExperience();
            $niveauExperience = $manager->chercher($parameters);
            $manager          = new ManagerOffre();
            $offre            = $manager->chercher(['idNiveauExperience' => $niveauExperience->getIdNiveauExperience()]);
            if (empty($offre)) {
                $manager = new ManagerNiveauExperience();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer le niveau d\'expérience "' . $niveauExperience->getNiveau() . '"';
            }
        }

        /** 
         * Supprimer un niveau d'étude
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerNiveauEtude($parameters)
        {
            $manager     = new ManagerNiveauEtude();
            $niveauEtude = $manager->chercher($parameters);
            $manager     = new ManagerOffre();
            $offre       = $manager->chercher(['idNiveauEtude' => $niveauEtude->getIdNiveauEtude()]);
            $manager     = new ManagerDiplome();
            $diplome     = $manager->chercher(['idNiveauEtude' => $niveauEtude->getIdNiveauEtude()]);
            if (empty($offre) && empty($diplome)) {
                $manager = new ManagerNiveauEtude();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer le niveau d\'étude "' . $niveauEtude->getNiveau() . '"';
            }
        }

        /** 
         * Supprimer un niveau d'entretien
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerNiveauEntretien($parameters)
        {
            $manager   = new ManagerEntretien();
            $entretien = $manager->chercher($parameters);
            $manager   = new ManagerInterlocuteurNiveauEntretien;
            $interlocuteurNivEntretien = $manager->chercher($parameters);
            if (empty($entretien) && empty($interlocuteurNivEntretien)) {
                $manager = new ManagerNiveauEntretien();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer ce niveau d\'entretien';
            }
        }

        /** 
         * Supprimer un interlocuteur
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerInterlocuteur($parameters)
        {
            $manager   = new ManagerInterlocuteurNiveauEntretien();
            $interlocuteur = $manager->chercher($parameters);
            if (empty($interlocuteur)) {
                $manager = new ManagerInterlocuteur();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer cet interlocuteur';
            }
        }

        /** 
         * Supprimer un poste dans un service
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerServicePoste($parameters)
        {
            $manager      = new ManagerServicePoste;
            $servicePoste = $manager->chercher($parameters);
            if (!empty($servicePoste)) {
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer cet interlocuteur';
            }
        }

        /**
         * Parametrer les données d'un graphe des offres
         * 
         * @param arary $parameters Les critères des données du graphe
         *
         * @return empty
        */
        public function parametrerGrapheOffre($parameters)
        {   
            $manager = new ManagerOffre();
            return $manager->recupererStatistique($parameters);
        }

        /**
         * Parametrer les données d'un graphe des interlocuteurs
         * 
         * @param arary $parameters Les critères des données du graphe
         *
         * @return empty
        */
        public function parametrerGrapheInterlocuteur($parameters)
        {   
            $manager = new ManagerInterlocuteur();
            return $manager->recupererStatistique($parameters);
        }

        /**
         * Parametrer les données d'un graphe des entretiens
         * 
         * @param arary $parameters Les critères des données du graphe
         *
         * @return empty
        */
        public function parametrerGrapheEntretien($parameters)
        {   
            $manager   = new ManagerEntretien();
            return $manager->recupererStatistique($parameters);
        }

        /**
         * Spécification de retour des données par rapport à l'url
         *
         * @param string $url l'url concerné
         * @param array $data Les données à spécifier
         *
         * @return array
        */
        public function specifierDonnees($url, $data)
        {
            $valueData = array();
            if (!empty($data)) {
                foreach ($data as $tabData) {
                    if ($url == "offre") {
                        if ($tabData['name'] == "envoye") {
                          $tabData['color'] = "#00BFFF";
                        } else if ($tabData['name'] == "qualification") {
                          $tabData['color'] = "#00FA9A";
                        } else if ($tabData['name'] == "entretien1") {
                          $tabData['color'] = "#00FA9A";
                        } else if ($tabData['name'] == "entretien2") {
                          $tabData['color'] = "#00FA9A";
                        } else if ($tabData['name'] == "accepte") {
                          $tabData['color'] = "#00FA9A";
                        } else if ($tabData['name'] == "refuse") {
                          $tabData['color'] = "#00FA9A";
                        } else {
                          $tabData['color'] = "#FA8072";
                        }
                        $tabData['type'] = "stackedColumn";
                        $tabData['showInLegend'] = true;
                        $tabData['axisYType'] = "secondary";
                        $valueData[] = $tabData;
                    } else if ($url == "entretien" || $url == "interlocuteur") {
                        if ($tabData['name'] == "en attente") {
                          $tabData['color'] = "#00BFFF";
                        } else if ($tabData['name'] == "valide") {
                          $tabData['color'] = "#00FA9A";
                        } else {
                          $tabData['color'] = "#FA8072";
                        }
                        $tabData['type'] = "stackedBar";
                        $tabData['showInLegend'] = true;
                        $tabData['axisYType'] = "secondary";
                        $valueData[] = $tabData;
                    }
                }
            }
            return $valueData;
        }
    
        /**
         * Convertir une date en date complète
         *
         * @param date $date la date à convertir
         *
         * @return string
        */
        public function writeDate($date)
        {
            $tmp = explode('-', $date);
            if (count($tmp) == 3) {
                return $tmp[2] . ' ' . $this->getMonthLetter($tmp[1]) . ' ' . $tmp[0];    
            } else {
                return $date;
            }
        }

        /**
         * Convertir un entier en mois
         *
         * @param int $month
         *
         * @return string
        */
        private function getMonthLetter($month)
        {
            $month  = intval($month);
            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            return $months[$month - 1];
        }

        /** @changelog 2022/01/04 [EVOL] (Lansky) Lister les candidatures d'une entreprise selon le filtre*/
        /** 
         * 
         * @param 
         * 
         * @return 
        */
        public function listerTests($parameters)
        {
            $manager    = new ManagerEntreprisePoste();
            $postes     = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager    = new ManagerTestCandidate();
            if ($postes) {
                foreach ($postes as $poste) {
                    if (!empty($manager->lister([ 'id_entreprise_poste' => $poste->getIdEntreprisePoste()]))) {
                        foreach ($manager->lister([ 'id_entreprise_poste' => $poste->getIdEntreprisePoste()]) as $value) {
                            $value->setIdEntreprisePoste($poste->getPoste());
                            $listTemp[]  = $value;
                        }
                    } 
                }
                
            }
            // echo "<pre>";
            // var_dump($listTemp); 
            // exit();
            $donnees = [
                'postes'            => $postes,
                'testeCandidates'   => $testeCandidates
                // 'testeCandidates'   => $listTemp
            ];
            $view   = new View("listerTests");
            $view->send("Backend", "Recrutement", $donnees, "entreprise");
            exit();
        }

        /** 
         * 
         * @param 
         * 
         * @return 
        */
        private function getTestCognitives($parameters)
        {
            $manager = new ManagerTestCognitive(); 
            $lists = $manager->lister(['id_entreprise' => $_SESSION['user']['idEntreprise']]);
            echo "<pre>";
            var_dump('xxxxxxxxxxxxx');
            var_dump($lists);
           return $lists;
        }

        /** 
         * 
         * @param 
         * 
         * @return 
        */
        private function getTestPersonalities($parameters)
        {
            $manager = new ManagerTestPersonality();
            $lists = $manager->lister(['id_entreprise' => $_SESSION['user']['idEntreprise']]);
            echo "<pre>";
            var_dump('wwwwwwwwwwwwwwwwwww');
            var_dump($lists);
           return $lists;
        }

        /** 
         * Séparation la chaine de caractère
         * @param string $string Chaine de caractère à séparer
         * @param hexadecimal $ascii Code ascii d'alphabet
         * 
         * @return array
        */
        private function separateString($string, $ascii) {
            $response = array();
            for ($i = 103; $i > 96; $i--) {
                if (strpos($string, chr($i).''.$ascii)!== false && substr($string, strpos($string, chr($i).''.$ascii)-1, 1)!== chr(40)) {
                    $response[chr($i)] = substr($string, strpos($string, chr($i).''.$ascii)+2);
                    $string = substr($string, 0, strpos($string, chr($i).''.$ascii)-1);
                } 
            }
            return $response;
        }

        /** 
         * 
         * @param 
         * 
         * @return 
        */
        public function uploadCsvFile($parameters)
        {
            echo "<pre>";
            if (isset($parameters['upload'])) {
                $file               = $_FILES['file']['tmp_name'];
                $idClassification   = 0;
                $classification     = null;
                $columnName         = '';
                $manager            = new ManagerEntreprisePoste();
                $poste              = $manager->chercher([
                    'idEntreprise' => $_SESSION['user']['idEntreprise'],
                    'idEntreprisePoste' => $parameters['idEntreprisePoste']
                ]);
            var_dump($_FILES['filedocx']['name']);
            // if (isset($_FILES['filedocx'])) {
            //     $errors = array();
            //     $zip = new ZipArchive;
            //     if (end(explode('.', $_FILES["filedocx"]["name"]))!== 'docx') {
            //         $errors[] = 'Error: Wrong format';
            //     }
            //     if($zip->open($_FILES['filedocx']['tmp_name']) === false){
            //         $errors[] = 'Failed to open file';
            //     }
            //     if (empty($errors)) {
            //         $zip->extractTo('./uploads/tmp_doc');
            //         var_dump($zip);
            //         var_dump($zip->filename);
            //         var_dump($zip->comment);
            //         $zip->close();
            //     }
            // }
            exit();
                if (($handle = fopen($file, "r")) !== FALSE && end(explode('.', $_FILES['file']['name'])) == 'csv') {
                    if (end(explode('/', $_SERVER['HTTP_REFERER'])) == 'tests') {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            // var_dump($data);
                            // if (strstr(strtolower($data[0]),'classification') && strstr(strtolower($data[1]),'question')
                            //     && strstr(strtolower($data[2]),'réponse') && strstr(strtolower($data[3]),'note')) {
                            //     $columnName = "note";
                            // } elseif (strstr(strtolower($data[0]),'classification') && strstr(strtolower($data[1]),'question')
                            //     && strstr(strtolower($data[2]),'réponse') && strstr(strtolower($data[3]),'vraie réponse')) {
                            //     $columnName = "real_answer";
                            // } elseif (!strstr(strtolower($data[0]),'classification') && !strstr(strtolower($data[1]),'question')
                            //     && !strstr(strtolower($data[2]),'réponse')) {
                            //     if ($data[1] && $data[2] && $data[3]) {
                            //         if ($data[0]) {
                            //             $manager    = new ManagerTestClassification();
                            //             $doublon    = $manager->chercher([
                            //                 'id_entreprise' => $_SESSION['user']['idEntreprise'],
                            //                 'libelle'       => utf8_encode(str_replace("'","\'",$data[0]))
                            //             ]);
                            //             if ($classification) {
                            //                 $contents[]     = array_merge(['classification' => $classification], ['questions' => $rangeQuestions]);
                            //                 $rangeQuestions = array();
                            //             }
                            //             if (!$doublon) {
                            //                 $retour     = $manager->ajouter([
                            //                     'libelle'       => $data[0],
                            //                     'id_entreprise' => $_SESSION['user']['idEntreprise']
                            //                 ]);
                            //                 $idClassification   = $retour->getIdTestClassification();
                            //                 $classification     = $retour;
                            //             }
                            //             else {
                            //                 $idClassification   = $doublon->getIdTestClassification();
                            //                 $classification     = $doublon;
                            //             }
                            //         }
                            //         $choiseAnswer = $this->separateString($data[2], chr(41)); /* si on ajout de critère ici, utiliser foreach du code asccii. C'est vite fait :-) */
                            //         // Récupère la quatrième colonne import que se soit note ou vrai réponse
                            //         if ($columnName == 'note') {
                            //             $managerDynamic     = new ManagerTestPersonality();
                            //             if (!empty($this->separateString(str_replace(' ', '', $data[3]), chr(58)))) {
                            //                 $columnValue    = $this->separateString(str_replace(' ', '', $data[3]), chr(58));
                            //             } elseif (!empty($this->separateString(str_replace(' ', '', $data[3]), chr(61)))) {
                            //                 $columnValue    = $this->separateString(str_replace(' ', '', $data[3]), chr(61));
                            //             } else {
                            //                 $columnValue    = ['0' => 'erreur détestée!!!'];
                            //             }
                            //         } elseif ($columnName == 'real_answer') {
                            //             $managerDynamic     = new ManagerTestCognitive();
                            //             $columnValue        = ['0' => mb_strtolower($data[3])];
                            //         }
                            //         $manager                = new ManagerTestQuestion();
                            //         if (!$manager->chercher(['question' => $data[1], 'id_test_classification' => $idClassification])) {
                            //             $retour     = $manager->ajouter([
                            //                 $columnName                 => serialize($columnValue),
                            //                 'question'                  => $data[1],
                            //                 'choise_answer'             => serialize($choiseAnswer),
                            //                 'id_test_classification'    => $idClassification
                            //             ]);
                            //             $rangeQuestions[] = $retour;
                            //         } else {
                            //             $rangeQuestions[] = $manager->chercher(['question' => $data[1], 'id_test_classification' => $idClassification]);
                            //         }
                            //     }
                            // }
                        }
                       /* // Ajouter/inserer dans contents la dernière ligne.
                        $contents[]     = array_merge(['classification' => $classification], ['questions' => $rangeQuestions]);
                        var_dump($contents);
                        var_dump($managerDynamic);
                        exit();
                        exit();
                       $resTemporary[] = $managerDynamic->ajouter([
                            'libelle'       => ucfirst(substr($_FILES['file']['name'], 0, -4)),
                            'contents'      => serialize($contents),
                            'id_entreprise' => $_SESSION['user']['idEntreprise']
                        ]);
                        // FARANY ITY
                        $manager = new ManagerTestCandidate();
                        $manager->ajouter([
                            'libell'                 => "xxxxxx",
                            'contents'              => fopen($_FILES['filedocx']['name'], "r"), // TEN BOL HOKETREHANA ITY A
                            'id_test_cognitive'     => $resTemporary[0],
                            'id-test_personality'   => $resTemporary[1],
                            'id_entreprise_poste'   => $poste
                        ]);*/
                    }
                    exit();
                    fclose($handle);
                    $_SESSION['info']['success']    = "Import fait avec succès .";
                } else {
                    $_SESSION['info']['danger']     = "Import intérrompu .";
                }
            }
        }

        public function listerAfficheEntreprise()
        {
            $resultats   = array();
            $manager     = new ManagerEntreprise();
            $entreprise  = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($entreprise)) {
                $manager  = new ManagerAfficheEntreprise();
                $affiches = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if (!empty($affiches)) {
                    $resultats = [
                        'affiches'      => $affiches,
                        'entreprise'    => $entreprise
                    ];
                }
            }
            return $resultats;
        }

        /**
         * Obtenir le CV d'un candidat
         *
         * @changelog 2023-03-27 [EVOL] (Billy Bam) Ajout de la méthode
         *
         * @return array
        */
        private function getCandidatCv($tabOffre)
        {
            if (!empty($tabOffre)) {
                $niveauEtudes = [
                    '1' => 'Bacc',
                    '2' => 'Bacc +2',
                    '3' => 'Bacc +3',
                    '4' => 'Bacc +4',
                    '5' => 'Bacc +5',
                    '6' => 'Débutant',
                    '7' => 'Bacc +8',
                    '8' => 'Bacc +11'
                ];
                foreach ($tabOffre as $candidatures) {
                    if (!empty($candidatures)) {
                        foreach ($candidatures as $candidature) {
                            if (!empty($candidature)) {
                                foreach ($candidature as $candidat){
                                    $allLangues     = "";
                                    $allLogiciels   = "";
                                    $allFormations  = array();
                                    $allExperiences = "";
                                    foreach ($candidat['competence'] as $competence){
                                        if (!empty($competence['langues'])){
                                            foreach ($competence['langues'] as $langue){
                                                $langues[] = [
                                                    'langue'        => $langue->getNomLangue(),
                                                    'niveauEcrit'   =>$langue->getNiveauEcrit(),
                                                    'niveauParle'   =>$langue->getNiveauParle()
                                                ];
                                            }
                                            $allLangues = $langues;
                                        }
                                        if (!empty($competence['logiciels'])){
                                            foreach ($competence['logiciels'] as $logiciel) {
                                                $logiciels[] = [
                                                    'logiciel' => $logiciel->getNomLogiciel(),
                                                    'maitrise' => $logiciel->getMaitriseLogiciel()
                                                ];
                                            }
                                            $allLogiciels = $logiciels;
                                        }
                                        if (!empty($competence['formations'])){
                                            foreach ($competence['formations'] as $formations) {
                                                if (array_key_exists('formation', $formations)) {
                                                    $formationCandidat[] = [
                                                        'dateDebut'     => $formations['formation']->getDateDebut(),
                                                        'dateFin'       => $formations['formation']->getDateFin(),
                                                        'niveauEtude'   => isset($niveauEtudes[$formations['formation']->getIdNiveauEtude()]) ? $niveauEtudes[$formations['formation']->getIdNiveauEtude()]: 'Non définie',
                                                        'etablissement' => $formations['formation']->getEtablissement(),
                                                        'sousDomaine'   => is_object($formations['sousDomaine']) ? $formations['sousDomaine']->getNomSousDomaine() : 'Aucun'
                                                    ];
                                                }
                                            }
                                            if (!empty($formationCandidat)) {
                                                $allFormations = $formationCandidat;
                                            }
                                        }
                                        if (!empty($competence['experiences'])){
                                            foreach ($competence['experiences'] as $experiences) {
                                                if ($experiences->getDateFin() == '1970-01-01') {
                                                    $experienceFin = "à ce jour";
                                                } else {
                                                    $experienceFin = $experiences->getDateFin();
                                                }
                                                $experienceCandidat[] = [
                                                    'dateDebut'     => $experiences->getDateDebut(),
                                                    'dateFin'       => $experienceFin,
                                                    'poste'         => $experiences->getPoste(),
                                                    'entreprise'    => $experiences->getEntreprise(),
                                                    'description'   => $experiences->getDescription()
                                                ];
                                            }
                                            $allExperiences = $experienceCandidat;    
                                        }
                                        $cvCandidat[] = [
                                            'idCandidat'    => $candidat['candidat']->getIdCandidat(),
                                            'nomCandidat'   => $candidat['candidat']->getNom()." ".$candidat['candidat']->getPrenom(),
                                            'description'   => $candidat['candidat']->getDescription(),
                                            'adresse'       => $candidat['candidat']->getAdresse(),
                                            'dateDeNaiss'   => $candidat['candidat']->getDateNaiss(),
                                            'telephone'     => $candidat['candidat']->getContact(),
                                            'langue'        => $allLangues,
                                            'logiciel'      => $allLogiciels,
                                            'formation'     => $allFormations,
                                            'experience'    => $allExperiences
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (isset($cvCandidat)){
                return $cvCandidat;
            }
        }

        /**
         * Mettre en place l'affichage des cartes
         * 
         * @changelog 2023-03-27 [EVOL] (Billy Bam) Ajout de la méthode
         * 
         * @param $tabOffre
         *  
         * @return $tab
        */ 
        private function cardCandidate($tabOffre)
        {
            if (!empty($tabOffre)){
                $order = 0;
                $color = [
                    'suggere'       => 'black',
                    'envoye'        => 'orange',
                    'refuse'        => 'red',
                    'qualification' => 'blue',
                    'entretien1'    => 'cyan',
                    'entretien2'    => 'purple',
                    'accepte'       => 'green'
                ]; 
                foreach ($tabOffre as $offre) {
                    foreach ($offre['candidatures'] as $value) {
                        $order++;
                        $entretien          = ""; 
                        $idEntretien        = "";
                        $lieuEntretien      = "";
                        $dateEntretien      = "";
                        $heureEntretien     = "";
                        $date               = "";
                        $lieu               = "";
                        $heure              = "";
                        $photo = $value['candidat']->getPhoto() != null ? $value['candidat']->getPhoto() : "default.jpg";
                        if (!empty($value['entretien'])){
                            if ($value['candidature']->getStatut() == "entretien1" || $value['candidature']->getStatut() == "entretien2"){
                                $entretien      = " Entretien : (Heure : ".$value['entretien']->getHeure().
                                " Date :".$value['entretien']->getDate()." Lieu : ".$value['entretien']->getLieu().")";
                                $idEntretien    = $value['entretien']->getIdEntretien();
                                $lieuEntretien  = "Lieu : ".$value['entretien']->getLieu();
                                $months         = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', '     Décembre'];
                                $dateJour       = explode('-', $value['entretien']->getDate());
                                $index          = (int)$dateJour[1] - 1;
                                $dateEntretien  = "Date : ". $dateJour[2]." ".$months[$index]." ".$dateJour[0] ;
                                $heureEntretien = "Heure : ".$value['entretien']->getHeure();
                                $date           = date('d/m/Y', strtotime($value['entretien']->getDate()));
                                $lieu           = $value['entretien']->getLieu();
                                $heure          = $value['entretien']->getHeure();
                            } 
                        }  
                        $tab[] = [
                            'id'                => $value['candidature']->getIdCandidature(), 
                            'title'             => $value['candidat']->getNom()." ". $value['candidat']->getPrenom(), 
                            'order'             => $order, 
                            'idOffre'           => $value['candidature']->getIdOffre(),
                            'description'       => "Poste : ".$offre['poste']->getPoste(), 
                            'status'            => $value['candidature']->getStatut(), 
                            'color'             => array_key_exists($value['candidature']->getStatut(), $color) ?$color[$value['candidature']->getStatut()] : 'black',
                            'imageUrl'          => HOST."Web/Ressources/images/candidats/".$photo,
                            'image'             => $photo,
                            'idCandidat'        => $value['candidat']->getIdCandidat(),
                            'idEntretien'       => $idEntretien,
                            'lieuEntretien'     => $lieuEntretien,
                            'dateEntretien'     => $dateEntretien,
                            'heureEntretien'    => $heureEntretien,
                            'date'              => $date,
                            'lieu'              => $lieu,
                            'heure'             => $heure,
                            'cards'             => "#cardData"
                        ];
                    }
                }
            }
            if (isset($tab)) {
                return $tab;
            }
        }

        private function listerInterlocuteursJson()
        {
            $data   = $this->listerInterlocuteurs();
            return $data;
        }

        private function listerEmploye()
        {
            $manager = new ManagerEmploye();
            $employe = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            return $employe;
        }

        private function listerLesOffres()
        {
            $data = $this->listerOffres();
            if (!empty($data)) {
                $datas = $data['offres'];   
            }
            return $datas;
        }

        /**
         * fonction pour mettre en place le système de matching
         * 
         * @changelog 2023-03-29 [EVOL] (Billy Bam) Ajout de la méthode
         * 
         * @param $parameters
         *  
         * @return $resultats
         * 
        */     
        private function listerSuggestOffresCandidats($parameters)
        {
            $resultats      = array();
            $manager        = new ManagerCandidat();
            $candidats      = $manager->lister();
            if (!empty($candidats)) {
                foreach($candidats as $candidat) {
                    $profilCandidat = $manager->specifierProfilCandidat(['idCandidat' => $candidat->getIdCandidat()]);
                    extract($profilCandidat);
                    $pointLangue    = 0;
                    $point          = 0;
                    $macth          = 0;
                    $form           = 0;
                    if (!empty($parameters['langue'])) {
                        $manager        = new ManagerLangue();
                        $langues        = $manager->lister(['id_candidat' => $candidat->getIdCandidat()]);
                        $manager        = new ManagerCandidat();
                        $candidatOffre  = $manager->chercher(['idCandidat' => $candidat->getIdCandidat()]);
                        $langueOffre    = $parameters['langue'];
                        $langueOffres   = explode(",", $langueOffre);
                        foreach ($langues as $langue) {
                            foreach ($langueOffres as $offreLangue) {
                                if (strtolower($langue->getNomLangue()) === strtolower($offreLangue)) {
                                    $pointLangue++;
                                }
                            }
                        }
                        if ($pointLangue > 3){
                            $pointLangue = 3;
                        }
                    }
                    if (!empty($candidat->getPersonnalite())) {
                        $personnalite       = $candidat->getPersonnalite();
                        $personnalites      = explode("_", $personnalite);
                        $personnaliteOffre  = $parameters['personnalite'];
                        $personnaliteOffres = explode("_", $personnaliteOffre);
                        foreach ($personnalites as $perso) {
                            foreach ($personnaliteOffres as $persoOffre) {
                                if ($perso == $persoOffre) {
                                    $point++;
                                }
                            }
                        }
                    }
                    if (!empty($experiences)) {
                        $manager        = new ManagerEntreprisePoste();
                        $poste          = $manager->chercher(['idEntreprisePoste' => $parameters['idEntreprisePoste']]);
                        $manager        = new ManagerNiveauExperience();
                        $niveau         = $manager->chercher(['idNiveauExperience' => $parameters['idNiveauExperience']]);
                        $manager        = new ManagerCandidat();
                        $candidatOffre  = $manager->chercher(['idCandidat' => $candidat->getIdCandidat()]);
                        foreach ($experiences as $experience) {
                            if (strtolower($poste->getPoste()) === strtolower($experience['poste']) && $niveau->getNiveau()[0] <= $experience['annee']) {
                                $macth  = 10;
                            } elseif (strtolower($poste->getPoste()) === strtolower($experience['poste']) && $niveau->getNiveau()[0] > $experience['annee']) {
                                $macth  = 5;
                            } else {
                                $macth  = 0;
                            }
                        }
                    } 
                    if (!empty($formations)) {
                        $manager        = new ManagerSousDomaine();
                        $sousDomaine    = $manager->chercher(['idSousDomaine' => $parameters['idSousDomaine']]);
                        $manager        = new ManagerDomaine();
                        $domaine        = $manager->chercher(['idDomaine' => $sousDomaine->getIdDomaine()]);
                        $manager        = new ManagerNiveauEtude();
                        $niveauEtude    = $manager->chercher(['idNiveauEtude' => $parameters['idNiveauEtude']]);
                        $manager        = new ManagerCandidat();
                        $candidatOffre  = $manager->chercher(['idCandidat' => $candidat->getIdCandidat()]);
                        foreach ($formations as $formation) {
                            if (strtolower($domaine->getNomDomaine()) === strtolower($formation['domaine']) && $niveauEtude->getOrdre() <= $formation['niveau']) {
                                $form   = 10;
                            } elseif ($domaine->getNomDomaine() === $formation['domaine'] && $niveauEtude->getOrdre() > $formation['niveau']) {
                                $form   = 5;
                            } else {
                                $form   = 0;
                            }
                        }
                    }
                    $total      = $form + $macth + $point + $pointLangue;
                    $totals     = $total * 3; 
                    if ($totals >= self::MATCHING) {
                        $resultats[] = [
                            'candidat'  => $candidat,
                            'totals'    => $totals
                        ];
                        $this->envoyerEmailOffre($candidat, $totals, $parameters);
                    }
                }
            }
            $resultats = [
                'candidats' => $resultats,
                'offre'     => $parameters
            ];
            $this->envoyerEmailOffreEntreprise($resultats);
            $this->ajouterCandidature($resultats);
            return $resultats;
        }

        /**
         * Envoyer un email à une entreprise pour suggerer un candidat 
         * 
         * @changelog 2023-03-31 [EVOL] (Billy Bam) Ajout de la méthode
         * 
         * @param $resultat 
         * 
         * @return void 
         * 
        */ 
        private function envoyerEmailOffreEntreprise($resultats)
        {
            $manager    = new ManagerEmailContact();
            $emails     = $manager->chercher(['type' => 'information']);
            if (!empty($resultats['candidats'])) {
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $resultats['offre']['idEntreprise']]);
                $manager    = new ManagerEntreprisePoste();
                $postes     = $manager->chercher(['idEntreprisePoste' => $resultats['offre']['idEntreprisePoste']]);
                $subject    = "Suggestion de candidats pour une offre";
                $message    = "
                            <html>
                                <body>
                                    <div class='container'>
                                        <label>Bonjour , </label><br>
                                        <label>
                                            Nous espérons que vous vous portez bien. Nous sommes ravis de vous informer que des nouveaux candidats competents sont disponobles pour le poste 
                                            ". $postes->getPoste() . " que vous avez publié recement.
                                        </label><br>
                                        <label> 
                                            Nous souhaiterions vous proposer les candidats suivants, qui pourraient correspondre à vos exigences vis-à-vis du poste en question.
                                        </label><br>
                                        <div class='container'>
                                            <table class='table table-striped'>
                                                <thead style='color: white; background: black; '>
                                                    <tr>
                                                        <th scope='col'>Candidat</th>
                                                        <th scope='col'>Taux de réussite</th>
                                                    </tr>
                                                </thead>
                                                <tbody>";
                            foreach($resultats['candidats'] as $candidat) {
                                $message .= "<tr>
                                                <td scope='col'>".$candidat['candidat']->getNom()." ".$candidat['candidat']->getPrenom()."</td>
                                                <td style='text-align:center;' scope='col'>".$candidat['totals']."%</td>
                                            </tr>";
                            }
                $message    .= "
                                                </tbody>
                                            </table>
                                        </div>
                                        <label>
                                            Pour plus d'information, veuillez-vous connecter sur le site en cliquant <a href='" . HOST . "/connexion'>ici</a>
                                        </label><br><br>
                                        <label>Cordialement, </label><br><br>
                                        <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                    </div>
                                </body>
                            </html>
                            ";
                $to         = $entreprise->getEmail();                            
                $headers    = "Content-type: text/html" . "\r\n" . "From: " . $emails->getEmail();
                mail($to, $subject, $message, $headers);
            }
        }

        /**
         * Envoyer un email à un candidat pour suggerer une offre 
         * 
         * @changelog 2023-03-31 [EVOL] (Billy Bam) Ajout de la méthode
         * 
         * @param $resultat 
         * 
         * @return void 
         * 
        */ 
        private function envoyerEmailOffre($candidat, $totals, $parameters)
        {
            $manager = new ManagerEmailContact();
            $emails  = $manager->chercher(['type' => 'information']);
            $manager = new ManagerEntreprisePoste();
            $postes  = $manager->chercher(['idEntreprisePoste' => $parameters['idEntreprisePoste']]);
            $subject = "Recrutement";
            $message = "<html><body>
                            <div class='container'>
                                <label>Bonjour " . ucwords($candidat->getPrenom()) . ", </label><br><br>
                                <label>
                                    Nous espérons que vous vous portez bien. Nous sommes ravis de vous informer qu'une nouvelle opportunité professionnelle est disponible pour vous.
                                </label><br>
                                <label> 
                                    Nous souhaiterions vous proposer le poste de " . $postes->getPoste() . ", qui pourrait correspondre à vos compétences et à votre expérience.
                                </label><br>
                                <label> 
                                    Nous avons étudié attentivement votre profil et nous sommes convaincus que vous avez le potentiel pour réussir dans ce poste.
                                </label><br>
                                <label> 
                                    Selon nos estimations, votre taux de réussite pour ce poste serait de ". $totals . "%.
                                </label><br>
                                <label>
                                    Pour plus d'information, veuillez-vous connecter sur le site en cliquant <a href='" . HOST . "/connexion'>ici</a>
                                </label><br><br>
                                <label>Cordialement, </label><br><br>
                                <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                            </div>
                        </body></html>";
            $to = $candidat->getEmail();                            
            $headers = "Content-type: text/html" . "\r\n" . "From: " . $emails->getEmail();
            mail($to, $subject, $message, $headers);
        }

        /**
         * Envoyer un email à une entreprise pour suggerer un candidat 
         * 
         * @changelog 2023-04-20 [EVOL] (Billy Bam) Ajout de la méthode
         * 
         * @param $resultat 
         * 
         * @return void 
         * 
        */ 
        public function listerCommentaire($parameters)
        {
            $resultats      = array();
            $tabUser        = array();
            $tabCommentaire = array();
            $manager        = new ManagerCommentaire();
            $commentaires   = $manager->lister(['idCandidature' => $parameters['idCandidature']]);
            $manager        = new ManagerCandidature();
            $candidature    = $manager->chercher(['idCandidature' => $parameters['idCandidature']]);
            $manager        = new ManagerOffre();
            $offre          = $manager->chercher(['idOffre' => $candidature->getIdOffre()]);
            if (!empty($commentaires)) {
                foreach($commentaires as $commentaire){
                    $manager = new ManagerCompte();
                    $compte = $manager->chercher(['idCompte' => $commentaire->getIdCompte()]);
                    if ($compte->getIdentifiant() == "employe") {
                        $manager = new ManagerEmploye();
                        $employe = $manager->chercher(['idCompte' => $commentaire->getIdCompte()]);
                        $tabUser = [
                            'user' => $employe
                        ];
                    } elseif ($compte->getIdentifiant() == "entreprise") {
                        $manager = new ManagerEntreprise();
                        $entreprise = $manager->chercher(['idCompte' => $commentaire->getIdCompte()]);
                        $tabUser = [
                            'user'      => $entreprise
                        ];
                    }
                    $tabCommentaire = [
                        'commentaire'   => $commentaire,
                        'user'          => $tabUser,
                        'compte'        => $compte
                    ];  
                    $resultats[] = [
                        'commentaire'   => $tabCommentaire,
                        'candidature'   => $candidature,
                        'offre'         => $offre, 
                        'attributions'  => $this->listerAttributionRecrutement($candidature)  
                    ]; 
                }
            } else {
                $resultats[] = [
                    'candidature'   => $candidature,
                    'offre'         => $offre,
                    'attributions'  => $this->listerAttributionRecrutement($candidature)
                ]; 
            }
            return $resultats;
        }

        /**
         * Mettre à jour un commentaire 
         * 
         * @changelog 2023-04-20 [EVOL] (Billy Bam) Ajout de la méthode
         * 
         * @param $resultat 
         * 
         * @return void 
         * 
        */ 
        public function mettreAJourCommentaire($parameters)
        {
            $manager = new ManagerCommentaire();
            $datetime   = new DateTime('now', new DateTimeZone('Europe/Paris'));
            $offset     = $datetime->getTimezone()->getOffset($datetime);
            $datetime->add(new DateInterval('PT' . abs($offset) . 'S'));
            if ($offset < 0) {
                $datetime->sub(new DateInterval('PT1H'));
            }
            $new_datetime = $datetime->format('Y-m-d H:i:s');
            if (!empty($parameters['idCommentaire'])) {
                $parameters['dateCommentaire'] = $new_datetime;
                return $manager->modifier($parameters);
            } else {
                $parameters['dateCommentaire'] = $new_datetime;
                return $manager->ajouter($parameters);
            }
        }

        /** 
         * Lister les interlocuteurs
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
        */
        public function listerInterlocuteurRecrutement()
        {
            return $this->listerInterlocuteurs();
        }

        public function listerAttribution()
        {
            $manager = new ManagerAttribution();
            return $this->lister();
        } 

        /**
         * mettre à jour un attribution
         * 
         * @param array $parameters Les données à mettre à jour
         * 
         * @return empty 
        */
        public function mettreAJourAttribution($parameters)
        {
            $manager = new ManagerAttribution();
            if (isset($parameters['idAttribution'])) {
                return $manager->modifier($parameters);
            } else {
                return $manager->ajouter($parameters);
            }
        }

        public function voirDetailOffreCandidature($parameters)
        {
            return $this->voirDetailOffre($parameters);
        }

        /** 
         * Voir le détail d'un candidat qui ont envoyer une candidature 
         * 
         * @param array $parameters Critères des données à voir
         * 
         * @return array 
        */
        public function voirDetailCvCandidature($parameters)
        {
            return $this->voirDetailCvCandidat($parameters);
        }

        /**
         * ajout de la candidature d'un candidat suggeré
         * 
         * @param $resultats
         * 
         * @return void
         * 
        */ 
        private function ajouterCandidature($resultats)
        {
            if (!empty($resultats['candidats'])) {
                $idCandidat = 0;
                foreach($resultats['candidats'] as $candidat)
                {
                    $idCandidat = $candidat['candidat']->getIdCandidat();
                    $manager    = new ManagerCandidature();
                    $result     = $manager->ajouter([
                        'idOffre'           => $resultats['offre']['idOffre'],
                        'idCandidat'        => $idCandidat,
                        'dateCandidature'   => date('Y-m-d'),
                        'statut'            => "suggere"
                    ]);
                }
            }
        }

        /**
         * Lister les attributions sur le recrutement concernant unou plusieurs candidats 
         * 
         * @changelog 2023-04-22 [EVOL] (Billy Bam) Ajout de la méthode
         * 
         * @param $resultat 
         * 
         * @return void 
         * 
        */ 
        private function listerAttributionRecrutement($candidature)
        {
            $manager         = new ManagerAttribution();
            $attributions    = $manager->lister(['idCandidature' => $candidature->getIdCandidature()]);
            $tabEmploye      = array();
            if (!empty($attributions)) {
                foreach ($attributions as $attribution) {
                    $manager         = new ManagerEmploye();
                    $employe         = $manager->chercher(['idEmploye' => $attribution->getIdEmploye()]);
                    $tabEmploye[]    = [
                        'employe'    => $employe
                    ];        
                }
                $tabAttr[] = [
                    'attribution'   => $attributions,
                    'employe'       => $tabEmploye
                ];                
            }
            if (isset($tabAttr)) {
               return $tabAttr;
            }
        }

        /**
         * fonction pour supprimer un commentaire
         *
         * @changelog 2023-04-22 [EVOL] (Billy Bam) Ajout de la méthode
         *  
         * @param $parameters
         * 
         * @return $resultats 
         * 
        */ 
        public function supprimerCommentaire($parameters)
        {
            $manager                        = new ManagerCommentaire();
            $manager->supprimer(['idCommentaire' => $parameters['idCommentaire']]);
            $_SESSION['info']['success']    = "Suppression avec succès";
        }
        /**
         * fonction pour lister un commentaires
         *
         * @changelog 2023-04-22 [EVOL] (Billy Bam) Ajout de la méthode
         *  
         * @param $parameters
         * 
         * @return $resultats 
         * 
        */ 
        public function listerComment($parameters)
        {
            $manager = new ManagerCommentaire();
            $comment = $manager->chercher(['idCommentaire' => $parameters['idCommentaire']]);
            return $comment;
        }

        /**
         * fonction pour lister les commentaires à exporter
         *  
         * @changelog 2023-04-22 [EVOL] (Billy Bam) Ajout de la méthode
         * 
         * @param $parameters
         * 
         * @return $resultats 
         * 
        */ 
        public function listerExportComment($parameters)
        {
            $tabUser        = array();
            $tabCommentaire = array();
            $resultats      = array();
            $manager        = new ManagerCandidature();
            $candidature    = $manager->chercher(['idCandidature' => $parameters['idCandidature']]);
            $manager        = new ManagerCommentaire();
            $commentaires   = $manager->lister(['idCandidature' => $parameters['idCandidature']]);
            if (!empty($commentaires)) {
                foreach ($commentaires as $commentaire) {
                    $manager = new managerCompte();
                    $compte  = $manager->chercher(['idCompte' => $commentaire->getIdCompte()]);
                    if ($compte->getIdentifiant() == "entreprise") {
                        $manager    = new ManagerEntreprise();
                        $entreprise = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
                        $tabUser[]  = [
                            'compte'    => $compte,
                            'user'      => $entreprise
                        ];
                    } elseif ($compte->getIdentifiant() == "employe"){
                        $manager    = new ManagerEmploye();
                        $employe    = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
                        $tabUser[]  = [
                            'compte'    => $compte,
                            'user'      => $employe
                        ];
                    }
                    $tabCommentaire[] = [
                        'tabUser'       => $tabUser,
                        'commentaire'   => $commentaire
                    ];
                }
                $resultats[] = [
                    'commentaires' => $this->cardComment($tabCommentaire),
                    'candidature'  => $candidature
                ];
            }
            return $resultats;
        }

        /**
         * fonction pour lister les commentaires à exporter en json
         * 
         * @changelog 2023-04-22 [EVOL] (Billy Bam) Ajout de la méthode
         *  
         * @param $parameters
         * 
         * @return $resultats 
         * 
        */ 
        private function cardComment($comments)
        {
            $tabComment = array();
            if (!empty($comments)) {
                $userName       = 0;
                $userPhoto      = 0;
                $commentaire    = 0;
                $date           = 0;
                $statut         = 0;
                foreach ($comments as $comment) {
                    foreach ($comment['tabUser'] as $user) {
                        if ($user['compte']->getIdentifiant() == "entreprise") {
                            $userName   = $user['user']->getNom();
                            $userPhoto  = "entreprises/".$user['user']->getLogo();
                        } elseif ($user['compte']->getIdentifiant() == "employe") {
                            $userName   = $user['user']->getNom()." ".$user['user']->getPrenom();
                            $userPhoto  = "employes/".$user['user']->getPhoto();
                        }
                    }
                    $tabComment[] = [
                        'photo'             => $userPhoto,
                        'nom'               => $userName,
                        'commentaire'       => $comment['commentaire']->getCommentaire(),
                        'dateCommentaire'   => $comment['commentaire']->getDateCommentaire(),
                        'statut'            => $comment['commentaire']->getStatut()
                    ];
                }
            }
            if (isset($tabComment)) {
                return $tabComment;
            }
        }

        /**
         * fonction pour la mise en place des graphes de suivi des candidatures
         *  
         * @changelog 2023-04-22 [EVOL] (Billy Bam) Ajout de la méthode
         * 
         * @param $parameters
         * 
         * @return $resultats 
         * 
        */ 
        public function listerSuiviCandidature($parameters)
        {
            $months = [
                'Janvier'       => '01',
                'Février'       => '02',
                'Mars'          => '03',
                'Avril'         => '04',
                'Mai'           => '05',
                'Juin'          => '06',
                'Juillet'       => '07',
                'Août'          => '08',
                'Septembre'     => '09',
                'Octobre'       => '10',
                'Novembre'      => '11',
                'Décembre'      => '12'
            ]; 
            $dates = 0;
            if (isset($parameters['month'])) {
                $date   = explode(" ", $parameters['month']);
                $filter = $months[$date[0]];
                $year   = $date[1];
                $dates  = 1;
            } elseif (isset($parameters['year'])) {
                $filter = $parameters['year'];
                $dates  = 2;
            } elseif (isset($parameters['date1'])) {
                $date1      = explode("/", $parameters['date1']);
                $date2      = explode("/", $parameters['date2']);
                $filter1    = $date1[2]."-".$date1[1]."-".$date1[0];
                $filter2    = $date2[2]."-".$date2[1]."-".$date2[0];
                $dates      = 3;
            } 
            $manager    = new ManagerOffre();
            $offres     = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $tabData    = array();
            $resultats  = array();
            if (!empty($offres)) {
                foreach ($offres as $offre) {
                    $manager        = new ManagerEntreprisePoste();
                    $postes         = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                    $manager        = new ManagerCandidature();
                    if ($dates == 0) {
                        $candidature    = $manager->lister(['idOffre' => $offre->getIdOffre()]);
                    } elseif ($dates == 1) {
                        $candidature    = $manager->lister(['idOffre' => $offre->getIdOffre(), 'MONTH(dateCandidature)'     => $filter , 'YEAR(dateCandidature)'    => $year]);
                    } elseif ($dates == 2) {
                        $candidature    = $manager->lister(['idOffre' => $offre->getIdOffre(), 'YEAR(dateCandidature)'      => $filter]);
                    } elseif ($dates == 3) {
                        $candidature    = $manager->lister(['idOffre' => $offre->getIdOffre(), 'dateCandidature>='          => $filter1 , 'dateCandidature<='       => $filter2 ]);
                    }
                    $tabData[]      = [
                        'poste'         => $postes,
                        'candidature'   => $candidature,
                        'offre'         => $offre
                    ];
                }
                $resultats = [
                    'offres' => $this->countCandidature($tabData)
                ];
            }
            return $resultats;
        }
        
        /**
         * fonction pour compter le nombre de candidature a chaque poste et à chaque statut des candidatures
         * 
         * @changelog 2023-04-03 [EVOL] (Billy Bam) Ajout de la méthode
         *  
         * @param $parameters
         * 
         * @return $resultats 
         * 
        */ 
        public function countCandidature($data)
        {
            $tabOffre       = array();
            if (!empty($data)) {
                foreach ($data as $offre) {
                    $envoye         = 0;
                    $qualification  = 0;
                    $entretien1     = 0;
                    $entretien2     = 0;
                    $accepte        = 0;
                    $refuse         = 0;
                    $suggere        = 0;
                    if (!empty($offre['candidature'])) {
                        foreach ($offre['candidature'] as $candidature) {
                            if ($candidature->getStatut() == "envoye") {
                                $envoye++;
                            } elseif ($candidature->getStatut() == "qualification") {
                                $qualification++;
                            } elseif ($candidature->getStatut() == "entretien1") {
                                $entretien1++;
                            } elseif ($candidature->getStatut() == "entretien2") {
                                $entretien2++;
                            } elseif ($candidature->getStatut() == "entretien2") {
                                $entretien2++;
                            } elseif ($candidature->getStatut() == "accepte") {
                                $accepte++;
                            } elseif ($candidature->getStatut() == "refuse") {
                                $refuse++;
                            } else {
                                $suggere++;
                            }
                        }
                    }
                    $tabOffre[] = [
                        'poste'         => $offre['poste']->getPoste(),
                        'envoye'        => $envoye,
                        'qualification' => $qualification,
                        'entretien1'    => $entretien1,
                        'entretien2'    => $entretien2,
                        'accepte'       => $accepte,
                        'refuse'        => $refuse,
                        'suggere'       => $suggere
                    ];
                }
            }
            if (isset($tabOffre)) {
                return $tabOffre;
            }
        }

        /** 
         * Afficher la formulaire d'une offre
         *
         * @param array $parameters Les données à récupérer
         * 
         * @return array 
        */
        public function afficherFormNewOffre($parameters)
        {
            $manager                    = new ManagerEntreprise();
            $entreprise                 = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager                    = new ManagerOffre();
            if (isset($parameters)) {
                $offre = $manager->chercher($parameters);
            } else {
                $offre = $manager->initialiser();
            }
            $manager            = new ManagerSousDomaine();
            $sousDomaines       = $manager->lister(null);
            $manager            = new ManagerDomaine();
            $domaines           = $manager->lister();
            $manager            = new ManagerContrat();
            $contrats           = $manager->lister();
            $manager            = new ManagerNiveauExperience();
            $niveauxExperiences = $manager->lister();
            $manager            = new ManagerNiveauEtude();
            $niveauxEtudes      = $manager->lister();
            $manager            = new ManagerPersonnalite();
            $personnalites      = $manager->lister();
            // $manager            = new ManagerEntreprisePoste();
            // $postes             = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            return [ 
                'entreprise'         => $entreprise, 
                'offre'              => $offre, 
                'sousDomaines'       => $sousDomaines,
                'domaines'           => $domaines, 
                'contrats'           => $contrats, 
                'niveauxExperiences' => $niveauxExperiences, 
                'niveauxEtudes'      => $niveauxEtudes, 
                'personnalites'      => $personnalites
                // 'postes'             => $postes
            ];          
        }

        /**
         * fonction pour ajouter une offre pour un poste qui n'existe pas encore
         * 
         * @changelog 2023-04-25 [EVOL] (Billy Bam) Ajout de la méthode
         * 
         * @param $parameters
         * 
         * @return array
         * 
        */
        public function mettreAJourNewOffre($parameters)
        {
            $missions = $parameters['input_mission'];
            unset($parameters['nomPoste']);
            unset($parameters['input_mission']);
            if ($parameters['idNiveauEtude'] == "autre" && $parameters['niveau'] != "") {
                $manager        = new ManagerNiveauEtude();
                $niveauEtude    = $manager->chercher(['niveau' => $parameters['niveau']]);
                if (empty($niveauEtude)) {
                    $niveauEtude = $manager->ajouter(['niveau' => $parameters['niveau']]);
                }
                $parameters['idNiveauEtude'] = $niveauEtude->getIdNiveauEtude();
            }
            if ($parameters['anneeExperienceInterne'] == "autre" && $parameters['niveauExperienceInterne'] != "") {
                $manager            = new ManagerNiveauExperience();
                $niveauExperience   = $manager->chercher(['niveau' => $parameters['niveauExperienceInterne']]);
                if (empty($niveauExperience)) {
                    $niveauExperience = $manager->ajouter(['niveau' => $parameters['niveauExperienceInterne']]);
                }
            }
            unset($parameters['niveauExperienceInterne']);
            unset($parameters['niveauExperienceExterne']);
            $parameters['poste']    = ucfirst($parameters['poste']);
            $manager                = new ManagerEntreprise();
            $entreprise             = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($entreprise)) {
                $manager = new ManagerEntreprisePoste();
                $entreprisePoste                = $manager->ajouter([
                    'idEntreprise'              => $_SESSION['user']['idEntreprise'],
                    'poste'                     => $parameters['poste'],
                    'formationRequise'          => "N/A",
                    'idNiveauEtude'             => $parameters['idNiveauEtude'],
                    'capaciteNiveauEtude'       => "min",
                    'relationInterne'           => "N/A",
                    'relationExterne'           => "N/A",
                    'anneeExperienceInterne'    => $parameters['anneeExperienceInterne'],
                    'experienceInterne'         => "N/A",
                    'capaciteExperienceInterne' => "min",
                    'anneeExperienceExterne'    => $parameters['anneeExperienceInterne'],
                    'experienceExterne'         => "N/A",
                    'capaciteExperienceExterne' => "min",
                    'raison'                    => "N/A",
                    'evolution'                 => "0",
                    'savoirs'                   => "N/A",
                    'savoirEtre'                => "N/A",  
                    'savoirFaire'               => "N/A",
                    'statut'                    => "1"  
                ]);
                $_SESSION['info']['success']    = "Offre ajoutée avec succès";
            }
            $parameters['idEntreprisePoste'] = $entreprisePoste->getIdEntreprisePoste();
            $this->mettreAjourMission($entreprisePoste->getIdEntreprisePoste(), $missions);
            $this->mettreAJourOffres($entreprisePoste, $parameters);
            return $entreprisePoste;
        }

        /**
         * fonction pour ajouter une offre pour un poste qui n'existe pas encore
         * 
         * @changelog 2023-04-25 [EVOL] (Billy Bam) Ajout de la méthode
         * 
         * @param $entreprisePoste
         * @param $parameters
         * 
         * @return array
         * 
        */
        private function mettreAJourOffres($entreprisePoste ,$parameters)
        {
            $parameters = $this->verifierParamsSousDomaine($parameters);
            $parameters = $this->verifierParamsContrat($parameters); 
            if (isset($parameters['autreQualite'])) {
                unset($parameters['autreQualite']);
            }
            if (!empty($parameters['autrePersonnalite'])) {
                $qualites = explode("_", $parameters['autrePersonnalite']);
                foreach ($qualites as $qualite) {
                    if (!empty($qualite)) {
                        $manager = new ManagerPersonnalite();
                        $search  = $manager->chercher(['qualite' => $qualite]);
                        if (empty($search)) {
                            $manager->ajouter(['qualite' => ucfirst($qualite)]);
                        }
                    }
                }
            }
            unset($parameters['autrePersonnalite']);
            unset($parameters['qualite']);
            $referer    = $_SERVER["HTTP_REFERER"];
            $notice     =  explode('/', $referer);
            $folderPath = DOC_ROOT. 'Ressources/images/offre/';
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            } 
            foreach($_FILES as $field => $value){
                if(!empty($_FILES[$field]['name'])){
                    $fieldName          = $field . "_" . time() . ".png";
                    $target             = DOC_ROOT. 'Ressources/images/offre' . basename($_FILES[$field]['name']);
                    move_uploaded_file( $_FILES[$field]['tmp_name'], $target);
                    rename($target, $folderPath . $fieldName);
                    $parameters[$field] = $fieldName;
                    $parameters['couvertureOffre'] = str_replace("C:\\fakepath\\" . $_FILES[$field]['name'], $fieldName, $parameters['couvertureOffre']);
                }
            }
            $manager = new ManagerOffre();
            $parameters['idNiveauExperience'] = $entreprisePoste->getAnneeExperienceExterne();
            $parameters['idNiveauExperience'] = $entreprisePoste->getAnneeExperienceExterne();
            $couvertureOffre = "";
            if (!empty($parameters['couvertureOffre'])) {
                $couvertureOffre = $parameters['couvertureOffre'];
            }
            $this->listerSuggestOffresCandidats($parameters);
            $offre = $manager->ajouter([
                'idEntreprise'          => $_SESSION['user']['idEntreprise'],
                'idSousDomaine'         => $parameters['idSousDomaine'],
                'idContrat'             => $parameters['idContrat'], 
                'idNiveauExperience'    => $entreprisePoste->getAnneeExperienceExterne(),
                'idNiveauEtude'         => $entreprisePoste->getIdNiveauEtude(),
                'personnalite'          => $parameters['personnalite'], 
                'dateEmission'          => $parameters['dateEmission'], 
                'dateLimite'            => $parameters['dateLimite'],
                'idEntreprisePoste'     => $entreprisePoste->getIdEntreprisePoste(),
                'conditionNumeraire'    => $parameters['conditionNumeraire'],
                'couvertureOffre'       => $couvertureOffre,
                'langue'                => $parameters['langue']
            ]);
            return $offre; 
        }
    }