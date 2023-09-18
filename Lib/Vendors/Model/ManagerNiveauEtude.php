<?php
    
    /**
     * Manager de l'entité Niveau d'étude
     * @author Voahirana
     * @since 14/10/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\NiveauEtude;

    class ManagerNiveauEtude extends DbManager
    {
       /**
         * Lister les niveaux d'études
         *
         * @return array
         */
        public function lister() 
        {
            $niveauEtudes = array();
            $string       = " ORDER BY ordre ASC";
            $resultats    = $this->findAll('niveau_etude', null, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $niveauEtude    = new NiveauEtude($resultat);
                    $niveauEtudes[] = $niveauEtude;
                }
            }            
            return $niveauEtudes;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new NiveauEtude();
        }

        /** 
         * Chercher un niveau d'étude
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields      = array();
            $values      = array();
            $niveauEtude = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            } 
            $resultat = $this->findOne('niveau_etude', $fields, $values);
            if (!empty($resultat)) {
                $niveauEtude = new NiveauEtude($resultat);
            }
            return $niveauEtude;
        }

        /** 
         * Chercher le dérnier identifiant d'un niveau d'étude
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('niveau_etude', 'idNiveauEtude');
        }

        /**
         * Ajouter un niveau d'étude
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idNiveauEtude'] = $this->insert('niveau_etude', $parameters);
            return new NiveauEtude($parameters);
        }

        /**
         * Modifier un niveau d'étude
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('niveau_etude', $parameters);
            return new NiveauEtude($parameters);
        }

        /**
         * Supprimer un niveau d'étude
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('niveau_etude', $parameters);
        }
    }