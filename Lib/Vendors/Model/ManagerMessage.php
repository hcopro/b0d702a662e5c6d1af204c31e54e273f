<?php
    
    /**
     * Manager de l'entité Message
     *
     * @author Toky
     *
     * @since 27/07/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Message;

    class ManagerMessage extends DbManager
    {
        /** 
         * Lister les messages
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $messages  = array();
            $queryOrderBy  = " ORDER BY idMessage DESC";
            $resultats     = $this->findAll('message', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $message    = new Message($resultat);
                $messages[] = $message;
            }
            return $messages;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Message();
        }

        /** 
         * Chercher un message
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $message = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('message', $fields, $values);
            if (!empty($resultat)) {
                $message = new Message($resultat);
            }
            return $message;
        }

        /**
         * Selectionner des messages dans un intervalle
         *
         * @param array @attributes critère avec égalité
         * @param array @min        intervalle minimum
         * @param array @max        interval maximum
         *
         * @return array
         */
        public function selectionner($attributes=null, $min, $max)
        {
            $messages     = array();
            $orderByQuery = " ORDER BY idMessage DESC";
            $resultats = $this->selectAll('message', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $message    = new Message($resultat);
                $messages[] = $message;
            }
            return $messages;
        }

        /**
         * Ajouter un message
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idMessage'] = $this->insert('message', $parameters);
            return new Message($parameters);
        }

        /**
         * Modifier un message
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('message', $parameters);
            return new Message($parameters);
        }

        /**
         * Supprimer un message
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('message', $parameters);
        }
        
    }