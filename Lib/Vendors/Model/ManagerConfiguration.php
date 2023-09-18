<?php
    
    /**
     * Manager de l'entité Configuration
     *
     * @author Toky
     *
     * @since 24/06/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Configuration;

    class ManagerConfiguration extends DbManager
    {
        /** 
         * Lister les Configurations
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister()
        {
            $configurations  = array();
            $queryOrderBy   = " ORDER BY idConfiguration ASC";
            $resultats      = $this->findAll('configuration', null, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $configuration    = new Configuration($resultat);
                $configurations[] = $configuration;
            }
            return $configurations;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Configuration();
        }

        /** 
         * Chercher une configuration
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields        = array();
            $values        = array();
            $configuration = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('configuration', $fields, $values);
            if (!empty($resultat)) {
                $configuration = new Configuration($resultat);
            }
            return $configuration;
        }

        /**
         * Ajouter une configuration
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idConfiguration'] = $this->insert('configuration', $parameters);
            return new Configuration($parameters);
        }

        /**
         * Modifier une configuration
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('configuration', $parameters);
            return new Configuration($parameters);
        }

        /**
         * Supprimer une configuration
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('configuration', $parameters);
        }
        
    }