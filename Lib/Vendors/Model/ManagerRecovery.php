<?php
    
    /**
     * Manager de l'entité Recovery
     *
     * @author Lansky
     *
     * @since 11/10/2022
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Recovery;

    class ManagerRecovery extends DbManager
    {
        /** 
         * Lister les recoveries
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs = null)
        {
            $recoverys   = array();
            $orderByQuery = " ORDER BY idRecovery ASC";
            $resultats = $this->findAll('recovery', $attributs, $orderByQuery);
            foreach ($resultats as $resultat) {
                $recovery   = new Recovery($resultat);
                $recoverys[] = $recovery;
            }
            return $recoverys;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Recovery();
        }

        /** 
         * Chercher une recovery
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields    = array();
            $values    = array();
            $recovery     = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('recovery', $fields, $values);
            if (!empty($resultat)) {
                $recovery = new Recovery($resultat);
            }
            return $recovery;
        }

        /**
         * Ajouter une recovery
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idRecovery'] = $this->insert('recovery', $parameters);
            return new Recovery($parameters);
        }

        /**
         * Modifier une recovery
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('recovery', $parameters);
            return new Recovery($parameters);
        }

        /**
         * Supprimer une recovery
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('recovery', $parameters);
        }
        
    }