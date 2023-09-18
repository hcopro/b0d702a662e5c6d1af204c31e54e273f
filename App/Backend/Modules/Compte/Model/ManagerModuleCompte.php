<?php
    
    /**
     * Manager du modules Compte du Backend
     *
     * @author Voahirana
     *
     * @since 30/09/19 
    */

    use \Core\DbManager;
    use \Core\View;
    use \Core\PublicFonctions;
    use \Model\ManagerSuperadmin;
    use \Model\ManagerEntreprise;
    use \Model\ManagerCandidat;
    use \Model\ManagerPersonnalite;
    use \Model\ManagerEmailContact;
    use \Model\ManagerCandidature;
    use \Model\ManagerHistorique;
    use \Model\ManagerEmploye;
    use \Model\ManagerConfiguration;
    use \Model\ManagerTemplate;
    use \Model\ManagerContrat;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerEntrepriseService;
    use \Model\ManagerServicePoste;
    use \Model\ManagerCategorieProfessionnelle;
    use \Model\ManagerContratEmploye;
    use \Model\ManagerMessage;
    use \Model\ManagerCompte;
    use \Model\ManagerFormation;
    use \Model\ManagerCompetence;
    use \Model\ManagerLangue;
    use \Model\ManagerLogiciel;
    use \Model\ManagerSousDomaine;
    use \Model\ManagerDomaine;
    use \Model\ManagerNiveauEtude;
    use \Model\ManagerExperience;
    use \Model\ManagerDiplome;
    use \Model\ManagerCentreInteret;
    use \Model\ManagerOffre;
    use \Model\ManagerEntretien;
    use \Model\ManagerNiveauEntretien;
    use \Model\ManagerInterlocuteur;
    use \Entity\Contrat;
    require_once "Lib/Core/PhpDocx.php";

    class ManagerModuleCompte extends DbManager
    {
        const YES                         = 1;
        const NO                          = 0;
        const VALIDATED                   = 2;
        const EMPTY                       = 0;
        const PROPOSED                    = 1;  
        const TODAY                       = 1;
        const TOMORROW                    = 2;
        const YESTERDAY                   = 3;
        const THIS_WEEK                   = 4;
        const NEXT_WEEK                   = 5;
        const LAST_WEEK                   = 6;
        const THIS_MONTH                  = 7;
        const NEXT_MONTH                  = 8;
        const LAST_MONTH                  = 9; 
        const NEW_MESSAGE                 = 1;
        const ARCHIVED_MESSAGE            = 0;
        const SEEN_MESSAGE                = 2;
        const ALL_MESSAGE                 = 3;     
        const CDI                         = 'CDI';
        const CDD                         = 'CDD';
        const CDE                         = 'CDE';
        const CA                          = 'CA';
        const STAGE                       = 'STAGE';
        const INTERIM                     = 'INTERIM';
        const ALL                         = 'all';

        /** 
         * Lister les candidats actifs
         * 
         * @return array
        */
        public function listerActiveCandidats()
        {
            $resultats = array();
            $manager   = new ManagerCandidat();
            $candidats = $manager->lister();
            if (!empty($candidats)) {
                foreach ($candidats as $candidat) {
                    $manager = new ManagerCompte();
                    $compte  = $manager->chercher(['idCompte' => $candidat->getIdCompte()]);
                    if ($compte->getStatut() != "archive") {
                        $resultats[] = [
                            'candidat'=> $candidat, 
                            'compte'  => $compte];
                    } 
                }
            }
            return $resultats;
        }

        /** 
         * Lister les archives des candidats
         * 
         * @return array
        */
        public function listerArchiveCandidats()
        {
            $resultats = array();
            $manager   = new ManagerCandidat();
            $candidats = $manager->lister();
            if (!empty($candidats)) {
                foreach ($candidats as $candidat) {
                    $manager = new ManagerCompte();
                    $compte  = $manager->chercher(['idCompte' => $candidat->getIdCompte()]);
                    if ($compte->getStatut() == "archive") {
                        $resultats[] = [
                            'candidat' => $candidat, 
                            'compte'   => $compte
                        ];
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les entreprises actifs
         * 
         * @return array
        */
        public function listerActiveEntreprises()
        {
            $resultats   = array();
            $manager     = new ManagerEntreprise();
            $entreprises = $manager->lister();
            if (!empty($entreprises)) {
                foreach ($entreprises as $entreprise) {
                    $manager    = new ManagerCompte();
                    $compte     = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                    $manager    = new ManagerEmploye();
                    $employes   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                    if ($compte->getStatut() == "active") {
                        $resultats[] = [
                            'entreprise'            => $entreprise, 
                            'compte'                => $compte,
                            'effectifCollaborateur' => count($employes)
                        ];
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les entreprises inactifs
         * 
         * @return array
        */
        public function listerInactiveEntreprises()
        {
            $resultats   = array();
            $manager     = new ManagerEntreprise();
            $entreprises = $manager->lister();
            if (!empty($entreprises)) {
                foreach ($entreprises as $entreprise) {
                    $manager = new ManagerCompte();
                    $compte  = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                    if ($compte->getStatut() == "desactive") {
                        $resultats[] = [
                            'entreprise' => $entreprise, 
                            'compte'     => $compte
                        ];
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les archives des entreprises
         * 
         * @return array
        */
        public function listerArchiveEntreprises()
        {
            $resultats = array();
            $manager   = new ManagerEntreprise();
            $entreprises = $manager->lister();
            if (!empty($entreprises)) {
                foreach ($entreprises as $entreprise) {
                    $manager = new ManagerCompte();
                    $compte  = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                    if ($compte->getStatut() == "archive") {
                        $resultats[] = [
                            'entreprise' => $entreprise, 
                            'compte'     => $compte
                        ];
                    }
                }
            }
            return $resultats;
        } 

        /** 
         * Lister les superadmins
         * 
         * @return array
        */
        public function listerSuperadmins()
        {
            $resultats   = array();
            $manager     = new ManagerSuperadmin();
            $superadmins = $manager->lister();
            if (!empty($superadmins)) {
                foreach ($superadmins as $superadmin) {
                    if ($superadmin->getIdCompte() != $_SESSION['compte']['idCompte']) {
                        $manager     = new ManagerCompte();
                        $compte      = $manager->chercher(['idCompte' => $superadmin->getIdCompte()]);
                        $resultats[] = [
                            'superadmin' => $superadmin, 
                            'compte'     => $compte
                        ];
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les Historiques
         * 
         * @return array
        */
        public function listerHistoriques()
        {
            $resultats   = array();
            $entity      = "";
            $manager     = new ManagerHistorique();
            $historiques = $manager->lister(null);
            if (!empty($historiques)) {
                foreach ($historiques as $historique) {
                    $manager    = new ManagerCompte();
                    $compte     = $manager->chercher(['idCompte' => $historique->getIdCompte()]);
                    if (!empty($compte)) {
                        $manager    = new ManagerSuperadmin();
                        $superadmin = $manager->chercher(['idSuperadmin' => $historique->getIdSuperadmin()]);
                        $entity     = "\Model\Manager" . ucfirst($compte->getIdentifiant());
                        $manager    = new $entity();
                        $user       = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
                        if (!empty($user)) {
                            $resultats[] = [
                                'historique' => $historique,
                                'compte'     => $compte,
                                'user'       => $user,   
                                'superadmin' => $superadmin
                            ];
                        }
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les emails de contact
         * 
         * @return array
        */
        public function listerEmailsContacts()
        {
            $manager = new ManagerEmailContact();
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
         * Voir le profil d'un Candidat
         * 
         * @author Billy Bam
         * 
         * @return array
        */
        public function voirProfilCandidat()
        {
            $result['candidat']         = $this->voirCandidat()['candidat'];
            if (array_key_exists('candidatures', $this->listerCandidatures())){
                $result['candidatures'] = $this->listerCandidatures()['candidatures'];
            }
            $result['formations']       = array_key_exists('formations', $this->getCandidateFormation()) ? $this->getCandidateFormation()['formations'] : array();
            $result['diplomes']         = array_key_exists('diplomes', $this->getCandidateDiplome()) ? $this->getCandidateDiplome()['diplomes'] : array();
            $result['niveauEtudes']     = $this->listerNiveauEtude();
            $result['experiences']      = $this->getCandidateExperience()['experiences'];
            $result['offres']           = $this->suggestOffresCandidate();
            $result['personnalites']    = $this->listerPersonnalites(['id_candidat' => $_SESSION['user']['idCandidat']]);
            $result['langues']          = $this->listerLangues(['id_candidat' => $_SESSION['user']['idCandidat']]);
            $result['logiciels']        = $this->listerLogiciel(['id_candidat' => $_SESSION['user']['idCandidat']]);
            $result['centreInterets']   = $this->listerCentreInteret(['id_candidat' => $_SESSION['user']['idCandidat']]);
            $result['sousDomaines']     = $this->listerSousDomaine(['idSousDomaine']);
            $result['domaines']         = $this->listerDomaine(['id_domaine' => $_SESSION['user']['idCandidat']]);
            $result['competences']      = $this->listerCompetence(['idCompetence']);
            return $result;
        }

        /** 
         * Voir le profil d'une entreprise
         * 
         * @return objet
        */
        public function voirProfilEntreprise()
        {

            $manager    = new ManagerEntreprise(); 
            $entreprise = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager    = new ManagerCompte();
            $compte     = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager    = new ManagerEmploye();
            $employes   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            return [  
                'entreprise'            => $entreprise,
                'compte'                => $compte,
                'effectifCollaborateur' => count($employes)
            ];
        }

        /** 
         * Voir le profil d'un superadmin
         * 
         * @return objet|empty
        */
        public function voirProfilSuperadmin()
        {
            $manager = new ManagerSuperadmin();
            return $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
        }

        /** 
         * Voir le profil d'un employé
         * 
         * @return objet
        */
        public function voirProfilEmploye()
        {
            $manager            = new ManagerEmploye(); 
            $_SESSION['user']   = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']])->toArray();
            $employe            = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager            = new ManagerContratEmploye();
            $contratEmploye     = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
            if ($contratEmploye) {
                $manager        = new ManagerServicePoste();
                $servicePoste   = $manager->chercher(['idServicePoste' => $contratEmploye->getIdServicePoste()]);
                $manager        = new ManagerEntrepriseService();
                $service        = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                $manager        = new ManagerEntreprisePoste();
                $poste          = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                $manager        = new ManagerCategorieProfessionnelle();
                $categorie      = $manager->chercher(['idCategorieProfessionnelle' => $servicePoste->getIdCategorieProfessionnelle()]);
            }
            if (!isset($service)) {
                $service = null;
            }
            if (!isset($categorie)) {
                $categorie = null;
            }
            if (!isset($poste)) {
                $poste = null;
            }
            $manager            = new ManagerCompte();
            $compte             = $manager->chercher(['idCompte' => $employe->getIdCompte()]);
            return [
                'employe'   => $employe,
                'poste'     => $poste,
                'service'   => $service,
                'categorie' => $categorie,
                'compte'    => $compte
            ];
        }

        /** 
         * Voir le détail d'un Candidat
         * 
         * @param array $parameters Le candidat concerné
         *
         * @return array
        */
        public function voirDetailCandidat($parameters)
        {
            $resultat = array();
            $manager  = new ManagerCandidat();
            if (isset($parameters)) {
                $candidat = $manager->chercher($parameters);
                if (!empty($candidat)) {
                    $manager  = new ManagerCompte(); 
                    $compte   = $manager->chercher(['idCompte' => $candidat->getIdCompte()]);
                    $resultat = [
                        'candidat' => $candidat, 
                        'compte'   => $compte];
                }
            } 
            return $resultat;
        } 

        /** 
         * Voir le détail d'une entreprise
         *
         * @param array $parameters L'entreprise concernée
         *
         * @return array
        */
        public function voirDetailEntreprise($parameters)
        {
            $resultat = array();
            $manager  = new ManagerEntreprise();
            if (isset($parameters)) {
                $entreprise = $manager->chercher($parameters);
                if (!empty($entreprise)) {
                    $manager  = new ManagerCompte(); 
                    $compte   = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                    $resultat = [
                        'entreprise' => $entreprise, 
                        'compte'     => $compte];
                }
            } 
            return $resultat;
        }

        /** 
         * Voir le détail d'un superadmin
         * 
         * @param array $parameters Le superadmin concerné
         *
         * @return array
        */
        public function voirDetailSuperadmin($parameters)
        {
            $resultat = array();
            $manager  = new ManagerSuperadmin();
            $superadmin = $manager->chercher($parameters);
            if (!empty($superadmin)) {
                $manager  = new ManagerCompte(); 
                $compte   = $manager->chercher(['idCompte' => $superadmin->getIdCompte()]);
                $resultat = [
                    'superadmin' => $superadmin, 
                    'compte'     => $compte];
            } 
            return $resultat;
        }

        /** 
         * Voir le détail historique
         * 
         * @param array $parameters l'historique à détailler
         *
         * @return array
        */
        public function voirDetailHistorique($parameters)
        {
            $resultat = array();
            $manager  = new ManagerHistorique();
            $historique = $manager->chercher($parameters);
            if (!empty($historique)) {
                $manager  = new ManagerCompte(); 
                $compte   = $manager->chercher(['idCompte' => $historique->getIdCompte()]);
                $manager  = new ManagerSuperadmin(); 
                $superadmin   = $manager->chercher(['idSuperadmin' => $historique->getIdSuperadmin()]);
                $resultat = [
                    'historique' => $historique,
                    'superadmin' => $superadmin, 
                    'compte'     => $compte];
            } 
            return $resultat;
        }

        /**
         * Voir la boite aux lettres
         *
         * @param array $parameters les données à voir
         *
         * @return array
        */
        public function voirMessage($parameters)
        {
            if (empty($_SESSION['message'])) {
                $_SESSION['message']['periode'] = self::ALL;
            }
            $manager = new ManagerMessage();
            $compte  = $manager->chercher(['idCompte' => $_SESSION['user']['idCompte']]);
            if ($compte != null) {
                return [
                    'compte' => $compte
                ];
            }
        }

        /**
         * Marquer un message comme lu
         *
         * @param array $parameters critères du message à marquer
         *
         * @return empty
        */
        public function marquerMessage($parameters)
        {
            if (!empty($parameters)) {
                $manager     = new ManagerMessage();
                foreach (explode(',',$parameters['idMessage']) as $idMessage) {
                    if ($idMessage) {
                        $message     = $manager->chercher(['idMessage' => $idMessage]);
                        if ($message->getIdCompte() == $_SESSION['user']['idCompte']) {
                            $message = $manager->modifier([
                                'idMessage' => $idMessage,
                                'statut'    => self::SEEN_MESSAGE
                            ]);
                        }
                    }
                }
            }
        }

        /**
         * Archiver un message
         *
         * @param array $parameters critères du message à archiver
         *
         * @return empty
        */
        public function archiverMessage($parameters)
        {
            if (!empty($parameters)) {
                $manager     = new ManagerMessage();
                foreach (explode(',', $parameters['idMessage']) as $idMessage) {
                    if ($idMessage) {
                        $message     = $manager->chercher(['idMessage' => $idMessage]);
                        if ($message->getIdCompte() == $_SESSION['user']['idCompte']) {
                            $message = $manager->modifier([
                                'idMessage' => $idMessage,
                                'statut'    => self::ARCHIVED_MESSAGE
                            ]);
                        }
                        if ($message->getStatut() == self::ARCHIVED_MESSAGE) {
                            $_SESSION['info']['success'] = "le message a été archivé avec succès";
                        } else {
                            $_SESSION['info']['danger'] = "Echec lors de l'opération";
                        }
                    }
                }
            }
        }

        /**
         * Restaurer un message
         *
         * @param array $parameters critères du message à restaurer
         *
         * @return empty
        */
        public function restaurerMessage($parameters)
        {
            if (!empty($parameters)) {
                $manager     = new ManagerMessage();
                foreach (explode(',', $parameters['idMessage']) as $idMessage) {
                    if ($idMessage) {
                        $message     = $manager->chercher(['idMessage' => $idMessage]);
                        if ($message->getIdCompte() == $_SESSION['user']['idCompte']) {
                            $message = $manager->modifier([
                                'idMessage' => $idMessage,
                                'statut'    => self::SEEN_MESSAGE
                            ]);
                            if ($message->getStatut() == self::SEEN_MESSAGE) {
                                $_SESSION['info']['success'] = "le message a été restauré avec succès";
                            } else {
                                $_SESSION['info']['danger'] = "Echec lors de l'opération";
                            }
                        }
                    }
                }
            }
        }

        /**
         * Lister les messages
         * 
         * @param array $parameters les données à lister
         *
         * @return array
        */
        public function listerMessages($parameters)
        {

            if (!empty($_SESSION['user'])) {
                $_SESSION['message']['type'] = $parameters['type'];
                if (!empty($parameters)) {
                    $messages = array();
                    if (!empty($parameters['periode'])) {
                        $_SESSION['message']['periode'] = $parameters['periode'];
                        unset($_SESSION['message']['debut']);
                        unset($_SESSION['message']['fin']);
                        unset($_SESSION['message']['mois']);
                        $intervalle = $this->getIntervalle($parameters['periode']);
                        if (is_null($intervalle)) {
                            $intervalle = ['debut' => null, 'fin' => null];
                        }
                        $messages   = $this->getMessages($_SESSION['user']['idCompte'],$intervalle['debut'], $intervalle['fin'], $parameters['type']);
                    } elseif (!empty($parameters['debut'])) {
                        $_SESSION['message']['debut'] = $parameters['debut'];
                        unset($_SESSION['message']['periode']);
                        unset($_SESSION['message']['fin']);
                        unset($_SESSION['message']['mois']);
                        if (!empty($parameters['fin'])) {
                            $_SESSION['message']['fin'] = $parameters['fin'];
                            unset($_SESSION['message']['periode']);
                            unset($_SESSION['message']['mois']);
                            $debut      = $parameters['debut'];
                            $fin        = $parameters['fin'];
                            $messages   = $this->getMessages($_SESSION['user']['idCompte'],$debut, $fin, $parameters['type']);
                        } else {
                            $debut      = $parameters['debut'];
                            $fin        = $parameters['debut'];
                            $messages   = $this->getMessages($_SESSION['user']['idCompte'],$debut, $fin, $parameters['type']);
                        }
                    } elseif (!empty($parameters['mois'])) {
                        $_SESSION['message']['mois'] = $parameters['mois'];
                        unset($_SESSION['message']['debut']);
                        unset($_SESSION['message']['fin']);
                        unset($_SESSION['message']['periode']);
                        $debut     = date('Y-' . $parameters['mois'].'-01');
                        $fin       = date('Y-' . $parameters['mois'].'-t');
                        $messages   = $this->getMessages($_SESSION['user']['idCompte'],$debut, $fin, $parameters['type']);
                    }
                    $donnees = [
                        'messages' => $messages
                    ];
                    $view = new View("listerMessages");
                    $view->sendWithoutTemplate("Backend", "Compte", $donnees, ""); 
                    exit();
                }
            }
        }

        /**
         * Retourner les messages d'un utilisateur dans un intervalle de temps
         *
         * @param int  $idCompte l'identifiant de l'utilsateur
         * @param date $debut    le début de l'intervalle
         * @param date $fin      la fin de l'intervalle
         * @param int  $type     le type de statut des messages
         *
         * @return array
        */
        private function getMessages($idCompte, $debut, $fin, $type)
        {
            if (strpos($debut, '/') !== false) {
                $date = explode("/", $debut);
                $debut = $date[2] . '-' . $date[1] . '-' . $date[0];
            }
            if (strpos($fin, '/') !== false) {
                $date = explode("/", $fin);
                $fin = $date[2] . '-' . $date[1] . '-' . $date[0];
            }
            $manager  = new ManagerMessage();
            if ($type == self::ALL_MESSAGE) {
                if (is_null($debut)) {
                    $tmps = $manager->lister([
                        'idCompte' => $idCompte
                    ]);
                } else {
                    $tmps = $manager->selectionner(
                        ['idCompte' => $idCompte],
                        ['date'     => $debut],
                        ['date'     => $fin]
                    ); 
                }
                $messages = array();
                foreach ($tmps as $tmp) {
                    if ($tmp->getStatut() != self::ARCHIVED_MESSAGE) {
                        $messages[] = $tmp;
                    }
                }
            } else {
                if (is_null($debut)) {
                    $messages = $manager->lister([
                        'idCompte'  => $idCompte,
                        'statut'    => $type
                    ]);
                } else {
                    $messages = $manager->selectionner(
                        ['idCompte' => $idCompte,
                        'statut'    => $type],
                        ['date'     => $debut],
                        ['date'     => $fin]
                    );    
                }
            }
            return $messages;
        }

        /**
         * Vérifier les nouveaux messages
         *
         * @param array $parameters critère des messages
         *
         * @return empty
        */
        public function verifierNouveauMessage($parameters)
        {
            if (!empty($parameters['idCompte']) && $parameters['idCompte'] == $_SESSION['user']['idCompte']) {
                $manager        = new ManagerMessage();
                $messages       = $manager->lister(['idCompte' => $parameters['idCompte'], 'statut' => self::NEW_MESSAGE]);
                if (!empty($messages)) {
                    if (count($messages) > self::NO) {
                        echo count($messages);
                    } else {
                        echo "non";
                    }
                } else {
                    echo "non";
                }
            }
            exit();
        }

        /**
         * Récupérer les dates de début et fin d'une période
         *
         * @param int $periode la période selon les constantes définie dans cette classe
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
                default:
                    return null;
                    break;
            }
        }

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
         * Chercher un candidat 
         * 
         * @param array $parameters Le candidat concerné
         * 
         * @return object
        */
        public function chercherCandidat($parameters)
        {
            $manager = new ManagerCandidat();
            return $manager->chercher($parameters);
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
         * Afficher le formulaire d'un superadmin
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
        */
        public function afficherFormSuperadmin($parameters)
        {
            $manager = new ManagerSuperadmin();
            if (isset($parameters)) {
                $superadmin = $manager->chercher($parameters);
            } else {
                $superadmin = $manager->initialiser();
            }
            return $superadmin;
        }

        /** 
         * Afficher le formulaire d'un compte
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
        */
        public function afficherFormPseudo($parameters)
        {
            $manager = new ManagerCompte();
            if (isset($parameters)) {
                $compte = $manager->chercher($parameters);
            } else {
                $compte = $manager->initialiser();
            }
            return $compte;
        }

        /** 
         * Afficher le formulaire d'un email de contact
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
        */
        public function afficherFormEmailContact($parameters)
        {
            $manager = new ManagerEmailContact();
            if (isset($parameters)) {
                $emailContact = $manager->chercher($parameters);
            } else {
                $emailContact = $manager->initialiser();
            }
            return $emailContact;
        }

        /** 
         * Supprimer un superadmin
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerSuperadmin($parameters)
        {
            $resultat = "";
            $managerSuperadmin  = new ManagerSuperadmin();
            if (isset($parameters)) {
                $superadmin = $managerSuperadmin->chercher($parameters);
                if (!empty($superadmin)) {
                    $manager = new ManagerHistorique();
                    $historique = $manager->chercher(['idSuperadmin' => $superadmin->getIdSuperadmin()]);
                    $managerCompte = new ManagerCompte();
                    $compte        = $managerCompte->chercher(['idCompte' => $superadmin->getIdCompte()]);
                    if (!empty($compte) && $compte->getStatut() == "desactive" && empty($historique)) {
                        $listes = $manager->lister(['idCompte' => $superadmin->getIdCompte()]);
                        if (!empty($listes)) {
                            foreach ($listes as $liste) {
                                $manager->supprimer(['idCompte' => $liste->getIdCompte()]);
                            }
                        }
                        $managerCompte->supprimer(['idCompte' => $superadmin->getIdCompte()]);
                        $managerSuperadmin->supprimer($parameters);
                        $_SESSION['info']['success'] = "Suppression avec succès";
                    } else {
                        $_SESSION['info']['warning'] = "On ne peut pas encore supprimer ce compte";
                    }
                }
            }
        }

        /** 
         * Ajouter un superadmin
         *
         * @param array $parameters Les données à ajouter 
         *
         * @return object
        */
        public function ajouterSuperadmin($parameters)
        {
            $superadmin = "";
            $compte     = "";
            $dataCompte = $_SESSION['variable'];
            if (!empty($dataCompte)) {
                $dataCompte['motDePasse'] = md5($dataCompte['motDePasse']);
                $manager                  = new ManagerCompte();
                $compte                   = $manager->creerCompte($dataCompte);
            }
            $parameters['idCompte'] = $compte->getIdCompte();
            if (!empty($parameters['idCompte'])) {
                $manager    = new ManagerSuperadmin();
                $superadmin = $manager->ajouter($parameters);
            }
            return $superadmin;
        }

         /** 
         * Modifier un superadmin
         *
         * @param array $parameters Les données à modifier 
         *
         * @return empty
        */
        public function modifierSuperadmin($parameters)
        {
            $manager = new ManagerSuperadmin();
            $manager->modifier($parameters);
            $_SESSION['user'] = $manager->chercher(['idSuperadmin' => $parameters['idSuperadmin']])->toArray();
        }

        /** 
         * Ajouter un email de contact
         *
         * @param array $parameters Les données à ajouter 
         *
         * @return empty
        */
        public function ajouterEmailContact($parameters)
        {
            $manager = new ManagerEmailContact();
            $manager->ajouter($parameters);
        }

        /** 
         * Modifier un email de contact
         *
         * @param array $parameters Les données à modifier 
         *
         * @return empty
        */
        public function modifierEmailContact($parameters)
        {
            $manager = new ManagerEmailContact();
            $manager->modifier($parameters);
        }

        /** 
         * Supprimer un email de contact
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerEmailContact($parameters)
        {
            $manager = new ManagerEmailContact();
            $manager->supprimer($parameters);
            $_SESSION['info']['success'] = "Suppression avec succès";
        }

        /** 
         * Modifier un candidat
         *
         * @param array $parameters Les données à modifier 
         *
         * @return empty
        */
        public function modifierCandidat($parameters)
        {
            $stringPersonnalite = "";         
            if (isset($parameters['autrePersonnalite'])) {
                if (!empty($parameters['autrePersonnalite'])) {
                   $this->modifierPersonnalite($parameters['autrePersonnalite']);
                   $parameters['personnalite'] .= $parameters['autrePersonnalite']; 
                }
                unset($parameters['autrePersonnalite']);
            }
            if (isset($parameters['autreQualite'])) {
                unset($parameters['autreQualite']);
            }
            if (isset($parameters['personnalite'])) {
                $tabPersonnalite = explode("_", $parameters['personnalite']);
                array_pop($tabPersonnalite);
                if (isset($tabPersonnalite)) { 
                    var_dump("tabEn marche");
                    for ($i = 0; $i < count($tabPersonnalite) ; $i++) {
                        if (!(strstr($stringPersonnalite, $tabPersonnalite[$i]."_"))) {
                            $stringPersonnalite .= $tabPersonnalite[$i] . "_";
                        }
                    }
                }
                $parameters["personnalite"] = $stringPersonnalite;
            }
            $manager    = new ManagerCandidat();
            $candidat   = $manager->modifier($parameters);
            if (isset($parameters['personnalite'])) {
                $data = explode('_', $candidat->getPersonnalite());
                array_pop($data);
                echo json_encode($data);
                exit();   
            } else {
                $_SESSION['user'] = $manager->chercher(['idCandidat' => $parameters['idCandidat']])->toArray();
            }
        }

        /** 
         * Modifier un l'état du publicité d'un profil candidat
         *
         * @param array $parameters Les données à modifier 
         *
         * @return empty
        */
        public function modifierPublique($parameters)
        {
            $manager = new ManagerCandidat();
            $candidat = $manager->chercher($parameters);
            if ($candidat->getPublique() == 1) {
                $parameters['publique'] = 0;
            } else {
                $parameters['publique'] = 1;
            }
            $manager->modifier($parameters);
        }

         /** 
         * Modifier un entreprise
         *
         * @param array $parameters Les données à modifier 
         *
         * @return empty
        */
        public function modifierEntreprise($parameters)
        {
            $manager = new ManagerEntreprise();
            $manager->modifier($parameters);
            $_SESSION['user'] = $manager->chercher(['idEntreprise' => $parameters['idEntreprise']])->toArray();
        }

        /** 
         * Modifier un compte
         *
         * @param array $compte le compte à modifier
         * @param array $parameters critères des données à modifier 
         *
         * @return empty
        */
        public function modifierCompte($compte, $parameters)
        {
            if ($compte->getIdentifiant() == "candidat") {
                $manager = new ManagerCandidat();
                $candidat = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
                $manager = new ManagerCandidature();
                $candidature = $manager->chercher(['idCandidat' => $candidat->getIdCandidat()]);
                if (empty($candidature)) {
                    $this->mettreAJourCompte($compte, $parameters);
                } else {
                    $_SESSION['info']['danger'] = "On ne peut pas encore désactiver ce compte";
                }
            } else {
                $this->mettreAJourCompte($compte, $parameters);
            }
        }

        /** 
         * Executer la modification d'un compte
         *
         * @param object $compte le compte concerné
         * @param array $parameters les données à modifier 
         *
         * @return empty
        */
        private function mettreAJourCompte($compte, $parameters)
        {
            $to      = "";
            $subject = "";
            $message = "";
            $etat    = "";
            $headers = "";

            $manager = new ManagerCompte();
            $newCompte = $manager->modifier($parameters);
            if ($newCompte->getStatut() == "active") {
                $etat = "activé";
            } else if ($newCompte->getStatut() == "desactive") {
                $etat = "désactivé";
            } else if ($newCompte->getStatut() == "archive"){
                $etat = "archivé";
            }
            $_SESSION['info']['success'] = "Compte " . $etat;
            $compte  = $manager->chercher(['idCompte' => $compte->getIdCompte()]);

            $entity  = "\Model\Manager" . ucfirst($compte->getIdentifiant());
            $manager = new $entity();
            $user    = $manager->chercher(['idCompte' => $parameters['idCompte']]);
            $manager = new ManagerEmailContact();
            $emails  = $manager->lister();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    if ($email->getType() == "information" && !empty($user)) {
                        $to      = $user->getEmail();
                        $subject = "Une information pour vous";
                        $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour " . strtoupper($user->getNom()) . " !,</label><br><br>
                                                <label>Nous informons que votre compte sur le site de la société
                                                <br>Human Cart'Office est désormais " . $etat . "</label><br><br>
                                                <label>Pour plus d'information, veuillez vous contacter sur le site en cliquant <a href='" . HOST . "/accueil#contact'>ici</a></label><br>
                                                <label>Pour accéder à votre compte cliquez <a href='" . HOST . "/connexion'>ici</a></label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                            </div>
                                        </body></html>";
                        $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                        mail($to, $subject, $message, $headers);
                    }
                }
            }
            $this->historiserCompte($compte);
        }

        /** 
         * Historiser les compte
         *
         * @param object $compte le compte concerné
         *
         * @return empty
        */
        private function historiserCompte($compte)
        {
            $parameters = array();
            $manager = new ManagerSuperadmin();
            $superadmin = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($superadmin)) {
                $parameters['date'] = date("Y-m-d");
                $parameters['action'] = $compte->getStatut();
                $parameters['idCompte'] = $compte->getIdCompte();
                $parameters['idSuperadmin'] = $superadmin->getIdSuperadmin();
            } 
            if (!empty($parameters)) {
                $manager = new ManagerHistorique();
                $manager->ajouter($parameters);
            }
        }

        /**
         * Voir l'organigramme de l'entreprise
         *
         * @param array $parameters les critères des données à afficher
         * 
         * @return array
        */
        public function voirOrganigramme($parameters)
        {
            $manager     = new ManagerEntreprise();
            $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager     = new ManagerEntrepriseService();
            $services    = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $manager     = new ManagerEmploye();
            $allEmployes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $employes    = array();
            foreach ($allEmployes as $employe) {
                $type = $this->getTypeContratEmploye($employe);
                if ($type != null) {
                    if ($type->getDesignation() == self::CDD || $type->getDesignation() == self::CDI) {
                        $employes[] = $employe;
                    }
                }
            }
            $postes      = array();
            foreach ($employes as $employe) {
                $postes[$employe->getIdEmploye()]  = $this->getPosteEmploye($employe);
            }
            return [
                'entreprise'  => $entreprise,
                'employes'    => $employes,
                'postes'      => $postes,
                'services'    => $services
            ]; 
        }

        /**
         * Récupérer les données de l'organigramme
         *
         * @param array $parameters les critères des données à récupérer
         *
         * @return array
        */
        public function getDataOrganigramme($parameters)
        {
            if (!empty($parameters)) {
                if ($parameters['id'] == self::NO) {
                    $manager = new ManagerEmploye();
                    $tmpEmployes = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $employes = array();
                    foreach ($tmpEmployes as $employe) {
                        $poste = $this->getPosteEmploye($employe);
                        $employeArray = $employe->toArray();
                        $employeArray['poste'] = $poste->getPoste();
                        $employeArray['niveau'] = $poste->getNiveau(); 
                        $employes[] = $employeArray;
                    }
                } else {
                    $manager = new ManagerEntrepriseService();
                    $service = $manager->chercher([
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
                            $manager    = new ManagerEmploye();
                            $employe    = $manager->chercher(['idEmploye' => $contrat->getIdEmploye()]);
                            if (!in_array($employe, $employes)) {
                                $poste = $this->getPosteEmploye($employe);
                                $employeArray = $employe->toArray();
                                $employeArray['poste'] = $poste->getPoste();
                                $employeArray['niveau'] = $poste->getNiveau(); 
                                $employes[] = $employeArray;
                                while ($employe->getChefHierarchique() != self::NO) {
                                    $manager = new ManagerEmploye();
                                    $chef = $manager->chercher(['idEmploye' => $employe->getChefHierarchique()]);
                                    if (!in_array($chef, $employes)) {
                                        $poste = $this->getPosteEmploye($employe);
                                        $employeArray = $chef->toArray();
                                        $employeArray['poste'] = $poste->getPoste();
                                        $employeArray['niveau'] = $poste->getNiveau(); 
                                        $employes[] = $employeArray;
                                    }
                                    $employe = $chef;
                                }
                            }
                        }
                    }
                }
                echo json_encode($employes);
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
         * Redirection si une requête GET n'est pas autorisée
         *
         * @return empty
        */
        private function rediriger()
        {
            header("Location:" . HOST . "manage/entreprise/dashboard");
        }

        /**
         *  Lister les langues
         * 
         *  @return array
         *  
         *  @param $resultat
        */  
        public function listerLangues($args)
        {
            $manager = new ManagerLangue();
            return $manager->lister($args);
        }

        /**
         * fonction d'obtention experience
         * 
         * @param $resultat
         * 
         * @return array
        */
        public function getCandidateExperience()
        {
            $resultats     = array();
            $manager       = new ManagerCandidat();
            $candidat      = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $manager        = new ManagerExperience(); 
                $experiences    = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                $resultats      = [
                    'candidat'    => $candidat, 
                    'experiences' => $experiences
                ];
            }
            return $resultats;
        }

        /**
         * fonction permettant d'obtenir les formations des candidats
         *
         * 
         * @param $resultat
         *
         * @return array
        */
        public function getCandidateFormation()
        {

            $resultats  = array();
            $manager    = new ManagerCandidat();
            $candidat   = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $manager    = new ManagerFormation(); 
                $formations = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                if (!empty($formations)) {
                    foreach ($formations as $formation) {
                        $manager      = new ManagerSousDomaine(); 
                        $sousDomaine  = $manager->chercher(['idSousDomaine' => $formation->getIdSousDomaine()]);
                        $manager      = new ManagerNiveauEtude();
                        $niveauEtude  = $manager->chercher(['idNiveauEtude' => $formation->getIdNiveauEtude()]);
                        $tabFormation[]  = [
                            'formation'   => $formation,
                            'niveauEtude' => $niveauEtude,
                            'sousDomaine' => $sousDomaine
                        ];
                    }
                    $resultats['formations'] = $tabFormation;
                }
                $resultats['candidat'] =  $candidat;                 
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
            $resultats           = array();
            $tabCandidature      = array();
            $manager             = new ManagerCandidat();
            $candidat            = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $manager      = new ManagerCandidature();
                $candidatures = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                if (!empty($candidatures)) {
                    foreach ($candidatures as $candidature) {
                        $manager         = new ManagerOffre();
                        $offre           = $manager->chercher(['idOffre' => $candidature->getIdOffre()]);
                        $manager         = new ManagerEntreprisePoste();
                        $poste           = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                        $manager         = new ManagerEntreprise();
                        $entreprise      = $manager->chercher(['idEntreprise' => $offre->getIdEntreprise()]);
                        $manager         = new ManagerEntretien();
                        $entretien       = $manager->chercher(['idCandidature' => $candidature->getIdCandidature()]);
                        $niveauEntretien = "";
                        $interlocuteurs  = "";
                        if (!empty($entretien)) {
                            $manager = new ManagerNiveauEntretien();
                            $niveauEntretien = $manager->chercher(['idNiveauEntretien' => $entretien->getIdNiveauEntretien()]);
                            if (!empty($niveauEntretien)) {
                                $manager        = new ManagerInterlocuteur();
                                $interlocuteurs = $manager->lister(['idEntreprise' => $offre->getIdEntreprise()]);
                            }
                        }
                        $tabCandidature[]     = [
                            'candidature'     => $candidature, 
                            'entretien'       => $entretien,
                            'offre'           => $offre, 
                            'entreprise'      => $entreprise,
                            'niveauEntretien' => $niveauEntretien,
                            'interlocuteurs'  => $interlocuteurs,
                            'poste'           => $poste
                        ];
                    }
                    $resultats = [
                        'candidatures' => $tabCandidature,
                        'candidat' => $candidat
                    ]; 
                }
            }
            return $resultats;
        }

        public function getListeOffres()
        {
            $tabOffre   = array();
            $resultats  = array();
            $manager = new ManagerOffre();
            $offres = $manager->lister();
            if (!empty($offres)) {
                foreach ($offres as $offre) {
                    $manager     = new ManagerSousDomaine();
                    $sousDomaine = $manager->chercher(['idSousDomaine' => $offre->getIdSousDomaine()]);
                    $manager     = new ManagerContrat();
                    $contrat     = $manager->chercher(['idContrat' => $offre->getIdContrat()]);
                    $manager     = new ManagerEntreprisePoste();
                    $poste       = $manager->chercher(['idEntreprisePoste' => $offre->getIdEntreprisePoste()]);
                    $manager     = new ManagerEntreprise();
                    $entreprise  = $manager->chercher(['idEntreprise' => $offre->getIdEntreprise()]);
                    $tabOffre[]  = [
                        'poste'       => $poste,
                        'offre'       => $offre, 
                        'sousDomaine' => $sousDomaine, 
                        'contrat'     => $contrat,
                        'entreprise'  => $entreprise
                    ];                        
                }
                $resultats['offres'] = $tabOffre;  
            }
            return $resultats;
        }

        /**
         * fonction d'obtention diplome
         * 
         * @param $resultat
         * 
         * @return array
        */
        public function getCandidateDiplome()
        {
            $resultats = array();
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
            }
            return $resultats;
        }

        /**
         *  Lister les logiciels
         * 
         *  @return array
         * 
         *  @param $manager
        */ 
        public function listerLogiciel($args)
        {
            $manager = new ManagerLogiciel();
            return $manager->lister($args);
        }

        /**
         *  Lister les centres d'interet
         * 
         *  @return array
         * 
         *  @param $manager
        */ 
        public function listerCentreInteret($args)
        {
            $manager = new ManagerCentreInteret();
            return $manager->lister($args);
        }

        /**
         *  Lister les sous domaines
         * 
         *  @return array
         * 
         *  @param $manager
        */ 
        public function listerSousDomaine($args)
        {
            $manager = new ManagerSousDomaine();
            return $manager->lister($args);
        }

        /**
         *  Lister les niveaux d'etudes
         * 
         *  @return array
         * 
         *  @param $manager
        */ 
        public function listerNiveauEtude()
        {
            $manager = new ManagerNiveauEtude();
            return $manager->lister();
        }

        /**
         *  Lister les domaines
         * 
         *  @return array
         * 
         *  @param $manager
        */ 
        public function listerDomaine($args)
        {
            $manager = new ManagerDomaine();
            return $manager->lister($args);
        }

        /**
         *  Lister les competences
         * 
         *  @return array
         * 
         *  @param $manager
        */ 
        public function listerCompetence($args)
        {
            $manager = new ManagerCompetence();
            return $manager->lister($args);
        }

        /**
         *  voir le profil du candidat
         * 
         *  @return array
         * 
         *  @param $manager
        */ 
        public function voirCandidat()
        {
            $manager    = new ManagerCandidat();
            $candidat   = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager    = new ManagerCompte();
            $compte     = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            return [
                'candidat'  => $candidat,
                'compte'    => $compte
            ];
        }

        /**
         *  envoyer un email pour les offres suggeres
         * 
         *  @return array
         * 
         *  @param $manager
        */ 
        public function sendEmailOffre($parameters)
        {
            $result['offres']           = $this->suggestOffresCandidate();
            if (isset($result)) {
                $subject    = "Recrutement";
                $message    = "<html><body>
                                    <div class='container'>
                                        <label>Bonjour " . ucwords($candidat->getPrenom()) . ", </label><br><br>
                                        <label>
                                          Nous avons le plaisir de vous informer que nous avons une nouvelle offre à vous proposer
                                          il s'agit du poste de" . $offre['poste']->getPoste();"
                                        </label><br><br>
                                        <label>
                                            Pour plus d'information, veuillez-vous connecter sur le site en cliquant <a href='" . HOST . "/connexion'>ici</a>
                                        </label><br><br>
                                        <label>Cordialement, </label><br><br>
                                        <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                    </div>
                                </body></html>";
                $to         = $candidat->getEmail();                            
                $headers    = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                mail($to, $subject, $message, $headers);
            }
        }

        /** 
         * Lister les offres suggérés à un candidat
         *
         * @return array
        */
        private function suggestOffresCandidate()
        {
            $resultats      = Array();
            $manager        = new ManagerCandidat();
            $candidat       = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $profilCandidat = $manager->specifierProfilCandidat(['idCandidat' => $candidat->getIdCandidat()]);
                extract($profilCandidat);
                $managerOffre = new ManagerOffre();
                $offres       = $managerOffre->lister();
                if (!empty($offres)) {
                    foreach ($offres as $offre) {
                        $manager = new ManagerCandidature();
                        $candidature = $manager->chercher(['idOffre' => $offre->getIdOffre(), 'idCandidat' => $candidat->getIdCandidat()]);
                        if (empty($candidature)) {
                            if ($offre->getDateLimite() >= date('Y-m-d')) {
                                $profilOffre = $managerOffre->specifierProfilOffre(['idOffre' => $offre->getIdOffre()]);
                                if (!empty($experiences)) {
                                    foreach ($experiences as $experience) {
                                        if ($profilOffre['experience']['poste'] == $experience['poste'] && $profilOffre['experience']['annee'] <= $experience['annee']) {
                                            $manager        = new PublicFonctions();
                                            $resultats[]    = $manager->getDetailOffre(['idOffre' => $offre->getIdOffre()]);
                                        }
                                    }
                                } 
                                if (!empty($formations)) {
                                    foreach ($formations as $formation) {
                                        if (isset($formation['domaine']) && isset($profilOffre['formation']['domaine']) && isset($formation['niveau']) && isset($profilOffre['formation']['niveau'])) {
                                            if ($profilOffre['formation']['domaine'] == $formation['domaine'] && $profilOffre['formation']['niveau'] <= $formation['niveau']) {
                                                $manager        = new PublicFonctions();
                                                $resultats[]    = $manager->getDetailOffre(['idOffre' => $offre->getIdOffre()]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $method = new PublicFonctions();
                $resultats = $method->sortOffres($candidat, $resultats);
                $responses = [];
                foreach ($resultats as $vals) {
                    if (!empty($vals["offre"])){
                        $responses[] = $vals["offre"];
                    }
                }
            }
            return $responses;
        }

        /** 
         * Creer le CV du candidat
         * 
         * @author Billy Bam
         * 
         * @return array
        */
        public function creerCV()
        {
            $result['candidat']         = $this->voirCandidat()['candidat'];
            $result['formations']       = array_key_exists('formations', $this->getCandidateFormation()) ? $this->getCandidateFormation()['formations'] : array();
            $result['diplomes']         = array_key_exists('diplomes', $this->getCandidateDiplome()) ? $this->getCandidateDiplome()['diplomes'] : array();
            $result['niveauEtudes']     = $this->listerNiveauEtude();
            $result['experiences']      = $this->getCandidateExperience()['experiences']; 
            $result['langues']          = $this->listerLangues(['id_candidat' => $_SESSION['user']['idCandidat']]);
            $result['logiciels']        = $this->listerLogiciel(['id_candidat' => $_SESSION['user']['idCandidat']]);
            $result['centreInterets']   = $this->listerCentreInteret(['id_candidat' => $_SESSION['user']['idCandidat']]);
            $result['sousDomaines']     = $this->listerSousDomaine(['idSousDomaine']);
            $result['domaines']         = $this->listerDomaine(['id_domaine' => $_SESSION['user']['idCandidat']]);
            return $result;
        }

        /**
         *Ajouter une langue
         * 
         * @param array $parameters 
         *  
         * @return empty
         * 
        */
        public function ajouterLangue($parameters)
        {
            $manager    = new ManagerLangue();
            $result     = $manager->ajouter([
                'id_langue'     => $parameters['idLangue'], 
                'id_candidat'   => $parameters['idCandidat'], 
                'nom_langue'    => $parameters['nomLangue'], 
                'niveau_ecrit'  => isset($parameters['niveauEcrit']) ? $parameters['niveauEcrit'] : 0, 
                'niveau_parle'  => isset($parameters['niveauParle']) ? $parameters['niveauParle'] : 0
            ]);
            if (is_object($result)) {
                $_SESSION['info']['success'] = "Ajout avec succès";
            }
            else{
                $_SESSION['info']['warning'] = "On ne peut pas encore ajouter cette langue";
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
         * ajouter une experience
         * 
         * @author Billy Bam
         * 
         * @param array
         * 
         * @return empty
         * 
        */
        public function ajouterExperience($parameters)
        {
            $manager = new ManagerExperience();
            $result = $manager->ajouter([
                'idExperience'  => $parameters['idExperience'],
                'idCandidat'    => $parameters['idCandidat'],
                'dateDebut'     => date('Y-m-d', strtotime($parameters['dateDebut'])),
                'dateFin'       => date('Y-m-d', strtotime($parameters['dateFin'])),
                'poste'         => $parameters['poste'],
                'entreprise'    => $parameters['entreprise'],
                'description'   => $parameters['description']
            ]);
            if (is_object($result)) {
                $_SESSION['info']['success'] = "Ajout avec succès";
            }
            else{
                $_SESSION['info']['warning'] = "On ne peut pas encore ajouter cette experience";
            }
        }

        /** 
         * ajouter une formation
         * 
         * @author Billy Bam
         * 
         * @param array
         * 
         * @return empty
         * 
        */
        public function ajouterFormation($parameters)
        {
            $parameters = $this->verifierParamsSousDomaine($parameters);
            $manager    = new ManagerFormation();
            $result     = $manager->ajouter([
                'idFormation'   => $parameters['idFormation'],
                'idCandidat'    => $parameters['idCandidat'],
                'idSousDomaine' => $parameters['idSousDomaine'],
                'idNiveauEtude' => $parameters['idNiveauEtude'],
                'dateDebut'     => date('Y-m-d', strtotime($parameters['dateDebut'])),
                'dateFin'       => date('Y-m-d', strtotime($parameters['dateFin'])),
                'etablissement' => $parameters['etablissement'],
                'description'   => $parameters['description']
            ]);
            if (is_object($result)) {
                $_SESSION['info']['success'] = "Ajout avec succès";
            }
            else{
                $_SESSION['info']['warning'] = "On ne peut pas encore ajouter cette experience";
            }
        }

        /** 
         * ajouter un centre d'interet
         * 
         * @author Billy Bam
         * 
         * @param array
         * 
         * @return empty
         * 
        */
        public function ajouterCentreInteret($parameters)
        {
            $manager    = new ManagerCentreInteret();
            $result     = $manager->ajouter([
                'id_centre_interet'             => $parameters['idCentreInteret'],
                'id_candidat'                   => $parameters['idCandidat'],
                'categorie_centre_interet'      => $parameters['categorieCentreInteret'],
                'description_centre_interet'    => $parameters['descriptionCentreInteret']
            ]);
            if (is_object($result)) {
                $_SESSION['info']['success'] = "Ajout avec succès";
            }
            else{
                $_SESSION['info']['warning'] = "On ne peut pas encore ajouter ce centre d'interet";
            }
        }

        /** 
         * Ajouter un logiciel
         * 
         * @author Billy Bam
         * 
         * @param array
         * 
         * @return empty
         * 
        */
        public function ajouterLogiciel($parameters)
        {
            $manager    = new ManagerLogiciel();
            $result     = $manager->ajouter([
                'id_logiciel'               => $parameters['idLogiciel'], 
                'id_candidat'               => $parameters['idCandidat'],
                'categorie_logiciel'        => $parameters['categorieLogiciel'], 
                'nom_logiciel'              => $parameters['nomLogiciel'], 
                'date_de_sortie'            => date('Y-m-d', strtotime($parameters['dateDeSortie'])), 
                'fonctionnalite_logiciel'   => $parameters['fonctionnaliteLogiciel'],
                'photo_logiciel'            => $parameters['photoLogiciel'],
                'maitrise_logiciel'         => $parameters['maitriseLogiciel'] 
            ]);
            if (is_object($result)) {
                $_SESSION['info']['success'] = "Ajout avec succès";
            }
            else{
                $_SESSION['info']['warning'] = "On ne peut pas encore ajouter ce logiciel";
            }
        }

        /** 
         * Supprimer une langue
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerLangue($parameters)
        {
            $manager = new ManagerLangue();
            $manager->supprimer(['id_langue' => $parameters['idLangue']]);
        }

        /** 
         * Supprimer un logiciel
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimerLogiciel($parameters)
        {
            $manager = new ManagerLogiciel();
            $manager->supprimer(['id_logiciel' => $parameters['idLogiciel']]);
        }

        public function supprimerExperience($parameters)
        {
            $manager = new ManagerExperience();
            $manager->supprimer(['idExperience' => $parameters['idExperience']]);
        }

        public function supprimerFormation($parameters)
        {
            $manager = new ManagerFormation();
            $manager->supprimer(['idFormation' => $parameters['idFormation']]);
        }

        public function supprimerCentreInteret($parameters)
        {
            $manager = new ManagerCentreInteret();
            $manager->supprimer(['id_centre_interet' => $parameters['idCentreInteret']]);
        }

        public function modifierPersonnalite($autrePersonnalite)
        {
            $qualites = explode("_", $autrePersonnalite);
            foreach ($qualites as $qualite) {
                if (!empty($qualite)) {
                    $manager    = new ManagerPersonnalite();
                    $existe     = $manager->chercher(['qualite' => $qualite]);
                    if (empty($existe)) {
                        $manager->ajouter(['qualite' => ucfirst($qualite)]);
                    } 
                }
            }
            
        }

        /** 
         * Mettre à jour une langue
         * 
         * @author Billy Bam
         * 
         * @param array
         * 
         * @return empty
         * 
        */
        public function modifierLangue($parameters)
        {
            $manager    = new ManagerLangue();
            $result     = $manager->modifier([
                'id_langue'     => $parameters['idLangue'],
                'id_candidat'   => $parameters['idCandidat'],
                'nom_langue'    => $parameters['nomLangue'],
                'niveau_ecrit'  => $parameters['niveauEcrit'],
                'niveau_parle'  => $parameters['niveauParle']
            ]);
            if (is_object($result)) {
                $_SESSION['info']['success'] = "Modification avec succès";
            }
            else{
                $_SESSION['info']['warning'] = "On ne peut pas encore modifier cette langue";
            }
        }

        /** 
         * Mettre à jour une experience
         * 
         * @author Billy Bam
         * 
         * @param array
         * 
         * @return empty
         * 
        */
        public function modifierExperience($parameters)
        {
            $manager    = new ManagerExperience();
            $result     = $manager->modifier([
                'idExperience'  => $parameters['idExperience'],
                'idCandidat'    => $parameters['idCandidat'],
                'dateDebut'     => date('Y-m-d', strtotime($parameters['dateDebut'])),
                'dateFin'       => date('Y-m-d', strtotime($parameters['dateFin'])),
                'poste'         => $parameters['poste'],
                'entreprise'    => $parameters['entreprise'],
                'description'   => $parameters['description']
            ]);
            if (is_object($result)) {
                $_SESSION['info']['success'] = "Modification avec succès";
            }
            else{
                $_SESSION['info']['warning'] = "On ne peut pas encore modifier cette experience";
            } 
        }

        public function modifierFormation($parameters)
        {
            $parameters     = $this->verifierParamsSousDomaine($parameters);
            $manager        = new ManagerFormation();
            $result         = $manager->modifier([
                'idFormation'   => $parameters['idFormation'],
                'idCandidat'    => $parameters['idCandidat'],
                'idSousDomaine' => $parameters['idSousDomaine'],
                'idNiveauEtude' => $parameters['idNiveauEtude'],
                'dateDebut'     => date('Y-m-d', strtotime($parameters['dateDebut'])),
                'dateFin'       => date('Y-m-d', strtotime($parameters['dateFin'])),
                'etablissement' => $parameters['etablissement'],
                'description'   => $parameters['description']
            ]);
            if (is_object($result)) {
                $_SESSION['info']['success'] = "Ajout avec succès";
            }
            else{
                $_SESSION['info']['warning'] = "On ne peut pas encore ajouter cette experience";
            }
        }

        /** 
         * Mettre à jour un centre d'interet
         * 
         * @author Billy Bam
         * 
         * @param array
         * 
         * @return empty
         * 
        */
        public function modifierCentreInteret($parameters)
        {
            $manager    = new ManagerCentreInteret();
            $result     = $manager->modifier([
                'id_centre_interet'             => $parameters['idCentreInteret'],
                'id_candidat'                   => $parameters['idCandidat'],
                'categorie_centre_interet'      => $parameters['categorieCentreInteret'],
                'description_centre_interet'    => $parameters['descriptionCentreInteret']
            ]);
            if (is_object($result)) {
                $_SESSION['info']['success'] = "Modification avec succès";
            }
            else{
                $_SESSION['info']['warning'] = "On ne peut pas encore modifier cette langue";
            }
        }
        
        /** 
         * Mettre à jour un logiciel
         * 
         * @author Billy Bam
         * 
         * @param array
         * 
         * @return empty
         * 
        */
        public function modifierLogiciel($parameters)
        {
            $manager    = new ManagerLogiciel(); 
            if (isset($parameters['photoLogiciel'])) {
                $result = $manager->modifier([
                    'id_logiciel'               => $parameters['idLogiciel'], 
                    'id_candidat'               => $parameters['idCandidat'],
                    'categorie_logiciel'        => $parameters['categorieLogiciel'], 
                    'nom_logiciel'              => $parameters['nomLogiciel'], 
                    'date_de_sortie'            => date('Y-m-d', strtotime($parameters['dateDeSortie'])), 
                    'fonctionnalite_logiciel'   => $parameters['fonctionnaliteLogiciel'],
                    'photo_logiciel'            => $parameters['photoLogiciel'],
                    'maitrise_logiciel'         => $parameters['maitriseLogiciel'] 
                ]);  
            } else {
                $result = $manager->modifier([
                    'id_logiciel'               => $parameters['idLogiciel'], 
                    'id_candidat'               => $parameters['idCandidat'],
                    'categorie_logiciel'        => $parameters['categorieLogiciel'], 
                    'nom_logiciel'              => $parameters['nomLogiciel'], 
                    'date_de_sortie'            => date('Y-m-d', strtotime($parameters['dateDeSortie'])), 
                    'fonctionnalite_logiciel'   => $parameters['fonctionnaliteLogiciel'],
                    'maitrise_logiciel'         => $parameters['maitriseLogiciel'] 
                ]);
            }
            if (is_object($result)) {
                $_SESSION['info']['success'] = "Modification avec succès";
            }
            else{
                $_SESSION['info']['warning'] = "On ne peut pas encore modifier ce logiciel";
            }
        }

    }
