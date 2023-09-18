<?php
    
    /**
     * Manager de l'entité Interlocuteur
     *
     * @author Voahirana
     *
     * @since 27/11/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Interlocuteur;

    class ManagerInterlocuteur extends DbManager
    {
       /**
         * Lister les interlocuteurs
         *
         * @return array
         */
        public function lister($attributes) 
        {
            $interlocuteurs = array();
            $string         = " ORDER BY nom ASC";
            $resultats      = $this->findAll('interlocuteur', $attributes, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $interlocuteur    = new Interlocuteur($resultat);
                    $interlocuteurs[] = $interlocuteur;
                }
            }            
            return $interlocuteurs;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Interlocuteur();
        }

        /** 
         * Chercher un interlocuteur
         *
         * @param array $attributes Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($attributes)
        {
            $fields        = array();
            $values        = array();
            $interlocuteur = "";
            foreach ($attributes as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('interlocuteur', $fields, $values);
            if (!empty($resultat)) {
                $interlocuteur = new Interlocuteur($resultat);
            }
            return $interlocuteur;
        }

        /** 
         * Chercher le dérnier identifiant d'un interlocuteur
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('interlocuteur', 'idInterlocuteur');
        }

        /**
         * Ajouter un interlocuteur
         *
         * @param array $attributes Les données à ajouter
         *
         * @return object
         */
        public function ajouter($attributes) 
        {
            return $this->insert('interlocuteur', $attributes);
        }

        /**
         * Modifier un interlocuteur
         *
         * @param array $attributes Les données à modifier
         *
         * @return object
         */
        public function modifier($attributes) 
        {
            $this->update('interlocuteur', $attributes);
            return new Interlocuteur($attributes);
        }

        /**
         * Supprimer un interlocuteur
         *
         * @param array $attributes Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($attributes) 
        {
            return $this->delete('interlocuteur', $attributes);
        }

        /**
         * Récupérer toutes les données statistiques des entretiens d'une entreprise 
         * 
         * @param array $attributes Le critères des données à récupérer
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
                    $addQuery = " AND MONTH(entretien.date) = " . (string)(array_search($month[0], $mois) + 1) . " AND YEAR(entretien.date) = " . $month[1];
                }
            }
            if (isset($parameters['year'])) {
                $addQuery = " AND YEAR(entretien.date) = " . $parameters['year'];
            }
            if (isset($parameters['date1']) && isset($parameters['date2'])) {
                $addQuery = " AND entretien.date BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', $parameters['date1']))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', $parameters['date2']))) . "'";
            }  
            $query      = "SELECT COUNT(entretien.idEntretien) AS nbEntretien, 
                                  interlocuteur.nom AS interlocuteur,
                                  interlocuteur.civilite AS civilite,
                                  interlocuteur.idInterlocuteur AS idInterlocuteur
                           FROM interlocuteur 
                           JOIN interlocuteur_niveau_entretien ON interlocuteur.idInterlocuteur = interlocuteur_niveau_entretien.idInterlocuteur 
                           JOIN niveau_entretien ON interlocuteur_niveau_entretien.idNiveauEntretien = niveau_entretien.idNiveauEntretien 
                           JOIN entretien ON entretien.idNiveauEntretien = niveau_entretien.idNiveauEntretien 
                           WHERE niveau_entretien.idNiveauEntretien IN (SELECT idNiveauEntretien FROM niveau_entretien WHERE idEntreprise = " . $_SESSION['user']['idEntreprise'] . ")" . $addQuery . "
                           GROUP BY interlocuteur"; 
            $requete   = $this->pdo()->prepare($query);
            $requete->execute();
            $responses = $requete->fetchAll();
            foreach (unserialize(STATUT_ENTRETIEN) as $statut) {
                if (!empty($responses)) {
                    foreach ($responses as $response) {
                        extract($response);
                        $query = " SELECT COUNT(entretien.idEntretien) AS nbParstatut
                                   FROM entretien 
                                   JOIN niveau_entretien ON entretien.idNiveauEntretien = niveau_entretien.idNiveauEntretien 
                                   JOIN interlocuteur_niveau_entretien ON interlocuteur_niveau_entretien.idNiveauEntretien = niveau_entretien.idNiveauEntretien 
                                   JOIN interlocuteur ON interlocuteur_niveau_entretien.idInterlocuteur = interlocuteur.idInterlocuteur 
                                   WHERE interlocuteur.idInterlocuteur = " . $idInterlocuteur . $addQuery . " 
                                   AND entretien.statut = '" . $statut . "'";
                        $requete = $this->pdo()->prepare($query);
                        $requete->execute(); 
                        $entretien = $requete->fetch();
                        if (!empty($entretien)) {
                            extract($entretien);                            
                            $indexLabel = round((int)$nbParstatut / ((int)$nbEntretien / 100),2);
                            if ($indexLabel > 0) {
                                $indexLabel .= "%";
                            } else {
                                $indexLabel = "";
                            }
                            $dataPoints[] = [
                                "y"          => (int)$nbParstatut,
                                "label"      => ucfirst($civilite) . " " . ucwords($interlocuteur),
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