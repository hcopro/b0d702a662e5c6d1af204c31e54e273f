<?php
    
    /**
     * Manager du module Interim du Backend
     *
     * @author Toky
     *
     * @since 10/09/2020 
     */

	use \Core\DbManager;
    use \Core\View;
    use \Model\ManagerCompte;
    use \Model\ManagerEntrepriseFerie;
    use \Model\ManagerEmploye;
    use \Model\ManagerEntreprise;
    use \Model\ManagerServicePoste;
    use \Model\ManagerEntrepriseService;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerContratEmploye;
    use \Model\ManagerMessage;
    use \Model\ManagerTacheAutomatique;
    use \Model\ManagerContrat;
    use \Model\ManagerInterim;
    use \Model\ManagerEvaluationInterim;

	class ManagerModuleInterim extends DbManager
	{
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
        const REFUSED                   = 0;
        const NO                        = 0;
        const YES                       = 1;
        const COMPTE_ENTREPRISE         = 'entreprise';
        const COMPTE_EMPLOYE            = 'employe';
        const TYPE_REQUEST              = "demande";
        const TYPE_VALIDATED            = "valide";
        const TYPE_REJECTED             = "rejete";
        const TYPE_CANCELED             = "annule";
        const TYPE_INFORMATION          = "information";
        const SECOND_TO_DAY             = 86400;
        const ONE_DAY                   = 1;
        const SOURCE                    = "/usr/bin/php /var/www/html/sites/fabrice_randriamihamina/sirhprod.s188766.com/html/";
        const ATTENTE_ACTIVE            = 1;
        const ATTENTE_DESACTIVE         = 0;
        const DIRECT_VALIDATION         = 0;
        const ALL_VALIDATION            = 1;
        const DEFINED_VALIDATION        = 2;
        const ANNEE_ATTENTE             = 1;
        const STAGE                     = "STAGE";
        const CDI                       = "CDI";
        const CDE                       = "CDE";
        const CA                        = "CA";
        const CDD                       = "CDD";
        const POSTE_INTERNE             = 0;


        /**
         * Voir l'interface principale de gestion des intérims
         *
         * @param array $parameters
         *
         * @return array
         */
         public function voirInterim($parameters)
         {
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager    = new ManagerEmploye();
            $employes   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $filtres    = $this->getFiltre($entreprise->getIdEntreprise());
            return [
                'entreprise'  => $entreprise,
                'employes'    => $employes,
                'filtres'     => $filtres,
            ];
         }  

        /**
         * Voir l'interface principale de gestion des intérims
         *
         * @param array $parameters
         *
         * @return array
         */
        public function voirSuiviInterim($parameters)
        {
            $manager    = new ManagerEmploye();
            $chef       = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
            $employes   = $manager->lister(['chefHierarchique' => $chef->getIdEmploye()]);
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $chef->getIdEntreprise()]);
            return [
                'chef'        => $chef,
                'employes'    => $employes
            ];
        }

        /**
         * Voir l'interface principale des évaluations d'intérim
         *
         * @param array $parameters
         *
         * @return array
         */
        public function voirEvaluationInterim($parameters)
        {
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager    = new ManagerEmploye();
            $employes   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $filtres    = $this->getFiltre($entreprise->getIdEntreprise());
            return [
                'entreprise'  => $entreprise,
                'employes'    => $employes,
                'filtres'     => $filtres,
            ];
        }   

        /**
         * Lister les intérims
         *
         * @param array $parameters
         *
         * @return empty
         */
        public function listerInterims($parameters)
        {
            $manager     = new ManagerEntreprise();
            $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($parameters)) {
                if ($parameters['id'] != self::NO) {
                    $manager = new ManagerEntreprisePoste();
                    $poste   = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise(), 'idEntreprisePoste' => $parameters['id']]);
                    $manager = new ManagerInterim();
                    $tmpInterims = $manager->lister(['idEntreprisePoste' => $poste->getIdEntreprisePoste()]);
                } else {
                    $manager   = new ManagerEntreprisePoste();
                    $postes    = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $postes    = array_unique($postes);
                    $tmpInterims = array();
                    foreach ($postes as $poste) {
                        $manager = new ManagerInterim();
                        $tmpInterims = array_merge($tmpInterims, $manager->lister(['idEntreprisePoste' => $poste->getIdEntreprisePoste()]));
                    }
                }
                $interims = array();
                foreach ($tmpInterims as $interim) {
                    $anneeInterim = date('Y', strtotime($interim->getDebut()));
                    if ($anneeInterim == $parameters['annee']) {
                        $interims[] = $interim;
                    }
                }
                $donnees = $this->getInterims($interims); 
                $view = new View("listerInterims");
                $view->sendWithoutTemplate("Backend", "Interim", $donnees, "entreprise"); 
                exit();
            }
        }

        /**
         * Lister les suivis d'intérims
         *
         * @param array $parameters
         *
         * @return empty
         */
        public function listerSuiviInterims($parameters)
        {
            $manager     = new ManagerEmploye();
            $chef  = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
            if (!empty($parameters)) {
                $manager     = new ManagerInterim();
                $tmpInterims = $manager->lister(['chef' => $chef->getIdEmploye()]);
                $interims    = array();
                foreach ($tmpInterims as $interim) {
                    $manager    = new ManagerEvaluationInterim();
                    $evaluation = $manager->chercher(['idInterim' => $interim->getIdInterim()]);
                    if ($evaluation != null) {
                        if ($evaluation->getNote() >= $parameters['noteMin'] && $evaluation->getNote() <= $parameters['noteMax']) {
                            $interims[] = $interim;
                        }
                    } else {
                        $interims[] = $interim;
                    }
                }
                $donnees = $this->getInterims($interims); 
                $view = new View("listerSuiviInterims");
                $view->sendWithoutTemplate("Backend", "Interim", $donnees, "employe"); 
                exit();
            }
        }

        /**
         * Lister les évaluations d'intérim
         *
         * @param array $parameters
         *
         * @return array
         */
        public function listerEvaluationInterims($parameters)
        {
            $manager     = new ManagerEntreprise();
            $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($parameters)) {
                if ($parameters['id'] != self::NO) {
                    $manager = new ManagerEntreprisePoste();
                    $poste   = $manager->chercher(['idEntreprise' => $entreprise->getIdEntreprise(), 'idEntreprisePoste' => $parameters['id']]);
                    $manager = new ManagerInterim();
                    $tmpInterims = $manager->lister(['idEntreprisePoste' => $poste->getIdEntreprisePoste()]);
                } else {
                    $manager   = new ManagerEntreprisePoste();
                    $postes    = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    $postes    = array_unique($postes);
                    $tmpInterims = array();
                    foreach ($postes as $poste) {
                        $manager = new ManagerInterim();
                        $tmpInterims = array_merge($tmpInterims, $manager->lister(['idEntreprisePoste' => $poste->getIdEntreprisePoste()]));
                    }
                }
                $interims = array();
                foreach ($tmpInterims as $interim) {
                    $manager    = new ManagerEvaluationInterim();
                    $evaluation = $manager->chercher(['idInterim' => $interim->getIdInterim()]);
                    if ($evaluation != null) {
                        if ($evaluation->getNote() >= $parameters['noteMin'] && $evaluation->getNote() <= $parameters['noteMax']) {
                            $interims[] = $interim;
                        }
                    } else {
                        $interims[] = $interim;
                    }
                }
                $donnees = $this->getInterims($interims); 
                $view = new View("listerEvaluationInterims");
                $view->sendWithoutTemplate("Backend", "Interim", $donnees, "entreprise"); 
                exit();
            }
        }

        /**
         * Récupérer les données d'intérim
         *
         * @param array $interims liste d'intérims
         *
         * @return array
         */
        private function getInterims($interims)
        {
            $resultats = array();
            foreach ($interims as $interim) {
                $tmp['interim'] = $interim;
                $debut = explode('-', $interim->getDebut());
                $tmp['debut']   = $debut[2] . '/' . $debut[1] . '/' . $debut[0];
                $fin = explode('-', $interim->getFin());
                $tmp['fin']   = $fin[2] . '/' . $fin[1] . '/' . $fin[0];
                if (strtotime($interim->getDebut()) < strtotime(date('Y-m-d'))) {
                    $tmp['editable'] = false;
                } else {
                    $tmp['editable'] = true;
                }
                $manager = new ManagerEmploye();
                $tmp['employe']   = $manager->chercher(['idEmploye' => $interim->getIdEmploye()]);
                $tmp['chef']      = $manager->chercher(['idEmploye' => $interim->getChef()]); 
                $manager          = new ManagerEntreprisePoste();
                $tmp['poste']     = $manager->chercher(['idEntreprisePoste' => $interim->getIdEntreprisePoste()]); 
                $manager = new ManagerEvaluationInterim();
                $tmp['evaluation'] = $manager->chercher(['idInterim' => $interim->getIdInterim()]);
                $resultats[]      = $tmp;
            }
            return $resultats;
        } 

        /**
         * Récupérer les données d'un intérim
         *
         * @param array $parameters les critères de l'intérim
         *
         * @return array
         */
        public function getInterim($parameters)
        {
            $manager = new ManagerInterim();
            $interim = $manager->chercher(['idInterim' => $parameters['idInterim']]);
            $resultats = array();
            $tmp['interim'] = $interim->toArray();
            $debut = explode('-', $interim->getDebut());
            $tmp['debut']   = $debut[2] . '/' . $debut[1] . '/' . $debut[0];
            $fin = explode('-', $interim->getFin());
            $tmp['fin']   = $fin[2] . '/' . $fin[1] . '/' . $fin[0];
            $manager = new ManagerEmploye();
            $tmp['employe']   = $manager->chercher(['idEmploye' => $interim->getIdEmploye()])->toArray();
            $tmp['chef']      = $manager->chercher(['idEmploye' => $interim->getChef()]); 
            if ($tmp['chef'] != null) {
                $tmp['chef'] = $tmp['chef']->toArray();
            }
            $manager          = new ManagerEntreprisePoste();
            $tmp['poste']     = $manager->chercher(['idEntreprisePoste' => $interim->getIdEntreprisePoste()])->toArray(); 
            $manager = new ManagerEvaluationInterim();
            $tmp['evaluation'] = $manager->chercher(['idInterim' => $interim->getIdInterim()]);
            if ($tmp['evaluation'] != null) {
                $tmp['evaluation'] = $tmp['evaluation']->toArray();
            }
            $resultats     = $tmp;
            echo json_encode($resultats);
            exit();
        } 

        /**
         * Mettre à jour un interim
         *
         * @param array $parameters les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourInterim($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerInterim();
                $retour = $manager->ajouter([
                    'idEmploye' => $parameters['idEmploye'],
                    'idEntreprisePoste' => $parameters['idEntreprisePoste'],
                    'chef'  => $parameters['chef'],
                    'debut' => $parameters['debut'],
                    'fin'   => $parameters['fin']
                ]);
                if ($retour->getIdInterim() != self::NO) {
                    $_SESSION['info']['success'] = "Enregistrement terminé avec succès";
                } else {
                    $_SESSION['info']['danger'] = "Echec de l'enregistrement";
                }
            }
        }

        /**
         * Supprimer un intérim si celui-ci n'a pas encore débuté
         *
         * @param array $parameters les critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerInterim($parameters)
        {
            if (!empty($parameters)) {
                $manager = new ManagerInterim();
                $interim = $manager->chercher(['idInterim' => $parameters['idInterim']]);
                $manager = new ManagerEmploye();
                $employe = $manager->chercher(['idEmploye' => $interim->getIdEmploye()]);
                if ($employe != null && $employe->getIdEntreprise() == $_SESSION['user']['idEntreprise']) {
                    $manager->supprimer([
                        'idInterim' => $parameters['idInterim']
                    ]);
                }
            }
        }

        /**
         * Mettre à jour une évaluation
         *
         * @param array $parameters les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourEvaluationInterim($parameters)
        {
            if (!empty($parameters['idEvaluationInterim'])) {
                $manager = new ManagerEvaluationInterim();
                $retour  = $manager->modifier([
                    'idEvaluationInterim' => $parameters['idEvaluationInterim'],
                    'note'                => $parameters['note'],
                    'remarque'            => $parameters['remarque']
                ]);
            } else {
                $manager = new ManagerEvaluationInterim();
                $retour  = $manager->ajouter([
                    'idInterim'   => $parameters['idInterim'],
                    'note'        => $parameters['note'],
                    'remarque'    => $parameters['remarque'],
                    'evaluateur'  => $parameters['evaluateur']
                ]);
                if ($retour->getIdEvaluationInterim() != self::NO) {
                    $_SESSION['info']['success'] = "Enregistrement terminé avec succès";
                } else {
                    $_SESSION['info']['danger'] = "Echec de l'enregistrement";
                }
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
         * Récuperer le jour entier d'une date en français
         *
         * @param date $date la date
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
         * @param date $date la date
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
	}