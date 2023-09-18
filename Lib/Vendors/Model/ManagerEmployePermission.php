<?php
    
    /**
     * Manager de l'entité EmployePermission
     *
     * @author Toky
     *
     * @since 23/07/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EmployePermission;

    class ManagerEmployePermission extends DbManager
    {
        /** 
         * Lister les permissions d'un employe
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $employePermissions      = array();
            $queryOrderBy            = " ORDER BY idEmployePermission DESC";
            $resultats               = $this->findAll('employe_permission', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $employePermission    = new EmployePermission($resultat);
                $employePermissions[] = $employePermission;
            }
            return $employePermissions;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new EmployePermission();
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
            $employePermission = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('employe_permission', $fields, $values);
            if (!empty($resultat)) {
                $employePermission = new EmployePermission($resultat);
            }
            return $employePermission;
        }

        /**
         * Selectionner les demandes de permission dans un intervalle
         *
         * @param array @attributes critère avec égalité
         * @param array @min        intervalle minimum
         * @param array @max        interval maximum
         *
         * @return array
         */
        public function selectionner($attributes=null, $min, $max)
        {
            $employePermissions   = array();
            $orderByQuery         = " ORDER BY date ASC";
            $resultats = $this->selectAll('employe_permission', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $employePermission    = new EmployePermission($resultat);
                $employePermissions[] = $employePermission;
            }
            return $employePermissions;
        }

        /**
         * Ajouter une permission d'un employe
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idEmployePermission'] = $this->insert('employe_permission', $parameters);
            return new EmployePermission($parameters);
        }

        /**
         * Modifier une permission d'un employe
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('employe_permission', $parameters);
            return new EmployePermission($parameters);
        }

        /**
         * Supprimer une permission d'un employé
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('employe_permission', $parameters);
        }
        
    }