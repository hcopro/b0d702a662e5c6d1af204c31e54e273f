<?php

    /**
     * Les fonctions utilisées fréquentes dans les classes durant l'éxecution du programme
     *
     * @author Lansky
     *
     * @since 06/09/2022
    */

    namespace Core;
    require 'vendor/autoload.php';
    
    use \PhpOffice\PhpSpreadsheet\Spreadsheet;
    use \PhpOffice\PhpSpreadsheet\IOFactory;
    use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use \Model\ManagerConge;
    use \Model\ManagerContratEmploye;
    use \Model\ManagerEmploye;
    use \Model\ManagerEmployePermission;
    use \Model\ManagerEmployeRepos;
    use \Model\ManagerEntreprisePermission;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerEntrepriseService;
    use \Model\ManagerParametrePermission;
    use \Model\ManagerServicePoste;
    use \Model\ManagerAutorized;
    use \Model\ManagerCandidat;
    use \Model\ManagerSousDomaine;
    use \Model\ManagerFormation;
    use \Model\ManagerDiplome;
    use \Model\ManagerDomaine;
    use \Model\ManagerNiveauEtude;
    use \Model\ManagerExperience;
    use \Model\ManagerOffre;
    use \Model\ManagerContrat;
    use \Model\ManagerMission;
    use \Model\ManagerCandidature;
    use \Model\ManagerNiveauExperience;
    use \Model\ManagerEntreprise;
    use \Model\ManagerCompte;
    use \Model\ManagerSuperadmin;
    use \Model\ManagerMessage;
    use \Model\ManagerEmailContact;
    use \Model\ManagerParametreConge;


    class PublicFonctions
    {
        const VALIDATED     = 2;
        const YES           = 1;
        const NO            = 0;
        const ALL           = 'all';
        const POSTE_INTERNE = 0;
        const PROPOSED      = 1;

        /**
         * Convertir un entier en mois
         *
         * @param int $month
         *
         * @return string
        */
        public function getMonthLetter($month)
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
        public function writeDate($date, $withHour = false)
        {
            $tmp    = explode('-', $date);
            $temps  = '';
            if (count($tmp) == 3 && $date > self::NO) {
                $tmp[2]    = explode(' ', $tmp[2]);
                // $time      = strpos($tmp[2][1], ':') ? explode(':',$tmp[2][1]) : null;
                $time      = in_array(':',$tmp[2]) ? explode(':',$tmp[2][1]) : null;
                if (is_array($time) && $withHour) {
                    $temps = ' à '. $time[0].'h '.$time[1].'min '.$time[2].'sec';
                }
                return $tmp[2][0] . ' ' . $this->getMonthLetter($tmp[1]) . ' ' . $tmp[0] . $temps;    
            } else {
                return $date;
            }
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
        public static function countWorkDaysInMounth($year, $month, $ignore = false) {
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
         * Vérifier si un employé est en permission à une date donnée
         *
         * @param object  $employe L'employé en question
         * @param date    $date    La date en question
         *
         * @return object|false
        */
        public static function estEnPermission($employe, $date)
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
                        return $employePermission;
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
         * @param object  $employe L'employé en question
         * @param date    $date    La date en question
         *
         * @return object|false
        */
        public static function estEnRepos($employe, $date)
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
                        return $employeRepos;
                    }
                    $tmpDate = date('Y-m-d', strtotime('+1 DAY', strtotime($tmpDate)));
                    $tmpDuree--;
                }
            }
            return false;
        }

        /**
         * Vérifier si un employé a un congé à une date donnée
         *
         * @param object $employe  L'emmployé
         * @param date   $date     La date
         *
         * @return object|false
        */
        public static function estEnConge($employe, $date)
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
         * Tester si possède des subordonnés
         *
         * @param array $parameters les critères de l'employé
         *
         * @return array
        */
        public function hasSubordonne($parameters)
        {
            $employes = self::listOfMyTeam()["subordonnes"];
            if (count($employes) > self::NO) {
                extract(self::getServicePosteOfSubordinates($employes));
                return array(
                    'hasSubordinate'    => self::YES,
                    'collaborator'      => $employesArray,
                    'serviceList'       => $services,
                    'posteList'         => $postes
                );
            } else {
                return array('hasSubordinate' => self::NO, 'collaborator' => array());
            }
        }

        /**
         * Récupérer la liste des identifiants de la service et de poste occupée par les subordonnées d'un chef hiérarchique
         *
         *  @changelog 2023-06-14 [EVOL] (Lansky) ajout de la méthode
         * 
         * @param array $employes Les salariés de l'entreprise
         *
         * @return array
        */
        public static function getServicePosteOfSubordinates(&$employes)
        {
            $managerContratEmploye  = new ManagerContratEmploye();
            $managerServicePoste    = new ManagerServicePoste();
            $managerService         = new ManagerEntrepriseService();
            $managerPoste           = new ManagerEntreprisePoste();
            $employesArray          = [];
            $serviceList            = [];
            $objectService          = [];
            $objectPoste            = [];
            $posteList              = [];
            foreach ($employes as $employe) {
                $contrat    = $managerContratEmploye->chercher(['idEmploye' => $employe->getIdEmploye()/*, 'statut' => self::VALIDATED*/]); // Tous les statut si possible; sinon ceux-ci peut causer un dug si seulement si le contrat est NULL
                $employe    = $employe->toArray();
                if ($contrat) {
                    $servicePoste           = $managerServicePoste->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                    $service                = $managerService->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                    $poste                  = $managerPoste->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                    $employe['service']     = ucwords($service->getService());
                    $employe['idService']   = $service->getIdEntrepriseService();
                    $employe['poste']       = $poste->getPoste();
                    $employe['idPoste']     = $poste->getIdEntreprisePoste();
                    if (!in_array($service->getIdEntrepriseService(), $serviceList)) {
                        $serviceList[]      = $service->getIdEntrepriseService();
                        $objectService[]    = $service;
                    }
                    if (!in_array($poste->getIdEntreprisePoste(), $posteList)) {
                        $posteList[]    = $poste->getIdEntreprisePoste();
                        $objectPoste[]  = $poste;
                    }
                } else {
                    $employe['service'] = 'Non définie';
                    $employe['poste']   = 'Aucun';
                }
                $employesArray[]  = $employe;
            }
            return [
                'postes'            => $posteList,
                'objectServices'    => $objectService,
                'objectPostes'      => $objectPoste,
                'services'          => $serviceList,
                'employesArray'     => $employesArray
            ];
        }

        /**
         * Ajouter le menu pour les employés autorisés
         *
         * @param int $idUser
         *
         * @return string
        */
        public static function addMenuAutorizedByUser($idUser) {
            $manager = new ManagerAutorized();
            return $manager->chercher(['id_employe' => $idUser]) ? 'YES' : 'NO';
        }

        /** 
         * Recuperer le détail d'une offre 
         * 
         * @param array $parameters Critères des données à voir  
         * 
         * @return array 
        */
        public function getDetailOffre($parameters)
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
                    $manager        = new ManagerCandidat();
                    $candidat       = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
                    $manager        = new ManagerCandidature();
                    $candidature    = $manager->chercher([
                        'idOffre'    => $offre->getIdOffre(),
                        'idCandidat' => $candidat->getIdCandidat()
                    ]);
                }
                $resultat   = [  
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
         * Trier les offres correspondantes à un candidat
         *
         * @param object $candidat Le candidat concerné
         * @param array $offre Les offres correspondantes
         *
         * @return array
        */
        public function sortOffres($candidat, $offres)
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
                        'priori'    => $length, 
                        'offre'     => $offre
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
         * Prendre le chef
         *
         * @param object $employe       Le salarié concerné pour voir son chef
         * @param object $demandeur     Le salarié de base qui a fait le demande
         *
         * @return object
        */
        public function getChief($employe, $demandeur)
        {
            $manager    = new ManagerEmploye();
            $chef       = $manager->initialiser();
            foreach (explode(',', $employe->getChefHierarchique()) as $superior) {
                if (intval($superior) != self::NO) {
                    if ($this->compareService($demandeur, $manager->chercher(['idEmploye' => $superior]))) {
                        $chef = $manager->chercher(['idEmploye' => $superior]);
                        break;
                    }
                }
            }
            return $chef;
        }

        /**
         * Vérifier le chef courant est le même service du salarié
         *
         * @param object $employe   Le salarié concerné
         * @param object $chef      Le suppérieure
         *
         * @return boolean
        */
        private function compareService($employe, $chef)
        {
            $response = false;
            if ($chef) {
                if ($chef->getMyTeam() == self::ALL) {
                    $response = true;
                } else {
                    $myTeam     = explode(',', $chef->getMyTeam());
                    $manager    = new ManagerContratEmploye();
                    $contrat    = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
                    $manager    = new ManagerServicePoste();
                    if ($contrat) {
                        $servicePoste = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                    }
                    if (isset($servicePoste)) {
                        if ($servicePoste != null) {
                            $manager = new ManagerEntrepriseService();
                            $service = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                            if (in_array($service->getIdEntrepriseService(), $myTeam)) {
                                $response = true;
                            }
                        }
                    }
                }
            }
            return $response;
        }

        /**
         * Récupérer la liste de mes équipes
         *
         * @changelog 2023-05-19 (Lansky) [OPTIM] Ajout de la méthode
         * 
         * @param array $parameters       Le paramètre
         *
         * @return array
        */
        public function listOfMyTeam(&$parameters=null)
        {
            $manager        = new ManagerEmploye();
            $employe        = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
            $donnees        = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $subordinates   = self::getSubordinates($employe, $donnees);
            if ($employe->getMyTeam() != self::ALL) {
                $subordinates = array_filter($subordinates, function($value) use ($employe) {
                    return $this->compareService($value, $employe);
                });
            }
            return [
                'employe'     => $employe,
                'subordonnes' => $subordinates
            ];
        }

        /**
         * Récupérer ses subordonnées
         *
         * @changelog 2023-06-01 (Lansky) [OPTIM] Ajout de la méthode
         * 
         * @param array $allEmployees       La liste du tableau contient employé
         * @param object $employe           L'utilisateur pour voir ses subordonnées
         *
         * @return array
        */
        private function getSubordinates($employe, &$allEmployees)
        {
            $subordinates = [];
            foreach ($allEmployees as $key => $otherEmployee) {
                if (strpos($otherEmployee->getChefHierarchique(), $employe->getIdEmploye()) !== false) {
                    $subordinates[] = $otherEmployee;
                    unset($allEmployees[$key]);
                }
            }
            if (empty($subordinates)) {
                return [];
            }
            $subordinatesAll = $subordinates;
            foreach ($subordinates as $key => $otherEmployee) {
                $otherChilds = self::getSubordinates($otherEmployee, $allEmployees);
                if (empty($otherChilds)) {
                    continue;
                }
                $subordinatesAll = array_merge($subordinatesAll, $otherChilds);
            }
            return $subordinatesAll;
        }

        /**
         * Récupérer les données pour le filtre et les subordonnées rattacher au salarié
         *
         * @changelog 2022-03-08 [EVOL] (Lansky) ajout de la méthode
         * 
         * @param int $idEntreprise L'identifiant de l'entreprise
         *
         * @return array
        */
        public function getFiltre($idEntreprise)
        {
            /**@changelog 2023-06-14 [FIX] (Lansky) Boucle infinie */
            $managerPoste       = new ManagerEntreprisePoste();
            $managerService     = new ManagerEntrepriseService();
            $managerEmploye     = new ManagerEmploye();
            $filter             = [];
            if ($_SESSION['compte']['identifiant'] == 'employe') {
                extract(self::listOfMyTeam());
                $objectServices = [];
                $objectPostes   = [];
                if ($_SESSION['user']['myTeam']) {
                    extract(self::getServicePosteOfSubordinates($subordonnes));
                }
                $filter = [
                    'services'  => $objectServices,
                    'postes'    => $objectPostes,
                    'employes'  => $subordonnes,
                    'employe'   => $employe
                ];
            } else {
                $filter = [
                    'employes'  => $managerEmploye->lister(['idEntreprise' => $idEntreprise]),
                    'services'  => $managerService->lister(['idEntreprise' => $idEntreprise]),
                    'postes'    => $managerPoste->lister([
                        'idEntreprise'  => $idEntreprise,
                        'statut'        => self::POSTE_INTERNE
                    ])
                ];
            }
            return $filter;
        }

        /**
         * Récupérer les données pour le filtre
         *
         * @param int $idEntreprise L'identifiant de l'entreprise
         *
         * @return array
        */
        // public function getFiltre($idEntreprise)
        // {
        //     $manager       = new ManagerEntreprise();
        //     $entreprise    = $manager->chercher(['idEntreprise' => $idEntreprise]);
        //     $manager       = new ManagerEntreprisePoste();
        //     $postes        = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise(), 'statut' => self::POSTE_INTERNE]);
        //     $manager       = new ManagerEntrepriseService();
        //     $services      = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
        //     $manager       = new ManagerEmploye();
        //     $filtres       = array();
        //     $filtres['services'] = $services;
        //     $filtres['postes'] = $postes;
        //     /** @changelog 2022-03-08 [EVOL] (Lansky) Récupérer les subordonnées rattacher au salarié */
        //     if ($_SESSION['compte']['identifiant'] == 'employe') {
        //         $employe        = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
        //         $donnees        = $manager->lister(['chefHierarchique' => "LIKE '%" . $employe->getIdEmploye() . "%'"]);
        //         $employes       = $donnees;
        //         $isDone         = false;
        //         $newServices    = array();
        //         $newPostes      = array();
        //         if (count($donnees) == self::NO) {
        //             $isDone = true;
        //         } 
        //         while (!$isDone) {
        //             $tmp = array();
        //             foreach ($donnees as $donnee) {
        //                 $findSubordinates = $manager->lister(['chefHierarchique' => "LIKE '%" . $donnee->getIdEmploye() . "%'"]);
        //                 if ($findSubordinates) {
        //                     foreach ($findSubordinates as $k => $v) {
        //                         $cmp = $this->compareService($v, $employe);
        //                         if (!$cmp) {
        //                             unset($findSubordinates[$k]);
        //                         }
        //                     }
        //                 }
        //                 $manager        = new ManagerEmploye();
        //                 $tmp            = array_merge($tmp, $findSubordinates);
        //                 $manager        = new ManagerContratEmploye();
        //                 $contrat        = $manager->chercher(['idEmploye' => $donnee->getIdEmploye()]);
        //                 if ($contrat) {
        //                     $manager        = new ManagerServicePoste();
        //                     $servicePoste   = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
        //                     if ($servicePoste) {
        //                         $key = in_array($servicePoste->getIdEntrepriseService(), $services);
        //                         if ($key) {
        //                             $newServices[]  = $services[$key];
        //                         }
        //                         $key = in_array($servicePoste->getIdEntreprisePoste(), $postes);
        //                         if ($key) {
        //                             $newPostes[]    = $postes[$key];
        //                         }
        //                     }
        //                 }
        //             }
        //             if (count($tmp) == self::NO) {
        //                 $isDone = true;
        //             } else {
        //                 $donnees = $tmp;
        //                 $employes = array_merge($employes, $donnees);
        //             }
        //         }
        //         $filtres['employe'] = $employe;
        //         $manager        = new ManagerContratEmploye();
        //         $contrat        = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
        //         $manager        = new ManagerServicePoste();
        //         $servicePoste   = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
        //         if ($servicePoste) {
        //             $key = in_array($servicePoste->getIdEntrepriseService(), $services);
        //             if ($key) {
        //                 $newServices[]          = $services[$key];
        //                 $filtres['services']    = array_unique($newServices);
        //             }
        //             $key = in_array($servicePoste->getIdEntreprisePoste(), $postes);
        //             if ($key) {
        //                 $newPostes[]        = $postes[$key];
        //                 $filtres['postes']  = array_unique($newPostes);
        //             }
        //         }
        //         $employes = $this->listOfMyTeam()['subordonnes'];
        //     } else {
        //         $employes      = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
        //     }
        //     $filtres['employes'] = $employes;
        //     return $filtres;
        // }

        /**
         * Vérifier l'abscence d'un employé à la date donnée
         *
         * @param  object $chef     Le chef hierarchique
         * @param  date $date       La date en question
         *
         * @return boolean
        */
        public function verifyAbsence($chef, $date)
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
         * Récupérer le chef qui n'est pas validateur de congé
         * 
         * @changelog 2023-05-15 [OPT] (Lansky) Ajout de la méthode
         * 
         * @param  object $chef     Le chef hierarchique
         * @param  object $demandeur  Le demandeur de congé
         *
         * @return object
        */
        public function getChiefNotValidate($chef, $demandeur)
        {
            while ($chef->getIsValidator() == 0) {
                // Ecraser Le chef qui n'est pas validateur de demande
                $chef = self::getChief($chef, $demandeur);
                if (is_null($chef) || is_null($chef->getIdEmploye())) {
                    break 1; /* Terminer la boucle while. */
                }
            }
            return $chef;
        }

        /**
         * Récupérer le chef qui n'est pas validateur de congé
         * 
         * @changelog 2023-06-21 [OPT] (Lansky) Ajout de la méthode
         * 
         * @param  boolean $verified        Vérification d'absence
         * @param  object $superior         Le chef hierarchique
         * @param  object $demandeur        Le demandeur de congé
         * @param  string $typeDemand       Le type de la demande soit un congé, soit une permission
         * @param  date $dateDebut          La date debut de la demande
         * @param  date $dateFin            La date fin de la demande
         * @return object
        */
        public function notifyChiefAbsent($verified, $superior, $demandeur, $typeDemande, $dateDebut, $dateFin, $raison=null)
        {
            $managerParametreConge  = new ManagerParametreConge();
            $managerMessage         = new ManagerMessage();   
            // Récupérer les parametres du congé de l'entreprise
            $parametre      = $managerParametreConge->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $chef           = $superior;
            $date           = $this->writeDate($dateDebut, false) . (date('Y-m-d', strtotime($dateDebut . '+1 day')) == $dateFin ? '' : " jusqu'au " . $this->writeDate($dateFin, false));
            $nameDemandeur  = $demandeur->getCivilite() . " " . strtoupper($demandeur->getNom()) . " " . ucwords($demandeur->getPrenom());
            $content    = "<p>Bonjour, </p><br<br><p>" .
                                "Nous vous informons qu'en votre absence " . $nameDemandeur . " a demandé " . $typeDemande . ", ce " . $date . " <br>Motif : " . $raison . 
                            "</p>";
            while ($verified) {
                // Notifier le supérieur au lieu de validation
                if ($parametre->getNotifyAbsence() == self::YES) {
                    $idMessage  = self::sendMessageNotification($chef->getIdCompte(), 'En votre absence&nbsp;<span class="titre">' . $nameDemandeur . '</span> a demandé ' . $typeDemande, $content);
                    if ($idMessage) {
                        $managerMessage->modifier([
                            'idMessage' => $idMessage,
                            'aFaire'    => self::NO
                        ]);
                    }
                    self::sendMailNotification($chef, 'En votre absence ' . $nameDemandeur . ' a demandé ' . $typeDemande, $content, true);
                }
                $chef       = self::getChief($chef, $demandeur);
                if (!is_null($chef) && $chef->getIdEmploye()) {
                    $verified   = self::verifyAbsence($chef, date('Y-m-d'));
                    $chef       = self::getChiefNotValidate($chef, $demandeur);
                } else {
                    $verified = false;
                }
            }
            return $chef;
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
        public function writeDateConge($debut, $heureDebut, $fin, $heureFin, $beginto, $during)
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
         * Récupérer le compte de l'utilisateur
         * 
         * @changeLog 2023-06-22 [EVOL] (Lansky) Ajout ed la méthode
         *
         * @param int $idCompte      L'identidiant du compte
         *
         * @return array
        */
        public static function getUserByIdCompte($idCompte)
        {
            $managerEntreprise  = new ManagerEntreprise();
            $managerEmploye     = new ManagerEmploye();
            $managerSuperAdmin  = new ManagerSuperadmin();
            $managerCandidat    = new ManagerCandidat();
            $managerCompte      = new ManagerCompte();
            $compte             = $managerCompte->chercher(['idCompte' => $idCompte]);
            $manager            = ($compte->getIdentifiant() == 'entreprise' ? $managerEntreprise : ($compte->getIdentifiant() == 'employe' ? $managerEmploye : ($compte->getIdentifiant() == 'superadmin' ? $managerSuperAdmin : $managerCandidat)));
            $managerUser        = $manager->chercher(['idCompte' => $idCompte]);
            $name               = $compte->getIdentifiant() == 'entreprise' ? $managerUser->getNom() : $managerUser->getCivilite() . " " . strtoupper($managerUser->getNom()) . " " . ucwords($managerUser->getPrenom());
            $name               = $_SESSION['compte']['idCompte'] == $compte->getIdCompte() ? 'Vous' : $name;
            return ['compte' => $compte, 'managerUser' => $managerUser, 'name' => $name];
        }

        /** 
         * Récupérer le dérnier identifiant de la table (Non opérationnel)
         * 
         * @changeLog 2023-07-12  [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param string $table
         * @param string $idName
         * 
         * @return int
        */
        public static function getLastId($table, $idName)
        {            
            return $this->findLast('table', 'idName')['id'] + 1;
        }

        private static function randomColorPart() {
            return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
        }

        public static function randomColor() {
            return self::randomColorPart() . self::randomColorPart() . self::randomColorPart();
        }
        /** 
         * Récupérer le dérnier identifiant de la table (Non opérationnel)
         * 
         * @changeLog 2023-08-23  [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param array $arrayOfObject      Le tableau à trier
         * @param string $sortBy            Trier le tableau d'objets par une propriété spécifique
         * 
         * @return array
        */
        public static function uniqueArrayOfObjectDuplicated($arrayOfObject, $sortBy)
        {
            // pas satisfaisant le résultat
            // $uniqueArray = array_map("unserialize", array_unique(array_map("serialize", $arrayOfObject)));
            // return array_column($uniqueArray, null, $sortBy);
            usort($arrayOfObject, function($a, $b) use ($sortBy) {
                return self::compareObjects($a, $b, $sortBy);
            }); // Triez le tableau d'objets à l'aide de la fonction de comparaison personnalisée
            return array_unique($arrayOfObject); // Supprimer les objets en double du tableau
        }
        /** 
         * Définition de la fonction de comparaison personnalisée
         * 
         * @changeLog 2023-08-23  [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param object $a 
         * @param object $b
         * 
         * @return object
        */
         private static function compareObjects($a, $b, $sortBy)
        {
            return $a->$sortBy() <=> $b->$sortBy();
        }

        /**
         * Envoyer un message de notification à un utilisateur
         * 
         * @changeLog 2023-08-25 [OPT] (Lansky) Ajout de la méthode
         * 
         * @param int    $idCompte L'identifiant de l'utilisateur
         * @param string $objet    L'objet du message
         * @param string $contenu  Le contenu du message   
         *
         * @return empty
        */
        private static function sendMessageNotification($idCompte, $objet, $contenu)
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
         * Envoyer un email de notification à un employé
         *
         * @changeLog 2023-08-25 [OPT] (Lansky) Ajout de la méthode
         * 
         * @param object $object    L'employé ou l'entreprise concerné
         * @param string $content   Le contenu du mail
         *
         * @return empty
        */
        private static function sendMailNotification($destinataire, $subject, $content, $is_info = false, $pieceJointe = null)
        {
            if ($is_info) {
                $manager    = new ManagerEmailContact();
                $from       = 'HumaNexus <' . strtolower($manager->chercher(['type' => 'information'])->getEmail()) . '>';
            } else {
                $from       = strtolower($_SESSION['user']['email']);
            }
            $to         = $destinataire->getEmail();
            // Headers
            $headers[]  = 'MIME-Version: 1.0'; // Defining the MIME version
            $headers[]  = "From: " . $from;
            if (strstr($content, '<img')) {
                $content = str_replace("Son ordonance", "Ci-jointe son ordonance", substr($content, 0, strpos($content, '<img')));
            }
            if (is_null($subject)) {
                $subject = "Notification venant de " . strtoupper($_SESSION['user']['nom']);
            }
            $content    .= '<br><br> Cordialement,<br><br>L&apos;équipe <a href="https://hco.mg/">Human Cart&apos;Office</a>';
            if ($pieceJointe) { // Pas encore utilisé
                $pathFiles      = DOC_ROOT . 'Ressources/images/ordonances/' .$pieceJointe;
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
         * Exporter les données en excel ou en csv
         *
         * @changeLog 2023-08-31 [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param array $data    Données à exporter
         *
         * @return empty
        */
        public function dataExportExcel($data=[], $name=null)
        {
            // Créez une nouvelle feuille de calcul
            $spreadsheet    = new Spreadsheet();
            $sheet          = $spreadsheet->getActiveSheet();
            $rowIndice      = 0;
            // Ajoutez des données à la feuille de calcul
            foreach ($data as $row) {
                $currentColumn = 'A';
                $rowIndice++;
                foreach ($row as $value) {
                    $cell = $currentColumn . $rowIndice;
                    // if (is_numeric($value)) {
                    //     // Insérez un nombre avec deux chiffres après la virgule dans une cellule
                    //     $number = 123.456789; // Votre nombre
                    //     $cellCoordinate = 'A1'; // Coordonnées de la cellule
                    //     $format = '0.00'; // Format pour deux chiffres après la virgule
                    //     $sheet->setCellValueExplicit($cellCoordinate, $number, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    //     $sheet->getStyle($cellCoordinate)->getNumberFormat()->setFormatCode($format);

                    // }
                    $sheet->setCellValue($cell, $value);
                    $currentColumn = self::incrementColumn($currentColumn);
                }
            }
            // Créez un objet Writer pour générer le fichier XLSX
            $name   = $name ? $name . '.xlsx' : 'export-user.xlsx';
            $writer = new Xlsx($spreadsheet);
            $file   = TMP_DIR . '/' . $name;
            $writer->save($file); // Sauvegardez le fichier Excel
            if (file_exists($file)) {
                // Définissez le type de contenu et les en-têtes pour le téléchargement du fichier
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                header('Content-Description: File Transfer');
                // Envoi du fichier Excel en téléchargement
                header('Cache-Control: max-age=0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                unlink($file); // Supprimer le fichier au serveur
            } else {
                // Gérer le cas où le fichier n'existe pas
                echo "Le fichier n'a pas été trouvé.";
            }
            exit;
        }

        /**
         * Convertir heure en float
         *
         * @changeLog 2023-09-07 [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param string    $heureFormat    Heure à vonvertisser et son format  "H:i:s" => par exemple "03:29:58"
         * @param int       $limit          Nombre de chiffre après la virgule
         * @param string    $separator      Notation indique la représentation du nombre décimal
         * @param string    $millier        Millers signifie l'habitude de la lecture du chiffre
         *
         * @return float // Retournera 3.4994444444
        */
        public static function convertTime2Float($heureFormat, $limit = 2, $separator = ',', $millier = ' ')
        {
            // Divisez les minutes par 60 pour obtenir la fraction d'heure
            list($heures, $minutes, $secondes) = sscanf($heureFormat, "%d:%d:%d");
            $heureFloat = $heures + ($minutes / 60);
            return number_format($heureFloat, $limit, $separator, $millier);
        }

        /**
         * Incrémenter la colonne
         *
         * @changeLog 2023-09-13 [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param string    $column    Heure à vonvertisser et son format  "H:i:s" => par exemple "03:29:58"
         *
         * @return string
        */
        private static function incrementColumn($column)
        {
            // Vérifier si la dernière colonne est "Z"
            if ($column === 'Z') {
                return 'AA'; // Si c'est le cas, retourner "AA"
            }
            // Si ce n'est pas "Z", convertir la colonne en tableau de caractères
            $characters = str_split($column);
            $lastChar   = array_pop($characters); // Prendre le dernier caractère de la colonne
            // Si le dernier caractère est "Z", récursivement traiter la partie précédente
            if ($lastChar === 'Z') {
                // Appel récursif pour traiter la partie précédente
                $previousPart   = implode('', $characters);
                $newLastChar    = 'A'; // Réinitialiser le dernier caractère à "A"
                $newPart        = incrementColumn($previousPart); // Appel récursif
                return $newPart . $newLastChar;
            } else {
                $newLastChar = chr(ord($lastChar) + 1); // Si le dernier caractère n'est pas "Z", incrémenter le dernier caractère
                return implode('', $characters) . $newLastChar;
            }
        }
        
    }