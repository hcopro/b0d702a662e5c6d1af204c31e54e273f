<?php

    /**
     * Manager du module Congé du Backend
     *
     * @author Toky
     *
     * @since 19/08/2020
    */

    use \Core\DbManager;
    use \Core\View;
    use \Core\PublicFonctions;

    use \Model\ManagerCompte;
    use \Model\ManagerJourFerie;
    use \Model\ManagerEntrepriseFerie;
    use \Model\ManagerEmploye;
    use \Model\ManagerPresence;
    use \Model\ManagerPointage;
    use \Model\ManagerEntreprise;
    use \Model\ManagerServicePoste;
    use \Model\ManagerEntrepriseService;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerContratEmploye;
    use \Model\ManagerTypePermission;
    use \Model\ManagerEntreprisePermission;
    use \Model\ManagerEmployePermission;
    use \Model\ManagerEmployeRepos;
    use \Model\ManagerParametrePermission;
    use \Model\ManagerMessage;
    use \Model\ManagerConge;
    use \Model\ManagerStockConge;
    use \Model\ManagerEmailContact;
    use \Model\ManagerValidationConge;
    use \Model\ManagerTacheAutomatique;
    use \Model\ManagerParametreConge;
    use \Model\ManagerContrat;
    use \Model\ManagerFormationProfessionnelle;
    use \Model\ManagerEmployeFormation;

    class ManagerModuleConge extends DbManager
    {
        const LEAVE_PROPOSED            = 1;
        const LEAVE_REJECTED            = 0;
        const LEAVE_VALIDATED           = 2;
        const LEAVE_ABOLISHED           = 4;
        const LEAVE_ARCHIVED            = 0;
        const LEAVE_NOT_ARCHIVED        = 1;
        const LEAVE_CANCELED            = 5;
        const FILTER_LEAVE_ALL          = 4;
        const FILTER_LEAVE_PROPOSED     = 1;
        const FILTER_LEAVE_VALIDATED    = 2;
        const FILTER_LEAVE_ARCHIVED     = 3;
        const FILTER_LEAVE_REJECTED     = 0;
        const FILTER_GROUP_ALL          = 1;
        const FILTER_GROUP_SERVICE      = 2;
        const FILTER_GROUP_POSTE        = 3;
        const FILTER_GROUP_EMPLOYE      = 4;
        const PRESENT_YES               = 1;
        const PRESENT_NO                = 0;
        const MONDAY                    = 'Lundi';
        const TUESDAY                   = 'Mardi';
        const WEDNESDAY                 = 'Mercredi';
        const THURSDAY                  = 'Jeudi';
        const FRIDAY                    = 'Vendredi';
        const SATURDAY                  = 'Samedi';
        const SUNDAY                    = 'Dimanche';
        const CONTRACTED_MONDAY         = 'Lu';
        const CONTRACTED_TUESDAY        = 'Ma';
        const CONTRACTED_WEDNESDAY      = 'Me';
        const CONTRACTED_THURSDAY       = 'Je';
        const CONTRACTED_FRIDAY         = 'Ve';
        const CONTRACTED_SATURDAY       = 'Sa';
        const CONTRACTED_SUNDAY         = 'Di';
        const EMPTY                     = 0;
        const PROPOSED                  = 1;
        const VALIDATED                 = 2;
        const EXPIRED                   = 3;
        const ABOLISHED                 = 4;
        const CANCELED                  = 5;
        const REFUSED                   = 0;
        const NO                        = 0;
        const YES                       = 1;
        const COMPTE_ENTREPRISE         = 'entreprise';
        const COMPTE_EMPLOYE            = 'employe';
        const TYPE_REQUEST              = "demande";
        // const TYPE_PASS_REQUEST         = "passe validation";
        const TYPE_VALIDATED            = "valide";
        const TYPE_REJECTED             = "rejete";
        const TYPE_CANCELED             = "annule";
        const TYPE_ABOLISHED            = "aboli";
        const TYPE_INFORMATION          = "information";
        const TYPE_SETTLED              = "regleConge";
        const SECOND_TO_DAY             = 86400;
        const ONE_DAY                   = 1;
        const SOURCE                    = "/usr/bin/php " . CRON_DIR;
        const ATTENTE_ACTIVE            = 1;
        const ATTENTE_DESACTIVE         = 0;
        const DIRECT_VALIDATION         = 0;
        const ALL_VALIDATION            = 1;
        const DEFINED_VALIDATION        = 2;
        const ANNEE_ATTENTE             = 1;
        const CALCUL_PAR_MOIS           = 0;
        const CALCUL_AU_PRORATA         = 1;
        const CALCUL_PAR_AN             = 2;
        const STAGE                     = "STAGE";
        const CDI                       = "CDI";
        const CDE                       = "CDE";
        const CA                        = "CA";
        const CDD                       = "CDD";
        const MATIN                     = 1;
        const APRES_MIDI                = 2;
        const SOIR                      = 3;
        const DEMI_JOURNEE              = 0.5;
        const POSTE_INTERNE             = 0;
        const SEEN                      = 2;
        const WORK_HOUR                 = 8;
        const STOCK_MENSUEL             = 2.5;

        /**
         * Voir le planning de congé
         *
         * @param array $parameters     Les critères des données à afficher
         *
         * @return array
        */
        public function voirPlanning($parameters)
        {
           $manager       = new ManagerEntreprise();
           $entreprise    = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
           $factory       = new PublicFonctions();
           $filtres       = $factory->getFiltre($entreprise->getIdEntreprise());
           $date['now']   = date('Y-m');
           $date['prev']  = date('Y-m', strtotime('first day of last month'));
           $date['next']  = date('Y-m', strtotime('first day of next month'));
           $mois['now']   = $this->getMonthLetter(date('m')) . ' ' . date('Y') ;
           $mois['prev']  = $this->getMonthLetter(date('m', strtotime('first day of last month'))) . ' ' . date('Y') ;
           $mois['next']  = $this->getMonthLetter(date('m', strtotime('first day of next month'))) . ' ' . date('Y') ;
           return [
                'entreprise' => $entreprise,
                'filtres'    => $filtres,
                'date'       => $date,
                'mois'       => $mois
           ];
        }

        /**
         * Voir le tableau de bord de congé via compte employé
         * 
         * @param array $parameters     Les critères des données à afficher
         *
         * @return array
        */
        public function voirCongeEmploye($parameters)
        {
            $_SESSION['filters'] = ['annee' => '', 'type' => self::FILTER_LEAVE_ALL];
            $manager   = new ManagerEmploye();
            $employe   = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
            $manager   = new ManagerStockConge();
            $stock     = $manager->chercher([
                'idEmploye' => $employe,
                'annee'     => date('Y')
            ]);
            return [
                'employe'  => $employe,
                'stock'    => $stock
            ];
        }

        /**
         * Voir le tableau de bord de congé des collaborateurs via son compte employé
         *
         * @param array $parameters     Les critères des données à afficher
         *
         * @return array
        */
         public function voirCongeCollaborateur($parameters)
        {
            return $this->generateFilter($parameters);
        }

        /**
         * Générer la filtre
         *
         * @param array $parameters     Les critères des données à filtrer
         *
         * @return array
        */
        private function generateFilter($parameters)
        {
            $manager    = new ManagerEntreprise();
            $factory    = new PublicFonctions();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            return [
                'entreprise'  => $entreprise,
                'filtres'     => $factory->getFiltre($entreprise->getIdEntreprise())
            ];
        }

        /**
         * Voir le tableau de bord de congé via compte entreprise
         * 
         * @param array $parameters     Les critères des données à afficher
         *
         * @return array
        */
        public function voirCongeEntreprise($parameters)
        {
            $_SESSION['filters'] = ['annee' => date('Y'), 'type' => self::FILTER_LEAVE_ALL];
            return $this->generateFilter($parameters);
        }

        /**
         * Voir les paramètres concernant les congés d'une entreprise
         *
         * @param array $parameters     Les critères des données à afficher
         * 
         * @return array
        */
        public function voirParametreConge($parameters)
        {
            $manager     = new ManagerEntreprise();
            $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager     = new ManagerParametreConge();
            $parametre   = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
            if ($parametre == null) {
                $parametre = $manager->ajouter([
                    'idEntreprise'   => $entreprise->getIdEntreprise(),
                    'attente'        => self::ATTENTE_ACTIVE,
                    'processus'      => self::ALL_VALIDATION,
                    'niveau'         => self::NO
                ]);
            }
            return [
                'entreprise'  => $entreprise,
                'parametre'   => $parametre
            ];
        }

        /**
         * Voir les demandes de validation de congé d'un employé(N+1)
         *
         * @param array $parameters     Les critères des données à afficher
         *
         * @return array
        */
        public function voirValidationEmploye($parameters)
        {
            $manager    = new ManagerEmploye();
            $employe    = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
            if (!isset($_SESSION['filters'])) {
                $_SESSION['filters'] = ['annee' => '', 'type' => self::FILTER_LEAVE_ALL];
            }
           return [
                'employe'  => $employe,
           ];
        }

        /**
         * Récupérer les données du cogé d'une employé
         *
         * @param array $parameters     Les critères des données à afficher
         * @param object $employe       L'employé concerné
         *
         * @return array
        */
        private function getDataConge($parameters, $employe)
        {
            if ($parameters['annee'] != null) {
                $debutAnnee  = date($parameters['annee'] . '-01-01');
                $finAnnee    = date($parameters['annee'] . '-12-31');
            }
            $manager     = new ManagerConge();
            $demandes    = array();
            if ($parameters['type'] == self::FILTER_LEAVE_ALL) {
                if ($parameters['annee'] != null) {
                    $demandes    = $manager->selectionner(
                        ['idEmploye' => $employe->getIdEmploye(),
                        'etat'       => self::LEAVE_NOT_ARCHIVED],
                        ['debut'     => $debutAnnee],
                        ['debut'     => $finAnnee]
                    );
                } else {
                    $demandes    = $manager->lister([
                        'idEmploye' => $employe->getIdEmploye(),
                        'etat'      => self::LEAVE_NOT_ARCHIVED
                    ]);
                }
            } elseif ($parameters['type'] == self::FILTER_LEAVE_ARCHIVED) {
                if ($parameters['annee'] != null) {
                    $demandes    = $manager->selectionner(
                        ['idEmploye' => $employe->getIdEmploye(),
                        'etat'       => self::LEAVE_ARCHIVED],
                        ['debut'     => $debutAnnee],
                        ['debut'     => $finAnnee]
                    );
                } else {
                    $demandes    = $manager->lister([
                        'idEmploye' => $employe->getIdEmploye(),
                        'etat'      => self::LEAVE_ARCHIVED
                    ]);
                }
            } else {
                if ($parameters['annee'] != null) {
                    $demandes    = $manager->selectionner(
                        ['idEmploye' => $employe->getIdEmploye(),
                        'statut'     => $parameters['type']],
                        ['debut'     => $debutAnnee],
                        ['debut'     => $finAnnee]
                    );
                } else {
                    $demandes    = $manager->lister([
                        'idEmploye' => $employe->getIdEmploye(),
                        'statut'    => $parameters['type']
                    ]);
                }
            }
            $validations = array();
            foreach ($demandes as $demande) {
                $validations[$demande->getIdConge()] = $this->getSuiviDemande($demande->getIdConge());
            }
            return [
                'demandes'    => $demandes,
                'validations' => $validations
            ];
        }

        /**
         * Voir le tableau de bord de l'historique de congé via compte employé
         * 
         * @param array $parameters     Les critères des données à afficher
         *
         * @return array
        */
        public function voirHistoriqueConge($parameters)
        {
            $idEmploye = 0;
            if (isset($parameters['idEmploye'])) {
                $idEmploye = $parameters['idEmploye'];
                if (count($parameters) == 1 && isset($parameters['idEmploye'])) {
                    $parameters = NULL;
                }
            }
            $manager    = new ManagerEmploye();
            $employe    = $manager->chercher(['idEmploye' => $idEmploye > 0 ? $idEmploye : $_SESSION['user']['idEmploye']]);
            if (is_null($parameters)) {
                $responses              = $this->getHistoricCongeUser($employe, date('Y'), self::FILTER_LEAVE_ALL);
                $responses['employe']   = $employe;
                return $responses;
            } else {
                if (!empty($parameters)) {
                    if (empty($parameters['type'])) {
                        $parameters['type'] = self::FILTER_LEAVE_ALL;
                    }
                    $donnees = $this->getDataConge($parameters, $employe);
                    $stockYear = $this->getHistoricCongeUser($employe, $parameters['annee'], $parameters['type']);
                    $donnees['stock'] = $stockYear['stock'];
                    $donnees['conge'] = $stockYear['conge'];
                    $view = new View("voirHistoriqueConge");
                    $view->sendWithoutTemplate("Backend", "Conge", $donnees, "");
                    exit();
                }
            }
        }

        /**
         * Calculer la durée d'une congée
         *
         * @changelog 2022-04-19 [OPTIM] (Lansky) Récupérer la durée du congé en mi-temps
         * 
         * @param object $demande   La demande de congé à calculer
         *
         * @return float ou int
        */
        public static function calculateCongeDuring ($demande)
        {
            if ($demande->getDuring() > 0) {
                $heure = $demande->getDuring() / self::WORK_HOUR;
            } else {
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
            }
            return (abs(strtotime($demande->getFin()) - strtotime($demande->getDebut()))/ (60*60*24)) + $heure;
        }

        /**
         * Voir les demande de validation de congé d'une entreprise (validation finale)
         *
         * @param array $parameters     Les critères des données à afficher
         *
         * @return array
        */
        public function voirValidationEntreprise($parameters)
        {
           $manager     = new ManagerEntreprise();
           $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
           $manager     = new ManagerEmploye();
           $employes    = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
           return [
                'employes'    => $employes,
                'entreprise'  => $entreprise
           ];
        }

        /**
         * Mettre A jour une demande de congé
         *
         * @param array $parameters     Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourDemandeConge($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                $manager    = new ManagerParametreConge();
                // Récupérer les parametres du congé de l'entreprise
                $parametre  = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                // Donner la valeur vide à ceux qui n'en ont pas via $parameters
                if (!isset($parameters['fin'])) {
                    $parameters['fin'] = $parameters['debut'];
                }
                if (!isset($parameters['heureDebut']) && !isset($parameters['heureFin'])) {
                    $parameters['heureDebut']   = '';
                    $parameters['heureFin']     = '';
                }
                /**@changeLog 2023-08-28 [EVOL] (Lansky) Déduire le solde de conge en week-end */
                if ($parametre->getDeductWeekend() == self::YES) {
                    $jourSemaine = date('w', strtotime($parameters['fin']));
                    if ($jourSemaine == 5) { // Vérifiez si le jour de la fin de la demande de congé est égal à vendredi
                        $addDay = 2;
                    } elseif ($jourSemaine == 6) { // Vérifiez si le jour de la fin de la demande de congé est égal à samedi
                        $addDay = 1;
                    }
                    if ($jourSemaine > 4 && $parameters['heureFin'] == self::SOIR) {
                        $parameters['fin'] = date('Y-m-d', strtotime($parameters['fin'] . '+' . $addDay . ' days'));
                    }
                }
                if ($parametre->getAttente() == self::ATTENTE_DESACTIVE || $this->getAnciennete($employe->getIdEmploye())['annees'] >= self::ANNEE_ATTENTE) {
                    if (strtotime($parameters['debut']) >= strtotime(date("Y-m-d")) 
                        && strtotime($parameters['fin']) >= strtotime(date("Y-m-d"))
                    ) {
                        // Vérifier si l'utilisateur met la même date de debut du congé à celle de fin ainsi que l'heure de départ au congé est identique à l'heure de retour au poste. Ou l'utilisateur a chosi le mode de congé à mi-temps.
                        if ($parameters['debut'] != $parameters['fin']
                            || ($parameters['debut'] == $parameters['fin']
                                && ($parameters['heureDebut'] != $parameters['heureFin']
                                    || (isset($parameters['beginto']) && isset($parameters['during']))
                                )
                            )
                        ) {
                            // Vérifier si le salarié est en formation. La date de congé est identique à la date de formation.
                            if (!$this->estEnFormation($employe, $parameters['debut'], $parameters['fin'])) {
                                // Vérifier l'entreprise du salarié
                                if ($employe->getIdEmploye() == $_SESSION['user']['idEmploye']
                                    || ($_SESSION['compte']['identifiant'] == self::COMPTE_ENTREPRISE
                                        && $employe->getIdEntreprise() == $_SESSION['user']['idEntreprise']
                                    )
                                ) {
                                    // Récupérer le solde de congé du salarié de cette année
                                    $annee             = date('Y');
                                    $manager           = new ManagerStockConge();
                                    $stockConge        = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'annee' => $annee]);
                                    // Si le salarié n'a pas de solde et de lui créer
                                    if ($stockConge == null) {
                                        $manager        = new ManagerStockConge();
                                        $stockConge     = $manager->ajouter([
                                            "idEmploye" => $employe->getIdEmploye(),
                                            "duree"     => self::NO,
                                            "annee"     => $annee
                                        ]);
                                    }
                                    $dureeDisponible   = $stockConge->getDuree();
                                    // Récupérer la date maximum du congé que peut prendre par le salarié
                                    $maxFin            = date('Y-m-d', strtotime($parameters['debut'] . ' +' . (intval($dureeDisponible)) . ' DAY'));
                                    //  Tester si le salarié peut prendre la durée de congé qu'il souhaite
                                    if (strtotime($parameters['fin']) <= strtotime($maxFin)) {
                                        /**@changelog 2022-04-26 [OPT] (Lansky) Vérifier si le salarié a déjà faire la demande de même jour */
                                        $result = $this->verifyDateConge($parameters);
                                        if (count($result) == 0) {
                                            /**@changelog 2022-08-02 [OPT] (Lansky) Vérifier le jour debut de la demande du congé est un jour férié initié par l'entreprise */
                                            if (!$this->isFerie($parameters['debut'])) {
                                                /**@changelog 2022-08-02 [OPT] (Lansky) Vérifier le jour fin de la demande du congé est un jour férié initié par l'entreprise */
                                                if ($this->isFerie($parameters['fin'])) {
                                                    $parameters['fin'] = date('Y-m-d', strtotime($parameters['fin'] . ' - 1 days'));
                                                    // Tester si la date de fin n'est pas le jour ouvrable
                                                    if (date('w',strtotime($parameters['fin'])) == 0 || date('w',strtotime($parameters['fin'])) == 6) {
                                                        $parameters['fin'] = date('Y-m-d', strtotime($parameters['fin'] . ' ' . ((date('w',strtotime($parameters['fin'])) % 5) - 2) . ' days'));
                                                    }
                                                }
                                                // Assigner une valeur entier aux variables $parameters['beginto'] et $parameters['during']
                                                if (isset($parameters['beginto']) && isset($parameters['during'])) {
                                                    if ($parameters['beginto'] <= 12) {
                                                        $parameters['heureDebut'] = self::MATIN;
                                                    } elseif ($parameters['beginto'] < 18 && $parameters['beginto'] > 12) {
                                                        $parameters['heureDebut'] = self::APRES_MIDI;
                                                    }
                                                    $concat = $parameters['during'] + $parameters['beginto'];
                                                    if ($concat <= 12) {
                                                        $parameters['heureFin'] = self::MATIN;
                                                    } elseif ($concat < 18 && $concat > 12) {
                                                        $parameters['heureFin'] = self::APRES_MIDI;
                                                    } else {
                                                        $parameters['heureFin'] = self::SOIR;
                                                    }
                                                } else {
                                                    $parameters['beginto'] = 0;
                                                    $parameters['during']  = 0;
                                                }
                                                /**@changelog 2022-05-02 [OPT] (Lansky) Vérification la demande de congé (congé fin vendredi et debut lundi) => on ajout 2jours pour samedi et dimanche */
                                                $date = new DateTime($parameters['debut']);
                                                $manager        = new ManagerConge();
                                                if (lcfirst($date->format('l')) == 'monday' && $parameters['heureDebut'] == self::MATIN) {
                                                    $conge = $manager->chercher([
                                                        'idEmploye' => $parameters['idEmploye'],
                                                        'fin'       => date('Y-m-d', strtotime($parameters['debut'] . ' - 3 days')),
                                                        'heureFin'  => self::SOIR,
                                                        'beginto'   => self::EMPTY
                                                    ]);
                                                    if ($conge) {
                                                        if ($conge->getStatut() == self::LEAVE_PROPOSED || $conge->getStatut() == self::LEAVE_PROPOSED || $conge->getStatut() == 3) {
                                                            $parameters['debut'] = date('Y-m-d', strtotime($parameters['debut'] . ' - 2 days'));
                                                        }
                                                    }
                                                }
                                                // Récupérer pour deuxième fois la date maximum du congé que peut prendre par le salarié
                                                $maxFin = date('Y-m-d', strtotime($parameters['debut'] . ' +' . (intval($dureeDisponible)) . ' DAY'));
                                                //  Retester si le salarié peut prendre la durée de congé qu'il souhaite
                                                if (strtotime($parameters['fin']) <= strtotime($maxFin)) {
                                                    $demandeConge   = $manager->ajouter([
                                                        'idEmploye'     => $parameters['idEmploye'],
                                                        'debut'         => $parameters['debut'],
                                                        'fin'           => $parameters['fin'],
                                                        'statut'        => self::LEAVE_PROPOSED,
                                                        'etat'          => self::LEAVE_NOT_ARCHIVED,
                                                        'raison'        => $parameters['raison'],
                                                        'heureFin'      => $parameters['heureFin'],
                                                        'heureDebut'    => $parameters['heureDebut'],
                                                        'beginto'       => $parameters['beginto'], 
                                                        'during'        => $parameters['during']
                                                    ]);
                                                    if ($demandeConge->getIdConge() != self::NO) {
                                                        $manager    = new ManagerEmploye();
                                                        $demandeur  = $manager->chercher(['idEmploye' => $demandeConge->getIdEmploye()]);
                                                        $factory    = new PublicFonctions();
                                                        $chef       = $factory->getChief($employe, $demandeur);
                                                        $this->passerValidation($employe, $chef, $demandeConge);
                                                        $_SESSION['info']['success'] = "La demande a été envoyée avec succès !";
                                                    } else {
                                                        $_SESSION['info']['danger'] = "Erreur de la base !";
                                                    }
                                                } else {
                                                    $_SESSION['info']['danger'] = "Votre solde est insuffisant &#128524; !!!";
                                                }
                                            } else {
                                                $_SESSION['info']['danger'] = $parameters['debut'] . " est un jour férié &#x1F609; !!!";
                                            }
                                        } else {
                                            $_SESSION['info']['danger'] = "Echec &#128524; !!! Merci de vérifier vos jours de congé .";
                                        }
                                    } else {
                                        $_SESSION['info']['danger'] = "La durée demandée dépasse la durée permise !";
                                    }
                                }  else {
                                    $_SESSION['info']['danger'] = "Vous n'avez pas le droit de demander congé à cet salarié !";
                                }
                            } else {
                                if ($_SESSION['compte']['identifiant'] == self::COMPTE_ENTREPRISE) {
                                    $_SESSION['info']['danger'] = "Cet employé est/sera en formation pendant cette période !";
                                } elseif ($_SESSION['compte']['identifiant'] == self::COMPTE_EMPLOYE) {
                                    $_SESSION['info']['danger'] = "Vous êtes/serez en formation pendant cette période !";
                                }
                            }
                        } else {
                            $_SESSION['info']['danger'] = "Mêmes dates de début et de fin";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "La période sélectionnée est déjà passée !";
                    }
                } else {
                    if ($_SESSION['compte']['identifiant'] == self::COMPTE_ENTREPRISE) {
                        $_SESSION['info']['danger'] = "Cet employé n'a pas encore atteint les 12 mois de travail effectif !";
                    } elseif ($_SESSION['compte']['identifiant'] == self::COMPTE_EMPLOYE) {
                        $_SESSION['info']['danger'] = "Vous n'avez pas encore atteint les 12 mois de travail effectif !";
                    }
                }
            }
        }

        /**
         * Vérifier si un employé est en formation sur un intervalle de temps
         *
         * @param object $employe   L'employé en question
         * @param date   $debut     Le début de l'intervalle
         * @param date   $fin       La fin   de l'intervalle
         *
         * @return boolean
        */
        private function estEnFormation($employe, $debut, $fin)
        {
            $debutRecherche = date('Y-m-d', strtotime('2020-01-01'));
            $finRecherche = date('Y-m-d', strtotime('2020-12-31'));
            $manager = new ManagerFormationProfessionnelle();
            $allFormations = $manager->selectionner(
                null,
                ['debut' => $debutRecherche],
                ['debut' => $finRecherche]
            );
            $retour = false;
            $formations = array();
            foreach ($allFormations as $formation) {
                if ($this->isParticipant($employe->getIdEmploye(), $formation->getIdFormationProfessionnelle())) {
                    $currentDate = $formation->getDebut();
                    while (strtotime($currentDate) <= strtotime($formation->getFin())) {
                        if (strtotime($currentDate) >= strtotime($debut) && strtotime($currentDate) <= strtotime($fin)) {
                            $retour = true;
                        }
                        $currentDate  = date('Y-m-d', strtotime($currentDate . ' + 1 DAY'));
                    }
                }
            }
            return $retour;
        }

        /**
         * Vérifier si un employé participe à une formation
         * 
         * @param int idEmploye                     L'identifiant de l'employé
         * @param int idFormationProfessionnelle    L'identifiant de la formation
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
         * Mettre A jour un congé d'un employe
         *
         * @param array $parameters     Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourEmployeConge($parameters)
        {
            if (!empty($parameters)) {
                $dureeConge             = 0;
                $debut                  =  implode("-", array_reverse(explode('/', $parameters['date']), true)); // Renverser en ordre décroissante le contenue du tableau date pour avoir le format date SQL
                /**@changelog 2022-04-22 [OPT] (Lansky) Ajouter fonctionnement d'abscence reglé par congé en mi-temps */
                if (isset($parameters['fin'])) {
                    $beginto    = 0;
                    $during     = 0;
                    $heureDebut = self::MATIN;
                    $heureFin   = self::SOIR;
                } else {
                    $fin        = $debut;
                    if ($parameters['beginto'] <= 12) {
                        $heureDebut = self::MATIN;
                    } elseif ($parameters['beginto'] < 18 && $parameters['beginto'] > 12) {
                        $heureDebut = self::APRES_MIDI;
                    }
                    $concat = $parameters['during'] + $parameters['beginto'];
                    if ($concat <= 12) {
                        $heureFin = self::MATIN;
                    } elseif ($concat < 18 && $concat > 12) {
                        $heureFin = self::APRES_MIDI;
                    } else {
                        $heureFin = self::SOIR;
                    }
                }
                $parameters['duree']    = isset($parameters['fin']) ? abs(strtotime($debut) - strtotime(implode("-", array_reverse(explode('/', $parameters['fin']), true)))) / (60*60*24) + 1 : intval($parameters['during']) / self::WORK_HOUR; // Calculer la différance entre ces deux dates.
                $fin                    = date('Y-m-d', strtotime($debut . ' + ' . max((intval($parameters['duree']) - self::ONE_DAY), 0) . ' DAY')); // Affecter dans la variable la fin de la date du congé reglé (a été pris)
                $manager                = new ManagerConge();
                /**@changelog 2022-05-02 [OPT] (Lansky) Vérification la demande de congé (congé fin vendredi et debut lundi) => on ajout 2jours pour samedi et dimanche */
                $date                   = new DateTime($parameters['debut']);
                if (lcfirst($date->format('l')) == 'monday' && $heureDebut == self::MATIN) {
                    $conge = $manager->chercher([
                        'idEmploye' => $parameters['idEmploye'],
                        'fin'       => date('Y-m-d', strtotime($parameters['debut'] . ' - 3 days')),
                        'heureFin'  => self::SOIR
                    ]);
                    if ($conge) {
                        $parameters['debut'] = date('Y-m-d', strtotime($parameters['debut'] . ' - 2 days'));
                    }
                }
                $conge                  = $manager->ajouter([
                    'idEmploye' => $parameters['idEmploye'],
                    'statut'    => self::LEAVE_VALIDATED,
                    'debut'     => $debut,
                    'fin'       => $fin,
                    'beginto'   => $parameters['beginto'],
                    'during'    => $parameters['during'],
                    'etat'          => self::YES,
                    'heureDebut'    => $heureDebut,
                    'heureFin'      => $heureFin,
                    'raison'        => 'Absence pour la raison personnelle'
                ]);
                // On ajoute une demi-journée à soustraire
                /*if (intval($parameters['duree']) != $parameters['duree']) {
                    $dureeConge = self::DEMI_JOURNEE;
                }*/
                if ($conge != null) {
                    $manager = new ManagerEmploye();
                    $employe = $manager->chercher(['idEmploye' => $conge->getIdEmploye()]);
                    $manager = new ManagerStockConge();
                    $year    = date('Y');
                    $stock   = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'annee' => $year]);
                    if ($parameters['duree'] > 0) {
                        $dureeConge += $this->getJours($conge->getDebut(), $conge->getHeureDebut(), $conge->getFin(), $conge->getHeureFin(), $conge->getBeginto(), $conge->getDuring());
                    }
                    $manager->modifier([
                        'idStockConge' => $stock->getIdStockConge(),
                        'duree'        => $stock->getDuree() - $dureeConge
                    ]);
                    /**@changelog 2022-04-07 [OPT] (Lansky) Envoye notification reglé par le congé l'absence du salarié */
                    // Notifier l'administration d'entreprise
                    $manager     = new ManagerEntreprise();
                    $entreprise  = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                    $content     = $this->generateMessageContent(self::TYPE_SETTLED, $conge);
                    $contentMail = $this->generateMailContent(self::TYPE_SETTLED, $conge);
                    $idMessage   = $this->sendMessageNotification($entreprise->getIdCompte(), 'Reglé son absence par congé de la part de <span class="titre">' . $employe->getCivilite() . ' ' . $employe->getNom() . ' ' . $employe->getPrenom() . '</span>', $content);
                    $manager     = new ManagerMessage();
                    $manager->modifier([
                        'idMessage' => $idMessage,
                        'aFaire'    => self::NO
                    ]);
                    // Notifier le salarié
                    $subjectMail = "Reglé son absence par congé de la part de " . $employe->getNom() . ' ' . $employe->getPrenom();
                    $this->sendMailNotification($entreprise, $subjectMail, $contentMail, true);
                    $content     = $this->generateMessageContent(self::TYPE_SETTLED, $conge, true, true);
                    $contentMail = $this->generateMailContent(self::TYPE_SETTLED, $conge, true, true);
                    $this->sendMessageNotification($employe->getIdCompte(), "Reglé votre absence par congé validée", $content, true);
                    $mailContent = $this->generateMailContent(self::TYPE_SETTLED, $conge, false, true);
                    $this->sendMailNotification($employe, "Reglé votre absence par congé validée", $mailContent, true);
                    $tmpEmploye  = $employe;
                    $this->notifyAllSupperior($tmpEmploye, self::TYPE_SETTLED, $conge, $employe, true, false, false);
                    $_SESSION['info']['success'] = "Votre absence a été bien régle par congé";
                }  else {
                    $_SESSION['info']['danger'] = "Une erreur s'est produite !";
                }
            } else {
                $_SESSION['info']['danger'] = "Informations sont vides !";
            }
        }

        /**
         * Voir les congés disponibles pour un employé
         *
         * @param array $parameters Les données à vérifier
         *
         * @return empty
        */
        public function verifierCongeDisponible($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerEmploye();
                $employe   = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                if ($employe->getIdEmploye() == $_SESSION['user']['idEmploye']
                    || ($_SESSION['compte']['identifiant'] == self::COMPTE_ENTREPRISE
                        && $employe->getIdEntreprise() == $_SESSION['user']['idEntreprise']
                    )
                ) {
                    $annee       = date('Y');
                    $manager     = new ManagerStockConge();
                    $stockConge  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'annee' => $annee]);
                    $duree       = $stockConge->getDuree();
                    /**@changelog 2022-04-26 [OPT] (Lansky) Ajouter le solde du congé au jours de la demande */
                    $manager        = new ManagerConge();
                    $waitValidate   = $manager->lister(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::LEAVE_PROPOSED]);
                    if (count($waitValidate) > 0) {
                        foreach ($waitValidate as $conge) {
                            $duree -= $this->calculateCongeDuring($conge);
                        }
                    }
                    if ($duree < 0) {
                        $duree = 0;
                    }
                    echo $duree;
                }
            }
        }

        /**
         * Lister les demandes de congé d'un employé
         *
         * @param array $parameters     Critères des données à afficher
         *
         * @return array
        */
        public function listerDemandeConges($parameters)
        {
            if (!empty($parameters)) {
                $_SESSION['filters'] = ['annee' => $parameters['annee'], 'type' => $parameters['type']];
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
                $donnees    = $this->getDataConge($parameters, $employe);
                $view       = new View("listerDemandeConges");
                $view->sendWithoutTemplate("Backend", "Conge", $donnees, "");
                exit();
            }
        }

        /**
         * Lister les demandes de congé d'un employé
         *
         *  @param array $parameters     Critères des données à afficher
         *
         * @return array
        */
        public function listerConges($parameters)
        {
            if (!empty($parameters)) {
                $manager     = new ManagerEntreprise();
                $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                if (!empty($parameters['groupe'])) {
                    if ($parameters['groupe'] == self::FILTER_GROUP_ALL) {
                       $employes = $this->getEmployes($entreprise->getIdEntreprise(), true);
                    } elseif ($parameters['groupe'] == self::FILTER_GROUP_SERVICE) {
                        if (!empty($parameters['id'])) {
                            $manager        = new ManagerEntrepriseService();
                            $service        = $manager->chercher([
                                'idEntrepriseService' => $parameters['id']
                            ]);
                            $manager        = new ManagerServicePoste();
                            $servicePostes  = $manager->lister([
                                'idEntrepriseService' => $service->getIdEntrepriseService()
                             ]);
                            $employes       = array();
                            foreach ($servicePostes as $servicePoste) {
                                $manager    = new ManagerContratEmploye();
                                $contrats   = $manager->lister(['idServicePoste' => $servicePoste->getIdServicePoste(), 'statut' =>self::VALIDATED]);
                                foreach ($contrats as $contrat) {
                                    $employes[] = $this->getEmployes($contrat->getIdEmploye());
                                }
                            }
                            $employes = array_unique($employes);
                        }
                    } elseif ($parameters['groupe'] == self::FILTER_GROUP_POSTE) {
                        if (!empty($parameters['id'])) {
                            $manager  = new ManagerEntreprisePoste();
                            $poste    = $manager->chercher(['idEntreprisePoste' => $parameters['id']]);
                            $manager        = new ManagerServicePoste();
                            $servicePostes  = $manager->lister(['idEntreprisePoste' => $poste->getIdEntreprisePoste()]);
                            $employes       = array();
                            foreach ($servicePostes as $servicePoste) {
                                $manager    = new ManagerContratEmploye();
                                $contrats   = $manager->lister(['idServicePoste' => $servicePoste->getIdServicePoste(), 'statut' =>self::VALIDATED]);
                                foreach ($contrats as $contrat) {
                                    $employes[] = $this->getEmployes($contrat->getIdEmploye());
                                }
                            }
                            $employes = array_unique($employes);
                        }
                    } elseif ($parameters['groupe'] == self::FILTER_GROUP_EMPLOYE) {
                        if (!empty($parameters['id'])) {
                            $employes    = array();
                            $employes[]     = $this->getEmployes($parameters['id']);
                        }
                    }
                }
                if (!empty($parameters['annee'])) {
                    $annee       = $parameters['annee'];
                }
                if (!isset($annee)) {
                    $annee = date('Y');
                }
                $congesTotals      = array();
                $congesRestants    = array();
                $congesPris        = array();
                $congesPeutPrendre = array();
                foreach ($employes as $employe) {
                    $manager   = new ManagerParametreConge();
                    $parametre = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $manager   = new ManagerStockConge();
                    if ($employe) {
                        $conge     = $manager->chercher([
                            'idEmploye'  => $employe->getIdEmploye(),
                            'annee'      => $annee
                        ]);
                    }
                    if ($conge != null && !is_null($employe)) {
                        $congesRestants[$employe->getIdEmploye()]     = $conge->getDuree();
                        $congesPris[$employe->getIdEmploye()]         = $this->getQuantiteCongePris($employe, $annee);
                        $congesTotals[$employe->getIdEmploye()]       = $congesRestants[$employe->getIdEmploye()] + $congesPris[$employe->getIdEmploye()];
                        if ($parametre->getAttente() == self::ATTENTE_DESACTIVE || $this->getAnciennete($employe->getIdEmploye())['annees'] >= self::ANNEE_ATTENTE) {
                            $congesPeutPrendre[$employe->getIdEmploye()]  = true;
                        } else {
                            $congesPeutPrendre[$employe->getIdEmploye()]  = false;
                        }
                    } else {
                        if (is_null($employe)) {
                            $manager = new ManagerEmploye();
                            $employe = $manager->initialiser();
                            $employe->setIdEmploye(0);
                        }
                        $congesTotals[$employe->getIdEmploye()]       = null;
                        $congesRestants[$employe->getIdEmploye()]     = null;
                        $congesPris[$employe->getIdEmploye()]         = null;
                        $congesPeutPrendre[$employe->getIdEmploye()]  = null;
                    }
                }
                $donnees     = [
                    'employes'           => $employes,
                    'congesTotals'       => $congesTotals,
                    'congesRestants'     => $congesRestants,
                    'congesPris'         => $congesPris,
                    'congesPeutPrendre'  => $congesPeutPrendre
                ];
                $view = new View("listerConges");
                $view->sendWithoutTemplate("Backend", "Conge", $donnees, "");
                exit();
            }
        }

        /**
         * Récupérer les employé à afficher
         *
         * @param int $id           L'identifiant à chercher
         * @param boolean $lister   La liste de données
         *
         * @return array
        */
        private function getEmployes ($id, $lister = false) 
        {
            $factory = new PublicFonctions();
            $manager = new ManagerEmploye();
            if ($lister) {
                return $factory->getFiltre($_SESSION['user']['idEntreprise'])['employes'];
            } else {
                $employes = $factory->getFiltre($_SESSION['user']['idEntreprise'])['employes'];
                foreach ($employes as $employe) {
                    if ($employe->getIdEmploye() == $id) {
                        return $employe;
                    }   
                }
            }
        }

        /**
         * Supprimer une demande de congé
         *
         * @param array $parameters     Critère des données à supprimer
         *
         * @return empty
        */
        public function supprimerDemandeConge($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerConge();
                $conge     = $manager->chercher(['idConge' => $parameters['idConge']]);
                $manager   = new ManagerEmploye();
                $employe   = $manager->chercher(['idEmploye' => $conge->getIdEmploye()]);
                if ($employe->getIdEmploye() == $_SESSION['user']['idEmploye']
                    || ($_SESSION['compte']['identifiant'] == self::COMPTE_ENTREPRISE
                        && $employe->getIdEntreprise() == $_SESSION['user']['idEntreprise']
                    )
                ) {
                    /**@changelog 2022-04-14 [OPT] (Lansky) Changer le notification du congé lors de retirer sa demande */
                    $manager  = new ManagerValidationConge();
                    $isValidated = $manager->lister(['idConge' => $parameters['idConge']]);
                    foreach ($isValidated as $valide) {
                        $manager  = new ManagerValidationConge();
                        $manager->modifier([
                            'idValidationConge' => $valide->getIdValidationConge(),
                            'statut'            => self::LEAVE_CANCELED
                        ]);
                        $manager = new ManagerMessage();

                        $mssg = $manager->chercher(['idMessage' => $valide->getIdMessage()]);
                        if ($mssg) {
                            $manager->modifier([
                                'idMessage' => $mssg->getIdMessage(),
                                'aFaire'    => self::NO,
                                'objet'     => 'Information de congé retirée',
                                'contenu'   => $this->generateMessageContent(self::TYPE_CANCELED, $conge)
                            ]);
                        }
                    }
                    $manager = new ManagerConge();
                    $manager->modifier([
                        'idConge'   => $conge->getIdConge(),
                        'statut'    => self::LEAVE_CANCELED
                    ]);
                }
            }
        }

        /**
         * Retourner un suivi de validations d'une demande de congé
         *
         * @param int $idConge      L'identifiant du congé
         *
         * @return array
        */
        private function getSuiviDemande($idConge)
        {
            $manager     = new ManagerValidationConge();
            $validations = $manager->lister(['idConge' => $idConge]);
            $suivis      = array();
            foreach ($validations as $validation) {
                $manager    = new ManagerCompte();
                $compte     = $manager->chercher(['idCompte' => $validation->getIdCompte()]);
                if ($compte->getIdentifiant() == self::COMPTE_EMPLOYE) {
                    $tmp['responsable'] = self::COMPTE_EMPLOYE;
                    $manager            = new ManagerEmploye();
                    $employe            = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
                    $tmp[self::COMPTE_EMPLOYE] = $employe;
                } elseif ($compte->getIdentifiant() == self::COMPTE_ENTREPRISE) {
                    $tmp['responsable'] = self::COMPTE_ENTREPRISE;
                    $manager            = new ManagerEntreprise();
                    $entreprise         = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
                    $tmp[self::COMPTE_ENTREPRISE] = $entreprise;
                }
                $tmp['statut'] = $validation->getStatut();
                $suivis[]      = $tmp;
            }
            return $suivis;
        }

        /**
         * Lister les validation de congé d'un employé
         *
         * @param array $parameters     Critères des données à afficher
         *
         * @return array
        */
        public function listerValidationConges($parameters)
        {
            if (!empty($parameters)) {
                $managerValidationConge = new ManagerValidationConge();
                $managerStockConge      = new ManagerStockConge();
                $managerEntreprise      = new ManagerEntreprise();
                $managerEmploye         = new ManagerEmploye();
                $managerCompte          = new ManagerCompte();
                $managerConge           = new ManagerConge();
                if ($parameters['annee'] != null) {
                    $debutAnnee             = date($parameters['annee'] . '-01-01');
                    $finAnnee               = date($parameters['annee'] . '-12-31');
                    $_SESSION['filters']    = ['annee' => $parameters['annee'], 'type' => $parameters['type']];
                } else {
                    $debutAnnee  = date('2019-01-01');
                    $finAnnee    = date(date('Y', strtotime('+1 year')) . '-12-31');
                }
                if ($_SESSION['compte']['identifiant'] == self::COMPTE_EMPLOYE) {
                    $employe     = $managerEmploye->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
                    $compte      = $managerCompte->chercher(['idCompte' => $employe->getIdCompte()]);
                } elseif ($_SESSION['compte']['identifiant'] == self::COMPTE_ENTREPRISE) {
                    $entreprise  = $managerEntreprise->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $compte      = $managerCompte->chercher(['idCompte' => $entreprise->getIdCompte()]);
                }
                $allValidations = array();
                if ($parameters['type'] == self::FILTER_LEAVE_ALL) {
                    $allValidations     = $managerValidationConge->lister([
                        'idCompte'      => $compte->getIdCompte(),
                        'etat'          => self::LEAVE_NOT_ARCHIVED
                    ]);
                } elseif ($parameters['type'] == self::FILTER_LEAVE_ARCHIVED) {
                    $allValidations     = $managerValidationConge->lister([
                        'idCompte'      => $compte->getIdCompte(),
                        'etat'          => self::LEAVE_ARCHIVED
                    ]);
                } else {
                    $allValidations     = $managerValidationConge->lister([
                        'idCompte'      => $compte->getIdCompte(),
                        'statut'          => $parameters['type']
                    ]);
                }
                $validations        = array();
                $demandes           = array();
                $employes           = array();
                $suivisValidation   = [];
                foreach ($allValidations as $validation) {
                    $conge     = $managerConge->chercher(['idConge' => $validation->getIdConge()]);
                    if ($conge) {
                        if (strtotime($conge->getDebut()) >= strtotime($debutAnnee)
                            && strtotime($conge->getDebut()) <= strtotime($finAnnee)
                        ) {
                            $validations[] = $validation;
                            $solde = $managerStockConge->chercher(['idEmploye' => $conge->getIdEmploye(), 'annee' => date('Y')]);
                            $demandes[$validation->getIdValidationConge()] = ['demande' => $conge, 'solde' => $solde];
                            $employes[$conge->getIdConge()] = $managerEmploye->chercher(['idEmploye' => $conge->getIdEmploye()]);
                        }
                        $suivi = $this->getSuiviDemande($conge->getIdConge());
                        if ((count($suivi) == 1 && $suivi[0]['statut'] == self::LEAVE_PROPOSED) || $validation->getStatut() == self::LEAVE_CANCELED) {
                            $suivi = [];
                        }
                        $suivisValidation[$conge->getIdConge()] = $suivi;
                    }
                }
                $donnees     = [
                    'demandes'          => $demandes,
                    'validations'       => $validations,
                    'employes'          => $employes,
                    'suivisValidation'  => $suivisValidation
                ];
                $view = new View("listerValidationConges");
                $view->sendWithoutTemplate("Backend", "Conge", $donnees, "");
                exit();
            }
        }

        /**
         * Valider une demande de congé
         *
         * @param array $parameters     Les critères de la demande à valider
         *
         * @return empty
        */
        public function validerValidationConge($parameters)
        {
            if (!empty($parameters)) {
                $managerValidationConge = new ManagerValidationConge();
                $managerStockConge      = new ManagerStockConge();
                $managerEmploye         = new ManagerEmploye();
                $managerMessage         = new ManagerMessage();
                $managerCompte          = new ManagerCompte();
                $managerConge           = new ManagerConge();
                $factory                = new PublicFonctions();
                /**@changelog 2022-09-23 [OPT] (Lansky) Validation du congé lors de la sélection multiple */
                foreach (explode(',', $parameters['idValidationConge']) as $key => $validationValue) {
                    if ($validationValue) {
                        $validation = $managerValidationConge->chercher(['idValidationConge' => $validationValue]);
                        $compte     = $managerCompte->chercher(['idCompte' => $validation->getIdCompte()]);
                        if ($compte->getIdCompte() == $_SESSION['user']['idCompte']) {
                            $resultat  = $managerValidationConge->modifier([
                                'idValidationConge' => $validation->getIdValidationConge(),
                                'statut'            => self::LEAVE_VALIDATED
                            ]);
                            $resultat  = $managerValidationConge->chercher(['idValidationConge' => $validation->getIdValidationConge()]);
                            /**@changelog 2022-09-07 [OPT] (Lansky) Chercher les validations du congé ont la même date, ce qui a causé la rédondance de la validation */
                            $redondancy = $managerValidationConge->lister([
                                'idConge' => $resultat->getIdConge(), 
                                'idCompte'=>$resultat->getIdCompte()
                            ]);
                            if ($redondancy) {
                                foreach ($redondancy as $double) {
                                    $managerValidationConge->modifier([
                                        'idValidationConge' => $double->getIdValidationConge(),
                                        'statut'            => self::LEAVE_VALIDATED
                                    ]);                      
                                }
                            }
                            $message   = $managerMessage->chercher(['idMessage' => $resultat->getIdMessage()]);
                            if ($message != null) {
                                $managerMessage->modifier([
                                    'idMessage'   => $message->getIdMessage(),
                                    'statut'      => self::SEEN
                                ]);
                            }
                            if ($compte->getIdentifiant() == self::COMPTE_ENTREPRISE) {
                                if ($resultat != null) {
                                    $conge     = $managerConge->chercher(['idConge' => $validation->getIdConge()]);
                                    $resultat  = $managerConge->modifier([
                                        'idConge'  => $conge->getIdConge(),
                                        'statut'   => self::LEAVE_VALIDATED
                                    ]);
                                    if ($resultat->getStatut() == self::LEAVE_VALIDATED) {
                                        $conge      = $managerConge->chercher(['idConge' => $conge->getIdConge()]);
                                        $employe    = $managerEmploye->chercher(['idEmploye' => $conge->getIdEmploye()]);
                                        $year       = date('Y');
                                        $stock      = $managerStockConge->chercher([
                                            'idEmploye' => $employe->getIdEmploye(),
                                            'annee'     => $year
                                        ]);
                                        $jours      = $this->getJours($conge->getDebut(), $conge->getHeureDebut(), $conge->getFin(), $conge->getHeureFin(), $conge->getBeginto(), $conge->getDuring());
                                        $managerStockConge->modifier([
                                            'idStockConge' => $stock->getIdStockConge(),
                                            'duree'        => $stock->getDuree() - $jours
                                        ]);
                                        $content        = $this->generateMessageContent(self::TYPE_VALIDATED, $conge, true);
                                        $mailContent    = $this->generateMailContent(self::TYPE_VALIDATED, $conge, true);
                                        $this->sendMessageNotification($employe->getIdCompte(), 'Demande de congé validée', $content);
                                        $this->sendMailNotification($employe, 'Demande de congé validée', $mailContent);
                                        $_SESSION['info']['success'] = "La demande a été validée avec succès !";
                                        $tmpEmploye = $employe;
                                        $this->notifyAllSupperior($tmpEmploye, self::TYPE_INFORMATION, $conge, $employe, false, false, false);
                                    }
                                }
                            } elseif ($compte->getIdentifiant() == self::COMPTE_EMPLOYE) {
                                if ($resultat != null) {
                                    $conge      = $managerConge->chercher(['idConge' => $validation->getIdConge()]);
                                    $employe    = $managerEmploye->chercher(['idCompte' => $compte->getIdCompte()]);
                                    $demandeur  = $managerEmploye->chercher(['idEmploye' => $conge->getIdEmploye()]);
                                    $chef       = $factory->getChief($employe, $demandeur);
                                    $this->passerValidation($employe, $chef, $conge);
                                    $_SESSION['info']['success'] = "La demande a été validée avec succès !";
                                }
                            }
                        }
                    }
                }
            }
        }

        /**
         * Envoyer un rappel de demande de congé
         *
         * @param array $parameters     Critères de la demande
         *
         * @return empty
        */
        public function rappelerDemandeConge($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerValidationConge();
                $validation = $manager->chercher([
                    'idConge' => $parameters['idConge'],
                    'statut'  => self::LEAVE_PROPOSED
                ]);
                if ($validation != null) {
                    $manager = new ManagerEmploye();
                    $validateur = $manager->chercher(['idCompte' => $validation->getIdCompte()]);
                    if ($validateur == null) {
                        $manager = new ManagerEntreprise();
                        $validateur = $manager->chercher(['idCompte' => $validation->getIdCompte()]);
                    }
                    $manager = new ManagerConge();
                    $conge   = $manager->chercher(['idConge' => $validation->getIdConge()]);
                    $manager = new ManagerEmploye();
                    $demandeur = $manager->chercher(['idEmploye' => $conge->getIdEmploye()]);
                    $content = $this->generateMessageContent(self::TYPE_REQUEST, $validation);
                    $this->sendMessageNotification($validation->getIdCompte(), 'Rappel de demande de validation de congé de la part de <span class="titre"> ' . $demandeur->getCivilite() . " " . $demandeur->getNom() . ' ' . $demandeur->getPrenom() . '</span>', $content);
                    $contentMail = $this->generateMailContent(self::TYPE_REQUEST, $conge);
                    $this->sendMailNotification($validateur, "Rappel de demande de validation de congé de la part de '" . $demandeur->getCivilite() . " " . $demandeur->getNom() . " " . $demandeur->getPrenom() . "'", $contentMail);
                    $_SESSION['info']['success'] = "Votre rappel a été envoyé";
                }
                else {
                    $_SESSION['info']['danger'] = "Impossible d'envoyer un rappel";
                }
            }
        }

        /**
         * Vérifier l'abscence d'un employé à la date donnée
         *
         * @param  object $chef     Le chef hierarchique
         * @param  date $date       La date en question
         *
         * @return boolean
        */
        private function verifyAbsence($chef, $date)
        {
            $enConge        = $this->estEnConge($chef, $date);
            $enPermission   = $this->estEnPermission($chef, $date);
            $enRepos        = $this->estEnRepos($chef, $date);
            if ($enConge || $enPermission || $enRepos) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Faire passer la validation d'une demande de congé
         * 
         * @param  object $employe  L'employé concerné
         * @param  object $chef     Le chef hierarchique
         * @param  object $demande  La demande de congé
         *
         * @return empty
        */
        public function passerValidation($employe, $chef, $demande)
        {
            $factory    = new PublicFonctions();
            $manager    = new ManagerEmploye();
            $demandeur  = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
            $manager    = new ManagerParametreConge();
            // Récupérer les parametres du congé de l'entreprise
            $parametre  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $versRh     = false;
            /**@changelog 2022-05-03 [OPT] (Lansky) Seul le validateur peut valider la demande de congé */
            if ($chef) {
                $chef = $factory->getChiefNotValidate($chef, $demandeur);
                /**@changelog 2022-09-28 [OPT] (Lansky) Vérifier si le validateur est en congé ou en repos ou en congé le quand on fait une demande de congé et faire passer la validation au sepérieur */
                $verified = $chef && $chef->getIdEmploye() ? $this->verifyAbsence($chef, date('Y-m-d')) : false;
                if ($verified) {
                    $content    = "<p>Bonjour, </p><br<br><p>" .
                                        "Nous vous informons qu'en votre absence " .
                                        $demandeur->getCivilite() . " " . $demandeur->getNom() . " " . $demandeur->getPrenom() .
                                        " a demandé un congé du " . $this->writeDateConge($demande->getDebut(), $demande->getHeureDebut(), $demande->getFin(), $demande->getHeureFin(), $demande->getBeginto(), $demande->getDuring()) .
                                    "</p>";
                    while ($verified) {
                        // Notifier le supérieur au lieu de validation
                        if ($parametre->getNotifyAbsence() == self::YES) {
                            $idMessage  = $this->sendMessageNotification($chef->getIdCompte(), 'En votre absence&nbsp;<span class="titre">' . $demandeur->getCivilite() . ' ' . $demandeur->getNom() . ' ' . $demandeur->getPrenom() . '</span> a demandé un congé', $content);
                            if ($idMessage) {
                                $manager     = new ManagerMessage();
                                $manager->modifier([
                                    'idMessage' => $idMessage,
                                    'aFaire'    => self::NO
                                ]);
                            }
                            $this->sendMailNotification($chef, 'En votre absence ' . $demandeur->getCivilite() . ' ' . $demandeur->getNom() . ' ' . $demandeur->getPrenom() . ' a demandé un congé', $content, true);
                        }
                        $manager    = new ManagerEmploye();
                        $chef       = $factory->getChief($chef, $demandeur);
                        if (!is_null($chef) && $chef->getIdEmploye()) {
                            $verified   = $this->verifyAbsence($chef, date('Y-m-d'));
                            $chef       = $factory->getChiefNotValidate($chef, $demandeur);
                        } else {
                            $verified = false;
                        }
                    }
                }
            }
            if ($chef != null && $chef->getIdEmploye()) {
                if ($parametre->getProcessus() == self::DIRECT_VALIDATION) {
                    $versRh = true;
                } elseif ($parametre->getProcessus() == self::DEFINED_VALIDATION) {
                    $niveau = $parametre->getNiveau();
                    $poste = $this->getPosteEmploye($chef);
                    if ($poste->getNiveau() > $niveau) {
                        $versRh = true;
                    }
                }
            } else {
                $versRh = true;
            }
            if ($versRh == true) { 
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                $manager    = new ManagerValidationConge();
                $resultat   = $manager->ajouter([
                    'idCompte'  => $entreprise->getIdCompte(),
                    'idConge'   => $demande->getIdConge(),
                    'statut'    => self::LEAVE_PROPOSED
                ]);
                if ($resultat->getIdValidationConge() != self::NO) {
                    $content     = $this->generateMessageContent(self::TYPE_REQUEST, $resultat);
                    $contentMail = $this->generateMailContent(self::TYPE_REQUEST, $demande);
                    $idMessage   = $this->sendMessageNotification($entreprise->getIdCompte(), 'Demande de validation de congé de la part de <span class="titre">' . $demandeur->getCivilite() . ' ' . $demandeur->getNom() . ' ' . $demandeur->getPrenom() . '</span>', $content);
                    $manager     = new ManagerMessage();
                    $manager->modifier([
                        'idMessage' => $idMessage,
                        'aFaire'    => self::NO
                    ]);
                    $subjectMail = "Demande de validation de congé de la part de " . $demandeur->getNom() . ' ' . $demandeur->getPrenom();
                    $this->sendMailNotification($entreprise, $subjectMail, $contentMail);
                    $manager = new ManagerValidationConge();
                    $retour = $manager->modifier([
                        'idValidationConge' => $resultat->getIdValidationConge(),
                        'idMessage'         => $idMessage
                    ]);
                }
            } else {
                $manager  = new ManagerValidationConge();
                $resultat = $manager->ajouter([
                    'idCompte'  => $chef->getIdCompte(),
                    'idConge'   => $demande->getIdConge(),
                    'statut'    => self::LEAVE_PROPOSED
                ]);
                if ($resultat != null) {
                    $content     = $this->generateMessageContent(self::TYPE_REQUEST, $resultat);
                    $contentMail = $this->generateMailContent(self::TYPE_REQUEST, $demande);
                    $idMessage   = $this->sendMessageNotification($chef->getIdCompte(), 'Demande de validation de congé de la part de <span class="titre">' . $demandeur->getCivilite() . ' ' . $demandeur->getNom() . ' ' . $demandeur->getPrenom() . '</span>', $content);
                    $subjectMail = "Demande de validation de congé de la part de " . $demandeur->getNom() . ' ' . $demandeur->getPrenom();
                    $manager     = new ManagerMessage();
                    $manager->modifier([
                        'idMessage' => $idMessage,
                        'aFaire'    => self::NO
                    ]);
                    $this->sendMailNotification($chef, $subjectMail, $contentMail);
                    $manager = new ManagerValidationConge();
                    $manager->modifier([
                        'idValidationConge' => $resultat->getIdValidationConge(),
                        'idMessage'         => $idMessage
                    ]);
                }
            }
        }

        /**
         * Retourner le poste d'une employé
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
         * Rejeter une demande de congé
         *
         * @param array $parameters Les critères de la demande à rejeter
         *
         * @return empty
        */
        public function rejeterValidationConge($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerValidationConge();
                $validation = $manager->chercher(['idValidationConge' => $parameters['idValidationConge']]);
                $manager    = new ManagerCompte();
                $compte     = $manager->chercher(['idCompte' => $validation->getIdCompte()]);
                if ($compte->getIdCompte() == $_SESSION['user']['idCompte']) {
                    $manager   = new ManagerValidationConge();
                    $resultat  = $manager->modifier([
                        'idValidationConge' => $validation->getIdValidationConge(),
                        'statut'            => self::LEAVE_REJECTED,
                    ]);
                    $resultat  = $manager->chercher(['idValidationConge' => $validation->getIdValidationConge()]);
                    $manager   = new ManagerMessage();
                    $message   = $manager->chercher(['idMessage' => $resultat->getIdMessage()]);
                    if ($message != null) {
                        $manager->modifier([
                            'idMessage'   => $message->getIdMessage(),
                            'statut'      => self::SEEN
                        ]);
                    }
                    if ($resultat != null) {
                        $manager   = new ManagerConge();
                        $conge     = $manager->chercher(['idConge' => $validation->getIdConge()]);
                        if ($conge->getStatut() == self::LEAVE_ABOLISHED) {
                            $conge  = $manager->modifier([
                                'idConge'    => $conge->getIdConge(),
                                'motifRefus' => $parameters['motifRefus']
                            ]);
                            $conge          = $manager->chercher(['idConge' => $conge->getIdConge()]);
                            $object         = "Demande d'annulation de congé rejetée"; 
                            $_SESSION['info']['success'] = "La demande d'annulation de congé a été rejetée";
                            $manager        = new ManagerEmploye();
                            $employe        = $manager->chercher(['idEmploye' => $conge->getIdEmploye()]);
                            $content        = $this->generateMessageContent(self::TYPE_REJECTED, $conge);
                            $contentMail    = $this->generateMailContent(self::TYPE_REJECTED, $conge);
                            $this->sendMessageNotification($employe->getIdCompte(), $object, $content);
                            $this->sendMailNotification($employe, $object, $contentMail);
                        } else {
                            $resultat  = $manager->modifier([
                                'idConge'    => $conge->getIdConge(),
                                'statut'     => self::LEAVE_REJECTED,
                                'motifRefus' => $parameters['motifRefus']
                            ]);
                            $resultat = $manager->chercher(['idConge' => $conge->getIdConge()]);
                            if ($resultat->getStatut() == self::LEAVE_REJECTED) {
                                $object         = 'Demande de congé rejetée'; 
                                $_SESSION['info']['success'] = "La demande de congé a été rejetée";
                                $manager        = new ManagerEmploye();
                                $employe        = $manager->chercher(['idEmploye' => $conge->getIdEmploye()]);
                                $content        = $this->generateMessageContent(self::TYPE_REJECTED, $resultat);
                                $contentMail    = $this->generateMailContent(self::TYPE_REJECTED, $resultat);
                                $this->sendMessageNotification($employe->getIdCompte(), $object, $content);
                                $this->sendMailNotification($employe, $object, $contentMail);
                            }
                            $manager = new ManagerValidationConge();
                            $validations = $manager->lister([
                                'idConge' => $conge->getIdConge(),
                                'statut'  => self::LEAVE_VALIDATED
                            ]);
                            $validateurs = array();
                            foreach ($validations as $validation) {
                                $manager = new ManagerEmploye();
                                $validateurs[] = $manager->chercher(['idCompte' => $validation->getIdCompte()]);
                            }
                            $mailContent = $this->generateMailContent(self::TYPE_INFORMATION, $resultat);
                            foreach ($validateurs as $validateur) {
                                $this->sendMailNotification($validateur, "Demande de congé refusée", $mailContent);
                            }
                        }
                    }
                }
            }
        }

        /**
         * Archiver une validation de congé
         *
         * @param array $parameters Les critères de la demande à archiver
         *
         * @return empty
        */
        public function archiverValidationConge($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerValidationConge();
                foreach (explode(',', $parameters['idValidationConge']) as $value) {
                    if ($value) {
                        $validation = $manager->chercher(['idValidationConge' => $value]);
                        $manager    = new ManagerCompte();
                        $compte     = $manager->chercher(['idCompte' => $validation->getIdCompte()]);
                        if ($compte->getIdCompte() == $_SESSION['user']['idCompte']) {
                            $manager   = new ManagerValidationConge();
                            $manager->modifier([
                                'idValidationConge' => $validation->getIdValidationConge(),
                                'etat'            => self::LEAVE_ARCHIVED
                            ]);
                        }
                    }
                }
            }
        }

        /**
         * Restaurer une validation de congé
         *
         * @param array $parameters Les critères de la demande à restaurer
         *
         * @return empty
        */
        public function restaurerValidationConge($parameters)
        {
            if (!empty($parameters)) {
                foreach (explode(',', $parameters['idValidationConge']) as $value) {
                    if ($value) {
                        $manager    = new ManagerValidationConge();
                        $validation = $manager->chercher(['idValidationConge' => $value]);
                        $manager    = new ManagerCompte();
                        $compte     = $manager->chercher(['idCompte' => $validation->getIdCompte()]);
                        if ($compte->getIdCompte() == $_SESSION['user']['idCompte']) {
                            $manager   = new ManagerValidationConge();
                            $manager->modifier([
                                'idValidationConge' => $validation->getIdValidationConge(),
                                'etat'            => self::LEAVE_NOT_ARCHIVED
                            ]);
                        }
                    }
                }
            }
        }

        /**
         * Archiver une demande de congé
         *
         * @param array $parameters Les critères de la demande à archiver
         * 
         * @return empty
        */
        public function archiverDemandeConge($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerConge();
                $conge      = $manager->chercher(['idConge' => $parameters['idConge']]);
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $conge->getIdEmploye()]);
                if ($employe->getIdEmploye() == $_SESSION['user']['idEmploye']) {
                    $manager   = new ManagerConge();
                    $manager->modifier([
                        'idConge' => $conge->getIdConge(),
                        'etat'    => self::LEAVE_ARCHIVED
                    ]);
                }
            }
        }

        /**
         * Restaurer une demande de congé
         *
         * @param array $parameters Les critères de la demande à restaurer
         *
         * @return empty
        */
        public function restaurerDemandeConge($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerConge();
                $conge      = $manager->chercher(['idConge' => $parameters['idConge']]);
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $conge->getIdEmploye()]);
                if ($employe->getIdEmploye() == $_SESSION['user']['idEmploye']) {
                    $manager   = new ManagerConge();
                    $manager->modifier([
                        'idConge' => $conge->getIdConge(),
                        'etat'    => self::LEAVE_NOT_ARCHIVED
                    ]);
                }
            }
        }

        /**
         * Récupérer les données de solde de congé d'un employé
         *
         * @param array $parameters Les critères du solde
         *
         * @return empty
        */
        public function getStockConge($parameters)
        {
            if (!empty($parameters)) {
                $year     = date('Y');
                $manager  = new ManagerEmploye();
                $employe  = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                if ($employe != null && $employe->getIdEntreprise() == $_SESSION['user']['idEntreprise']) {
                    $manager  = new ManagerStockConge();
                    $stock    = $manager->chercher([
                        'idEmploye' => $employe->getIdEmploye(),
                        'annee' => $year
                    ]);
                    if ($stock != null) {
                        $donnees['stock']   = $stock->toArray();
                        $donnees['employe'] = $employe->toArray();
                        echo json_encode($donnees);
                        exit();
                    }
                }
            }
        }

        /**
         * Mettre à jour un solde de congé d'un employé
         *
         * @param array $parameters Les critères à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourStockConge($parameters)
        {
            if (!empty($parameters)) {
                $year     = date('Y');
                $manager  = new ManagerEmploye();
                $employe  = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                if ($employe != null && $employe->getIdEntreprise() == $_SESSION['user']['idEntreprise']) {
                    $manager  = new ManagerStockConge();
                    $stock    = $manager->chercher([
                        'idEmploye' => $employe->getIdEmploye(),
                        'annee' => $year
                    ]);
                    if ($stock != null) {
                        $retour = $manager->modifier([
                            'idStockConge'  => $stock->getIdStockConge(),
                            'duree' => $parameters['duree']
                        ]);
                        if ($retour->getDuree() == $parameters['duree']) {
                            $_SESSION['info']['success'] = "Le solde a été mis à jour !";
                        } else {
                            $_SESSION['info']['danger'] = "Echec de l'opération !";
                        }
                    } else {
                        $_SESSION['info']['danger'] = "Données introuvable !";
                    }
                }
            }
        }

        /**
         * Récupérer une liste d'employés selon un filtre
         *
         * @param int $filterGroup Le type de filtre
         * @param int $idFiltre    L'identifiant du filtre
         *
         * @return array
        */
        private function getEmployeByFiltre($filterGroup, $idFiltre)
        {
            if (!empty($filterGroup)) {
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $employes   = array();
                if ($filterGroup == self::FILTER_GROUP_ALL) {
                    $manager  = new ManagerEmploye();
                    $employes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                } elseif ($filterGroup == self::FILTER_GROUP_SERVICE) {
                    $manager        = new ManagerEntrepriseService();
                    $service        = $manager->chercher(['idEntrepriseService' => $idFiltre]);
                    $manager        = new ManagerServicePoste();
                    $servicePostes  = $manager->lister(['idEntrepriseService' => $service->getIdEntrepriseService()]);
                    $employes       = array();
                    foreach ($servicePostes as $servicePoste) {
                        $manager    = new ManagerContratEmploye();
                        $contrats   = $manager->lister(['idServicePoste' => $servicePoste->getIdServicePoste(), 'statut' => self::VALIDATED]);
                        foreach ($contrats as $contrat) {
                            $manager    = new ManagerEmploye();
                            $employes[] = $manager->chercher(['idEmploye' => $contrat->getIdEmploye()]);
                        }
                    }
                    $employes = array_unique($employes);
                } elseif ($filterGroup == self::FILTER_GROUP_POSTE) {
                    $manager        = new ManagerEntreprisePoste();
                    $poste          = $manager->chercher(['idEntreprisePoste' => $idFiltre]);
                    $manager        = new ManagerServicePoste();
                    $servicePostes  = $manager->lister(['idEntreprisePoste' => $poste->getIdEntreprisePoste()]);
                    $employes       = array();
                    foreach ($servicePostes as $servicePoste) {
                        $manager    = new ManagerContratEmploye();
                        $contrats   = $manager->lister(['idServicePoste' => $servicePoste->getIdServicePoste(), 'statut' => self::VALIDATED]);
                        foreach ($contrats as $contrat) {
                            $manager    = new ManagerEmploye();
                            $employes[] = $manager->chercher(['idEmploye' => $contrat->getIdEmploye()]);
                        }
                    }
                    $employes = array_unique($employes);
                } elseif ($filterGroup == self::FILTER_GROUP_EMPLOYE) {
                    $manager     = new ManagerEmploye();
                    $employes[]  = $manager->chercher(['idEmploye' => $idFiltre]);
                }
                if (isset($_SESSION['user']['idEmploye']) && (int)$idFiltre < 1) {
                    $factory    = new PublicFonctions();
                    $employes   = $factory->listOfMyTeam()['subordonnes'];
                }
                return $employes;
            }
        }

        /**
         * Récupérer le planning d'une liste d'employés dans un mois
         *
         * @param array  $employes    La liste d'employés
         * @param int    $mois        Le mois en question
         * @param int    $annee        L'année du mois
         *
         * @return array
        */
        private function getPlanning($employes, $mois, $annee, $fin = null)
        {
            $dateEmployes = array();
            foreach ($employes as $employe) {
                $manager        = new ManagerEntreprise();
                $entreprise     = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                $dateEmployes[] = $this->getInfoEmployePlanning($employe, $mois, $annee, $fin);
            }
            return $dateEmployes;
        }

        /**
         * Récuperer les informations d'une date de planning d'une entreprise
         * 
         * @param array $parameters
         *
         * @return array
        */
        public function voirDatePlanning($parameters)
        {
            if (!empty($parameters)) {
                $mois       = explode('-', $parameters['mois'])[1];
                $annee      = explode('-', $parameters['mois'])[0];
                $manager    = new ManagerEntreprise();
                $endDate    = isset($parameters['endDate']) ? $parameters['endDate'] : null;
                $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                echo json_encode($this->getInfoDatePlanning($entreprise, $mois, $annee, $endDate));
                exit();
            }
        }

        /**
         * Récuperer les employés concernés par le filtre
         *
         * @param array $parameters
         *
         * @return array
        */
        public function voirEmployePlanning($parameters)
        {
            if (!empty($parameters)) {
                $employes = $this->getEmployeByFiltre($parameters['groupe'], $parameters['id']);
                $data     = array();
                foreach ($employes as $employe) {
                    $manager         = new ManagerContratEmploye();
                    $contrat         = $manager->chercher(['idEmploye' => $employe->getIdEmploye()/*, 'statut' => self::VALIDATED*/]); // Tous les statut si possible; sinon ceux-ci peut causer un dug si seulement si le contrat est NULL
                    $employe         = $employe->toArray();
                    if ($contrat) {
                        $manager         = new ManagerServicePoste();
                        $servicePoste    = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                        $manager         = new ManagerEntrepriseService();
                        $service         = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                        $manager         = new ManagerEntreprisePoste();
                        $poste           = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                        $employe['service'] = ucwords($service->getService());
                        $employe['poste']   = $poste->getPoste();
                    } else {
                        $employe['service'] = 'Inconnue';
                        $employe['poste']   = 'Aucun';
                    }
                    $data[]  = $employe;
                }
                echo json_encode($data);
                exit();
            }
        }

        /**
         * Récuperer le contenu du planning
         *
         * @param array $parameters
         *
         * @return array
        */
        public function voirContenuPlanning($parameters)
        {  
            if (!empty($parameters)) {
                $employes   = $this->getEmployeByFiltre($parameters['groupe'], $parameters['id']);
                $mois       = explode('-', $parameters['mois'])[1];
                $annee      = explode('-', $parameters['mois'])[0];
                $fin        = isset($parameters['endDate']) ? $parameters['endDate'] : null;
                $data       = $this->getPlanning($employes, $mois, $annee, $fin);
                echo json_encode($data);
                exit();
            }
        }

        /**
         * Récuperer les informations d'une date de planning d'une entreprise
         *
         * @param object $entreprise L'entreprise
         * @param date   $date       La date en question
         * @param int    $annee      L'année du mois
         *
         * @return array
        */
        private function getInfoDatePlanning($entreprise, $mois, $annee, $endDate = null)
        {
            $debut          = date($annee . '-' . $mois . '-01');
            $fin            = is_null($endDate) ? date("Y-m-d", mktime(0, 0, 0, ($mois + 1), 0, $annee)) : $endDate;
            $currentDate    = $debut;
            $dates          = array();
            while (strtotime($currentDate) <= strtotime($fin)) {
                $donnees              = array();
                $donnees['date']      = $currentDate;
                $donnees['jour']      = $this->getContractedDayLetter($currentDate) . ' ' . date('d', strtotime($currentDate));
                $donnees['dateComplete'] = $this->writeDate($currentDate);
                $donnees['isWeekend'] = $this->estWeekend($currentDate);
                $donnees['isFerie']   = $this->estFerie($entreprise, $currentDate);
                $currentDate          = date('Y-m-d', strtotime($currentDate . ' + 1 DAY'));
                $dates[]              = $donnees;
            }
            return $dates;
        }

        /**
         * Récuperer les informations de planning d'un employé dans un mois
         *
         * @param object $employe    L'employé
         * @param int    $mois       Le mois en question
         * @param int    $annee      L'année du mois
         *
         * @return array
        */
        private function getInfoEmployePlanning($employe, $mois, $annee, $endDate = null)
        {
            $debut          = date($annee . '-' . $mois . '-01');
            $fin            = is_null($endDate) ? date("Y-m-d", mktime(0, 0, 0, ($mois + 1), 0, $annee)) : $endDate;
            $currentDate    = $debut;
            $resultats      = array();
            $manager        = new ManagerEntreprise();
            $entreprise     = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
            while (strtotime($currentDate) <= strtotime($fin)) {
                $donnees                  = array();
                $donnees['date']          = $currentDate;
                $donnees['dateEcrite']    = $this->writeDate($currentDate);
                $donnees['jour']          = $this->getContractedDayLetter($currentDate) . ' ' . date('d', strtotime($currentDate));
                $donnees['isWeekend']     = $this->estWeekend($currentDate);
                $donnees['isFerie']       = $this->estFerie($entreprise, $currentDate);
                $donnees['isConge']       = $this->estEnConge($employe, $currentDate);
                $donnees['isPermission']  = $this->estEnPermission($employe, $currentDate);
                $donnees['isRepos']       = $this->estEnRepos($employe, $currentDate);
                $presence                 =  $this->getPresence($employe, $currentDate);
                $donnees['presence']      = $presence;
                if ($presence != false) {
                    if ($presence->getStatut() == self::PRESENT_YES) {
                        $donnees['isPresent'] = true;
                    } else {
                        $donnees['isPresent'] = false;
                    }
                }
                $currentDate              = date('Y-m-d', strtotime($currentDate . ' + 1 DAY'));
                $resultats[]              = $donnees;
            }
            return $resultats;
        }

        /**
         * Vérifier si un employé a un congé à une date donnée
         *
         * @param object $employe  L'employé
         * @param date   $date     La date
         *
         * @return array|false
        */
        private function estEnConge($employe, $date)
        {
            $manager = new ManagerConge();
            $conges  = $manager->lister(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
            foreach ($conges as $conge) {
                if (strtotime($date) >= strtotime($conge->getDebut()) && strtotime($date) <= strtotime($conge->getFin())) {
                    $conge = $conge->toArray();
                    $conge['dateDebut'] = $this->writeDate($conge['debut']);
                    $conge['dateFin']   = $this->writeDate($conge['fin']);
                    return $conge;
                }
            }
            return false;
        }

        /**
         * Retourner la quantité de congé pris dans une année
         *
         * @param object $employe   L'employé concerné
         * @param int    $annee     L'année en question
         *
         * @return float
        */
        private function getQuantiteCongePris($employe, $annee)
        {
            $manager  = new ManagerConge();
            $conges   = $manager->lister(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
            $quantite = 0;
            foreach ($conges as $conge) {
                if (date('Y', strtotime($conge->getDebut())) == date('Y', strtotime($conge->getFin()))) {
                    if (date('Y', strtotime($conge->getDebut())) == $annee) {
                        $date = $conge->getDebut();
                        while (strtotime($date) <= strtotime($conge->getFin())) {
                            $quantite ++;
                            $date = date('Y-m-d', strtotime('+ 1 DAY', strtotime($date)));
                        }
                        if ($conge->getBeginto() > 0) {
                            $quantite = $conge->getDuring() / self::WORK_HOUR;
                        } else {
                            if ($conge->getHeureDebut() == self::APRES_MIDI) {
                                $quantite -= self::DEMI_JOURNEE;
                            }
                            if ($conge->getHeureFin() == self::APRES_MIDI) {
                                $quantite -= self::DEMI_JOURNEE;
                            }
                        }
                    }
                } else {
                    if (date('Y', strtotime($conge->getDebut())) == $annee) {
                        $date = $conge->getDebut();
                        while (strtotime($date) <= strtotime(date("31-12-" . $annee))) {
                            $quantite ++;
                            $date = date('Y-m-d', strtotime('+ 1 DAY', strtotime($date)));
                        }
                        if ($conge->getHeureDebut() == self::APRES_MIDI) {
                            $quantite -= self::DEMI_JOURNEE;
                        }
                    } elseif (date('Y', strtotime($conge->getFin())) == $annee) {
                        $date = date("01-01-" . $annee);
                        while (strtotime($date) <= strtotime($conge->getFin())) {
                            $quantite ++;
                            $date = date('Y-m-d', strtotime('+ 1 DAY', strtotime($date)));
                        }
                        if ($conge->getHeureFin() == self::APRES_MIDI) {
                            $quantite -= self::DEMI_JOURNEE;
                        }
                    }
                }
            }
            return $quantite;
        }

        /**
         * Vérifier si un employé est en permission à une date donnée
         *
         * @param object  $employe  L'employé en question
         * @param date    $date     La date en question
         *
         * @return string|false
        */
        private function estEnPermission($employe, $date)
        {
            $manager            = new ManagerParametrePermission();
            $parametre          = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
            $permissionMax      = $parametre->getDureeMaxPermission();
            $intervalleMin      = date('Y-m-d', strtotime('-' . $permissionMax . ' DAY', strtotime($date)));
            $intervalleMax      = $date;
            $manager            = new ManagerEmployePermission();
            $employePermissions = $manager->selectionner(
                ['idEmploye' => $employe->getIdEmploye(),
                'statut'     => self::VALIDATED],
                ['date'      => $intervalleMin],
                ['date'      => $intervalleMax]
            );
            foreach ($employePermissions as $employePermission) {
                $manager              = new ManagerEntreprisePermission();
                $entreprisePermission = $manager->chercher(['idEntreprisePermission' => $employePermission->getIdEntreprisePermission()]);
                $tmpDuree             = $entreprisePermission->getDuree();
                $tmpDate              = $employePermission->getDate();
                while ($tmpDuree > self::NO) {
                    if ($date == $tmpDate) {
                        $manager = new ManagerTypePermission();
                        $type    = $manager->chercher(['idTypePermission' => $entreprisePermission->getIdTypePermission()]);
                        return $type->getDesignation();
                    }
                    $tmpDate = date('Y-m-d', strtotime('+1 DAY', strtotime($tmpDate)));
                    $tmpDuree--;
                }
            }
            return false;
        }

        /**
         * Vérifier si un employé est en repos médical à une date donnée
         *
         * @param object  $employe  L'employé en question
         * @param date    $date     La date en question
         *
         * @return true|false
        */
        private function estEnRepos($employe, $date)
        {
            $manager            = new ManagerParametrePermission();
            $parametre          = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
            $reposMax           = $parametre->getDureeMaxRepos();
            $intervalleMin      = date('Y-m-d', strtotime('-' . $reposMax . ' DAY', strtotime($date)));
            $intervalleMax      = $date;
            $manager            = new ManagerEmployeRepos();
            $employeRepos       = $manager->selectionner(
                ['idEmploye' => $employe->getIdEmploye(),
                'statut'     => self::VALIDATED],
                ['date'      => $intervalleMin],
                ['date'      => $intervalleMax]
            );
            foreach ($employeRepos as $employeRepos) {
                $tmpDuree     = $employeRepos->getDuree();
                $tmpDate      = $employeRepos->getDate();
                while ($tmpDuree > self::NO) {
                    if ($date == $tmpDate) {
                        return true;
                    }
                    $tmpDate = date('Y-m-d', strtotime('+1 DAY', strtotime($tmpDate)));
                    $tmpDuree--;
                }
            }
            return false;
        }

        /**
         * Vérifier si un employé a un pointage à une date donnée
         *
         * @param object  $employe  L'employé en question
         * @param date    $date     La date en question
         *
         * @return object|false
        */
        private function getPresence($employe, $date)
        {
            $manager  = new ManagerPresence();
            $presence = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'date' => $date]);
            if ($presence != null) {
                return $presence;
            }
            return false;
        }

        /**
         * Vérifier si une date est un jour férié pour une entreprise
         *
         * @param object $entreprise    L'entreprise
         * @param date   $date          La date en question
         *
         * @return string|false
        */
        private function estFerie($entreprise, $date)
        {
            $manager   = new ManagerEntrepriseFerie();
            $ferie     = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise(), 'date' => $date]);
            if ($ferie != null) {
                $manager = new ManagerJourFerie();
                $jourFerie = $manager->chercher(['idJourFerie' => $ferie->getIdJourFerie()]);
                return $jourFerie->getDesignation();
            }
            return false;
        }

        /**
         * Vérifier si une date est un weekend
         *
         * @param date $date    La date en question
         *
         * @return true|false
         *
        */
        private function estWeekend($date)
        {
            if ($this->getDayLetter($date) == self::SATURDAY || $this->getDayLetter($date) == self::SUNDAY) {
                return true;
            }
            return false;
        }

        /**
         * Récuperer le jour entier d'une date en français
         *
         * @param date $date La date
         *
         * @return string
        */
        private function getDayLetter($date)
        {
            $day = date('D', strtotime($date));
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
         * Récuperer le jour contracté d'une date en français
         *
         * @param date $date La date
         *
         * @return string
        */
        private function getContractedDayLetter($date)
        {
            $day = date('D', strtotime($date));
            $days['Mon'] = self::CONTRACTED_MONDAY;
            $days['Tue'] = self::CONTRACTED_TUESDAY;
            $days['Wed'] = self::CONTRACTED_WEDNESDAY;
            $days['Thu'] = self::CONTRACTED_THURSDAY;
            $days['Fri'] = self::CONTRACTED_FRIDAY;
            $days['Sat'] = self::CONTRACTED_SATURDAY;
            $days['Sun'] = self::CONTRACTED_SUNDAY;
            return $days[$day];
        }

        /**
         * Convertir un entier en mois
         *
         * @param int $month    Le mois à convertir
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
         * @param date $date    La date à convertir
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
         * @param date $debut       La date de début
         * @param int  $heureDebut  L'heure de début du congé
         * @param date $fin         La date de fin du congé
         * @param int  $heureFin    L'heure de fin
         *
         * @return float
        */
        private function getJours($debut, $heureDebut, $fin, $heureFin, $beginto, $during)
        {
            if (true) {
                if ($beginto > 0 && $during > 0) {
                    $resultat = $during / self::WORK_HOUR;
                } else {
                    $secondes = abs(strtotime($debut) - strtotime($fin));
                    $resultat =  ($secondes / self::SECOND_TO_DAY) + self::ONE_DAY;
                    if ($heureDebut == self::APRES_MIDI) {
                        $resultat -= self::DEMI_JOURNEE;
                    }
                    if ($heureFin == self::APRES_MIDI) {
                        $resultat -= self::DEMI_JOURNEE;
                    }
                }
            } else {
                $manager = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $resultat = 0;
                $currentDate = $debut;
                while (strtotime($currentDate) <= strtotime($fin)) {
                    if (!$this->estWeekend($currentDate) && !$this->estFerie($entreprise, $currentDate)) {
                        $resultat++;
                    }
                    $currentDate = date('Y-m-d', strtotime('+1 DAY', strtotime($currentDate)));
                }
            }
            return $resultat;
        }

        /**
         * Afficher le solde de congé
         *
         * @param int  $solde   Solde de congé
         *
         * @return string
        */
        public static function showSoldeConge($solde)
        {
            $j = floor($solde) >= 2 ? 'jours ' : 'jour ';
            $jour = floor($solde) > 0 ? floor($solde) . $j : 0 . $j;
            $heure = floor(8 * ($solde - floor($solde))) > 0 ? floor(8 * ($solde - floor($solde))) . 'h' : '';
            $result =  $jour . $heure;
            return $result;
        }

        /**
         * Ecrire une date de congé complète
         *
         * @param date $debut      La date de début
         * @param int  $heureDebut L'heure de début
         * @param date $fin        La date de fin
         * @param int  $heureFin   L'heure de fin
         *
         * @return string
        */
        private function writeDateConge($debut, $heureDebut, $fin, $heureFin, $beginto, $during)
        {
            $tmpDebut = explode('-', $debut);
            $tmpFin   = explode('-', $fin);
            if (count($tmpDebut) == 3 && count($tmpFin) == 3) {
                if (strtotime($debut) == strtotime($fin)) {
                    $resultat = $tmpDebut[2] . ' ' . $this->getMonthLetter($tmpDebut[1]) . ' ' . $tmpDebut[0];
                    if ($beginto > 0 && $during > 0) {
                        $resultat .= "[à partir de ".$beginto."H avec la durée de ".$during."H]";
                    } else {
                        if ($heureDebut == self::MATIN && $heureFin == self::APRES_MIDI) {
                            $resultat .= "[Demi-journée matin]";
                        } elseif ($heureFin == self::APRES_MIDI && $heureFin == self::SOIR) {
                            $resultat .= "[Demi-journée après-midi]";
                        }
                    }
                    return $resultat;
                } else {
                    if ($heureDebut == self::APRES_MIDI) {
                        $texteDebut = "[après-midi]";
                    } else {
                        $texteDebut = "";
                    }
                    if ($heureFin == self::APRES_MIDI) {
                        $texteFin = "[après-midi]";
                    } else {
                        $texteFin = "";
                    }
                    return $tmpDebut[2] . " " . $this->getMonthLetter($tmpDebut[1]) . " " . $tmpDebut[0] . " " . $texteDebut . " jusqu'au " . $tmpFin[2] . " " . $this->getMonthLetter($tmpFin[1]) . " " . $tmpFin[0] . " " . $texteFin;
                }
            } else {
                return "";
            }
        }

        /**
         * Envoyer un message de notification à un utilisateur
         *
         * @param int    $idCompte  L'identifiant de l'utilisateur
         * @param string $objet     L'objet du message
         * @param string $contenu   Le contenu du message
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
         * @param string  $type             Le type de notification
         * @param object  $object           L'objet en question
         * @param boolean  $rh              Être RH
         * @param boolean  $is_employe      Être employé   
         *
         * @return string
        */
        private function generateMessageContent($type, $object, $rh = false, $is_employe = false)
        {
            $possession = $is_employe ? 'Votre' : 'Son';
            $content    = "<p>Bonjour, </p>";
            if ($type == self::TYPE_REQUEST) {
                $manager  = new ManagerCompte();
                $compte   = $manager->chercher(['idCompte' => $object->getIdCompte()]);
                $manager  = new ManagerConge();
                $conge    = $manager->chercher(['idConge' => $object->getIdConge()]);
                $manager  = new ManagerEmploye();
                $demandeur = $manager->chercher(['idEmploye' => $conge->getIdEmploye()]);
                $content .= "<p>" .
                            "Vous avez une <a href='" . HOST . "manage/" . $compte->getIdentifiant() . "/validation'>validation de demande de congé</a> à effectuer de la part de <span class='titre'>" . $demandeur->getNom() . " " . $demandeur->getPrenom() . "</span> ." .
                            "</p>";
            } elseif ($type == self::TYPE_VALIDATED) {
                $content .= "<p>" .
                            "Votre <a href='" . HOST . "manage/employe/conge'>demande de congé</a> " .
                            "pour le " . $this->writeDateConge($object->getDebut(), $object->getHeureDebut(), $object->getFin(), $object->getHeureFin(), $object->getBeginto(), $object->getDuring()) . " a été validée" .
                            "</p>";
            } elseif ($type == self::TYPE_REJECTED) {
                $link       = $object->getStatut() == self::LEAVE_ABOLISHED ? ' d&#039;annulation' : '';
                $content    .= "<p>" .
                            "Votre <a href='" . HOST . "manage/employe/conge'>demande" . $link . " de congé</a> " .
                            "pour le " . $this->writeDateConge($object->getDebut(), $object->getHeureDebut(), $object->getFin(), $object->getHeureFin(), $object->getBeginto(), $object->getDuring()) . " a été refusée ." .
                            "</br><u>Motif</u>&nbsp;:&nbsp;<italic>\"" . ucfirst($object->getMotifRefus()) . "\"</italic>" .
                            "</p>";
            } elseif ($type == self::TYPE_INFORMATION) {
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $object->getIdEmploye()]);
                $text       = $object->getStatut() == self::LEAVE_ABOLISHED ? ' a annulé son congé prévu le ' : ' sera en congé le ';
                $content    .= "<p>" .
                            "Nous vous informons que " .
                            $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() .
                            $text . $this->writeDateConge($object->getDebut(), $object->getHeureDebut(), $object->getFin(), $object->getHeureFin(), $object->getBeginto(), $object->getDuring()) .
                            "</p>";
            } elseif ($type == self::TYPE_CANCELED) {
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $object->getIdEmploye()]);
                $content .= "<p>" .
                            "Nous vous informons que " .
                            $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() .
                            " a retiré sa <a href='" . HOST . "manage/" . $_SESSION['compte']['identifiant'] . "/validation'>demande de congé</a> " .
                            "prévu le " . $this->writeDateConge($object->getDebut(), $object->getHeureDebut(), $object->getFin(), $object->getHeureFin(), $object->getBeginto(), $object->getDuring()) .
                            "</p>";
            } elseif ($type == self::TYPE_ABOLISHED) {
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $object->getIdEmploye()]);
                $name       = $is_employe ? "vous avez" : $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() . " a";
                $link       = $rh ? "sa <a href='" . HOST . "manage/entreprise/validation'>demande d'annulation de congé</a> " : "votre congé ";
                $content .= "<p>" .
                                "Nous vous informons que " . $name . " annulé " . $link . "prévu le "
                                . $this->writeDateConge($object->getDebut(), $object->getHeureDebut(), $object->getFin(), $object->getHeureFin(), $object->getBeginto(), $object->getDuring()) .
                            " .</p>";
            } elseif ($type == self::TYPE_SETTLED) {
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $object->getIdEmploye()]);
                $is_employe ? $name = "vous avez" : $name = $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() . " a";
                $content .= "<p> Nous vous informons que <b>" . $name . "</b>" . " régularisé " . lcfirst($possession) . " absence par congé du <b>" . $this->writeDateConge($object->getDebut(), $object->getHeureDebut(), $object->getFin(), $object->getHeureFin(), $object->getBeginto(), $object->getDuring()) . "</b></p>";
            }
            if (!isset($demandeur)) {
                $demandeur = null;
            }
            $manager    = new ManagerStockConge();
            $idEmploye  = $demandeur ? $demandeur->getIdEmploye() : $object->getIdEmploye();
            $duree      = $manager->chercher(['idEmploye' => $idEmploye, 'annee' => date('Y')])->getDuree();
            $content    .= "<p>" . $possession . " solde de congé est <b>" . $this->showSoldeConge($duree) . "</b> .</p>" ;
            return $content;
        }

        /**
         * Envoyer un email de notification à un employé
         *
         * @param object $object    L'employé ou l'entreprise concerné
         * @param string $content   Le contenu du mail
         *
         * @return empty
        */
        private function sendMailNotification($destinataire, $subject, $content, $is_info = false)
        {
            if ($is_info) {
                $manager    = new ManagerEmailContact();
                $from       = 'HumaNexus <' . strtolower($manager->chercher(['type' => 'information'])->getEmail()) . '>';
            } else {
                $from       = strtolower($_SESSION['user']['email']);
            }
            $to         = $destinataire->getEmail();
            $headers[]  = 'MIME-Version: 1.0';
            $headers[]  = 'Content-type: text/html; charset=iso-8859-1';
            $headers[]  = "From: " . $from;
            $content    .= '<br><br> Cordialement,<br><br>L&apos;équipe <a href="https://hco.mg/">Human Cart&apos;Office</a>';
            mail($to, $subject, $content, implode("\r\n", $headers));
        }

        /**
         * Générer une phrase qui annonce les validateurs
         *
         * @param array $validateurs    Une liste des valisateurs
         *
         * @return string
        */
        private function generateSentenceValidation($validateurs)
        {
            $phrase = " ";
            if (count($validateurs) == 1) {
                $phrase .= "<b>" . $validateurs[0]->getNom() . " " . $validateurs[0]->getPrenom() . "</b>";
                $phrase .= " a déjà validé cette demande.";
            } elseif (count($validateurs) > 1) {
                $first = true;
                $index = 1;
                foreach ($validateurs as $validateur) {
                    if ($first) {
                        $phrase .= "<b>" . $validateur->getNom() . " " . $validateur->getPrenom() . "</b>";
                        $first = false;
                    } else {
                        if ($index == count($validateurs)) {
                            $phrase .= " et <b>" . $validateur->getNom() . " " . $validateur->getPrenom() . "</b>";
                        } else {
                            $phrase .= ", <b>" . $validateur->getNom() . " " . $validateur->getPrenom() . "</b>";
                        }
                    }
                    $index++;
                }
                $phrase .= " ont déjà validé cette demande.";
            }
            return $phrase;
        }

        /**
         * Générer un contenu pour mail concernant les congés
         *
         * @param int    $type          Le type de message
         * @param object $demande       La demande de congé
         *
         * @return string
        */
        private function generateMailContent($type, $demande, $rh = false, $is_employe = false)
        {
            $periode    = "";
            $journee    = "";
            /**@changelog 2022-04-01 [OPT] (Lansky) Ajouter la demande de congé en heure de la journnée */
            $poss       = $is_employe ? 'votre' : 'son';
            if ($demande->getDuring() > 0 && $demande->getBeginto() > 0) {
                $periode    = "à partir de " . $demande->getBeginto() . "H, durée de " . $demande->getDuring() . "H";
                $som        = $demande->getDuring() + $demande->getBeginto();
                if ($som < 12) {
                    $heureFin   = "[matin]";
                } elseif ($som == 12) {
                    $heureFin   = "[midi]";
                } elseif ($som > 12 && $som < 18) {
                    $heureFin   = "[après-midi]";
                } elseif ($som >= 18) {
                    $heureFin   = "[soir]";
                }
                if ($demande->getBeginto() < 12) {
                    $journee    = "matin";
                    $heureDebut = "[matin]";
                } elseif ($demande->getBeginto() == 12) {
                    $journee    = "midi";
                    $heureDebut = "[midi]";
                } elseif ($demande->getBeginto() > 12 && $demande->getBeginto() < 18) {
                    $journee    = "après-midi";
                    $heureDebut = "[après-midi]";
                } elseif ($demande->getBeginto() >= 18) {
                    $journee    = "soir";
                    $heureDebut = "[soir]";
                }
            } else {
                if ($demande->getHeureDebut() == self::APRES_MIDI) {
                    $heureDebut = "[midi]";
                    $periode    = "après-midi";
                } elseif ($demande->getHeureDebut() == self::MATIN) {
                    $heureDebut = "[matin]";
                    $periode    = "matin";
                } else {
                    $heureDebut = "";
                }
                if ($demande->getHeureFin() == self::APRES_MIDI) {
                    $heureFin = "[midi]";
                    $periode  = "matin";
                } elseif ($demande->getHeureFin() == self::SOIR) {
                    $heureFin = "[soir]";
                    $periode  = "après-midi";
                } else {
                    $heureFin = "";
                }
                if ($demande->getHeureDebut() == self::MATIN && $demande->getHeureFin() == self::SOIR) {
                    $journee  = "";
                    $periode  = "";
                } else{
                    $journee  = "de demi-journée " . $heureDebut;
                }
            }
            $manager        = new ManagerValidationConge();
            $validations    = $manager->lister(['idConge' => $demande->getIdConge(), 'statut' => self::LEAVE_VALIDATED]);
            $validateurs    = array();
            foreach ($validations as $validation) {
                $manager    = new ManagerCompte();
                $compte     = $manager->chercher(['idCompte' => $validation->getIdCompte()]);
                $manager    = new ManagerEmploye();
                $tmpEmploye = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
                if ($tmpEmploye != null) {
                    $validateurs[] = $tmpEmploye;
                }
            }
            $content = "<p>Bonjour, </p>";
            if ($demande->getDebut() != $demande->getFin()) {
                $aDay       = '';
                $beginTime  = $heureDebut . "</b> jusqu'au <b>" . date("d/m/Y", strtotime($demande->getFin())) . " " . $heureFin;
            } else {
               $aDay        = $journee;
                $beginTime  = $periode;
            }
            if ($type == self::TYPE_REQUEST) {
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                $manager    = new ManagerStockConge();
                $duree      = $manager->chercher(['idEmploye' =>  $employe->getIdEmploye() ,'annee' => date('Y')])->getDuree();
                $possession = $rh ? 'Votre' : 'Son';
                if ($demande->getBeginto() > 0) { 
                    $addContent = "de " . $demande->getDuring() . " H le <b>" . date("d/m/Y", strtotime($demande->getDebut()))
                                    . "</b> en raison de <b> '" . $demande->getRaison() . "' .</b><br> Son congé commence à "
                                    . $demande->getBeginto() . "H, il doit-être au bureau à " . $som . "H" ; 
               } else {
                    $addContent =   $aDay . " pour le <b>" . date("d/m/Y", strtotime($demande->getDebut())) . " " . $beginTime
                                    . "</b> en raison de <b> '" . $demande->getRaison() . "'.</b>";
                }
                $content .= "<p><b>" . $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom()
                                . "</b>" . " a demandé un congé " . $addContent . "<br>" . $possession . " solde de congé est <b>"
                                . $this->showSoldeConge($duree) . "</b> .</p>" . $this->generateSentenceValidation($validateurs)
                                . "<br><p>Veuillez Consultez la demande <a href=" . HOST . "manage/"
                                . $_SESSION['compte']['identifiant'] . "/validation>ici</a></p>";
            } elseif ($type == self::TYPE_VALIDATED) {
                $content .= "<p>Votre demande de congé pour le <b>" . date("d/m/Y", strtotime($demande->getDebut()))
                            . " " . $beginTime . "</b> a été validée .</p>"
                            . "<br><p>Veuillez consulter l'historique de vos congés <a href=" . HOST . "manage/employe/conge>ici</a></p>";
            } elseif ($type == self::TYPE_REJECTED) {
                $content .= "<p>Votre demande de congé pour le <b>" . date("d/m/Y", strtotime($demande->getDebut())) . " "
                . $beginTime . "</b> a été refusée en raison de <b>'" . $demande->getMotifRefus() . "'</b>.</p>"
                ."<br><p>Veuillez consulter l'historique de vos congés <a href=" . HOST . "manage/employe/conge>ici</a></p>";
            } elseif ($type == self::TYPE_INFORMATION) {
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                if ($demande->getStatut() == self::LEAVE_VALIDATED) {
                    $content .= "<p> Nous vous informons que <b>" . $employe->getCivilite() . " " . $employe->getNom() . " "
                                    . $employe->getPrenom() . "</b>" . " sera en congé le <b>"
                                    . date("d/m/Y", strtotime($demande->getDebut())) . " " . $beginTime
                                    . "</b> en raison de <b>'" . $demande->getRaison() . "'</b></p>";
                } elseif ($demande->getStatut() == self::LEAVE_REJECTED) {
                    $content .= "<p> Nous vous informons que la demande de congé de <b>" . $employe->getCivilite() . " "
                                    . $employe->getNom() . " " . $employe->getPrenom() . "</b>" . " prévu le <b>"
                                    . date("d/m/Y", strtotime($demande->getDebut())) . " " . $beginTime
                                    . "</b> en raison de <b>'" . $demande->getRaison() . "'</b> a été refusée pour cause de <b>'"
                                    . $demande->getMotifRefus() . "'</b>.</p>";
                } elseif ($demande->getStatut() == self::LEAVE_ABOLISHED) {
                        if ($demande->getDebut() != $demande->getFin()) {
                            $text = " " . $heureDebut . "</b> jusqu'au <b>" . date("d/m/Y", strtotime($demande->getFin())) . " " . $heureFin;
                        } else {
                            $text = " " . $periode;
                        }
                        $content .= "<p> Nous vous informons que <b>" . $employe->getCivilite() . " " . $employe->getNom() . " "
                                        . $employe->getPrenom() . "</b> a annulé son congé prévu le <b>"
                                        . date("d/m/Y", strtotime($demande->getDebut())) . $text . "</b> en raison de <b>'"
                                        . $demande->getRaison() . "'</b> .</p>";
                }
            } elseif ($type == self::TYPE_CANCELED) {
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                $content .= "<p> Nous vous informons que <b>" . $employe->getCivilite() . " " . $employe->getNom() . " "
                            . $employe->getPrenom() . "</b>" . " a retiré sa demande de congé prévu le <b>"
                            . date("d/m/Y", strtotime($demande->getDebut())) . " " . $beginTime . "</b></p>";
            } elseif ($type == self::TYPE_ABOLISHED) {
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                if ($demande->getDebut() != $demande->getFin()) {
                    $text = $heureDebut . "</b> jusqu'au <b>" . date("d/m/Y", strtotime($demande->getFin())) . " " . $heureFin . "</b> .</p>";
                } else {
                    $text = $periode . " .</p>";
                }
                $name       = $is_employe ? "vous avez" : $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() . " a";
                $link       = $rh ? "</br> Veuillez consulter <a href=" . HOST . "manage/entreprise/"
                                . " title='annuler demande de congé'>ici</a>" : "";
                $content .= "<p> Nous vous informons que <b>" . $name . "</b>" . " annulé " . $poss . " congé du <b>"
                            . date("d/m/Y", strtotime($demande->getDebut())) . " " . $text . $link;
            } elseif ($type == self::TYPE_SETTLED) {
                /**@changelog 2022-04-01 [OPT] (Lansky) Envoye notification reglé par le congé l'absence du salarié */
                $manager    = new ManagerEmploye();
                $employe    = $manager->chercher(['idEmploye' => $demande->getIdEmploye()]);
                $text = $demande->getDebut() != $demande->getFin() ? $heureDebut . "</b> jusqu'au <b>"
                            . date("d/m/Y", strtotime($demande->getFin())) . " " . $heureFin . "</b></p>" : $periode;
                $name = $is_employe ? "vous avez": $employe->getCivilite() . " " . $employe->getNom() . " "
                                                    . $employe->getPrenom() . " a";
                $content    .= "<p> Nous vous informons que <b>" . $name . "</b>" . " régularisé " . $poss . " absence par congé du <b>"
                                . date("d/m/Y", strtotime($demande->getDebut())) . " " . $text . " .</br>" ;
            } 
            return $content;
        }

        /**
         * Activer une tâche planifiée
         *
         * @param string $nom           Le nom de la tâche
         * @param string $description   La description de la tâche
         * @param string $script        Le script de la tâche
         *
         * @return empty
        */
        private function activerTacheAutomatique($nom, $description, $script)
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
                    $newCronTab[] = '# nom: ' . $nom ;
                    $newCronTab[] = '# description: ' . $description ;
                    $newCronTab[] = $script;
                    $toBeCopied = true;
                } elseif ($isSection == true) {
                    $motsLigne = explode(' ', $ligne);
                    if ($motsLigne[0] == '#') {
                        if ($motsLigne[1] == 'nom:' && $motsLigne[2] == $nom) {
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
                $newCronTab[] = '# nom: ' . $nom ;
                $newCronTab[] = '# description: ' . $description ;
                $newCronTab[] = $script;
                $newCronTab[] = $finFichier;
                $newCronTab[] = "\n";
            }
            if (file_exists('Tmp/tmpFile')) {
                unlink('Tmp/tmpFile');
            }
            $fichier = fopen('Tmp/tmpFile', 'w');
            fwrite($fichier, implode("\n", $newCronTab));
            fclose($fichier);
            exec('crontab');
            exec('crontab Tmp/tmpFile');
        }

        /**
         * Désactiver une tache automatique
         *
         * @param string $nom   Le nom de la tâche concernée
         *
         * @return empty
        */
        private function desactiverTacheAutomatique($nom)
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
                        if ($motsLigne[1] == 'nom:') {
                            if ($motsLigne[2] == $nom) {
                                $toBeCopied = false;
                            } else {
                                $toBeCopied = true;
                            }
                        }
                    }
                }
                if ($toBeCopied == true) {
                    $newCronTab[] = $ligne;
                }
            }
            $newCronTab[] = "\n";
            if (file_exists('Tmp/tmp')) {
                unlink('Tmp/tmp');
            }
            $fichier = fopen('Tmp/tmp', 'w');
            fwrite($fichier, implode("\n", $newCronTab));
            fclose($fichier);
            exec('crontab');
            exec('crontab Tmp/tmp');
        }

        /**
         * Vérifier les tâches automatique activées
         *
         * @param string $nom   Le nom de la tâche concernée
         *
         * @return boolean
        */
        private function isActiveTask($nom)
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
                        if ($motsLigne[1] == "nom:" && $motsLigne[2] == $nom) {
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        /**
         * Voir la configuration des tâches planifiées
         *
         * @param array $parameters     Les critères des données à afficher
         *
         * @return array
        */
        public function voirTachePlanifiee($parameters)
        {
            $scripts    = array();
            $dir        = opendir(CRON_DIR);
            while($file = readdir($dir)) {
                if($file != '.' && $file != '..' && !is_dir($dir.$file)) {
                    $scripts[] = $file;
                }
            }
            closedir($dir);
            $manager   = new ManagerTacheAutomatique();
            $taches    = $manager->lister();
            $resultats = array();
            foreach ($taches as $tache) {
                $tmp['tache']       = $tache;
                $tmp['isActive']    = $this->isActiveTask($tache->getNom());
                $resultats[]        = $tmp;
            }
            return [
                'resultats' => $resultats,
                'scripts'   => $scripts
            ];
        }

        /**
         * Récupérer une tâche automatique
         *
         * @param array $parameters     Les critères de la tâche
         *
         * @return empty
        */
        public function getTachePlanifiee($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerTacheAutomatique();
                $tache = $manager->chercher(['idTacheAutomatique' => $parameters['idTacheAutomatique']]);
                if ($tache != null) {
                    echo json_encode($tache->toArray());
                    exit();
                }
            }
        }

        /**
         * Mettre à jour une tâche planifiée
         *
         * @param array $parameters     Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourTachePlanifiee($parameters)
        {
            if (!empty($parameters)) {
                if (empty($parameters['idTacheAutomatique'])) {
                    $manager   = new ManagerTacheAutomatique();
                    $manager->ajouter([
                        'nom'         => $parameters['nom'],
                        'description' => $parameters['description'],
                        'script'      => $parameters['script'],
                        'periode'     => $parameters['periode'],
                        'codePeriode' => $parameters['codePeriode']
                    ]);
                } else {
                    $manager   = new ManagerTacheAutomatique();
                    $manager->modifier([
                        'idTacheAutomatique' => $parameters['idTacheAutomatique'],
                        'nom'                => $parameters['nom'],
                        'description'        => $parameters['description'],
                        'script'             => $parameters['script'],
                        'periode'            => $parameters['periode'],
                        'codePeriode'        => $parameters['codePeriode']
                    ]);
                    $_SESSION['info']['success'] = "Modification effectuée avec succès !";
                }
            }
        }

        /**
         * Supprimer une tâche planifiée
         *
         * @param array $parameters     Les données à supprimer
         *
         * @return empty
        */
        public function supprimerTachePlanifiee($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerTacheAutomatique();
                $manager->supprimer([
                    'idTacheAutomatique'  => $parameters['idTacheAutomatique']
                ]);
            }
        }

        /**
         * Activer une tâche planifiée
         *
         * @param array $parameters     Les données à supprimer
         *
         * @return empty
        */
        public function activerTachePlanifiee($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerTacheAutomatique();
                $tache     = $manager->chercher(['idTacheAutomatique' => $parameters['idTacheAutomatique']]);
                if ($tache != null) {
                    $script = $tache->getCodePeriode() . ' ' . self::SOURCE . $tache->getScript();
                    $this->activerTacheAutomatique($tache->getNom(), $tache->getDescription(), $script);
                }
            }
        }

        /**
         * Désactiver une tâche planifiée
         *
         * @param array $parameters     Les données à supprimer
         *
         * @return empty
        */
        public function desactiverTachePlanifiee($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerTacheAutomatique();
                $tache     = $manager->chercher(['idTacheAutomatique' => $parameters['idTacheAutomatique']]);
                if ($tache != null) {
                    $this->desactiverTacheAutomatique($tache->getNom());
                }
            }
        }

        /**
         * Activer attente Congé
         *
         * @param array $paramaters     Les données à activer
         *
         * @return empty
        */
        public function activerAttenteConge($parameters)
        {
            if (!empty($parameters)) {
                $manager     = new ManagerEntreprise();
                $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager     = new ManagerParametreConge();
                $parametre   = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if ($parametre != null) {
                    $retour  = $manager->modifier([
                        'idParametreConge' => $parametre->getIdParametreConge(),
                        'attente'          => self::ATTENTE_ACTIVE
                    ]);
                    if ($retour != null) {
                        echo self::YES;
                        exit();
                    }
                }
            }
            echo self::NO;
        }

        /**
         * Désactiver attente Congé
         *
         * @param array $paramaters     Les données à desactiver
         *
         * @return empty
        */
        public function desactiverAttenteConge($parameters)
        {
            if (!empty($parameters)) {
                $manager     = new ManagerEntreprise();
                $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager     = new ManagerParametreConge();
                $parametre   = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if ($parametre != null) {
                    $retour  = $manager->modifier([
                        'idParametreConge' => $parametre->getIdParametreConge(),
                        'attente'          => self::ATTENTE_DESACTIVE
                    ]);
                    if ($retour != null) {
                        echo self::YES;
                        exit();
                    }
                }
            }
            echo self::NO;
        }

        /**
         * Changer le processus de demande de congé
         *
         * @param array $paramaters     Les données à modifier
         *
         * @return empty
        */
        public function changerProcessusConge($parameters)
        {
            if (!empty($parameters)) {
                $manager     = new ManagerEntreprise();
                $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager     = new ManagerParametreConge();
                $parametre   = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if ($parametre != null) {
                    $retour  = $manager->modifier([
                        'idParametreConge' => $parametre->getIdParametreConge(),
                        'processus'        => $parameters['processus'],
                        'niveau'           => $parameters['niveau']
                    ]);
                    if ($retour->getProcessus() == $parameters['processus']) {
                        echo $retour->getProcessus();
                        exit();
                    }
                }
            }
            echo self::NO;
            exit();
        }

        /**
         * Changer la méthode de calcul de congé
         *
         * @param array $parameters     Les données à modifier
         *
         * @return empty
        */
        public function changerCalculConge($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager    = new ManagerParametreConge();
                $parametre  = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if ($parametre != null) {
                    $retour = $manager->modifier([
                        'idParametreConge' => $parametre->getIdParametreConge(),
                        'calcul' => $parameters['calcul']
                    ]);
                    if ($retour->getCalcul() == $parameters['calcul']) {
                        echo $retour->getCalcul();
                        exit();
                    }
                }
            }
            echo self::NO;
            exit();
        }

        /**
         * Calculer l'ancienneté d'un employé
         *
         * @param int idEmploye     L'identifiant de l'employé
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
            $resultat                   = $this->getDuree($embauche, $dateFin);
            $resultats['dateEmbauche']  = $embauche;
            $resultats['dateDebauche']  = $debauche;
            $resultats['annees']        = intval($resultat / 12);
            $resultats['mois']          = intval($resultat % 12);
            return $resultats;
        }

        /**
         * Obtenir la date d'embauche d'un employé
         *
         * @param int $idEmploye    L'identfiant de l'employé
         *
         *  @return date
        */
        private function getDateEmbauche($idEmploye)
        {
            $manager          = new ManagerContratEmploye();
            $contratEmploye   = $manager->chercher(['idEmploye' => $idEmploye, 'statut' => self::VALIDATED]);
            if ($contratEmploye == null) {
                $contratEmployes = $manager->lister(['idEmploye' => $idEmploye, 'statut' => self::EXPIRED]);
                if ($contratEmployes != null) {
                    $contratEmploye  = $contratEmployes[0];
                }
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
         * @param int $idEmploye    L'identifiant de l'employé
         *
         * @return date
        */
        private function getDateDebauche($idEmploye)
        {
            $manager          = new ManagerContratEmploye();
            $contratEmploye   = $manager->chercher(['idEmploye' => $idEmploye, 'statut' => self::VALIDATED]);
            if ($contratEmploye == null) {
                $contratEmployes = $manager->lister(['idEmploye' => $idEmploye, 'statut' => self::EXPIRED]);
                if ($contratEmployes != null) {
                    $contratEmploye  = $contratEmployes[0];
                }
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
                        if (!is_null($typeContrat)) {
                            if (strtolower($typeContrat->getDesignation()) == strtolower(self::CDI)
                                && $contratEmploye->getStatut() == self::VALIDATED
                                   && strtotime($contratEmploye->getDateFin()) == strtotime(date("0000-00-00"))
                            ) {
                                return null;
                            } else {
                                $contratEmploye = $tmp;
                            }
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
         * Calculer la différence entre 2 dates
         *
         * @param date $date1  La date1
         * @param date $date2  La date2
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
         * Annulation de la demande de congé lorsque celui-ci a été validée
         *
         * @param $parameters 
         *
         * @return int nombre de mois
        */
        public function annulerDemandeConge($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerConge();
                $conge      = $manager->chercher(['idConge' => $parameters['idConge']]);
                $manager->modifier(['idConge' => $conge->getIdConge(), 'statut' => self::LEAVE_ABOLISHED]);
                $manager    = new ManagerEmploye();
                $demandeur  = $manager->chercher(['idEmploye' => $conge->getIdEmploye()]);
                $status     = $_SESSION['compte']['identifiant'] == 'employe' ? self::LEAVE_PROPOSED : self::LEAVE_ABOLISHED;
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $demandeur->getIdEntreprise()]);
                $manager    = new ManagerValidationConge();
                $resultat   = $manager->ajouter([
                    'idCompte'  => $entreprise->getIdCompte(),
                    'idConge'   => $conge->getIdConge(),
                    'statut'    => $status
                ]);
                $content     = $this->generateMessageContent(self::TYPE_ABOLISHED, $conge, true, false);
                $contentMail = $this->generateMailContent(self::TYPE_ABOLISHED, $conge, true, false);
                $idMessage   = $this->sendMessageNotification($entreprise->getIdCompte(), 'Annulation de demande de congé de la part de <span class="titre">' . $demandeur->getCivilite() . ' ' . $demandeur->getNom() . ' ' . $demandeur->getPrenom() . '</span>', $content);
                $manager     = new ManagerMessage();
                $manager->modifier([
                    'idMessage' => $idMessage,
                    'aFaire'    => self::NO
                ]);
                $subjectMail = "Demande de validation d'annulation de congé de la part de " . $demandeur->getNom() . ' ' . $demandeur->getPrenom();
                $this->sendMailNotification($entreprise, $subjectMail, $contentMail, false); // Le salarié est l'expéditeur
                $manager = new ManagerValidationConge();
                $retour = $manager->modifier([
                    'idValidationConge' => $resultat->getIdValidationConge(),
                    'idMessage'         => $idMessage
                ]);
            }
        }

        /**
         * Valider une demande de congé
         *
         * @param array $parameters     Les critères de la demande à valider
         *
         * @return empty
        */
        public function validerAnnulationConge($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerValidationConge();
                $validation = $manager->chercher(['idValidationConge' => $parameters['idValidationConge']]);
                $manager    = new ManagerCompte();
                $compte     = $manager->chercher(['idCompte' => $validation->getIdCompte()]);
                if ($compte->getIdCompte() == $_SESSION['user']['idCompte']) {
                    $manager   = new ManagerValidationConge();
                    $resultat  = $manager->modifier([
                        'idValidationConge' => $validation->getIdValidationConge(),
                        'statut'            => self::LEAVE_VALIDATED
                    ]);
                    $resultat  = $manager->chercher(['idValidationConge' => $validation->getIdValidationConge()]);
                    $manager   = new ManagerMessage();
                    $message   = $manager->chercher(['idMessage' => $resultat->getIdMessage()]);
                    if ($message != null) {
                        $manager->modifier([
                            'idMessage'   => $message->getIdMessage(),
                            'statut'      => self::SEEN
                        ]);
                    }
                    if ($compte->getIdentifiant() == self::COMPTE_ENTREPRISE) {
                        if ($resultat != null) {
                            $manager   = new ManagerConge();
                            $conge     = $manager->chercher(['idConge' => $validation->getIdConge()]);
                            $resultat  = $manager->modifier([
                                'idConge'  => $conge->getIdConge(),
                                'statut'   => self::LEAVE_ABOLISHED
                            ]);
                            if ($resultat->getStatut() == self::LEAVE_ABOLISHED) {
                                $conge = $manager->chercher(['idConge' => $conge->getIdConge()]);
                                $manager = new ManagerEmploye();
                                $employe = $manager->chercher(['idEmploye' => $conge->getIdEmploye()]);
                                $manager = new ManagerStockConge();
                                $year    = date('Y');
                                $stock   = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'annee' => $year]);
                                $jours   = $this->getJours($conge->getDebut(), $conge->getHeureDebut(), $conge->getFin(), $conge->getHeureFin(), $conge->getBeginto(), $conge->getDuring());
                                $manager->modifier([
                                    'idStockConge' => $stock->getIdStockConge(),
                                    'duree'        => $stock->getDuree() + $jours
                                ]);
                                $content = $this->generateMessageContent(self::TYPE_ABOLISHED, $conge, false, true);
                                $mailContent = $this->generateMailContent(self::TYPE_ABOLISHED, $conge, false, true);
                                $this->sendMessageNotification($employe->getIdCompte(), "Demande d'annulation de congé validée", $content);
                                $this->sendMailNotification($employe, "Demande d'annulation de congé validée", $mailContent);
                                $_SESSION['info']['success'] = "La demande a été validée avec succès !";
                                $tmpEmploye = $employe;
                                $this->notifyAllSupperior($tmpEmploye, self::TYPE_INFORMATION, $conge, $employe, false, false, false);
                            }
                        }
                    }
                }
            }
        }
        
        /**
         * Vérifier toutes les demandes de congés du salarié si identiquent à celle de nouvelle demande
         *
         * @changelog 26/04/2022 [OPT] (Lansky) Vérifier que la date de demande de congé est pas la même qu'avant
         * 
         * @param array $parameters     Les critères des données à afficher
         *
         * @return array
        */
        private function verifyDateConge($parameters)
        {
            extract($parameters);
            $response   = array();
            $manager    = new ManagerConge();
            $tmpFin = $debut > date('Y-m-d', strtotime($fin . '-1 days')) ? date('Y-m-d', strtotime($fin . '-1 days')) : $fin;
            $result     = $manager->lister([
                'idEmploye' => $_SESSION['user']['idEmploye'],
                'debut'     => 'BETWEEN "' . $debut . '" AND "' . $tmpFin
                                . '" OR fin BETWEEN "' . $debut . '" AND "' . $tmpFin . '"'
            ]);
            // $result     = $manager->lister([
            //     'idEmploye' => $_SESSION['user']['idEmploye'],
            //     'debut'     => 'BETWEEN "' . $debut . '" AND "' . $fin
            //                     . '" OR fin BETWEEN "' . $debut . '" AND "' . $fin . '"'
            // ]);
            foreach ($result as $conge) {
                if ($conge->getStatut() <= self::VALIDATED || $conge->getStatut() <= self::PROPOSED) {
                    if ($debut > $conge->getDebut()) {
                        if ($debut < $conge->getFin()) { // NON
                            $response[] = "Votre congé est prévu le" . $conge->getDebut(); 
                        } elseif ($debut == $conge->getFin()) {
                            if ($heureDebut < $conge->getHeureFin()) { // NON
                                switch ($conge->getHeureFin()) {
                                    case self::MATIN:
                                        $jour = '[matin]';
                                        break;
                                    case self::APRES_MIDI:
                                        $jour = '[après-midi]';
                                        break;
                                    case self::SOIR:
                                        $jour = '[soir]';
                                        break;
                                    default:
                                        $jour = '[indéfini]';
                                        break;
                                }
                                $response[] = "Votre congé se treminera du " . $conge->getFin() . " " . $jour;
                            } /* else { // OK } */
                        } /* else {  // OK } */
                    } elseif ($debut < $conge->getDebut()) {
                        if ($fin > $conge->getDebut()) { // NON
                            $response[] = "Votre congé débutera du" . $conge->getDebut() . " jusqu'au " . $conge->getfin();
                        } elseif ($fin == $conge->getDebut()) {
                            if ($conge->getHeureDebut() == self::MATIN) { // NON
                                $response[] = "Votre congé est prévu le" . $conge->getDebut();
                            } /* else { OK } */
                        } /* else { // OK } */
                    } else {
                        if ($fin != $conge->getFin()) { // NON
                            $response[] = "Vous serez en congé du " . $conge->getFin();
                        } else {
                            if ($conge->getHeureFin() != self::APRES_MIDI) { // NON
                                $response[] = "Votre congé se treminera le soir de " . $conge->getFin();
                            } /* else { // OK } */
                        }
                    }
                }
            }
            return $response;
        }

        /**@changelog 2022-08-02 [OPT] (Lansky) Vérifier le jour debut de la demande du congé est un jour férié initié par l'entreprise */
        /**
         * Vérifier si une date est un jour férié
         *
         * @param date $date    La date en question
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

        /**@changelog 2022-12-20 [OPT] (Lansky) Calculer le nombre de la démande de congé annuel d'un salarié */
        /**
         * Récupérer l'historique du congé d'utilisateur
         *
         * @param object $employe   Le salarié en question
         * @param string $annee     L'année  
         * @param int $type         Le type du congé pour le filtre
         *
         * @return array
        */
        private function getHistoricCongeUser($employe, $annee, $type)
        {
            $manager    = new ManagerStockConge();
            $stock      = $manager->chercher([
                'idEmploye' => $employe->getIdEmploye(),
                'annee'     => $annee
            ]);
            $lastStock  = $manager->chercher([
                'idEmploye' => $employe->getIdEmploye(),
                'annee'     => (int)$annee - 1
            ]);
            $dateEmbauche = date('Y', strtotime($this->getDateEmbauche($employe->getIdEmploye())));
            $month      = ($annee == date('Y')) ? date('m') : (($dateEmbauche == $annee) ? (12 - (int)date('m', strtotime($this->getDateEmbauche($employe->getIdEmploye())))) : 12);
            if ($lastStock == null) {
                $lastStock = $manager->initialiser();
                $lastStock->setDuree(0);
            }
            $stockAnnuel = ($stock != null) ? $lastStock->getDuree() +  self::STOCK_MENSUEL * $month : 0;
            if (is_null($stock)) {
                $stock=$manager->initialiser();
                $stock->setIdStockConge(0);
                $stock->setDuree(0);
                $stock->setAnnee(date('Y'));
                $stock->setIdEmploye($employe->getIdEmploye());
            }
            $refus      = 0;
            $attente    = 0;
            $valide     = 0;
            $refusNbr   = 0;
            $attenteNbr = 0;
            $valideNbr  = 0;
            $response   =  [
                array(
                    'refus'     => $refus,
                    'nbr'       => $refusNbr 
                ),
                array(
                    'attente'   => $attente,
                    'nbr'       => $attenteNbr
                ),
                array(
                    'valide'    => $valide,
                    'nbr'       => $valideNbr
                )
            ];
            foreach ($this->getDataConge(["annee" => $annee, "type" => $type], $employe)['demandes'] as $value) {
                switch ($value->getStatut()) {
                    case self::LEAVE_REJECTED :
                        $refusNbr++;
                        $refus += $this->calculateCongeDuring($value);
                    break;

                    case self::LEAVE_PROPOSED :
                        $attenteNbr++;
                        $attente += $this->calculateCongeDuring($value);
                    break;

                    case self::LEAVE_VALIDATED:
                        $valideNbr++;
                        $valide += $this->calculateCongeDuring($value);
                    break;

                    case self::LEAVE_ABOLISHED:
                        $manager            = new ManagerValidationConge();
                        $validationState    =  $manager->lister(['idConge' => $value->getIdConge()]);
                        if ($validationState[0]->getStatut() == self::LEAVE_REJECTED) {
                            $valideNbr++;
                            $valide += $this->calculateCongeDuring($value);
                        } elseif($validationState[0]->getStatut() == self::LEAVE_PROPOSED) {
                            $refusNbr++;
                            $refus += $this->calculateCongeDuring($value);
                        }
                    break;
                 }  
                $response  = [
                    [
                        'refus'     => $refus,
                        'nbr'       => $refusNbr 
                    ],
                    [
                        'attente'   => $attente,
                        'nbr'       => $attenteNbr
                    ],
                    [
                        'valide'    => $valide,
                        'nbr'       => $valideNbr
                    ]
                ];
            }
            $response[] = ['stockAnnuel' => $stockAnnuel];
            return array('stock' => $stock, 'conge' => $response);
        }

        /**
         * Notifier tous les supérieur hiérarchique du salarié
         *
         * @changelog 2023-05-05 [OPTIM] (Lansky) Ajout de la méthode
         * 
         * @param object $tmpEmploye    Le salarié temporaire a trouver son suppérieur
         * @param string $typeConge     Le type du congé
         * @param object $conge         Le congé à traiter
         * @param object $applicant     L'applicant en question
         * @param boolean $is_info      La notification est-elle une information
         * @param boolean $rh           L'identifiant est-elle final
         * @param boolean $is_employe   L'identifiant est-elle l'appliquant
         *
         * @return empty
        */
        private function notifyAllSupperior($tmpEmploye, $typeConge, $conge, $applicant, $is_info, $rh, $is_employe)
        {
            $content        = $this->generateMessageContent($typeConge, $conge, $rh, $is_employe);
            $mailContent    = $this->generateMailContent($typeConge, $conge,$rh, $is_employe);
            $factory        = new PublicFonctions();
            $addText        = $conge->getStatut() == self::LEAVE_ABOLISHED ? "demande d'annulation de" : "";
            $objectMail     = "Validation de " . $addText . " congé de " . $applicant->getCivilite() . " " . $applicant->getNom() . " " . $applicant->getPrenom();
            $objectMessage  = "Information de " . $addText . " congé";
            while ($tmpEmploye->getChefHierarchique() != self::NO) {
                $chef = $factory->getChief($tmpEmploye, $applicant);
                $this->sendMessageNotification($chef->getIdCompte(), $objectMessage, $content);
                $this->sendMailNotification($chef, $objectMail, $mailContent, $is_info);
                $tmpEmploye = $chef;
            }
        }

        /**
         * Revalider une demande de congé si cette dernière a été réfusée
         *
         * @changelog 2023-06-29 [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param array $parameters    Le paramètre 
         * @param boolean $is_employe   L'identifiant est-elle l'appliquant
         *
         * @return empty
        */
        public function validerRevalidationConge($parameters)
        {
            $managerConge = new ManagerConge();
            $managerConge->modifier([
                'idConge'       => $parameters['idConge'],
                'statut'        => self::LEAVE_PROPOSED,
                'motifRefus'    =>  $parameters['motifRefus'] . "Mais " . $_SESSION['user']['nom'] . " revalide cette demande"
            ]);
            self::validerValidationConge($parameters);
        }

        /**
         * Changer le parametre du congé
         *
         * @changelog 2023-08-28 [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param array $parameters     Les données à modifier
         *
         * @return empty
        */
        public function changerParametreConge($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $manager    = new ManagerParametreConge();
                $parametre  = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if ($parametre != null) {
                    extract($parameters);
                    $retour = $manager->modifier([
                        'idParametreConge'  => $parametre->getIdParametreConge(),
                        'deduct_weekend'    => $deduct,
                        'notify_absence'    => $notify
                    ]);
                    if ($retour) {
                        echo self::YES;
                        exit();
                    }
                }
            }
            echo self::NO;
            exit();
        }

        /**
         * Changer l'autorisation de redemander un congé dans la plage de date qui ont été réfusée
         *
         * @changeLog 2023-08-30 [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param array $paramaters     Les données à modifier
         *
         * @return boolean
        */
        public function changerAutoriserDemandeConge($parameters)
        {
            if (!empty($parameters)) {
                extract($parameters);
                $managerConge   = new ManagerConge();
                $conge          = $managerConge->modifier([
                    'idConge'   => $idConge,
                    'allow'     => self::YES
                ]);
                if ($conge != null) {
                    echo self::YES;
                    exit();
                }
            }
            echo self::NO;
            exit();
        }
    }