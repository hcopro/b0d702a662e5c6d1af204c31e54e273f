<?php
    
    /**
     * Manager de l'entité ValidationConge
     *
     * @author Toky
     *
     * @since 21/08/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ValidationConge;

    class ManagerValidationConge extends DbManager
    {
        /** 
         * Lister les Validations de Conges
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $validationConges  = array();
            $queryOrderBy  = " ORDER BY idValidationConge DESC";
            $resultats     = $this->findAll('validation_conge', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $validationConge    = new ValidationConge($resultat);
                $validationConges[] = $validationConge;
            }
            return $validationConges;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new ValidationConge();
        }

        /** 
         * Chercher une validation de Congé
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $validationConge = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('validation_conge', $fields, $values);
            if (!empty($resultat)) {
                $validationConge = new ValidationConge($resultat);
            }
            return $validationConge;
        }

        /**
         * Selectionner des ValidationConges dans un intervalle
         *
         * @param array @attributes critère avec égalité
         * @param array @min        intervalle minimum
         * @param array @max        interval maximum
         *
         * @return array
         */
        public function selectionner($attributes=null, $min, $max)
        {
            $validationConges     = array();
            $orderByQuery = " ORDER BY idValidationConge DESC";
            $resultats = $this->selectAll('validation_conge', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $validationConge    = new ValidationConge($resultat);
                $validationConges[] = $validationConge;
            }
            return $validationConges;
        }

        /**
         * Ajouter un ValidationConge
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idValidationConge'] = $this->insert('validation_conge', $parameters);
            return new ValidationConge($parameters);
        }

        /**
         * Modifier un ValidationConge
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('validation_conge', $parameters);
            return new ValidationConge($parameters);
        }

        /**
         * Supprimer un ValidationConge
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('validation_conge', $parameters);
        }
        
    }