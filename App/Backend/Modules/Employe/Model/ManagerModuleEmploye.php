<?php
    
    /**
     * Manager du modules Employe du Backend
     *
     * @author Voahirana
     *
     * @since 13/03/20
    */

    use \Core\ChiffreEnLettre;
    use \Core\DbManager;
    use \Model\ManagerBanque;
    use \Model\ManagerEmploye;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerEntrepriseService;
    use \Model\ManagerPersonnalite;
    use \Model\ManagerCompteBanque;
    use \Model\ManagerCompte;
    use \Model\ManagerEntreprise;
    use \Model\ManagerContratEmploye;
    use \Model\ManagerServicePoste;
    use \Model\ManagerContrat;
    use \Model\ManagerRenouvellement;
    use \Model\ManagerTemplate;
    use \Model\ManagerCategorieProfessionnelle;
    use \Model\ManagerMission;
    use \Model\ManagerConfiguration;
    use \Model\ManagerStockConge;
    use \Model\ManagerEmailContact;
    use \Model\ManagerAutorized;
    use \Model\ManagerMenuEntreprise;
    require_once "Lib/Core/PhpDocx.php";

    class ManagerModuleEmploye extends DbManager
    {
        const RENOUVELLEMENT          = 'renouvellement';
        const PROLONGEMENT            = 'prolongement';
        const CONTRAT                 = 'contrat';
        const ATTESTATON              = 'attestation';
        const CERTIFICAT              = 'certificat';
        const EDIT_PROLONGEMENT       = 'edit-prolongement';
        const EDIT_RENOUVELLEMENT     = 'edit-renouvellement';
        const NEW_CONTRAT             = 'nouveau-contrat';
        const HISTORIQUE_PAGE         = 'historique';
        const GESTION_PAGE            = 'gestion';
        const TWO_YEARS               = 24;
        const ONE_MONTH               = 1;
        const ZERO_DAY                = 0;
        const TWO_TIMES               = 2;
        const ZERO_TIME               = 0;
        const ONE_TIME                = 1;
        const EMPTY                   = 0;
        const PROPOSED                = 1;
        const VALIDATED               = 2;
        const EXPIRED                 = 3;
        const CDI                     = 'CDI';
        const CDD                     = 'CDD';
        const CDE                     = 'CDE';
        const CA                      = 'CA';
        const STAGE                   = 'STAGE';
        const NOTHING                 = '...';
        const YES                     = 1;
        const NO                      = 0;
        const TEMPLATE_RENOUVELLEMENT = 0;
        const TEMPLATE_ATTESTATION    = -1;
        const TEMPLATE_CERTIFICAT     = -2;
        const DEFAULT_TEMPLATE        = 0;
        const USER_ENTREPRISE         = 'entreprise';
        const USER_EMPLOYE            = 'employe';
        const STOCK_MENSUEL           = 2.5;
        const ALL                     = 'all';
        
        /** 
         * Lister les banques
         * 
         * @return array
        */
        public function listerBanques()
        {
            $resultats = array();
            $manager   = new ManagerBanque();
            $banques   = $manager->lister();
            if (!empty($banques)) {
                foreach ($banques as $banque) {
                    $resultats[] = $banque;
                }
            }
            return $resultats;
        }

        /**
         * Lister les catégories professionnelles
         *
         * @return array
        */
        public function listerCategorieProfessionnelles()
        {
            $manager   = new ManagerCategorieProfessionnelle();
            $resultats = $manager->lister();
            return $resultats;
        }

        /** 
         * Lister les employés
         * 
         * @return array
        */
        public function listerEmployes()
        {
            $resultats  = array();
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($entreprise)) {
                $manager  = new ManagerEmploye();
                $employes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if (!empty($employes)) {
                    foreach ($employes as $employe) {
                        $manager = new ManagerContratEmploye();
                        $contrat = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
                        $manager = new ManagerServicePoste();
                        if ($contrat) {
                            $servicePoste = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                        }
                        if ($servicePoste != null) {
                            $manager = new ManagerEntrepriseService();
                            $service = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                            $manager = new ManagerEntreprisePoste();
                            $poste   = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                        } else {
                            $service = null;
                            $poste   = null;
                        }
                        $resultats[] = [
                            'employe' => $employe,
                            'poste'   => $poste,
                            'service' => $service
                        ];
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Afficher le formulaire d'une banque
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Objet
        */
        public function afficherFormBanque($parameters)
        {
            $manager = new ManagerBanque();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        } 

        /** 
         * Mettre à jour une banque
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourBanque($parameters)
        {
            $parameters['codeBanque'] = strtoupper($parameters['codeBanque']);
            $manager                  = new ManagerBanque();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /**
         *
         * Afficher le formulaire d'une catégorie professionnelle
         *
         * @param array $parameters les données à récupérer
         * 
         * @return empty
        */
        public function afficherFormCategorieProfessionnelle($parameters)
        {
            $manager = new ManagerCategorieProfessionnelle();
            if (!empty($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            }
        }

        /**
         * Mettre à jour une catégorie professionnelle
         * 
         * @param array $parameters les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourCategorieProfessionnelle($parameters)
        {
            $manager = new ManagerCategorieProfessionnelle();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Afficher le formulaire d'un employé
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
        */
        public function afficherFormEmploye($parameters)
        {
            $tmpIdEmploye  = is_null($parameters) ? null : $parameters['idEmploye'] ;
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $tmpIdEmploye) || is_null($parameters)) {
                $resultats = array();
                $manager   = new ManagerEmploye();
                if (isset($parameters)) {
                    $employe = $manager->chercher($parameters);
                } else {
                    $employe = $manager->initialiser();
                }
                $employes      = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager       = new ManagerEntreprisePoste();
                $postes        = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager       = new ManagerEntrepriseService();
                $services      = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager       = new ManagerCategorieProfessionnelle();
                $categories    = $manager->lister();
                $manager       = new ManagerPersonnalite();
                $personnalites = $manager->lister();
                $manager       = new ManagerBanque();
                $banques       = $manager->lister();
                $manager       = new ManagerCompteBanque();
                if ($employe->getIdEmploye() != "") {
                    $compteBanque = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
                } else {
                    $compteBanque = $manager->initialiser(); 
                }
                return [
                    'chefs'                     => $employes,
                    'postes'                    => $postes,
                    'employe'                   => $employe,
                    'banques'                   => $banques,
                    'services'                  => $services,
                    'compteBanque'              => $compteBanque,
                    'personnalites'             => $personnalites,
                    'categories'                => $categories,
                    'defaultMenuAutorisation'   => $this->getDefaultMenuAutorisation()
                ];
            } else {
                $this->rediriger();
            }
        }   

        /** 
         * Afficher le formulaire de modification d'un employé
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
        */
        public function afficherFormUpdateEmploye($parameters)
        {
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                $manager                   = new ManagerEmploye();
                $employe                   = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $employe->setSalaire($this->decrypter($employe->getSalaire()));
                $employe->setSalaireEnLettre($this->decrypter($employe->getSalaireEnLettre()));             
                $employes                  = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager                   = new ManagerEntreprisePoste();
                $postes                    = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager                   = new ManagerEntrepriseService();
                $services                  = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager                   = new ManagerCategorieProfessionnelle();
                $categorieProfessionnelles = $manager->lister(); 
                // @changelog 2020-06-12 [FIX] (Toky) Modification liée à la BDD
                $manager      = new ManagerContratEmploye();
                $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye() , 'statut' => self::VALIDATED]);
                if ($contratEmploye == null) {
                    $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye() , 'statut' => self::PROPOSED]);
                    if ($contratEmploye == null) {
                        $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye() , 'statut' => self::EMPTY]);
                        if ($contratEmploye == null) {
                            $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye() , 'statut' => self::EXPIRED]);
                        }
                    }
                }
                $managerServicePoste = new ManagerServicePoste();
                $servicePoste        = is_object($contratEmploye) ? $managerServicePoste->chercher(['idServicePoste' => $contratEmploye->getIdServicePoste()]) : $managerServicePoste->initialiser();
                $manager             = new ManagerEntreprisePoste(); 
                $posteEmploye        = is_object($contratEmploye) ? $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]) : $manager->initialiser();
                $manager             = new ManagerEntrepriseService(); 
                $serviceEmploye      = is_object($contratEmploye) ? $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]) : $manager->initialiser();
                $manager             = new ManagerCategorieProfessionnelle();
                $categorieProfessionnelle = is_object($contratEmploye) ? $manager->chercher(['idCategorieProfessionnelle' => $servicePoste->getIdCategorieProfessionnelle()]) : $manager->initialiser();
                $manager       = new ManagerPersonnalite();
                $personnalites  = $manager->lister();
                if (!empty($employe)) {
                    $manager = new ManagerCompte();
                    $compte = $manager->chercher(['idCompte' => $employe->getIdCompte()]);
                    $_SESSION['employe'] = $employe->toArray();
                    $_SESSION['compteEmploye'] = $compte->toArray();
                }
                return [
                    'chefs'                     => $employes,
                    'postes'                    => $postes,
                    'employe'                   => $employe,
                    'services'                  => $services,
                    'personnalites'             => $personnalites,
                    'contratEmploye'            => $contratEmploye,
                    'categorieProfessionnelles' => $categorieProfessionnelles,
                    'categorieEmploye'          => $categorieProfessionnelle,
                    'serviceEmploye'            => $serviceEmploye,
                    'posteEmploye'              => $posteEmploye,
                    'defaultMenuAutorisation'   => $this->getDefaultMenuAutorisation(),
                    'menuAutorizedByWorker'     => $this->getMenuAutorizedByWorker($employe->getIdEmploye())
                ];
            } else {
                $this->rediriger();
            }
        }

        /**
         * Retourner les postes dans un service
         *
         * @param array $parameters les critères du service
         *
         * @return array
        */
        public function getPostes($parameters) {
            if (!empty($parameters)) {
                $manager             = new ManagerEntrepriseService();
                $entrepriseService = $manager->chercher(['idEntrepriseService' => $parameters['idEntrepriseService']]);
                if ($entrepriseService->getIdEntreprise() == $_SESSION['user']['idEntreprise']) {
                    $manager             = new ManagerServicePoste();
                    $servicePostes       = $manager->lister([
                        'idEntrepriseService' => $parameters['idEntrepriseService']
                    ]);
                    $entreprisePostes = array();
                    $manager = new ManagerEntreprisePoste();
                    foreach ($servicePostes as $servicePoste) {
                        $entreprisePostes[] = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                    }
                    $entreprisePostes = array_unique($entreprisePostes);
                    $resultats = array();
                    foreach ($entreprisePostes as $entreprisePoste) {
                        $resultats[] = $entreprisePoste->toArray();
                    }
                    echo json_encode($resultats);
                    exit();
                }
            }
        }

        /**
         * Calculer la différence entre 2 dates
         *
         * @param date $date1 date1
         * @param date $date2 date2
         *
         * @return int nombre de mois
        */
        private function getDuree($date1, $date2)
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
            return intval($retour['day'] / 30); 
        }

        /**
         * Obtenir une date à partir d'une date de départ et une durée en jour
         *
         * @param date $date date de référence
         * @param int  $duree nombre de mois
         *
         * @return date
        */
        private function getDate($date , $duree)
        {
            $dateTimestamp = strtotime($date);
            return date('Y-m-d', strtotime('+' . $duree . ' month', $dateTimestamp));
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
                if (array_key_exists(0, $contratEmployes)) {
                    $contratEmploye  = $contratEmployes[0];
                }
                if ($contratEmploye == null) {
                    $contratEmploye  = $manager->initialiser();
                }
            }
            if ($contratEmploye != null) {
                $manager          = new ManagerContrat();
                $typeContrat      = $manager->chercher(['idContrat' => $contratEmploye->getType()]);
                if ($typeContrat != NULL) {
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
            } else {
                return null;
            }
        }

        /**
         * Obtenir la date de débauche d'un employé
         * 
         * @param int $idEmploye l'identifiant de l'employé
         *
         * @return date
        */
        private function getDateDebauche($idEmploye)
        {
            $manager          = new ManagerContratEmploye();
            $contratEmploye   = $manager->chercher(['idEmploye' => $idEmploye, 'statut' => self::VALIDATED]);
            if ($contratEmploye == null) {
                $contratEmployes = $manager->lister(['idEmploye' => $idEmploye, 'statut' => self::EXPIRED]);
                if (array_key_exists(0, $contratEmployes)) {
                    $contratEmploye  = $contratEmployes[0];
                }
                if ($contratEmploye == null) {
                    $contratEmploye  = $manager->initialiser();
                }
            }
            if ($contratEmploye != null) {
                $manager          = new ManagerContrat();
                $typeContrat      = $manager->chercher(['idContrat' => $contratEmploye->getType()]);
                if ($typeContrat != NULL) {
                    if (strtolower($typeContrat->getDesignation()) == strtolower(self::STAGE)) {
                        return $contratEmploye->getDateFin();
                    } else {
                        while ($contratEmploye->getSuivant() != self::NO) {
                            $manager      = new ManagerContratEmploye();
                            $tmp          = $manager->chercher(['idContratEmploye' => $contratEmploye->getSuivant()]);
                            if ($tmp->getStatut() != self::EMPTY && $tmp->getStatut() != self::PROPOSED) {
                                $manager      = new ManagerContrat();
                                $typeContrat  = $manager->chercher(['idContrat' => $tmp->getType()]);
                                if (strtolower($typeContrat->getDesignation()) == strtolower(self::CDI) 
                                    && $contratEmploye->getStatut() == self::VALIDATED
                                       && strtotime($contratEmploye->getDateFin()) == strtotime(date("0000-00-00"))
                                ) {
                                    return null;
                                } else {
                                    $contratEmploye = $tmp;
                                }
                            } else {
                                return null;
                            }
                        }
                        if (strtolower($typeContrat->getDesignation()) == strtolower(self::CDI) 
                            && $contratEmploye->getStatut() == self::VALIDATED
                              && strtotime($contratEmploye->getDateFin()) == strtotime(date("0000-00-00"))
                        ) {
                            return null;
                        } else {
                            return $contratEmploye->getDateFin();
                        }
                    }
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }

        /**
         * Récupérer tous les postes occupés
         *
         * @param int $idEmploye l'identifiant de l'employé
         *
         * @return array
        */
        public function getPostesOccupes($idEmploye)
        {
            $manager            = new ManagerContratEmploye();
            $contratEmployes    = array();
            $allContratEmployes = array();
            $manager  = new ManagerContratEmploye();
            $contratEmploye = $manager->chercher(['idEmploye' => $idEmploye, 'statut' => self::EMPTY]);
            if ($contratEmploye == null) {
                $contratEmploye = $manager->chercher(['idEmploye' => $idEmploye, 'statut' => self::PROPOSED]);
                 if ($contratEmploye == null) {
                    $contratEmploye = $manager->chercher(['idEmploye' => $idEmploye, 'statut' => self::VALIDATED]);
                     if ($contratEmploye == null) {
                        $contratEmploye = $manager->chercher(['idEmploye' => $idEmploye, 'statut' => self::EXPIRED]);
                    }
                }
            }
            $allContratEmployes[]     = $contratEmploye; 
            while ($contratEmploye->getPrecedent() != self::NO) {
                $tmp = $manager->chercher(['idContratEmploye' => $contratEmploye->getPrecedent()]);
                $allContratEmployes[] = $tmp;
                $contratEmploye       = $tmp;
            }
            unset($contratEmploye);
            unset($tmp);
            foreach ($allContratEmployes as $contratEmploye) {
                if (($contratEmploye->getStatut() == self::EXPIRED && $contratEmploye->getPrincipal() == self::NO)
                            || ($contratEmploye->getStatut() == self::VALIDATED && $contratEmploye->getPrincipal() == self::NO)
                ) {
                    $contratEmployes[] = $contratEmploye;
                }
            }
            $postesOccupes = array();
            foreach ($contratEmployes as $contratEmploye) {
                $manager        = new ManagerServicePoste();
                $servicePoste   = $manager->chercher(['idServicePoste' => $contratEmploye->getIdServicePoste()]);
                $tmp['debut']   = $contratEmploye->getDateDebut();
                if ($contratEmploye->getDateFin() != "0000-00-00") {
                    $tmp['fin'] = $contratEmploye->getDateFin();
                } else {
                    $tmp['fin'] = date('Y-m-d');
                }
                $manager        = new ManagerEntreprisePoste();
                $poste          = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                $tmp['poste']   = $poste->getPoste();
                $manager        = new ManagerCategorieProfessionnelle();
                $categorie      = $manager->chercher(['idCategorieProfessionnelle' => $servicePoste->getIdCategorieProfessionnelle()]);
                $tmp['categorie'] = $categorie->getDesignation();
                $postesOccupes[] = $tmp;
            }
            return $postesOccupes;
        } 

        /** 
         * Calculer l'ancienneté d'un employé
         * 
         * @param int idEmploye l'identifiant de l'employé
         *
         * @return array
        */
        private function getAnciennete($idEmploye)
        {
            $embauche = $this->getDateEmbauche($idEmploye);
            $debauche = $this->getDateDebauche($idEmploye);
            if ($debauche == null) {
                $dateFin = date("Y-m-d");
            } elseif (strtotime($debauche) > strtotime(date("Y-m-d"))) {
                $dateFin = date("Y-m-d");
            } else {
                $dateFin = $debauche;
            }
            $resultat             = $this->getDuree($embauche, $dateFin);
            $resultats['annees']  = intval($resultat / 12);
            $resultats['mois']    = intval($resultat % 12);
            return $resultats;
        }

        /**
         * Convertir une date en date complète
         *
         * @param date $date la date à convertir
         *
         * @return string
        */
        private function writeDate($date)
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
            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            return $months[$month - 1];
        }

        /**
         * Récupérer la template adaptée
         *
         * @param int $idContrat l'identifiant du type de contrat
         * @param int $idEntreprise l'identifiant de l'entreprise
         *
         * @return object
        */
        private function getTemplate($idContrat, $idEntreprise)
        {
            $manager  = new ManagerTemplate();
            $template = $manager->chercher([
                'idEntreprise' => $idEntreprise,
                'idContrat'    => $idContrat
            ]);
            if ($template != null) {
                return $template;
            } else {
                return $manager->chercher([
                    'idEntreprise' => self::DEFAULT_TEMPLATE,
                    'idContrat'    => $idContrat
                ]);
            }
        }

        /** 
         * Afficher le formulaire de modification d'un compte banque
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
        */
        public function afficherFormUpdateCompteBanque($parameters)
        {
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                $resultat = array();
                $manager  = new ManagerEmploye();
                $employe  = $manager->chercher($parameters);
                $manager  = new ManagerBanque();
                $banques  = $manager->lister();
                if (!empty($employe)) {
                    $manager      = new ManagerCompteBanque();
                    $compteBanque = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
                    if (empty($compteBanque)) {
                        $compteBanque = $manager->initialiser();
                    }
                    $resultat     = [
                        'employe'      => $employe,
                        'banques'      => $banques,
                        'compteBanque' => $compteBanque
                    ];
                }
                return $resultat;
            } else {
                $this->rediriger();
            }
        }  

        /** 
         * Récupérer le compte qu'on vient d'insérer
         *
         * @param array $parameters Les données à ajouter 
         *
         * @return object
        */
        private function getCompte($variable = array())
        {
            $compte     = "";
            $dataCompte = count($variable) > 1 ? $variable : $_SESSION['variable'];
            if (!empty($dataCompte)) {
                $dataCompte['motDePasse'] = md5($dataCompte['motDePasse']);
                $manager                  = new ManagerCompte();
                $compte                   = $manager->creerCompte($dataCompte);
            }
            return $compte;            
        }

        /** 
         * Insertion des nouvelles personnalités
         *
         * @param array $parameters Les données à insérer 
        */
        private function insertPersonnalite($parameters)
        {
            $qualites = explode("_", $parameters);
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

        /** 
         * Insertion d'un compte banque
         *
         * @param array $parameters Les données à insérer 
        */
        private function insertCompteBanque($parameters)
        {
            $manager = new ManagerCompteBanque();
            if ($parameters['idBanque'] != "" && $parameters['numeroCompte'] != "" && $parameters['idEmploye'] != "") {
                if (reset($parameters) == "") {
                    $manager->ajouter($parameters);
                } else {
                    $manager->modifier($parameters);
                }
            }
        }

        /**
         * Tester si possède des subordonnés
         *
         * @param array $parameters les critères de l'employé
         *
         * @return empty
        */
        public function hasSubordonne($parameters)
        {
            $manager = new ManagerEmploye();
            $liste   = $manager->lister(['chefHierarchique' => 'LIKE "%' . $parameters['idEmploye'] . '%"']);
            if (count($liste) > self::NO) {
                echo self::YES;
                exit();
            } else {
                echo self::NO;
                exit();
            }
        }

        /** 
         * Mettre à jour un employe
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourEmploye($parameters)
        {
            $employe            = "";
            $action             = "";
            $params             = array();
            $dataCompteBanque   = array();
            if (isset($parameters['idCompteBanque'])) {
                if (!is_null($parameters['idBanque'])) {
                    $dataCompteBanque['idCompteBanque'] = $parameters['idCompteBanque'];
                    $dataCompteBanque['idBanque']       = $parameters['idBanque'];
                    $dataCompteBanque['numeroCompte']   = $parameters['numeroCompte'];
                }
                unset($parameters['idCompteBanque']);
                unset($parameters['idBanque']);
                unset($parameters['numeroCompte']);
            }
            if (isset($parameters['qualite'])) {
                unset($parameters['qualite']);
            }
            if (isset($parameters['autreQualite'])) {
                unset($parameters['autreQualite']);
            }
            if (!empty($parameters['autrePersonnalite'])) {
                $this->insertPersonnalite($parameters['autrePersonnalite']);
            }
            unset($parameters['autrePersonnalite']);
            if (isset($parameters['autreDossier']) && $parameters['autreDossier'] == "") {
                unset($parameters['autreDossier']);
            }
            /** @changelog 2022-05-03 [OPT] (Lansky) Ajout un champ validateur demande de congé */
            if (isset($parameters['isValidator'])) {
                $parameters['is_validator'] = (int)$parameters['isValidator'];
                unset($parameters['isValidator']);
            }
            $variable   = $parameters['variable'] ?? array();
            if (isset($parameters['variable'])) {
                unset($parameters['variable']);
            }
            if (isset($parameters['soldeConge'])) {
                $soldeConge = $parameters['soldeConge'];
                unset($parameters['soldeConge']);
            }
            if (isset($parameters['myTeam'])) {
                $parameters['my_team'] = $parameters['myTeam'];
                unset($parameters['myTeam']);
            }
            if (isset($parameters['soldeConge'])) {
                $soldeConge = $parameters['soldeConge'];
                unset($parameters['soldeConge']);
            }
            if (isset($parameters['menuAutorised'])) {
                $params['menuAutorised'] = $parameters['menuAutorised'];
                unset($parameters['menuAutorised']);
                if (isset($parameters['addMenu'])) {
                    $params['addMenu']  = $parameters['addMenu'];
                    unset($parameters['addMenu']);
                }
            }
            if (!isset($parameters['salaire'])) {
                $number2Letter  = new ChiffreEnLettre();
                $parameters['salaire'] = 100000; // Salaire pardefaut
                $parameters['salaireEnLettre'] = $number2Letter->conversion($parameters['salaire']);
            }
            $manager    = new ManagerEmploye();
            if (array_key_exists('idEntreprisePoste', $parameters)) {
                $idEntreprisePoste  = $parameters['idEntreprisePoste'];
                if ($parameters['idEmploye'] == "") {
                    $compte                     = $this->getCompte($variable);
                    $parameters['idCompte']     = $compte->getIdCompte();
                    $idEntrepriseService        = $parameters['idEntrepriseService'];
                    $idCategorieProfessionnelle = $parameters['idCategorieProfessionnelle'];
                    $tmp = array();
                    foreach ($parameters as $key => $value) {
                        if ($key != "idEntrepriseService" && $key != "idEntreprisePoste" && $key != "idCategorieProfessionnelle"){
                            $tmp[$key] = $value;
                        } 
                    }
                    $parameters = $tmp;
                    $parameters['salaire'] = $this->crypter($parameters['salaire']);
                    $parameters['salaireEnLettre'] = $this->crypter($parameters['salaireEnLettre']);
                    $employe    = $manager->ajouter($parameters);           
                    $managerServicePoste = new ManagerServicePoste();
                    $servicePoste = $managerServicePoste->chercher([
                        "idEntreprisePoste"          => $idEntreprisePoste,
                        "idEntrepriseService"        => $idEntrepriseService,
                        "idCategorieProfessionnelle" => $idCategorieProfessionnelle
                    ]);
                    if ($servicePoste == null) {
                        $servicePoste = $managerServicePoste->ajouter([
                            "idEntreprisePoste"          => $idEntreprisePoste,
                            "idEntrepriseService"        => $idEntrepriseService,
                            "idCategorieProfessionnelle" => $idCategorieProfessionnelle
                        ]);
                    }
                    $manager        = new ManagerContratEmploye();
                    $contratEmploye = $manager->ajouter([
                        "idServicePoste" => $servicePoste->getIdServicePoste(),
                        "idEmploye"      => $employe->getIdEmploye(),
                        "statut"         => self::EMPTY
                    ]);
                    $manager        = new ManagerStockConge();
                    $stockConge     = $manager->ajouter([
                        "idEmploye" => $employe->getIdEmploye(),
                        "duree"     => isset($soldeConge) ? $soldeConge : self::NO,
                        "annee"     => date("Y")
                    ]);
                    $action = "ajouter";
                } else {
                    if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE && $this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                        $managerServicePoste = new ManagerServicePoste();
                        $servicePoste        = $managerServicePoste->chercher([
                            "idEntreprisePoste"          => $parameters["idEntreprisePoste"],
                            "idEntrepriseService"        => $parameters["idEntrepriseService"],
                            "idCategorieProfessionnelle" => $parameters["idCategorieProfessionnelle"]
                        ]);
                        if ($servicePoste == null) {
                            $servicePoste = $managerServicePoste->ajouter([
                                "idEntreprisePoste"          => $parameters["idEntreprisePoste"],
                                "idEntrepriseService"        => $parameters["idEntrepriseService"],
                                "idCategorieProfessionnelle" => $parameters["idCategorieProfessionnelle"]
                            ]);
                        }
                        unset($parameters['idEntreprisePoste']);
                        unset($parameters['idEntrepriseService']);
                        unset($parameters['idCategorieProfessionnelle']);
                        $parameters['salaire']          = $this->crypter($parameters['salaire']);
                        $parameters['salaireEnLettre']  = $this->crypter($parameters['salaireEnLettre']);             
                        $employe                        = $manager->modifier($parameters);
                        $manager                        = new ManagerContratEmploye();
                        $contratEmploye                 = $manager->lister(["idEmploye" => $employe->getIdEmploye()])[0];
                        if ($contratEmploye->getPrincipal() != self::NO) {
                            $contratEmploye = $manager->chercher(['idContratEmploye' => $contratEmploye->getPrincipal()]);
                        }
                        $this->authorizedWorker($params, $employe);
                        if ($servicePoste != null && $servicePoste->getIdEntreprisePoste() != self::NO && $servicePoste->getIdEntrepriseService() != self::NO) {
                            if ($contratEmploye->getStatut() == self::EMPTY || $contratEmploye->getStatut() == self::PROPOSED || $contratEmploye->getStatut() == self::VALIDATED) {
                                $manager = new ManagerContratEmploye();
                                $manager->modifier([
                                    "idContratEmploye"  => $contratEmploye->getIdContratEmploye(),
                                    "idServicePoste"    => $servicePoste->getIdServicePoste()
                                ]);
                                if ($contratEmploye->getEssai() != self::NO) {
                                    $essai =  $manager->chercher(['idContratEmploye' => $contratEmploye->getEssai()]);
                                    $manager->modifier([
                                        "idContratEmploye"  => $essai->getIdContratEmploye(),
                                        "idServicePoste"    => $servicePoste->getIdServicePoste()
                                    ]);
                                }
                                $action  = "modifier";
                                header("Location : " . HOST . "manage/update-contratEmploye?idEmploye=" . $parameters['idEmploye']);
                                exit();
                            }
                        }
                    } elseif ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                        $manager = new ManagerEmploye();
                        $manager->modifier($parameters);
                    } else {
                        $this->rediriger();
                    }  
                }
                if (!empty($dataCompteBanque)) {                
                    $dataCompteBanque['idEmploye'] = $employe->getIdEmploye();
                    $this->insertCompteBanque($dataCompteBanque);
                }
                $this->authorizedWorker($params, $employe);
                $manager = new ManagerEntreprisePoste();
                $poste   = $manager->chercher(['idEntreprisePoste' => $idEntreprisePoste]);
                $this->sendMailEmploye($employe, $poste, $action, $variable);
            }
            return $employe;
        }

        /**
         * Rappeler un compte à son utilisateur par email
         *
         * @param array $parameters les critères de l'utilisateur
         *
         * @return empty
        */
        public function sendCompte($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerEmploye();
                $employe = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $manager = new ManagerCompte();
                $compte  = $manager->chercher(['idCompte' => $employe->getIdCompte()]);
                if ($compte != null) {
                    $compte = $manager->modifier([
                        'idCompte'   => $compte->getIdCompte(),
                        'motDePasse' => md5($parameters['motDePasse'])
                    ]);
                    if ($compte->getMotDePasse() == md5($parameters['motDePasse'])) {
                        $compte = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
                        $_SESSION['variable']['login']      = $compte->getLogin();
                        $_SESSION['variable']['motDePasse'] = $parameters['motDePasse'];
                        $this->sendMailEmploye($employe, "null", "rappeler");
                        $_SESSION['info']['success'] = "Le mot de passe a été modifié et envoyé à l'utilisateur avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec de la modification";
                    }
                }
            }
        }

        /**
         * Envoyer un email à un employé après avoir mis à jour un employé
         * 
         * @param object $employe l'employé concerné
         * @param string $action l'action du mis à jour
         *
         * @return empty
        */
        private function sendMailEmploye($employe, $poste, $action, $args = array())
        {
            if (!empty($action) && !empty($employe) && !empty($poste)) {
                $to         = $employe->getEmail();
                $headers[]  = 'MIME-Version: 1.0';
                $headers[]  = 'Content-type: text/html; charset=iso-8859-1';
                $headers[]  = 'From: HumaNexus <' . strtolower($_SESSION['user']['email']) . '>';
                $variable   = count($args) > 0 ? $args : $_SESSION['variable'];
                if ($action == "ajouter") {
                    $subject = "Accès à HumaNexus auprès de la société " . strtoupper($_SESSION['user']['nom']);
                    $message = "<html><body>
                                    <div class='container'>
                                        <label>Bonjour " . $employe->getCivilite() . ' ' . strtoupper($employe->getNom()) . " ,</label><br><br>
                                        <label>Nous informons que nous venant de vous inscrire sur HumaNexus
                                        <br> au sein de notre société</label><br><br>
                                        <label>Ci-après votre login et mot de passe :</label><br><br>
                                        <strong>Login : </strong> " . $variable['login']  . "<br>
                                        <strong>Mot de passe : </strong> " . $variable['motDePasse'] . "<br><br>
                                        <label>Lien d'accès à votre compte : <a href=" . HOST . "connexion>se connecter</a></label><br><br>
                                        <label>Cordialement, </label><br><br>
                                        <label> La société " . strtoupper($_SESSION['user']['nom']) ."</label>
                                    </div>
                                </body></html>";
                    mail($to, $subject, $message, implode("\r\n", $headers));
                } elseif ($action == "rappeler") {
                    $subject = "Nouveau mot de passe";
                    $message = "<html><body>
                                    <div class='container'>
                                        <label>Bonjour " . $employe->getCivilite() . ' ' . strtoupper($employe->getNom()) . " ,</label><br><br>
                                        <label>Nous informons que nous venons de modifier votre mot de passe<br>
                                        <label>Ci-après votre login et mot de passe :</label><br><br>
                                        <strong>Login : </strong> " . $_SESSION['variable']['login']  . "<br>
                                        <strong>Mot de passe : </strong> " . $_SESSION['variable']['motDePasse'] . "<br><br>
                                        <label>Lien d'accès à votre compte : <a href=" . HOST . "connexion>se connecter</a></label><br><br>
                                        <label>Cordialement, </label><br><br>
                                        <label> La société " . strtoupper($_SESSION['user']['nom']) ."</label>
                                    </div>
                                </body></html>";
                    mail($to, $subject, $message, implode("\r\n", $headers));
                }
            }
        }

        /**
         * Supprimer une catégorie professionnelle
         *
         * @param array $parameters Critères de la catégorie professionnelle à supprimer
         *
         * @return empty
        */
        public function deleteCategorieProfessionnelle($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerCategorieProfessionnelle();
                $manager->supprimer(['idCategorieProfessionnelle' => $parameters['idCategorieProfessionnelle']]);
                header('Location:' . HOST . "manage/categorieProfessionnelles");
            }
        }

        /** 
         * Mettre à jour un compte banque et le type de paiement d'un employé
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
        */
        public function mettreAJourCompteBanque($parameters)
        {
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                $manager = new ManagerEmploye();
                $employe = $manager->modifier([
                    'idEmploye'    => $parameters['idEmploye'],
                    'typePaiement' => $parameters['typePaiement']
                ]);
                $typePaiement = $parameters['typePaiement'];
                unset($parameters['typePaiement']);
                $manager = new ManagerCompteBanque();
                if ($typePaiement != "en espèce") {
                    if (reset($parameters) == "") {
                        $manager->ajouter($parameters);
                    } else {
                        $manager->modifier($parameters);
                    }
                } else {
                    $compteBanque = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                    if (!empty($compteBanque)) {
                        $manager->supprimer(['idCompteBanque' => $compteBanque->getIdCompteBanque()]);
                    }
                }
            } else {
                $this->rediriger();
            }
        }

        /** 
         * Voir le détail d'un employé
         * 
         * @param array $parameters L'employé concerné
         *
         * @return array
        */
        public function voirDetailEmploye($parameters)
        {
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                $resultat = array();
                $manager  = new ManagerEmploye();
                if (isset($parameters)) {
                    $employe = $manager->chercher($parameters);
                    if (!empty($employe)) {
                        // @changelog 2020-06-11 [FIX] (Toky) Modification liée à la BDD
                        $chef         = $manager->chercher(['idEmploye' => $employe->getChefHierarchique()]);
                        $manager      = new ManagerCompte(); 
                        $compte       = $manager->chercher(['idCompte' => $employe->getIdCompte()]);
                        $manager      = new ManagerContratEmploye();
                        $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye() , 'statut' => self::VALIDATED]);
                        if ($contratEmploye == null) {
                            $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye() , 'statut' => self::PROPOSED]);
                            if ($contratEmploye == null) {
                                $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye() , 'statut' => self::EMPTY]);
                                if ($contratEmploye == null) {
                                    $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye() , 'statut' => self::EXPIRED]);
                                }
                            }
                        }
                        if ($contratEmploye == null) {
                            $contratEmploye = $manager->initialiser();
                        }
                        $manager      = new ManagerContrat();
                        $typeContrat  = $manager->chercher(['idContrat' => $contratEmploye->getType()]);   
                        $manager      = new ManagerServicePoste();
                        $servicePoste = $manager->chercher(['idServicePoste' => $contratEmploye->getIdServicePoste()]);
                        if ($servicePoste == null) {
                            $servicePoste = $manager->initialiser();
                        }
                        $manager      = new ManagerEntreprisePoste(); 
                        $poste        = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                        $manager      = new ManagerEntrepriseService(); 
                        $service      = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                        $manager      = new ManagerCategorieProfessionnelle();
                        $categorie    = $manager->chercher(['idCategorieProfessionnelle' => $servicePoste->getIdCategorieProfessionnelle()]);
                        $manager      = new ManagerCompteBanque();
                        $compteBanque = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
                        $banque       = "";
                        if (!empty($compteBanque)) {
                            $manager = new ManagerBanque();
                            $banque  = $manager->chercher(['idBanque' => $compteBanque->getIdBanque()]);
                        }
                        $resultat = [
                            'chef'           => $chef,
                            'poste'          => $poste,
                            'banque'         => $banque, 
                            'compte'         => $compte,
                            'service'        => $service,
                            'categorie'      => $categorie,
                            'employe'        => $employe,
                            'compteBanque'   => $compteBanque,
                            'contratEmploye' => $contratEmploye,
                            'typeContrat'    => $typeContrat,
                            'embauche'       => $this->writeDate($this->getDateEmbauche($employe->getIdEmploye())),
                            'debauche'       => $this->writeDate($this->getDateDebauche($employe->getIdEmploye())),
                            'anciennete'     => $this->getAnciennete($employe->getIdEmploye())
                        ];
                    }
                } 
                return $resultat;
            } else {
                $this->rediriger();
            }
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
                if ($parameters['idEmploye'] == $_SESSION['user']['idEmploye']) {
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
         * 
         *
         * @param string $mot le mot à crypter
         *
         * @return string
        */
        public function importerEmploye($parameters)
        {

            $managerEmploye = new ManagerEmploye();
            $managerCompte  = new ManagerCompte();
            $number2Letter  = new ChiffreEnLettre();
            $file           = $_FILES['file']['tmp_name'];
            $idCategorie    = 0;
            $countries      = unserialize(COUNTRIES);
            $dataInserted   = array();
            $tre = array();
            $extension      = explode('.', $_FILES['file']['name']);
            if (($handle = fopen($file, "r")) !== FALSE && end($extension) == 'csv') {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    // Enlever les espaces au debut et fin du string
                    foreach ($data as $key => $value) {
                        $data[$key] = trim($value);
                    }
                    if (array_key_exists(14, $data)) {
                        if ($data[14]=='') {
                            $data[14] = 100000; // Ajouter une valeur comme par defaut
                        }
                        if ($data[27]=='') {
                            $data[27] = '-'; // Ajouter une valeur si la cellule est vide
                        }
                    }
                    $isEmpty    = false;
                    $emptyKeys  = array_keys($data, '', true); // Collecter l'index du contenus vides
                    foreach ($emptyKeys as $val) {
                        if (($val <= 15 && $val != 3) || ($val >= 24 && $val <= 27)) { // Si l'un de ces indices est vide, alors intérrompre
                            $isEmpty = true;
                            break;
                        }
                    }
                    $tre[] = $data;
                    if ((!in_array('', $data) || !$isEmpty) && !strstr(strtolower($data[2]),'nom', true) && !strstr(strtolower($data[2]),'prénom', true)) {
                        if ($data[27]) {
                            $chefName   = $data[27];
                            $chefNom    = mb_strtoupper(substr($chefName, 0, strpos($chefName, ' ')));
                            $chefPrenom = ucwords(substr($chefName, strpos($chefName, ' ') + 1));
                            $chef       = $managerEmploye->chercher([
                                'nom'           => $chefNom,
                                'prenom'        => $chefPrenom,
                                'idEntreprise'  => $_SESSION['user']['idEntreprise']
                            ]);
                            if ($chef) {
                                $idChef = $chef->getIdEmploye();
                            }
                        }
                        if (!isset($idChef)) {
                            $idChef = '0';
                        }
                        $nom            = mb_strtoupper($data[1]);
                        $prenom         = ucwords($data[2]);
                        $loginName      = $this->generateName($nom, $prenom);
                        $pays           = trim($data[32]) ? trim($data[32]) : 'Madagascar';
                        $foundKey       = array_search(ucfirst($pays), array_column($countries, 'country'));
                        $contact        =  $countries[$foundKey]['code'] . '/' . trim($data[8]);
                        // Numéro de compte de la banque
                        $numCompte      = trim($data[17]) ? $data[17] : '10000000000000000000001' ;
                        $numCompte      = substr_replace(substr_replace(substr_replace($numCompte, ' ', 5, 0), ' ', 11, 0), ' ', -2, 0);
                        $result         = array(
                            // "photo"                         =>"",
                            "idCompte"                      => "", // Vide par defaut
                            "idEmploye"                     => "", // Vide par defaut
                            "idEntreprise"                  => $_SESSION['user']['idEntreprise'],
                            "civilite"                      => $this->civilite($data[0]),
                            "nom"                           => $nom,
                            "prenom"                        => $prenom,
                            "nombreEnfants"                 => is_int(trim($data[3])) ? trim($data[3]) : 0,
                            "dateNaissance"                 => $this->getFormatDate($data[4]),
                            "lieuNaissance"                 => ucwords($data[5]),
                            "adresse"                       => $data[6],
                            "ville"                         => ucfirst($data[7]),
                            "contact"                       => $contact,
                            "email"                         => $data[9],
                            "numeroCin"                     => $data[10],
                            "dateCin"                       => $this->getFormatDate($data[11]),
                            "lieuCin"                       => ucwords($data[12]),
                            "matricule"                     => $data[13],
                            "salaire"                       => $data[14],
                            "salaireEnLettre"               => trim($data[14]) ? $number2Letter->conversion($data[14]) : "Non définie",
                            "typePaiement"                  => $data[15],
                            "idCompteBanque"                =>"", // Vide par defaut
                            "idBanque"                      => $this->getBanque($data[16])->getIdBanque(),
                            "numeroCompte"                  => $numCompte,
                            "numeroCnaps"                   => trim($data[18]) ? $data[18] : 0000,
                            "statutCnaps"                   => trim($data[19]) ? $data[19] : 'en attente',
                            "osie"                          => $this->getBool($data[20]),
                            "avanceSalaire"                 => $this->getBool($data[21]),
                            "avanceSpeciale"                => $this->getBool($data[22]),
                            "isValidator"                   => $this->getBool($data[23]),
                            "idEntrepriseService"           => $this->getService($data[24])->getIdEntrepriseService(),
                            "idEntreprisePoste"             => $this->getPoste($data[25])->getIdEntreprisePoste(),
                            "idCategorieProfessionnelle"    => $this->getCategorieProfessionnelle($data[26])->getIdCategorieProfessionnelle(),
                            "chefHierarchique"              => $idChef,
                            "qualite"                       => trim($data[28]),
                            "autreQualite"                  => $this->modifyStr($data[29]),
                            "personnalite"                  => $this->modifyStr($data[30]),
                            "autrePersonnalite"             => $this->modifyStr($data[31]),
                            "statut"                        => '1', // 1 fona io fa tsy aiko  a
                            "soldeConge"                    => is_numeric(str_replace(',', '.', trim($data[33]))) ? str_replace(',', '.', trim($data[33])) : 0,
                            "variable"                      => array(
                                                                "identifiant"   =>  "employe",
                                                                "login"         => $loginName,
                                                                "motDePasse"    => $this->generatePassWord(8),
                                                                "statut"        =>"active"               
                                                            )
                        );
                        $account        = $managerEmploye->chercher([
                            "civilite"                      => $this->civilite($data[0]),
                            "nom"                           => $nom,
                            "prenom"                        => $prenom,
                            "numeroCin"                     => $data[10]
                        ]); // Chercher dans la base si le salarié a déjà un compte
                        if (!$account) {
                            $account   = $this->mettreAJourEmploye($result);
                            if ($account) {
                                $dataInserted[] = array('nom'=>$nom, 'prenom'=>$prenom, 'loginName' => $loginName );
                            }
                        }
                        $compte = $managerCompte->chercher(['idCompte' => $account->getIdCompte()]);
                        $liste  = array('employe' => $account, 'compte' => $compte);
                        $this->sendMailEntreprise($liste, true);
                    } else {
                        // On peut déclarer de initialiser des variables ici.
                        # Changer $data[int] en variable bien claire on dirait
                    }
                }
                fclose($handle);
                // Envoyer un maill de notiification à l'entrreprise pour informer la liste des employés qui ont été créé un compte
                if (count($dataInserted) > 0) {
                    $this->sendMailEntreprise($dataInserted, false);
                    $_SESSION['info']['success']    = "Import fait avec succès .";
                } else {
                    $_SESSION['info']['danger']     = "On ne peut pas importer certain(s) employé(s) !!!";
                }
            } else {
                $_SESSION['info']['danger']     = "Import intérrompu .";
            }
        }

        /**
         * 
         * Générer le mod de passe aléatoire
         *
         * @param int $length Longueur de la chaîne voulue
         * 
         * @return string
        */
        private function generatePassWord(int $length = 6): string
        {
            $asciiCodes     = range(33, 126);
            $codesLenght    = (count($asciiCodes)-1);
            shuffle($asciiCodes);
            $string = '';
            for($i = 1; $i <= $length; $i++){
                $previousChar = $char ?? '';
                $char = chr($asciiCodes[random_int(0, $codesLenght)]);
                while($char == $previousChar){
                    $char = chr($asciiCodes[random_int(0, $codesLenght)]);
                }
                $string .= $char;
            }
            return $string;
        }

        /**
         * 
         * Générer le nom de login ou identifiant
         *
         * @param string $nom Le nom en question
         * @param string $nom Le prénom en question
         * 
         * @return string
        */
        private function generateName(string $nom, string $prenom): string
        {
            $string     = '';
            $name       = '';
            $manager    = new ManagerCompte();
            $nom        = strtolower($nom);
            $prenom     = mb_strtolower($prenom);
            foreach (explode(' ', $prenom) as $name) {
                $old = $manager->chercher(['login' => $name, 'identifiant' => 'employe']);
                if (!$old) {
                    $string = $name;
                    break;
                }
            }
            if (!trim($string)) {
                $subName    = '-' . substr($nom, 0,3);
                $name       = substr($prenom, 0, strpos($prenom, ' ')) . $subName;
                $old        = $manager->chercher(['login' => $name, 'identifiant' => 'employe']);
                if (!$old) {
                    $string = $name;
                } else {
                    $string = $name . substr($nom, 3, 6);
                }
            }
            return $string;
        }

        /**
         * 
         * Récupérer un service dans une entreprise, créer s'il n'y en a pas
         *
         * @param string $service Le nom du service
         * 
         * @return object
        */
        private function getService(string $service): object
        {
            $bool       = false;
            $manager    = new ManagerEntrepriseService();
            if (trim($service)) {
                $services   = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                foreach ($services as $value) {
                    if (mb_strtolower($value->getService()) === mb_strtolower(trim($service))) {
                        $result = $value;
                        $bool   = true;
                        break;
                    }
                }
                if (!$bool) {
                    $result = $manager->ajouter([
                        'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                        'service'       => ucwords(trim($service))
                    ]);
                }
            } else {
                $result = $manager->initialiser();
            }
            return $result;
        }

        /**
         * 
         * Récupérer un poste dans une entreprise, créer s'il n'y en a pas
         *
         * @param string $poste     Le nom du poste
         * 
         * @return object
        */
        private function getPoste(string $poste): object
        {
            $bool       = false;
            $manager    = new ManagerEntreprisePoste();
            if (trim($poste)) {
                $postes = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                foreach ($postes as $value) {
                    if (mb_strtolower($value->getPoste()) === mb_strtolower(trim($poste))) {
                        $result = $value;
                        $bool   = true;
                        break;
                    }
                }
                if (!$bool) {
                    $result = $manager->ajouter([
                        'idEntreprise'              => $_SESSION['user']['idEntreprise'],
                        'poste'                     => ucwords(trim($poste)),
                        'capaciteExperienceExterne' => 'min',
                        'capaciteNiveauEtude'       => 'min',
                        'anneeExperienceInterne'    => 0,
                        'anneeExperienceExterne'    => 0,
                        'statut'                    => 0,
                        'idNiveauEtude'             => 6
                    ]);
                }
            } else {
                $result = $manager->initialiser();
            }
            return $result;
        }

        /**
         * 
         * Modifier une chaîne et éliminer les espaces debut et fin 
         *
         * @param string $str La chaîne à modifier
         * 
         * @return string
        */
        private function modifyStr($str)
        {
            $resp = '';
            if (trim($str)) {
                $tab = explode(',', trim($str));
                foreach ($tab as $val) {
                    $resp.= trim($val) . '_';
                }
            }
            return $resp;
        }

        /**
         * 
         * Récupérer la banque 
         *
         * @param string $code Le code de la banque
         * 
         * @return object
        */
        private function getBanque(string $code): object
        {
            $manager = new ManagerBanque();
            if (trim($code)) {
                $banque = $manager->chercher(['codeBanque' => strtoupper(trim($code))]);
                if (!$banque) {
                    $banque = $manager->chercher(['code_bic' => trim($code)]);
                }
                if (!$banque) {
                    $banque = $manager->chercher(['nomBanque' => trim($code)]);
                }
                if (!$banque) {
                    $banque = $manager->chercher(['sigle' => trim($code)]);
                }
                if (!$banque) {
                    $banque = $manager->initialiser();
                }
            } else {
                $banque = $manager->initialiser();
            }
            return $banque;
        }

        /**
         * 
         * Récupérer la banque 
         *
         * @param string $category La catégorie professionnelle
         * 
         * @return object
        */
        private function getCategorieProfessionnelle(string $category): object
        {
            $manager = new ManagerCategorieProfessionnelle();
            if (trim($category)) {
                $response = $manager->chercher(['designation' => trim($category)]);
                if (!$response) {
                    $response = $manager->ajouter(['designation' => trim($category)]);
                    if (intval($response->getIdCategorieProfessionnelle()) < 1) {
                        $response = $manager->chercher(['designation' => trim($category)]);
                    }
                }
            } else {
                #ra tsis d atao am le farany ambany
                $response = $manager->initialiser();
            }
            return $response;
        }

        /**
         * 
         * 
         *
         * @param string $str
         * 
         * @return string
        */
        private function civilite(string $str): string
        {
            $string = strtolower($str);
            if ($string == 'monsieur' ||$string == 'monsieurs' || $string == 'messieur'  || $string == 'mr' || $string == 'm.' || $string == 'messieurs') {
                $string = 'Mr';
            } elseif($string == 'madame' || $string == 'mme' ||  $string == 'mm' || $string == 'mesdame' || $string == 'medame' || $string == 'mesdames'||  $string == 'mame'|| strstr($string,'dam')) {
                $string = 'Mme';
            } elseif($string == 'mademoiselle' || $string == 'mademoiselles' || $string == 'mlle' || $string == 'mll') {
                $string = 'Mlle';
            } else{
                $string = 'Autre';
            }
            
            return $string;
        }

        /**
         * 
         * Vérifier la format de la date 
         *
         * @param string $date La date
         * 
         * @return string
        */
        private function getFormatDate(string $date): string
        {
            if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/",$date)) {
                $date   = str_replace('/', '-', $date);
                $result = date("Y-m-d", strtotime($date));
            } elseif (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",$date)) {
                $result = date("Y-m-d", strtotime($date));
            } elseif (preg_match("/^[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/",$date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
                $result = $date;
            } else {
                $result = $date;
            }
            return $result;
        }

        /**
         * 
         * Modifier le contenue de la valeur oui et non 
         *
         * @param string $str La chaîne à modifier
         * 
         * @return string
        */
        private function getBool(string $str): string
        {
            return (strtolower($str) == 'oui' && trim($str)) ? '1' : '0';
        }

        /**
         * Envoyer un email à l'entreprise après avoir importé ses employés (nouveau compte employé)
         * 
         * @param array $liste  La lilste des employés créée
         *
         * @return empty
        */
        private function sendMailEntreprise($liste = array(), $avoirCompte = false)
        {
            $manager    = new ManagerEmailContact();
            $email      = $manager->chercher(['type' => 'information']);
            $to         = $_SESSION['user']['email'];
            $headers[]  = 'MIME-Version: 1.0';
            $headers[]  = 'Content-type: text/html; charset=iso-8859-1';
            $headers[]  = 'From: HumaNexus <' . $email->getEmail() . '>';
            if ($avoirCompte) {
                extract($liste);
                $subject    =  "Création du compte ";
                $message    = "<html><body>
                                <div class='container'>
                                    <label>Bonjour ,</label><br><br>
                                    <label>Nous vous informons que le compte de" . $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() . " a été créés avec succès.</label>
                                        <br><label>Login  :  " . $compte->getLogin() . "</label><br>
                                        <label>Lien d'accès  :  " . HOST . "connexion</label><br>
                                    <label>Veuillez nous contacter si besoin : <a href=https://hco.mg/contact/>ici</a></label><br><br>
                                    <label>Cordialement , </label><br><br>
                                    <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                </div>
                            </body></html>";
            } else {
                # Tri par la colonne nom dans l'ordre croissant alphabetique
                $columns    = array_column($liste, 'nom'); 
                array_multisort($columns, SORT_ASC, $liste);
                $subject    = "Accès à HumaNexus auprès de votre société : " . strtoupper($_SESSION['user']['nom']);
                $message    = "<html><body>
                                <div class='container'>
                                    <label>Bonjour ,</label><br><br>
                                    <label>Nous vous informons que la création des comptes de vos employés est effectué.</label>
                                        <br></label>Voici la liste de vos employés ainsi que leur nom de login :</label><br>";
                                    foreach ($liste as $key => $employe) {
                                        $message .= "<label>&nbsp;&nbsp;&nbsp;&nbsp;" . ($key + 1) . " ) " . strtoupper($employe['nom']) . " " . ucwords($employe['prenom']) . " : <strong>  " . $employe['loginName'] . " </strong></label><br>";
                                    }
                $message    .=       "<label>Lien d'accès : " . HOST . "connexion</label><br>
                                    <label>Veuillez nous contacter si besoin <a href=https://hco.mg/contact/>ici</a></label><br><br>
                                    <label>Cordialement , </label><br><br>
                                    <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                </div>
                            </body></html>";
            }
            mail($to, $subject, $message, implode("\r\n", $headers));
        }

        /**
         * Envoyer un email à l'entreprise après avoir importé ses employés (nouveau compte employé)
         * 
         * @param array $liste  La lilste des employés créée
         *
         * @return empty
        */
        public function exporterTemplate($parameters)
        {
            $file_name = $parameters['fichier'];
            if (file_exists(DOC_ROOT . "Ressources/fichiers/template/" . $file_name)) {
                $phpDocx = new PhpDocx(DOC_ROOT . "Ressources/fichiers/template/" . $file_name);
                $phpDocx->download($file_name);
            } else {
                // $_SESSION['notification']['type'] = self::NOTIFICATION_ERROR;
                $_SESSION['notification']['titre'] = "Erreur de téléchargement";
                $_SESSION['notification']['message'] = "Le fichier n'a pas été trouvé, veuillez en spécifier un autre";
            }
        }

        /**
         * Donner une autorisation au salarié un certain menu
         * 
         * @param array $paramters  Les paramètres utilent
         * @param object $employe   L'employé concerné
         *
         * @return empty
        */
        private function authorizedWorker($parameters, $employe)
        {
            if (array_key_exists('addMenu', $parameters)) {
                if ($parameters['addMenu'] == 'on') {
                    $manager                    = new ManagerAutorized();
                    $autorize                   = $manager->chercher(['id_employe' => $employe->getIdEmploye()]);
                    $method                     = (!$autorize) ? 'ajouter' : 'modifier';
                    $accessGiven                = get_object_vars(json_decode($parameters['menuAutorised'])); // Convertir objet en array
                    $accessGiven['RECRUTEMENT'] = get_object_vars($accessGiven['RECRUTEMENT']);
                    foreach ($accessGiven['RECRUTEMENT'] as $clef => $values) {
                        foreach ($values as $key => $value) {
                            $accessGiven['RECRUTEMENT'][$clef][$key] = get_object_vars($value);
                        }
                    }
                    $autorize   = $manager->$method([
                        'id_autorized'  => $autorize ? $autorize->getIdAutorized() : '',
                        'id_employe'    => $employe->getIdEmploye(),
                        'add_menu'      => serialize($accessGiven)
                    ]);
                }
            }
        }

        /**
         * Recupérer les menus à autoriser au salarié
         *
         * @param empty
         *
         * @return array
        */ 
        private function getDefaultMenuAutorisation() {
            $manager        = new ManagerMenuEntreprise();
            $menuEntreprise = $manager->chercher(['id_entreprise' => $_SESSION['user']['idEntreprise']]);
            return array('RECRUTEMENT' => $menuEntreprise->getContaint()['entreprise']['RECRUTEMENT']);
        }
        
        /**
         * Recupérer les menus à autoriser au salarié
         *
         * @param int $idEmploye L'identifiant du salarié
         *
         * @return array
        */ 
        private function getMenuAutorizedByWorker($idEmploye) {
            $manager = new ManagerAutorized();
            $response = $manager->chercher(['id_employe' => $idEmploye]);
            return $response ? $response->getAddMenu() : array();
        }
        
        /**
         * Recupérer chiffre en lettre
         *
         * @param array $parameters
         *
         * @return json
        */ 
        public function getLetterJson($parameters) {
            extract($parameters);
            $manager = new ChiffreEnLettre();
            echo json_encode($manager->conversion($number));
            exit;        
        }
    }