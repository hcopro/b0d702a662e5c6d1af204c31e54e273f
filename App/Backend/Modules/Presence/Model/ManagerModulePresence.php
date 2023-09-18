<?php
    
    /**
     * Manager du module Presence du Backend
     *
     * @author Toky
     *
     * @since 2020-07-14
    */

    use \Core\DbManager;
    use \Core\View;
    use \Core\PublicFonctions;
    use \Model\ManagerJourFerie;
    use \Model\ManagerEntrepriseFerie;
    use \Model\ManagerEmploye;
    use \Model\ManagerTache;
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
    use \Model\ManagerCompte;
    use \Model\ManagerStockConge;
    use \Model\ManagerParametrePointage;
    use \Model\ManagerRetard;
    use \Model\ManagerMission;
    use \Model\ManagerParametreConge;
    use \Model\ManagerValidationPermission;
    use \Model\ManagerValidationConge;
    use \Model\ManagerEmailContact;
    use \Model\ManagerShift;
    use \Model\ManagerCampaign;


    class ManagerModulePresence extends DbManager
    {
        const USER_EMPLOYE         = 'employe';
        const USER_ENTREPRISE      = 'entreprise';
        const POINTAGE_PAGE        = 'pointage';
        const PERMISSION_PAGE      = 'permission';
        const PARAMETRE_PAGE       = 'parametre';
        const FERIE_PAGE           = 'jourFerie';
        const REPOS_PAGE           = 'repos';
        const RETARD_PAGE          = 'retard';
        const FREE_TASK            = -1;
        const PAUSED_TASK          = 0;
        const CURRENT_TASK         = 1;
        const START_ACTION         = 'start';
        const STOP_ACTION          = 'stop';
        const RESUME_ACTION        = 'resume';
        const OPEN_POINTING        = 0;
        const CLOSED_POINTING      = 1;
        const PRESENT_YES          = 1;
        const PRESENT_NO           = 0;
        const PRESENT_ALL          = 2;
        const EN_PERMISSION        = 3;
        const AU_REPOS             = 4;
        const EN_CONGE             = 5;
        const LATE                 = 6;
        const MONDAY               = 'Lundi';
        const TUESDAY              = 'Mardi';
        const WEDNESDAY            = 'Mercredi';
        const THURSDAY             = 'Jeudi';
        const FRIDAY               = 'Vendredi';
        const SATURDAY             = 'Samedi';
        const SUNDAY               = 'Dimanche';
        const TODAY                = 1;
        const TOMORROW             = 2;
        const YESTERDAY            = 3;
        const THIS_WEEK            = 4;
        const NEXT_WEEK            = 5;
        const LAST_WEEK            = 6;
        const THIS_MONTH           = 7;
        const NEXT_MONTH           = 8;
        const LAST_MONTH           = 9;
        const NATURE_PERMISSION    = "permission";
        const NATURE_REPOS         = "repos";
        const NATURE_TASK          = "tache";
        const TYPE_REQUEST         = "demande";
        const TYPE_VALIDATED       = "valide";
        const TYPE_REJECTED        = "rejete";
        const TYPE_CANCELED        = "annule";
        const TYPE_INFORMATION     = "information";
        const FILTER_GROUP_ALL     = 1;
        const FILTER_GROUP_SERVICE = 2;
        const FILTER_GROUP_POSTE   = 3;
        const FILTER_GROUP_EMPLOYE = 4;
        const EMPTY                = 0;
        const PROPOSED             = 1;
        const VALIDATED            = 2;
        const EXPIRED              = 3;
        const REFUSED              = 0;
        const DEMANDE_ALL          = 3;
        const NORME_DUREE_MAX_PERMISSION = 10;
        const NORME_DUREE_MAX_Repos = 180;
        const NO                    = 0;
        const YES                   = 1;
        const SEEN                  = 1;
        const NOT_SEEN              = 0;
        const ONE_DAY               = "08:00:00";
        const START_DAY             = "00:00:00";
        const ONE_MINUTE            = 60;
        const POSTE_INTERNE         = 0;
        const VU                    = 2;
        const SETTLED               = "YES";
        /** @changeLog 2021-11-30 [EVOL] (Lansky) Ajout page tâche planifiée et page tempsou duré de travail dans pointage */
        const TACHE_REALISEE_PAGE   = "tacheRealisee";
        const HOUR_WORK_DAY         = 8;
        const HEUR_PAUSE_JOURNALIER = 30; // En Minute
        const DIRECT_VALIDATION     = 0;
        const DEFINED_VALIDATION    = 2;
        const LEAVE_ARCHIVED        = 0;
        const LEAVE_NOT_ARCHIVED    = 1;
        const SOIR                  = 3;
        /**
         * Lister les jours fériés
         *
         * @param array $parameters Les critères des données à afficher
         *
         * @return array
        */
        public function listerJourFeries($parameters)
        {
            if (empty($parameters)) {
                $resultats    = array();
                $manager      = new ManagerJourFerie();
                $jourFeries   = $manager->lister();
                if (!empty($jourFeries)) {
                    foreach ($jourFeries as $jourFerie) {
                        $resultats[] = $jourFerie;
                    }
                }
                return $resultats;
            }
        }

        /**
         * Lister les types de permission
         *
         * @param array $parameters Les critères des données à afficher
         *
         * @return array
        */
        public function listerPermissions($parameters)
        {
            if (empty($parameters)) {
                $resultats    = array();
                $manager      = new ManagerTypePermission();
                $permissions  = $manager->lister();
                if (!empty($permissions)) {
                    foreach ($permissions as $permission) {
                        $resultats[] = $permission;
                    }
                }
                return $resultats;
            }
        }

        /**
         * Lister les présences
         *
         * @param array $parameters Les critères des données à afficher
         *
         * @return array
        */
        public function listerJournees($parameters)
        {
            if (!empty($parameters)) {
                $infoTempsTotal['hour'] = 0;
                $infoTempsTotal['minute'] = 0;
                $infoTempsTotal['second'] = 0;
                $data = array();
                if (!empty($parameters['periode'])) {
                    $_SESSION['journee']['periode'] = $parameters['periode'];
                    unset($_SESSION['journee']['debut']);
                    unset($_SESSION['journee']['fin']); 
                    $intervalle = $this->getIntervalle($parameters['periode']);
                    $donnees    = $this->getJournee($parameters['idEmploye'], $intervalle['debut'], $intervalle['fin']);
                } elseif (!empty($parameters['debut'])) {
                    $_SESSION['journee']['debut'] = $parameters['debut'];
                    unset($_SESSION['journee']['periode']);
                    $date = explode("/", $parameters['debut']);
                    $parameters['debut'] = $date[2] . '-' . $date[1] . '-' . $date[0];
                    unset($date);
                    if (!empty($parameters['fin'])) {
                        $_SESSION['journee']['fin'] = $parameters['fin'];
                        unset($_SESSION['journee']['periode']);
                        $date = explode("/", $parameters['fin']);
                        $parameters['fin'] = $date[2] . '-' . $date[1] . '-' . $date[0];
                        $donnees = $this->getJournee($parameters['idEmploye'], $parameters['debut'], $parameters['fin']);
                    } else {
                        $donnees = $this->getJournee($parameters['idEmploye'], $parameters['debut'], $parameters['debut']);
                    }
                } else {
                    $donnees = array();
                }
                $view = new View("listerJournees");
                $view->sendWithoutTemplate("Backend", "Presence", $donnees, ""); 
                exit();
            } else{
                header("Location : " . HOST . "manage/employe/pointage/dashboard");
                exit();
            }
        }

        /**
         * Lister les jours fériés d'une entreprise dans une année
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
        */
        public function listerEntrepriseFeries($parameters)
        {
            if (!empty($parameters)) {
                $manager             = new ManagerEntreprise();
                $entreprise          = $manager->chercher(['idEntreprise' => $parameters['idEntreprise']]);
                $manager             = new ManagerEntrepriseFerie();
                $allEntrepriseFeries = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                $entrepriseFeries    = array();
                foreach ($allEntrepriseFeries as $ferie) {
                    $anneeFerie      = date('Y', strtotime($ferie->getDate()));
                    if ($anneeFerie == $parameters['annee']) {
                        $ferie->setDate($this->writeDate($ferie->getDate()));
                        $tmp['ferie']       = $ferie;
                        $manager            = new ManagerJourFerie();
                        $tmp['type']        = $manager->chercher(['idJourFerie' => $ferie->getIdJourFerie()]);
                        $entrepriseFeries[] = $tmp;
                    }
                }
                $donnees = [
                    "entrepriseFeries" => $entrepriseFeries
                ];
                $view = new View("listerEntrepriseFeries");
                $view->sendWithoutTemplate("Backend", "Presence", $donnees, "entreprise"); 
                exit();
            }
        }


        /**
         * Récupérer la journée d'un employe
         *
         * @param int  $idEmploye L'identifiant de l'employe
         * @param date $debut La date de debut en question
         * @param date $fin La date de fin en question
         *
         * @return array
        */
        private function getJournee($idEmploye, $debut, $fin)
        {
            $managerEntreprisePermission        = new ManagerEntreprisePermission();
            $managerEmployePermission           = new ManagerEmployePermission();
            $managerParametrePointage           = new ManagerParametrePointage();
            $managerEntrepriseFerie             = new ManagerEntrepriseFerie();
            $managerTypePermission              = new ManagerTypePermission();
            $managerEntreprise                  = new ManagerEntreprise();
            $managerJourFerie                   = new ManagerJourFerie();
            $managerPresence                    = new ManagerPresence();
            $managerPointage                    = new ManagerPointage();
            $managerMission                     = new ManagerMission();
            $managerEmploye                     = new ManagerEmploye();
            $managerRetard                      = new ManagerRetard();
            $managerTache                       = new ManagerTache();
            $factory                            = new PublicFonctions();
            $employe                            = $managerEmploye->chercher(['idEmploye' => $idEmploye]);
            $entreprise                         = $managerEntreprise->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
            $journee['info']['employe']         = $employe;
            $journee['info']['permissions']     = $this->getPermissionPossibles([], $employe);
            $journee['info']['nombreRepos']     = $this->getReposPossibles([], $employe);
            $journee['info']['nombreConges']    = $this->getCongesPossibles([], $employe);
            extract($this->processDaysRecursively($journee, $debut, $fin, [], 0, $idEmploye, $managerEntreprisePermission, $managerTypePermission, $managerEntrepriseFerie, $managerJourFerie, $managerPresence, $managerPointage, $managerParametrePointage, $managerTache, $managerMission, $managerRetard, $factory, $entreprise, $employe));
            $infoTempsTotal = $this->getDuree($tempsTotal);
            $donnees        = ['infoTempsTotal' => $infoTempsTotal, 'data' => $data];
            return $donnees;
        } 

        /**
         * Calculer le temps effectué par mois
         *
         * @param object $employe  L'employé concerné
         * @param int    $mois     Le mois en question
         * @param int    $annee    L'année en question
         *
         * @return array
        */
        private function getTempsEmploye($employe, $mois, $annee)
        {
            extract($this->getBeginToEndMounth($mois, $annee));
            $manager   = new ManagerPresence();
            $presences = $manager->selectionner(
                ['idEmploye' => $employe->getIdEmploye()],
                ['date'      => $debut],
                ['date'      => $fin]
            );
            $temps['presence']      = 0;
            $temps['permission']    = 0;
            $temps['repos']         = 0;
            $temps['conge']         = 0;
            $temps['retard']        = 0;
            $temps['total']         = 0;
            $temps['nombre-retard'] = 0;
            foreach ($presences as $presence) {
                $permission = $this->estEnPermission($employe, $presence->getDate());
                $repos      = $this->estEnRepos($employe, $presence->getDate());
                $conge      = $this->estEnConge($employe, $presence->getDate());
                if ($presence->getStatut() == self::PRESENT_YES) {
                    $manager   = new ManagerPointage();
                    $pointages = $manager->lister(['idPresence' => $presence->getIdPresence()]);
                    foreach ($pointages as $pointage) {
                        if ($pointage->getDebut() != null && $pointage->getFin() != null) {
                            $temps['presence'] += abs(strtotime($pointage->getFin()) - strtotime($pointage->getDebut()));
                        }
                    }
                    $manager    = new ManagerRetard();
                    $retard     = $manager->chercher([
                        'id_employe'    => $employe->getIdEmploye(),
                        'id_presence'   => $presence->getIdPresence(),
                        'date'          => 'LIKE "%' . $presence->getDate() . '%"'
                    ]);
                    if ($retard) {
                        $temps['retard'] += strtotime($retard->getDuring());
                        $temps['nombre-retard']++;
                    }
                } elseif ($permission != false) {
                    $temps['permission'] += abs(strtotime(self::ONE_DAY) - strtotime(self::START_DAY));
                } elseif ($repos != false) {
                    $temps['repos'] += abs(strtotime(self::ONE_DAY) - strtotime(self::START_DAY));
                } elseif ($conge != false) {
                    $temps['conge'] += abs(strtotime(self::ONE_DAY) - strtotime(self::START_DAY));
                }
            }
            $temps['total']      = $temps['presence'] + $temps['permission'] + $temps['repos'] + $temps['conge'];
            $temps['presence']   = $this->getDuree($temps['presence']);
            $temps['permission'] = $this->getDuree($temps['permission']);
            $temps['repos']      = $this->getDuree($temps['repos']);
            $temps['conge']      = $this->getDuree($temps['conge']);
            $temps['total']      = $this->getDuree($temps['total']);
            $temps['retard']     = $this->getDuree($temps['retard']);
            return $temps;
        }

        /**
         * Lister les statistiques mensuelles d'un employé
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
        */
        public function listerStatistiques($parameters)
        {
            if (!empty($parameters['idEmploye']) && !empty($parameters['mois']) && !empty($parameters['annee'])) {
                $manager                    = new ManagerEmploye();
                $employe                    = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $temps                      = $this->getTempsEmploye($employe, $parameters['mois'], $parameters['annee']);
                $donnees['presence']        = number_format($temps['presence']['hour'] + ($temps['presence']['minute'] / self::ONE_MINUTE), 2);
                $donnees['permission']      = number_format($temps['permission']['hour'] + ($temps['permission']['minute'] / self::ONE_MINUTE), 2);
                $donnees['repos']           = number_format($temps['repos']['hour'] + ($temps['repos']['minute'] / self::ONE_MINUTE), 2);
                $donnees['conge']           = number_format($temps['conge']['hour'] + ($temps['conge']['minute'] / self::ONE_MINUTE), 2);
                $donnees['total']           = number_format($temps['total']['hour'] + ($temps['total']['minute'] / self::ONE_MINUTE), 2);
                $donnees['retard']          = number_format($temps['retard']['hour'] + ($temps['retard']['minute'] / self::ONE_MINUTE), 2);
                $donnees['nombre-retard']   = $temps['nombre-retard'];
                echo json_encode($donnees);
                exit();
            }
        }

        /**
         * Voir le tableau de bord de suivi du côté de l'employé
         *
         * @param array $parameters Les données à afficher
         *
         * @return array
        */
        public function voirSuiviEmploye($parameters)
        {
            $manager = new PublicFonctions();
            return $manager->listOfMyTeam($parameters);
        }

        /**
         * Voir le tableau de bord de suivi du côté de l'entreprise
         *
         * @param array $parameters Les données à afficher
         *
         * @return array
        */
        public function voirSuiviEntreprise($parameters)
        {
            $manager    = new ManagerEmploye();
            $employes   = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            return [
                'entreprise'    => $entreprise,
                'subordonnes'   => $employes
            ];
        }

        /**
         * Récupérer les suivis d'un employé dans un intervalle de temps
         *
         * @param array $idEmploye Identifiant de l'employé
         * @param date  $debut     Début du suivi
         * @param date  $fin       Fin du suivi
         *
         * @return array
        */
        private function getSuivis($idEmploye, $debut, $fin, $userTaskGroup)
        {
            $manager    = new ManagerEmploye();
            $employe    = $manager->chercher(['idEmploye' => $idEmploye]);
            $taches     = array();
            $durees     = array();
            $ratios     = array();
            $date       = $debut;
            $dates      = [];
            $respTotal  = [
                "time"      => 0,
                "second"    => 0,
                "minute"    => 0,
                "hour"      => 0
            ];
            while (strtotime($date) <= strtotime($fin)) {
                $timing     = 0;
                $manager    = new ManagerPresence();
                $presence   = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'date' => $date]);
                if ($presence != null) {
                    $manager   = new ManagerPointage();
                    $pointages = $manager->lister(['idPresence' => $presence->getIdPresence()]);
                    foreach ($pointages as $pointage) {
                        if (!is_null($pointage->getDebut())) {
                            $manager      = new ManagerTache();
                            $tache        = $manager->chercher(['idTache' => $pointage->getIdTache()]);
                            if ($tache != null) {
                                if ($userTaskGroup =='all' || $userTaskGroup == $tache->getIdMission()) {
                                    if (is_null($pointage->getFin())) {
                                        $pointage->setFin(date('H:i:s', strtotime(date('H:i:s') . '+3 hour'))); // À changer pour dynamiser
                                    }
                                    if (!array_key_exists($tache->getIdTache(), $taches)) {
                                        $taches[$tache->getIdTache()] = $tache;
                                    }
                                    if ($pointage->getDebut() != null && $pointage->getFin() != null) {
                                        $dates[$tache->getIdTache()]    = date('d/m/Y', strtotime($date));
                                    }
                                }
                                if (!array_key_exists($tache->getIdTache(), $durees)) {
                                    $durees[$tache->getIdTache()] = $this->getDuree(0);
                                }
                                $durees[$tache->getIdTache()]   = $this->sommeTabToTime($durees[$tache->getIdTache()], abs(strtotime($pointage->getFin()) - strtotime($pointage->getDebut())));
                                $timing                         += abs(strtotime($pointage->getFin()) - strtotime($pointage->getDebut()));
                            }
                        }
                    }
                    $respTotal                = $this->sommeTabToTime($respTotal, $timing);
                }
                $date = date('Y-m-d', strtotime($date . ' + 1 DAY'));
            }
            foreach ($durees as $key => $value) {
                $ratios[$key]   = ($value['time'] > 0 && $respTotal['time'] > 0) ? round(($value['time'] / $respTotal['time']) * 100, 2) : 0;
            }
            return [
                'taches'        => $taches,
                'durees'        => $durees,
                'ratios'        => $ratios,
                'tempsTotal'    => $respTotal,
                'dates'         => $dates
            ];
        }

        /**
         * Afficher les suivis selon les filtres
         *
         * @param array $oldTab     Ancien tableau à sommer
         * @param int $argInt       Valeur de la seconde
         *
         * @return array
        */
        private function sommeTabToTime($oldTab, $argInt)
        {
            $tmpTime            = $this->getDuree($argInt);
            $oldTab['time']     += (int)$tmpTime['time'];
            $oldTab['second']   += (int)$tmpTime['second'];
            $oldTab['minute']   += (int)$tmpTime['minute'];
            $oldTab['hour']     += (int)$tmpTime['hour'];
            if ($oldTab['second'] >= self::ONE_MINUTE) {
                $oldTab['minute'] += floor(($oldTab['second']) / self::ONE_MINUTE);
                $oldTab['second'] = $oldTab['second'] % self::ONE_MINUTE;
            }
            if ($oldTab['minute'] >= self::ONE_MINUTE) {
                $oldTab['hour'] += floor(($oldTab['minute']) / self::ONE_MINUTE);
                $oldTab['minute'] = $oldTab['minute'] % self::ONE_MINUTE;
            }
            return $oldTab;
        }

        /**
         * Afficher les suivis selon les filtres
         *
         * @param array $parameters Critères des donnéees
         *
         * @return empty
        */
        public function listerSuivis($parameters)
        {
            if (!empty($parameters)) {
                $donnees    = $this->getUserTask($parameters);
                $view       = new View("listerSuivis");
                $view->sendWithoutTemplate("Backend", "Presence", $donnees, $_SESSION['compte']['identifiant']);
                exit();
            }
        }

        /**
         * Afficher les tâches réalisées par employé selon les filtres
         *
         * @param array $parameters Critères des donnéees
         *
         * @return empty
        */
        public function listerTacheRealisee($parameters)
        {
            $parameters['type'] = self::PRESENT_YES;
            if (!empty($parameters)) {
                $donnees['taches']  = $this->getAllPointageEmploye($parameters)['pointages'];
                extract($donnees);
                if (!empty($taches)) {
                    foreach ($taches as $key => $value) {
                        extract($value);
                        if (!isset($fin)) {
                            $fin = null;
                        }
                        if (!isset($debut)) {
                            $debut = null;
                        }
                        if ($nombrePresences > 0) {
                            $manager = new ManagerPresence();
                            $responses = $manager->lister([
                                'idEmploye' => $employe->getIdEmploye(),
                                'date'      => 'BETWEEN "' . $this->getDateInteger($debut). '" AND "' . $this->getDateInteger($fin) .'"',
                                'statut'    => self::PRESENT_YES
                            ]); 
                            foreach ($responses as $response) {
                                $manager = new ManagerPointage();
                                $response->setIdPresence($manager->lister(['idPresence' => $response->getIdPresence()]));
                                $manager = new ManagerTache();
                                foreach ($response->getIdPresence() as $valResponse) {
                                    $valResponse->setIdTache($manager->chercher(['idTache' => $valResponse->getIdTache()]));
                                }
                            }
                            // Parcourir les données pour fusionner toutes les tâches sont identiques
                            $compares = $responses;
                            foreach ($responses as $clef => $val) {
                                foreach ($val->getIdPresence() as $clef2 => $presence) {
                                    if (is_int($clef2)) {
                                        $duree = $this->getDiffTime( $presence->getDebut(), $presence->getFin());
                                        count($val->getIdPresence()) > 1 ? $tmp = $clef : $tmp = $clef + 1 ;
                                        for ($ind = $tmp ; $ind < count($compares); $ind++) {
                                            $cmp = $compares[$ind];
                                            foreach ($cmp->getIdPresence() as $key2 => $compare) {
                                                if (!is_array($compare->getIdTache())) {
                                                    if ($clef == $ind && $clef2 == $key2) {
                                                        continue;
                                                    } else {
                                                        if (is_array($presence->getIdTache())) {
                                                            $tmpObject = $presence->getIdTache()['tache'];
                                                        } else {
                                                            $tmpObject = $presence->getIdTache();
                                                        }
                                                        if ($tmpObject->getIdTache() == $compare->getIdTache()->getIdTache()) { 
                                                            $dureeTmp = $this->getDiffTime($compare->getDebut(), $compare->getFin());
                                                            foreach ($duree as $k => $time) {
                                                                $duree[$k] +=  $dureeTmp[$k];
                                                            }
                                                            // Delete same content in column of array presence
                                                            if (count($cmp->getIdPresence()) > 1) {
                                                                if ($key2 == count($cmp->getIdPresence()) - 1
                                                                    || ($key2 == 0 && count($cmp->getIdPresence()) <= 2)) {
                                                                    $idPresence = $cmp->getIdPresence();
                                                                    $arraySplice = \array_splice($idPresence, -1, 1);
                                                                    $cmp->setIdPresence($arraySplice);
                                                                    $responses[$ind]->setIdPresence($cmp->getIdPresence());
                                                                } else {
                                                                    // if (is_array($cmp->getIdPresence())) {
                                                                        // for ($i=0; $i < count($cmp->getIdPresence()); $i++) { 
                                                                            // if (array_key_exists($i, $cmp->getIdPresence())) {

                                                                            // }/* else {
                                                                                
                                                                            // }*/
                                                                        // }
                                                                    // } else {
                                                                        $cmp->setIdPresence(\array_splice($cmp->getIdPresence(), $key2, - (count($cmp->getIdPresence()) - ($key2 + 1))));
                                                                        $responses[$ind]->setIdPresence($cmp->getIdPresence());
                                                                    // }
                                                                }
                                                            } else {
                                                                \array_splice($compares, $ind, 1); 
                                                            } 
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        $presence->setIdTache(array_merge([ 'tache' => $presence->getIdTache()], ["duree" => $duree = $this->arrayTime($duree)]));
                                    }
                                }
                            }
                            $taches[$key]['presence'] = $compares;
                        }
                    }
                }
                $donnees = ['taches' => $taches];
                $view = new View("listerTacheRealisee");
                $view->sendWithoutTemplate("Backend", "Presence", $donnees, "entreprise");
            } else {
                header("Location : " . HOST . "manage/entreprise/tacheRealisee");
            }
            exit();
        }
        
        /**
         * Calculer la différence entre 2 dates
         *
         * @param date $date1 La date intitiale
         * @param date $date2 La date finale
         *
         * @return array durée
        */
        private function getDiffTime($date1, $date2)
        {
            $difference       = abs(strtotime($date2) - strtotime($date1));
            $retour           = array();
            $tmp              = $difference;
            $retour['second'] = $tmp % self::ONE_MINUTE;
            $tmp              = floor(($tmp - $retour['second']) / self::ONE_MINUTE);
            $retour['minute'] = $tmp % self::ONE_MINUTE;
            $tmp              = floor(($tmp - $retour['minute']) / self::ONE_MINUTE);
            $retour['hour']   = $tmp % 24;
            $tmp              = floor(($tmp - $retour['hour'])  / 24);
            $retour['day']    = $tmp;
            return $retour; 
        }

        /**
         * Calculer en heure
         *
         * @param array $time Tableau contenant heure, minute et seconde
         *
         * @return array durée
        */
        private function arrayTime($time)
        {
            if ($time['second'] % self::ONE_MINUTE >= 0 && floor($time['second'] / self::ONE_MINUTE) > 0) {
                $time['minute'] += floor($time['second'] / self::ONE_MINUTE);
                $time['second'] = $time['second'] % self::ONE_MINUTE;
            }
            if ($time['minute'] % self::ONE_MINUTE >= 0 && floor($time['minute'] / self::ONE_MINUTE) > 0) {
                $time['hour'] += floor($time['minute'] / self::ONE_MINUTE);
                $time['minute'] = $time['minute'] % self::ONE_MINUTE;
            }
            if ($time['hour'] % 24 >= 0 && floor($time['hour'] / 24) > 0) {
                $time['day'] += floor($time['hour'] / 24);
                $time['hour'] = $time['hour'] % 24;
            }
            return $time; 
        }

        /**
         * Filtrer les pointages
         *
         * @param array $parameters     Critères des donnéees
         * @param array $employes       La listes des employés 
         *
         * @return array
        */
        private function getPointageFilter($parameters, $employes)
        {
            if (!empty($parameters['periode'])) {
                $intervalle = $this->getIntervalle($parameters['periode']);
                $pointages  = $this->getPointages($employes, $parameters['type'], $intervalle['debut'], $intervalle['fin']);
            } elseif (!empty($parameters['debut'])) {
                if (!empty($parameters['fin'])) {
                    $pointages  = $this->getPointages($employes, $parameters['type'], $parameters['debut'], $parameters['fin']);
                } else {
                    $pointages  = $this->getPointages($employes, $parameters['type'], $parameters['debut'], $parameters['debut']);
                }
            } elseif (!empty($parameters['mois'])) {
                extract($this->getBeginToEndMounth($parameters['mois']));
                $pointages = $this->getPointages($employes, $parameters['type'], $debut, $fin);
            }
            return $pointages;
        }

        /**
         * Récupérer les pointages selon les filtres
         *
         * @param array $parameters Critères des donnéees
         *
         * @return array
        */
        private function getAllPointageEmploye($parameters)
        {
            $data = $this->getAllEmployeesFilterByGroup($parameters);
            extract($data);
            if ($executeFilter) {
                $pointages  = $this->getPointageFilter($parameters, $employes);
            }
            if (!isset($pointages)) {
                $manager = new ManagerPointage();
                $pointages = $manager->initialiser();
            }
            return [
                'pointages' => $pointages
            ];
        }
        /**
         * Afficher les pointages selon les filtres
         *
         * @param array $parameters Critères des donnéees
         *
         * @return empty
        */
        public function listerPointages($parameters)
        {
            if (!empty($parameters)) {
                $donnees        = $this->getAllPointageEmploye($parameters);
                $view = new View("listerPointages");
                $view->sendWithoutTemplate("Backend", "Presence", $donnees, "entreprise"); 
                exit();
            } else {
                header("Location : " . HOST . "manage/entreprise/pointage/dashboard");
                exit();
            }
        }

        /**
         * Lister les demandes de permissions d'une entreprise
         *
         * @param array $parameters Critères des donnéees à lister
         *
         * @return empty
        */
        public function listerDemandePermissions($parameters)
        {
            if (!empty($parameters)) {
                $user           = $_SESSION['compte']['identifiant'];
                $factory        = new PublicFonctions();
                $permissions    = [];
                extract($parameters);
                if (!isset($periode)) {
                    $periode = null;
                }
                if (!isset($mois)) {
                    $mois = null;
                }
                if (!isset($debut)) {
                    $debut = null;
                }
                if (!isset($fin)) {
                    $fin = null;
                }
                if ($groupe) {
                    if ($periode == '') {
                        $periode = null;
                    }
                    extract($factory->getFiltre($_SESSION['user']['idEntreprise']));
                    if ($groupe == self::FILTER_GROUP_ALL) {
                        if (!is_null($periode)) {
                            extract($this->getIntervalle($periode));
                        } elseif (!is_null($debut)) {
                            if (!is_null($fin)) {
                                $fin = $debut;
                            }
                        } elseif (!is_null($mois)) {
                            extract($this->getBeginToEndMounth($mois));
                        } else {
                            $debut = date('d/m/Y', mktime(0, 0, 0, 1, 1, date('Y'))); // Date de début de l'année de l'année en cours
                            $fin = date('d/m/Y', mktime(0, 0, 0, 12, 31, date('Y'))); // Date de fin de l'année de l'année en cours
                        }
                    } elseif ($groupe == self::FILTER_GROUP_SERVICE) {
                        if (!is_null($id)) {
                            $manager        = new ManagerEntrepriseService();
                            $service        = $manager->chercher(['idEntrepriseService' => $id]);
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
                            if (!is_null($periode)) {
                                extract($this->getIntervalle($periode));
                            } elseif (!is_null($debut)) {
                                if (!is_null($fin)) {
                                    $fin = $debut;
                                }
                            } elseif (!is_null($mois)) {
                                extract($this->getBeginToEndMounth($mois));
                            }
                        }
                    } elseif ($groupe == self::FILTER_GROUP_POSTE) {
                        if (!is_null($id)) {
                            $manager        = new ManagerEntreprisePoste();
                            $poste          = $manager->chercher(['idEntreprisePoste' => $id]);
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
                            if (!is_null($periode)) {
                                extract($this->getIntervalle($periode));
                            } elseif (!is_null($debut)) {
                                if (!is_null($fin)) {
                                    $fin = $debut;
                                }
                            } elseif (($parameters['mois'])) {
                                extract($this->getBeginToEndMounth($parameters['mois']));
                            }
                        }
                    } elseif ($groupe == self::FILTER_GROUP_EMPLOYE) {
                        if (!is_null($id)) {
                            $manager     = new ManagerEmploye();
                            $employes    = array();
                            $employes[]  = $manager->chercher(['idEmploye' => $id]);
                            if (!is_null($periode)) {
                                extract($this->getIntervalle($periode));
                            } elseif (!is_null($debut)) {
                                if (!is_null($fin)) {
                                    $fin = $debut;
                                }
                            } elseif (!is_null($mois)) {
                                extract($this->getBeginToEndMounth($mois));
                            }
                        }
                    }
                }

                $donnees = [
                    // 'permissions' => $this->getDemandePermissions($employes, $type, $debut, $fin)
                    'permissions' => $this->getPermissions($employes, $type, $debut, $fin)
                ];
                $view = new View("listerDemandePermissions");
                $view->sendWithoutTemplate("Backend", "Presence", $donnees, $user); 
            } else {
                header("Location : " . HOST . "manage/" . $user . "/permission/dashboard");
            }
            exit();
        }

        /**
         * Lister les demandes de permission d'un compte
         *
         * @changLog 2023-06-20 [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param array $parameters Critères des donnéees à lister
         *
         * @return array
        */
        public function getDemandePermissions($employes, $type, $debut, $fin)
        {
            $managerValidationPermission    = new ManagerValidationPermission();
            $myValidationPermission         = $managerValidationPermission->lister([
                'id_compte' => $_SESSION['compte']['idCompte'],
                'etat'      => self::LEAVE_NOT_ARCHIVED
            ]);
            return $myValidationPermission;
        }

        /**
         * Lister les demandes de repos d'une entreprise
         *
         * @param array $parameters Critères des donnéees à lister
         *
         * @return empty
        */
        public function listerDemandeRepos($parameters)
        {
            if (!empty($parameters)) {
                $manager        = new ManagerEntreprise();
                $entreprise     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                if (!empty($parameters['groupe'])) {
                    if ($parameters['groupe'] == self::FILTER_GROUP_ALL) {
                        $manager  = new ManagerEmploye();
                        $employes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                        if (!empty($parameters['periode'])) {
                            $intervalle   = $this->getIntervalle($parameters['periode']);
                            $repos  = $this->getRepos($employes, $parameters['type'], $intervalle['debut'], $intervalle['fin']);
                        } elseif (!empty($parameters['debut'])) {
                            if (!empty($parameters['fin'])) {
                                $repos  = $this->getRepos($employes, $parameters['type'], $parameters['debut'], $parameters['fin']);
                            } else {
                                $repos  = $this->getRepos($employes, $parameters['type'], $parameters['debut'], $parameters['debut']);
                            }
                        } elseif (!empty($parameters['mois'])) {
                            extract($this->getBeginToEndMounth($parameters['mois']));
                            $repos = $this->getRepos($employes, $parameters['type'], $debut, $fin);
                        }
                    } elseif ($parameters['groupe'] == self::FILTER_GROUP_SERVICE) {
                        if (!empty($parameters['id'])) {
                            $manager        = new ManagerEntrepriseService();
                            $service        = $manager->chercher(['idEntrepriseService' => $parameters['id']]);
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
                            if (!empty($parameters['periode'])) {
                                $intervalle   = $this->getIntervalle($parameters['periode']);
                                $repos  = $this->getRepos($employes, $parameters['type'], $intervalle['debut'], $intervalle['fin']);
                            } elseif (!empty($parameters['debut'])) {
                                if (!empty($parameters['fin'])) {
                                    $repos  = $this->getRepos($employes, $parameters['type'], $parameters['debut'], $parameters['fin']);
                                } else {
                                    $repos  = $this->getRepos($employes, $parameters['type'], $parameters['debut'], $parameters['debut']);
                                }
                            } elseif (!empty($parameters['mois'])) {
                               extract($this->getBeginToEndMounth($parameters['mois']));
                                $repos = $this->getRepos($employes, $parameters['type'], $debut, $fin);
                            }
                        }
                    } elseif ($parameters['groupe'] == self::FILTER_GROUP_POSTE) {
                        if (!empty($parameters['id'])) {
                            $manager        = new ManagerEntreprisePoste();
                            $poste          = $manager->chercher(['idEntreprisePoste' => $parameters['id']]);
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
                            if (!empty($parameters['periode'])) {
                                $intervalle   = $this->getIntervalle($parameters['periode']);
                                $repos  = $this->getRepos($employes, $parameters['type'], $intervalle['debut'], $intervalle['fin']);
                            } elseif (!empty($parameters['debut'])) {
                                if (!empty($parameters['fin'])) {
                                    $repos  = $this->getRepos($employes, $parameters['type'], $parameters['debut'], $parameters['fin']);
                                } else {
                                    $repos  = $this->getRepos($employes, $parameters['type'], $parameters['debut'], $parameters['debut']);
                                }
                            } elseif (!empty($parameters['mois'])) {
                                extract($this->getBeginToEndMounth($parameters['mois']));
                                $repos = $this->getRepos($employes, $parameters['type'], $debut, $fin);
                            }
                        }
                    } elseif ($parameters['groupe'] == self::FILTER_GROUP_EMPLOYE) {
                        if (!empty($parameters['id'])) {
                            $manager     = new ManagerEmploye();
                            $employes    = array();
                            $employes[]  = $manager->chercher(['idEmploye' => $parameters['id']]);
                            if (!empty($parameters['periode'])) {
                                $intervalle = $this->getIntervalle($parameters['periode']);
                                $repos  = $this->getRepos($employes, $parameters['type'], $intervalle['debut'], $intervalle['fin']);
                            } elseif (!empty($parameters['debut'])) {
                                if (!empty($parameters['fin'])) {
                                    $repos  = $this->getRepos($employes, $parameters['type'], $parameters['debut'], $parameters['fin']);
                                } else {
                                    $repos  = $this->getRepos($employes, $parameters['type'], $parameters['debut'], $parameters['debut']);
                                }
                            } elseif (!empty($parameters['mois'])) {
                                extract($this->getBeginToEndMounth($parameters['mois']));
                                $repos = $this->getRepos($employes, $parameters['type'], $debut, $fin);
                            }
                        }
                    }
                }
                $donnees = [
                    'repos' => $repos
                ];
                $view = new View("listerDemandeRepos");
                $view->sendWithoutTemplate("Backend", "Presence", $donnees, "entreprise"); 
                exit();
            } else {
                header("Location : " . HOST . "manage/entreprise/repos/dashboard");
                exit();
            }
        }

        /**
         * Récupérer les dates de début et fin d'une période
         *
         * @param int $periode La période selon les constantes définie dans cette classe
         *
         * @return array
        */
        private function getIntervalle($periode)
        {
            $today   = date('Y-m-d', strtotime('+3 hour', strtotime(gmdate('Y-m-d'))));
            switch ($periode) {
                case self::TODAY :
                    $intervalle['debut'] = $today;
                    $intervalle['fin']   = $today;
                    return $intervalle;
                    break;
                case self::YESTERDAY :
                    $date                = date('Y-m-d', strtotime($today . ' - 1 DAY'));
                    $intervalle['debut'] = $date;
                    $intervalle['fin']   = $date;
                    return $intervalle;
                    break;
                case self::TOMORROW :
                    $date                = date('Y-m-d', strtotime($today . ' + 1 DAY'));
                    $intervalle['debut'] = $date;
                    $intervalle['fin']   = $date;
                    return $intervalle;
                    break;
                case self::THIS_WEEK :
                    $debut                = date("Y-m-d", strtotime("next monday"));
                    $intervalle['debut']  = date('Y-m-d', strtotime($debut . ' - 7 DAY'));
                    $fin                  = date("Y-m-d", strtotime("previous sunday"));
                    $intervalle['fin']    = date('Y-m-d', strtotime($fin . ' + 7 DAY'));
                    return $intervalle;
                    break;
                case self::LAST_WEEK :
                    $debut                = date("Y-m-d", strtotime("next monday"));
                    $intervalle['debut']  = date('Y-m-d', strtotime($debut . ' - 14 DAY'));
                    $intervalle['fin']    = date("Y-m-d", strtotime("previous sunday"));
                    return $intervalle;
                    break;
                case self::NEXT_WEEK :
                    $intervalle['debut']  = date("Y-m-d", strtotime("next monday"));
                    $fin                  = date("Y-m-d", strtotime("previous sunday"));
                    $intervalle['fin']    = date('Y-m-d', strtotime($fin . ' + 14 DAY'));
                    return $intervalle;
                    break;
                case self::THIS_MONTH :
                    $intervalle['debut'] = date('Y-m-d', strtotime('first day of this month'));
                    $intervalle['fin']   = date('Y-m-d', strtotime('last day of this month'));
                    return $intervalle;
                    break;
                case self::LAST_MONTH :
                    $intervalle['debut'] = date('Y-m-d', strtotime('first day of last month'));
                    $intervalle['fin']   = date('Y-m-d', strtotime('last day of last month'));
                    return $intervalle;
                    break;
                case self::NEXT_MONTH :
                    $intervalle['debut'] = date('Y-m-d', strtotime('first day of next month'));
                    $intervalle['fin']   = date('Y-m-d', strtotime('last day of next month'));
                    return $intervalle;
                    break;
                case $periode >= 2018 :
                    $first_day = new DateTime($periode . '-01-01'); // Create a new DateTime object for January 1st of periode
                    $last_day = new DateTime($periode . '-12-31'); // Create a new DateTime object for December 31st of periode
                    $intervalle['debut'] = $first_day->format('Y-m-d');
                    $intervalle['fin']   = $last_day->format('Y-m-d');
                    return $intervalle;
                    break;
                default:
                    return null;
                    break;
            }
        }

        /**
         * Récupérer les pointages d'un groupe d'employés entre 2 dates
         *
         * @param array $employes Liste d'employés
         * @param strting  $type  Le type de présence
         * @param date  $debut    Le début de l'interval de temps
         * @param date  $fin      La fin de l'interval de temps
         *
         * @return array
        */
        public function getPointages($employes, $type, $debut, $fin)
        {
            /**@changeLog 2022-10-11 [EVOL] (Lansky) Ajouter retard et élimine les redondances du code en mettant dans une fonction privée */
            if (strpos($debut, '/') !== false) {
                $date = explode("/", $debut);
                $debut = $date[2] . '-' . $date[1] . '-' . $date[0];
            }
            if (strpos($fin, '/') !== false) {
                $date = explode("/", $fin);
                $fin = $date[2] . '-' . $date[1] . '-' . $date[0];
            }
            $resultats  = array();
            $today      = date('Y-m-d', strtotime('+3 hour', strtotime(gmdate('Y-m-d'))));
            foreach ($employes as $employe) {
                $tmp                        = array();
                $tmp['presence']            = null;
                $tmp['retard']              = null;
                $tmp['permission']          = null;
                $tmp['repos']               = null;
                $tmp['conge']               = null;
                $tmp['nombrePresences']     = 0;
                $tmp['nombreRetard']        = 0;
                $tmp['nombreRepos']         = 0;
                $tmp['nombreAbsences']      = 0;
                $tmp['nombrePermissions']   = 0;
                $tmp['nombreConges']        = 0;
                $tmp['poste']               = null;
                $tmp['service']             = null;
                $tmp['depart']              = null;
                $tmp['arrivee']             = null;
                $tmp['employe']             = $employe;
                $retardTotal                = 0;
                $manager                    = new ManagerContratEmploye();
                $contrat                    = $manager->chercher([
                    'idEmploye' => $employe->getIdEmploye(),
                    'statut'    => self::VALIDATED
                ]);
                if ($contrat != null) {
                    $tmpContrat = $contrat;
                    if ($contrat->getPrincipal() != 0) {
                        $contrat = $manager->chercher(['idContratEmploye' => $contrat->getPrincipal()]);
                    }
                    if (is_null($contrat)) {
                        $contrat = $tmpContrat;
                    }
                    $manager            = new ManagerServicePoste();
                    $servicePoste       = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                    $manager            = new ManagerEntreprisePoste();
                    $poste              = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                    $manager            = new ManagerEntrepriseService();
                    $service            = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                    $tmp['poste']       = $poste->getPoste();
                    $tmp['service']     = $service->getService();
                }
                $tmp['debut']       = $this->writeDate($debut);
                if ($debut != $fin) {
                    $tmp['fin']     = $this->writeDate($fin);
                }
                $manager            = new ManagerPresence();
                $presences          = $manager->selectionner(['idEmploye' => $employe->getIdEmploye()], ['date'=> $debut], ['date' => $fin]);
                if (count($presences) == 1) {
                    $tmp['permission'] = $this->estEnPermission($employe, $presences[0]->getDate());
                    $tmp['repos']      = $this->estEnRepos($employe, $presences[0]->getDate());
                    $tmp['conge']      = $this->estEnConge($employe, $presences[0]->getDate());
                    $tmp['presence']   = $presences[0];
                    $manager           = new ManagerPointage();
                    $pointages         = $manager->lister(['idPresence' => $presences[0]->getIdPresence()]);
                    if (count($pointages) > self::NO) {
                        $tmp['arrivee']    = $pointages[0]->getDebut();
                        foreach ($pointages as $pointage) {
                            if ($pointage->getDebut() != null && $pointage->getFin() != null) {
                                $tmp['depart'] = $pointage->getFin();
                            }
                        }
                    }
                }
                $tempsTotal         = 0;
                if (count($presences) > self::NO) {
                    foreach ($presences as $presence) {
                        if ($presence->getStatut() == self::PRESENT_YES) {
                            $tmp['nombrePresences']++;
                            $manager    = new ManagerRetard();
                            $retard     = $manager->chercher([
                                'id_employe'    => $employe->getIdEmploye(),
                                'id_presence'   => $presence->getIdPresence(),
                                'date'          => 'LIKE "%' . $presence->getDate() . '%"'
                           ]);
                            if ($retard) {
                                $tmp['nombreRetard']++;
                                $retardTotal += strtotime($retard->getDuring());
                            }
                        } elseif ($presence->getStatut() == self::PRESENT_NO) {
                            $jour = $this->getDayLetter(date("D", strtotime($presence->getDate())));
                            if ($jour != self::SATURDAY && $jour != self::SUNDAY) {
                                $manager = new ManagerEntrepriseFerie();
                                $ferie   = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise(), 'date' => $presence->getDate()]);
                                if ($ferie == null) {
                                    $permission = $this->estEnPermission($employe, $presence->getDate());
                                    $repos      = $this->estEnRepos($employe, $presence->getDate());
                                    $conge      = $this->estEnConge($employe, $presence->getDate());
                                    if ($repos != null) {
                                        if (!isset($jourRepos)) {
                                            $jourRepos = 0;
                                        }
                                        $jourRepos += $repos->getDuree();
                                    }
                                    if ($permission != false) {
                                        $tmp['nombrePermissions']++;
                                    } elseif ($repos != false) {
                                        $tmp['nombreRepos']++;
                                    } elseif ($conge != false) {
                                        $tmp['nombreConges']++;
                                    } else {
                                        $tmp['nombreAbsences']++;
                                    }
                                }
                            }
                        }
                        $manager        = new ManagerPointage();
                        $pointages      = $manager->lister(['idPresence' => $presence->getIdPresence()]);
                        foreach ($pointages as $pointage) {
                            if ($pointage->getDebut() != null && $pointage->getFin() != null) {
                                $tempsTotal += abs(strtotime($pointage->getFin()) - strtotime($pointage->getDebut()));
                            }
                        }
                    }
                } else {
                    $tmp['nombrePresences']     = null;
                    $tmp['nombreRepos']         = null;
                    $tmp['nombreAbsences']      = null;
                    $tmp['nombrePermissions']   = null;
                    $tmp['nombreConges']        = null;
                }
                $tmp['temps']   = $this->getDuree($tempsTotal);
                $tmp['retard']  = $this->getDuree($retardTotal);
                if ($type == self::PRESENT_YES) {
                    if (($tmp['presence'] != null && $tmp['presence']->getStatut() == self::PRESENT_YES) || $tmp['nombrePresences'] > 0) {
                        $resultats[] = $tmp;
                    }
                } elseif ($type == self::EN_PERMISSION) {
                    if (($tmp['permission'] != null && $tmp['permission']->getStatut() == self::VALIDATED) || $tmp['nombrePermissions'] > 0) {
                        $resultats[] = $tmp;
                    }
                } elseif ($type == self::AU_REPOS) {
                    if (($tmp['repos'] != null && $tmp['repos']->getStatut() == self::VALIDATED) || $tmp['nombreRepos'] > 0) {
                        $resultats[] = $tmp;
                    }
                } elseif ($type == self::EN_CONGE) {
                    if ($tmp['conge'] == true || $tmp['nombreRepos'] > 0) {
                        $resultats[] = $tmp;
                    }
                } elseif ($type == self::PRESENT_NO) {
                    if (($tmp['presence'] != null && $tmp['presence']->getStatut() == self::PRESENT_NO && $tmp['repos'] == null && $tmp['conge'] == null && $tmp['permission'] == null) || $tmp['nombreAbsences'] > 0) {
                        $resultats[] = $tmp;
                    }
                }elseif ($type == self::LATE) {
                    if ($tmp['retard'] != null && $tmp['nombreRetard'] > 0) {
                        $resultats[] = $tmp;
                    }
                } else {
                    $resultats[]   = $tmp; 
                }
            }
            return $resultats;
        }

        /**
         * Vérifier si un employé est en permission à une date donnée
         *
         * @param object  $employe L'employé en question
         * @param date    $date    La date en question
         *
         * @return object|false
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
            $manager            = new ManagerEntreprisePermission();
            // foreach ($employePermissions as $employePermission) {
            //     $manager              = new ManagerEntreprisePermission();
            //     $entreprisePermission = $manager->chercher(['idEntreprisePermission' => $employePermission->getIdEntreprisePermission()]);
            //     $tmpDuree             = $entreprisePermission->getDuree();
            //     $tmpDate              = $employePermission->getDate();
            //     while ($tmpDuree > self::NO) {
            //         if ($date == $tmpDate) {
            //             return $employePermission;
            //         }
            //         $tmpDate = date('Y-m-d', strtotime('+1 DAY', strtotime($tmpDate)));
            //         $tmpDuree--;
            //     }
            // }
            // return false;
            return self::findStatusUser($employePermissions, $date, $manager);
        }

        /**
         * Vérifier si un employé a envoyé une demande de permission à une date donnée
         *
         * @param object  $employe L'employé en question
         * @param date    $date    La date en question
         *
         * @return object|false
        */
        private function aDemandePermission($employe, $date)
        {
            $manager            = new ManagerParametrePermission();
            $parametre          = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
            $permissionMax      = $parametre->getDureeMaxPermission();
            $intervalleMin      = date('Y-m-d', strtotime('-' . $permissionMax . ' DAY', strtotime($date)));
            $intervalleMax      = $date;
            $manager            = new ManagerEmployePermission();
            $employePermissions = $manager->selectionner(
                ['idEmploye' => $employe->getIdEmploye(),
                'statut'     => self::PROPOSED],
                ['date'      => $intervalleMin],
                ['date'      => $intervalleMax]
            );
            $manager            = new ManagerEntreprisePermission();
            // foreach ($employePermissions as $employePermission) {
            //     $manager              = new ManagerEntreprisePermission();
            //     $entreprisePermission = $manager->chercher(['idEntreprisePermission' => $employePermission->getIdEntreprisePermission()]);
            //     $tmpDuree             = $entreprisePermission->getDuree();
            //     $tmpDate              = $employePermission->getDate();
            //     while ($tmpDuree > self::NO) {
            //         if ($date == $tmpDate) {
            //             return $employePermission;
            //         }
            //         $tmpDate = date('Y-m-d', strtotime('+1 DAY', strtotime($tmpDate)));
            //         $tmpDuree--;
            //     }
            // }
            // return false;
            return self::findStatusUser($employePermissions, $date, $manager);
        }

        /**
         * Vérifier si un employé est en repos médical à une date donnée
         *
         * @param object  $employe L'employé en question
         * @param date    $date    La date en question
         *
         * @return object|false
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
            // foreach ($employeRepos as $employeRepos) {
            //     $tmpDuree     = $employeRepos->getDuree();
            //     $tmpDate      = $employeRepos->getDate();
            //     while ($tmpDuree > self::NO) {
            //         if ($date == $tmpDate) {
            //             return $employeRepos;
            //         }
            //         $tmpDate = date('Y-m-d', strtotime('+1 DAY', strtotime($tmpDate)));
            //         $tmpDuree--;
            //     }
            // }
            // return false;
            return self::findStatusUser($employeRepos, $date);
        }

        /**
         * Vérifier si un employé a un congé à une date donnée
         *
         * @param object $employe  L'emmployé
         * @param date   $date     La date
         *
         * @return object|false
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
         * Vérifier si un employé a demander un repos médical à une date donnée
         *
         * @param object  $employe L'employé en question
         * @param date    $date    La date en question
         *
         * @return object|false
        */
        private function aDemandeRepos($employe, $date)
        {
            $manager            = new ManagerParametrePermission();
            $parametre          = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
            $reposMax           = $parametre->getDureeMaxRepos();
            $intervalleMin      = date('Y-m-d', strtotime('-' . $reposMax . ' DAY', strtotime($date)));
            $intervalleMax      = $date;
            $manager            = new ManagerEmployeRepos();
            $employeRepos       = $manager->selectionner(
                ['idEmploye' => $employe->getIdEmploye(),
                'statut'     => self::PROPOSED],
                ['date'      => $intervalleMin],
                ['date'      => $intervalleMax]
            );
            // foreach ($employeRepos as $employeRepos) {
            //     $tmpDuree     = $employeRepos->getDuree();
            //     $tmpDate      = $employeRepos->getDate();
            //     while ($tmpDuree > self::NO) {
            //         if ($date == $tmpDate) {
            //             return $employeRepos;
            //         }
            //         $tmpDate = date('Y-m-d', strtotime('+1 DAY', strtotime($tmpDate)));
            //         $tmpDuree--;
            //     }
            // }
            // return false;
            return self::findStatusUser($employeRepos, $date);
        }

        /**
         * Récupérer les demandes de permission d'un groupe d'employés entre 2 dates
         *
         * Cette méthode fonctionne bien mais aucun usage pour l'instant (Lansky)
         * 
         * @param array $employes Liste d'employés
         * @param strting  $type  Le type de permission
         * @param date  $debut    Le début de l'interval de temps
         * @param date  $fin      La fin de l'interval de temps
         *
         * @return array
        */
        public function getPermissions($employes, $type, $debut, $fin)
        {
            $managerValidationPermission    = new ManagerValidationPermission();
            $managerEntreprisePermission    = new ManagerEntreprisePermission();
            $managerEmployePermission       = new ManagerEmployePermission();
            $managerEntrepriseService       = new ManagerEntrepriseService();
            $managerEntreprisePoste         = new ManagerEntreprisePoste();
            $managerContratEmploye          = new ManagerContratEmploye();
            $managerTypePermission          = new ManagerTypePermission();
            $managerServicePoste            = new ManagerServicePoste();
            $managerEntreprise              = new ManagerEntreprise();
            $managerEmploye                 = new ManagerEmploye();
            $managerCompte                  = new ManagerCompte();
            $factory                        = new PublicFonctions();
            if (strpos($debut, '/') !== false) {
                $date = explode("/", $debut);
                $debut = $date[2] . '-' . $date[1] . '-' . $date[0];
            }
            if (strpos($fin, '/') !== false) {
                $date = explode("/", $fin);
                $fin = $date[2] . '-' . $date[1] . '-' . $date[0];
            }
            $resultats = array();
            foreach ($employes as $employe) {
                $tmp['poste']    = null;
                $tmp['service']  = null;
                $tmp['employe']  = $employe;
                $contrat         = $managerContratEmploye->chercher([
                    'idEmploye' => $employe->getIdEmploye(),
                    'statut'    => self::VALIDATED
                ]);
                if ($contrat != null) {
                    $tmpContrat = $contrat;
                    if ($contrat->getPrincipal() != 0) {
                        $contrat = $managerContratEmploye->chercher(['idContratEmploye' => $contrat->getPrincipal()]);
                    }
                    if (is_null($contrat)) {
                        $contrat = $tmpContrat;
                    }
                    $servicePoste       = $managerServicePoste->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                    $poste              = $managerEntreprisePoste->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                    $service            = $managerEntrepriseService->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                    $tmp['poste']       = $poste->getPoste();
                    $tmp['service']     = $service->getService();
                }
                if ($type == self::DEMANDE_ALL) {
                    $demandes   = $managerEmployePermission->selectionner(['idEmploye' => $employe->getIdEmploye()], ['date' => $debut], ['date' => $fin]);
                } else {
                    $demandes   = $managerEmployePermission->selectionner(['idEmploye' => $employe->getIdEmploye(), 'statut' => $type], ['date' => $debut], ['date' => $fin]);
                }
                foreach ($demandes as $demande) {
                    $myValidation = $managerValidationPermission->chercher([
                        'id_compte'             => $_SESSION['compte']['idCompte'],
                        'etat'                  => self::LEAVE_NOT_ARCHIVED,
                        'id_employe_permission' => $demande->getIdEmployePermission()
                    ]);
                    if ($myValidation) {
                        $now            = date("Y-m-d");
                        $hasValidatedBy = [];
                        $validations    = $managerValidationPermission->lister([
                            'etat'                  => self::LEAVE_NOT_ARCHIVED,
                            'id_employe_permission' => $demande->getIdEmployePermission()
                        ]);
                        if ($validations) {
                            foreach ($validations as $validation) {
                                extract($factory::getUserByIdCompte($validation->getIdCompte()));
                                $hasValidatedBy[]   = ['name' => $name, 'status' => $validation->getStatut()];
                            }
                        }
                        if (strtotime($now) <= strtotime($demande->getDate())) {
                            $tmp['isEditable'] = true;
                        } else {
                            if ($demande->getStatut() != self::PROPOSED) {
                                $tmp['isEditable'] = false;
                            } else {
                                $tmp['isEditable'] = true;
                            }
                        }
                        $demande->setDate($this->writeDate($demande->getDate()));
                        $tmp['hasValidatedBy']          = $hasValidatedBy;
                        $tmp['demande']                 = $demande;
                        $tmp['validationPermission']    = $myValidation;
                        $entreprisePermission           = $managerEntreprisePermission->chercher(['idEntreprisePermission' => $demande->getIdEntreprisePermission()]); 
                        $permission                     = $managerTypePermission->chercher(['idTypePermission' => $entreprisePermission->getIdTypePermission()]);
                        $tmp['type']                    = $permission;
                        $resultats[]                    = $tmp;
                    }
                }
            }
            return $resultats;
        }

        /**
         * Récupérer les demandes de repos d'un groupe d'employés entre 2 dates
         *
         * @param array $employes Liste d'employés
         * @param strting  $type  Le type du rerpos
         * @param date  $debut    Le début de l'interval de temps
         * @param date  $fin      La fin de l'interval de temps
         *
         * @return array
        */
        public function getRepos($employes, $type, $debut, $fin)
        {
            if (strpos($debut, '/') !== false) {
                $date = explode("/", $debut);
                $debut = $date[2] . '-' . $date[1] . '-' . $date[0];
            }
            if (strpos($fin, '/') !== false) {
                $date = explode("/", $fin);
                $fin = $date[2] . '-' . $date[1] . '-' . $date[0];
            }
            $resultats = array();
            foreach ($employes as $employe) {
                $tmp['poste']    = null;
                $tmp['service']  = null;
                $tmp['employe']  = $employe;
                $manager         = new ManagerContratEmploye();
                $contrat         = $manager->chercher([
                    'idEmploye' => $employe->getIdEmploye(),
                    'statut'    => self::VALIDATED
                ]);
                if ($contrat != null) {
                    $tmpContrat = $contrat;
                    if ($contrat->getPrincipal() != 0) {
                        $contrat = $manager->chercher(['idContratEmploye' => $contrat->getPrincipal()]);
                    }
                    if (is_null($contrat)) {
                        $contrat = $tmpContrat;
                    }
                    $manager            = new ManagerServicePoste();
                    $servicePoste       = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                    $manager            = new ManagerEntreprisePoste();
                    $poste              = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                    $manager            = new ManagerEntrepriseService();
                    $service            = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                    $tmp['poste']       = $poste->getPoste();
                    $tmp['service']     = $service->getService();
                }
                $manager    = new ManagerEmployeRepos();
                if ($type == self::DEMANDE_ALL) {
                    $demandes   = $manager->selectionner(['idEmploye' => $employe->getIdEmploye()], ['date' => $debut], ['date' => $fin]);
                } else {
                    $demandes   = $manager->selectionner(['idEmploye' => $employe->getIdEmploye(), 'statut' => $type], ['date' => $debut], ['date' => $fin]);
                }
                foreach ($demandes as $demande) {
                    $now                  = date("Y-m-d");
                    if (strtotime($now) <= strtotime($demande->getDate())) {
                        $tmp['isEditable'] = true;
                    } else {
                        if ($demande->getStatut() == self::VALIDATED || $demande->getStatut() == self::REFUSED) {
                            $tmp['isEditable'] = false;
                        } else {
                            $tmp['isEditable'] = true;
                        }
                    }
                    $demande->setDate($this->writeDate($demande->getDate()));
                    $tmp['demande']       = $demande;
                    $resultats[]          = $tmp;
                }
            }
            return $resultats;
        }

        /**
         * Afficher formulaire d'ajout de jour férié
         *
         * @param array $parameters Les données à affficher
         *
         * @return object
        */
        public function afficherFormJourFerie($parameters)
        {
            $manager = new ManagerJourFerie();
            if (!empty($parameters)) {
                $jourFerie = $manager->chercher($parameters);
            } else {
                $jourFerie = $manager->initialiser();
            }
            return [
                'jourFerie' => $jourFerie,
            ];
        }

        /**
         * Afficher le formulaire d'ajout ou de modification d'un type de permission
         *
         * @param array $parameters Les données à afficher
         *
         * @return object
        */
        public function afficherFormPermission($parameters)
        {
            $manager = new ManagerTypePermission();
            if (!empty($parameters)) {
                $permission = $manager->chercher($parameters);
            } else {
                $permission = $manager->initialiser();
            }
            return [
                'permission' => $permission
            ]; 
        }

        /**
         * Mettre à jour un jour férié
         *
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourJourFerie($parameters)
        {
            $manager = new ManagerJourFerie();
            if (reset($parameters) == "") {
                $manager->ajouter($parameters);
            } else {
                $manager->modifier($parameters);
            }
        }

        /**
         * Mettre à jour un type de permission
         *
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourPermission($parameters)
        {
            $manager = new ManagerTypePermission();
            if (reset($parameters) == "") {
                $manager->ajouter($parameters);
            } else {
                $manager->modifier($parameters);
            }
        }


        /**
         * Supprimer un jour férié
         *
         * @param array $parameters Les données à supprimer
         *
         * @return empty
        */
        public function supprimerJourFerie($parameters)
        {
            $manager = new ManagerJourFerie();
            if ($manager->chercher(['idJourFerie' => $parameters['idJourFerie']]) != null){
                $manager->supprimer($parameters);
            }
        }

        /**
         * Supprimer un type de permission
         *
         * @param array $parameters Les données à supprimer
         *
         * @return empty
        */
        public function supprimerPermission($parameters)
        {
            $manager = new ManagerTypePermission();
            if ($manager->chercher(['idTypePermission' => $parameters['idTypePermission']]) != null){
                $manager->supprimer($parameters);
            }
        }

        /**
         * Récupérer les collaboraty
         *
         * @param array $parameters
         *
         * @return empty
        */
        public function getMySubordinate($parameters)
        {
            $factory            = new PublicFonctions();
            $responseUsers      = array();
            $responseMission    = array();
            $taskList           = [];
            if (!isset($parameters['idEmploye'])) {
                $result         =  $_SESSION['compte']['identifiant'] == 'employe' ? $factory->getFiltre($_SESSION['user']['idEntreprise']) : $this->getAllEmployeesFilterByGroup($parameters);
                foreach ($result['employes'] as $value) {
                    $responseUsers[] = $value->toArray();
                }
            }
            $manager            = new ManagerMission();
            $missions           = $manager->initialiser();
            if (isset($parameters['service']) || isset($parameters['idEmploye'])) {
                $missions = $manager->getUserMissions($parameters);
            } elseif (isset($parameters['poste'])) {
                $missions = $manager->lister(['idServicePoste' => $parameters['poste']]);
            }
            $manager            = new ManagerTache();
            foreach ($missions as $mission) {
                $responseMission[]  = $mission->toArray();
                $tasksObject        = $manager->lister(['id_mission' => $mission->getIdMission(), 'idEmploye' => $parameters['idEmploye']]);
                $taskArray          = array();
                if ($tasksObject != NULL) {
                    foreach ($tasksObject as $task) {
                        $taskArray[] = $task->toArray();
                    }
                }
                $taskList[$mission->getIdMission()] = $taskArray;
            }
            echo json_encode(['employes' => $responseUsers, 'missions' => $responseMission, 'taskList' => $taskList]);
            exit();
        }

        /**
         * Voir le tableau de bord du temps de travail du côté de l'employé
         *
         * @param array $parameters Les données à afficher
         *
         * @return array
        */
        public function voirTempsEmploye($parameters)
        {
            $factory                    = new PublicFonctions();
            $managerParametrePointage   = new ManagerParametrePointage();
            $parametrePointage          = $managerParametrePointage->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if ($_SESSION['compte']['identifiant'] == self::USER_EMPLOYE) {
                /**@changeLog 2022-10-19 [EVOL] (Lansky) Ajouter la fonctionnalité de retard compte employé */
                $subordinateLlists  = $this->voirSuiviEmploye(array());
                $hasSubordinate     = count($subordinateLlists['subordonnes']) > 0 ?? false;
                $manager            = new ManagerEntreprise();
                $entreprise         = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                $filtres            = $factory->getFiltre($entreprise->getIdEntreprise());
                if (isset($parameters['page'])) {
                    if ($parameters['page'] == self::RETARD_PAGE || $parameters['page'] == self::PERMISSION_PAGE) {
                        if ($parametrePointage == null) {
                            $manager->ajouter([
                                'idEntreprise' => $entreprise->getIdEntreprise(),
                                'arretActive'   => 0,
                                'heureArret'    => '18:00:00',
                                'heure_debut'   => '08:00:00',
                                'is_fixed_time' => 'YES'
                            ]);
                            $parametrePointage = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                        }
                        return [
                            'entreprise'        => $entreprise,
                            'filtres'           => $filtres,
                            'hasSubordinate'    => $hasSubordinate

                        ];
                    }
                } else {
                    if (empty($_SESSION['journee'])) {
                        $_SESSION['journee']['periode'] = self::THIS_WEEK;
                    }
                    $manager      = new ManagerEmploye();
                    $employe      = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
                    $today        = date('Y-m-d', strtotime('+3 hour', strtotime(gmdate('Y-m-d'))));
                    $now          = date('H:i:s', strtotime('+3 hour', strtotime(gmdate('H:i:s'))));
                    $tacheCourante = null;
                    $_SESSION['tacheCourante'] = null;
                    $manager      = new ManagerPresence();
                    $presence     = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'date' => $today]);
                    if ($presence == null) {
                        $manager->ajouter([
                            'idEmploye' => $employe->getIdEmploye(),
                            'date'      => $today,
                            'statut'    => self::PRESENT_NO
                        ]);
                        $presence     = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'date' => $today]);
                    }
                    $permissions  = $this->getPermissionPossibles([], $employe);
                    if ($presence != null) {
                        $manager  = new ManagerPointage();
                        $pointageCourant = $manager->chercher(['idPresence' => $presence->getIdPresence(), 'statut' => self::OPEN_POINTING]);
                        if ($pointageCourant != null) {
                            $dureeCourante = gmdate ('H:i:s', abs(strtotime($pointageCourant->getDebut()) - strtotime($now)));
                            $manager = new ManagerTache();
                            $tacheCourante = $manager->chercher(['idTache' => $pointageCourant->getIdTache()]);
                            $_SESSION['tacheCourante'] = $tacheCourante;
                        }
                    }
                    $manager       = new ManagerPointage();
                    $pointages     = $manager->lister(['idPresence' => $presence->getIdPresence()]);
                    $listeIdTaches = array();
                    $dureeDay      = 0;
                    foreach ($pointages as $pointage) {
                        if ($pointage->getDebut() != null && $pointage->getFin() != null) {
                            $dureeDay += abs(strtotime($pointage->getFin()) - strtotime($pointage->getDebut())); 
                        }
                        if (!in_array($pointage->getIdTache(), $listeIdTaches)) {
                            $listeIdTaches[] = $pointage->getIdTache();
                        }
                    }
                    $manager      = new ManagerTache();
                    $taches       = array();
                    foreach ($listeIdTaches as $idTache) {
                        $taches[] = $manager->chercher(['idTache' => $idTache]);
                    }
                    $shareTask  = $manager->lister([
                        'idEmploye'     => $_SESSION['user']['idEmploye'],
                        'attributor'    => '>0',
                        'date_debut'    => 'LIKE "' . $today . '%"'
                    ]);
                    /**@changeLog 2022-11-16 [EVOL] (Lansky) Ajouter la/les tâche(s) qui lui a atttribuer ddans sa liste journalière */
                    $taskToBeDone = $manager->lister([
                        'idEmploye'     => $_SESSION['user']['idEmploye'],
                        'date_debut'    => '> "'.date('Y-m-d') . '"'
                    ]);
                    $taches = array_merge($taches, $shareTask,$taskToBeDone);
                    if (!empty($parameters['idTache'])) {
                        if (!empty($parameters['action'])) {
                            if ($parameters['action'] == self::RESUME_ACTION) {
                                if ($presence->getStatut() == self::PRESENT_NO) {
                                    $manager = new ManagerPresence();
                                    $manager->modifier([
                                        'idPresence' => $presence->getIdPresence(),
                                        'statut'     => self::PRESENT_YES
                                    ]);
                                }
                                $manager   = new ManagerPointage();
                                $pointage  = $manager->chercher(['idPresence' => $presence->getIdPresence(), 'idTache' => $parameters['idTache']]);
                                $manager->ajouter([
                                    'idPresence'  => $presence->getIdPresence(),
                                    'idTache'     => $parameters['idTache'],
                                    'debut'       => $now,
                                    'statut'      => self::OPEN_POINTING
                                ]);
                                $manager = new ManagerTache();
                                $tache = $manager->chercher([
                                    'idTache'   => $parameters['idTache'],
                                    'idEmploye' => $employe->getIdEmploye()
                                ]);
                                if ($tache != null) {
                                    $manager->modifier([
                                        'idTache'  => $parameters['idTache'],
                                        'statut'   => self::CURRENT_TASK
                                    ]);
                                }
                                header("Location:" . HOST . "manage/employe/pointage/dashboard");
                                exit();
                            }
                        } else {
                            $manager = new ManagerTache();
                            $tache = $manager->chercher([
                                'idTache'   => $parameters['idTache'],
                                'idEmploye' => $employe->getIdEmploye()
                            ]);
                            if ($tache != null) {
                                $tacheSelectionnee = $manager->chercher(['idTache' => $parameters['idTache']]);
                            } else {
                                $tacheSelectionnee = $manager->initialiser();   
                            }
                        }
                    } else {
                        $tacheSelectionnee = $manager->initialiser();
                    }
                    /**@changeLog 2022-10-20 [EVOL] (Lansky) Ajouter affichage quand un salarié est en retard aujourd'hui */
                    $manager    = new ManagerRetard();
                    $retard     = $manager->chercher([
                        'id_employe'    => $_SESSION['user']['idEmploye'],  
                        'date'          => 'LIKE "%' . date('Y-m-d') . '%"'
                    ]);
                    $manager        = new ManagerMission();
                    $userMissions   = $manager->getUserMissions(['idEmploye' => $_SESSION['user']['idEmploye']]);
                    $manager        = new ManagerTache();
                    $taskList       = [];
                    foreach ($userMissions as $mission) {
                        $taskList[$mission->getIdMission()] = $manager->lister([
                            'id_mission'    => $mission->getIdMission(),
                            'idEmploye'     => $_SESSION['user']['idEmploye']
                        ]);
                    }

                    $managerShift = new ManagerShift();
                    $managerCampaign = new ManagerCampaign();
                    $shiftsUser = $managerShift->lister(['id_employe' => $_SESSION['user']['idEmploye']]);
                    foreach ($shiftsUser as $shift) {
                        $shift->setIdCampaign($managerCampaign->chercher(['id_campaign' => $shift->getIdCampaign()]));
                    }
                    return [
                        'date'              => $today,
                        'dureeDay'          => $this->getDuree($dureeDay),
                        'dureeCourante'     => isset($dureeCourante) ? $dureeCourante : null,
                        'dateLettre'        => $this->writeDate($today),
                        'employe'           => $employe,
                        'taches'            => $taches,
                        'tacheCourante'     => $tacheCourante,
                        'tacheSelectionnee' => $tacheSelectionnee,
                        'permissions'       => $permissions,
                        'retard'            => $retard,
                        'hasSubordinate'    => $hasSubordinate,
                        'filtres'           => $filtres,
                        'userMissions'      => $userMissions,
                        'taskList'          => $taskList,
                        'shiftsUser'        => $shiftsUser,
                        'parametrePointage' => $parametrePointage
                    ];
                }
            }
        }

        /**
         * Récupérer les permissions possibles pour un employé
         *
         * @changeLog 2023-09-14 [OPTIM] (Lansky) Ajout le paramètre $employe
         * 
         * @param array $parameters     Critère des données
         * @param object $employe       Le salarié en question
         *
         * @return array
        */
        public function getPermissionPossibles($parameters, $employe=null)
        {
            if (!empty($parameters)) {
                $manager        = new ManagerEmploye();
                $employe        = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
            }
            if ($employe) {
                $permissions    = array();
                $donnees        = array();
                $manager        = new ManagerTypePermission();
                $allPermissions = $manager->lister();
                $manager        = new ManagerParametrePermission();
                $parametre      = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                $jourMax        = $parametre->getDureeMaxPermission();
                $jourRestant    = $jourMax - $this->getNombreJourPermissionValidee($employe);
                if ($jourRestant > self::NO) {
                    foreach ($allPermissions as $type) {
                        $manager              = new ManagerEntreprisePermission();
                        $entreprisePermission = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise(), 'idTypePermission' => $type->getIdTypePermission()]);
                        if ($entreprisePermission != null) {
                            $manager              = new ManagerEmployePermission();
                            $employePermissions   = $manager->lister([
                                'idEmploye' => $employe->getIdEmploye(), 
                                'idEntreprisePermission' => $entreprisePermission->getIdEntreprisePermission(),
                                'statut' => self::VALIDATED
                            ]);
                            if ($entreprisePermission->getDuree() > 0 && $jourRestant >= $entreprisePermission->getDuree()) {
                                if (count($employePermissions) == self::NO) {
                                    $permissions[] = $type;
                                    $donnees[]     = $type->toArray();
                                } else {
                                    $isPossible = true;
                                    foreach ($employePermissions as $employePermission) {
                                        $yearNow        = date('Y');
                                        $yearPermission = date('Y', strtotime($employePermission->getDate()));
                                        if ($yearPermission == $yearNow) {
                                            $isPossible = false;
                                        }
                                    }
                                    if ($isPossible) {
                                        $donnees[]        = $type->toArray();
                                        $permissions[]    = $type;
                                    }
                                }
                            }
                        }
                    }
                }
                /*if ($_SESSION['compte']['identifiant'] == "entreprise") {
                    echo json_encode($donnees);
                }*/ // On desactive pour le moment
                return $permissions;
            }
        }

        /**
         * Retourner le nombre de repos possibles pour un employé
         * 
         * @changeLog 2023-09-14 [OPTIM] (Lansky) Ajout le paramètre $employe
         *
         * @param array $parameters     Critère des données
         * @param object $employe       Le salarié en question
         *
         * @return array
        */
        public function getReposPossibles($parameters, $employe=null)
        {
            if (!empty($parameters)) {
                $manager        = new ManagerEmploye();
                $employe        = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
            }
            if ($employe) {
                $repos          = array();
                $donnees        = array();
                $manager        = new ManagerParametrePermission();
                $parametre      = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                $jourMax        = $parametre->getDureeMaxRepos();
                $jourRestant    = $jourMax - $this->getNombreJourReposValide($employe);
                $donnees['jourRestant'] = $jourRestant;
                /*if ($_SESSION['compte']['identifiant'] == "entreprise") {
                    echo json_encode($donnees);
                    exit;
                }*/ // On desactive pour le moment
                return $jourRestant;
            }
        }

        /**
         * Retourner le nombre de congés possibles pour un employé
         * 
         * @changeLog 2023-09-14 [OPTIM] (Lansky) Ajout le paramètre $employe
         *
         * @param array $parameters     Critère des données
         * @param object $employe       Le salarié en question
         *
         * @return array
        */
        public function getCongesPossibles($parameters, $employe=null)
        {
            if (!empty($parameters)) {
                $manager           = new ManagerEmploye();
                $employe           = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
            }
            if ($employe) {
                $annee             = date('Y');
                $manager           = new ManagerStockConge();
                $stockConge        = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'annee' => $annee]);
                $dureeDisponible   = $stockConge ? $stockConge->getDuree() : 0;
                return $dureeDisponible;
            }
        }

        /**
         * Récupérer le nombre de jours de permission restants pour un employe dans l'année en cours
         *
         * @param object $employe L'employé concerné
         *
         * @return int
        */
        private function getNombreJourPermissionValidee($employe)
        {
            $manager     = new ManagerEmployePermission();
            $permissions = $manager->lister(['idEmploye' => $employe->getIdEmploye()]);
            $jours       = 0;
            foreach ($permissions as $permission) {
                if ($permission->getStatut() == self::VALIDATED) {
                    $jours += $permission->getDuree();
                }
            }
            return $jours;
        }

        /**
         * Récupérer le nombre de jours de repos restants pour un employe dans l'année en cours
         *
         * @param object $employe L'employé concerné
         *
         * @return int
        */
        private function getNombreJourReposValide($employe)
        {
            $manager     = new ManagerEmployeRepos();
            $reposs      = $manager->lister(['idEmploye' => $employe->getIdEmploye()]);
            $jours       = 0;
            foreach ($reposs as $repos) {
                if ($repos->getStatut() == self::VALIDATED) {
                    $jours               += $repos->getDuree();
                }
            }
            return $jours;
        }

        /**
         * Voir le tableau de bord du temps de travail du côté de l'entreprise
         *
         * @param array $parameters Les données à afficher
         *
         * @return array
        */
        public function voirTempsEntreprise($parameters)
        {
            $factory = new PublicFonctions();
            if ($_SESSION['compte']['identifiant'] == self::USER_ENTREPRISE) {
                if ($parameters['page'] == self::POINTAGE_PAGE) {
                    $manager           = new ManagerEntreprise();
                    $entreprise        = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $manager           = new ManagerParametrePointage();
                    $parametrePointage = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    if ($parametrePointage == null) {
                        $manager->ajouter([
                            'idEntreprise' => $entreprise->getIdEntreprise(),
                            'arretActive'   => 0,
                            'heureArret'    => '18:00:00',
                            'heure_debut'   => '08:00:00',
                            'is_fixed_time' => 'YES'
                        ]);
                        $parametrePointage = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    }
                    return [
                        'entreprise' => $entreprise,
                        'parametre'  => $parametrePointage,
                        'filtres'    => $factory->getFiltre($entreprise->getIdEntreprise())
                    ];
                    } elseif ($parameters['page'] == self::TACHE_REALISEE_PAGE) {
                    $manager            = new ManagerEntreprise();
                    $entreprise         = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $manager            = new ManagerParametrePointage();
                    $parametrePointage  = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $filtres            = $factory->getFiltre($entreprise->getIdEntreprise());
                    $manager            = new ManagerPresence();
                    $presences          = array();
                    if ($parametrePointage == null) {
                        $manager->ajouter([
                            'idEntreprise' => $entreprise->getIdEntreprise(),
                            'arretActive'   => 0,
                            'heureArret'    => '18:00:00',
                            'heure_debut'   => '08:00:00',
                            'is_fixed_time' => 'YES'
                        ]);
                        $parametrePointage = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    }
                    return [
                        'entreprise' => $entreprise,
                        'parametre'  => $parametrePointage,
                        'filtres'    => $filtres,
                        'presences'  => $presences
                    ];
                } elseif ($parameters['page'] == self::PARAMETRE_PAGE) {
                    $manager               = new ManagerEntreprise();
                    $entreprise            = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $manager               = new ManagerTypePermission();
                    $typePermissions       = $manager->lister(['classique' => self::YES]);
                    $manager               = new ManagerEntreprisePermission();
                    $donnees               = array();
                    foreach ($typePermissions as $typePermission) {
                        $entreprisePermission = $manager->chercher([
                            'idTypePermission' => $typePermission->getIdTypePermission(),
                            'idEntreprise'     => $entreprise->getIdEntreprise()
                        ]);
                        if ($entreprisePermission == null) {
                            $entreprisePermission  = $manager->ajouter([
                                'idEntreprise'     => $entreprise->getIdEntreprise(),
                                'idTypePermission' => $typePermission->getIdTypePermission(),
                                'duree'            => 0
                            ]);
                        }
                        $tmp['permission']                = $typePermission;
                        $tmp['entreprisePermission']      = $entreprisePermission;
                        $donnees[]                        = $tmp;
                    }
                    $manager               = new ManagerParametrePermission();
                    $parametrePermission   = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    if ($parametrePermission == null) {
                        $manager->ajouter(['idEntreprise' => $entreprise->getIdEntreprise()]);
                        $parametrePermission   = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    }
                    $entreprisePermissions = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    return [
                        'entreprise'            => $entreprise,
                        'parametre'             => $parametrePermission,
                        'donnees'               => $donnees
                    ];
                } elseif ($parameters['page'] == self::PERMISSION_PAGE) {
                    $manager    = new ManagerEntreprise();
                    $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $manager    = new ManagerEmploye();
                    $employes   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    return [
                        'entreprise' => $entreprise,
                        'employes'   => $employes,
                        'filtres'    => $factory->getFiltre($entreprise->getIdEntreprise())
                    ];
                } elseif ($parameters['page'] == self::REPOS_PAGE) {
                    $manager    = new ManagerEntreprise();
                    $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $manager    = new ManagerEmploye();
                    $employes   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    return [
                        'entreprise' => $entreprise,
                        'employes'   => $employes,
                        'filtres'    => $factory->getFiltre($entreprise->getIdEntreprise())
                    ];
                } elseif ($parameters['page'] == self::FERIE_PAGE) {
                    $manager    = new ManagerEntreprise();
                    $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $manager    = new ManagerJourFerie();
                    $jourFeries = $manager->lister();
                    return [
                        'entreprise' => $entreprise,
                        'jourFeries' => $jourFeries,
                        'annee'      => date('Y')
                    ];
                } elseif ($parameters['page'] == self::RETARD_PAGE) {
                    /**@changeLog 2022-10-13 [EVOL] (Lansky) Ajouter la fonctionnalité de retard */
                    $manager            = new ManagerEntreprise();
                    $entreprise         = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $manager            = new ManagerParametrePointage();
                    $parametrePointage  = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    if ($parametrePointage == null) {
                        $manager->ajouter([
                            'idEntreprise' => $entreprise->getIdEntreprise()
                        ]);
                        $parametrePointage = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    }
                    return [
                        'entreprise'    => $entreprise,
                        'parametre'     => $parametrePointage,
                        'filtres'       => $factory->getFiltre($entreprise->getIdEntreprise())
                    ];
                }
            }
        }

        /**
         * Normaliser le format du temps
         *
         * @param string $time     Temps numérique
         *
         * @return string
        */
        private function normalizeTime($time)
        {
            if (empty($time)) {
                $time = '00';
            }
            return $time < 10 && strlen($time) == 1 ? '0' . $time : $time;
        }

        /**
         * Mettre à jour une tâche
         *
         * @param array $parameters     Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourTache($parameters)
        {
            $today     = date('Y-m-d', strtotime('+3 hour', strtotime(gmdate('Y-m-d'))));
            $manager   = new ManagerPresence();
            $presence  = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye'], 'date' => $today]);
            if (array_key_exists('shareTask', $parameters)) {
                $parameters['dateDebut'] = $parameters['dateDebut'] . ' ' . $this->normalizeTime($parameters['heure']) . ':' . $this->normalizeTime($parameters['minute']) . ':' . $this->normalizeTime($parameters['second']); // Date time format SQL
                foreach (explode(',', $parameters['idEmploye']) as $idEmploye) {
                    $manager    = new ManagerTache();
                    $tache      = $manager->ajouter([
                        'titre'         => $parameters['titre'],
                        'description'   => $parameters['description'],
                        'idEmploye'     => $idEmploye,
                        'estimated'     => $parameters['during'],
                        'attributor'    => $parameters['attributor'],
                        'statut'        => self::FREE_TASK,
                        'date_debut'    => $parameters['dateDebut'],
                        'id_mission'    => $parameters['taskGroup']
                        // 'activities'    => $parameters['activities']
                    ]);
                    // Envoie notification au salarié concerné
                    $content    = $this->generateMessageContent(self::NATURE_TASK, self::TYPE_REQUEST, $tache, false);
                    $object     = "TÂCHE À FAIRE";
                    $manager    = new ManagerEmploye();
                    $employe    = $manager->chercher(['idEmploye' => $idEmploye]);
                    $this->sendMessageNotification($employe->getIdCompte(), $object, $content);
                    $this->sendMailNotification($employe, $content, $object);
                }
            } else {
                if (empty($parameters['idTache'])) {
                    $manager    = new ManagerTache();
                    $tache      = $manager->ajouter([
                        'titre'         => $parameters['titre'],
                        'description'   => $parameters['description'],
                        'idEmploye'     => $parameters['idEmploye'],
                        'statut'        => self::FREE_TASK,
                        'id_mission'    => array_key_exists('taskGroup',$parameters) ? $parameters['taskGroup'] : 0
                    ]);
                } else {
                    $args       = [
                        'idTache'       => $parameters['idTache'],
                        'titre'         => $parameters['titre'],
                        'description'   => $parameters['description'],
                        'id_mission'    => array_key_exists('taskGroup',$parameters) ? $parameters['taskGroup'] : 0
                    ];
                    if (!strstr($_SERVER['HTTP_REFERER'], 'update')) {
                        $args['statut'] = self::FREE_TASK;
                    }
                    $manager    = new ManagerTache();
                    $tache      =$manager->modifier($args);
                }
                if ($tache->getIdTache() != self::NO && !strstr($_SERVER['HTTP_REFERER'], 'update')) {
                    $manager   = new ManagerPointage();
                    $manager->ajouter([
                        'idPresence'  => $presence->getIdPresence(),
                        'idTache'     => $tache->getIdTache(),
                        'statut'      => self::CLOSED_POINTING
                    ]);
                }
            }
        }

        /**
         * Mettre à jour une demande de permission
         *
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourEmployePermission($parameters)
        {
            $managerEntreprisePermission    = new ManagerEntreprisePermission();
            $managerEmployePermission       = new ManagerEmployePermission();
            $managerTypePermission          = new ManagerTypePermission();
            $managerEntreprise              = new ManagerEntreprise();
            $managerEmploye                 = new ManagerEmploye();
            if (!empty($parameters) && (int)$parameters['idTypePermission'] > 0) {
                $employe = $managerEmploye->chercher(['idEmploye' => $parameters['idEmploye']]);
                if (!isset($parameters['settled'])) {
                    $parameters['settled']  = null;
                }
                if ($parameters['settled'] == self::SETTLED) {
                    $settled        = self::SETTLED;
                    $objectNotif    =  $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() . " a réglé par permission son absence";
                } else {
                    $settled        = "NO";
                    $objectNotif    = "Demande de permission de la part de " . $employe->getCivilite() . " "  . $employe->getNom() . " " . $employe->getPrenom();
                }
                $entreprise            = $managerEntreprise->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                $typePermission        = $managerTypePermission->chercher(['idTypePermission' => $parameters['idTypePermission']]);
                $entreprisePermission  = $managerEntreprisePermission->chercher([
                    'idEntreprise'      => $employe->getIdEntreprise(),
                    'idTypePermission'  => $typePermission->getIdTypePermission()
                ]);
                $employePermission     = $managerEmployePermission->ajouter([
                    'idEmploye'                 => $employe->getIdEmploye(),
                    'idEntreprisePermission'    => $entreprisePermission->getIdEntreprisePermission(),
                    'date'                      => $parameters['date'],
                    'settled'                   => $settled,
                    //'duree'                     => $entreprisePermission->getDuree(),
                    'statut'                    => self::PROPOSED
                ]);
                if ($employePermission->getIdEmployePermission() != self::NO) {
                    if ($parameters['settled'] == self::SETTLED) {
                        $content    = $this->generateMessageContent(self::NATURE_PERMISSION, self::TYPE_REQUEST, $employePermission, false);
                        $idMessage  = $this->sendMessageNotification($entreprise->getIdCompte(), $objectNotif, $content);
                        $managerEmployePermission->modifier([
                            'idEmployePermission'   => $employePermission->getIdEmployePermission(),
                            'duree'                 => $entreprisePermission->getDuree(),
                            'statut'                => self::VALIDATED,
                            'idMessage'             => $idMessage
                        ]);
                        // Notifier les supérieurs par mail et par message de notification
                        $this->sendMailNotification($entreprise, $content,$objectNotif);
                        $this->sendChefNotifications($employePermission, $objectNotif, self::NATURE_PERMISSION, self::TYPE_REQUEST);
                        $info = "Votre absence a été reglée par permission !";
                    } else {
                        $factory    = new PublicFonctions();
                        $chef       = $factory->getChief($employe, $employe);
                        self::passerValidation($employe, $chef, $employePermission, $objectNotif);
                        $info = "La demande de permission a été envoyée avec succès !";
                    }
                    $_SESSION['info']['success']    = $info;
                } else {
                    $_SESSION['info']['danger']     = "Echec lors de l'opération !";
                }
            } else {
                $_SESSION['info']['danger']     = "Définissez la raison de votre permission !";
            }
        }

        /**
         * Mettre à jour une permission d'entreprise
         *
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourEntreprisePermission($parameters)
        {
            if (!empty($parameters)) {
                $manager        = new ManagerTypePermission();
                $typePermission = $manager->ajouter([
                    'designation' => $parameters['typePermission'],
                    'classique'   => self::NO
                ]);
                if ($typePermission != null) {
                    $manager    = new ManagerEntreprisePermission();
                    $manager->ajouter([
                        'idTypePermission'  => $typePermission->getIdTypePermission(),
                        'idEntreprise'      => $_SESSION['user']['idEntreprise'],
                        'duree'             => $parameters['duree']
                    ]);
                }
            }
        }

        /**
         * Mettre à jour une demande de repos
         *
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourEmployeRepos($parameters)
        {
            if (!empty($parameters) && $parameters['duree'] > 0) {
                /**@changeLog [EVOL] (Lansky) 2022-06-30 Ajouter fichier image ordonances pour la pièce justificative */
                $folderPath = DOC_ROOT. 'Ressources/images/ordonances/';
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }
                if (!empty($_FILES['justify']['name'])) {
                    $fieldName  = 'justify' . "_" . time() . ".png";
                    $target     = DOC_ROOT. 'Ressources/images/ordonances/' . basename($_FILES['justify']['name']);
                    move_uploaded_file( $_FILES['justify']['tmp_name'], $target);
                    rename($target, $folderPath . $fieldName);
                    $justify = $fieldName;
                }
                if (!isset($justify)) {
                    $justify = '';
                }
                $manager         = new ManagerEmploye();
                $employe         = $manager->chercher(['idEmploye' => $parameters['idEmploye']]);
                $manager         = new ManagerEntreprise();
                $entreprise      = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                $manager         = new ManagerEmployeRepos();
                $employeRepos    = $manager->ajouter([
                    'idEmploye'     => $employe->getIdEmploye(),
                    'raison'        => $parameters['raison'],
                    'date'          => $parameters['date'],
                    'duree'         => $parameters['duree'],
                    'justify'       => $justify,
                    'settled'       => isset($parameters['settled']) ? self::SETTLED : "NO",
                    'statut'        => self::PROPOSED
                ]); 

                if ($employeRepos != null) {
                    $userNames  = ucfirst($employe->getCivilite()) . " " . strtoupper($employe->getNom()) . " " . ucwords($employe->getPrenom());
                    $content    = $this->generateMessageContent(self::NATURE_REPOS, self::TYPE_REQUEST, $employeRepos, false);
                    $object     = $employeRepos->getSettled() == self::SETTLED ? "À modifier par " . $userNames . " a régularisé son absence par repos médical" : "Demande de repos médical de " . $userNames;
                    $this->sendMessageNotification($entreprise->getIdCompte(), $object, $content);
                    $this->sendMailNotification($entreprise, $content,$object, $justify);
                    $this->sendChefNotifications($employeRepos, $object, self::NATURE_REPOS, self::TYPE_REQUEST, $employeRepos->getJustify());
                    $_SESSION['info']['success']    = "La demande de repos médical a été envoyée avec succès !";
                } else {
                    $_SESSION['info']['danger']     = "Echec lors de l'opération !";
                }
            } else {
                $_SESSION['info']['danger']     = "Echec, la durée doit-être supérieur à 0 !";
            }
        }

        /**
         * Mettre à jour un jour férié d'une entreprise
         *
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourEntrepriseFerie($parameters)
        {
            $managerEntrepriseFerie = new ManagerEntrepriseFerie();
            $managerValidationConge = new ManagerValidationConge();
            $managerStockConge      = new ManagerStockConge();
            $managerJourFerie       = new ManagerJourFerie();
            $managerEmploye         = new ManagerEmploye();
            $managerConge           = new ManagerConge();
            $managerMessage         = new ManagerMessage();
            $factory                = new PublicFonctions();
            if (!empty($parameters)) {
                extract($parameters);
                $idEntreprise   = $_SESSION['user']['idEntreprise'];
                $ferie          = $managerJourFerie->chercher(['idJourFerie' => $idJourFerie]);
                $entrepriseFeries           = $managerEntrepriseFerie->lister(['idEntreprise' => $idEntreprise, 'idJourFerie' => $idJourFerie]);
                $exist                      = false;
                $anneeNouveauFerie          = date('Y', strtotime($date));
                foreach ($entrepriseFeries as $entrepriseFerie) {
                    if (date('Y', strtotime($entrepriseFerie->getDate())) == $anneeNouveauFerie) {
                        $idEntrepriseFerie = $entrepriseFerie->getIdEntrepriseFerie();
                        $exist = true;
                        break;
                    }
                }
                if ($exist) {
                    $managerEntrepriseFerie->modifier([
                        'idEntrepriseFerie'  => $idEntrepriseFerie,
                        'idEntreprise'       => $idEntreprise,
                        'idJourFerie'        => $idJourFerie,
                        'date'               => $date
                    ]);
                } else {
                    $conges = $managerConge->lister(['debut' => $date . "' OR fin = '" . $date, 'statut' => 'IN (1,3)']);
                    foreach ($conges as $conge) {
                        $employe    = $managerEmploye->chercher([
                            'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                            'idEmploye'     => $conge->getIdEmploye()
                        ]);
                        $stock      = $managerStockConge->chercher([
                            'annee'     => date('Y', strtotime($date)),
                            'idEmploye' => $conge->getIdEmploye()
                        ]);
                        $addStock   = $stock->getDuree() + 1;
                        if ($conge->getDebut() == $conge->getFin()) {
                            if ($conge->getDuring() > 0) {
                                $addStock   = $stock->getDuree() + ($conge->getDuring() / 8); // Si congé en mi-temps le salarié
                            }
                            $validations = $managerValidationConge->lister(['idConge' => $conge->getIdConge()]);
                            $content = "<p>Bonjour, </p>";
                            $content .= "<p>Nous vous informons que la demande de congé de " . $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() . " pour le <b>" . $factory->writeDate($conge->getDebut()) . "</b> a considéreé comme férié en raison de " . $ferie->getDesignation() . " est le jour férié, renseigner au sein de votre entreprise " . $_SESSION['user']['nom'] . " .</p>";
                            $objectNotif = "Annulation du congé en jour férié";
                            foreach ($validations as $validation) {
                                $idMessage  = $validation->getIdMessage();
                                $idCompte   = $validation->getIdCompte();
                                $managerValidationConge->supprimer(['idValidationConge' => $validation->getIdValidationConge()]);
                                $managerMessage->supprimer(['idMessage' => $idMessage]);
                                // Notifier les validateurs qui ont validé le conge
                                self::sendMessageNotification($idCompte, $objectNotif, $content);
                            }
                            $managerConge->supprimer([
                                'idConge' => $conge->getIdConge()
                            ]);
                            $action     = 'delete';
                            $motif      = 'Annulation';
                            $dateConge  = $factory->writeDate($conge->getDebut(), false);
                            // Annuler le conge du salarié et lui notifier
                        } else {
                            if ($conge->getDebut() == $date) {
                                $debutFin   = 'debut';
                                $modifyDate = "+1";
                            } else {
                                $debutFin   = 'fin';
                                $modifyDate = "-1";
                            }
                            // Modifer le conge du salarié et lui notifier
                            $newDate    = new DateTime($date);
                            $newDate->modify($modifyDate . " day");
                            $changeDate = $newDate->format("Y-m-d");
                            $managerConge->modifier([
                                'idConge'   => $conge->getIdConge(),
                                $debutFin   => $changeDate,
                                'heureFin'  => $conge->getDebut() == $date ? $conge->getHeureFin() : self::SOIR
                            ]);
                            $action     = 'update';
                            $motif      = 'Modification';
                            $dateConge  = $factory->writeDate($conge->getDebut(), false) . " jusqu'à " . $factory->writeDate($conge->getFin(), false);
                        }
                        if ($conge->getStatut() == self::VALIDATED) {
                            $managerStockConge->modifier([
                                'idStockConge'  => $stock->getIdStockConge(),
                                'duree'         => $addStock
                            ]);
                        }
                        // Notification 
                        $objectNotif    = $motif . " du congé en jour férié";
                        $content        = self::generateContent($dateConge, $ferie, $action, $factory->writeDate($date));
                        self::sendMessageNotification($employe->getIdCompte(), $objectNotif, $content);
                        $manager        = new ManagerEmailContact();
                        $to             = $employe->getEmail();
                        $headers[]      = 'MIME-Version: 1.0';
                        $headers[]      = 'Content-type: text/html; charset=iso-8859-1';
                        $headers[]      = "From: HumaNexus <" . strtolower($manager->chercher(['type' => 'information'])->getEmail()) . ">";
                        $content        .= '<br><br> Cordialement,<br><br>L&apos;équipe <a href="https://hco.mg/">Human Cart&apos;Office</a>';
                        mail($to, $subject, $content, implode("\r\n", $headers));
                    }
                    $managerEntrepriseFerie->ajouter($parameters);
                }
            }
        }

        private function generateContent($date, $ferie, $action, $dateFerie)
        {
            $acte       = $action == 'delete' ? 'annulé' : 'modifié';
            $content    = "<p>Bonjour, </p>";
            $content    .= "<p>Nous vous informons que votre demande de congé pour le <b>" . $date . "</b> a " . $acte . " en raison de <i>'" . $ferie->getDesignation() . "'</i> est le jour férié(<i>" . $dateFerie . "</i>), renseigner au sein de votre entreprise " . $_SESSION['user']['nom'] . " .</p>";
            return $content;
        }

        /**
         * Supprimer une tâche
         *
         * @param array $parameters Les données à supprimer
         *
         * @return empty
        */
        public function supprimerTache($parameters)
        {
            $managerTache       = new ManagerTache();
            $managerPointage    = new ManagerPointage();
            if (isset($parameters['idTache'])) {
                $tache = $managerTache->chercher(['idTache' => $parameters['idTache']]);
                if (!is_null($tache)) {
                    $pointages = $managerPointage->lister(['idTache' => $tache->getIdTache()]);
                    foreach ($pointages as $pointage) {
                        $managerPointage->supprimer(['idPointage' => $pointage->getIdPointage()]);
                    }
                    $managerTache->supprimer($parameters);
                }
            }
            // $manager    = new ManagerTache();
            // $tache      = $manager->chercher(['idTache' => $parameters['idTache']]);
            // $manager    = new ManagerPointage();
            // $pointages  = $manager->lister(['idTache' => $tache->getIdTache()]);
            // foreach ($pointages as $pointage) {
            //     $manager->supprimer(['idPointage' => $pointage->getIdPointage()]);
            // }
            // $manager    = new ManagerTache();
            // if ($tache != null) {
            //     $manager->supprimer($parameters);
            // }
        }

        /**
         * Supprimer un jour férié d'une entreprise
         *
         * @param array $parameters Les données à supprimer
         *
         * @return empty
        */
        public function supprimerEntrepriseFerie($parameters)
        {   
            if (!empty($parameters)) {
                $manager    = new ManagerEntrepriseFerie();
                $ferie      = $manager->chercher(['idEntrepriseFerie' => $parameters['idEntrepriseFerie'], 'idEntreprise' => $_SESSION['user']['idEntreprise']]);
                if ($ferie != null) {
                    $manager->supprimer(['idEntrepriseFerie' => $parameters['idEntrepriseFerie']]);
                }
            }
        }

        /**
         * Mettre à jour un paramètre de pointage
         *
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourParametrePointage($parameters)
        {
            $ips        = array ();
            $inc        = 0;
            foreach ($parameters as $key => $value) {
                if (strstr($key, 'ipAddr')) {
                    $ips[] = ['ipName' => $parameters["ipName".$inc], 'ipv4' => $value];
                    $inc++;
                }
            }
            // HCOMEDIALIBS 154.126.33.236, LALA 41.188.58.158,
            $parameters['arretActive']              = empty($parameters['arretActive']) ? self::NO : self::YES;
            $parameters['arretActiveNotification']  = empty($parameters['arretActiveNotification']) ? self::NO : self::YES;
            $manager = new ManagerParametrePointage();
            $retour  = $manager->modifier([
                'idParametrePointage'   => $parameters['idParametrePointage'],
                'arretActive'           => $parameters['arretActive'],
                'list_ip'               =>  serialize($ips),
                'heureArret'            => date('H:i:s', strtotime($parameters['heureArret'])),
                'heure_debut'           => date('H:i:s', strtotime($parameters['heureDebut'])),
                'is_fixed_time'         => $parameters['isFixedTime'],
                'receive_notif'         => $parameters['arretActiveNotification']
            ]);
            if ($retour->getArretActive() == $parameters['arretActive'] && $retour->getHeureArret() == $parameters['heureArret'] && $retour->getHeureDebut() == $parameters['heureDebut']) {
                $_SESSION['info']['success'] = "Le système de pointage a été modifié avec succès";
            } else {
                $_SESSION['info']['danger'] = "Echec lors de l'opération";
            }
        }

        /**
         * Mettre à jour un pointage
         *
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
        */
        public function mettreAJourPointage($parameters)
        {
            $today     = date('Y-m-d', strtotime('+3 hour', strtotime(gmdate('Y-m-d'))));
            $now       = date('H:i:s', strtotime('+3 hour', strtotime(gmdate('H:i:s'))));
            $manager   = new ManagerEmploye();
            $employe   = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
            $manager   = new ManagerPresence();
            $presence  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'date' => $today]);
            if ($presence == null) {
                $manager->ajouter([
                    'idEmploye'  => $employe->getIdEmploye(),
                    'date'       => $today,
                    'statut'     => PRESENT_NO
                ]);
                $presence  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'date' => $today]);
            }
            if (!empty($parameters['idTache'])) {
                $manager = new ManagerTache();
                $tache   = $manager->chercher(['idTache' => $parameters['idTache']]);
                if ($parameters['action'] == self::START_ACTION) {
                    if ($presence->getStatut() == self::PRESENT_NO) {
                        $manager  = new ManagerPresence();
                        $manager->modifier([
                            'idPresence' => $presence->getIdPresence(),
                            'statut'     => self::PRESENT_YES
                        ]);
                    }
                    $manager  = new ManagerPointage();
                    if ($tache->getStatut() == self::FREE_TASK) {
                        $pointage = $manager->chercher(['idPresence' => $presence->getIdPresence(), 'idTache' => $tache->getIdTache()]);
                        if (is_null($pointage)) {
                            $pointage = $manager->ajouter([
                                'idPresence' => $presence->getIdPresence(),
                                'idTache'    => $tache->getIdTache(),
                                'debut'      => $now,
                                'statut'     => self::CLOSED_POINTING
                            ]);
                        }
                        $pointage = $manager->modifier([
                            'idPointage' => $pointage->getIdPointage(),
                            'debut'      => $now,
                            'statut'     => self::OPEN_POINTING
                        ]);
                    } elseif ($tache->getStatut() == self::PAUSED_TASK) {
                        $pointage = $manager->ajouter([
                            'idPresence' => $presence->getIdPresence(),
                            'idTache'    => $tache->getIdTache(),
                            'debut'      => $now,
                            'statut'     => self::OPEN_POINTING
                        ]);
                    }
                    if (isset($pointage)) {
                        if ($pointage != null) {
                            $manager = new ManagerTache();
                            $manager->modifier([
                                'idTache'  => $tache->getIdTache(),
                                'statut'   => self::CURRENT_TASK
                            ]);
                        }
                    }
                } elseif ($parameters['action'] == self::STOP_ACTION) {
                    $manager  = new ManagerPointage();
                    $pointage = $manager->chercher([
                        'idPresence' => $presence->getIdPresence(),
                        'idTache'    => $tache->getIdTache(),
                        'statut'     => self::OPEN_POINTING
                    ]);
                    if ($pointage != null) {
                        $pointage = $manager->modifier([
                            'idPointage' => $pointage->getIdPointage(),
                            'fin'        => $now,
                            'statut'     => self::CLOSED_POINTING
                        ]);
                        $manager = new ManagerTache();
                        $manager->modifier([
                            'idTache'  => $tache->getIdTache(),
                            'statut'   => self::PAUSED_TASK
                        ]);
                    }
                }
            }
        }

        /**
         * Incrementer la durée d'une permission d'une entreprise
         *
         * @param array $parameters Les critères de la table à incrémenter
         *
         * @return empty
        */
        public function incrementerPermission($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerEntreprisePermission();
                $entreprisePermission = $manager->chercher([
                    'idEntreprise'           => $_SESSION['user']['idEntreprise'],
                    'idEntreprisePermission' => $parameters['idEntreprisePermission']
                ]);
                if ($entreprisePermission != null) {
                    $resultat = $manager->modifier([
                        'idEntreprisePermission'  => $entreprisePermission->getIdEntreprisePermission(),
                        'duree'                   => $entreprisePermission->getDuree() + 1
                    ]);
                    if ($resultat->getDuree() == $entreprisePermission->getDuree() + 1) {
                        echo $resultat->getDuree();
                        exit();
                    } else {
                        echo -1;
                        exit();
                    }
                }
            }
        }

        /**
         * Décrementer la durée d'une permission d'une entreprise
         *
         * @param array $parameters Les critères de la table à incrémenter
         *
         * @return empty
        */
        public function decrementerPermission($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerEntreprisePermission();
                $entreprisePermission = $manager->chercher([
                    'idEntreprise'            => $_SESSION['user']['idEntreprise'],
                    'idEntreprisePermission'  => $parameters['idEntreprisePermission']
                ]);
                if ($entreprisePermission != null) {
                    $duree = $entreprisePermission->getDuree() - 1;
                    if ($duree >= 0) {
                        $resultat = $manager->modifier([
                            'idEntreprisePermission'   => $entreprisePermission->getIdEntreprisePermission(),
                            'duree'                    => $duree
                        ]);
                        if ($resultat->getDuree() == $entreprisePermission->getDuree() - 1) {
                            echo $resultat->getDuree();
                            exit();
                        } else {
                            echo -1;
                            exit();
                        }
                    }
                }
            }
        }

        /**
         * Incrementer la durée maximale d'une permission d'une entreprise
         *
         * @param array $parameters Les critères de la table à incrémenter
         *
         * @return empty
        */
        public function incrementerDureeMaxPermission($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerParametrePermission();
                $parametrePermission = $manager->chercher([
                    'idEntreprise'           => $_SESSION['user']['idEntreprise'],
                    'idParametrePermission'  => $parameters['idParametrePermission']
                ]);
                if ($parametrePermission != null) {
                    $manager->modifier([
                        'idParametrePermission'   => $parametrePermission->getIdParametrePermission(),
                        'dureeMaxPermission'      => $parametrePermission->getDureeMaxPermission() + 1
                    ]);
                }
            }
        }

        /**
         * Incrementer la durée maximale d'un repos d'une entreprise
         *
         * @param array $parameters Les critères de la table à incrémenter
         *
         * @return empty
        */
        public function incrementerDureeMaxRepos($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerParametrePermission();
                $parametrePermission = $manager->chercher([
                    'idEntreprise'           => $_SESSION['user']['idEntreprise'],
                    'idParametrePermission'  => $parameters['idParametrePermission']
                ]);
                if ($parametrePermission != null) {
                    $manager->modifier([
                        'idParametrePermission'   => $parametrePermission->getIdParametrePermission(),
                        'dureeMaxRepos'           => $parametrePermission->getDureeMaxRepos() + 1
                    ]);
                }
            }
        }

        /**
         * Décrementer la durée maximale d'une permission d'une entreprise
         *
         * @param array $parameters Les critères de la table à incrémenter
         *
         * @return empty
        */
        public function decrementerDureeMaxPermission($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerParametrePermission();
                $parametrePermission = $manager->chercher([
                    'idEntreprise'           => $_SESSION['user']['idEntreprise'],
                    'idParametrePermission'  => $parameters['idParametrePermission']
                ]);
                if ($parametrePermission != null) {
                    $duree = $parametrePermission->getDureeMaxPermission() - 1;
                    if ($duree >= self::NORME_DUREE_MAX_PERMISSION) {
                        $manager->modifier([
                            'idParametrePermission'   => $parametrePermission->getIdParametrePermission(),
                            'dureeMaxPermission'      => $duree
                        ]);
                    }
                }
            }
        }

        /**
         * Décrementer la durée maximale d'un repos médical d'une entreprise
         *
         * @param array $parameters Les critères de la table à incrémenter
         *
         * @return empty
        */
        public function decrementerDureeMaxRepos($parameters)
        {
            if (!empty($parameters)) {
                $manager   = new ManagerParametrePermission();
                $parametrePermission = $manager->chercher([
                    'idEntreprise'           => $_SESSION['user']['idEntreprise'],
                    'idParametrePermission'  => $parameters['idParametrePermission']
                ]);
                if ($parametrePermission != null) {
                    $duree = $parametrePermission->getDureeMaxRepos() - 1;
                    if ($duree >= self::NORME_DUREE_MAX_Repos) {
                        $manager->modifier([
                            'idParametrePermission'   => $parametrePermission->getIdParametrePermission(),
                            'dureeMaxRepos'           => $duree
                        ]);
                    }
                }
            }
        }

        /**
         * Valider une demande de permission
         *
         * @param array $parameters Les critères des données à valider
         *
         * @return empty
        */
        public function validerDemandePermission($parameters)
        {
            $managerValidationPermission    = new ManagerValidationPermission();
            $managerEmployePermission       = new ManagerEmployePermission();
            $managerEmploye                 = new ManagerEmploye();
            $validationPermission           = null;
            if (!empty($parameters)) {
                extract($parameters);
                if ($_SESSION['compte']['identifiant'] == 'entreprise') {
                    self::validerDemandePermissionEntreprise($parameters);
                } else {
                    $employePermission = $managerEmployePermission->chercher([
                        'idEmployePermission'   => $idEmployePermission,
                        'idEmploye'             => $demandeur
                    ]);
                    $demandeur           = $managerEmploye->chercher([
                        'idEmploye'    => $demandeur,
                        'idEntreprise' => $_SESSION['user']['idEntreprise']
                    ]);
                    $currentUser        = $managerEmploye->chercher([
                        'idEmploye'    => $_SESSION['user']['idEmploye'],
                        'idEntreprise' => $_SESSION['user']['idEntreprise']
                    ]);
                    $objectNotif    = "Demande de permission de la part de " . $demandeur->getCivilite() . " "  . $demandeur->getNom() . " " . $demandeur->getPrenom();
                    if ($demandeur != null && $employePermission->getStatut() != self::VALIDATED) {
                        $factory    = new PublicFonctions();
                        $chef       = $factory->getChief($currentUser, $demandeur);
                        self::passerValidation($currentUser, $chef, $employePermission, $objectNotif);
                    }
                }
                $validationPermission   = $managerValidationPermission->modifier([
                    'id_validation_permission'  => $idValidationPermission,
                    'statut'                    => self::VALIDATED
                ]);
                if ($validationPermission->getStatut() == self::VALIDATED) {
                    $_SESSION['info']['success']    = "La demande de permission a été validée avec succès !";
                } else {
                    $_SESSION['info']['danger']     = "Echec lors de l'opération !";
                }
            } else {
                $_SESSION['info']['danger']     = "Validation impossible !";
            }
        }

        /**
         * Valider une demande de la permission final pour l'entreprise
         *
         * @changeLog 2023-06-21 (Lansky) Modification de la méthode
         * 
         * @param array $parameters Les critères des données à valider
         *
         * @return empty
        */
        private function validerDemandePermissionEntreprise($parameters)
        {
            extract($parameters);
            $managerEntreprisePermission    = new ManagerEntreprisePermission();
            $managerValidationPermission    = new ManagerValidationPermission();
            $managerEmployePermission       = new ManagerEmployePermission();
            $managerEmploye                 = new ManagerEmploye();
            $managerMessage                 = new ManagerMessage();
            if (!empty($parameters)) {
                $employePermission = $managerEmployePermission->chercher(['idEmployePermission' => $idEmployePermission]);
                $employe           = $managerEmploye->chercher([
                    'idEmploye'    => $employePermission->getIdEmploye(),
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($employe != null && $employePermission->getStatut() != self::VALIDATED) {
                    $entreprisePermission   = $managerEntreprisePermission->chercher(['idEntreprisePermission' => $employePermission->getIdEntreprisePermission()]);
                    $duree                  = $entreprisePermission->getDuree();
                    $employePermission      = $managerEmployePermission->modifier([
                        'idEmployePermission' => $idEmployePermission,
                        'statut'              => self::VALIDATED,
                        'duree'               => $duree
                    ]);
                    if ($employePermission->getStatut() == self::VALIDATED) {
                        $employePermission  = $managerEmployePermission->chercher(['idEmployePermission' => $idEmployePermission]);
                        $content            = $this->generateMessageContent(self::NATURE_PERMISSION, self::TYPE_VALIDATED, $employePermission);
                        $objectNotif        = "Demande de permission validée";
                        $idMessage          = $this->sendMessageNotification($employe->getIdCompte(), $objectNotif, $content);
                        $this->sendMailNotification($employe, $content, $objectNotif);
                        if ($employe->getChefHierarchique() != self::NO) {
                            $today   = date('Y-m-d');
                            if (strtotime($employePermission->getDate()) > strtotime($today)) {
                                $content        = $this->generateMessageContent(self::NATURE_PERMISSION, self::TYPE_INFORMATION, $employePermission);
                                $objectNotif    = "Information concernant " . $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom();
                                $validatoinList = $managerValidationPermission->lister([
                                    'idEmployePermission'   => $idEmployePermission,
                                    'idMessage'             => $idMessage
                                ]);
                                foreach ($validatoinList as $validation) {
                                    $chef   = $managerEmploye->chercher([
                                        'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                                        'idCompte'      => $validation->getIdCompte()
                                    ]);
                                    $this->sendMessageNotification($chef->getIdCompte(), $objectNotif, $content);
                                    $this->sendMailNotification($chef, $content, $objectNotif);
                                }
                            }
                        }
                        // $message   = $managerMessage->chercher(['idMessage' => $employePermission->getIdMessage()]);
                        // if ($message != null) {
                        //     $managerMessage->modifier([
                        //         'idMessage'   => $message->getIdMessage(),
                        //         'statut'      => self::VU
                        //     ]);
                        // }
                    }
                }
            }
        }

        /**
         * Valider une demande de repos
         *
         * @param array $parameters Les critères des données à valider
         *
         * @return empty
        */
        public function validerDemandeRepos($parameters)
        {
            if (!empty($parameters)) {
                $manager           = new ManagerEmployeRepos();
                $employeRepos      = $manager->chercher(['idEmployeRepos' => $parameters['idEmployeRepos']]);
                $manager           = new ManagerEmploye();
                $employe           = $manager->chercher([
                    'idEmploye'    => $employeRepos->getIdEmploye(),
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($employe != null && $employeRepos->getStatut() != self::VALIDATED) {
                    $manager             = new ManagerEmployeRepos();
                    $employeRepos        = $manager->modifier([
                        'idEmployeRepos' => $parameters['idEmployeRepos'],
                        'statut'         => self::VALIDATED
                    ]);
                    if ($employeRepos != null) {
                        $employeRepos   = $manager->chercher(['idEmployeRepos' => $parameters['idEmployeRepos']]);
                        $content        = $this->generateMessageContent(self::NATURE_REPOS, self::TYPE_VALIDATED, $employeRepos);
                        $objectNotif    = "Demande de repos médical validée";
                        $this->sendMessageNotification($employe->getIdCompte(), $objectNotif, $content);
                        $this->sendMailNotification($employe, $content, $objectNotif);
                        if ($employe->getChefHierarchique() != self::NO) {
                            $today   = date('Y-m-d');
                            if (strtotime($employeRepos->getDate()) > strtotime($today)) {
                                $tmpEmploye = $employe;
                                while ($tmpEmploye->getChefHierarchique() != self::NO) {
                                    $manager        = new ManagerEmploye();
                                    $chef           = $manager->chercher(['idEmploye' => $tmpEmploye->getChefHierarchique()]);
                                    $content        = $this->generateMessageContent(self::NATURE_REPOS, self::TYPE_INFORMATION, $employeRepos);
                                    $objectNotif    = "Information concernant " . $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getCivilite();
                                    $this->sendMessageNotification($chef->getIdCompte(), $objectNotif, $content);
                                    $this->sendMailNotification($chef, $content, $objectNotif);
                                    $tmpEmploye = $chef;
                                }
                            }
                        }
                        $_SESSION['info']['success']    = "La demande de repos a été validée avec succès !";
                    } else {
                        $_SESSION['info']['danger']     = "Echec lors de l'opération !";
                    }
                }
            }
        }

        /**
         * Rejeter une demande de permission
         *
         * @param array $parameters Les critères des données à valider
         *
         * @return empty
        */
        public function rejeterDemandePermission($parameters)
        {
            $managerValidationPermission    = new ManagerValidationPermission();
            $managerEmployePermission       = new ManagerEmployePermission();
            $managerMessage                 = new ManagerMessage();
            $managerEmploye                 = new ManagerEmploye();
            if (!empty($parameters)) {
                extract($parameters);
                $employePermission = $managerEmployePermission->chercher(['idEmployePermission' => $idEmployePermission]);
                $demandeur         = $managerEmploye->chercher([
                    'idEmploye'    => $employePermission->getIdEmploye(),
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($demandeur != null && $employePermission->getStatut() != self::REFUSED) {
                    $managerEmployePermission->modifier([
                        'idEmployePermission' => $idEmployePermission,
                        'statut'              => self::REFUSED
                    ]);
                    $managerValidationPermission->modifier([
                        'id_validation_permission'  => $idValidationPermission,
                        'statut'                    => self::REFUSED
                    ]);
                    $employePermission  = $managerEmployePermission->chercher(['idEmployePermission'   => $idEmployePermission]);
                    if ($employePermission->getStatut() == self::REFUSED) {
                        $content            = $this->generateMessageContent(self::NATURE_PERMISSION, self::TYPE_REJECTED, $employePermission);
                        $objectNotif        = "Demande de permission refusée par " . $_SESSION['user']['nom'];
                        $idMessage          = $this->sendMessageNotification($demandeur->getIdCompte(), $objectNotif, $content);
                        $this->sendMailNotification($demandeur, $content, $objectNotif);
                        $managerEmployePermission->modifier([
                            'idEmployePermission'   => $idEmployePermission,
                            'idMessage'             => $idMessage,
                            'motif_refus'           => $motifRefus
                        ]);
                        $_SESSION['info']['success']    = "La demande de repos a été rejetée !";
                    } else {
                        $_SESSION['info']['danger']     = "Echec lors de l'opération !";
                    }
                }
            }
        }

        /**
         * Rejeter une demande de repos
         *
         * @param array $parameters Les critères des données à valider
         *
         * @return empty
        */
        public function rejeterDemandeRepos($parameters)
        {
            if (!empty($parameters)) {
                $manager           = new ManagerEmployeRepos();
                $employeRepos      = $manager->chercher(['idEmployeRepos' => $parameters['idEmployeRepos']]);
                $manager           = new ManagerEmploye();
                $employe           = $manager->chercher([
                    'idEmploye'    => $employeRepos->getIdEmploye(),
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($employe != null && $employeRepos->getStatut() != self::REFUSED) {
                    $manager    = new ManagerEmployeRepos();
                    $employeRepos = $manager->modifier([
                        'idEmployeRepos' => $parameters['idEmployeRepos'],
                        'statut'              => self::REFUSED
                    ]);
                    if ($employeRepos != null) {
                        $employeRepos      = $manager->chercher(['idEmployeRepos' => $parameters['idEmployeRepos']]);
                        $content = $this->generateMessageContent(self::NATURE_REPOS, self::TYPE_REJECTED, $employeRepos);
                        $objectNotif = "Demande de repos médical refusée";
                        $this->sendMessageNotification($employe->getIdCompte(), $objectNotif, $content);
                        $this->sendMailNotification($employe, $content, $objectNotif);
                        $_SESSION['info']['success']    = "La demande de repos a été rejetée !";
                    } else {
                        $_SESSION['info']['danger']     = "Echec lors de l'opération !";
                    }
                }
            }
        }

        /**
         * Supprimer une demande de repos
         *
         * @param array $parameters Les critères des données à valider
         *
         * @return empty
        */
        public function supprimerDemandeRepos($parameters)
        {
            if (!empty($parameters)) {
                $manager           = new ManagerEmployeRepos();
                $employeRepos      = $manager->chercher(['idEmployeRepos' => $parameters['idEmployeRepos']]);
                $manager           = new ManagerEmploye();
                $employe           = $manager->chercher([
                    'idEmploye'     => $employeRepos->getIdEmploye(),
                    'idEntreprise'  => $_SESSION['user']['idEntreprise']
                ]);
                /**
                 * @changeLog 2023-06-08 [FIX] (Lansky) envoyer la notification au(x) autre(s)
                */
                if ($employe != null) {
                    $manager        = new ManagerEntreprise();
                    $entreprise     = $manager->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                    $content        = $this->generateMessageContent(self::NATURE_REPOS, self::TYPE_CANCELED, $employeRepos);
                    $objectNotif    = "Annulation d'une demande de repos médical [" . $employe->getNom() . " " . $employe->getPrenom() . "]";
                    $objectNotif    = array_key_exists('idEmploye', $_SESSION['user']) ? ($_SESSION['user']['idEmploye'] == $employe->getIdEmploye() ? $objectNotif : null) : (($_SESSION['compte']['identifiant'] == 'entreprise' && $_SESSION['user']['idEntreprise'] == $employe->getIdEntreprise()) ? $objectNotif . " de la part" . ucwords($_SESSION['user']['nom']) : null);
                    if ($objectNotif == null) {
                        $_SESSION['info']['danger'] = "Opération impossible, vous n'avez pas le droit !";
                    } else {
                        $receiveur = ($_SESSION['compte']['identifiant'] == 'employe') ? $entreprise : $employe;
                        $this->sendMessageNotification($receiveur->getIdCompte(), $objectNotif, $content);
                        /**@changeglog [EVOL] Lansky 2022-07-29 Envoyer les notifications mail message aux suppérieurs*/
                        $this->sendMailNotification($receiveur, $content, $objectNotif);
                        $this->sendChefNotifications($employeRepos, $objectNotif, self::NATURE_REPOS, self::TYPE_CANCELED);
                        $manager        = new ManagerEmployeRepos();
                        $retour         = $manager->supprimer([
                            'idEmployeRepos' => $parameters['idEmployeRepos']
                        ]);
                        if ($retour) {
                            $_SESSION['info']['success']    = "La demande de repos a été annulée avec succès !";
                        } else {
                            $_SESSION['info']['danger']     = "Echec lors de l'opération !";
                        }
                    }
                }
            }
        }

        /**
         * Supprimer une demande de permission
         *
         * @param array $parameters Les critères des données à valider
         *
         * @return empty
        */
        public function supprimerDemandePermission($parameters)
        {
            
            if (!empty($parameters)) {
                extract($parameters);
                $managerValidationPermission    = new ManagerValidationPermission();
                $managerEmployePermission       = new ManagerEmployePermission();
                $managerEntreprise              = new ManagerEntreprise();
                $managerEmploye                 = new ManagerEmploye();
                $managerMessage                 = new ManagerMessage();
                $employePermission              = $managerEmployePermission->chercher(['idEmployePermission' => $idEmployePermission]);
                $demandeur                      = $managerEmploye->chercher([
                    'idEmploye'    => $employePermission->getIdEmploye(),
                    'idEntreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($demandeur != null) {
                    $entreprise     = $managerEntreprise->chercher(['idEntreprise' => $demandeur->getIdEntreprise()]);
                    $content        = $this->generateMessageContent(self::NATURE_PERMISSION, self::TYPE_CANCELED, $employePermission, true);
                    $objectNotif    = "Annulation d'une demande de permission [" . $demandeur->getCivilite() . " " . $demandeur->getNom() . " " . $demandeur->getPrenom() . "]";
                    if ($demandeur->getIdCompte() == $_SESSION['compte']['idCompte']) {
                        /**@changeglog [EVOL] Lansky 2022-07-29 Envoyer les notifications mail message aux suppérieurs*/
                        $this->sendChefNotifications($employePermission, $objectNotif, self::NATURE_PERMISSION, self::TYPE_CANCELED);
                        $destinateur = $entreprise;
                    } else {
                        $destinateur = $demandeur;
                        $validations = $managerValidationPermission->lister(['id_employe_permission' => $idEmployePermission]);
                        foreach ($validations as $validation) {
                            $idMessage = $validation->getIdMessage();
                            $this->sendMessageNotification($validation->getIdCompte(), $objectNotif, $content);
                            $managerValidationPermission->supprimer(['id_validation_permission'  => $validation->getIdValidationPermission()]);
                            $managerMessage->supprimer(['idMessage' => $idMessage]);
                        }
                        $content = str_replace('sa', 'votre', $content); // Rechercher et remplacer le mot 'votre' en 'sa'
                    }
                    $this->sendMessageNotification($destinateur->getIdCompte(), $objectNotif, $content);
                    $this->sendMailNotification($destinateur, $content, $objectNotif);
                    $employePermission = $managerEmployePermission->supprimer([
                        'idEmployePermission' => $idEmployePermission
                    ]);
                    if ($employePermission) {
                        $_SESSION['info']['success']    = "La demande de permission a été annulée avec succès !";
                    } else {
                        $_SESSION['info']['danger']     = "Echec lors de l'opération !";
                    }
                }
            }
        }

        /**
         * Convertir une date en date complète
         *
         * @param date $date La date à convertir
         *
         * @return string
        */
        public function writeDate($date)
        {
            $tmp = explode('-', $date);
            if (count($tmp) == 3) {
                $tmp[2] = explode(' ', $tmp[2])[0];
                return $tmp[2] . ' ' . $this->getMonthLetter($tmp[1]) . ' ' . $tmp[0];    
            } else {
                return $date;
            }
        }

        /**
         * Convertir un entier en mois
         *
         * @param int $month Le mois en entier
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
         * Convertir un entier en mois
         *
         * @param date $date La date à converetir
         *
         * @return string
        */
        private function getDateInteger($date)
        {
            $months = [
                'Janvier'   => 1,
                'Février'   => 2,
                'Mars'      => 3,
                'Avril'     => 4,
                'Mai'       => 5,
                'Juin'      => 6,
                'Juillet'   => 7,
                'Août'      => 8,
                'Septembre' => 9,
                'Octobre'   => 10,
                'Novembre'  => 11,
                'Décembre'  => 12
            ];
            if (!is_null($date)) {
                $dateArr = explode(' ', $date);
                $date = is_array($dateArr) ? $dateArr[2] .'-'. $months[$dateArr[1]] .'-'. $dateArr[0] : array();
            }
            return $date;
        }

        /**
         * Convertir un jour en jour entier en français
         *
         * @param string $day Le jour en 3 lettres et en anglais
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
         * Récupérer heure et minute à partir d'une durée en seconde
         *
         * @param int $seconde Durée en seconde
         *
         * @return array
        */
        private function getDuree($seconde)
        {
            $factory            = new PublicFonctions();
            $retour['time']     = floor($seconde / 1);
            $tmp                = $seconde;
            $retour['second']   = $tmp % self::ONE_MINUTE;
            $tmp                = floor(($tmp - $retour['second']) / self::ONE_MINUTE);
            $retour['minute']   = $tmp % self::ONE_MINUTE;
            $tmp                = floor(($tmp - $retour['minute']) / self::ONE_MINUTE);
            $retour['hour']     = $tmp;
            $time               = date('H:i:s', $seconde); // Format l'heure modifiée comme une chaîne de caractères (si nécessaire)
            $heureInitiale      = new DateTime($time); // Création un objet DateTime à partir de l'heure obtenue
            $heureModifiee      = $heureInitiale->modify('-1 hours'); // Soustraire heures à l'heure obtenue
            $retour['floatTime']= $factory::convertTime2Float($heureModifiee->format('H:i:s'), 5, '.', '');
            return $retour;
        }

        /**
         * Envoyer un email de notification à un employé
         * 
         * @param object $destination   Le destinataire concerné
         * @param string $content       Le contenu du mail
         * @param string $subject       L'objet de l'email
         * @param string $pieceJointe   La pièce jointe nom du fichier image
         *
         * @return empty
        */
        private function sendMailNotification($destination, $content, $subject = null, $pieceJointe = null)
        {
            $pathFiles  = DOC_ROOT . 'Ressources/images/ordonances/' .$pieceJointe;
            $to         = $destination->getEmail();
            // Headers
            $headers[]  = 'MIME-Version: 1.0'; // Defining the MIME version
            $headers[]  = "From: " . strtolower($_SESSION['user']['email']); // Sender Email
            if (strstr($content, '<img')) {
                $content = str_replace("Son ordonance", "Ci-jointe son ordonance", substr($content, 0, strpos($content, '<img')));
            }
            if (is_null($subject)) {
                $subject = "Notification venant de " . strtoupper($_SESSION['user']['nom']);
            }
            $content    .= '<br><br> Cordialement,<br><br>L&apos;équipe <a href="https://hco.mg/">Human Cart&apos;Office</a>';
            if ($pieceJointe) { // Pas encore utilisé
                // clé aléatoire de limite
                $boundary       = "PHP-mixed-".md5(time());
                $boundWithPre   = "\n--".$boundary;
                $headers[]      = "Content-Type: multipart/mixed; boundary=\"".$boundary. "\"";
                $fileAttachment = trim($pathFiles);
                $pathInfo       = pathinfo($fileAttachment);
                $attchmentName  = "attachment_".date("YmdHms").((isset($pathInfo['extension']))? ".".$pathInfo['extension'] : "");
                $attachment     = chunk_split(base64_encode(file_get_contents($fileAttachment)));
                $message        = $boundWithPre;
                $message        .= "\nContent-Type: text/html; charset=UTF-8\n";
                $message        .= "\n$content";
                $message        .= $boundWithPre;
                $message        .= "\nContent-Type: application/octet-stream; name=\"".$attchmentName."\"";
                $message        .= "\nContent-Transfer-Encoding: base64\n";
                $message        .= "\nContent-Disposition: attachment\n";
                $message        .= $attachment;
                $message        .= $boundWithPre."--";
                $content        = $message; 
            } else {
                $headers[]  = 'Content-type: text/html; charset=iso-8859-1';
            }
            mail($to, $subject, $content, implode("\r\n", $headers));  
        }

        /**
         * Envoyer un message de notification à un utilisateur
         * 
         * @param int    $idCompte L'identifiant de l'utilisateur
         * @param string $objet    L'objet du message
         * @param string $contenu  Le contenu du message   
         *
         * @return empty
        */
        private function sendMessageNotification($idCompte, $objet, $contenu)
        {
            $today    = date('Y-m-d');
            $manager  = new ManagerMessage();
            $message  = $manager->ajouter([
                'idCompte'  => $idCompte,
                'objet'     => $objet,
                'contenu'   => $contenu,
                'date'      => $today,
                'statut'    => self::PROPOSED,
                'aFaire'    => self::NO
            ]);
            return $message->getIdMessage();
        }

        /**
         * Générer un contenu de message de notification
         *
         * @param string  $nature       La nature de la notification
         * @param string  $type         Le type de la nature
         * @param object  $object       L'objet en question
         * @param boolean $unlinked     Avec ou sans lien
         *
         * @return string
        */
        private function generateMessageContent($nature, $type, $object, $unlinked = false)
        {
            $manager = new ManagerEmploye();
            $factory = new PublicFonctions();
            $employe = $manager->chercher(['idEmploye' => $object->getIdEmploye()]);
            $content = "<p>Bonjour, </p>";
            if ($nature == self::NATURE_PERMISSION) {
                $manager              = new ManagerEntreprisePermission();
                $entreprisePermission = $manager->chercher(['idEntreprisePermission' => $object->getIdEntreprisePermission()]);
                $manager              = new ManagerTypePermission();
                $typePermission       = $manager->chercher(['idTypePermission' => $entreprisePermission->getIdTypePermission()]);
                $addS                 = $entreprisePermission->getDuree() . " jour" . ($entreprisePermission->getDuree() > 1 ? 's' : '');
                if ($type == self::TYPE_REQUEST) {
                    $response = $this->generateText($unlinked, self::NATURE_PERMISSION, $employe);
                    extract($response);
                    $content .= "<p>Nous vous informons que " . $linkedName . " a demandé une " . $linkedView . " en raison de " . $typePermission->getDesignation() . " pour le " . $this->writeDate($object->getDate()) . ".</p>";
                } elseif ($type == self::TYPE_CANCELED) {
                    $response   = $this->generateText($unlinked, self::NATURE_PERMISSION, $employe);
                    extract($response);
                    extract($factory::getUserByIdCompte($_SESSION['compte']['idCompte']));
                    $name       = $compte->getIdentifiant() == 'employe' ? $managerUser->getCivilite(). " " . $managerUser->getNom(). " " . $managerUser->getPrenom() : $managerUser->getNom();
                    $content    .= "<p>Nous vous informons que " . $name . " a annulé sa demande de " . $linkedView . " en raison de " . $typePermission->getDesignation() . " pour le " . $this->writeDate($object->getDate()) . ".</p>";
                } elseif ($type == self::TYPE_VALIDATED) {
                    $content .= "<p>Nous vous informons que votre demande de permission pour le " . $this->writeDate($object->getDate()) . " a été validée. </p>
                                Vous avez obtenu " . $addS . " de permission";
                } elseif ($type == self::TYPE_REJECTED) {
                    $content .= "<p>Nous vous informons que votre demande de permission pour le " . $this->writeDate($object->getDate()) . " a été rejetée. </p>";
                } elseif ($type == self::TYPE_INFORMATION) {
                    $content .= "<p>Nous vous informons que " . $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() . " a obtenu une permission de " . $entreprisePermission->getDuree() . " jour" . $addS . " le " . $this->writeDate($object->getDate()) . "</p>";
                }
            } elseif ($nature == self::NATURE_REPOS) {
                $addS = $object->getDuree() > 1 ? 's' : '';
                if ($type == self::TYPE_REQUEST) {
                    $response = $this->generateText($unlinked, self::NATURE_REPOS, $employe);
                    extract($response);
                    $content .= "<p>Nous vous informons que " . $linkedName . " a demandé un " . $linkedView . " de  " . $object->getDuree() . " jour" . $addS . " en raison de " . $object->getRaison() . " pour le " . $this->writeDate($object->getDate()) . ".<br>Son ordonance médicale :  </p> <br><img class='img-fluid' id='image' src='" . HOST . "../Web/Ressources/images/ordonances/" . $object->getJustify() . "'><style type='text/css'>img#image:hover{transform: scale(2);transition: all .4s;}</style>";

                } elseif ($type == self::TYPE_CANCELED) {
                    $response = $this->generateText($unlinked, self::NATURE_REPOS, $employe);
                    extract($response);
                    $content .= "<p>Nous vous informons que " . $linkedName . " a annulé sa demande de " . $linkedView . " de  " . $object->getDuree() . " jour" . $addS . ", comme motif de " . $object->getRaison() . " pour le " . $this->writeDate($object->getDate()) . ".</p>";
                } elseif ($type == self::TYPE_VALIDATED) {
                    $content .= "<p>Nous vous informons que votre demande de repos médical de ". $object->getDuree() ."jour" . $addS . " pour le " . $this->writeDate($object->getDate()) . " a été validée. </p>";
                } elseif ($type == self::TYPE_REJECTED) {
                    $content .= "<p>Nous vous informons que votre demande de repos médical de ". $object->getDuree() ."jour" . $addS . " pour le " . $this->writeDate($object->getDate()) . " a été rejetée. </p>";
                } elseif ($type == self::TYPE_INFORMATION) {
                    $content .= "<p>Nous vous informons que " . $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() . " a obtenu un repos médical de " . $object->getDuree() . " jour" . $addS . " le " . $this->writeDate($object->getDate()) . "</p>";
                }
            } elseif ($nature == self::NATURE_TASK) {
                if ($type == self::TYPE_REQUEST) {
                    $content .= "<p>Vous avez une tâche à faire qui a comme titre : &quot;<bold>" . $object->getTitre() . " </bold>&quot; .<br>À commencer le " . $this->writeDate($object->getDateDebut()) . " , cela vous prendra " . $object->getEstimated() . "H .<br>Pour plus de detail cliquer <a href='" . HOST . "manage/employe/pointage/dashboard'>ici</a> <br>Bonne continuation &#x1F609; !!!
                                </p>";
                }
            }
            return $content;
        }

        /**
         * @changlog 2022-07-26 [EVOL] (Lansky) Générer le text avec le lien
         *
         * @param boolean $unlinked     Avec ou sans link
         * @param string  $naure        L'objet de la notification e-mail ou message
         * @param string  $nature       La nature de la notification
         *
         * @return array
        */
        private function generateText($unlinked, $nature, $employe)
        {
            $result = array();
            $text = $nature == self::NATURE_REPOS ? "repos médical" : self::NATURE_PERMISSION;
            if ($unlinked) {
                $result['linkedName'] = $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom();
                $result['linkedView'] = $text;
            } else {
                $result['linkedName'] = html_entity_decode("&lt;a href=&quot;" . HOST . "manage/employe?idEmploye=" . $employe->getIdEmploye() . "&quot;&gt;" . $employe->getCivilite() . " " . $employe->getNom() . " " . $employe->getPrenom() . "&lt;/a&gt;");
                $result['linkedView'] = html_entity_decode("&lt;a href=&quot;" . HOST . "manage/entreprise/" . $nature . "/dashboard&quot;&gt;" . $text . "&lt;/a&gt;"); 
            }
            return $result;
        }

        /**
         * @changlog 2022-07-26 [EVOL] (Lansky) Envoyer message de notification aux supérieurs
         *
         * @param object  $employeAction    L'objet du permission ou repos démandé par l'employé
         * @param string  $objectNotif      L'objet de la notification e-mail ou message
         * @param string  $nature           La nature de la notification
         * @param string  $type             Le type de la nature
         * 
         * @return empty
        */
        private function sendChefNotifications($employeAction, $objectNotif, $nature, $type, $pieceJointe = null)
        {
            $manager    = new ManagerEmploye();
            $applicant  = $manager->chercher(['idEmploye' => $employeAction->getIdEmploye()]);
            $factory    = new PublicFonctions();
            if ($employeAction != null) {
                if ($applicant->getChefHierarchique() != self::NO) {
                    $tmpEmploye = $applicant;
                    $content    = $this->generateMessageContent($nature, $type, $employeAction, true);
                    while ($tmpEmploye->getChefHierarchique() != self::NO) {
                        $chef = $factory->getChief($tmpEmploye, $applicant);
                        if ($chef->getIdEmploye()) {
                            $this->sendMessageNotification($chef->getIdCompte(), $objectNotif, $content);
                            $this->sendMailNotification($chef, $content, $objectNotif);
                        }
                        $tmpEmploye = $chef;
                    }
                }
            }
        }

        /**
         * Insérer le temps de travail
         *
         * @param array $parameters 
         *
         * @return empty
        */
        public function getWorkTimer($parameters)
        {
            $find       = ['idEmploye' => $_SESSION['user']['idEmploye']];
            if (!isset($parameters['idTask'])) {
                $parameters['idTask'] = 0;
            }
            $addArray   = $parameters['idTask'] > 0 ? ['idTache' => $parameters['idTask']] : ['statut' => self::CURRENT_TASK];
            $find       = array_merge($addArray,$find);
            $manager    = new ManagerTache();
            $tache      = $manager->chercher($find);
            if ($tache) {
                $newVal = $tache->getWorkedTime() + 1;
                $response = array(
                    'workedTime'    => $newVal,
                    'idTache'       => $tache->getIdTache(),
                    'estimated'     => $tache->getEstimated(),
                    'attributor'    => $tache->getAttributor(),
                    'idEmploye'     => $tache->getIdEmploye()
                );
                echo json_encode($response);
                $response = $manager->modifier([
                    'idTache'       => $tache->getIdTache(),
                    'worked_time'   => $newVal
                ]); 
            }
            exit();
        }

        /**
         * Suivi des tâches 
         *
         * @param array $parameters 
         *
         * @return empty || array
        */
        public function getTrackingTask($parameters)
        {
            $factory = new PublicFonctions();
            if (!is_null($parameters)) {
                $user               = $_SESSION['compte']['identifiant'];
                $url                = explode('/', $_GET['page']);
                $parameters['type'] = self::PRESENT_YES;
                if (end($url) == 'tracking') {
                    $donnees    = $this->calculateTrackingTask($parameters);
                    $vue        = "listerTracking";
                } elseif (end($url) == 'currentTask') {
                    $vue        = "currentTask";
                    $donnees    = $this->getCurrentTask($parameters);
                }
                $view = new View($vue);
                $view->sendWithoutTemplate("Backend", "Presence", $donnees, $user);
                exit();
            } else {
                return $factory->getFiltre($_SESSION['user']['idEntreprise']);
            }
        }

        /**
         * Prendre le premier et la fin de la date du mois
         *
         * @param string $mois
         * @param int $year
         *
         * @return array
        */
        private function getBeginToEndMounth($mois, $year = 0)
        {
            $annee  = $year > 0 ? $year : 'Y';
            $debut  = date($annee . '-' . $mois . '-01');
            $fin    = date("Y-m-d", mktime(0, 0, 0, ($mois + 1), 0, date($annee)));
            return array('debut' => $debut , 'fin' => $fin);
        }

        /**
         * 
         *
         * @param string $mois
         * @param int $year
         *
         * @return array
        */
        private function getCurrentTask($args)
        {
            $managerPointage    = new ManagerPointage();
            $managerPresence    = new ManagerPresence();
            $managerTache       = new ManagerTache();
            $factory            = new PublicFonctions();
            $donnes             = $factory->getFiltre($_SESSION['user']['idEntreprise']);
            $listes             = array();
            foreach ($donnes['employes'] as $salarie) {
                $presence       = $managerPresence->chercher([
                    'idEmploye' => $salarie->getIdEmploye(),
                    'date'      => date('Y-m-d'),
                    'statut'    => self::PRESENT_YES
                ]);
                if ($presence) {
                    $pointage   = $managerPointage->chercher([
                        'idPresence'    => $presence->getIdPresence(),
                        'statut'        => self::OPEN_POINTING // En cours seulement
                    ]);
                    if ($pointage != NULL) {
                        $tache      = $managerTache->chercher(['idTache' => $pointage->getIdTache()]);
                    } else {
                        $pointages   = $managerPointage->lister([
                            'idPresence'    => $presence->getIdPresence(),
                            'statut'        => self::CLOSED_POINTING // Tous les pointages d'aujourd'hui
                        ]);
                        $taches = []; 
                        foreach ($pointages as $key => $value) {
                            $tmpTask = $managerTache->chercher(['idTache' => $value->getIdTache()]);
                            array_push($taches, $tmpTask);
                            if ($key == 0) {
                                $pointage = $value;
                                // $tache = $tmpTask;
                            }
                            if ($key == count($pointages) - 1) {
                                $pointage->setFin($value->getfin());
                            }
                            
                        }
                        $tache = $factory::uniqueArrayOfObjectDuplicated($taches, 'getIdTache');
                    }
                    $listes[$salarie->getIdEmploye()] = [
                        'userName'  => $salarie->getNom() . ' ' . $salarie->getPrenom(),
                        'tache'     => $tache,
                        'pointage'  => $pointage 
                    ];
                }
            }
            return $listes;
        } 
     
        /**
         * Le nombre de jour de travail dans un mois
         *
         * @param int $year
         * @param int $month
         * @param bool $ignore
         *
         * @return int
        */
        private function countDays($year, $month, $ignore = false) {
            $count = 0;
            $counter = mktime(0, 0, 0, $month, 1, $year);
            while (date("n", $counter) == $month) {
                if (in_array(date("w", $counter), $ignore) == false) {
                    $count++;
                }
                $counter = strtotime("+1 day", $counter);
            }
            return $count;
        }

        /**
         * Calcule de la suivi des tâches
         *
         * @param array $parameters 
         *
         * @return empty
        */
        private function calculateTrackingTask($args, $year = 0)
        {
            $factory    = new PublicFonctions();
            $donnes     = $factory->getFiltre($_SESSION['user']['idEntreprise']);
            $heurePause = $this->countDays($year, 1, array(0, 6)) * self::HEUR_PAUSE_JOURNALIER * self::ONE_MINUTE; // somme heure de pause dans un mois en seconde => 30min/jour
            if ($year == 0) {
                $year = date('Y');
            }
            if (!empty($args['periode'])) {
                $date = $this->getIntervalle($args['periode']);
                extract($date);
                if ($args['periode'] == self::THIS_WEEK || $args['periode'] == self::LAST_WEEK) {
                    $divisorName = 'by week'; 
                } else {
                    $divisorName = 'by month';
                }
            } elseif (isset($args['mois'])) {
                if (!empty($args['mois'])) {
                    extract($this->getBeginToEndMounth($args['mois']));
                }
                $divisorName = 'by month';
            }
            $responses          = array();
            $userWorkedDuring   = 0;
            $divisor            = $divisorName == 'by month' ? (3600 * $this->countDays($year, 1, array(0, 6)) * self::HOUR_WORK_DAY) - $heurePause : (((3600 * self::HOUR_WORK_DAY) - (self::HEUR_PAUSE_JOURNALIER * self::ONE_MINUTE)) * 5); // une heure en seconde * nombre de jour de travail dans un mois * heure de travail journalier - heure de pause par mois ou semaine
            $userWorkedDuring   = 0;
            $totalUserTracked   = 0;
            $total              = 0;            
            foreach ($donnes['employes'] as $salarie) {
                $manager        = new ManagerPresence();
                $presences      = $manager->lister([
                    'idEmploye' => $salarie->getIdEmploye(),
                    'date'      => 'BETWEEN "' . $debut . '" AND "' . $fin .'"',
                    'statut'    => self::PRESENT_YES
                ]);
                $tracked        = 0;
                $aFaire         = 0;
                $done           = 0;
                $sommePoint     = 0;
                if ($presences) {
                    foreach ($presences as $presence) {
                        $manager    = new ManagerPointage();
                        $pointages  = $manager->lister([
                            'idPresence'    => $presence->getIdPresence()/*,
                            'statut'        => self::CLOSED_POINTING*/
                        ]);
                        $sommePoint += count($pointages);
                        foreach ($pointages as $pointage) {
                            if ($pointage->getStatut() == self::CLOSED_POINTING) {
                                $tracked +=  abs(strtotime($pointage->getFin()) - strtotime($pointage->getDebut()));
                                $done++;
                            } else {
                                $aFaire++;
                            }
                        }
                    }
                }
                $subTotal           = $tracked > 0 ? ($tracked * 100) / $divisor : 0;
                $userTracked        = abs($done - $aFaire) > 0 && $sommePoint > 0 ? abs($done - $aFaire) / $sommePoint : 0;
                $userWorkedDuring   += $tracked;
                $totalUserTracked   += $userTracked;
                $total              +=$subTotal;
                $responses[$salarie->getIdEmploye()] = array(
                    'name'          => $salarie->getNom() . ' ' . $salarie->getPrenom(),
                    'workedDuring'  => $this->getDuree($tracked),
                    'tracked'       => $userTracked > 0 ? ($userTracked / count($donnes['employes'])) : 0,
                    'totalDuring'   => $subTotal > 0 ? ($subTotal / count($donnes['employes'])) : 0
                );
            }
             $totals = [
                'userWorkedDuring'  => $this->getDuree($userWorkedDuring),
                'totalUserTracked'  => $totalUserTracked,
                'total'             => $total
            ];
            $responses = array_merge($responses, ['totals' => $totals]);
            return $responses;
        }

        /**
         * Afficher les retards selon les filtres
         *
         * @param array $parameters Critères des donnéees
         *
         * @return empty
        */
        public function listerRetards($parameters)
        {
            $user  = $_SESSION['compte']['identifiant'];
            if (!empty($parameters)) {
                $donnees    = $this->getLateEmployees($parameters);
                $view       = new View("listerRetards");
                $view->sendWithoutTemplate("Backend", "Presence", $donnees, $user); 
            } else {
                if (!headers_sent()) {
                    header("Location: " . HOST . "manage/" . $user . "/retard/dashboard");
                } else {
                    echo "<meta http-equiv=\"refresh\" content=\"0;url=$url[1]\">\r\n";
                }
            }
            exit();
        }

        /**
         * Récupérer les retards selon les filtres
         *
         * @param array $parameters Critères des donnéees
         *
         * @return array
        */
        private function getLateEmployees($parameters)
        {
            if (!empty($parameters['periode'])) {
                $intervalle = $this->getIntervalle($parameters['periode']);
                extract($intervalle);
            } elseif (isset($parameters['debut'])) {
                extract($this->getDateDebutFin($parameters['debut'], $parameters['fin']));
            } elseif (isset($parameters['mois'])) {
                extract($this->getBeginToEndMounth($parameters['mois'], date('Y')));
            }
            if ($debut && $fin) {
                $debut  = $debut . " 00:00:00";
                $fin    = $fin . " 23:59:59";
            }
            if ($_SESSION['compte']['identifiant'] == 'employe') {
                $listesSubordonnnes = $this->voirSuiviEmploye($parameters);
                extract($listesSubordonnnes);
                $employes = $subordonnes;
            } else {
                $data = $this->getAllEmployeesFilterByGroup($parameters);
                extract($data);
            }
            $manager    = new ManagerRetard();
            $listes     = array();
            foreach ($employes as $employe) {
                $recoveryTime = '00:00:00';
                $retards = $manager->lister([
                    'id_employe'    => $employe->getIdEmploye(),
                    'date'          => '>= "' . $debut . '" AND date <= "' . $fin . '"'
                ]);
                if ($retards) {
                    foreach ($retards as $retard) {
                        $keyFound                           = array_key_exists($retard->getIdEmploye(), $listes);
                        $during                             = !$keyFound ? $retard->getDuring() : date('H:i:s', (strtotime($listes[$retard->getIdEmploye()]['during']) + strtotime($retard->getDuring()) - strtotime("00:00:00")));
                        $time1                              = new DateTime($during);
                        $time2                              = new DateTime($recoveryTime);
                        $diffTime                           = $time1->diff($time2);
                        $listes[$retard->getIdEmploye()]    =  array(
                            'userName'      => $employe->getNom() . ' ' . $employe->getPrenom(),
                            'lastArrived'   => $retard->getDate(),
                            'during'        => $during,
                            'delayNumber'   => !$keyFound ? 1 : $listes[$retard->getIdEmploye()]['delayNumber'] + 1,
                            'recoveryTime'  => $recoveryTime,
                            'total'         => $diffTime->format('%H:%I:%S')
                        );
                    }   
                }
            }
            return array('retards' => $listes);
        }

        /**
         * Récupérer les employés selon les filtres
         *
         * @param array $parameters Critères des donnéees
         *
         * @return array
        */
        private function getAllEmployeesFilterByGroup($parameters)
        {
            $manager        = new ManagerEntreprise();
            $entreprise     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $executeFilter  = false;
            $employes       = array();
            if (!empty($parameters['groupe'])) {
                if ($parameters['groupe'] == self::FILTER_GROUP_ALL) {
                    $manager        = new ManagerEmploye();
                    $employes       = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $executeFilter  = true;
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
                            $contrats   = $manager->lister(['idServicePoste' => $servicePoste->getIdServicePoste()/*, 'statut' =>self::VALIDATED*/]);
                            foreach ($contrats as $contrat) {
                                $manager    = new ManagerEmploye();
                                $employes[] = $manager->chercher(['idEmploye' => $contrat->getIdEmploye()]);
                            }
                        }
                        $employes       = array_unique($employes);
                        $executeFilter  = true;
                    }
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_POSTE) {
                    if (!empty($parameters['id'])) {
                        $manager        = new ManagerEntreprisePoste();
                        $poste          = $manager->chercher(['idEntreprisePoste' => $parameters['id']]);
                        $manager        = new ManagerServicePoste();
                        $servicePostes  = $manager->lister(['idEntreprisePoste' => $poste->getIdEntreprisePoste()]);
                        $employes       = array();
                        foreach ($servicePostes as $servicePoste) {
                            $manager    = new ManagerContratEmploye();
                            $contrats   = $manager->lister(['idServicePoste' => $servicePoste->getIdServicePoste()/*, 'statut' =>self::VALIDATED*/]);
                            foreach ($contrats as $contrat) {
                                $manager    = new ManagerEmploye();
                                $employes[] = $manager->chercher(['idEmploye' => $contrat->getIdEmploye()]);
                            }
                        }
                        $employes       = array_unique($employes);
                        $executeFilter  = true;
                    }
                } elseif ($parameters['groupe'] == self::FILTER_GROUP_EMPLOYE) {
                    if (!empty($parameters['id'])) {
                        $manager        = new ManagerEmploye();
                        $employes       = array();
                        $employes[]     = $manager->chercher(['idEmploye' => $parameters['id']]);
                        $executeFilter  = true;
                    }
                }
            }
            return ['employes' => $employes, 'executeFilter' => $executeFilter];
        }

        /**
         * Récupérer les dates tel que la date de debut et celle de la fin
         *
         * @param string $dateDebut     Debut d'une date
         * @param string $dateFin       Fin d'une date
         *
         * @return array
        */
        public function getDateDebutFin($dateDebut, $dateFin)
        {
            if ($dateDebut) {
                $date  = explode("/", $dateDebut);
                    $dateDebut = $date[2] . '-' . $date[1] . '-' . $date[0];
                    if (empty($dateFin)) {
                        $debut = $dateDebut;
                        $fin   = $dateDebut;
                    } else {
                        $date                = explode("/", $dateFin);
                        $dateFin   = $date[2] . '-' . $date[1] . '-' . $date[0];
                        $debut = $dateDebut;
                        $fin   = $dateFin;
                    }
                return array('debut' => $debut, 'fin' => $fin);
            }
        }

        /**
         * Faire passer la validation d'une demande de la permission
         * 
         * @changeLog 2023-06-07 [OPTIM] (Lansky) Ajout de la méthode
         *  
         * @param  object $employe      L'employé courant dans la session
         * @param  object $chef         Le chef hierarchique
         * @param  object $demande      La demande de la permission
         *
         * @return empty
        */
        private function passerValidation($employe, $chef, $demande,$objectNotif='Aucun')
        {
            $managerValidationPermission    = new ManagerValidationPermission();
            $managerEntreprisePermission    = new ManagerEntreprisePermission();
            $managerTypePermission          = new ManagerTypePermission();
            $managerParametre               = new ManagerParametreConge();
            $managerEntreprise              = new ManagerEntreprise();
            $managerEmploye                 = new ManagerEmploye();
            $mangerMessage                  = new ManagerMessage();
            $managerCompte                  = new ManagerCompte();
            $factory                        = new PublicFonctions();
            $demandeur                      = $managerEmploye->chercher(['idEmploye' => $demande->getIdEmploye()]);
            $entreprise                     = $managerEntreprise->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $entreprisePermission           = $managerEntreprisePermission->chercher([
                'idEntreprisePermission'    => $demande->getIdEntreprisePermission(),
                'idEntreprise'              => $_SESSION['user']['idEntreprise']
            ]);
            $versRh                         = false;
            $typePermission                 = $managerTypePermission->chercher(['idTypePermission' => $entreprisePermission->getIdTypePermission()]);
            $addS                           = $entreprisePermission->getDuree() . " jour" . ($entreprisePermission->getDuree() > 1 ? 's' : '');
            // Seul le validateur peut valider la demande de la permission
            if ($chef->getIdEmploye()) {
                $chef = $factory->getChiefNotValidate($chef, $demandeur);
                // Vérifier si le validateur est en congé ou en repos ou en congé ou en permission le quand on fait une demande de congé et faire passer la validation au sepérieur */
                $verified = $chef && $chef->getIdEmploye() ? $factory->verifyAbsence($chef, date('Y-m-d')) : false;
                if ($verified) {
                    $chef = $factory->notifyChiefAbsent($verified, $chef, $demandeur, 'une permission', $demande->getDate(), date('Y-m-d', strtotime($demande->getDate() . '+' . $entreprisePermission->getDuree() . ' days')), $typePermission->getDesignation());
                }
            }
            if ($chef->getIdEmploye()) {
                $parametre = $managerParametre->chercher(['idEntreprise' => $chef->getIdEntreprise()]);
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
            $destinataire   = $versRh ? $entreprise : $chef;
            $compte         = $managerCompte->chercher(['idCompte' => $destinataire->getIdCompte()]);
            $content        = self::generateMessageContent(self::NATURE_PERMISSION, self::TYPE_REQUEST, $demande, true);
            // Notifier les supérieurs par mail et par message de notification
            $content        .= "<p>Veuillez Consultez la demande <a href=" . HOST . "manage/" . $compte->getIdentifiant() . "/permission/dashboard>ici</a></p>";
            self::sendMailNotification($destinataire, $content,$objectNotif);
            
            $content                = "<p>Bonjour, </p></br></br><p>Vous avez une <a href='" . HOST . "manage/" . $compte->getIdentifiant() . "/permission/dashboard'>validation de demande de permission</a> à effectuer de la part de " . $demandeur->getCivilite() . " " . $demandeur->getNom() . " " . $demandeur->getPrenom() . " .</br>Raison: " . $typePermission->getDesignation() . " le " . $this->writeDate($demande->getDate()) . ", durée de " . $addS . " .</p>";
            $idMessage              = self::sendMessageNotification($destinataire->getIdCompte(), $objectNotif, $content);
            $managerValidationPermission->ajouter([
                'id_compte'             => $destinataire->getIdCompte(),
                'id_employe_permission' => $demande->getIdEmployePermission(),
                'id_message'            => $idMessage,
                'statut'                => self::PROPOSED,
                'etat'                  => self::LEAVE_NOT_ARCHIVED
            ]);
        }

        /**
         * Traitement lorsque le client est éteint
         * 
         * @changelog 2023-06-15 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters
         *
         * @return empty
        */
        public function powerOff($parameters)
        {
            $managerParametrePointage   = new ManagerParametrePointage();
            $managerPointage            = new ManagerPointage();
            $managerPresence            = new ManagerPresence();
            $managerTache               = new ManagerTache();
            $heureCourante              = new DateTime(date('H:i:s'));
            $heureCourante->add(new DateInterval('PT2H'));
            $parametrePointage          = $managerParametrePointage->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (strtotime($parametrePointage->getHeureArret()) < strtotime($heureCourante->format('H:i:s'))) {
                // Journalisation dans un fichier
                $fileField = DOC_ROOT. "Ressources/fichiers/logs_user/entreprise_" . $_SESSION['user']['idEntreprise'];
                if (!file_exists($fileField)) {
                    mkdir($fileField, 0777, true);
                }
                $textLine   = " Arrêt pointage d'userID: " . $parameters['idEmploye'] . " ,ce " . date('Y-m-d') . " " . $heureCourante->format('H:i:s') . " - raison: Client powered off\n";
                $presence   = $managerPresence->chercher([
                    'idEmploye' => $parameters['idEmploye'],
                    'date'      =>date('Y-m-d'),
                    'statut'    => self::PRESENT_YES
                ]);
                if (is_object($presence)) {
                    $pointage = $managerPointage->chercher([
                        'idPresence'    => $presence->getIdPresence(),
                        'statut'        => self::OPEN_POINTING
                    ]);
                    if (is_object($pointage)) {
                        $managerPointage->modifier([
                            'idPointage'    => $pointage->getIdPointage(),
                            'fin'           => $heureCourante->format('H:i:s'),
                            'statut'        => self::CLOSED_POINTING
                        ]);
                        $managerTache->modifier([
                            'idTache'   => $pointage->getIdTache(),
                            'statut'    => self::PAUSED_TASK
                        ]);
                        $file = $fileField . "/poweroff_log_" . date('Y_m_d') . ".txt";
                        file_put_contents($file, $textLine, FILE_APPEND | LOCK_EX);
                        /*
                            Lorsque le client éteindra son appareil ou quittera votre site, la fonction onunload sera déclenchée et enverra une requête AJAX au fichier "poweroff.php".

                            Veuillez noter que cette méthode peut ne pas fonctionner dans toutes les situations, car la fermeture du navigateur ou le rafraîchissement de la page peut ne pas toujours déclencher l'événement onunload. De plus, cette méthode ne permet pas de différencier explicitement une extinction volontaire du client d'autres types de déconnexion ou d'interruption de connexion.
                        */
                    }
                }
            }
            // exit; tester si ça fonctionne
        }

        /**
         * Récupérer les shift
         *
         * @param array $parameters
         *
         * @return array
        */
        public function voirShift($parameters)
        {
            $managerCampaign = new ManagerCampaign();
            return $this->cardShift($managerCampaign->lister(['id_entreprise' => $_SESSION['user']['idEntreprise']]));
            
        }
        
        /**
         * Mettre en place l'affichage des cartes
         * 
         * @changeLog 2023-06-05 [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param $tab
         *  
         * @return $tab
        */ 
        private function cardShift($campaigns=[])
        {
            $date                   = "";
            $lieu                   = "";
            $heure                  = "";
            $managerEmploye         = new ManagerEmploye();
            $factory                = new PublicFonctions();
            $order                  = -1;
            $array['shiftColumn'][] =  ['text' => "Employés suggérés", 'status' => 'suggest'] ;
            $array['cardColumn'][]  =  [] ;
            $tab                    = $managerEmploye->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            foreach ($campaigns as $campaign) {
                $color  = $factory::randomColor();
                $status = $campaign->getLibelle() . '_' . $campaign->getIdCampaign();
                $array['shiftColumn'][] =  [
                    'field'     => "Date",
                    'id'        => $campaign->getIdCampaign(),
                    'text'      => $campaign->getLibelle(),
                    'status'    => $status,
                    'format'    => "{0:jj/MM/aaaa HH:mm}",
                    'template'  => "#= kendo.toString(kendo.parseDate(Date), 'dd/MM/yyyy HH:mm') #"

                ] ;
                $array['colorItem'][]   = ['value' => $status, 'text' => $color, 'color' => $color];
                $shifts                 = self::getAllShiftByCampaign($campaign->getIdCampaign());
                foreach ($shifts as $shift) {
                    $showTime   = $factory->writeDate($shift->getStartTime(), true) . ' jusqu\'à ' . $factory->writeDate($shift->getEndTime(), true);
                    $order++;
                    $date       = explode(' ', $shift->getEndTime())[0];
                    $heure      = explode(' ', $shift->getEndTime())[1];
                    $worker     = $managerEmploye->chercher(['idEmploye' => $shift->getIdEmploye()]);
                    $photo      = $worker->getPhoto() ? $worker->getPhoto() : strtolower($worker->getCivilite()) . '.png';
                    $array['cardColumn'][] = [
                        'id'                => $shift->getIdShift(), 
                        'title'             => $worker->getNom()." ". $worker->getPrenom(), 
                        'order'             => $order, 
                        'description'       => $showTime, 
                        'color'             => $color,
                        'imageUrl'          => HOST."Web/Ressources/images/employes/" . $photo,
                        'image'             => $photo,
                        'employe'           => $worker->getIdEmploye(),
                        'date'              => $shift->getEndTime(),
                        'status'            => $status,
                        'cards'             => "#cardData"
                    ];
                }
            }
            $color                  = $factory::randomColor();
            $array['colorItem'][]   = ['value' => 'suggest', 'color' => $color];
            foreach ($tab as  $value) {
                $order++;
                $photo = $value->getPhoto() != null ? $value->getPhoto() : strtolower($value->getCivilite()) . '.png';
                $array['cardColumn'][] = [
                    'id'                => null, 
                    'title'             => $value->getNom()." ". $value->getPrenom(), 
                    'order'             => $order, 
                    'description'       => "libre : ", 
                    'color'             => $color,
                    'imageUrl'          => HOST."Web/Ressources/images/employes/" . $photo,
                    'image'             => $photo,
                    'employe'           => $value->getIdEmploye(),
                    'date'              => date('Y-m-d'),
                    'status'            => 'suggest',
                    'cards'             => "#cardData"
                ];
            }
            return $array;
        }

        /**
         * Ajouter nouveau planning de la campagne du shift
         * 
         * @changelog 2023-06-30 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters
         *
         * @return json
        */
        public function addNewCampaign($parameters)
        {
            extract($parameters);
            $managerShift       = new ManagerShift();
            $managerCampaign    = new ManagerCampaign();
            $shifts             = [];
            $campaign           = $managerCampaign->ajouter([
                'libelle'       => $libelle,
                'id_entreprise' => $_SESSION['user']['idEntreprise'],
                'start_date'    => $startDate,
                'end_date'      => $endDate,
                'start_time'    => $startTime,
                'end_time'      => $endTime
            ]);
            if (count(explode(',',$workerList)) > 0) {
                foreach (explode(',',$workerList) as $idEmploye) {
                    if ($idEmploye) {
                        $shift = $managerShift->ajouter([
                            'id_employe'    => $idEmploye,
                            'id_entreprise' => $_SESSION['user']['idEntreprise'],
                            'id_campaign'   => $campaign->getIdCampaign(),
                            'start_time'    => $startDate . ' ' . $startTime,
                            'end_time'      => $endDate . ' ' . $endTime
                        ]);
                        $shifts[] = $shift->toArray();
                    }
                }
            }
            echo json_encode(['campaign' => $campaign->toArray(),'shifts' => $shifts]);
            exit;
        }

        /**
         * Ajouter nouveau planning du shift de la campagne existante
         * 
         * @changelog 2023-07-13 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters
         *
         * @return json
        */
        public function addShift($parameters)
        {
            extract($parameters);
            $managerCampaign    = new ManagerCampaign();
            $campaign           = $managerCampaign->chercher(['id_campaign' => $idCampaign]);
            if (strtotime(date('Y-m-d')) <= strtotime($campaign->getEndDate())) {
                $managerShift   = new ManagerShift();
                if (strtotime(date('Y-m-d')) > strtotime($campaign->getStartDate())) {
                    $startDateTime = date('Y-m-d') . ' ' . $campaign->getEndTime() ; // Définir la date debut du shift
                }
                $response       = $managerShift->ajouter([
                    'id_employe'    => $idEmploye,
                    'id_campaign'   => $idCampaign,
                    'id_entreprise' => $_SESSION['user']['idEntreprise'],
                    'start_time'    => $startDateTime,
                    'end_time'      => $endDateTime
                ]);
            } else {
                echo "error";
                $response = "Impossible d'ajouter ce shift à la campagne qui est déjà terminer";
            }
            echo json_encode($response);
            exit;
        }

        /**
         * Modifier un planning de la campagne du shift
         * 
         * @changelog 2023-07-03 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters
         *
         * @return json
        */
        public function updateCampaign($parameters)
        {
            extract($parameters);
            $managerShift       = new ManagerShift();
            $managerCampaign    = new ManagerCampaign();
            $workerList         = explode(',',$workerList);
            $tabShift           = [];
            extract($this->getCampaignResponse(['id_campaign' => $idCampaign]));
            foreach ($allShifts as $value) {
                $tabShift[] = $value['idShift'];
            }
            if (strtotime(date('Y-m-d')) < strtotime($campaign->getStartDate())) {
                $responseShifts = [];
                $campaign       = $managerCampaign->modifier([
                    'id_campaign'   => $idCampaign,
                    'libelle'       => $libelle,
                    'start_date'    => $startDate,
                    'end_date'      => $endDate,
                    'start_time'    => $startTime,
                    'end_time'      => $endTime
                ]);
                foreach ($workerList as $idEmploye) {
                    $action     = '';
                    $findshift  = $managerShift->chercher([
                        'id_campaign'   => $idCampaign,
                        'id_employe'    => $idEmploye
                    ]);
                    $idShift        = $findshift ? $findshift->getIdShift() : 0;
                    $startDateTmp   = $startDate . ' ' . $startTime;
                    if ($findshift) {
                        $action     = 'modifier';
                    } else {
                        $action             = 'ajouter';
                        $voirShiftConfus    = self::getExistingShift($idEmploye, $idShift, $endDate, $startDate);
                        if ($voirShiftConfus) {
                            $date = new DateTime(date('Y-m-d', strtotime($voirShiftConfus->getEndDate())));
                            $date->modify('+1 day'); // Soustraire un jour en utilisant la méthode de modification
                            $startDateTmp   = $date->format('Y-m-d'); // Formater la date au format souhaité
                        } else {
                            $startDateTmp   = date('Y-m-d');
                        }
                    }
                    $shift  = $managerShift->$action([
                        'id_shift'      => $idShift,
                        'id_employe'    => $idEmploye,
                        'id_entreprise' => $_SESSION['user']['idEntreprise'],
                        'id_campaign'   => $idCampaign,
                        'start_time'    => $startDateTmp,
                        'end_time'      => $endDate . ' ' . $endTime
                    ]);
                    $responseShifts[]   = $shift->toArray();
                    $listIdshift[]      = $shift->getIdShift();
                }
                $diff = array_diff($tabShift, $listIdshift); // Obtenir les éléments de l'responseShifts qui ne sont pas présents dans le shifts et les supprimer
                foreach ($diff as $value) {
                    $managerShift->supprimer(['id_shift' =>$value]);
                }
                $responses = ['campaign' => $campaign->toArray(),'shifts' => $responseShifts];
            } elseif (strtotime(date('Y-m-d')) <= strtotime($campaign->getEndDate()) && $endDate >= date('Y-m-d')) {
                $responseShifts = [];
                if ($endDate != $campaign->getEndDate()) {
                    foreach ($workerList as $idEmploye) {
                        $endDateTmp         = $endDate;
                        $action             = '';
                        $findshift          = $managerShift->chercher([
                            'id_campaign'   => $idCampaign,
                            'id_employe'    => $idEmploye
                        ]);
                        $idShift            = $findshift ? $findshift->getIdShift() : null;
                        $voirShiftConfus    = self::getExistingShift($idEmploye, $idShift, $endDate, $startDate);
                        if ($findshift) {
                            $action         = 'modifier';
                            $startDateTmp   = date('Y-m-d', strtotime($findshift->getStartTime()));
                            if ($voirShiftConfus) {
                                $date = new DateTime(date('Y-m-d', strtotime($voirShiftConfus->getStartDate())));
                                $date->modify('-1 day'); // Soustraire un jour en utilisant la méthode de modification
                                $endDateTmp     = $date->format('Y-m-d'); // Formater la date au format souhaité
                            }
                        } else {
                            $action     = 'ajouter';
                            if ($voirShiftConfus) {
                                $date = new DateTime(date('Y-m-d', strtotime($voirShiftConfus->getEndDate())));
                                $date->modify('+1 day'); // Soustraire un jour en utilisant la méthode de modification
                                $startDateTmp   = $date->format('Y-m-d'); // Formater la date au format souhaité
                            } else {
                                $startDateTmp   = date('Y-m-d', strtotime('+1 day'));
                            }
                        }
                        $shift  = $managerShift->$action([
                            'id_shift'      => $idShift,
                            'id_employe'    => $idEmploye,
                            'id_entreprise' => $_SESSION['user']['idEntreprise'],
                            'id_campaign'   => $idCampaign,
                            'start_time'    => $startDateTmp . ' ' . $startTime,
                            'end_time'      => $endDateTmp . ' ' . $endTime
                        ]);
                        $responseShifts[]   = $shift->toArray();
                        $listIdshift[]      = $shift->getIdShift();
                    }
                    $diff = array_diff($tabShift, $listIdshift); // Obtenir les éléments de l'responseShifts qui ne sont pas présents dans le shifts et les modifier la date fin aujourd'hui même
                    foreach ($diff as $value) {
                        $managerShift->modifier(['id_shift' =>$value, 'end_time' => date('Y-m-d') . ' ' . $endTime]);
                    }
                }
                $campaign = $managerCampaign->modifier([
                    'id_campaign'   => $idCampaign,
                    'libelle'       => $libelle,
                    'end_date'      => $endDate,
                    'end_time'      => $endTime
                ]);
                $responses = ['campaign' => $campaign->toArray(),'shifts' => $responseShifts];
            } else {
                echo "error";
                $responses = "Impossible de modifier cette campagne qui est déjà terminé !";
            }
            echo json_encode($responses);
            exit;
        }

        /**
         * Modifier un shift
         * 
         * @changelog 2023-07-18 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters
         *
         * @return json
        */
        public function updateShift($parameters)
        {
            extract($parameters);
            $managerShift       = new ManagerShift();
            $managerCampaign    = new ManagerCampaign();
            $status             =  explode('_', $currentStatus);
            $idCampaign         = end($status);
            $campaign           = $managerCampaign->chercher(['id_campaign' => $idCampaign]);
            $shift              = $managerShift->chercher(['id_shift' => $idShift]);
            $endDate            = date('Y-m-d', strtotime($endDate));
            $startDateTime      = $campaign->getStartDate() . ' ' . $campaign->getStartTime();
            $endDateTime        = $campaign->getEndDate() . ' ' . $campaign->getEndTime();
            if (date('Y-m-d') <= $campaign->getendDate()) { // Vérifier si la campagne n'est pas encore terminé
                if ($shift) {
                    if ($shift->getIdCampaign() != $idCampaign) {
                        $managerShift->modifier([
                            'id_shift'      => $shift->getIdShift(),
                            'id_campaign'   => $idCampaign,
                            'start_time'    => $startDateTime,
                            'end_time'      => $endDateTime
                        ]);
                    } else {
                        $managerShift->modifier([
                            'id_shift'      => $shift->getIdShift(),
                            'id_campaign'   => $idCampaign,
                            'end_time'      => $endDate . ' ' . $endTime  // Terminer le shift à tout moment donné
                        ]);
                    }
                } else {
                    if (date('Y-m-d') >= $campaign->getStartDate()) { // Vérifier si la campagne est en cours
                        $startDateTime = date('Y-m-d', strtotime('+1 DAY')) . ' ' . $campaign->getStartTime(); // Changer la date de debut en date de demain ;
                    }
                    $voirShiftConfus    = self::getExistingShift($idEmploye, 0, $endDate, date('Y-m-d', strtotime($startDateTime)));
                    if ($voirShiftConfus) {
                        $startDateTime = date('Y-m-d', strtotime($voirShiftConfus->getEndTime() . '+1 day'));
                    }
                    $managerShift->ajouter([
                        'id_campaign'   => $idCampaign,
                        'id_employe'    => $idEmploye,
                        'id_entreprise' => $_SESSION['user']['idEntreprise'],
                        'start_time'    => $startDateTime,
                        'end_time'      => $endDateTime
                    ]);
                } 
            } else {
                echo "error : cette campagne est déjà terminée ! ";
                exit;
            }
            echo json_encode(['campaign' => $campaign->toArray()/*,'shifts' => $allShifts*/]);
            exit;
        }

        /**
         * 
         * Supprimer un planning du shift
         * 
         * @changelog 2023-07-03 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters
         *
         * @return json
        */
        public function deleteShift($parameters)
        {
            extract($parameters);
            $managerShift   = new ManagerShift();
            $message        = "";
            $shift          = $managerShift->chercher(['id_shift' => $idShift]);
            if (strtotime(date('Y-m-d')) < strtotime(date('Y-m-d', strtotime($shift->getStartTime())))) {
                $response = $managerShift->supprimer(['id_shift' => $idShift]);
                if ($response) {
                    $message = "Suppression avec succès !";
                }
            } else {
                echo "error"; // Juste renvoyer une erreur forcer
                $message = "Impossible de supprimer ce shift !";
            }
            echo json_encode($message);
            exit;
        }

        /**
         * 
         * Supprimer un planning de la campagne
         * 
         * @changelog 2023-07-03 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters
         *
         * @return json
        */
        public function deleteCampaign($parameters)
        {
            extract($parameters);
            $managerCampaign    = new ManagerCampaign();
            $managerShift       = new ManagerShift();
            $message            = "";
            $campaign           = $managerCampaign->chercher(['libelle' => 'LIKE "%' . $libelle . '%"']);
            if ($campaign) {
                if (strtotime(date('Y-m-d')) < strtotime($campaign->getStartDate())) {
                    $shifts = self::getAllShiftByCampaign($campaign->getIdCampaign());
                    foreach ($shifts as $shift) {
                        $managerShift->supprimer(['id_shift' => $shift->getIdShift()]);
                    }
                    $response = $managerCampaign->supprimer(['id_campaign' => $campaign->getIdCampaign()]);
                    if ($response) {
                        $message = "Suppression avec succès !";
                    }
                } else {
                    echo "error";
                    $message = "Impossible de supprimer une campagne qui est en cours !";
                }
            }
            echo json_encode($message);
            exit;
        }

        /**
         * 
         * Supprimer un planning du shift
         * 
         * @changelog 2023-07-03 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters
         *
         * @return json
        */
        public function getAllworker($parameters)
        {
            $managerEmploye = new ManagerEmploye();
            $listWorker     = $managerEmploye->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            foreach ($listWorker as $user) {
                $responses[] = [
                    "WorkerID"    => $user->getIdEmploye(),
                    "ContactName"   => $user->getNom() . " " . $user->getPrenom(),
                    "CompanyName"   =>"Alfreds Futterkiste",
                    "Photo"         => $user->getPhoto() ? $user->getPhoto() : strtolower($user->getCivilite()) . '.png'
                ];
            }
            echo json_encode($responses);
            exit;
        }

        /**
         * 
         * Supprimer un planning du shift
         * 
         * @changelog 2023-07-03 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters
         *
         * @return object/json
        */
        public function getCampaignResponse($parameters)
        {
            extract($parameters);
            $managerCampaign    = new ManagerCampaign();
            $return             = !isset($return) ? 'object' : $return ;
            $shifts             = [];
            if (isset($parameters['return'])) {
                unset($parameters['return']);
            }
            if (isset($libelle)) {
                $parameters['libelle'] = 'LIKE "%' . $libelle . '%"';
            }
            $campaign   = $managerCampaign->chercher($parameters);
            $allShifts  = self::getAllShiftByCampaign($campaign->getIdCampaign());
            foreach ($allShifts as $shift) {
                $shifts[] = $shift->toArray();
            }
            if ($return == 'json') {
                echo json_encode(['campaign' => $campaign->toArray(), 'shifts' => $shifts]);
                exit;
            } else {
                return ['campaign' => $campaign, 'allShifts' => $shifts];
            }
        }

        /**
         * 
         * Récupérer les shifts par campagne
         * 
         * @changelog 2023-07-17 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param int $idCampaign
         *
         * @return array
        */
        private static function getAllShiftByCampaign($idCampaign)
        {
            $managerShift = new ManagerShift();
            return $managerShift->lister(['id_campaign' => $idCampaign]);
        }

        /**
         * 
         * Vérifier un shift existant à l'intervalle de la date donnée
         * 
         * @changelog 2023-07-21 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param int $idEmploye
         * @param int $idShift
         * @param int $endDate
         * @param int $startDate
         *
         * @return object
        */
        private static function getExistingShift($idEmploye, $idShift, $endDate, $startDate)
        {
            $managerShift = new ManagerShift();
            return $managerShift->chercher([
                'id_entreprise' => $_SESSION['user']['idEntreprise'],
                'id_employe'    => $idEmploye,
                'end_date'      => 'BETWEEN "' . $startDate . '" AND "' . $endDate . '"',
                'start_time'    => 'BETWEEN "' . $startDate . '" AND "' . $endDate . '" AND `id_shift` != ' . $idShift . ' ORDER BY ABS(TIMESTAMPDIFF(SECOND, "' . $startDate . '", `start_time`)), ABS(TIMESTAMPDIFF(SECOND, "' . $startDate . '", `end_time`)) LIMIT 1' // Le plus proche
            ]);
        }

        private function getDebutFin($parameters)
        {
            $intervalle = ['debut' => date('Y-m-d'), 'fin' => date('Y-m-d')];
            if (isset($parameters['periode']) && !empty($parameters['periode'])) {
                $intervalle  = $this->getIntervalle($parameters['periode']);
            } elseif (!empty($parameters['debut'])) {
                $intervalle = $this->getDateDebutFin($parameters['debut'],$parameters['fin']);
            } elseif (isset($parameters['debut']) && isset($parameters['fin'])) {
                if (empty($parameters['debut']) && empty($parameters['fin'])) {
                    $intervalle['debut'] = date('Y-m-d', strtotime('first day of this month'));
                    $intervalle['fin']   = date('Y-m-d', strtotime('last day of this month'));
                } else {
                    $intervalle = $this->getDateDebutFin($parameters['debut'],$parameters['fin']);
                }
            } elseif (empty($parameters['periode'])) {
                /** @changeLog 2022-10-04 [FIX] (Lansky) Afficher toutes les tâches s'il n'y a pas de filtre */
                $intervalle = ['debut'  => date('2020-10-16'), 'fin' => date('Y-m-d')];
            }
            return $intervalle;
        }

        /**
         * Récupérer le résultat final de tâches réalisées par salarié
         * 
         * @changeLog 2023-08-30 [OPTIM] (Lansky) Ajout de la méthode
         *
         * @param array $parameters Critères des donnéees
         *
         * @return array
        */
        private function getUserTask($parameters)
        {
            $fin        = date('Y-m-d');
            $intervalle = $this->getDebutFin($parameters);
            extract($intervalle);
            if (!isset($parameters['userTaskGroup'])) {
                $parameters['userTaskGroup'] = 'all';
            }
            $donnees        = $this->getSuivis($parameters['idEmploye'], $debut, $fin, $parameters['userTaskGroup']);
            /** @changeLog 2022-11-21 [EVOl] (Lansky) Regroupement les tâches par mission */
            $taskGroupe     = array();
            $missionsArray  = array();
            $manager        = new ManagerMission();
            $missions       = $manager->getUserMissions($parameters);
            foreach ($missions as $mission) {
                $missionsArray[] = $mission->toArray();
            }
            foreach ($donnees['taches'] as $key => $value) {
                $during                                                                 = array_key_exists($value->getIdTache(), $donnees['durees']) ? $donnees['durees'][$value->getIdTache()] : $this->getDuree(0);
                $groupedtasks[$value->getIdMission()]['tache'][$value->getIdTache()]    = $value;
                $groupedtasks[$value->getIdMission()]['groupe']                         = $manager->chercher(['idMission' => $value->getIdMission()]);
                if (!isset($groupedtasks[$value->getIdMission()]['ratio'])) {
                    $groupedtasks[$value->getIdMission()]['ratio'] = 0;
                }
                $groupedtasks[$value->getIdMission()]['ratio']                          += isset($donnees['ratios'][$value->getIdTache()]) ? $donnees['ratios'][$value->getIdTache()] : 0;
                $taskGroupe[$value->getIdMission()][]                                   = array_merge($value->toArray(), ['during' => $during]);
                if (isset($taskGroupe[$value->getIdMission()]['mission']['tracking'])) {
                    $tracking           = $taskGroupe[$value->getIdMission()]['mission']['tracking'];
                    $tracking['second'] += $during['second'];
                    $tracking['minute'] += $during['minute'];
                    $tracking['hour']   += $during['hour'];
                    $tracking['time']   += $during['time'];
                    if ($tracking['second'] >= self::ONE_MINUTE) {
                        $tmp                = $tracking['second'];
                        $tracking['second'] = $tmp % self::ONE_MINUTE;
                        $tracking['minute'] += intdiv($tmp, self::ONE_MINUTE);
                    }
                    if ($tracking['minute'] >= self::ONE_MINUTE) {
                        $tmp                = $tracking['minute'];
                        $tracking['minute'] = $tmp % self::ONE_MINUTE;
                        $tracking['hour']   += intdiv($tmp, self::ONE_MINUTE);
                    }
                } else{
                    $tracking   = $during;
                    $taskGroupe[$value->getIdMission()]['mission']['tracking'] = $tracking;
                }
                if (count($missionsArray) > 0 && $tracking) {
                    $taskGroupe[$value->getIdMission()]['mission']  = array_merge($missionsArray[array_search($value->getIdMission(), array_column($missionsArray, 'idMission'))], ['tracking' => $tracking]);
                    $groupedtasks[$value->getIdMission()]['tracking'] = $taskGroupe[$value->getIdMission()]['mission']['tracking'];
                }
            }
            $journey    = $this->getJournee($parameters['idEmploye'], $debut, $fin);
            foreach ($journey["data"] as $key => $vals) {
                unset($vals["info"]['employe']); 
                if ($vals['info']['enConge']) {
                    if ($vals['info']['id'] < $vals['info']['enConge']->getFin() && $vals['info']['enConge']->getDebut() != $vals['info']['enConge']->getFin()) {
                        $vals['info']['enConge']->setHeureDebut(1);
                        $vals['info']['enConge']->setHeureFin(3);
                    }
                    $vals['info']['enConge'] = $vals['info']['enConge']->toArray();
                } 
                if ($vals['info']['enPermission']) {
                    $vals['info']['enPermission'] = $vals['info']['enPermission']->toArray();
                } 
                if ($vals['info']['auRepos']) {
                    $vals['info']['auRepos'] = $vals['info']['auRepos']->toArray();
                }    
                $journey["data"][$key] = $vals;
            }
            if (isset($groupedtasks)) {
                $donnees["taches"] = $groupedtasks;
            }
            $manager            = new ManagerParametrePointage();
            $parametrePointage  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            return array_merge(
                $donnees,
                ['taskGroupe'                   => $taskGroupe],
                ['journey'                      => $journey],
                ['userTaskGroup'                => $missionsArray],
                ['parametrePointageEntreprise'  => $parametrePointage ? $parametrePointage->toArray() : $manager->initialiser()->toArray()]
            );
        }



        private function getIdOfPermission($entreprisePermissions, $idEntreprisePermission)
        {
            $idTypePermissions = array_map(function($permission) use ($idEntreprisePermission) {
                if ($permission->getIdEntreprisePermission() == $idEntreprisePermission) {
                    return $permission->getIdTypePermission();
                }
            }, $entreprisePermissions);
            // son utilisation mais inutile pour l'instant
            // $id = $this->getIdOfPermission($entreprisePermission, $enPermission->getIdEntreprisePermission());
                        // $rubrique = $typePermission[$id]->getDesignation();
            return array_filter($idTypePermissions);
        }

        /**
         * Exporter en excel le tracking d'un salarié par plage de date donée
         * 
         * @changeLog 2023-09-12 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters Critères des donnéees
         *
         * @return empty
        */
        public function exportExcelTracking($parameters)
        {
            $factory = new PublicFonctions();
            extract($this->prepareDataTracking($parameters));
            $factory->dataExportExcel($response,$fileName);
            exit;
        }

        /**
         * Récupérer le tracking d'un salarié avant d'exporter en excel
         * 
         * @changeLog 2023-08-30 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters Critères des donnéees
         *
         * @return array
        */
        private function prepareDataTracking($parameters)
        {
            extract($this->getJourneyUser($parameters, 'name user'));
            array_unshift($data, array_keys($data[0])); // Ajouter l'entête du tableau au debut de la liste
            return ['response' =>$data, 'fileName' => $fileName];
        }

        /**
         * Récupérer le jour de présence d'un salarié
         * 
         * @changeLog 2023-09-13 [OPTIM] (Lansky) Ajout de la méthode
         *
         * @param array $journee
         * @param string $currentDate
         * @param string $fin
         * @param array $data
         * @param float $tempsTotal
         * @param object $idEmploye
         * @param object $managerEntreprisePermission
         * @param object $managerTypePermission
         * @param object $managerEntrepriseFerie
         * @param object $managerJourFerie
         * @param object $managerPresence
         * @param object $managerPointage
         * @param object $managerParametrePointage
         * @param object $managerTache
         * @param object $managerMission
         * @param object $managerRetard
         * @param object $factory
         * @param object $entreprise
         * @param object $employe
         * 
         * @return array
        */
        private function processDaysRecursively($journee, $currentDate, $fin, $data, $tempsTotal, $idEmploye, $managerEntreprisePermission, $managerTypePermission, $managerEntrepriseFerie, $managerJourFerie, $managerPresence, $managerPointage, $managerParametrePointage, $managerTache, $managerMission, $managerRetard, $factory, $entreprise, $employe)
        {
            if (strtotime($currentDate) <= strtotime($fin)) {
                $tab = [
                    'enPermission'      => $this->estEnPermission($employe, $currentDate),
                    'demandePermission' => $this->aDemandePermission($employe, $currentDate),
                    'auRepos'           => $this->estEnRepos($employe, $currentDate),
                    'demandeRepos'      => $this->aDemandeRepos($employe, $currentDate),
                    'enConge'           => $this->estEnConge($employe, $currentDate)
                ];
                $journee['info']    = array_merge($journee['info'], $tab);
                $journee['repos']   = $journee['info']['auRepos'];
                if ($journee['info']['enPermission'] != false) {
                    $entreprisePermission   = $managerEntreprisePermission->chercher([
                        'idEntreprisePermission' => $journee['info']['enPermission']->getIdEntreprisePermission()
                    ]);
                    $permission             = $managerTypePermission->chercher([
                        'idTypePermission'  => $entreprisePermission->getIdTypePermission()
                    ]);
                    $journee['permission']  = $permission;
                }
                $entrepriseFerie = $managerEntrepriseFerie->chercher([
                    'idEntreprise'  => $entreprise->getIdEntreprise(),
                    'date'          => $currentDate
                ]);
                if ($entrepriseFerie != null) {
                    $jourFerie                  = $managerJourFerie->chercher(['idJourFerie' => $entrepriseFerie->getIdJourFerie()]);
                    $journee['jourFerie']       = $jourFerie;
                    $journee['info']['isFerie'] = true;
                } else {
                    $journee['info']['isFerie'] = false;
                    unset($journee['jourFerie']);
                }
                $presence   = $managerPresence->chercher(['idEmploye' => $idEmploye, 'date' => $currentDate]);
                $temps      = 0;
                if ($presence != null) {
                    $dataPresence               = $presence->toArray();
                    $dataPresence['pointages']  = [];
                    $pointages                  = $managerPointage->lister(['idPresence' => $presence->getIdPresence()]);
                    foreach ($pointages as $pointage) {
                        if (is_null($pointage->getFin()) && $presence->getDate() < date('Y-m-d')) {
                            $paramsPointage = $managerParametrePointage->chercher(['idEntreprise' => $employe->getIdEntreprise()]);
                            $pointage->setFin($paramsPointage->getHeureArret());
                        }
                        if ($pointage->getDebut() != null && $pointage->getFin() != null) {
                            $during         = abs(strtotime($pointage->getFin()) - strtotime($pointage->getDebut()));
                            $temps          += $during;
                            $time           = date('H:i:s', $during);
                            $heureInitiale  = new DateTime($time);
                            $heureModifiee  = $heureInitiale->modify('-1 hours');
                            $floatTime      = $factory::convertTime2Float($heureModifiee->format('H:i:s'), 5, '.', '');
                            if ($pointage->getIdTache() != null) {
                                $tache = $managerTache->chercher(['idTache' => $pointage->getIdTache()]);
                                $dataPointage = $pointage->toArray();
                                $missionTache = $managerMission->chercher(['idMission' => $tache->getIdMission()]);
                                $dataPointage['tache'] = array_merge($tache->toArray(), ['mission' => is_object($missionTache) ? $missionTache->toArray() : null]);
                            } else {
                                $dataPointage = $pointage->toArray();
                                $dataPointage['tache'] = null;
                            }
                            $dataPresence['pointages'][] = array_merge($dataPointage, ['floatTime' => $floatTime]);
                        }
                    }
                    $journee['presence'] = $dataPresence;
                } else {
                    $journee['presence'] = null;
                }
                $jour                       = $this->getDayLetter(date("D", strtotime($currentDate)));
                $journee['info']['id']      = date("Y-m-d", strtotime($currentDate));
                $date                       = $this->writeDate(date("Y-m-d", strtotime($currentDate)));
                $journee['info']['jour']    = $jour;
                if ($jour == self::SATURDAY || $jour == self::SUNDAY) {
                    $journee['info']['isWeekend'] = true;
                } else {
                    $journee['info']['isWeekend'] = false;
                }
                $journee['info']['date']    = $date;
                $journee['info']['temps']   = $this->getDuree($temps);
                $retard                     = $managerRetard->chercher([
                    'id_employe'    => $idEmploye,
                    'date'          => 'LIKE "%' . $currentDate . '%"'
                ]);
                $retard                     = $retard ? $retard->toArray() : null;
                $journee['info']['retard']  = $retard;
                $data[]                     = $journee;
                $tempsTotal                 += $journee['info']['temps']['time'];
                $nextDate                   = date('Y-m-d', strtotime($currentDate . ' + 1 DAY')); // Calculer la prochaine date
                // Appel récursif avec la prochaine date
                return $this->processDaysRecursively($journee, $nextDate, $fin, $data, $tempsTotal, $idEmploye, $managerEntreprisePermission, $managerTypePermission, $managerEntrepriseFerie, $managerJourFerie, $managerPresence, $managerPointage, $managerParametrePointage, $managerTache, $managerMission, $managerRetard, $factory, $entreprise, $employe);
            } else {
                return ['data' => $data, 'tempsTotal' => $tempsTotal]; // Cas de base : lorsque $currentDate dépasse $fin, renvoie les données finales
            }
        }

        /**
         * Récupérer le tracking d'un salarié avant d'exporter en excel
         * 
         * @changeLog 2023-09-13 [OPTIM] (Lansky) Ajout de la méthode
         *
         * @param array $parameters Critères des donnéees
         *
         * @return array
        */
        private function getJourneyUser($parameters, $name='', $addName='')
        {
            extract($this->getDebutFin($parameters));
            extract($this->getJournee($parameters['idEmploye'], $debut, $fin));
            $managerTypePermission          = new ManagerTypePermission();
            $managerEntreprisePermission    = new ManagerEntreprisePermission();
            $factory                        = new PublicFonctions();
            $typePermission                 = [];
            $descriptionList                = [];
            $totalNombreHeure               = 0.0;
            $entreprisePermission           = $managerEntreprisePermission->lister([
                'idEntreprise' => $_SESSION['user']['idEntreprise']
            ]);
            $addName = $addName ? '_' . $addName : '_ST-1er_Tri_';
            $fileName = $name ?
                ($name == 'Repartition_heure_affaire_personne' ?
                    ($name . $addName . date('y', strtotime($debut)) . (date('y', strtotime($debut)) != date('y',strtotime($fin)) ? '-' . date('y', strtotime($fin)) : ''))
                : (date('y', strtotime($debut)) . date('m', strtotime($debut)) .
                    (date('m', strtotime($debut)) != date('m',strtotime($fin)) ? '_' . date('m', strtotime($fin)) : '') . '_' .
                    preg_replace('/\s+/', '-', $data[0]['info']['employe']->getPrenom())))
            : ('Export_' . date('d-m-Y', strtotime($debut)) . '_' . date('d-m-Y', strtotime($fin)));
            // Supprimer les indices "employe" et "permissions" du tableau "data"
            for ($i=0; $i < count($data) ; $i++) {
                if (array_key_exists('employe', $data[$i]['info'])) {
                    unset($data[$i]['info']['employe']);
                }
                if (array_key_exists('permissions', $data[$i]['info'])) {
                    unset($data[$i]['info']['permissions']);
                }
            }
            $permis = $managerTypePermission->getAllEntreprisePermission();
            foreach ($permis as $vals) {
                $typePermission[$vals->getIdTypePermission()] = $vals;
            }
            foreach ($data as $value) {
                $date           = $value['info']['date'];
                $affaire        = '-';
                $rubrique       = '-';
                $libelle        = '-';
                // $nombreHeure    = 0.00;
                $findMotif      = false;
                $addTab         = true;
                if (is_array($value['presence'])) {
                    if ($value['presence']['statut'] == self::PRESENT_YES) {
                        $pointages          = $value['presence']['pointages'];
                        $retard             = $value['info']['retard'];
                        $workedTimeJourney  = $value['info']['temps'];
                        // $tmpTime            = $workedTimeJourney['hour'] . ':' . $workedTimeJourney['minute'] . ':' . $workedTimeJourney['second'];
                        // $nombreHeure        = $factory::convertTime2Float($tmpTime, 2, '.', '');
                        // $totalNombreHeure   += $nombreHeure;
                        if (is_array($pointages)) {
                            $addTab = count($pointages) > 1 ? false : true;
                            foreach ($pointages as $valuePointag) {
                                $finishWorkTime = $valuePointag['fin'] ? $valuePointag['fin'] : ($value['presence']['date'] == date('Y-m-d') ? date('H:i:s') : $valuePointag['debut']);
                                $pointingTime   = $this->getDiffTime($valuePointag['debut'], $finishWorkTime);
                                $rubrique       = is_array($valuePointag['tache']['mission']) ? $valuePointag['tache']['mission']['description'] : '-';
                                $affaire        = $valuePointag['tache']['titre'];
                                $libelle        = $valuePointag['tache']['description'];
                                // $tmpTime        = $pointingTime['hour'] . ':' . $pointingTime['minute'] . ':' . $pointingTime['second'];
                                // $nombreHeure    = $factory::convertTime2Float($tmpTime, 2, '.', '');
                                if (count($pointages) > 1) {
                                    $return[]   = [
                                        'Date'      => $date,
                                        'Affaire'   => $affaire,
                                        'Rubrique'  => $rubrique,
                                        'Libellé'   => $libelle,
                                        'Nombre'    => number_format($valuePointag['floatTime'],2)
                                    ];
                                }
                                $descriptionList[$valuePointag['tache']['idTache']] = $valuePointag['tache']['titre']; 
                            }
                        }
                    } else {
                        $findMotif = true;
                    }
                } else {
                    //  Traiter les null ici
                    $findMotif = true;
                }
                if ($findMotif) {
                    $clesTrue = array_keys($value['info'], true); // Récupérer dans un tableau contenant toutes les clés pour lesquelles la valeur est égale à true
                    extract($value['info']);
                    $affaire = "ST0020 - Heures non affectées";
                    if (is_object($enPermission)) {
                        if ($enPermission->getStatut() == self::VALIDATED) {
                            $rubrique = $value['permission']->getDesignation();
                        }
                    } elseif (is_object($enConge)) {
                        $rubrique = $enConge->getRaison();
                    } elseif (in_array('isFerie', $clesTrue)) {
                        $rubrique = "C.JF - Jour Férié";
                    } elseif (in_array('auRepos', $clesTrue)) {
                        $rubrique = "C.RM - Repos Médical";
                    } elseif (in_array('isWeekend', $clesTrue)) {
                        // igniorer pour l'instant
                        $rubrique = "Week-end";
                    } else {
                        $rubrique = "Abscence sans raison";
                    }
                    $descriptionList[0] = $affaire;
                }
                $totalNombreHeure += $value['info']['temps']['floatTime'];
                if ((!$value['info']['isWeekend'] || $value['info']['temps']['time'] > 0) && $addTab) {
                    $return[] = [
                        'Date'      => $date,
                        'Affaire'   => $affaire,
                        'Rubrique'  => $rubrique,
                        'Libellé'   => $libelle,
                        'Nombre'    => number_format($value['info']['temps']['floatTime'], 2)
                    ];
                }
            }
            $return[] = [
                'Date'      => '',
                'Affaire'   => 'TOTAL',
                'Rubrique'  => '',
                'Libellé'   => '',
                'Nombre'    => number_format($totalNombreHeure, 2)
            ];
            return [
                'data'              => $return,
                'fileName'          => $fileName,
                'descriptionList'   =>$descriptionList
            ];
        }

        /**
         * 
         * 
         * @changeLog 2023-09-13 [EVOL] (Lansky) Ajout de la méthode
         *
         * @param array $parameters Critères des donnéees
         *
         * @return array
        */
        public function exportExcelAllUserTask($parameters)
        {
            $factory        = new PublicFonctions();
            $managerEmploye = new ManagerEmploye();
            $employes       = $managerEmploye->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $employes       = $factory::uniqueArrayOfObjectDuplicated($employes, 'getPrenom'); // Trie les salariés en ordre alphabetique de son prénom
            $headers        = ['Description'];
            $response       = [];
            $listRows       = [];
            $results        = [];
            foreach ($employes as $currentUser) {
                $parameters['idEmploye'] = $currentUser->getIdEmploye();
                $headers[$currentUser->getIdEmploye()] = $currentUser->getPrenom(); // OK
                extract($this->getJourneyUser($parameters, 'Repartition_heure_affaire_personne'));
                foreach ($descriptionList as $k => $val) {
                    if (!in_array($val, $listRows)) {
                        $listRows[$k] = $val;
                    }
                }
                $descriptionList['final'] = 'TOTAL';
                foreach ($data as $value) {
                    extract($value);
                    $key = array_search($Affaire, $descriptionList);
                    if (!isset($response[$currentUser->getIdEmploye()][$key])) {
                        $response[$currentUser->getIdEmploye()][$key] = 0.0; 
                    }
                    $response[$currentUser->getIdEmploye()][$key] += $Nombre; 
                }

            }
            asort($listRows);
            $listRows['final']  = 'TOTAL';
            $headers['final']   = 'TOTAL';
            $i = 0;
            foreach ($listRows as $kRow => $row) {
                $j      = 0;
                $somme  = 0.0;
                foreach ($headers as $kCol => $column) {
                    $value              = end($headers) == $kCol ? $row : ($kCol == 0 ? $somme : (array_key_exists($kRow, $response[$kCol]) ? $response[$kCol][$kRow] : number_format(0.0, 2)));
                    $results[$i][$j]    = $value;
                    $somme = ($kCol != 'final' && $kCol != 0) ? ($somme + $value) : 0.0;
                    $j++;
                }
                $i++;
            }
            array_unshift($results, $headers); // Ajouter l'entête du tableau au debut de la liste 
            extract($this->getDebutFin($parameters));
            $dateMark = "DU " . $debut . " AU " . $fin;
            array_unshift($results, [$dateMark]);
            $factory->dataExportExcel($results,$fileName);
            exit();
        }

        /**
         * Récupérer l'état d'un salarié s'il est en permission et/ou en repos
         * 
         * @changeLog 2023-09-13 [OPTIM] (Lansky) Ajout de la méthode
         *
         * @param array $arrayTab     La liste de la demanade de permission d'un salarié
         * @param date $dateToFinf              Date à chercher une permission
         * @param int $index                    L'indice pour parcourir le tableau permissions
         *
         * @return object/null
        */
        private static function findStatusUser($arrayTab, $dateToFind, $manager=null, $index=0)
        {
            if ($index >= count($arrayTab)) {
                return false; // Aucun Permission a été trouvée
            }
            $object      = $arrayTab[$index];
            $objectDuree = $object;
            if ($manager) {
                $objectDuree = $manager->chercher(['idEntreprisePermission' => $object->getIdEntreprisePermission()]);
            }
            $tmpDuree   = $objectDuree->getDuree();
            $tmpDate    = $object->getDate();
            if ($dateToFind == $tmpDate) {
                return $object;
            } else {
                $endDate = date('Y-m-d', strtotime('+' . ($tmpDuree - 1) . ' DAY', strtotime($tmpDate))); // Calculer la date de fin en fonction de la durée
                if ($dateToFind >= $tmpDate && $dateToFind <= $endDate) {
                    return $object;
                } else {
                    return self::findStatusUser($arrayTab, $dateToFind, $manager, $index + 1); // Rechercher récursivement dans la prochaine autorisation
                }
            }
        }



    }