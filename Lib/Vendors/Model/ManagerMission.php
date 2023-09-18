<?php
    
    /**
     * Manager de l'entité Mission
     *
     * @author Voahirana
     *
     * @since 02/04/19 
     */

    namespace Model;

	use \Core\DbManager;
    use \Entity\Mission;

	class ManagerMission extends DbManager
	{
        /**
         * Lister les missions
         *
         * @param array $attributes Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes) 
        {
            $missions  = array();
            $resultats = $this->findAll('mission', $attributes, null);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $mission    = new Mission($resultat);
                    $missions[] = $mission; 
                }
            } 
            return $missions;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Mission();
        }

        /** 
         * Chercher une mission
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields  = array();
            $values  = array();
            $mission = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('mission', $fields, $values);
            if (!empty($resultat)) {
                $mission = new Mission($resultat);
            }
            return $mission;
        }

        /** 
         * Chercher le dérnier identifiant d'une mission
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('mission', 'idMission');
        }

        /**
         * Insérer une mission
         *
         * @param array $parameters Les données à insérer
         *
         * @return object
         */
        public function ajouter($parameters) 
        { 
            $this->insert('mission', $parameters);
            return new Mission($parameters);
        }

        /**
         * Modifier une mission
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('mission', $parameters);
            return new Mission($parameters);
        }

        /**
         * Supprimer une mission
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('mission', $parameters);
        }

        /**
         * Supprimer une mission
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function getUserMissions($params)
        {
            $missions   = array();
            $query      = array_key_exists('idEmploye', $params) ? 'SELECT DISTINCT (m.idMission), m.idEntreprisePoste, m.description, m.niveau 
                                                                    FROM contrat_employe AS ce
                                                                    INNER JOIN service_poste AS sp on ce.idEmploye = ' . $params['idEmploye'] . ' AND ce.idServicePoste = sp.idServicePoste AND ce.suivant = 0
                                                                    INNER JOIN entreprise_poste AS ep on ep.idEntreprisePoste = sp.idEntreprisePoste
                                                                    INNER JOIN mission AS m on m.idEntreprisePoste = ep.idEntreprisePoste' :
                                                                    "SELECT DISTINCT (m.idMission), m.idEntreprisePoste, m.description, m.niveau
                                                                     FROM service_poste as sp
                                                                     INNER JOIN  mission as m
                                                                     ON sp.idEntreprisePoste = m.idEntreprisePoste AND sp.idEntrepriseService = " . $params['service'];
            $requete    = $this->pdo()->prepare($query);
            $requete->execute();
            $response   = $requete->fetchAll();
            if (!empty($response)) {
                foreach ($response as $resultat) {
                    $mission    = new Mission($resultat);
                    $missions[] = $mission; 
                }
            }
            return $missions;
        }
    }