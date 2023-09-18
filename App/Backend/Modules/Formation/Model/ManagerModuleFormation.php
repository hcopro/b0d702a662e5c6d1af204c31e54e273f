<?php
    
    /**
     * Manager du module Formation du Backend
     *
     * @author Toky
     *
     * @since 15/09/2020 
     */

	use \Core\DbManager;
    use \Core\View;
    use \Model\ManagerCompte;
    use \Model\ManagerEmploye;
    use \Model\ManagerEntreprise;
    use \Model\ManagerServicePoste;
    use \Model\ManagerEntrepriseService;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerContratEmploye;
    use \Model\ManagerMessage;
    use \Model\ManagerContrat;
    use \Model\ManagerFormationProfessionnelle;
    use \Model\ManagerEvaluationFormation;
    use \Model\ManagerFormateur;
    use \Model\ManagerOffreFormateur;
    use \Model\ManagerThemeFormation;
    use \Model\ManagerEmployeFormation;
    use \Model\ManagerSousDomaine;
    use \Model\ManagerDomaineFormateur;
    use \Model\ManagerDemandeFormation;

	class ManagerModuleFormation extends DbManager
	{
        const ALL_DOMAINE               = 0;
        const NO                        = 0;
        const YES                       = 1;
        const STATUT_ARCHIVED           = 0;
        const STATUT_ACTIF              = 1;
        const AJAX_OK                   = "ok";
        const AJAX_WRONG                = "wrong";
        const OFFRE_PROPOSED            = 0;
        const OFFRE_VALIDATED           = 1;
        const DEMANDE_PROPOSED          = 1;
        const DEMANDE_REFUSED           = 0;
        const DEMANDE_VALIDATED         = 2;
        const ALL_DEMANDES              = 3;
        const TYPE_DEMANDE_ARCHIVED     = 4;
        const DEMANDE_ACTIF             = 1;
        const DEMANDE_ARCHIVED          = 0;
        const TYPE_REQUEST              = "demande";
        const TYPE_VALIDATED            = "valide";
        const TYPE_REJECTED             = "rejete";
        const TYPE_CANCELED             = "annule";
        const TYPE_INFORMATION          = "information";
        const TYPE_REMINDER             = "rappel";
        const EVALUATION_FORMATION      = 0;
        const EVALUATION_PARTICIPANT    = 1;
        const EMPTY                     = 0;
        const PROPOSED                  = 1;
        const VALIDATED                 = 2;
        const EXPIRED                   = 3;
        const REFUSED                   = 0;

        /**
         * Voir le catalogue de thèmes pour les formation
         *
         * @param array $parameters 
         * 
         * @return array
         */
        public function voirCatalogueFormation($parameters)
        {
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager    = new ManagerSousDomaine();
            $domaines   = $manager->lister(null);
            return [
                'entreprise' => $entreprise,
                'domaines'   => $domaines
            ];
        }

        /**
         * Mettre à jour un thème de formation
         *
         * @param array $parameters les critères des données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourThemeFormation($parameters)
        {
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (empty($parameters['idEmploye'])) {
                $parameters['idEmploye'] = self::NO; 
            }
            if (!isset($parameters['statut'])) {
                $parameters['statut'] = self::YES;
            }
            if (empty($parameters['idSousDomaine'])) {
                $manager = new ManagerSousDomaine();
                $retour  = $manager->ajouter([
                    'nomSousDomaine' => $parameters['nomSousDomaine'],
                    'idDomaine' => self::NO
                ]);
                $parameters['idSousDomaine'] = $retour->getIdSousDomaine();
            }
            if (!empty($parameters['idThemeFormation'])) {
                $manager = new ManagerThemeFormation();
                $retour  = $manager->modifier([
                    'idThemeFormation' => $parameters['idThemeFormation'],
                    'idSousDomaine'    => $parameters['idSousDomaine'],
                    'theme'            => $parameters['theme'],
                    'description'      => $parameters['description'],
                    'priorite'         => $parameters['priorite']
                ]);
                if ($retour->getIdThemeFormation() != null) {
                    $_SESSION['info']['success'] = "Le thème a été modifié avec succès !";
                }
            } else {
                $year    = date('Y');
                $manager = new ManagerThemeFormation();
                $retour  = $manager->ajouter([
                    'idEntreprise'  => $entreprise->getIdEntreprise(),
                    'annee'         => $year,
                    'idSousDomaine' => $parameters['idSousDomaine'],
                    'theme'         => $parameters['theme'],
                    'description'   => $parameters['description'],
                    'priorite'      => $parameters['priorite'],
                    'idEmploye'     => $parameters['idEmploye'],
                    'statut'        => $parameters['statut']
                ]); 
                if ($retour->getIdThemeFormation() != null) {
                    $_SESSION['info']['success'] = "Le thème a été ajouté avec succès !";
                } else {
                    $_SESSION['info']['danger'] = "Echec de l'ajout du nouveau thème";
                }
            }
        }

        /**
         * Voir les suggestions de thèmes d'un employé
         *
         * @param array $parameters critères des données à afficher
         *
         * @return array
         */
        public function voirSuggestionFormation($parameters)
        {
            $manager    = new ManagerSousDomaine();
            $domaines   = $manager->lister(null);
            $manager    = new ManagerThemeFormation();
            $themes     = $manager->lister([
                'idEmploye' => $_SESSION['user']['idEmploye']
            ]);
            return [
                'themes'   => $this->getThemeFormations($themes),
                'domaines' => $domaines,
            ];
        }

        /**
         * Lister les thèmes de formation
         *
         * @param array $parameters les critères des données à lister
         *
         * @return array
         */
        public function listerThemeFormations($parameters)
        {
            $manager = new ManagerThemeFormation();
            if ($parameters['idSousDomaine'] == self::ALL_DOMAINE) {
                $themes = $manager->lister([
                    'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                    'annee'         => $parameters['annee']
                ]);
            } else {
                $themes = $manager->lister([
                    'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                    'annee'         => $parameters['annee'],
                    'idSousDomaine' => $parameters['idSousDomaine']
                ]);
            }
            $donnees = $this->getThemeFormations($themes); 
            $view = new View("listerThemeFormations");
            $view->sendWithoutTemplate("Backend", "Formation", $donnees, "entreprise"); 
            exit();
        }

        /**
         * Retourner les informations complètes des thèmes
         *
         * @param array $themes une liste de thèmes
         *
         * @return array
         */
        public function getThemeFormations($themes)
        {
            $donnees = array();
            foreach ($themes as $theme) {
                $tmp['theme'] = $theme;
                $manager      = new ManagerSousDomaine();
                $tmp['sousDomaine'] = $manager->chercher(['idSousDomaine' => $theme->getIdSousDomaine()]);
                $manager  = new ManagerEmploye();
                if ($theme->getIdEmploye() != self::NO) {
                    $tmp['employe'] = $manager->chercher(['idEmploye' => $theme->getIdEmploye()]);
                } else {
                    $tmp['employe'] = null;
                }
                $manager  = new ManagerOffreFormateur();
                $offres   = $manager->lister(['idThemeFormation' => $theme->getIdThemeFormation()]);
                $offreValidee = $manager->chercher([
                    'idThemeFormation' => $theme->getIdThemeFormation(),
                    'statut' => self::OFFRE_VALIDATED
                ]);
                $tmp['formationOuverte'] = null;
                if ($offreValidee != null) {
                    $manager = new ManagerFormationProfessionnelle();
                    $formation = $manager->chercher(['idOffreFormateur' => $offreValidee->getIdOffreFormateur()]);
                    if ($formation != null) {
                        $today = date('Y-m-d');
                        if ($formation->getFin() != null) {
                            if (strtotime($today) > strtotime($formation->getFin())) {
                                $tmp['formationOuverte'] = false;
                            } else {
                                $tmp['formationOuverte'] = true;
                            }
                        } else {
                            $tmp['formationOuverte'] = true;
                        }
                    }
                }
                if (count($offres) > self::NO) {
                    $tmp['editable'] = false;
                } else {
                    $tmp['editable'] = true;
                }
                $donnees[] = $tmp;
            }
            return $donnees;
        }

        /**
         * Supprimer un thème
         *
         * @param array $parameters les critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerThemeFormation($parameters)
        {
            if (!empty($parameters['idThemeFormation'])) {
                $manager    = new ManagerThemeFormation();
                $theme      = $manager->chercher(['idThemeFormation' => $parameters['idThemeFormation']]);
                if ($theme != null && $theme->getIdEntreprise() == $_SESSION['user']['idEntreprise']) {
                    $manager->supprimer([
                        'idThemeFormation' => $parameters['idThemeFormation']
                    ]);
                    $_SESSION['info']['success'] = "Le thème a été supprimé !";
                } else {
                    $_SESSION['info']['danger'] = "Echec lors de la suppression du thème !";
                }
            } else {
                $_SESSION['info']['danger'] = "Aucune donnée à supprimer !";
            }
        }

        /**
         * Récupérer un thème
         *
         * @param array $parameters les critères des données à récupérer
         *
         * @return empty
         */
        public function getThemeFormation($parameters)
        {
            if (!empty($parameters['idThemeFormation'])) {
                $manager    = new ManagerThemeFormation();
                $theme      = $manager->chercher(['idThemeFormation' => $parameters['idThemeFormation']]);
                if ($theme != null && $theme->getIdEntreprise() == $_SESSION['user']['idEntreprise']) {
                    $resultat = $theme->toArray();
                    echo json_encode($resultat);
                    exit;
                }
            }
        }

        /**
         * Récupérer une évaluation de formation
         *
         * @param array $parameters les critères des données à récupérer
         *
         * @return empty
         */
        public function getEvaluationFormation($parameters)
        {
            if (!empty($parameters['idFormationProfessionnelle']) && empty($parameters['idEmploye'])) {
                $manager  = new ManagerFormationProfessionnelle();
                $formation = $manager->chercher(['idFormationProfessionnelle' => $parameters['idFormationProfessionnelle']]);
                if ($formation != null) {
                    $manager = new ManagerEvaluationFormation();
                    $evaluation = $manager->chercher([
                        'idFormationProfessionnelle' => $formation->getIdFormationProfessionnelle(),
                        'evaluateur' => $_SESSION['user']['idEmploye']
                    ]);
                    if ($evaluation == null) {
                        $evaluation = $manager->initialiser();
                    }
                    $resultat = $evaluation->toArray();
                    echo json_encode($resultat);
                    exit();
                }
            } elseif (!empty($parameters['idFormationProfessionnelle']) && !empty($parameters['idEmploye'])) {
                $manager = new ManagerEmployeFormation();
                $employeFormation = $manager->chercher([
                    'idFormationProfessionnelle' => $parameters['idFormationProfessionnelle'],
                    'idEmploye' => $parameters['idEmploye']
                ]);
                if ($employeFormation != null) {
                    $manager = new ManagerEvaluationFormation();
                    $evaluation = $manager->chercher([
                        'idEmployeFormation' => $employeFormation->getIdEmployeFormation(),
                        'evaluateur' => $_SESSION['user']['idEmploye']
                    ]);
                    if ($evaluation == null) {
                        $evaluation = $manager->initialiser();
                        $evaluation->setIdEmployeFormation($employeFormation->getIdEmployeFormation());
                    }
                    $resultat = $evaluation->toArray();
                    echo json_encode($resultat);
                    exit();
                }
            }
        }

        /**
         * mettre à jour une évaluation de formation
         *
         * @param array $parameters les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourEvaluationFormation($parameters)
        {
            if (!empty($parameters)) {
                if (!empty($parameters['idEvaluationFormation'])) {
                    $manager = new ManagerEvaluationFormation();
                    $evaluation = $manager->chercher([
                        'idEvaluationFormation' => $parameters['idEvaluationFormation'],
                        'evaluateur'            => $_SESSION['user']['idEmploye']
                    ]);
                    if ($evaluation != null) {
                        $retour = $manager->modifier([
                            'idEvaluationFormation' => $evaluation->getIdEvaluationFormation(),
                            'note' => $parameters['note'],
                            'remarque' => $parameters['remarque']
                        ]); 
                        if ($retour->getNote() == $parameters['note'] && $retour->getRemarque() == $parameters['remarque']) {
                            $_SESSION['info']['success'] = "L'évaluation a été enregistrée"; 
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "Données introuvables";
                    }
                } else {
                    $manager = new ManagerEvaluationFormation();
                    if (!empty($parameters['idFormationProfessionnelle'])) {
                        $retour = $manager->ajouter([
                            'idFormationProfessionnelle' => $parameters['idFormationProfessionnelle'],
                            'idEmployeFormation' => self::NO,
                            'note' => $parameters['note'],
                            'remarque' => $parameters['remarque'],
                            'evaluateur' => $_SESSION['user']['idEmploye']
                        ]);
                    } elseif (!empty($parameters['idEmployeFormation'])) {
                        $retour = $manager->ajouter([
                            'idFormationProfessionnelle' => self::NO,
                            'idEmployeFormation' => $parameters['idEmployeFormation'],
                            'note' => $parameters['note'],
                            'remarque' => $parameters['remarque'],
                            'evaluateur' => $_SESSION['user']['idEmploye']
                        ]);
                    }
                    if ($retour->getIdEvaluationFormation() != self::NO) {
                        $_SESSION['info']['success'] = "L'évaluation a été enregistrée"; 
                    } else {
                        $_SESSION['info']['danger'] = "Echec lors de l'opération";
                    }
                }
            }
        }

        /**
         * Voir la liste des formateurs
         *
         * @param array $parameters 
         * 
         * @return array
         */
        public function voirFormateur($parameters)
        {
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager    = new ManagerSousDomaine();
            $domaines   = $manager->lister(null);
            return [
                'entreprise'  => $entreprise,
                'domaines'    => $domaines
            ];
        }

        /**
         * Mettre à jour un formateur
         *
         * @param array $parameters les critères des données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourFormateur($parameters)
        {
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($parameters['idFormateur'])) {
                $manager = new ManagerFormateur();
                $retour  = $manager->modifier([
                    'idFormateur'  => $parameters['idFormateur'],
                    'civilite'     => $parameters['civilite'],
                    'nom'          => $parameters['nom'],
                    'prenom'       => $parameters['prenom'],
                    'contact'      => $parameters['contact'],
                    'email'        => $parameters['email'],
                    'nif'          => $parameters['nif'],
                    'stat'         => $parameters['stat'],
                    'rcs'          => $parameters['rcs'],
                ]);
                if ($retour->getIdFormateur() != null) {
                    $_SESSION['info']['success'] = "Le formateur a été modifié avec succès !";
                }
            } else {
                $manager = new ManagerFormateur();
                $retour  = $manager->ajouter([
                    'idEntreprise' => $entreprise->getIdEntreprise(),
                    'civilite'     => $parameters['civilite'],
                    'nom'          => $parameters['nom'],
                    'prenom'       => $parameters['prenom'],
                    'contact'      => $parameters['contact'],
                    'email'        => $parameters['email'],
                    'nif'          => $parameters['nif'],
                    'stat'         => $parameters['stat'],
                    'rcs'          => $parameters['rcs'],
                    'statut'       => self::STATUT_ACTIF
                ]);
                if ($retour->getIdFormateur() != null) {
                    $_SESSION['info']['success'] = "Le Formateur a été ajouté avec succès !";
                } else {
                    $_SESSION['info']['danger'] = "Echec de l'ajout du nouveau Formateur";
                }
            }
        }

        /**
         * Archiver un formateur
         *
         * @param array $parameters les critères des données à archiver
         *
         * @return empty
         */
        public function archiverFormateur($parameters)
        {
           if (!empty($parameters['idFormateur'])) {
                $manager   = new ManagerFormateur();
                $formateur = $manager->chercher([
                    'idFormateur'  => $parameters['idFormateur'],
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($formateur != null) {
                    $retour = $manager->modifier([
                        'idFormateur' => $formateur->getIdFormateur(),
                        'statut' => self::STATUT_ARCHIVED
                    ]);
                    if ($retour != null) {
                        $_SESSION['info']['success'] = "Le Formateur a été archivé avec succès";
                    }
                }
                else {
                    $_SESSION['info']['danger'] = "Echec ! Formateur introuvable";
                }
            } else {
                $_SESSION['info']['danger'] = "Echec ! Formateur introuvable";
            }
        }

        /**
         * Restaurer un formateur
         *
         * @param array $parameters les critères des données à restaurer
         *
         * @return empty
         */
        public function restaurerFormateur($parameters)
        {
           if (!empty($parameters['idFormateur'])) {
                $manager   = new ManagerFormateur();
                $formateur = $manager->chercher([
                    'idFormateur'  => $parameters['idFormateur'],
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($formateur != null) {
                    $retour = $manager->modifier([
                        'idFormateur' => $formateur->getIdFormateur(),
                        'statut' => self::STATUT_ACTIF
                    ]);
                    if ($retour != null) {
                        $_SESSION['info']['success'] = "Le Formateur a été restauré avec succès";
                    }
                }
                else {
                    $_SESSION['info']['danger'] = "Echec ! Formateur introuvable";
                }
            } else {
                $_SESSION['info']['danger'] = "Echec ! Formateur introuvable";
            }
        }

        /**
         * Supprimer un formateur
         *
         * @param array $parameters les critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerFormateur($parameters)
        {
           if (!empty($parameters['idFormateur'])) {
                $manager   = new ManagerFormateur();
                $formateur = $manager->chercher([
                    'idFormateur'  => $parameters['idFormateur'],
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($formateur != null) {
                    $retour = $manager->supprimer([
                        'idFormateur' => $formateur->getIdFormateur()
                    ]);
                    if ($retour != null) {
                        $_SESSION['info']['success'] = "Le Formateur a été supprimé avec succès";
                    }
                }
                else {
                    $_SESSION['info']['danger'] = "Echec ! Formateur introuvable";
                }
            } else {
                $_SESSION['info']['danger'] = "Echec ! Formateur introuvable";
            }
        }

        /**
         * Lister les formateurs
         *
         * @param array $parameters les critères des données à lister
         *
         * @return array
         */
        public function listerFormateurs($parameters)
        {
            $manager = new ManagerFormateur();
            if ($parameters['idSousDomaine'] == self::ALL_DOMAINE) {
                $formateurs = $manager->lister([
                    'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                    'statut'        => $parameters['statut']
                ]);
            } else {
                $tmpFormateurs = $manager->lister([
                    'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                    'statut'        => $parameters['statut']
                ]);
                $formateurs = array();
                foreach ($tmpFormateurs as $formateur) {
                    if ($this->isDomaineFormateur($formateur->getIdFormateur(), $parameters['idSousDomaine'])) {
                        $formateurs[] = $formateur;
                    }
                }
            }
            $donnees = $this->getFormateurs($formateurs); 
            $view = new View("listerFormateurs");
            $view->sendWithoutTemplate("Backend", "Formation", $donnees, "entreprise"); 
            exit();
        }

        /**
         * Récupérer les informations nécessaires concernant les formateurs
         *
         * @param array $formateurs une liste de formateurs
         *
         * @return array
         */
        private function getFormateurs($formateurs)
        {
            $donnees = array();
            foreach ($formateurs as $formateur) {
                $tmp['formateur'] = $formateur;
                $manager  = new ManagerDomaineFormateur();
                $domaineFormateurs = $manager->lister(['idFormateur' => $formateur->getIdFormateur()]);
                $manager = new ManagerSousDomaine();
                $allSousDomaines = $manager->lister(null);
                $sousDomaines = array();
                foreach ($allSousDomaines as $valeur) {
                    if (!$this->isDomaineFormateur($formateur->getIdFormateur(), $valeur->getIdSousDomaine())) {
                        $sousDomaines[] = $valeur;
                    }
                }
                $tmp['sousDomaines'] = $sousDomaines;
                $tmp['domaines'] = array();
                foreach ($domaineFormateurs as $domaineFormateur) {
                    $manager = new ManagerSousDomaine();
                    $tmp['domaines'][] = $manager->chercher(['idSousDomaine' => $domaineFormateur->getIdSousDomaine()]);
                }
                $manager = new ManagerOffreFormateur();
                $offres = $manager->lister(['idFormateur' => $formateur->getIdFormateur()]);
                if (count($offres) > self::NO) {
                    $tmp['editable'] = false;
                } else {
                    $tmp['editable'] = true;
                }
                $donnees[] = $tmp;
            }
            return $donnees;
        } 

        /**
         * Récupérer les informations nécessaires concernant un formateur
         *
         * @param array $parameters critère du formateur
         *
         * @return empty
         */
        public function getFormateur($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerFormateur();
                $formateur = $manager->chercher([
                    'idFormateur'  => $parameters['idFormateur'],
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($formateur != null) {
                    $donnees = $formateur->toArray();
                    echo json_encode($donnees);
                    exit();
                }
            }
        } 

        /**
         * checker si un domaine fait partie de la compétence d'un formateur
         *
         * @param int $idFormateur l'identifiant du formateur
         * @param int $idSousDomaine l'identifiant du domaine
         *
         * @return bool
         */
        private function isDomaineFormateur($idFormateur, $idSousDomaine)
        {
            $manager = new ManagerDomaineFormateur();
            $domaineFormateur = $manager->chercher(['idFormateur' => $idFormateur, 'idSousDomaine' => $idSousDomaine]);
            if ($domaineFormateur != null) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Ajouter un domaine à un formateur
         *
         * @param array $parameters critère des données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourDomaineFormateur($parameters)
        {
            if (!empty($parameters['idFormateur']) && !empty($parameters['idSousDomaine'])) {
                $manager   = new ManagerFormateur();
                $formateur = $manager->chercher([
                    'idFormateur'  => $parameters['idFormateur'],
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($formateur != null) {
                    $manager = new ManagerDomaineFormateur();
                    $retour  = $manager->ajouter([
                        'idFormateur'   => $parameters['idFormateur'],
                        'idSousDomaine' => $parameters['idSousDomaine']
                    ]);
                    if ($retour->getIdDomaineFormateur() != null) {
                        echo self::AJAX_OK;
                        exit();
                    }
                }
            } 
            echo self::AJAX_WRONG;
            exit();
        }

        /**
         * Supprimer un domaine de formateur
         *
         * @param array $parameters critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerDomaineFormateur($parameters)
        {
            if (!empty($parameters['idFormateur']) && !empty($parameters['idSousDomaine'])) {
                $manager   = new ManagerFormateur();
                $formateur = $manager->chercher([
                    'idFormateur'  => $parameters['idFormateur'],
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($formateur != null) {
                    $manager = new ManagerDomaineFormateur();
                    $domaineFormateur = $manager->chercher([
                        'idFormateur' => $parameters['idFormateur'],
                        'idSousDomaine' => $parameters['idSousDomaine']
                    ]);
                    if ($domaineFormateur != null) {
                        $manager->supprimer(['idDomaineFormateur' => $domaineFormateur->getIdDomaineFormateur()]);
                        echo self::AJAX_OK;
                        exit();
                    }
                }
            } 
            echo self::AJAX_WRONG;
            exit();
        }

        /**
         * Voir les formations déjà effectuées par un formateur au sein de l'entreprise
         *
         * @param array $parameters les critères des données à voir
         *
         * @return array
         */
        public function voirFormationFormateur($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerFormateur();
                $formateur = $manager->chercher(['idFormateur' => $parameters['idFormateur']]);
                $manager = new ManagerOffreFormateur();
                $offres = $manager->lister([
                    'idFormateur' => $formateur->getIdFormateur(),
                    'statut' => self::OFFRE_VALIDATED
                ]);
                $manager = new ManagerFormationProfessionnelle();
                $formations = array();
                foreach ($offres as $offre) {
                    $formation = $manager->chercher(['idOffreFormateur' => $offre->getIdOffreFormateur()]);
                    $formations[] = $formation;
                }
                $donnees = $this->getFormationProfessionnelles($formations);
                return [
                    'formateur' => $formateur,
                    'donnees' => $donnees
                ];
            }
        }

        /**
         * Voir les offres sur une formation
         *
         * @param array $parameters les critères des données à voir
         *
         * @return array
         */
        public function voirOffreFormation($parameters)
        {
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($parameters['idThemeFormation'])) {
                $manager = new ManagerThemeFormation();
                $theme    = $manager->chercher([
                    'idThemeFormation' => $parameters['idThemeFormation'],
                    'idEntreprise' => $entreprise->getIdEntreprise()
                ]);
                if ($theme != null) {
                    $manager    = new ManagerSousDomaine();
                    $domaine    = $manager->chercher(['idSousDomaine' => $theme->getIdSousDomaine()]);
                    $manager    = new ManagerDomaineFormateur();
                    $domaineFormateurs = $manager->lister(['idSousDomaine' => $domaine->getIdSousDomaine()]);
                    $formateurs = array();
                    $manager    = new ManagerFormateur();
                    foreach ($domaineFormateurs as $domaineFormateur) {
                        $formateurs[] = $manager->chercher(['idFormateur' => $domaineFormateur->getIdFormateur()]);
                    }
                    return [
                        'entreprise' => $entreprise,
                        'formateurs' => $formateurs,
                        'theme'      => $theme
                    ];
                }
            }
        }

        /**
         * Lister les offres pour un thème donné
         * 
         * @param array $parameters
         *
         * @return array
         */
        public function listerOffreFormations($parameters)
        {
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($parameters['idThemeFormation'])) {
                $manager  = new ManagerThemeFormation();
                $theme    = $manager->chercher([
                    'idThemeFormation' => $parameters['idThemeFormation'],
                    'idEntreprise' => $entreprise->getIdEntreprise()
                ]);
                if ($theme != null) {
                    $manager = new ManagerOffreFormateur();
                    $allOffres  = $manager->lister(['idThemeFormation' => $theme->getIdThemeFormation()]);
                    $offres = array();
                    foreach ($allOffres as $offre) {
                        if (!empty($parameters['coutMin']) && !empty($parameters['coutMax'])) {
                            if ($offre->getCout() >= $parameters['coutMin'] && $offre->getCout() <= $parameters['coutMax']) {
                                $offres[] = $offre;
                            }
                        } elseif (!empty($parameters['coutMin']) && empty($parameters['coutMax'])) {
                            if ($offre->getCout() >= $parameters['coutMin']) {
                                $offres[] = $offre;
                            }
                        } elseif (empty($parameters['coutMin']) && !empty($parameters['coutMax'])) {
                            if ($offre->getCout() <= $parameters['coutMax']) {
                                $offres[] = $offre;
                            }
                        } else {
                            $offres[] = $offre;
                        }
                    }
                    $donnees = $this->getOffreFormations($offres);
                    $view = new View("listerOffreFormations");
                    $view->sendWithoutTemplate("Backend", "Formation", $donnees, "entreprise"); 
                    exit();
                }
            }
        }

        /**
         * Récupérer les informations concernant les offres
         *
         * @param array $offres une liste d'offres
         *
         * @return array
         */
        public function getOffreFormations($offres)
        {
            $donnees = array();
            foreach ($offres as $offre) {
                $tmp['offre'] = $offre;
                $manager = new ManagerThemeFormation();
                $tmp['theme'] = $manager->chercher(['idThemeFormation' => $offre->getIdThemeFormation()]);
                $manager = new ManagerSousDomaine();
                $tmp['domaine'] = $manager->chercher(['idSousDomaine' => $tmp['theme']->getIdSousDomaine()]);
                $manager = new ManagerFormateur();
                $tmp['formateur'] = $manager->chercher(['idFormateur' => $offre->getIdFormateur()]);
                $manager = new ManagerFormationProfessionnelle();
                $formations = $manager->lister(['idOffreFormateur' => $tmp['offre']->getIdOffreFormateur()]);
                $tmp['formationOuverte'] = false;
                $tmp['formationEditable'] = true;
                foreach ($formations as $formation) {
                    $today = date('Y-m-d');
                    if ($formation->getDebut() != null) {
                        if (strtotime($today) <= strtotime($formation->getDebut())) {
                            $tmp['formationOuverte'] = true;
                            $tmp['isParticipant'] = $this->isParticipant($_SESSION['user']['idEmploye'], $formation->getIdFormationProfessionnelle());
                            $tmp['isDemandant'] = $this->aEnvoyeDemandeFormation($_SESSION['user']['idEmploye'], $formation->getIdFormationProfessionnelle());
                            $tmp['formation'] = $formation;
                        }
                    }
                    if ($formation->getFin() != null) {
                        if (strtotime($today) > strtotime($formation->getFin())) {
                            $tmp['offreEditable'] = false; 
                        }
                    }
                }
                $donnees[] = $tmp;
            }
            return $donnees;
        }

        /**
         * mettre à jour une offre de formation
         *
         * @param array $parameters les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourOffreFormation($parameters)
        {
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($parameters)) {
                $manager  = new ManagerThemeFormation();
                $theme    = $manager->chercher([
                    'idThemeFormation' => $parameters['idThemeFormation'],
                    'idEntreprise' => $entreprise->getIdEntreprise()
                ]);
                if ($theme != null) {
                    if (empty($parameters['idOffreFormateur'])) {
                        if (!$this->existOffreFormateur($parameters['idFormateur'], $theme->getIdThemeFormation())) {
                            $manager = new ManagerOffreFormateur();
                            $retour = $manager->ajouter([
                                'idFormateur' => $parameters['idFormateur'],
                                'cout' => $parameters['cout'],
                                'idThemeFormation' => $theme->getIdThemeFormation()
                            ]);
                            if ($retour->getIdOffreFormateur() != self::NO) {
                                $_SESSION['info']['success'] = "L'offre a été ajoutée avec succès !";
                            } else {
                                $_SESSION['info']['danger'] = "Echec de l'ajout de l'offre";
                            }
                        } else {
                            $_SESSION['info']['warning'] = "Une offre de ce formateur existe déjà !";
                        }
                    } else {
                        $manager = new ManagerOffreFormateur();
                        $retour = $manager->modifier([
                            'idOffreFormateur' => $parameters['idOffreFormateur'],
                            'idFormateur' => $parameters['idFormateur'],
                            'cout' => $parameters['cout'],
                            'idThemeFormation' => $theme->getIdThemeFormation()
                        ]);
                    }
                }
            }
        }

        /**
         * Vérifier si une offre de formateur existe déjà
         *
         * @param int $idFormateur      l'identifiant du formateur
         * @param int $idThemeFormation l'identifiant du thème 
         *
         * @return bool
         */
        private function existOffreFormateur($idFormateur, $idThemeFormation)
        {
            $manager = new ManagerOffreFormateur();
            $offre = $manager->chercher([
                'idFormateur' => $idFormateur,
                'idThemeFormation' => $idThemeFormation
            ]);
            if ($offre != null) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Récupérer les informations nécessaires concernant une offre de formation
         *
         * @param array $parameters critère du formateur
         *
         * @return empty
         */
        public function getOffreFormateur($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerOffreFormateur();
                $offreFormateur = $manager->chercher([
                    'idOffreFormateur'  => $parameters['idOffreFormateur'],
                ]);
                if ($offreFormateur != null) {
                    $donnees = $offreFormateur->toArray();
                    echo json_encode($donnees);
                    exit();
                }
            }
        } 

        /**
         * Supprimer un formateur
         *
         * @param array $parameters les critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerOffreFormation($parameters)
        {
           if (!empty($parameters['idOffreFormateur'])) {
                $manager   = new ManagerOffreFormateur();
                $offre = $manager->chercher([
                    'idOffreFormateur'  => $parameters['idOffreFormateur']
                ]);
                if ($offre != null) {
                    $manager   = new ManagerThemeFormation();
                    $theme = $manager->chercher([
                        'idThemeFormation'  => $offre->getIdThemeFormation(),
                        'idEntreprise' => $_SESSION['user']['idEntreprise']
                    ]); 
                    if ($theme != null) {
                        $manager = new ManagerOffreFormateur();
                        $retour = $manager->supprimer([
                            'idOffreFormateur' => $offre->getIdOffreFormateur()
                        ]);
                        if ($retour == true) {
                            $_SESSION['info']['success'] = "L'offre a été supprimée avec succès";
                            header("Location : " . HOST . "manage/entreprise/offreFormation?idThemeFormation=" . $theme->getIdThemeFormation());
                            exit();
                        }
                    }
                }
            }
            $_SESSION['info']['danger'] = "Echec ! Offre introuvable";
            header("Location : " . HOST . "manage/entreprise/catalogueFormation");
            exit();
        }

        /**
         * Valider une offre de formation
         *
         * @param array $parameters les critères des données à valider
         *
         * @return empty
         */
        public function validerOffreFormation($parameters)
        {
            if (!empty($parameters['idOffreFormateur'])) {
                $manager = new ManagerOffreFormateur();
                $offre = $manager->chercher([
                    'idOffreFormateur' => $parameters['idOffreFormateur']
                ]);
                if ($offre != null) {
                    $manager  = new ManagerThemeFormation();
                    $theme = $manager->chercher([
                        'idThemeFormation' => $offre->getIdThemeFormation(),
                        'idEntreprise' => $_SESSION['user']['idEntreprise']
                    ]);
                    if ($theme != null) {
                        $manager = new ManagerOffreFormateur();
                        $retour = $manager->modifier([
                            'idThemeFormation' => $offre->getIdThemeFormation(),
                            'statut' => self::OFFRE_VALIDATED
                        ]);
                        if ($retour != null) {
                            $manager = new ManagerFormationProfessionnelle();
                            $retour = $manager->ajouter([
                                'idOffreFormateur' => $offre->getIdOffreFormateur()
                            ]);
                            if ($retour->getIdFormationProfessionnelle() != self::NO) {
                                $_SESSION['info']['success'] = "L'offre a été validée";
                                header("Location : " . HOST . "manage/entreprise/offreFormation?idThemeFormation=" . $theme->getIdThemeFormation());
                                exit();
                            }
                        }
                    }
                }
            }
            $_SESSION['info']['danger'] = "Echec ! Offre introuvable";
            header("Location : " . HOST . "manage/entreprise/catalogueFormation");
            exit();
        }

        /**
         * Reprendre une offre de formation
         *
         * @param array $parameters les critères de l'offre à reprendre
         *
         * @return empty
         */
        public function reprendreOffreFormation($parameters)
        {
            $this->validerOffreFormation($parameters);
        }

        /**
         * Annuler une offre de formation
         *
         * @param array $parameters les critères des données à annuler
         *
         * @return empty
         */
        public function annulerOffreFormation($parameters)
        {
            if (!empty($parameters['idOffreFormateur'])) {
                $manager = new ManagerOffreFormateur();
                $offre = $manager->chercher([
                    'idOffreFormateur' => $parameters['idOffreFormateur']
                ]);
                if ($offre != null) {
                    $manager  = new ManagerThemeFormation();
                    $theme = $manager->chercher([
                        'idThemeFormation' => $offre->getIdThemeFormation(),
                        'idEntreprise' => $_SESSION['user']['idEntreprise']
                    ]);
                    if ($theme != null) {
                        $manager = new ManagerOffreFormateur();
                        $retour = $manager->modifier([
                            'idThemeFormation' => $offre->getIdThemeFormation(),
                            'statut' => self::OFFRE_PROPOSED
                        ]);
                        if ($retour != null) {
                            $_SESSION['info']['success'] = "La validation de l'offre a été annulée";
                            header("Location : " . HOST . "manage/entreprise/offreFormation?idThemeFormation=" . $theme->getIdThemeFormation());
                            exit();
                        }
                    }
                }
            }
            $_SESSION['info']['danger'] = "Echec ! Offre introuvable";
            header("Location : " . HOST . "manage/entreprise/catalogueFormation");
            exit();
        }


        /**
         * Voir les formations
         *
         * @param array $parameters critères des données à voir
         * 
         * @return array
         */
        public function voirFormation($parameters)
        {
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager    = new ManagerSousDomaine();
            $domaines   = $manager->lister(null);
            $manager    = new ManagerEmploye();
            $employes   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            return [
                'entreprise' => $entreprise,
                'domaines'   => $domaines,
                'employes'   => $employes
            ];
        }

        /**
         * Lister les formations d'une année
         *
         * @param array $parameters critères des données à lister
         *
         * @return array
         */
        public function listerFormationProfessionnelles($parameters)
        {
            $manager = new ManagerThemeFormation();
            if ($parameters['idSousDomaine'] == self::ALL_DOMAINE) {
                $themes = $manager->lister([
                    'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                    'annee'         => $parameters['annee']
                ]);
            } else {
                $themes = $manager->lister([
                    'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                    'annee'         => $parameters['annee'],
                    'idSousDomaine' => $parameters['idSousDomaine']
                ]);
            }
            $offres = array();
            foreach ($themes as $theme) {
                $manager = new ManagerOffreFormateur();
                $offre = $manager->chercher([
                    'idThemeFormation' => $theme->getIdThemeFormation(),
                    'statut' => self::OFFRE_VALIDATED
                ]);
                if ($offre != null) {
                    $offres[] = $offre;
                }
            }
            $formations = array();
            foreach ($offres as $offre) {
                $manager = new ManagerFormationProfessionnelle();
                $tmpFormations = array();
                $tmpFormations = $manager->lister(['idOffreFormateur' => $offre->getIdOffreFormateur()]);
                foreach ($tmpFormations as $tmpFormation) {
                    $formations[] = $tmpFormation;
                }
            }
            $donnees = $this->getFormationProfessionnelles($formations); 
            $view = new View("listerFormations");
            $view->sendWithoutTemplate("Backend", "Formation", $donnees, "entreprise"); 
            exit();
        }

        /**
         * Récupérer les informations concernant les formations
         *
         * @param array $formations une liste de formations
         *
         * @return array
         */
        public function getFormationProfessionnelles($formations)
        {
            $donnees = array();
            foreach ($formations as $formation) {
                $tmp['formation'] = $formation;
                $manager = new ManagerOffreFormateur();
                $tmp['offreFormateur'] = $manager->chercher(['idOffreFormateur' => $formation->getIdOffreFormateur()]);
                $manager = new ManagerFormateur();
                $tmp['formateur'] = $manager->chercher(['idFormateur' => $tmp['offreFormateur']->getIdFormateur()]);
                $manager = new ManagerThemeFormation();
                $tmp['theme'] = $manager->chercher(['idThemeFormation' => $tmp['offreFormateur']->getIdThemeFormation()]);
                $manager = new ManagerSousDomaine();
                $tmp['sousDomaine'] = $manager->chercher(['idSousDomaine' => $tmp['theme']->getIdSousDomaine()]);
                $manager = new ManagerEmployeFormation();
                $employeFormations = $manager->lister(['idFormationProfessionnelle' => $formation->getIdFormationProfessionnelle()]);
                $tmp['participants'] = array();
                foreach ($employeFormations as $employeFormation) {
                    $manager = new ManagerEmploye();
                    $tmp['participants'][] = $manager->chercher(['idEmploye' => $employeFormation->getIdEmploye()]);
                }
                $manager = new ManagerEvaluationFormation();
                $tmp['evaluations'] = $this->getEvaluationFormations($formation->getIdFormationProfessionnelle());
                $tmp['editable'] = true;
                if ($formation->getFin() != null) {
                    $today = date('Y-m-d');
                    if (strtotime($today) > strtotime($formation->getFin())) {
                        $tmp['editable'] = false;
                    }
                }
                $manager = new ManagerEmploye();
                $employes = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $tmp['employes'] = $employes;
                $donnees[] = $tmp;
            }
            return $donnees;
        }

        /**
         * Récupérer les évaluations d'une formation
         *
         * @param int $idFormation l'identifiant de la formation
         *
         * @return array
         */
        private function getEvaluationFormations($idFormation)
        {
            $donnees      = array();
            $manager      = new ManagerFormationProfessionnelle();
            $formation    = $manager->chercher(['idFormationProfessionnelle' => $idFormation]);
            $manager      = new ManagerEmployeFormation();
            $employeFormations = $manager->lister(['idFormationProfessionnelle' => $idFormation]);
            $donnees['individuelles'] = array();
            $evaluationEmployeTotale = 0;
            $nombreEvaluationEmploye = 0;
            $evaluationSuperieurTotale = 0;
            $nombreEvaluationSuperieur = 0; 
            foreach ($employeFormations as $employeFormation) {
                $manager = new ManagerEmploye();
                $tmp['employe'] = $manager->chercher(['idEmploye' => $employeFormation->getIdEmploye()]);
                $manager = new ManagerEvaluationFormation();
                $tmp['evaluationEmploye'] = $manager->chercher([
                    'evaluateur' => $employeFormation->getIdEmploye(),
                    'idFormationProfessionnelle' => $employeFormation->getIdFormationProfessionnelle()
                ]);
                if ($tmp['evaluationEmploye'] != null) {
                    $evaluationEmployeTotale += $tmp['evaluationEmploye']->getNote();
                    $nombreEvaluationEmploye++;
                }
                $manager = new ManagerEmploye();
                $idChef = $tmp['employe']->getChefHierarchique();
                if ($idChef != self::NO) {
                    $tmp['chef'] = $manager->chercher(['idEmploye' => $idChef]);
                    $manager = new ManagerEvaluationFormation();
                    $tmp['evaluationSuperieur'] = $manager->chercher([
                        'evaluateur' => $tmp['chef']->getIdEmploye(),
                        'idEmployeFormation' => $employeFormation->getIdEmployeFormation()
                    ]);
                    if ($tmp['evaluationSuperieur'] != null) {
                        $evaluationSuperieurTotale += $tmp['evaluationSuperieur']->getNote();
                        $nombreEvaluationSuperieur++;
                    }
                } else {
                    $tmp['chef'] = null;
                    $tmp['evaluationSuperieur'] = null;
                }
                $donnees['individuelles'][] = $tmp; 
            }
            if ($nombreEvaluationEmploye != self::NO) {
                $donnees['moyenne']['participants'] = number_format($evaluationEmployeTotale / $nombreEvaluationEmploye, 1);
            } else {
                $donnees['moyenne']['participants'] = "N/A";
            }
            if ($nombreEvaluationSuperieur != self::NO) {
                $donnees['moyenne']['superieurs']   = number_format($evaluationSuperieurTotale / $nombreEvaluationSuperieur, 1); 
            } else {
                $donnees['moyenne']['superieurs'] = "N/A";
            }
            return $donnees;
        }

        /**
         * Envoyer un rappel d'évaluation à un employé
         *
         * @param array $parameters critères du rappel
         *
         * @return empty
         */
        public function rappelerEvaluationFormation($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerFormationProfessionnelle();
                $formation = $manager->chercher(['idFormationProfessionnelle' => $parameters['idFormationProfessionnelle']]);
                $manager = new ManagerEmploye();
                $employe = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $content = $this->generateMessageContent(self::TYPE_REMINDER, $formation, $parameters['type']);
                $this->sendMessageNotification($employe->getIdCompte(), 'Rappel', $content);
            }
        }

        /**
         * Mettre à jour une formation
         *
         * @param array $parameters critères des données à mettre à jour
         *
         * @return array
         */
        public function mettreAJourFormationProfessionnelle($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerFormationProfessionnelle();
                $formation = $manager->chercher([
                    'idFormationProfessionnelle' => $parameters['idFormationProfessionnelle']
                ]);
                if ($formation != null) {
                    $retour = $manager->modifier([
                        'idFormationProfessionnelle' => $formation->getIdFormationProfessionnelle(),
                        'debut' => $parameters['debut'],
                        'fin' => $parameters['fin'],
                    ]);
                    if ($retour->getDebut() != null) {
                        $_SESSION['info']['success'] = "La formation a été modifiée avec succès";
                    } else {
                        $_SESSION['info']['danger'] = "Echec de la modification";
                    }
                }
            }
        }

        /**
         * Récupérer les données d'une formation
         *
         * @param array $parameters critères des données à récupérer
         *
         * @return empty
         */
        public function getFormationProfessionnelle($parameters)
        {
            if (!empty($parameters['idFormationProfessionnelle'])) {
                $manager = new ManagerFormationProfessionnelle();
                $formation = $manager->chercher(['idFormationProfessionnelle' => $parameters['idFormationProfessionnelle']]);
                $editable = true;
                if ($formation->getFin() != null) {
                    $today = date('Y-m-d');
                    if (strtotime($today) > strtotime($formation->getFin())) {
                        $editable = false;
                    }
                }
                if ($formation != null) {
                    $formation = $formation->toArray();
                    $formation['editable'] = $editable;
                    echo json_encode($formation);
                    exit();
                }
            }
        }

        /**
         * Vérifier si un employé participe à une formation
         *
         * @param int idEmploye                  l'identifiant de l'employé
         * @param int idFormationProfessionnelle l'identifiant de la formation
         *
         * @return boolean
         */
        private function isParticipant($idEmploye, $idFormationProfessionnelle)
        {
            $manager = new ManagerEmployeFormation();
            $employeFormation = $manager->chercher([
                'idEmploye'  => $idEmploye,
                'idFormationProfessionnelle' => $idFormationProfessionnelle
            ]);
            if ($employeFormation != null) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Ajouter un participant dans une formation
         *
         * @param array $parameters  critère des données à ajouter
         *
         * @param return empty
         */
        public function mettreAJourEmployeFormation($parameters)
        {
            if (!empty($parameters['idFormationProfessionnelle']) && !empty($parameters['idEmploye'])) {
                $manager   = new ManagerEmploye();
                $employe = $manager->chercher([
                    'idEmploye'  => $parameters['idEmploye'],
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($employe != null) {
                    $manager = new ManagerEmployeFormation();
                    $retour  = $manager->ajouter([
                        'idFormationProfessionnelle' => $parameters['idFormationProfessionnelle'],
                        'idEmploye' => $parameters['idEmploye']
                    ]);
                    if ($retour->getIdEmployeFormation() != null) {
                        if ($employe->getChefHierarchique() != self::NO) {
                            $manager = new ManagerDemandeFormation();
                            $retour  = $manager->ajouter([
                                'idEmploye' => $employe->getIdEmploye(),
                                'idFormationProfessionnelle' => $parameters['idFormationProfessionnelle'],
                                'validateur' => $employe->getChefHierarchique(),
                                'statut' => self::DEMANDE_VALIDATED,
                                'etat' => self::DEMANDE_ACTIF
                            ]);
                        }
                        echo self::AJAX_OK;
                        exit();
                    }
                }
            } 
            echo self::AJAX_WRONG;
            exit();
        }

        /**
         * Retirer un participant d'une formation
         *
         * @param array $parameters  critère des données à supprimer
         *
         * @param return empty
         */
        public function supprimerEmployeFormation($parameters)
        {
            if (!empty($parameters['idFormationProfessionnelle'])) {
                if (!empty($parameters['idEmploye'])) {
                    $manager   = new ManagerEmploye();
                    $employe = $manager->chercher([
                        'idEmploye'  => $parameters['idEmploye'],
                        'idEntreprise' => $_SESSION['user']['idEntreprise']
                    ]);
                    if ($employe != null) {
                        $manager = new ManagerEmployeFormation();
                        $employeFormation  = $manager->chercher([
                            'idFormationProfessionnelle' => $parameters['idFormationProfessionnelle'],
                            'idEmploye' => $parameters['idEmploye']
                        ]);
                        if ($employeFormation != null) {
                            $retour = $manager->supprimer([
                                'idEmployeFormation' => $employeFormation->getIdEmployeFormation()
                            ]);
                            if ($retour) {
                                $manager = new ManagerDemandeFormation();
                                $demande = $manager->chercher([
                                    'idEmploye' => $employe->getIdEmploye(),
                                    'idFormationProfessionnelle' => $parameters['idFormationProfessionnelle']
                                ]);
                                if ($demande != null) {
                                    $manager->supprimer(['idDemandeFormation' => $demande->getIdDemandeFormation()]);
                                }
                                echo self::AJAX_OK;
                                exit();
                            }
                        }
                    }
                    echo self::AJAX_WRONG;
                    exit();
                } else {
                    $manager = new ManagerEmploye();
                    $employe = $manager->chercher([
                        'idEmploye' => $_SESSION['user']['idEmploye']
                    ]);
                    $manager = new ManagerEmployeFormation();
                    $employeFormation = $manager->chercher([
                        'idFormationProfessionnelle' => $parameters['idFormationProfessionnelle'],
                        'idEmploye' => $employe->getIdEmploye()
                    ]);
                    if ($employeFormation != null) {
                        $retour = $manager->supprimer([
                            'idEmployeFormation' => $employeFormation->getIdEmployeFormation()
                        ]);
                        if ($retour) {
                            $_SESSION['info']['success'] = "Vous avez annulé votre participation";
                        } else {
                            $_SESSION['info']['danger'] = "Echec de l'opération !";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "Données introuvables !";
                    }
                }
            } 
        }

        /**
         * participer à une formation 
         *
         * @param array $parameters les critères de la formation
         *
         * @return empty
         */
        public function participerFormationProfessionnelle($parameters)
        {
            if (!empty($parameters['idFormationProfessionnelle'])) {
                $manager  = new ManagerEmploye();
                $employe = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
                if ($employe->getChefHierarchique() == self::NO) {
                    $manager = new ManagerEmployeFormation();
                    $retour  = $manager->ajouter([
                        'idFormationProfessionnelle' => $parameters['idFormationProfessionnelle'],
                        'idEmploye' => $employe->getIdEmploye()
                    ]);
                    if ($retour->getIdEmployeFormation() != null) {
                        $_SESSION['info']['success'] = "Votre demande a été envoyée !"; 
                    } else {
                        $_SESSION['info']['danger'] = "Erreur lors de l'envoi de la demande !"; 
                    }
                } else {
                    $manager = new ManagerDemandeFormation();
                    $retour = $manager->ajouter([
                        'idFormationProfessionnelle' => $parameters['idFormationProfessionnelle'],
                        'idEmploye'  => $employe->getIdEmploye(),
                        'validateur' => $employe->getChefHierarchique(),
                        'statut'     => self::DEMANDE_PROPOSED,
                        'etat'       => self::DEMANDE_ACTIF
                    ]);
                    if ($retour->getIdDemandeFormation() != null) {
                        $_SESSION['info']['success'] = "Votre demande a été envoyée !"; 
                    } else {
                        $_SESSION['info']['danger'] = "Erreur lors de l'envoi de la demande !"; 
                    }
                }
            }
        }

        /**
         * annuler une demande de formation 
         *
         * @param array $parameters les critères de la demande
         *
         * @return empty
         */
        public function annulerDemandeFormation($parameters)
        {
            if (!empty($parameters['idFormationProfessionnelle'])) {
                $manager = new ManagerEmploye();
                $employe = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
                $manager = new ManagerDemandeFormation();
                $demande = $manager->chercher([
                    'idEmploye' => $employe->getIdEmploye(),
                    'idFormationProfessionnelle' => $parameters['idFormationProfessionnelle']
                ]);
                if ($demande != null) {
                    $retour = $manager->supprimer(['idDemandeFormation' => $demande->getIdDemandeFormation()]);
                    if ($retour != false) {
                        $_SESSION['info']['success'] = "Votre demande a été retirée";
                    } else {
                        $_SESSION['info']['danger'] = "Echec de l'opération !";
                    }
                } else {
                    $_SESSION['info']['danger'] = "Demande introuvable";
                }
            }
        }

        /**
         * Archiver une demande de formation
         *
         * @param array $parameters les critères de la demande
         *
         * @return empty
         */
        public function archiverDemandeFormation($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerDemandeFormation();
                $demande = $manager->chercher(['idDemandeFormation' => $parameters['idDemandeFormation']]);
                $manager = new ManagerEmploye();
                $demandeur = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                if ($demandeur->getChefHierarchique() == $_SESSION['user']['idEmploye']) {
                    $manager = new ManagerDemandeFormation();
                    $manager->modifier([
                        'idDemandeFormation' => $demande->getIdDemandeFormation(),
                        'etat' => self::DEMANDE_ARCHIVED
                    ]);
                }
            } else {
                $_SESSION['info']['danger'] = "Opération refusée !";
            }
        }

        /**
         * Valider une demande de formation
         *
         * @param array $parameters les critères de la demande
         *
         * @return empty
         */
        public function validerDemandeFormation($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerDemandeFormation();
                $demande = $manager->chercher(['idDemandeFormation' => $parameters['idDemandeFormation']]);
                $manager = new ManagerEmploye();
                $demandeur = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                if ($demandeur->getChefHierarchique() == $_SESSION['user']['idEmploye']) {
                    $manager = new ManagerEmployeFormation();
                    $retour = $manager->ajouter([
                        'idEmploye' => $demandeur->getIdEmploye(),
                        'idFormationProfessionnelle' => $demande->getIdFormationProfessionnelle()
                    ]);
                    if ($retour->getIdEmployeFormation()) {
                        $manager = new ManagerDemandeFormation();
                        $manager->modifier([
                            'idDemandeFormation' => $demande->getIdDemandeFormation(),
                            'statut' => self::DEMANDE_VALIDATED
                        ]);
                        $_SESSION['info']['success'] = "Opération réussie !";
                    }
                } else {
                    $_SESSION['info']['danger'] = "Opération refusée !";
                }
            }
        }

        /**
         * Refuser une demande de formation
         *
         * @param array $parameters les critères de la demande
         *
         * @return empty
         */
        public function refuserDemandeFormation($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerDemandeFormation();
                $demande = $manager->chercher(['idDemandeFormation' => $parameters['idDemandeFormation']]);
                $manager = new ManagerEmploye();
                $demandeur = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                if ($demandeur->getChefHierarchique() == $_SESSION['user']['idEmploye']) {
                    $manager = new ManagerDemandeFormation();
                    $manager->modifier([
                        'idDemandeFormation' => $demande->getIdDemandeFormation(),
                        'statut' => self::DEMANDE_REFUSED
                    ]);
                } else {
                    $_SESSION['info']['danger'] = "Opération refusée !";
                }
            }
        }

        /**
         * Restaurer une demande de formation
         *
         * @param array $parameters les critères de la demande
         *
         * @return empty
         */
        public function restaurerDemandeFormation($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerDemandeFormation();
                $demande = $manager->chercher(['idDemandeFormation' => $parameters['idDemandeFormation']]);
                $manager = new ManagerEmploye();
                $demandeur = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                if ($demandeur->getChefHierarchique() == $_SESSION['user']['idEmploye']) {
                    $manager = new ManagerDemandeFormation();
                    $manager->modifier([
                        'idDemandeFormation' => $demande->getIdDemandeFormation(),
                        'etat' => self::DEMANDE_ACTIF
                    ]);
                } else {
                    $_SESSION['info']['danger'] = "Opération refusée !";
                }
            }
        }

        /**
         * Vérifier si un employé a demandé à participer à une formation
         *
         * @param int idEmploye                  l'identifiant de l'employé
         * @param int idFormationProfessionnelle l'identifiant de la formation
         *
         * @return boolean
         */
        private function aEnvoyeDemandeFormation($idEmploye, $idFormationProfessionnelle)
        {
            $manager = new ManagerDemandeFormation();
            $demandeFormation = $manager->chercher([
                'idEmploye'  => $idEmploye,
                'idFormationProfessionnelle' => $idFormationProfessionnelle
            ]);
            if ($demandeFormation != null) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Voir les formations disponibles
         *
         * @param array $parameters critère des données à afficher
         *
         * @return array
         */
        public function voirFormationDisponible($parameters)
        {
            $year    = date('Y');
            $manager = new ManagerEmploye();
            $employe = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
            $manager = new ManagerThemeFormation();
            $themes  = $manager->lister([
                'annee'        => $year,
                'idEntreprise' => $employe->getIdEntreprise()
            ]);
            $manager = new ManagerOffreFormateur();
            $offres  = array();
            foreach ($themes as $theme) {
                $offreValidee = $manager->chercher([
                    'idThemeFormation' => $theme->getIdThemeFormation(),
                    'statut'           => self::OFFRE_VALIDATED
                ]);
                if ($offreValidee != null) {
                    $offres[] = $offreValidee;
                }
            }
            $donnees = $this->getOffreFormations($offres);
            return $donnees;
        }

        /**
         * Voir les formations assistées par un employé
         *
         * @param array $parameters  critère des données à afficher
         *
         * @return array
         */
        public function voirFormationAssistee($parameters)
        {
            $manager = new ManagerEmploye();
            $employe = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
            $manager = new ManagerEmployeFormation();
            $employeFormations = $manager->lister(['idEmploye' => $employe->getIdEmploye()]);
            $formations = array();
            foreach ($employeFormations as $employeFormation) {
                $manager = new ManagerFormationProfessionnelle();
                $formations[] = $manager->chercher(['idFormationProfessionnelle' => $employeFormation->getIdFormationProfessionnelle()]);
            }
            $donnees = $this->getFormationProfessionnelles($formations);
            return $donnees;
        }

        /**
         * Voir les validations de demande de formation à effectuer
         *
         * @param array $parameters critère des données à afficher
         *
         * @return array
         */
        public function voirValidationFormation($parameters)
        {
            $manager  = new ManagerEmploye();
            $employe  = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
            $manager  = new ManagerDemandeFormation();
            $demandes = $manager->lister(['validateur' => $employe->getIdEmploye()]);
            return $demandes;
        }

        /**
         * Lister les demandes de formations à valider pour un employé
         *
         * @param array $parameters critère des données à lister
         *
         * @return array
         */
        public function listerDemandeFormations($parameters)
        {
            if (!empty($parameters)) {
                $manager  = new ManagerEmploye();
                $employe  = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
                $manager  = new ManagerDemandeFormation();
                if ($parameters['type'] == self::ALL_DEMANDES) {
                    $demandes = $manager->lister([
                        'validateur' => $employe->getIdEmploye(),
                        'etat'       => self::DEMANDE_ACTIF
                    ]);
                } elseif ($parameters['type'] == self::TYPE_DEMANDE_ARCHIVED) {
                    $demandes = $manager->lister([
                        'validateur' => $employe->getIdEmploye(),
                        'etat'       => self::DEMANDE_ARCHIVED
                    ]);
                } else {
                    $demandes = $manager->lister([
                        'validateur' => $employe->getIdEmploye(),
                        'statut'     => $parameters['type']
                    ]);
                }
                $donnees = $this->getDemandeFormations($demandes); 
                $view = new View("listerDemandeFormations");
                $view->sendWithoutTemplate("Backend", "Formation", $donnees, "employe"); 
                exit();
            }
        }

        /**
         * Récupérer les informations sur les demandes de formation
         *
         * @param array $demandes une liste de demandes de formation
         *
         * @return array
         */
        private function getDemandeFormations($demandes)
        {
            $donnees = array();
            foreach ($demandes as $demande) {
                $tmp['demande'] = $demande;
                $manager = new ManagerEmploye();
                $tmp['demandeur'] = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]); 
                $manager = new ManagerFormationProfessionnelle();
                $tmp['formation'] = $manager->chercher(['idFormationProfessionnelle' => $demande->getIdFormationProfessionnelle()]);
                $manager = new ManagerOffreFormateur();
                $offre = $manager->chercher(['idOffreFormateur' => $tmp['formation']->getIdOffreFormateur()]);
                $manager = new ManagerThemeFormation();
                $tmp['theme'] = $manager->chercher(['idThemeFormation' => $offre->getIdThemeFormation()]);
                $donnees[] = $tmp;
            }
            return $donnees;
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
         * Retourner le nombre de jours écoulés entre deux dates
         *
         * @param date $debut le début
         * @param date $fin   la fin
         *
         * @return int
         */
        private function getJours($debut, $fin)
        {
            $secondes = abs(strtotime($debut) - strtotime($fin));
            return ($secondes / self::SECOND_TO_DAY) + self::ONE_DAY ;
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
         * Envoyer un message de notification à un utilisateur
         * 
         * @param int    $idCompte l'identifiant de l'utilisateur
         * @param string $objet    l'objet du message
         * @param string $contenu  le contenu du message   
         *
         * @return empty
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
        }

        /**
         * Générer un contenu de message de notification
         *
         * @param string  $type            le type de notification
         * @param object  $object          l'objet en question
         * @param int     $typeEvaluation  le type de l'évaluation
         *
         * @return string
         */
        private function generateMessageContent($type, $object, $typeEvaluation=null)
        {
            $content = "<p>Bonjour, </p>";
            if ($type == self::TYPE_REQUEST) {
                $manager  = new ManagerCompte();
                $compte   = $manager->chercher(['idCompte' => $object->getIdCompte()]);
                $content .= "<p>" .
                            "Vous avez une <a href='" . HOST . "manage/" . $compte->getIdentifiant() . "/voirValidationFormation'>validation de demande de formation</a> à effectuer." .
                            "</p>";
            } elseif ($type == self::TYPE_VALIDATED) {
                $content .= "<p>" .
                            "Votre <a href='" . HOST . "manage/employe/formationDisponible'>demande de formation</a> " .
                            "pour le thème <span class='text-important'>" . $object->getTheme() . "</span> a été validée" .
                            "</p>";
            } elseif ($type == self::TYPE_REJECTED) {
                $content .= "<p>" .
                            "Votre <a href='" . HOST . "manage/employe/formationDisponible'>demande de formation</a> " .
                            "pour le thème <span class='text-important'>" . $object->getTheme() . "</span> a été refusée" .
                            "</p>";
            } elseif ($type == self::TYPE_INFORMATION) {
                $manager   = new ManagerEmploye();
                $employe   = $manager->chercher(['idEmploye' => $object->getIdEmploye()]);
                $content .= "<p>" .
                            "Nous vous informons que " .
                            $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() . 
                            "sera en formation le " . $object->getDebut() . " jusqu'au " . $object->getFin() .
                            "</p>";
            } elseif ($type == self::TYPE_REMINDER) {
                if ($typeEvaluation == self::EVALUATION_FORMATION) {
                    $content .= "<p>" .
                            "Veuillez évaluer <a href='" . HOST . "manage/employe/formationAssistee'>une formation</a>" .
                            " à laquelle vous avez participé." .
                            "</p>";
                } elseif ($typeEvaluation == self::EVALUATION_PARTICIPANT) {
                    $content .= "<p>" .
                            "Veuillez évaluer <a href='" . HOST . "manage/employe/validationFormation'>une formation</a> à laquelle " .
                            "un ou plusieurs de vos subordonnés ont participé." .
                            "</p>";
                }
            }
            return $content;
        }
	}