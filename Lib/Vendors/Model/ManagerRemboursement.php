<?php
    
    /**
     * Manager de l'entité Remboursement
     *
     * @author Toky
     *
     * @since 18/11/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Remboursement;

    class ManagerRemboursement extends DbManager
    {
        /** 
         * Lister les Remboursements
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $remboursements   = array();
            $queryOrderBy     = " ORDER BY idRemboursement ASC";
            $resultats        = $this->findAll('remboursement', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $remboursement    = new Remboursement($resultat);
                $remboursements[] = $remboursement;
            }
            return $remboursements;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Remboursement();
        }

        /** 
         * Chercher un Remboursement
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $remboursement = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('remboursement', $fields, $values);
            if (!empty($resultat)) {
                $remboursement = new Remboursement($resultat);
            }
            return $remboursement;
        }

        /**
         * Ajouter un Remboursement
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idRemboursement'] = $this->insert('remboursement', $parameters);
            return new Remboursement($parameters);
        }

        /**
         * Modifier un Remboursement
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('remboursement', $parameters);
            return new Remboursement($parameters);
        }

        /**
         * Supprimer un Remboursement
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('remboursement', $parameters);
        }
        
    }