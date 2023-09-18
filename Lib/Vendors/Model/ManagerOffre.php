<?php
    
    /**
     * Manager de l'entité Offre
     *
     * @author Voahirana
     *
     * @since 14/10/19 
    */

    namespace Model;

	use \Core\DbManager;
    use \Entity\Offre;

	class ManagerOffre extends DbManager
	{
        /**
         * Lister les offres
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
        */
        public function lister($parameters = null) 
        {
            $offres    = array();
            $string    = " ORDER BY dateEmission DESC";
            $resultats = $this->findAll('offre', $parameters, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $offre    = new Offre($resultat);
                    $offres[] = $offre;
                }
            } 
            return $offres;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
        */
        public function initialiser()
        {
            return new Offre();
        }

        /** 
         * Chercher une offre
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
        */
        public function chercher($parameters)
        {
            $fields  = array();
            $values  = array();
            $offre   = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('offre', $fields, $values);
            if (!empty($resultat)) {
                $offre = new Offre($resultat);
            }
            return $offre;
        }

        /**
         * Insérer une offre
         *
         * @param array $parameters Les données à insérer
         *
         * @return object
        */
        public function ajouter($parameters) 
        {
            $this->insert('offre', $parameters);
            return new Offre($parameters);
        }

        /**
         * Modifier une offre
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
        */
        public function modifier($parameters) 
        {
            $this->update('offre', $parameters);
            return new Offre($parameters);
        }

        /**
         * Supprimer une offre
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimer($parameters) 
        {
            return $this->delete('offre', $parameters);
        }

        /** 
         * Récupérer les personnalités d'une offre
         *
         * @param array $parameters L'offre concernée
         * 
         * @return array 
        */
        public function getPersonnalitesOffre($parameters)
        {
            $resultats = array();
            $offre     = $this->chercher($parameters);
            if (!empty($offre)) {
                $resultats = explode('_', $offre->getPersonnalite());
                array_pop($resultats);
            }
            return $resultats;
        }

        /** 
         * Récupérer l'expérience d'une offre
         *
         * @param array $parameters L'offre concernée
         * 
         * @return array 
        */
        private function getExperienceOffre($parameters)
        {
            $resultat = "";
            /*$query    = "SELECT niveau_experience.niveau as niveau, 
                                sous_domaine.nomSousDomaine as sousDomaine 
                         FROM offre 
                         JOIN niveau_experience ON offre.idNiveauExperience = niveau_experience.idNiveauExperience 
                         JOIN sous_domaine ON offre.idSousDomaine = sous_domaine.idSousDomaine 
                         WHERE offre.idOffre = " . $parameters['idOffre'];
            $requete  = $this->pdo()->prepare($query);
            $requete->execute();
            $response = $requete->fetch();
            if (!empty($response)) {
                extract($response);
                // le champ niveau correspond à "2 ans" par exemple
                $annee    = explode(' ', $niveau);
                $resultat = [
                    'annee'       => (int)$annee[0], 
                    'sousDomaine' => $sousDomaine
                ];
            }*/
            $query    = "SELECT niveau_experience.niveau as niveau, 
                                entreprise_poste.poste as poste 
                         FROM offre 
                         JOIN niveau_experience ON offre.idNiveauExperience = niveau_experience.idNiveauExperience 
                         JOIN entreprise_poste ON offre.idEntreprisePoste = entreprise_poste.idEntreprisePoste 
                         WHERE offre.idOffre = " . $parameters['idOffre'];
            $requete  = $this->pdo()->prepare($query);
            $requete->execute();
            $response = $requete->fetch();
            if (!empty($response)) {
                extract($response);
                // le champ niveau correspond à "2 ans" par exemple
                $annee    = explode(' ', $niveau);
                $resultat = [
                    'annee'         => (int)$annee[0], 
                    'poste'         => $poste
                ];
            }
            return $resultat;
        }

        /** 
         * Récupérer le niveau d'étude d'une offre
         *
         * @param array $parameters L'offre concernée
         * 
         * @return array 
        */
        private function getNiveauEtudeOffre($parameters)
        {
            $resultat = "";
            $query    = "SELECT domaine.nomDomaine as domaine, 
                                niveau_etude.ordre as ordre 
                         FROM offre 
                         JOIN sous_domaine ON offre.idSousDomaine = sous_domaine.idSousDomaine 
                         JOIN niveau_etude ON offre.idNiveauEtude = niveau_etude.idNiveauEtude 
                         JOIN domaine ON domaine.idDomaine = sous_domaine.idDomaine 
                         WHERE offre.idOffre = " . $parameters['idOffre'];
            $requete = $this->pdo()->prepare($query);
            $requete->execute();
            $response = $requete->fetch();
            if (!empty($response)) {
                extract($response);
                $resultat = [
                    'domaine' => $domaine, 
                    'niveau'  => (int)$ordre
                ];
            }
            return $resultat;
        }

        /**
         * Spécifier le profil d'une offre
         * 
         * @param array $parameters L'offre concernée
         * 
         * @return array 
        */
        public function specifierProfilOffre($parameters)
        {   
            $resultats = array();
            if (isset($parameters)) {
                $experience     = $this->getExperienceOffre($parameters);
                $formation      = $this->getNiveauEtudeOffre($parameters);
                $resultats      = [
                    'experience'    => $experience, 
                    'formation'     => $formation
                ];
            } 
            return $resultats;
        }

        /**
         * Récupérer toutes les données statistiques des offres d'une entreprise 
         * 
         * @param array $parameters Le critères des données à récupérer
         * 
         * @return array 
        */
        public function recupererStatistique($parameters)
        {   
            $mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            $data       = array();
            $dataPoints = array();
            $addQuery   = '';
            if (isset($parameters['month'])) {
                $month = explode(' ', $parameters['month']);
                if (in_array($month[0], $mois, false)) {
                    $addQuery = " AND MONTH(offre.dateEmission) = " . (string)(array_search($month[0], $mois) + 1) . " AND YEAR(offre.dateEmission) = " . $month[1];
                }
            }
            if (isset($parameters['year'])) {
                $addQuery = " AND YEAR(offre.dateEmission) = " . $parameters['year'];
            }
            if (isset($parameters['date1']) && isset($parameters['date2'])) {
                $addQuery = " AND offre.dateEmission BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', $parameters['date1']))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', $parameters['date2']))) . "'";
            }
            /*$query      = "SELECT offre.idOffre AS idOffre, 
                                 offre.poste AS poste, 
                                 count(candidature.idCandidature) AS nbTotalCandidature 
                          FROM offre 
                          JOIN candidature ON offre.idOffre = candidature.idOffre 
                          WHERE offre.idEntreprise = ". $_SESSION['user']['idEntreprise'] . $addQuery . " 
                          GROUP BY poste
                          ORDER BY poste ASC";
            $requete   = $this->pdo()->prepare($query);
            $requete->execute();
            $responses = $requete->fetchAll();
            foreach (unserialize(STATUT_OFFRE) as $statut) {
                if (!empty($responses)) {
                    foreach ($responses as $response) {
                        extract($response);
                        $query   = "SELECT count(idCandidature) AS nbParStatut 
                                    FROM candidature 
                                    WHERE idOffre = " . $idOffre . " AND statut = '" . $statut . "'";
                        $requete = $this->pdo()->prepare($query);
                        $requete->execute(); 
                        $candidature = $requete->fetch();
                        if (!empty($candidature)) {
                            extract($candidature);
                            $indexLabel = round((int)$nbParStatut / ((int)$nbTotalCandidature / 100),2);
                            if ($indexLabel > 0) {
                                $indexLabel .= "%";
                            } else {
                                $indexLabel = "";
                            }
                            $dataPoints[] = [
                                "y"          => (int)$nbParStatut,
                                "label"      => ucfirst($poste),
                                "indexLabel" => $indexLabel
                            ];
                        }
                    }
                }
                $data[] = [
                    "name"       => $statut,
                    "dataPoints" => $dataPoints
                ];
                $dataPoints = array();
            }*/
            $query     = "SELECT offre.idOffre AS idOffre, 
                                 entreprise_poste.poste AS poste, 
                                 count(candidature.idCandidature) AS nbTotalCandidature 
                          FROM offre 
                          JOIN candidature ON offre.idOffre = candidature.idOffre
                          JOIN entreprise ON offre.idEntreprise = entreprise.idEntreprise
                          JOIN entreprise_poste ON entreprise_poste.idEntreprise = entreprise.idEntreprise 
                          WHERE offre.idEntreprise = ". $_SESSION['user']['idEntreprise'] . $addQuery . " 
                          GROUP BY poste
                          ORDER BY poste ASC";
            $requete   = $this->pdo()->prepare($query);
            $requete->execute();
            $responses = $requete->fetchAll();              
            foreach (unserialize(STATUT_OFFRE) as $statut) {
                if (!empty($responses)) {
                    foreach ($responses as $response) {
                        extract($response);
                        $query   = "SELECT count(idCandidature) AS nbParStatut 
                                    FROM candidature 
                                    JOIN offre ON candidature.idOffre = offre.idOffre 
                                    WHERE offre.idOffre = " . $idOffre . "  AND statut = '" . $statut . "'";
                        $requete = $this->pdo()->prepare($query);
                        $requete->execute(); 
                        $candidature = $requete->fetch();
                        if (!empty($candidature)) {
                            extract($candidature);
                            $indexLabel = round((int)$nbParStatut * 100 / ((int)$nbTotalCandidature),2);
                            if ($indexLabel > 0) {
                                $indexLabel .= "%";
                            } else {
                                $indexLabel = "";
                            }
                            
                            $found = false;
                            foreach ($dataPoints as &$dataPoint) {
                                if ($dataPoint["label"] == ucfirst($poste)) {
                                    $dataPoint["y"] += (int)$nbParStatut;
                                    $found = true;
                                    break;
                                }
                            }
                            if (!$found) {
                                $dataPoints[] = [
                                    "y"          => (int)$nbParStatut,
                                    "label"      => ucfirst($poste),
                                    "indexLabel" => $indexLabel
                                ];
                            }
                        }
                    }
                }
                $data[] = [
                    "name"       => $statut,
                    "dataPoints" => $dataPoints
                ];
                $dataPoints = [];
            }
            return $data;           
        }

        /** 
         * Vérifier si le poste est déjà engagé
         *
         * @param array $parameters L'offre concernée
         * 
         * @return array 
        */
        public function getEngagedPoste($parameters)
        {
            $query    = "SELECT entretien.statut AS statut
                         FROM entretien 
                         JOIN candidature ON entretien.idCandidature = candidature.idCandidature 
                         JOIN offre ON candidature.idOffre = offre.idOffre 
                         WHERE offre.idOffre = " . $parameters['idOffre'] .
                         " AND entretien.statut = 'valide'";
            $requete = $this->pdo()->prepare($query);
            $requete->execute();
            $response = $requete->fetch();
            return $response;
        }
	}