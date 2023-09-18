<?php
    
    /**
     * Manager de l'entité Employe
     *
     * @author Voahirana
     *
     * @since 13/03/20 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Employe;

    class ManagerEmploye extends DbManager
    {
       /**
         * Lister les employés d'une entreprise
         *
         * @param array $attributes Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes) 
        {
            $employes  = array();
            $string    = " ORDER BY nom ASC";
            $resultats = $this->findAll('employe', $attributes, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $employe    = new Employe($resultat);
                    $employes[] = $employe;
                }
            }            
            return $employes;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Employe();
        }

        /** 
         * Chercher un employé d'une entreprise
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields  = array();
            $values  = array();
            $employe = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }            
            $resultat = $this->findOne('employe', $fields, $values);
            if (!empty($resultat)) {
                $employe = new Employe($resultat);
            }
            return $employe;
        }

        /**
         * Ajouter un employé d'une entreprise
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        { 
            $parameters['idEmploye'] = $this->insert('employe', $parameters); 
            return new Employe($parameters);
        }

        /**
         * Modifier un employé d'une entreprise
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('employe', $parameters);
            return new Employe($parameters);
        }

        /**
         * Supprimer un employé d'une entreprise
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('employe', $parameters);
        }

        
    }