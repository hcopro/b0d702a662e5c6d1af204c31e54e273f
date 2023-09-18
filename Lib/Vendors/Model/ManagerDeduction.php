<?php
    
    /**
     * Manager de l'entité Deduction
     *
     * @author Toky
     *
     * @since 16/11/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Deduction;

    class ManagerDeduction extends DbManager
    {
        /** 
         * Lister les Deductions
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $deductions     = array();
            $queryOrderBy   = " ORDER BY idDeduction DESC";
            $resultats      = $this->findAll('deduction', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $deduction    = new Deduction($resultat);
                $deductions[] = $deduction;
            }
            return $deductions;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Deduction();
        }

        /** 
         * Chercher un Deduction
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $deduction = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('deduction', $fields, $values);
            if (!empty($resultat)) {
                $deduction = new Deduction($resultat);
            }
            return $deduction;
        }

        /**
         * Ajouter un Deduction
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idDeduction'] = $this->insert('deduction', $parameters);
            return new Deduction($parameters);
        }

        /**
         * Modifier un Deduction
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('deduction', $parameters);
            return new Deduction($parameters);
        }

        /**
         * Supprimer un Deduction
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('deduction', $parameters);
        }
        
    }