<?php
    
    /**
     * Manager de l'entité Candidat
     *
     * @author Voahirana
     *
     * @since 01/10/19 
    */

    namespace Model;

	use \Core\DbManager;
    use \Entity\Candidat;

	class ManagerCandidat extends DbManager
	{
        /**
         * Lister les candidats
         *
         * @return array
        */
        public function lister() 
        {
            $candidats = array();
            $string    = " ORDER BY nom ASC";
            $resultats = $this->findAll('candidat', null, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $candidat    = new Candidat($resultat);
                    $candidats[] = $candidat; 
                }
            } 
            return $candidats;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
        */
        public function initialiser()
        {
            return new Candidat();
        }

        /** 
         * Chercher un Candidat
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
        */
        public function chercher($parameters)
        {
            $fields   = array();
            $values   = array();
            $candidat = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('candidat', $fields, $values);
            if (!empty($resultat)) {
                $candidat = new Candidat($resultat);
            }
            return $candidat;
        }

        /** 
         * Chercher le dérnier identifiant d'un candidat
         *
         * @return array
        */
        public function chercherDernierId()
        {            
            return $this->findLast('candidat', 'idCandidat');
        } 

        /**
         * Insérer un candidat
         *
         * @param array $parameters Les données à insérer
         *
         * @return object
        */
        public function ajouter($parameters) 
        {
            $parameters['idCandidat'] = $this->insert('candidat', $parameters);
            return new Candidat($parameters);
        }

        /**
         * Modifier un candidat
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
        */
        public function modifier($parameters) 
        {
            $this->update('candidat', $parameters);
            return new Candidat($parameters);
        }

        /** 
         * Recupérer les personnalités d'un candidat
         *
         * @param array $parameters Le candidat concerné
         * 
         * @return array 
        */
        public function getPersonnalitesCandidat($parameters)
        {
            $resultats = array();
            $candidat = $this->chercher($parameters);
            if (!empty($candidat)) {
                $resultats = explode('_', $candidat->getPersonnalite());
                array_pop($resultats);
            }            
            return $resultats;
        }

        /** 
         * Recupérer les expériences d'un candidat
         *
         * @param array $parameters Le candidat concerné
         * 
         * @return array
        */
        private function getExperienceCandidat($parameters)
        {
            $resultats = array();
            /*$query     = "SELECT sum(DATEDIFF(experience.dateFin, experience.dateDebut)) AS day, 
                                 sous_domaine.nomSousDomaine as sousDomaine 
                          FROM experience 
                          JOIN sous_domaine ON experience.idSousDomaine = sous_domaine.idSousDomaine 
                          WHERE experience.idCandidat = " . $parameters['idCandidat'] . " 
                          GROUP BY sousDomaine";
            $requete   = $this->pdo()->prepare($query);
            $requete->execute();
            $responses = $requete->fetchAll();
            if (!empty($responses)) {
                foreach ($responses as $response) {
                    extract($response);
                    // Convertir la variable day en année
                    $year = (int)$day/30/12;
                    $resultats[] = [
                        'annee'       => round($year), 
                        'sousDomaine' => $sousDomaine
                    ];
                }
            }*/
            $query     = "SELECT experience.poste as poste, experience.dateFin as dateFin, experience.dateDebut as dateDebut  
                          FROM experience 
                          WHERE experience.idCandidat = " . $parameters['idCandidat'] . " 
                          GROUP BY poste";
            $requete   = $this->pdo()->prepare($query);
            $requete->execute();
            $responses = $requete->fetchAll();
            if (!empty($responses)) {
                foreach ($responses as $response) {
                    extract($response);
                    // Convertir la variable day en année
                    // $year = (int)$day/30/12;
                    if ($dateFin == "1970-01-01" ){
                        $date1 = strtotime(date('Y-m-d')); 
                        $date2 = strtotime($dateDebut);
                        $diff  = $date1 - $date2;
                        $annee = floor($diff / (365 * 24 * 60 * 60));
                    } else {
                        $date1 = strtotime($dateFin); 
                        $date2 = strtotime($dateDebut);
                        $diff  = $date1 - $date2;
                        $annee = floor($diff / (365 * 24 * 60 * 60));
                    }
                    $resultats[] = [
                        'annee'         => $annee, 
                        'poste'         => $poste
                    ];
                }
            }
            return $resultats;            
        }

        /** 
         * Recupérer les niveaux d'études d'un candidat
         *
         * @param array $parameters Le candidat concerné
         * 
         * @return array 
        */
        private function getNiveauEtudeCandidat($parameters)
        {
            $resultats = array();
            /*$query     = "SELECT max(niveau_etude.ordre) as ordre, 
                                 domaine.nomDomaine as domaine 
                          FROM diplome 
                          JOIN domaine ON diplome.idDomaine = domaine.idDomaine 
                          JOIN niveau_etude ON diplome.idNiveauEtude = niveau_etude.idNiveauEtude 
                          WHERE diplome.idCandidat = " . $parameters['idCandidat'] . " 
                          GROUP BY domaine 
                          ORDER BY ordre";*/
            $query     = "SELECT MAX(niveau_etude.ordre) AS ordre, domaine.nomDomaine AS domaine 
                          FROM formation
                          JOIN niveau_etude ON formation.idNiveauEtude = niveau_etude.idNiveauEtude
                          JOIN sous_domaine ON formation.idSousDomaine = sous_domaine.idSousDomaine
                          JOIN domaine ON sous_domaine.idDomaine = domaine.idDomaine 
                          WHERE formation.idCandidat = " . $parameters['idCandidat'] . " 
                          GROUP BY domaine 
                          ORDER BY ordre";
            $requete   = $this->pdo()->prepare($query);
            $requete->execute();
            $responses = $requete->fetchAll();
            if (!empty($responses)) {
                foreach ($responses as $response) {
                    extract($response);
                    $query   = "SELECT niveau FROM niveau_etude WHERE ordre = " . $ordre;
                    $requete = $this->pdo()->prepare($query);
                    $requete->execute();
                    $result  = $requete->fetch();
                    extract($result);
                    $resultats[] = [
                        'domaine' => $domaine, 
                        'niveau'  => (int)$ordre, 
                        'libelle' => $niveau
                    ];
                }
            }
            return $resultats;
        }

        /**
         * Desc: Spécifier le profil d'un candidat
         * 
         * @param array $parameters Le candidat concerné
         * 
         * @return array 
        */
        public function specifierProfilCandidat($parameters)
        {   
            $resultats = array();
            if (isset($parameters)) {
                $experiences = $this->getExperienceCandidat($parameters);
                $niveauxEtudes = $this->getNiveauEtudeCandidat($parameters);
                $resultats = [
                    'experiences'   => $experiences, 
                    'formations'    => $niveauxEtudes
                ];
            }
            return $resultats;
        }        
    }