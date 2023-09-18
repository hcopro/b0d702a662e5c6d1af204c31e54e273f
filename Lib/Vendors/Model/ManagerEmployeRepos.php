<?php
    
    /**
     * Manager de l'entité EmployeRepos
     *
     * @author Toky
     *
     * @since 07/08/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EmployeRepos;

    class ManagerEmployeRepos extends DbManager
    {
        /** 
         * Lister les repos d'un employe
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $employeReposs      = array();
            $queryOrderBy      = " ORDER BY idEmployeRepos DESC";
            $resultats         = $this->findAll('employe_repos', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $employeRepos    = new EmployeRepos($resultat);
                $employeReposs[] = $employeRepos;
            }
            return $employeReposs;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new EmployeRepos();
        }

        /** 
         * Chercher une permission d'un employe
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $employeRepos = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('employe_repos', $fields, $values);
            if (!empty($resultat)) {
                $employeRepos = new EmployeRepos($resultat);
            }
            return $employeRepos;
        }

        /**
         * Selectionner les demandes de repos dans un intervalle
         *
         * @param array @attributes critère avec égalité
         * @param array @min        intervalle minimum
         * @param array @max        interval maximum
         *
         * @return array
         */
        public function selectionner($attributes=null, $min, $max)
        {
            $employeReposs    = array();
            $orderByQuery     = " ORDER BY date ASC";
            $resultats = $this->selectAll('employe_repos', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $employeRepos    = new EmployeRepos($resultat);
                $employeReposs[] = $employeRepos;
            }
            return $employeReposs;
        }

        /**
         * Ajouter un repos d'un employe
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idEmployeRepos'] = $this->insert('employe_repos', $parameters);
            return new EmployeRepos($parameters);
        }

        /**
         * Modifier un repos d'un employe
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('employe_repos', $parameters);
            return new EmployeRepos($parameters);
        }

        /**
         * Supprimer un repos d'un employé
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('employe_repos', $parameters);
        }
        
    }