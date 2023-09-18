<?php
    
    /**
     * Manager de l'entité Presence
     *
     * @author Toky
     *
     * @since 16/07/2020
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Presence;

    class ManagerPresence extends DbManager
    {
        /**
         * Lister les présences
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes = null)
        {
            $presences   = array();
            $orderByQuery = " ORDER BY date ASC";
            $resultats = $this->findAll('presence', $attributes, $orderByQuery);
            foreach ($resultats as $resultat) {
                $presence   = new Presence($resultat);
                $presences[] = $presence;
            }
            return $presences;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Presence();
        }

        /** 
         * Chercher une presence
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields    = array();
            $values    = array();
            $presence = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('presence', $fields, $values);
            if (!empty($resultat)) {
                $presence = new Presence($resultat);
            }
            return $presence;
        }

        /**
         * Selectionner les présences dans un intervalle
         *
         * @param array @attributes critère avec égalité
         * @param array @min        intervalle minimum
         * @param array @max        interval maximum
         *
         * @return array
         */
        public function selectionner($attributes=null, $min, $max)
        {
            $presences   = array();
            $orderByQuery = " ORDER BY date ASC";
            $resultats = $this->selectAll('presence', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $presence   = new Presence($resultat);
                $presences[] = $presence;
            }
            return $presences;
        }

        /**
         * Ajouter une Présence
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $this->insert('presence', $parameters);
        }

        /**
         * Modifier une présence
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('presence', $parameters);
            return new Presence($parameters);
        }

        /**
         * Supprimer une présence
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('presence', $parameters);
        }
        
    }