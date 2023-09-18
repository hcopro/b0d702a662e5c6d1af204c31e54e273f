<?php
    
    /**
     * Manager de l'entité Entretien
     *
     * @author Voahirana
     *
     * @since 22/10/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Entretien;

    class ManagerEntretien extends DbManager
    {
        /**
         * Lister les entretiens
         *
         * @param array $attributes Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes = null) 
        {
            $entretiens = array();
            $string     = " ORDER BY date DESC";
            $resultats  = $this->findAll('entretien', $attributes, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $entretien    = new Entretien($resultat);
                    $entretiens[] = $entretien; 
                }
            } 
            return $entretiens;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Entretien();
        }

        /** 
         * Chercher un entretien
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields    = array();
            $values    = array();
            $entretien = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('entretien', $fields, $values);
            if (!empty($resultat)) {
                $entretien = new Entretien($resultat);
            }
            return $entretien;
        }

        /** 
         * Chercher le dérnier identifiant d'un entretien
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('entretien', 'idEntretien');
        }

        /**
         * Insérer une entretien
         *
         * @param array $parameters Les données à insérer
         *
         * @return object
         */
        public function ajouter($parameters) 
        { 
            $parameters['idEntretien'] = $this->insert('entretien', $parameters);
            return new Entretien($parameters);
        }

        /**
         * Modifier une entretien
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('entretien', $parameters);
            return new Entretien($parameters);
        }

        /**
         * Lister les entretiens d'une offre
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function listerEntretiensOffre($parameters) 
        {
            $resultats = array();
            $query     = "SELECT * FROM entretien 
                          JOIN candidature ON entretien.idCandidature = candidature.idCandidature 
                          JOIN offre ON candidature.idOffre = offre.idOffre 
                          WHERE offre.idOffre = " . $parameters['idOffre'];
            $requete   = $this->pdo()->prepare($query);
            $requete->execute();
            $response = $requete->fetchAll();
            if (!empty($response)) {
                $resultats = $response;
            }
            return $resultats;
        }
        
        /**
         * Récupérer toutes les données statistiques des entretiens d'une entreprise 
         * 
         * @param array $parameters Le critères des données à récupérer
         * 
         * @return array 
         */
        public function recupererStatistique($parameters)
        {   
            $mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            $data  = array(); 
            $dataPoints = array();
            $addQuery   = '';
            if (isset($parameters['month'])) {
                $month = explode(' ', $parameters['month']);
                if (in_array($month[0], $mois, false)) {
                    $addQuery = " AND MONTH(entretien.date) = " . (string)(array_search($month[0], $mois) + 1) . " AND YEAR(entretien.date) = " . $month[1];
                }
            }
            if (isset($parameters['year'])) {
                $addQuery = " AND YEAR(entretien.date) = " . $parameters['year'];
            }
            if (isset($parameters['date1']) && isset($parameters['date2'])) {
                $addQuery = " AND entretien.date BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', $parameters['date1']))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', $parameters['date2']))) . "'";
            }          
            $query = "SELECT offre.idOffre AS idOffre, 
                             offre.poste AS poste, 
                             count(entretien.idEntretien) AS nbTotalEntretien 
                      FROM offre 
                      JOIN candidature ON offre.idOffre = candidature.idOffre 
                      JOIN entretien ON entretien.idCandidature = candidature.idCandidature 
                      WHERE offre.idEntreprise = " . $_SESSION['user']['idEntreprise'] . $addQuery . "
                      GROUP BY poste
                      ORDER BY poste ASC";
            $requete = $this->pdo()->prepare($query);
            $requete->execute();
            $offres = $requete->fetchAll();
            foreach (unserialize(STATUT_ENTRETIEN) as $statut) {
                if (!empty($offres)) {
                    foreach ($offres as $offre) {
                        extract($offre);
                        $query = "SELECT count(entretien.idEntretien) as nbParStatut 
                                  FROM entretien 
                                  JOIN candidature ON entretien.idCandidature = candidature.idCandidature 
                                  WHERE candidature.idCandidature IN (SELECT candidature.idCandidature FROM candidature WHERE candidature.idOffre = " .  $idOffre . ")" . $addQuery . "
                                  AND entretien.statut = '" . $statut . "'";
                        $requete = $this->pdo()->prepare($query);
                        $requete->execute(); 
                        $entretien = $requete->fetch();
                        if (!empty($entretien)) {
                            extract($entretien);                            
                            $indexLabel = round((int)$nbParStatut / ((int)$nbTotalEntretien / 100),2);
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
            }
            return $data;
        }
    }