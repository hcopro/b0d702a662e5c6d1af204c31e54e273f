<?php
    
    /**
     * Manager du modules Compte du Frontend
     *
     * @author Voahirana
     *
     * @since 29/10/19 
     */

	use \Core\DbManager;
    use \Model\ManagerSuperadmin;
    use \Model\ManagerEntreprise;
    use \Model\ManagerCandidat;
    use \Model\ManagerCompte;
    use \Model\ManagerPersonnalite;
    use \Model\ManagerNiveauEntretien;
    use \Model\ManagerEmailContact;
    use \Model\ManagerParametrePermission;
    use \Model\ManagerParametreConge;
    use \Model\ManagerConfiguration;
    use \Model\ManagerMenuEntreprise;
    use \Model\ManagerParametrePointage;

	class ManagerModuleCompte extends DbManager
	{
        const DUREE_MAX_PERMISSION = 10;
        const DUREE_MAX_REPOS = 180;
        const ATTENTE_CONGE = 1;
        const PROCESSUS_DEMANDE = 0;
        const NIVEAU_VALIDATION = 0;
        const CALCUL_SOLDE = 0;
        const CONFIG_NOMBRE_ALERTE = 3;
        const CONFIG_PREMIERE_ALERTE = 3;
        const CONFIG_DEUXIEME_ALERTE = 2;
        const CONFIG_TROISIEME_ALERTE = 1;

        /** 
         * Afficher le formulaire d'un candidat
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
         */
        public function afficherFormCandidat($parameters)
        {
            $manager = new ManagerCandidat();
            if (isset($parameters)) {
                $candidat = $manager->chercher($parameters);
            } else {
                $candidat = $manager->initialiser();
            }
            return $candidat;
        }

        /** 
         * Afficher le formulaire d'une entreprise
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
         */
        public function afficherFormEntreprise($parameters)
        {
            $manager = new ManagerEntreprise();
            if (isset($parameters)) {
                $entreprise = $manager->chercher($parameters);
            } else {
                $entreprise = $manager->initialiser();
            }
            return $entreprise;
        }

        /** 
         * Ajouter un candidat
         *
         * @param array $parameters Les données à ajouter 
         *
         * @return object
         */
        public function ajouterCandidat($parameters)
        {
            $candidat   = "";
            $compte     = $this->getCompte();
            if (is_object($compte)) {
                $parameters['idCompte'] = $compte->getIdCompte();
                if (!empty($parameters['idCompte'])) {
                    $manager  = new ManagerCandidat();
                    $candidat = $manager->ajouter($parameters);
                }
                return $candidat;
            }
        }

        /** 
         * Ajouter une entreprise
         *
         * @param array $parameters Les données à ajouter 
         *
         * @return object
         */
        public function ajouterEntreprise($parameters)
        {
            $entreprise             = "";
            $compte                 = $this->getCompte();
            if (is_object($compte)) {
                $parameters['idCompte'] = $compte->getIdCompte();
                if (!empty($parameters['idCompte'])) {
                    $manager    = new ManagerEntreprise();
                    $entreprise = $manager->ajouter($parameters);
                    if ($entreprise->getIdEntreprise() != 0) {
                        $manager                = new ManagerParametrePermission();
                        $parametrePermission    = $manager->ajouter([
                            'idEntreprise'       => $entreprise->getIdEntreprise(),
                            'dureeMaxPermission' => self::DUREE_MAX_PERMISSION,
                            'dureeMaxRepos'      => self::DUREE_MAX_REPOS
                        ]);
                        $manager        = new ManagerParametreConge();
                        $parametreConge = $manager->ajouter([
                            'idEntreprise' => $entreprise->getIdEntreprise(),
                            'attente'      => self::ATTENTE_CONGE,
                            'processus'    => self::PROCESSUS_DEMANDE,
                            'niveau'       => self::NIVEAU_VALIDATION,
                            'calcul'       => self::CALCUL_SOLDE
                        ]);
                        $manager        = new ManagerConfiguration();
                        $configuration  = $manager->ajouter([
                            'idEntreprise' => $entreprise->getIdEntreprise(),
                            'emailAlerte'  => $entreprise->getEmail()
                        ]);
                        $data           = [
                            "service"      => "Nom de votre service entretien",
                            "idEntreprise" => $entreprise->getIdEntreprise(),
                            "ordre"        => 1
                        ];
                        $manager    = new ManagerNiveauEntretien();
                        $manager->ajouter($data);
                        /*     
                            echo "<pre>";
                            $res = array(
                            'entreprise' =>array(
                                'RECRUTEMENT' =>[
                                    "Entretiens"=>[
                                        array(
                                            "links"=>"manage/interlocuteurs",
                                            "title"=> "Interlocuteurs"),
                                        array(
                                            "links"=>"manage/entretiens",
                                            "title"=> "Liste"),
                                        array(
                                            "links"=>"manage/niveaux_entretiens",
                                            "title"=> "Niveaux")
                                    ],
                                    "Mes candidats"=>[
                                        array(
                                            "links"=>"manage/candidatures_entreprise",
                                            "title"=> "Candidatures")
                                    ],
                                    "Offres" =>[
                                        array(
                                            "links"=>"manage/offres",
                                            "title"=> "Liste"),
                                        array(
                                            "links"=>"manage/tableau_de_bord-offre",
                                            "title"=> "Tableau de bord")
                                    ],
                                    "Tests"=>[
                                        array(
                                            "links"=>"manage/tests",
                                            "title"=> "Mes tests"),
                                        array(
                                            "links"=>"manage/resultats",
                                            "title"=> "Résultat")
                                    ]
                                ],
                                'GESTION'=>[
                                    "Capital humain"=>[
                                        array(
                                            "links"=>"manage/entreprise/contrat",
                                            "title"=> "Contrat"),
                                        array(
                                            "links"=>"manage/employes",
                                            "title"=> "Employés"),
                                        array(
                                            "links"=>"manage/entreprise_postes",
                                            "title"=> "Poste"),
                                        array(
                                            "links"=>"manage/entreprise_services",
                                            "title"=> "Service"),
                                        "Formations"=>[
                                            array(
                                                "links"=>"manage/entreprise/formation",
                                                "title"=> "Catalogues de formations"),
                                            array(
                                                "links"=>"manage/entreprise/formateur",
                                                "title"=> "Catalogues de formateurs")
                                        ]
                                    ],
                                    "Temps & Absence"=>[
                                        "Gestion de pointage"=>[
                                            array(
                                                "links"=>"manage/entreprise/pointage/dashboard",
                                                "title"=> "Pointages"),
                                            array(
                                                "links"=>"manage/entreprise/tacheRealisee",
                                                "title"=> "Tâches réalisées")
                                        ],
                                        "Gestion d'absence" =>[
                                            array(
                                                "links"=>"manage/entreprise/permission/dashboard",
                                                "title"=> "Permission"),
                                            "Congé"=>[
                                                array(
                                                    "links"=>"manage/entreprise/interim",
                                                    "title"=> "Intérimaire"),
                                                array(
                                                    "links"=>"manage/entreprise/conge",
                                                    "title"=> "Gestion de congé"),
                                                array(
                                                    "links"=>"manage/entreprise/planning",
                                                    "title"=> "Planning de congés")
                                            ]
                                        ]
                                    ],
                                    "Paie & Prestations sociales"=>[
                                        array(
                                            "links"=>"manage/entreprise/avance",
                                            "title"=> "Avances"),
                                        array(
                                            "links"=>"manage/entreprise/paie",
                                            "title"=> "Paie")
                                    ]
                                ] ,
                                'ÉVALUATION' => [
                                    array(
                                        "links"=>"manage/entreprise/evaluation",
                                        "title"=> 'Mes évaluations'
                                    ),
                                    array(
                                        "links"=>"manage/entreprise/evaluation_modele",
                                        "title"=> 'Créer une évaluation'
                                    ),
                                    array(
                                        "links"=>"manage/entreprise/evaluation/categorie",
                                        "title"=> "Gérer mes formulaires d'évaluations"
                                    )
                                ] ,
                                'BAROMETRE' => [
                                    array(
                                        "links"=>"manage/barometre_list",
                                        "title"=> "Mes baromètres"
                                    )
                                ]
                            ),
                                'employe' => array(
                                    'GESTION'=>[
                                        "Paie & Prestations sociales"=>[
                                            array(
                                                "links"=>"manage/employe/demandeAvance",
                                                "title"=> "Avances"),
                                            array(
                                                "links"=>"manage/employe/detailFichePaie",
                                                "title"=> "Salaire")
                                        ],
                                        "Capital humain"=>[
                                            array(
                                                "links"=>"manage/show-monContrat",
                                                "title"=> "Contrat"),
                                            array(
                                                "links"=>"manage/employe/formationDisponible",
                                                "title"=> "Formation")
                                        ],
                                        "Temps et Absence"=>[
                                            array(
                                                "links"=>"manage/employe/conge",
                                                "title"=> "Congé"),
                                            array(
                                                "links"=>"manage/employe/pointage/dashboard",
                                                "title"=> "Pointage"),
                                            array(
                                                "links"=>"manage/employe/suivi/dashboard",
                                                "title"=> "Suivi")
                                        ]
                                    ] ,
                                    'ÉVALUATION' => [
                                        array(
                                            "links"=>"manage/employe/evaluation_valider",
                                            "title"=> 'Évaluation à valider'
                                        ),
                                        array(
                                            "links"=>"manage/employe/evaluation",
                                            "title"=> 'Mes évaluations'
                                        ),
                                        array(
                                            "links"=>"manage/employe/evaluation/archive",
                                            "title"=> "Mes archives"
                                        )        ] ,
                                    'BAROMETRE' => [
                                        array(
                                            "links"=>"manage/employe/barometre?reply=NO",
                                            "title"=> "Mes baromètres"
                                        )
                                    ]   
                                )
                            );
                            var_dump(serialize($res)); 
                            var_dump($res); 
                            $manager->ajouter([
                            'id_entreprise' => 21,
                            'libelle' => "Grand complet les menus",
                            'containt' => serialize($res)
                            ]);
                            exit();
                        */
                        /*
                            // $menus      = array(
                            //     'entreprise' =>array(
                            //         'RECRUTEMENT' =>[
                            //             "Entretiens"=>[
                            //                 array(
                            //                     "links"=>"manage/entreprise/dashboard",
                            //                     "title"=> "Interlocuteurs"),
                            //                 array(
                            //                     "links"=>"manage/entreprise/dashboard",
                            //                     "title"=> "Liste"),
                            //                 array(
                            //                     "links"=>"manage/entreprise/dashboard",
                            //                     "title"=> "Niveaux")
                            //             ],
                            //             "Mes candidats"=>[
                            //                 array(
                            //                     "links"=>"manage/entreprise/dashboard",
                            //                     "title"=> "Candidatures")
                            //             ],
                            //             "Offres" =>[
                            //                 array(
                            //                     "links"=>"manage/entreprise/dashboard",
                            //                     "title"=> "Liste"),
                            //                 array(
                            //                     "links"=>"manage/entreprise/dashboard",
                            //                     "title"=> "Tableau de bord")
                            //             ],
                            //             "Tests"=>[
                            //                 array(
                            //                     "links"=>"manage/entreprise/dashboard",
                            //                     "title"=> "Mes tests"),
                            //                 array(
                            //                     "links"=>"manage/entreprise/dashboard",
                            //                     "title"=> "Résultat")
                            //             ]
                            //         ],
                            //         'GESTION'=>[
                            //             "Capital humain"=>[
                            //                 array(
                            //                     "links"=>"manage/entreprise/contrat",
                            //                     "title"=> "Contrat"),
                            //                 array(
                            //                     "links"=>"manage/employes",
                            //                     "title"=> "Employés"),
                            //                 array(
                            //                     "links"=>"manage/entreprise_postes",
                            //                     "title"=> "Poste"),
                            //                 array(
                            //                     "links"=>"manage/entreprise_services",
                            //                     "title"=> "Service"),
                            //                 "Formations"=>[
                            //                     array(
                            //                         "links"=>"manage/entreprise/dashboard",
                            //                         "title"=> "Catalogues de formations"),
                            //                     array(
                            //                         "links"=>"manage/entreprise/dashboard",
                            //                         "title"=> "Catalogues de formateurs")
                            //                 ]
                            //             ],
                            //             "Temps & Absence"=>[
                            //                 "Gestion de pointage"=>[
                            //                     array(
                            //                         "links"=>"manage/entreprise/dashboard",
                            //                         "title"=> "Pointages"),
                            //                     array(
                            //                         "links"=>"manage/entreprise/dashboard",
                            //                         "title"=> "Tâches réalisées")
                            //                 ],
                            //                 "Gestion d'absence" =>[
                            //                     array(
                            //                         "links"=>"manage/entreprise/dashboard",
                            //                         "title"=> "Permission"),
                            //                     "Congé"=>[
                            //                         array(
                            //                             "links"=>"manage/entreprise/dashboard",
                            //                             "title"=> "Intérimaire"),
                            //                         array(
                            //                             "links"=>"manage/entreprise/conge",
                            //                             "title"=> "Gestion de congé"),
                            //                         array(
                            //                             "links"=>"manage/entreprise/planning",
                            //                             "title"=> "Planning de congés")
                            //                     ]
                            //                 ]
                            //             ],
                            //             "Paie & Prestations sociales"=>[
                            //                 array(
                            //                     "links"=>"manage/entreprise/dashboard",
                            //                     "title"=> "Avances"),
                            //                 array(
                            //                     "links"=>"manage/entreprise/dashboard",
                            //                     "title"=> "Paie")
                            //             ]
                            //         ] ,
                            //         'ÉVALUATION' => [
                            //             array(
                            //                 "links"=>"manage/entreprise/dashboard",
                            //                 "title"=> 'Mes évaluations'
                            //             ),
                            //             array(
                            //                 "links"=>"manage/entreprise/dashboard",
                            //                 "title"=> 'Créer une évaluation'
                            //             ),
                            //             array(
                            //                 "links"=>"manage/entreprise/dashboard",
                            //                 "title"=> "Gérer mes formulaires d'évaluations"
                            //             )
                            //         ] ,
                            //         'BAROMETRE' => [
                            //             array(
                            //                 "links"=>"manage/barometre_list",
                            //                 "title"=> "Mes baromètres"
                            //             )
                            //         ]
                            //     ),
                            //         'employe' => array(
                            //             'GESTION'=>[
                            //                 "Paie & Prestations sociales"=>[
                            //                     array(
                            //                         "links"=>"manage/employe/demandeAvance",
                            //                         "title"=> "Avances"),
                            //                     array(
                            //                         "links"=>"manage/employe/detailFichePaie",
                            //                         "title"=> "Salaire")
                            //                 ],
                            //                 "Capital humain"=>[
                            //                     array(
                            //                         "links"=>"manage/show-monContrat",
                            //                         "title"=> "Contrat"),
                            //                     array(
                            //                         "links"=>"manage/employe/formationDisponible",
                            //                         "title"=> "Formation")
                            //                 ],
                            //                 "Temps et Absence"=>[
                            //                     array(
                            //                         "links"=>"manage/employe/conge",
                            //                         "title"=> "Congé"),
                            //                     array(
                            //                         "links"=>"manage/employe/pointage/dashboard",
                            //                         "title"=> "Pointage"),
                            //                     array(
                            //                         "links"=>"manage/employe/suivi/dashboard",
                            //                         "title"=> "Suivi")
                            //                 ]
                            //             ] ,
                            //             'ÉVALUATION' => [
                            //                 array(
                            //                     "links"=>"manage/employe/evaluation_valider",
                            //                     "title"=> 'Évaluation à valider'
                            //                 ),
                            //                 array(
                            //                     "links"=>"manage/employe/evaluation",
                            //                     "title"=> 'Mes évaluations'
                            //                 ),
                            //                 array(
                            //                     "links"=>"manage/employe/evaluation/archive",
                            //                     "title"=> "Mes archives"
                            //                 )        ] ,
                            //             'BAROMETRE' => [
                            //                 array(
                            //                     "links"=>"manage/employe/barometre?reply=NO",
                            //                     "title"=> "Mes baromètres"
                            //                 )
                            //             ]
                            //         )
                            //     );
                        */



                    /* 2023-05-19 Desactiver pour le moment durant le test gratuit pendant 3 mois d'essaie

                        $menus      = array(
                            'entreprise' =>array(
                                'RECRUTEMENT' =>[
                                    "Entretiens"=>[
                                        array(
                                            "links"=>"/#/interlocuteurs",
                                            "title"=> "Interlocuteurs"),
                                        array(
                                            "links"=>"/#/entretiens",
                                            "title"=> "Liste"),
                                        array(
                                            "links"=>"/#/niveaux_entretiens",
                                            "title"=> "Niveaux")
                                    ],
                                    "Mes candidats"=>[
                                        array(
                                            "links"=>"/#/candidatures_entreprise",
                                            "title"=> "Candidatures")
                                    ],
                                    "Offres" =>[
                                        array(
                                            "links"=>"/#/offres",
                                            "title"=> "Liste"),
                                        array(
                                            "links"=>"/#/tableau_de_bord-offre",
                                            "title"=> "Tableau de bord")
                                    ],
                                    "Tests"=>[
                                        array(
                                            "links"=>"/#/tests",
                                            "title"=> "Mes tests"),
                                        array(
                                            "links"=>"/#/resultats",
                                            "title"=> "Résultat")
                                    ]
                                ],
                                'GESTION'=>[
                                    "Capital humain"=>[
                                        array(
                                            "links"=>"manage/entreprise/contrat",
                                            "title"=> "Contrat"),
                                        array(
                                            "links"=>"manage/employes",
                                            "title"=> "Employés"),
                                        array(
                                            "links"=>"manage/entreprise_postes",
                                            "title"=> "Poste"),
                                        array(
                                            "links"=>"manage/entreprise_services",
                                            "title"=> "Service"),
                                        "Formations"=>[
                                            array(
                                                "links"=>"/#/entreprise/formation",
                                                "title"=> "Catalogues de formations"),
                                            array(
                                                "links"=>"/#/entreprise/formateur",
                                                "title"=> "Catalogues de formateurs")
                                        ]
                                    ],
                                    "Temps & Absence"=>[
                                        "Gestion de pointage"=>[
                                            array(
                                                "links"=>"manage/entreprise/dashboard",
                                                "title"=> "Pointages"),
                                            array(
                                                "links"=>"manage/entreprise/dashboard",
                                                "title"=> "Tâches réalisées")
                                        ],
                                        "Gestion d'absence" =>[
                                            array(
                                                "links"=>"manage/entreprise/dashboard",
                                                "title"=> "Permission"),
                                            "Congé"=>[
                                                array(
                                                    "links"=>"manage/entreprise/dashboard",
                                                    "title"=> "Intérimaire"),
                                                array(
                                                    "links"=>"manage/entreprise/conge",
                                                    "title"=> "Gestion de congé"),
                                                array(
                                                    "links"=>"manage/entreprise/planning",
                                                    "title"=> "Planning de congés")
                                            ]
                                        ]
                                    ],
                                    "Paie & Prestations sociales"=>[
                                        array(
                                            "links"=>"/#/entreprise/avance",
                                            "title"=> "Avances"),
                                        array(
                                            "links"=>"/#/entreprise/paie",
                                            "title"=> "Paie")
                                    ]
                                ] ,
                                'ÉVALUATION' => [
                                    array(
                                        "links"=>"/#/entreprise/evaluation",
                                        "title"=> 'Mes évaluations'
                                    ),
                                    array(
                                        "links"=>"/#/entreprise/evaluation_modele",
                                        "title"=> 'Créer une évaluation'
                                    ),
                                    array(
                                        "links"=>"/#/entreprise/evaluation/categorie",
                                        "title"=> "Gérer mes formulaires d'évaluations"
                                    )
                                ] ,
                                'BAROMETRE' => [
                                    array(
                                        "links"=>"/#/barometre_list",
                                        "title"=> "Mes baromètres"
                                    )
                                ]
                            ),
                                'employe' => array(
                                    'GESTION'=>[
                                        "Paie & Prestations sociales"=>[
                                            array(
                                                "links"=>"/#/employe/demandeAvance",
                                                "title"=> "Avances"),
                                            array(
                                                "links"=>"/#/employe/detailFichePaie",
                                                "title"=> "Salaire")
                                        ],
                                        "Capital humain"=>[
                                            array(
                                                "links"=>"manage/show-monContrat",
                                                "title"=> "Contrat"),
                                            array(
                                                "links"=>"/#/employe/formationDisponible",
                                                "title"=> "Formation")
                                        ],
                                        "Temps et Absence"=>[
                                            array(
                                                "links"=>"manage/employe/conge",
                                                "title"=> "Congé"),
                                            array(
                                                "links"=>"manage/employe/pointage/dashboard",
                                                "title"=> "Pointage"),
                                            array(
                                                "links"=>"manage/employe/suivi/dashboard",
                                                "title"=> "Suivi")
                                        ]
                                    ] ,
                                    'ÉVALUATION' => [
                                        array(
                                            "links"=>"/#/employe/evaluation_valider",
                                            "title"=> 'Évaluation à valider'
                                        ),
                                        array(
                                            "links"=>"/#/employe/evaluation",
                                            "title"=> 'Mes évaluations'
                                        ),
                                        array(
                                            "links"=>"/#/employe/evaluation/archive",
                                            "title"=> "Mes archives"
                                        )        ] ,
                                    'BAROMETRE' => [
                                        array(
                                            "links"=>"/#/employe/barometre?reply=NO",
                                            "title"=> "Mes baromètres"
                                        )
                                    ]
                                )
                            );
                    */
                        $menus          = array(
                            'entreprise'    => array(
                                'RECRUTEMENT' =>[
                                    // "Entretiens"=>[
                                    //     array(
                                    //         "links"=>"manage/interlocuteurs",
                                    //         "title"=> "Interlocuteurs"),
                                    //     array(
                                    //         "links"=>"manage/entretiens",
                                    //         "title"=> "Liste"),
                                    //     array(
                                    //         "links"=>"manage/niveaux_entretiens",
                                    //         "title"=> "Niveaux")
                                    // ],
                                    "Mes candidats"=>[
                                        array(
                                            "links"=>"manage/candidatures_entreprise",
                                            "title"=> "Candidatures"),
                                        array(
                                            "links"=>"manage/suiviCandidature",
                                            "title"=> "Suivi candidature")
                                    ],
                                    "Offres" =>[
                                        array(
                                            "links"=>"manage/offres",
                                            "title"=> "Liste"),
                                        array(
                                            "links"=>"manage/tableau_de_bord-offre",
                                            "title"=> "Tableau de bord")
                                    ],
                                    "Tests"=>[
                                        array(
                                            "links"=>"/#/tests",
                                            "title"=> "Mes tests"),
                                        array(
                                            "links"=>"/#/resultats",
                                            "title"=> "Résultat")
                                    ]
                                ],
                                'GESTION'=>[
                                    "Capital humain"=>[
                                        array(
                                            "links"=>"manage/entreprise/contrat",
                                            "title"=> "Contrat"),
                                        array(
                                            "links"=>"manage/employes",
                                            "title"=> "Employés"),
                                        array(
                                            "links"=>"manage/entreprise_postes",
                                            "title"=> "Poste"),
                                        array(
                                            "links"=>"manage/entreprise_services",
                                            "title"=> "Service"),
                                        "Formations"=>[
                                            array(
                                                "links"=>"manage/entreprise/formation",
                                                "title"=> "Catalogues de formations"),
                                            array(
                                                "links"=>"manage/entreprise/formateur",
                                                "title"=> "Catalogues de formateurs")
                                        ]
                                    ],
                                    "Temps & Absence"=>[
                                        "Gestion de pointage"=>[
                                            array(
                                                "links"=>"manage/entreprise/pointage/dashboard",
                                                "title"=> "Pointages"),
                                            array(
                                                "links"=>"manage/shift/dashboard",
                                                "title"=> "Shift"),
                                            array(
                                                "links"=>"manage/entreprise/tacheRealisee",
                                                "title"=> "Tâches réalisées")
                                        ],
                                        "Gestion d'absence" =>[
                                            array(
                                                "links"=>"manage/entreprise/permission/dashboard",
                                                "title"=> "Permission"),
                                            "Congé"=>[
                                                array(
                                                    "links"=>"manage/entreprise/interim",
                                                    "title"=> "Intérimaire"),
                                                array(
                                                    "links"=>"manage/entreprise/conge",
                                                    "title"=> "Gestion de congé"),
                                                array(
                                                    "links"=>"manage/entreprise/planning",
                                                    "title"=> "Planning de congés")
                                            ]
                                        ]
                                    ],
                                    "Paie & Prestations sociales"=>[
                                        array(
                                            "links"=>"/#/entreprise/avance",
                                            "title"=> "Avances"),
                                        array(
                                            "links"=>"/#/entreprise/paie",
                                            "title"=> "Paie")
                                    ]
                                ] ,
                                'ÉVALUATION' => [
                                    array(
                                        "links"=>"/#/entreprise/evaluation",
                                        "title"=> 'Mes évaluations'
                                    ),
                                    array(
                                        "links"=>"/#/entreprise/evaluation_modele",
                                        "title"=> 'Créer une évaluation'
                                    ),
                                    array(
                                        "links"=>"/#/entreprise/evaluation/categorie",
                                        "title"=> "Gérer mes formulaires d'évaluations"
                                    )
                                ] ,
                                'BAROMETRE' => [
                                    array(
                                        "links"=>"manage/barometre_list",
                                        "title"=> "Mes baromètres"
                                    )
                                ]
                            ),
                            'employe'       => array(
                                'GESTION'=>[
                                    "Paie & Prestations sociales"=>[
                                        array(
                                            "links"=>"/#/employe/demandeAvance",
                                            "title"=> "Avances"),
                                        array(
                                            "links"=>"/#/employe/detailFichePaie",
                                            "title"=> "Salaire")
                                    ],
                                    "Capital humain"=>[
                                        array(
                                            "links"=>"manage/show-monContrat",
                                            "title"=> "Contrat"),
                                        array(
                                            "links"=>"manage/employe/formationDisponible",
                                            "title"=> "Formation")
                                    ],
                                    "Temps et Absence"=>[
                                        array(
                                            "links"=>"manage/employe/conge",
                                            "title"=> "Congé"),
                                        array(
                                            "links"=>"manage/employe/pointage/dashboard",
                                            "title"=> "Pointage"),
                                        array(
                                            "links"=>"manage/employe/suivi/dashboard",
                                            "title"=> "Suivi"),
                                        array(
                                            "links"=>"manage/shift/dashboard",
                                            "title"=> "Planning")
                                    ]
                                ] ,
                                'ÉVALUATION' => [
                                    array(
                                        "links"=>"/#/employe/evaluation_valider",
                                        "title"=> 'Évaluation à valider'
                                    ),
                                    array(
                                        "links"=>"/#/employe/evaluation",
                                        "title"=> 'Mes évaluations'
                                    ),
                                    array(
                                        "links"=>"/#/employe/evaluation/archive",
                                        "title"=> "Mes archives"
                                    )        ] ,
                                'BAROMETRE' => [
                                    array(
                                        "links"=>"manage/employe/barometre?reply=NO",
                                        "title"=> "Mes baromètres"
                                    )
                                ]
                            )
                        );
                        $manager        = new ManagerMenuEntreprise();
                        $entrepriseMenu = $manager->ajouter([
                            'libelle'       => $entreprise->getNom(),
                            'containt'      => serialize($menus),
                            'id_entreprise' => $entreprise->getIdEntreprise()
                        ]);
                        /** @changelog 29/11/2022 [EVOL] (Lansky) Ajouter un paramètre  du pointage par defaut lors création du compte */
                        $manager = new ManagerParametrePointage();
                        $pointageParam = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                        if (!$pointageParam) {
                            $manager->ajouter([
                                'arretActive'   => 0,
                                'heureArret'    => '18:00:00',
                                'idEntreprise'  => $entreprise->getIdEntreprise(),
                                'heure_debut'   => '08:00:00',
                                // 'list_ip'       =>,
                                'is_fixed_time' => 'YES'
                            ]);
                        }
                    }
                }
                return $entreprise;            
            }
        }

        /** 
         * Récupérer le compte qu'on vient d'insérer
         *
         * @param array $parameters Les données à ajouter 
         *
         * @return object
         */
        private function getCompte()
        {
            $compte = "";
            if (array_key_exists('nomCreateur', $_SESSION['variable'])) {
                if (isset($_SESSION['variable']['nomCreateur'])) {
                    if (!is_null($_SESSION['variable']['nomCreateur'])) {
                        $tmpSession = array(
                            'contactRapide' => $_SESSION['variable']['contactRapide'],
                            'mailCreateur'  => $_SESSION['variable']['mailCreateur'],
                            'nomCreateur'   => $_SESSION['variable']['nomCreateur']
                        );
                    }
                }
                unset($_SESSION['variable']['nomCreateur']);
                unset($_SESSION['variable']['mailCreateur']);
                unset($_SESSION['variable']['contactRapide']);
            }
            if (isset($_SESSION['variable'])) {
                $dataCompte = $_SESSION['variable'];
                if (!empty($dataCompte)) {
                    $dataCompte['motDePasse'] = md5($dataCompte['motDePasse']);
                    $manager                  = new ManagerCompte();
                    $compte                   = $manager->creerCompte($dataCompte);
                }
                if (isset($tmpSession)) {
                    $_SESSION['variable'] = array_merge($_SESSION['variable'], $tmpSession);
                }
            }
            return $compte;            
        }

        /** 
         * Envoyer le message sur le formulaire de contact
         *
         * @param array $parameters Les données à envoyé 
         *
         * @return empty
         */
        public function sendMessage($parameters)
        {
            $to      = "";
            $subject = "Message venant du visiteur de site";
            $message = "visiteur : " . strtoupper($parameters['nom']) . " " . ucwords($parameters['prenom']) . "\n\n Téléphone : " . $parameters['phone'] . "\n\n Email : " . $parameters['email'] . "\n\n" . ucfirst($parameters['message']);
            $headers = "From: " . $parameters['email'];
            $manager = new ManagerEmailContact();
            $emails  = $manager->lister();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    if ($email->getType() == 'contact') {
                        $to = $email->getEmail();
                        mail($to, $subject, $message, $headers);
                    }
                }
                $_SESSION['info']['success'] = "Message envoyé";
            } else {
                $_SESSION['info']['danger'] = "Message non envoyé";
            }
        }

        /** 
         * Envoyer un email à un utilisateur
         *
         * @param string $to l'email destinataire
         * @param string $subject le sujet de l'email
         * @param string $message le message à envoyer 
         * @param string $headers ce qu'on va préciser l'émetteur 
         *
         * @return empty
         */
        public function sendEmail($to, $subject, $message)
        {
            $manager = new ManagerEmailContact();
            $emails  = $manager->lister();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    if ($email->getType() == 'information') {
                        $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                        mail($to, $subject, $message, $headers);
                    }
                }
            } 
        }

        /** 
         * Envoyer un email au application suite à une création d'un compte entreprise
         *
         * @param string $entreprise l'entreprise qui vient de s'inscrire
         *
         * @return empty
         */
        public function notifierApp($entreprise)
        {
            $manager = new ManagerEmailContact();
            $emails  = $manager->lister();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    if ($email->getType() == 'information') {
                        $headers = "Content-type: text/html" . "\r\n" . "From: " . $entreprise->getEmail();
                        $subject = "Information sur l'inscription";
                        $message = "<html><body>
                                        <div class='container'>
                                            <label>Bonjour !</label><br><br>
                                            <label>L'entreprise " . $entreprise->getNom() . " vient de s'inscrire sur votre site.</label><br><br>
                                            <label>
                                                Les informations de la personne qui a fait l'inscription sont :
                                            </label>
                                            <br>
                                            <label>
                                                Nom : " . $_SESSION['variable']['nomCreateur'] . "
                                            </label>
                                            <br>
                                            <label>
                                                E-mail : " . $_SESSION['variable']['mailCreateur'] . "
                                            </label>
                                            <br>
                                            <label>
                                                Contact rapide : " . $_SESSION['variable']['contactRapide'] . "
                                            </label>
                                            <br><br>
                                            <label>
                                                <strong><u>Les informations concernants de l'entreprise</u></strong> :
                                            </label>
                                            <br>
                                            &emsp;<label>NIF : " . $entreprise->getNif() . "</label> <br>
                                            &emsp;<label>STAT : " . $entreprise->getStat() . "</label> <br>
                                            &emsp;<label>Contact du responsable :" . $entreprise->getContact() . "</label> <br>
                                            &emsp;<label>Contact RH : " . $entreprise->getContactRh() . "</label><br><br>
                                        </div>
                                    </body></html>";
                        mail($email->getEmail(), $subject, $message, $headers);
                    }
                }
            } 
        }
    }