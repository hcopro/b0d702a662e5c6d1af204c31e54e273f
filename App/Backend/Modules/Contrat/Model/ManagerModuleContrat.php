<?php
    
    /**
     * Manager du modules Contrat du Backend
     *
     * @author Toky
     *
     * @since 07/09/20
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
    use \Model\ManagerTemplate;
    use \Model\ManagerCategorieProfessionnelle;
    use \Model\ManagerMission;
    use \Model\ManagerConfiguration;
    use \Model\ManagerStockConge;
    require_once "Lib/Core/PhpDocx.php";

	class ManagerModuleContrat extends DbManager
	{
        const RENOUVELLEMENT              = 'renouvellement';
        const PROLONGEMENT                = 'prolongement';
        const CONTRAT                     = 'contrat';
        const ATTESTATION                 = 'attestation';
        const CERTIFICAT                  = 'certificat';
        const EDIT_PROLONGEMENT           = 'edit-prolongement';
        const EDIT_RENOUVELLEMENT         = 'edit-renouvellement';
        const NEW_CONTRAT                 = 'nouveau-contrat';
        const HISTORIQUE_PAGE             = 'historique';
        const GESTION_PAGE                = 'gestion';
        const TWO_YEARS                   = 24;
        const ONE_MONTH                   = 1;
        const ZERO_DAY                    = 0;
        const TWO_TIMES                   = 2;
        const ZERO_TIME                   = 0;
        const ONE_TIME                    = 1;
        const EMPTY                       = 0;
        const PROPOSED                    = 1;
        const VALIDATED                   = 2;
        const EXPIRED                     = 3;
        const ALL_STATUS                  = 4;
        const SOON_EXPIRED                = 5;
        const CDI                         = 'CDI';
        const CDD                         = 'CDD';
        const CDE                         = 'CDE';
        const CA                          = 'CA';
        const STAGE                       = 'STAGE';
        const INTERIM                     = 'INTERIM';
        const ALL_TYPE                    = 0;
        const NOTHING                     = '...';
        const YES                         = 1;
        const NO                          = 0;
        const TEMPLATE_RENOUVELLEMENT     = 0;
        const TEMPLATE_ATTESTATION        = -1;
        const TEMPLATE_CERTIFICAT         = -2;
        const USER_ENTREPRISE             = 'entreprise';
        const USER_EMPLOYE                = 'employe';
        const USER_ADMIN                  = 'superadmin';
        const STOCK_MENSUEL               = 2.5;
        const DEFAULT_TEMPLATE            = 0;
        const RENOUVELLEMENT_TEMPLATE     = 0;
        const ATTESTATION_TEMPLATE        = -1;
        const CERTIFICAT_TEMPLATE         = -2;
        const THIRD_WARNING               = 1;
        const SECOND_WARNING              = 2;
        const FIRST_WARNING               = 3;
        const NOTIFICATION_ERROR          = 'error';
        const NOTIFICATION_SUCCESS        = 'success';       
        const CRON_JOB                    = '50 12 * * * /usr/bin/php ' . CRON_DIR . 'alerte.php';  
        const WARNING_CONFIGURATION_PAGE  = 'alerte';
        const DOCUMENT_CONFIGURATION_PAGE = 'document';
        const SINGLE_WARNING              = 1;
        const TWO_WARNING                 = 2;
        const THREE_WARNING               = 3;
        const FILTER_GROUP_ALL            = 1;
        const FILTER_GROUP_SERVICE        = 2;
        const FILTER_GROUP_POSTE          = 3;
        const FILTER_GROUP_EMPLOYE        = 4;
        const POSTE_INTERNE               = 0;


        /**
         * Lister les contrats des employés
         *
         * @param array $parameters les données à lister
         *
         * @return array
         */
        public function listerContratEmployes($parameters)
        {
            $manager     = new ManagerEntreprise();
            $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($parameters)) {
                $manager        = new ManagerEntreprise();
                $entreprise     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $donnees        = array();
                if (!empty($parameters['groupe'])) {
                    if ($parameters['groupe'] == self::FILTER_GROUP_ALL) {
                        $manager  = new ManagerEmploye();
                        $employes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                        $donnees  = $this->getContrats($employes, $parameters['type'], $parameters['statut']);
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
                $donnees = $this->getContrats($employes, $parameters['type'], $parameters['statut']);
                $view = new View("listerContrats");
                $view->sendWithoutTemplate("Backend", "Contrat", $donnees, "entreprise"); 
                exit();
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
         * Récupérer les contrats des employés
         * 
         * @param array $employes une liste d'employes
         * @param int   $type     le type de contrat
         * @param int   $statut   le statut du contrat
         *
         * @return array
         */
        private function getContrats($employes, $type, $statut)
        {
            $resultats = array();
            foreach ($employes as $employe) {
                $tmp['employe'] = $employe;
                $tmp['contratEmploye'] = $this->getContratActuel($employe);
                if ($tmp['contratEmploye'] != null) {
                    if ($tmp['contratEmploye']->getStatut() == self::PROPOSED) {
                        $tmp['statut'] = 'en attente'; 
                    } elseif ($tmp['contratEmploye']->getStatut() == self::VALIDATED) {
                        $tmp['statut'] = 'en cours'; 
                    } elseif ($tmp['contratEmploye']->getStatut() == self::EXPIRED) {
                        $tmp['statut'] = 'expiré';
                    }
                    $manager = new ManagerContrat();
                    $tmp['contrat'] = $manager->chercher(['idContrat' => $tmp['contratEmploye']->getType()]);
                    if ($type != self::ALL_TYPE) {
                        if ($tmp['contrat']->getIdContrat() != $type) {
                            continue;
                        }
                    }
                } else {
                    $tmp['contrat'] = null;
                }
                if ($statut != self::ALL_STATUS) {   
                    if ($statut == self::SOON_EXPIRED) {
                        
                    } elseif ($statut != $tmp['contratEmploye']->getStatut()) {
                        continue;
                    }
                }
                $poste  = $this->getPosteEmploye($employe);
                if ($poste != false) {
                    $tmp['poste'] = $poste->getPoste();
                } else {
                    $tmp['poste'] = null;
                }
                $service = $this->getServiceEmploye($employe);
                if ($service != false) {
                    $tmp['service'] = $service->getService();
                } else {
                    $tmp['service'] = null;
                }
                $categorie = $this->getCategorieEmploye($employe);
                if ($categorie != false) {
                    $tmp['categorie'] = $categorie->getDesignation();
                } else {
                    $tmp['categorie'] = null;
                }
                $resultats[] = $tmp;
            }
            return $resultats;
        }

        /**
         * Récupérer le contrat actuel d'un employé
         * 
         * @param object $employe  l'employé
         *
         * @return object
         */
        private function getContratActuel($employe)
        {
            $today    = date('Y-m-d');
            $manager  = new ManagerContratEmploye();
            $contratEmploye = $manager->lister(['idEmploye' => $employe->getIdEmploye()]);
            if ($contratEmploye != null) {
                $contratEmploye = $manager->lister(['idEmploye' => $employe->getIdEmploye()])[0];
                while (strtotime($today) < strtotime($contratEmploye->getDateDebut()) || $contratEmploye->getStatut() == self::EMPTY) {
                    if ($contratEmploye->getPrecedent() != self::NO) {
                        $contratEmploye = $manager->chercher(['idContratEmploye' => $contratEmploye->getPrecedent()]);
                    } elseif ($contratEmploye->getStatut() != self::EMPTY) {
                        return $contratEmploye;
                    } else {
                        return null;
                    }
                }
                if ($contratEmploye->getPrincipal() != self::NO) {
                    $contratEmploye = $manager->chercher(['idContratEmploye' => $contratEmploye->getPrincipal()]);
                }
                return $contratEmploye;
            } else {
                return null;
            }
        }

        /**
         * Voir l'interface concernant les contrats
         *
         * @param $parameters les critères des données à afficher
         *
         * @return array
         */
        public function voirContrat($parameters)
        {
            $manager     = new ManagerEntreprise();
            $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $filtres     = $this->getFiltre($entreprise->getIdEntreprise());
            return [
                'entreprise' => $entreprise,
                'filtres'    => $filtres
            ];
        }

        /**
         * Voir l'interface concernant les templates
         *
         * @param $parameters les critères des données à afficher
         *
         * @return array
         */
        public function voirTemplateContrat($parameters)
        {
            if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                $idEntreprise = $_SESSION['user']['idEntreprise'];
            } elseif ($_SESSION['compte']['identifiant'] == self::USER_ADMIN) {
                $idEntreprise = self::NO;
            }
            $documents     = array();
            $manager       = new ManagerContrat();
            $contrats      = $manager->lister(['offreUniquement' => self::NO]);
            foreach ($contrats as $contrat) {
                $tmp['typeContrat'] = $contrat;
                $tmp['template']    = $this->getTemplate($contrat->getIdContrat(), $idEntreprise);
                $documents[] = $tmp;
            }
            $contrat = $manager->Initialiser();
            $contrat->setIdContrat(self::RENOUVELLEMENT_TEMPLATE);
            $contrat->setDesignation(self::RENOUVELLEMENT);
            $tmp['typeContrat'] = $contrat;
            $tmp['template']    = $this->getTemplate($contrat->getIdContrat(), $idEntreprise);
            $documents[] = $tmp;
            $contrat = $manager->Initialiser();
            $contrat->setIdContrat(self::ATTESTATION_TEMPLATE);
            $contrat->setDesignation(self::ATTESTATION);
            $tmp['typeContrat'] = $contrat;
            $tmp['template']    = $this->getTemplate($contrat->getIdContrat(), $idEntreprise);
            $documents[] = $tmp;
            $contrat = $manager->Initialiser();
            $contrat->setIdContrat(self::CERTIFICAT_TEMPLATE);
            $contrat->setDesignation(self::CERTIFICAT);
            $tmp['typeContrat'] = $contrat;
            $tmp['template']    = $this->getTemplate($contrat->getIdContrat(), $idEntreprise);
            $documents[] = $tmp;
            return [
                'documents' => $documents,
                'page'      => $parameters['page']
            ];
        }

        /**
         * Voir l'interface concernant les paramètres
         *
         * @param $parameters les critères des données à afficher
         *
         * @return array
         */
        public function voirParametreContrat($parameters)
        {
            $manager       = new ManagerEntreprise();
            $entreprise    = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager       = new ManagerConfiguration();
            $configuration = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if ($configuration == null) {
                $configuration = $manager->ajouter([
                    'idEntreprise'   => $_SESSION['user']['idEntreprise'],
                    'emailAlerte'    => $entreprise->getEmail(),
                    'nombreAlerte'   => self::THREE_WARNING,
                    'premiereAlerte' => self::FIRST_WARNING,
                    'deuxiemeAlerte' => self::SECOND_WARNING,
                    'troisiemeAlerte'=> self::THIRD_WARNING
                ]);
            }
            return [
                'configuration' => $configuration,
                'page'          => $parameters['page'],
                'alerteActive'  => $this->alerteActive($entreprise)
            ];
        }

        /**
         * Afficher le formulaire de configuration
         *
         * @param $parameters Les données à récupérer
         *
         * @return object
         */
        public function voirConfiguration($parameters)
        {
            if ($parameters['idEntreprise'] == $_SESSION['user']['idEntreprise']) {
                if ($parameters['page'] == self::WARNING_CONFIGURATION_PAGE) {
                    $manager       = new ManagerEntreprise();
                    $entreprise    = $manager->chercher(['idEntreprise' => $parameters['idEntreprise']]);
                    $manager       = new ManagerConfiguration();
                    $configuration = $manager->chercher(['idEntreprise' => $parameters['idEntreprise']]);
                    if ($configuration == null) {
                        $configuration = $manager->ajouter([
                            'idEntreprise'   => $parameters['idEntreprise'],
                            'emailAlerte'    => $entreprise->getEmail(),
                            'nombreAlerte'   => self::THREE_WARNING,
                            'premiereAlerte' => self::FIRST_WARNING,
                            'deuxiemeAlerte' => self::SECOND_WARNING,
                            'troisiemeAlerte'=> self::THIRD_WARNING
                        ]);
                    }
                    return [
                        'configuration' => $configuration,
                        'page'          => $parameters['page'],
                        'alerteActive'  => $this->alerteActive($entreprise)
                    ];
                } elseif ($parameters['page'] == self::DOCUMENT_CONFIGURATION_PAGE) {
                    $documents     = array();
                    $manager       = new ManagerContrat();
                    $contrats      = $manager->lister(['offreUniquement' => self::NO]);
                    foreach ($contrats as $contrat) {
                        $tmp['typeContrat'] = $contrat;
                        $tmp['template']    = $this->getTemplate($contrat->getIdContrat(), $parameters['idEntreprise']);
                        $documents[] = $tmp;
                    }
                    $contrat = new Contrat();
                    $contrat->setIdContrat(self::RENOUVELLEMENT_TEMPLATE);
                    $contrat->setDesignation(self::RENOUVELLEMENT);
                    $tmp['typeContrat'] = $contrat;
                    $tmp['template']    = $this->getTemplate($contrat->getIdContrat(), $parameters['idEntreprise']);
                    $documents[] = $tmp;
                    $contrat = new Contrat();
                    $contrat->setIdContrat(self::ATTESTATION_TEMPLATE);
                    $contrat->setDesignation(self::ATTESTATION);
                    $tmp['typeContrat'] = $contrat;
                    $tmp['template']    = $this->getTemplate($contrat->getIdContrat(), $parameters['idEntreprise']);
                    $documents[] = $tmp;
                    $contrat = new Contrat();
                    $contrat->setIdContrat(self::CERTIFICAT_TEMPLATE);
                    $contrat->setDesignation(self::CERTIFICAT);
                    $tmp['typeContrat'] = $contrat;
                    $tmp['template']    = $this->getTemplate($contrat->getIdContrat(), $parameters['idEntreprise']);
                    $documents[] = $tmp;
                    return [
                        'documents' => $documents,
                        'page'      => $parameters['page']
                    ];
                }  
            } else {
                $this->rediriger();
            }
        }

        /** 
         * Afficher le formulaire de gestion de contrat
         * 
         * @param $parameters Les données à récupérer
         * 
         * @return object
         */
        public function afficherFormUpdateContratEmploye($parameters)
        {
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                $page = array_key_exists('page', $parameters)? $parameters['page'] : null;
                if ($page == self::HISTORIQUE_PAGE) {
                    $manager            = new ManagerEmploye();
                    $employe            = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                    $employe->setSalaire($this->decrypter($employe->getSalaire()));
                    $employe->setSalaireEnLettre($this->decrypter($employe->getSalaireEnLettre()));             
                    $manager            = new ManagerContratEmploye();
                    $contratEmployes    = array();
                    $allContratEmployes = array();
                    $manager  = new ManagerContratEmploye();
                    $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::EMPTY]);
                    if ($contratEmploye == null) {
                        $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::PROPOSED]);
                        if ($contratEmploye == null) {
                            $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
                            if ($contratEmploye == null) {
                                $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye() , 'statut' => self::EXPIRED]);
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
                    $informations = array();
                    /*
                     *  $tmp variable temporaire pour stocker les informations sur chaque contrat 
                     */
                    foreach ($contratEmployes as $contratEmploye) {
                        $tmp['contratEmploye'] = $contratEmploye;
                        $manager               = new ManagerContrat();
                        $tmp['typeContrat']    = $manager->chercher(['idContrat' => $contratEmploye->getType()]);
                        $manager               = new ManagerServicePoste();
                        $servicePoste          = $manager->chercher(['idServicePoste' => $contratEmploye->getIdServicePoste()]);
                        $manager               = new ManagerEntreprisePoste();
                        $posteEmploye          = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                        $tmp['posteEmploye']   = $posteEmploye;
                        $manager               = new ManagerCategorieProfessionnelle();
                        $categorieEmploye      = $manager->chercher(['idCategorieProfessionnelle' => $servicePoste->getIdCategorieProfessionnelle()]);
                        $tmp['categorieEmploye'] = $categorieEmploye;
                        $manager               = new ManagerEntrepriseService();
                        $serviceEmploye        = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                        $tmp['serviceEmploye'] = $serviceEmploye;
                        array_push($informations, $tmp);         
                    }
                    return [
                        'employe'      => $employe,
                        'informations' => $informations,
                        'page'         => self::HISTORIQUE_PAGE
                    ];
                } else {
                    $manager  = new ManagerEmploye();
                    $employe  = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                    $employe->setSalaire($this->decrypter($employe->getSalaire()));
                    $employe->setSalaireEnLettre($this->decrypter($employe->getSalaireEnLettre()));             
                    $manager  = new ManagerContrat();
                    $contrats = $manager->lister(['offreUniquement' => self::NO]);
                    if (empty($parameters['idContratEmploye'])) {
                        $manager  = new ManagerContratEmploye();
                        $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::EMPTY]);
                        if ($contratEmploye == null) {
                            $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::PROPOSED]);
                            if ($contratEmploye == null) {
                                $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
                                if ($contratEmploye == null) {
                                    $contratEmploye = $manager->chercher(['idEmploye' => $employe->getIdEmploye() , 'statut' => self::EXPIRED]);
                                }
                            }
                        }
                    } else {
                        $manager = new ManagerContratEmploye();
                        $contratEmploye = $manager->chercher([
                            'idContratEmploye' => $parameters['idContratEmploye'],
                            'idEmploye'        => $parameters['idEmploye']
                        ]);
                        if ($contratEmploye == null) {
                            $this->rediriger();
                        }
                    }
                    if (!$contratEmploye) {
                        $manager = new ManagerContratEmploye();
                        $contratEmploye = $manager->chercher([
                            'idEmploye'        => $parameters['idEmploye']
                        ]);
                    }
                    $managerServicePoste = new ManagerServicePoste();
                    $servicePoste        = $managerServicePoste->chercher(['idServicePoste' => $contratEmploye->getIdServicePoste()]);
                    $manager             = new ManagerEntreprisePoste(); 
                    $posteEmploye        = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                    $manager             = new ManagerEntrepriseService();
                    $serviceEmploye      = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                    $manager             = new ManagerCategorieProfessionnelle();
                    $categorieEmploye    = $manager->chercher(['idCategorieProfessionnelle' => $servicePoste->getIdCategorieProfessionnelle()]);
                    if ($contratEmploye->getStatut() != self::EMPTY) {
                        $manager             = new ManagerContrat();
                        $typeContrat         = $manager->chercher(['idContrat' => $contratEmploye->getType()]);
                        if ( $typeContrat->getDesignation() == self::CDI ) {
                            $situation['duree_totale']   = self::NOTHING;
                            $situation['duree_restante'] = self::NOTHING;
                        } else {
                            $situation['duree_totale'] = $this->getDuree($contratEmploye->getDateDebut(), $contratEmploye->getDateFin());
                            /**
                            * date $tmp date de référence pour calculer la durée restante du contrat
                            * soit la date de début
                            * soit la date du jour
                            */
                            if (strtotime($contratEmploye->getDateFin()) >= strtotime(date("Y-m-d"))) {
                                $tmp = (strtotime($contratEmploye->getDateDebut()) > strtotime(date("Y-m-d"))) ? $contratEmploye->getDateDebut() : date("Y-m-d");
                                $situation['duree_restante'] = $this->getDuree($contratEmploye->getDateFin(), $tmp);
                            } else {
                                $situation['duree_restante'] = self::ZERO_TIME;
                            }
                        }
                        $manager = new ManagerRenouvellement();
                        $renouvellements = $manager->lister(['idContratEmploye' => $contratEmploye->getIdContratEmploye()]);
                        $compteur_renouvellement = 0 ;
                        foreach ($renouvellements as $renouvellement) {
                            if ($renouvellement->getStatut() == self::VALIDATED) {
                                $compteur_renouvellement++ ;
                            }
                        }
                        /**
                        * Calcul de la situation actuelle du contrat en cours
                        */
                        if ($typeContrat->getDesignation() == self::CDD) {
                            $situation['nouvelle_duree_possible'] = self::TWO_YEARS - $situation['duree_totale'];
                            $situation['renouvellement_possible'] = self::TWO_TIMES - $compteur_renouvellement;
                            if ($situation['renouvellement_possible'] > self::ZERO_TIME
                                && $situation['nouvelle_duree_possible'] > self::ZERO_TIME 
                                   && $contratEmploye->getStatut() == self::VALIDATED
                            ) {
                                if (!empty($renouvellements[0]) && $renouvellements[0]->getStatut() == self::PROPOSED) {
                                    $date1 = $renouvellements[0]->getDateDebut();
                                    $date2 = $renouvellements[0]->getDateFin();
                                } else {
                                    $date1 = $contratEmploye->getDateFin();
                                    $date2 = "";
                                }
                            } else {
                                $date1 = $contratEmploye->getDateDebut();
                                $date2 = $contratEmploye->getDateFin();
                            }
                        } elseif ($typeContrat->getDesignation() == self::CDE) {
                            if ($compteur_renouvellement == self::ZERO_TIME) {
                                $situation['nouvelle_duree_possible'] = $situation['duree_totale'] ;
                                $situation['renouvellement_possible'] = self::ONE_TIME;
                                if ($contratEmploye->getStatut() == self::VALIDATED) {
                                    $date1 = $contratEmploye->getDateFin();
                                    $date2 = $this->getDate($date1,$situation['nouvelle_duree_possible']);   
                                } else {
                                    $date1 = $contratEmploye->getDateDebut();
                                    $date2 = $contratEmploye->getDateFin();        
                                }
                            } else {
                                $situation['nouvelle_duree_possible'] = self::ZERO_TIME;
                                $situation['renouvellement_possible'] = self::ZERO_TIME;
                                if ($renouvellements[0]->getStatut() == self::PROPOSED) {
                                    $date1 = $renouvellements[0]->getDateDebut();
                                    $date2 = $this->getDate($date1,$situation['nouvelle_duree_possible']);   
                                } else {
                                    $date1 = $contratEmploye->getDateDebut();
                                    $date2 = $contratEmploye->getDateFin();        
                                }
                            }         
                        } else {
                            $situation['nouvelle_duree_possible'] = self::NOTHING;
                            $situation['renouvellement_possible'] = self::NOTHING;
                            $date1 = $contratEmploye->getDateDebut();
                            $date2 = $contratEmploye->getDateFin();
                        }
                        $manager    = new ManagerEntrepriseService();
                        $services   = $manager->lister(['idEntreprise' => $employe->getIdEntreprise()]);
                        $manager    = new ManagerCategorieProfessionnelle();
                        $categories = $manager->lister();
                        return [
                            'employe'                 => $employe,
                            'contrats'                => $contrats,
                            'typeContrat'             => $typeContrat,
                            'contratEmploye'          => $contratEmploye,
                            'poste'                   => $posteEmploye,
                            'services'                => $services,
                            'service'                 => $serviceEmploye,
                            'categories'              => $categories,
                            'categorie'               => $categorieEmploye,
                            'situation'               => $situation,
                            'date1'                   => $date1,
                            'date2'                   => $date2,
                            'renouvellements'         => $renouvellements,
                            'compteur_renouvellement' => $compteur_renouvellement,
                            'page'                    => self::GESTION_PAGE
                        ];
                    } else {
                        $manager    = new ManagerEntrepriseService();
                        $services   = $manager->lister(['idEntreprise' => $employe->getIdEntreprise()]);
                        $manager    = new ManagerCategorieProfessionnelle();
                        $categories = $manager->lister();
                        return [
                            'employe'                 => $employe,
                            'contrats'                => $contrats,
                            'categories'              => $categories,
                            'services'                => $services,
                            'contratEmploye'          => $contratEmploye,
                            'poste'                   => $posteEmploye,
                            'service'                 => $serviceEmploye,
                            'categorie'               => $categorieEmploye,
                            'page'                    => self::GESTION_PAGE
                        ];
                    }
                }
            } else {
                $this->rediriger();
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
                $contratEmploye  = $contratEmployes[0];
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
                $contratEmploye  = $contratEmployes[0];
            }
            if ($contratEmploye != null) {
                $manager          = new ManagerContrat();
                $typeContrat      = $manager->chercher(['idContrat' => $contratEmploye->getType()]);
                if (strtolower($typeContrat->getDesignation()) == strtolower(self::STAGE)) {
                    return $contratEmploye->getDateFin();
                } else {
                    while ($contratEmploye->getSuivant() != self::NO) {
                        $manager      = new ManagerContratEmploye();
                        $tmp          = $manager->chercher(['idContratEmploye' => $contratEmploye->getSuivant()]);
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
         * Imprimer Contrat
         *
         * @param array $parameters les données à inclure dans le document
         *
         * @return empty
         */
        public function imprimerContrat($parameters)
        {
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                $manager        = new ManagerEmploye();
                $employe        = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $employe->setSalaire($this->decrypter($employe->getSalaire()));
                $employe->setSalaireEnLettre($this->decrypter($employe->getSalaireEnLettre()));             
                $manager        = new ManagerEntreprise();
                $entreprise     = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                $manager        = new ManagerConfiguration();
                $configuration  = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if ($parameters['type'] == self::RENOUVELLEMENT) {
                    $manager               = new ManagerRenouvellement();
                    $renouvellement        = $manager->chercher(['idRenouvellement' => $parameters['idRenouvellement']]);
                    $dateDebut             = $renouvellement->getDateDebut();
                    $dateFin               = $renouvellement->getDateFin();
                    $manager               = new ManagerContratEmploye();
                    $contratEmploye        = $manager->chercher(['idContratEmploye' => $renouvellement->getIdContratEmploye()]);
                    $manager               = new ManagerContrat();
                    $typeContrat           = $manager->chercher(['idContrat' => $contratEmploye->getType()]);
                    $manager               = new ManagerServicePoste();
                    $servicePoste          = $manager->chercher(['idServicePoste' => $contratEmploye->getIdServicePoste()]);
                    $manager               = new ManagerEntreprisePoste();
                    $poste                 = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                    $manager               = new ManagerEntrepriseService();
                    $service               = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                    $manager               = new ManagerMission();
                    $missions              = $manager->lister(["idEntreprisePoste" => $servicePoste->getIdEntreprisePoste()]);
                    $manager               = new ManagerCategorieProfessionnelle();
                    $categorie             = $manager->chercher(['idCategorieProfessionnelle' => $servicePoste->getIdCategorieProfessionnelle()]);
                    $template          = $this->getTemplate(self::TEMPLATE_RENOUVELLEMENT, $entreprise->getIdEntreprise());
                } elseif ($parameters['type'] == self::CONTRAT || $parameters['type'] == self::ATTESTATION || $parameters['type'] == self::CERTIFICAT) {
                    $manager               = new ManagerContratEmploye();
                    $contratEmploye        = $manager->chercher(['idContratEmploye' => $parameters['idContratEmploye']]);
                    if ($parameters['type'] == self::CONTRAT) {
                        $dateDebut         = $contratEmploye->getDateDebut();
                        $dateFin           = $contratEmploye->getDateFin();
                    } else {
                        $dateDebut         = $this->getDateEmbauche($employe->getIdEmploye());
                        if ($this->getDateDebauche($employe->getIdEmploye()) != null) {
                            $dateFin       = $this->getDateDebauche($employe->getIdEmploye());
                        } else {
                            $dateFin       = $this->writeDate(date("Y-m-d"));
                        } 
                    }
                    $manager               = new ManagerContrat();
                    $typeContrat           = $manager->chercher(['idContrat' => $contratEmploye->getType()]);
                    $manager               = new ManagerServicePoste();
                    $servicePoste          = $manager->chercher(['idServicePoste' => $contratEmploye->getIdServicePoste()]);
                    $manager               = new ManagerEntreprisePoste();
                    $poste                 = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                    $manager               = new ManagerEntrepriseService();
                    $service               = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                    $manager               = new ManagerMission();
                    $missions              = $manager->lister(["idEntreprisePoste" => $servicePoste->getIdEntreprisePoste()]);
                    $manager               = new ManagerCategorieProfessionnelle();
                    $categorie             = $manager->chercher(['idCategorieProfessionnelle' => $servicePoste->getIdCategorieProfessionnelle()]);
                    if ($parameters['type'] == self::CONTRAT) {
                        $template          = $this->getTemplate($typeContrat->getIdContrat(), $entreprise->getIdEntreprise());
                    } elseif ($parameters['type'] == self::ATTESTATION) {
                        $template          = $this->getTemplate(self::TEMPLATE_ATTESTATION, $entreprise->getIdEntreprise());
                    } elseif ($parameters['type'] == self::CERTIFICAT) {
                        $template          = $this->getTemplate(self::TEMPLATE_CERTIFICAT, $entreprise->getIdEntreprise());
                    }
                    $essai                 = "";
                    if ($typeContrat->getDesignation() == self::CDI) {
                        if ($contratEmploye->getEssai() != self::NO) {
                            $manager         = new ManagerContratEmploye();
                            $contratEssai    = $manager->chercher(['idContratEmploye' => $contratEmploye->getEssai()]);
                            $manager         = new ManagerRenouvellement();
                            $renouvellements = $manager->lister(['idContratEmploye' => $contratEssai->getIdContratEmploye()]);
                            $essai           = $this->getDuree($contratEssai->getDateDebut(), $contratEssai->getDateFin()) / (count($renouvellements) + 1);  
                        }
                    }
                }
                if (file_exists(DOC_ROOT . "Ressources/fichiers/template/" . $template->getFichier())) {
                    $phpDocx = new PhpDocx(DOC_ROOT . "Ressources/fichiers/template/" . $template->getFichier());
                    $phpDocx->assign(CHAMP_NOM_ENTREPRISE, $entreprise->getNom());
                    $phpDocx->assign(CHAMP_ADRESSE_ENTREPRISE, $entreprise->getAdresse());
                    $phpDocx->assign(CHAMP_NIF_ENTREPRISE, $entreprise->getNif());
                    $phpDocx->assign(CHAMP_STAT_ENTREPRISE, $entreprise->getStat());
                    $phpDocx->assign(CHAMP_RCS_ENTREPRISE, $entreprise->getRcs());
                    $phpDocx->assign(CHAMP_REPRESENTANT_ENTREPRISE, ucwords($entreprise->getNomRepresentant()));
                    $phpDocx->assign(CHAMP_QUALITE_REPRESENTANT, $entreprise->getQualiteRepresentant());
                    switch ($employe->getCivilite()) {
                        case 'Mlle':
                            $phpDocx->assign(CHAMP_CIVILITE_SALARIE, 'Mademoiselle');
                            break;
                        case 'Mme':
                            $phpDocx->assign(CHAMP_CIVILITE_SALARIE, 'Madame');
                            break;
                        case 'Mr':
                            $phpDocx->assign(CHAMP_CIVILITE_SALARIE, 'Monsieur');
                            break;
                        default:
                            $phpDocx->assign(CHAMP_CIVILITE_SALARIE, $employe->getCivilite());
                            break;
                    }
                    $phpDocx->assign(CHAMP_NOM_SALARIE, strtoupper($employe->getNom()));
                    $phpDocx->assign(CHAMP_PRENOM_SALARIE, ucwords($employe->getPrenom()));
                    $phpDocx->assign(CHAMP_DATE_DE_NAISSANCE, $this->writeDate($employe->getDateNaissance()));
                    $phpDocx->assign(CHAMP_ADRESSE_SALARIE, $employe->getAdresse());
                    $phpDocx->assign(CHAMP_SALAIRE_EN_CHIFFRE, $employe->getSalaire());
                    if ($employe->getSalaire() >= 1000000) {
                        $phpDocx->assign(CHAMP_SALAIRE_EN_LETTRE, $employe->getSalaireEnLettre() . " d'Ariary");
                    } else {
                        $phpDocx->assign(CHAMP_SALAIRE_EN_LETTRE, $employe->getSalaireEnLettre() . " Ariary");
                    }
                    $phpDocx->assign(CHAMP_CATEGORIE_PROFESSIONNELLE, $categorie->getDesignation());
                    $phpDocx->assign(CHAMP_POSTE, $poste->getPoste());
                    $phpDocx->assign(CHAMP_NUMERO_CIN, $employe->getNumeroCin());
                    $phpDocx->assign(CHAMP_DATE_CIN, $this->writeDate($employe->getDateCin()));
                    $phpDocx->assign(CHAMP_LIEU_CIN, $employe->getLieuCin());
                    $phpDocx->assign(CHAMP_DATE_DE_DEBUT, $this->writeDate($dateDebut));
                    $phpDocx->assign(CHAMP_DATE_DE_FIN, $this->writeDate($dateFin));
                    $phpDocx->assign(CHAMP_NOTIFICATION, $configuration->getDerniereAlerte());
                    $phpDocx->assign(CHAMP_ESSAI, $essai);
                    $phpDocx->assign(CHAMP_BENEFICE_CONGE, self::STOCK_MENSUEL);
                    $phpDocx->assign(CHAMP_DATE_DU_JOUR, $this->writeDate(date("Y-m-d")));
                    $obligations = '';
                    foreach ($missions as $mission) {
                        $obligations = $obligations . "<w:p><w:r><w:t> • " . $mission->getDescription() . "</w:t></w:r></w:p>";
                    }
                    $phpDocx->assign(CHAMP_OBLIGATIONS, $obligations);
                    $postesOccupes = '';
                    foreach ($this->getPostesOccupes($employe->getIdEmploye()) as $infoPoste) {
                        $postesOccupes = $postesOccupes . "<w:p><w:r><w:t> • " . $infoPoste['poste'] . " dans la catégorie professionnelle " . $infoPoste['categorie'] . " du " . $this->writeDate($infoPoste['debut']) . " au " . $this->writeDate($infoPoste['fin']) . "</w:t></w:r></w:p>";
                    }
                    $phpDocx->assign(CHAMP_POSTES_OCCUPES, $postesOccupes);
                    $phpDocx->download($template->getFichier());
                } else {
                    $_SESSION['info']['danger'] = "Echec ! Le template n'a pas été trouvé !";
                    header("Location:" . HOST . "manage/entreprise/templateContrat");
                    exit();
                }
            } else {
                $this->rediriger();
            }
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
         * Mettre à jour un Contrat d'un employé
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourContratEmploye($parameters)
        {
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                if (!empty($_GET['idContratEmploye']) && !empty($_GET['statut'])) {
                    $manager = new ManagerContratEmploye();
                    $manager->modifier([
                        'idContratEmploye' => $_GET['idContratEmploye'],
                        'statut'           => $_GET['statut']
                    ]);
                    $contratEmploye = $manager->chercher(['idContratEmploye' => $_GET['idContratEmploye']]);
                    if ($contratEmploye->getPrecedent() != null && $contratEmploye->getPrecedent() != self::NO) {
                        $manager->modifier([
                            'idContratEmploye' => $contratEmploye->getPrecedent(),
                            'statut'           => self::EXPIRED
                        ]);
                        $contratPrecedent     = $manager->chercher(['idContratEmploye' => $contratEmploye->getPrecedent()]);
                        $manager              = new ManagerContrat();
                        $typeContratPrecedent = $manager->chercher(['idContrat' => $contratPrecedent->getType()]);
                        $manager              = new ManagerContratEmploye();
                        if ($typeContratPrecedent->getDesignation() == self::CDI) {
                            $manager->modifier([
                                'idContratEmploye' => $contratPrecedent->getIdContratEmploye(),
                                'dateFin'          => $contratEmploye->getDateDebut()
                            ]);
                            if ($contratPrecedent->getEssai() != self::NO) {
                                $contratEssai = $manager->chercher(['idContratEmploye' => $contratPrecedent->getEssai()]);
                                if ($contratEssai->getDateFin() < $contratEmploye->getDateDebut()) {
                                    $manager->modifier([
                                        'idContratEmploye' => $contratEssai->getIdContratEmploye(),
                                        'dateFin'          => $contratEmploye->getDateDebut(),
                                        'statut'           => self::EXPIRED
                                    ]);
                                } else {
                                    $manager->modifier([
                                        'idContratEmploye' => $contratEssai->getIdContratEmploye(),
                                        'statut'           => self::EXPIRED
                                    ]);
                                }
                            }
                        } else {
                            if ($contratPrecedent->getDateFin() > $contratEmploye->getDateDebut()) {
                                $manager->modifier([
                                    'idContratEmploye' => $contratPrecedent->getIdContratEmploye(),
                                    'dateFin'          => $contratEmploye->getDateDebut()
                                ]);
                            }
                        }
                    }
                } else {
                    if ($parameters['type'] == self::NEW_CONTRAT) {
                        $manager = new ManagerContratEmploye();
                        $contratActuel = $manager->chercher(['idContratEmploye' => $parameters['idContratEmploye']]);
                        $this->insertContratEmploye([
                            'idEmploye'      => $parameters['idEmploye'],
                            'precedent'      => $parameters['idContratEmploye'],
                            'idServicePoste' => $contratActuel->getIdServicePoste(),
                            'statut'         => self::EMPTY
                        ]);
                        $nouveauContrat = $manager->chercher([
                            'idEmploye' => $parameters['idEmploye'],
                            'precedent' => $parameters['idContratEmploye'],
                            'statut'    => self::EMPTY
                        ]);
                        $manager->modifier([
                            'idContratEmploye' => $parameters['idContratEmploye'],
                            'suivant'          => $nouveauContrat->getIdContratEmploye()
                        ]);
                    } elseif ($parameters['type'] == self::CONTRAT) {
                        $manager = new ManagerContrat();
                        $contrat = $manager->chercher([
                            'designation' => $parameters['typeContrat']
                        ]);
                        if ($contrat->getDesignation() == self::CDI) {
                            $manager = new ManagerContrat();
                            $contrat = $manager->chercher([
                                'designation' => self::CDE
                            ]);
                            $manager        = new ManagerContratEmploye();
                            if ($parameters['avecEssai'] == true) {
                                $contratActuel = $manager->chercher(['idContratEmploye' => $parameters['idContratEmploye']]);
                                if ($contratActuel->getPrecedent() != self::NO) {
                                    $contratPrecedent = $manager->chercher(['idContratEmploye' => $contratActuel->getPrecedent()]);
                                }
                                if ($contratActuel->getPrecedent() == self::NO
                                   || ($contratActuel->getPrecedent() != self::NO && $parameters['dateDebut'] >= $contratPrecedent->getDateFin()))
                                {
                                    $periodeEssai = $manager->chercher([
                                        'idEmploye' => $parameters['idEmploye'],
                                        'principal'    => $parameters['idContratEmploye']
                                    ]);
                                    if ($periodeEssai != null) {
                                        $periodeEssai = $manager->modifier([
                                            'idContratEmploye' => $periodeEssai->getIdContratEmploye(),
                                            'dateDebut'      => $parameters['dateDebut']
                                        ]);
                                    } else {
                                        $periodeEssai = $manager->ajouter([
                                            'idEmploye'      => $parameters['idEmploye'],
                                            'type'           => $contrat->getIdContrat(),
                                            'dateDebut'      => $parameters['dateDebut'],
                                            'principal'      => $parameters['idContratEmploye'],
                                            'idServicePoste' => $contratActuel->getIdServicePoste(),
                                            'statut'         => self::PROPOSED
                                        ]);
                                    }
                                    $manager = new ManagerContrat();
                                    $contrat = $manager->chercher([
                                        'designation' => $parameters['typeContrat']
                                    ]);
                                    $manager        = new ManagerContratEmploye();
                                    $contratEmploye = $manager->modifier([
                                        'idContratEmploye' => $parameters['idContratEmploye'],
                                        'type'             => $contrat->getIdContrat(),
                                        'dateDebut'        => $parameters['dateDebut'],
                                        'dateFin'          => null,
                                        'statut'           => self::PROPOSED,
                                        'essai'            => $periodeEssai->getIdContratEmploye()
                                    ]);
                                } else {
                                    $_SESSION['notification']['type']    = "erreur";
                                    $_SESSION['notification']['titre']   = "Dates anormales !";
                                    $_SESSION['notification']['message'] = "La date de début est comprise dans l'ancien contrat";
                                }
                            } else {
                                $manager = new ManagerContrat();
                                $contrat = $manager->chercher([
                                    'designation' => $parameters['typeContrat']
                                ]);
                                $manager        = new ManagerContratEmploye();
                                $contratEmploye = $manager->chercher(['idContratEmploye' => $parameters['idContratEmploye']]);
                                if ($contratEmploye->getPrecedent() != self::NO) {
                                    $contratPrecedent = $manager->chercher(['idContratEmploye' => $contratEmploye->getPrecedent()]);
                                }
                                if ($contratEmploye->getPrecedent() == self::NO
                                   || ($contratEmploye->getPrecedent() != self::NO && $parameters['dateDebut'] >= $contratPrecedent->getDateFin()))
                                {
                                    if ($contratEmploye->getEssai() != self::NO) {
                                        $manager->supprimer(['idContratEmploye' => $contratEmploye->getEssai()]);
                                    }
                                    $contratEmploye = $manager->modifier([
                                        'idContratEmploye' => $parameters['idContratEmploye'],
                                        'type'             => $contrat->getIdContrat(),
                                        'dateDebut'        => $parameters['dateDebut'],
                                        'dateFin'          => null,
                                        'statut'           => self::PROPOSED,
                                        'essai'            =>self::NO
                                    ]);
                                } else {
                                    $_SESSION['notification']['type']    = "erreur";
                                    $_SESSION['notification']['titre']   = "Dates anormales !";
                                    $_SESSION['notification']['message'] = "La date de début est comprise dans l'ancien contrat";
                                }
                            }
                        } else {
                            if ($parameters['dateDebut'] > $parameters['dateFin']) {
                                $_SESSION['notification']['type']    = "erreur";
                                $_SESSION['notification']['titre']   = "Dates anormales !";
                                $_SESSION['notification']['message'] = "La date de début est plus récente que la date de fin du contrat.";
                            } else {
                                $manager = new ManagerContratEmploye();
                                $contratEmploye = $manager->modifier([
                                    'idContratEmploye' => $parameters['idContratEmploye'],
                                    'type'             => $contrat->getIdContrat(),
                                    'dateDebut'        => $parameters['dateDebut'],
                                    'dateFin'          => $parameters['dateFin'],
                                    'statut'           => self::PROPOSED,
                                    'essai'            => self::NO
                                ]);
                                $contratEmploye = $manager->chercher(['idContratEmploye' => $parameters['idContratEmploye']]);
                                if ($contratEmploye->getPrincipal() != 0) {
                                    header("Location : " . HOST . "manage/update-contratEmploye?idEmploye=" . $parameters['idEmploye'] . "&idContratEmploye=" . $parameters['idContratEmploye']);
                                    exit();
                                }
                            }
                        }
                    } elseif ($parameters['type'] == self::PROLONGEMENT || $parameters['type'] == self::RENOUVELLEMENT) {
                        $manager = new ManagerContratEmploye();
                        $contratEmploye = $manager->chercher(['idContratEmploye' => $parameters['idContratEmploye']]);
                        if ($parameters['dateDebut'] < $parameters['dateFin'] && $contratEmploye->getDateFin() <= $parameters['dateDebut']) {
                            /*
                             * SI LE CDD EST INTERROMPU DE PLUS DE 1 MOIS
                             * ON REMET UN TOUT NOUVEAU CDD
                             */
                            if ( $parameters['type'] == self::RENOUVELLEMENT && $this->getDuree($contratEmploye->getDateFin(), $parameters['dateDebut']) > self::ONE_MONTH ) {
                                $this->insertContratEmploye([
                                    'idEmploye'      => $parameters['idEmploye'],
                                    'dateDebut'      => $parameters['dateDebut'],
                                    'dateFin'        => $parameters['dateFin'],
                                    'type'           => $contratEmploye->getType(),
                                    'idServicePoste' => $contratEmploye->getIdServicePoste(),
                                    'statut'         => self::PROPOSED
                                ]);
                            } else {
                                $manager = new ManagerRenouvellement();
                                $manager->ajouter([
                                    'dateDebut'        => $parameters['dateDebut'],
                                    'dateFin'          => $parameters['dateFin'],
                                    'idContratEmploye' => $parameters['idContratEmploye'],
                                    'statut'           => self::PROPOSED
                                ]);
                            }
                            if ($contratEmploye->getPrincipal() != self::NO) {
                                header("Location : " . HOST . "manage/update-contratEmploye?idEmploye=" . $parameters['idEmploye'] . "&idContratEmploye=" . $parameters['idContratEmploye']);
                                exit();
                            }
                        } else {
                            $_SESSION['notification']['type']    = "erreur";
                            $_SESSION['notification']['titre']   = "Dates anormales !";
                            $_SESSION['notification']['message'] = "La date de début est plus récente que la date de fin du contrat.";
                        }
                    } elseif ($parameters['type'] == self::EDIT_PROLONGEMENT || $parameters['type'] == self::EDIT_RENOUVELLEMENT) {
                        $manager        = new ManagerRenouvellement();
                        $renouvellement = $manager->chercher([
                            'idContratEmploye' => $parameters['idContratEmploye'],
                            'statut'           => self::PROPOSED
                        ]);
                        $manager        = new ManagerContratEmploye();
                        $contratEmploye = $manager->chercher(['idContratEmploye' => $parameters['idContratEmploye']]);
                        if ($parameters['dateDebut'] < $parameters['dateFin'] && $contratEmploye->getDateFin() <= $parameters['dateDebut']) {
                            $manager->modifier([
                                'idRenouvellement' => $renouvellement->getIdRenouvellement(),
                                'dateDebut'        => $parameters['dateDebut'],
                                'dateFin'          => $parameters['dateFin']
                            ]);
                            if ($contratEmploye->getPrincipal() != self::NO) {
                                header("Location : " . HOST . "manage/update-contratEmploye?idEmploye=" . $parameters['idEmploye'] . "&idContratEmploye=" . $parameters['idContratEmploye']);
                                exit();
                            }
                        } else {
                            $_SESSION['notification']['type']    = "erreur";
                            $_SESSION['notification']['titre']   = "Dates anormales !";
                            $_SESSION['notification']['message'] = "La date de début est plus récente que la date de fin du contrat.";
                        }
                    }
                }  
            } else {
                $this->rediriger();
            }     
        }

        /**
         * Créer un nouveau contrat
         *
         * @param array $parameters les données à inserer
         *
         * @return empty
         */
        private function insertContratEmploye($parameters)
        {
            if ($this->isAllowed($_SESSION['user']['idEntreprise'], $parameters['idEmploye'])) {
                $manager = new ManagerContratEmploye();
                $contratEmploye = $manager->ajouter($parameters);
            } else {
                $this->rediriger();
            }
        }

        /**
         * Supprimer un contrat pas encore valider
         *
         * @param array $parameters Les critères du contrat à supprimer
         *
         * @return empty
         */
        public function deleteContratEmploye($parameters)
        {
            if (!empty($parameters['idContratEmploye'])) {
                $manager         = new ManagerContratEmploye();
                $contratEmploye  = $manager->chercher(['idContratEmploye' => $parameters['idContratEmploye']]);
                $manager         = new ManagerEmploye();
                $employe         = $manager->chercher(['idEmploye' => $contratEmploye->getIdEmploye()]);
                if ($this->isAllowed($_SESSION['user']['idEntreprise'], $employe->getIdEmploye())) {
                    if ($contratEmploye->getPrecedent() != self::NO) {
                        $manager   = new ManagerContratEmploye();
                        $manager->modifier([
                            'idContratEmploye' => $contratEmploye->getPrecedent(),
                            'suivant'          => self::NO
                        ]);
                        $manager->supprimer(['idContratEmploye' => $contratEmploye->getIdContratEmploye()]);
                    } else {
                        $manager->modifier([
                            'idContratEmploye' => $contratEmploye->getIdContratEmploye(),
                            'type'             => null,
                            'dateDebut'        => null,
                            'dateFin'          => null,
                            'statut'           => self::EMPTY
                        ]);
                    }
                    header("Location:" . HOST . "manage/update-contratEmploye?idEmploye=" . $contratEmploye->getIdEmploye());
                } else {
                    $this->rediriger();
                }
            } else {
                $this->rediriger();
            }
        }


        /** 
         * Mettre à jour un renouvellement de contrat d'un employé
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAjourRenouvellement($parameters)
        {
            if (!empty($_GET['idRenouvellement']) && !empty($_GET['statut']) && !empty($_GET['idEmploye'])) {
                $manager = new ManagerRenouvellement();
                $manager->modifier([
                    'idRenouvellement' => $_GET['idRenouvellement'],
                    'statut'           => $_GET['statut']
                ]);
                $renouvellement = $manager->chercher(['idRenouvellement' => $_GET['idRenouvellement']]);
                if ($_GET['statut'] == self::VALIDATED) {
                    $manager = new ManagerContratEmploye();
                    $manager->modifier([
                        'idContratEmploye' => $renouvellement->getIdContratEmploye(),
                        'dateFin'          => $renouvellement->getDateFin()
                    ]);
                }
            } else {
                $this->rediriger();
            }
        }

        /**
         * Activer l'alerte automatique 
         *
         * @param object $entreprise concernée (en géné)
         *
         * @return empty
         */
        private function activerAlerteAutomatique($entreprise)
        {
            $debutFichier = "# début";
            $finFichier   = "# fin";
            $oldCronTab   = array();
            $newCronTab   = array();
            exec('crontab -l', $oldCronTab);
            $isSection  = false;
            $toBeCopied = true;
            foreach ($oldCronTab as $index => $ligne) {
                if ($ligne == $debutFichier) {
                    $isSection  = true;
                    $toBeCopied = true;
                } elseif ($ligne == $finFichier) { 
                    $newCronTab[] = '# ' . $entreprise->getIdEntreprise() . ' : Rappel par email pour ' . $entreprise->getNom();
                    $newCronTab[] = self::CRON_JOB . ' ' . $entreprise->getIdEntreprise();
                    $toBeCopied = true;
                } elseif ($isSection == true) {
                    $motsLigne = explode(' ', $ligne);
                    if ($motsLigne[0] == '#') { 
                        if ($motsLigne[1] == $entreprise->getIdEntreprise()) {
                            $toBeCopied = false;
                        } else {
                            $toBeCopied = true;
                        }
                    }
                }
                if ($toBeCopied == true) {
                    $newCronTab[] = $ligne;
                }
            }
            if ($isSection == false) {
                $newCronTab[] = $debutFichier;
                $newCronTab[] = '# ' . $entreprise->getIdEntreprise() . ' : Rappel par email pour ' . $entreprise->getNom();
                $newCronTab[] = self::CRON_JOB . ' ' . $entreprise->getIdEntreprise();
                $newCronTab[] = $finFichier;
                $newCronTab[] = "\n";
            }
            if (file_exists('tmp')) {
                unlink('tmp');
            }
            $fichier = fopen('tmp', 'w');
            fwrite($fichier, implode("\n", $newCronTab));
            fclose($fichier);
            exec('crontab');
            exec('crontab tmp');
        }

        /**
         * Désactiver l'alerte automatique
         * 
         * @param object $entreprise l'entreprise concernée
         *
         * @return empty
         */
        private function desactiverAlerteAutomatique($entreprise)
        {
            $debutFichier = "# début";
            $finFichier   = "# fin";
            $oldCronTab   = array();
            $newCronTab   = array();
            exec('crontab -l', $oldCronTab);
            $isSection  = false;
            $toBeCopied = true;
            foreach ($oldCronTab as $index => $ligne) {
                if ($ligne == $debutFichier) {
                    $isSection  = true;
                    $toBeCopied = true;
                } elseif ($ligne == $finFichier) {
                    $toBeCopied = true;
                } elseif ($isSection == true) {
                    $motsLigne = explode(' ', $ligne);
                    if ($motsLigne[0] == '#') { 
                        if ($motsLigne[1] == $entreprise->getIdEntreprise()) {
                            $toBeCopied = false;
                        } else {
                            $toBeCopied = true;
                        }
                    }
                }
                if ($toBeCopied == true) {
                    $newCronTab[] = $ligne;
                }
            }
            $newCronTab[] = "\n";
            if (file_exists('tmp')) {
                unlink('tmp');
            }
            $fichier = fopen('tmp', 'w');
            fwrite($fichier, implode("\n", $newCronTab));
            fclose($fichier);
            exec('crontab');
            exec('crontab tmp');
        }

        /**
         * Vérifier si l'alerte automatique est activée
         *
         * @param object $entreprise l'entreprise concernée
         *
         * @return boolean
         */
        private function alerteActive($entreprise)
        {
            $debutFichier = "# début";
            $finFichier   = "# fin";
            $oldCronTab   = array();
            exec('crontab -l', $oldCronTab);
            $isSection  = false;
            foreach ($oldCronTab as $index => $ligne) {
                if ($ligne == $debutFichier) {
                    $isSection  = true;
                } elseif ($isSection == true && $ligne != $finFichier) {
                    $motsLigne = explode(' ', $ligne);
                    if ($motsLigne[0] == '#') {
                        if ($motsLigne[1] == $entreprise->getIdEntreprise()) {
                            return true;
                        }
                    }
                }
            }
            return false;
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
         * Switcher l'activation ou la désactivation des rappels automatiques
         *
         * @param object $entreprise l'entreprise concernée
         *
         * @return empty
         */
        public function switcherAlerte($parameters)
        {
            if ($_SESSION['user']['idEntreprise'] == $parameters['idEntreprise']) {
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $parameters['idEntreprise']]); 
                if ($this->alerteActive($entreprise)) {
                    $this->desactiverAlerteAutomatique($entreprise);
                } else {
                    $this->activerAlerteAutomatique($entreprise);
                }
                header('Location:' . HOST . 'manage/entreprise/parametreContrat');
            } else {
                $this->rediriger();
            }
        }

        /**
         * Mettre à jour une configuration
         *
         * @param $parameters les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourConfiguration($parameters)
        {
            if ($parameters['idEntreprise'] == $_SESSION['user']['idEntreprise'] || $_SESSION['compte']['identifiant'] == self::USER_ADMIN) {
                if ($parameters['page'] == self::WARNING_CONFIGURATION_PAGE) {
                    $redirection = 'entreprise/parametreContrat';
                    $manager = new ManagerConfiguration();
                    if ($parameters['nombreAlerte'] == self::SINGLE_WARNING) {
                        $manager->modifier([
                            'idConfiguration' => $parameters['idConfiguration'],
                            'emailAlerte'     => $parameters['emailAlerte'],
                            'nombreAlerte'    => $parameters['nombreAlerte'],
                            'premiereAlerte'  => $parameters['premiereAlerte']
                        ]);
                    } elseif ($parameters['nombreAlerte'] == self::TWO_WARNING) {
                        $manager->modifier([
                            'idConfiguration' => $parameters['idConfiguration'],
                            'emailAlerte'     => $parameters['emailAlerte'],
                            'nombreAlerte'    => $parameters['nombreAlerte'],
                            'premiereAlerte'  => $parameters['premiereAlerte'],
                            'deuxiemeAlerte'  => $parameters['deuxiemeAlerte']
                        ]);
                    } elseif ($parameters['nombreAlerte'] == self::THREE_WARNING) {
                        $manager->modifier([
                            'idConfiguration' => $parameters['idConfiguration'],
                            'emailAlerte'     => $parameters['emailAlerte'],
                            'nombreAlerte'    => $parameters['nombreAlerte'],
                            'premiereAlerte'  => $parameters['premiereAlerte'],
                            'deuxiemeAlerte'  => $parameters['deuxiemeAlerte'],
                            'troisiemeAlerte' => $parameters['troisiemeAlerte']
                        ]);
                    }
                } elseif ($parameters['page'] == self::DOCUMENT_CONFIGURATION_PAGE) {
                    if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                        $redirection = 'entreprise/templateContrat';
                    } elseif ($_SESSION['compte']['identifiant'] == self::USER_ADMIN) {
                        $redirection = 'admin/templateContrat';
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
                                $manager = new ManagerTemplate();
                                $template = $manager->chercher([
                                    'idEntreprise' => $parameters['idEntreprise'],
                                    'idContrat'    => $parameters['idContrat']
                                ]);
                                if ($template == null) {
                                    $manager->ajouter([
                                        'idEntreprise' => $parameters['idEntreprise'],
                                        'idContrat'    => $parameters['idContrat'],
                                        'fichier'      => str_replace($targetDir, '', $targetFile)
                                    ]);
                                } else {
                                    $fileName = $targetDir . '' . $parameters['fichier'];
                                    if (file_exists($fileName)) {
                                        unlink($fileName);
                                    }
                                    $manager->modifier([
                                        'idTemplate' => $template->getIdTemplate(),
                                        'fichier'    => str_replace($targetDir, '', $targetFile)
                                    ]);
                                }
                                $_SESSION['notification']['type'] = self::NOTIFICATION_SUCCESS;
                                $_SESSION['notification']['titre'] = "Téléchargement terminé !";
                                $_SESSION['notification']['message'] = "Le fichier a été téléchargé avec succès.";
                            } else {
                                $_SESSION['notification']['type'] = self::ERROR;
                                $_SESSION['notification']['titre'] = "Téléchargement a échoué !";
                                $_SESSION['notification']['message'] = "Erreur lors de l'importation.";
                            }
                        } else {

                        }
                    } elseif (!empty($parameters['delete'])) {
                        $targetDir   = DOC_ROOT . "Ressources/fichiers/template/";
                        $fileName = $targetDir . '' . $parameters['fichier'];
                        if (file_exists($fileName)) {
                            if (unlink($fileName)) {
                                $manager = new ManagerTemplate();
                                $template = $manager->chercher([
                                    'idEntreprise' => $parameters['idEntreprise'],
                                    'idContrat'    => $parameters['idContrat']
                                ]);
                                if ($template != null) {
                                    $manager->supprimer([
                                        'idTemplate' => $template->getIdTemplate()
                                    ]);
                                }
                            } else {
                                $_SESSION['notification']['type'] = self::NOTIFICATION_ERROR;
                                $_SESSION['notification']['titre'] = "Erreur de suppression";
                                $_SESSION['notification']['message'] = "Le fichier est introuvable";
                            }
                        }
                    } elseif (!empty($parameters['download'])) {
                        if (file_exists(DOC_ROOT . "Ressources/fichiers/template/" . $parameters['fichier'])) {
                            $phpDocx = new PhpDocx(DOC_ROOT . "Ressources/fichiers/template/" . $parameters['fichier']);
                            $phpDocx->download($parameters['fichier']);
                        } else {
                            $_SESSION['notification']['type'] = self::NOTIFICATION_ERROR;
                            $_SESSION['notification']['titre'] = "Erreur de téléchargement";
                            $_SESSION['notification']['message'] = "Le fichier n'a pas été trouvé, veuillez en spécifier un autre";
                        }
                    }
                }
                header('Location:' . HOST . 'manage/' . $redirection);
            } else {
                $this->rediriger();
            }
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
            $manager       = new ManagerEntreprise();
            $entreprise    = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager       = new ManagerEntreprisePoste();
            $postes        = $manager->lister([
                'idEntreprise' => $entreprise->getIdEntreprise(),
                'statut'       => self::POSTE_INTERNE
            ]);
            $manager       = new ManagerEntrepriseService();
            $services      = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $manager       = new ManagerEmploye();
            $employes      = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $manager       = new ManagerContrat();
            $contrats      = $manager->lister(['offreUniquement' => self::NO]);
            $filtres       = array();
            $filtres['services'] = $services;
            $filtres['postes']   = $postes;
            $filtres['employes'] = $employes;
            $filtres['contrats'] = $contrats;
            return $filtres;
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
	}