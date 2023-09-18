<?php
    
    /**
     * Manager de l'entité Conge
     *
     * @author Toky
     *
     * @since 20/08/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Conge;

    class ManagerConge extends DbManager
    {
        /** 
         * Lister les Conges
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $conges  = array();
            $queryOrderBy  = " ORDER BY idConge DESC";
            $resultats     = $this->findAll('conge', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $conge    = new Conge($resultat);
                $conges[] = $conge;
            }
            return $conges;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Conge();
        }

        /** 
         * Chercher un Congé
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $conge = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('conge', $fields, $values);
            if (!empty($resultat)) {
                $conge = new Conge($resultat);
            }
            return $conge;
        }

        /**
         * Selectionner des Conges dans un intervalle
         *
         * @param array @attributes critère avec égalité
         * @param array @min        intervalle minimum
         * @param array @max        interval maximum
         *
         * @return array
         */
        public function selectionner($attributes=null, $min, $max)
        {
            $conges     = array();
            $orderByQuery = " ORDER BY idConge DESC";
            $resultats = $this->selectAll('conge', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $conge    = new Conge($resultat);
                $conges[] = $conge;
            }
            return $conges;
        }

        /**
         * Ajouter un Conge
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idConge'] = $this->insert('conge', $parameters);
            return new Conge($parameters);
        }

        /**
         * Modifier un Conge
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('conge', $parameters);
            return new Conge($parameters);
        }

        /**
         * Supprimer un Conge
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('conge', $parameters);
        }
        
    }