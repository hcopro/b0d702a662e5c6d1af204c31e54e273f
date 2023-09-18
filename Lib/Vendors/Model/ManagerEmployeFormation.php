<?php
    
    /**
     * Manager de l'entité EmployeFormation
     *
     * @author Toky
     *
     * @since 10/09/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EmployeFormation;

    class ManagerEmployeFormation extends DbManager
    {
        /** 
         * Lister les employes concernés par la formation
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs)
        {
            $employeFormations   = array();
            $queryOrderBy   = " ORDER BY idEmployeFormation DESC";
            $resultats      = $this->findAll('employe_formation', $attributs, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $employeFormation    = new EmployeFormation($resultat);
                $employeFormations[] = $employeFormation;
            }
            return $employeFormations;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new EmployeFormation();
        }

        /** 
         * Chercher un participant
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $employeFormation = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('employe_formation', $fields, $values);
            if (!empty($resultat)) {
                $employeFormation = new EmployeFormation($resultat);
            }
            return $employeFormation;
        }

        /**
         * Ajouter un participant
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idEmployeFormation'] = $this->insert('employe_formation', $parameters);
            return new EmployeFormation($parameters);
        }

        /**
         * Modifier un participant
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('employe_formation', $parameters);
            return new EmployeFormation($parameters);
        }

        /**
         * Supprimer un participant
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('employe_formation', $parameters);
        }
        
    }