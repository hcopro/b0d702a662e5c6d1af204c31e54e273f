<?php
    
    /**
     * Manager du modules Recrutement Backend
     *
     * @author Voahirana 
     *
     * @since 09/10/19 
    */

    use \Core\DbManager;

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
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerMission;

    class ManagerModuleRecrutement extends DbManager
    {
        /**
         * Lister toutes les offres disponibles
         *
         * @return array
        */
        public function listerOffres($parameters) 
        {
            $tabOffres    = array();
            $manager      = new ManagerOffre();
            $offres       = $manager->lister($parameters);
            $manager      = new ManagerSousDomaine();
            $sousDomaines = $manager->lister(null);
            $manager      = new ManagerContrat();
            $contrats     = $manager->lister();
            if (!empty($offres)) {
                foreach ($offres as $offre) {
                    $manager     = new ManagerEntreprise();
                    $entreprise  = $manager->chercher(['idEntreprise' => $offre->getIdEntreprise()]);
                    $manager     = new Managercompte();
                    $compte      = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                    if ($compte->getStatut() == "active") {
                        $manager = new ManagerContrat();
                        $contrat = $manager->chercher(['idContrat' => $offre->getIdContrat()]);
                        $manager = new ManagerEntreprisePoste();
                        $poste   = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                        $tabOffres[] = [
                            'offre'       => $offre, 
                            'entreprise'  => $entreprise,
                            'contrat'     => $contrat,
                            'poste'       => $poste
                        ];     
                    }                      
                } 
            }
            return [
                "offres"       => $tabOffres,
                "sousDomaines" => $sousDomaines,
                "contrats"     => $contrats
            ];
        }

        /** 
         * Voir le détail d'une offre 
         *
         * @param array $parameters Critères des données à voir 
         * 
         * @return objet 
        */
        public function voirOffre($parameters)
        {
            $resultat    = array(); 
            $manager     = new ManagerOffre();
            $offre       = $manager->chercher($parameters); 
            $candidature = "";
            if (!empty($offre)) {
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $offre->getIdEntreprise()]);
                $manager    = new Managercompte();
                $compte     = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                if ($compte->getStatut() == "active") {
                   $manager          = new ManagerSousDomaine();
                    $sousDomaine      = is_object($offre) ? $manager->chercher(['idSousDomaine' => $offre->getIdSousDomaine()]) : $manager->initialiser() ;
                    $manager          = new ManagerDomaine();
                    $domaine          = is_object($sousDomaine) ? $manager->chercher(['idDomaine' => $sousDomaine->getIdDomaine()]) : $manager->initialiser() ;
                    $manager          = new ManagerContrat();
                    $contrat          = is_object($offre) ? $manager->chercher(['idContrat' => $offre->getIdContrat()]) : $manager->initialiser() ;
                    $manager          = new ManagerNiveauExperience();
                    $niveauExperience = is_object($offre) ? $manager->chercher(['idNiveauExperience' => $offre->getIdNiveauExperience()]) : $manager->initialiser() ;
                    $manager          = new ManagerNiveauEtude();
                    $niveauEtude      = is_object($offre) ? $manager->chercher(['idNiveauEtude' => $offre->getIdNiveauEtude()]) : $manager->initialiser() ;
                    $manager          = new ManagerEntreprisePoste();
                    $poste            = is_object($offre) ? $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]) : $manager->initialiser() ;
                    $manager          = new ManagerMission();
                    $missions         = is_object($poste) ? $manager->lister(['idEntreprisePoste' => $poste->getIdEntreprisePoste()]) : $manager->initialiser() ;
                    if (isset($_SESSION['compte'])) {
                        if ($_SESSION['compte']['identifiant'] == "candidat") {
                            $manager     = new ManagerCandidat();
                            $candidat    = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
                            $manager     = new ManagerCandidature();
                            $candidature = $manager->chercher([
                                'idOffre'    => $offre->getIdOffre(),
                                'idCandidat' => $candidat->getIdCandidat()
                            ]);
                        }
                    }
                    $resultat = [  
                        'offre'            => $offre, 
                        'entreprise'       => $entreprise, 
                        'sousDomaine'      => $sousDomaine, 
                        'domaine'          => $domaine, 
                        'contrat'          => $contrat, 
                        'niveauExperience' => $niveauExperience, 
                        'niveauEtude'      => $niveauEtude,
                        'poste'            => $poste,
                        'candidature'      => $candidature,
                        'missions'         => $missions
                    ];   
                }
                
            }
            return $resultat;
        }

        /**
         * Lister toutes les entreprises qui nous ont confience
         *
         * @return array
        */
        public function listerEntreprise($parameters)
        {
            $manager = new ManagerEntreprise();
            return $manager->lister($parameters);
        }
        
        /**
         * Voire toutes les offres
         *
         * @return array
        */
        public function voirOffres($parameters)
        {
            $result = $this->listerOffres($parameters);
            $result['entreprises'] = $this->listerEntreprise($parameters);
            return $result;
        }
    }