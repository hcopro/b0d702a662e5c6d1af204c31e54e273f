<?php
    
    /**
     * Manager de l'entité Barometre
     *
     * @author Lansky
     *
     * @since 11/01/2022 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Barometre;

    class ManagerBarometre extends DbManager
    {
        /**
         * Lister les tests personnalités
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {

            $testPersonalities    = array();
            $queryOrderBy   = " ORDER BY libelle ASC";
            $resultats      = $this->findAll('barometre', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $barometre    = new Barometre($resultat);
                $barometres[] = $barometre;
            }
            return $barometres;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Barometre();
        }

        /** 
         * Chercher un test personnalité
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $barometre = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('barometre', $fields, $values);
            if (!empty($resultat)) {
                $barometre = new Barometre($resultat);
            }
            return $barometre;
        }

        /**
         * Ajouter un test personnalité
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idBarometre'] = $this->insert('barometre', $parameters);
            return new Barometre($parameters);
        }

        /**
         * Modifier un test personnalité
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('barometre', $parameters);
            return new Barometre($parameters);
        }

        /**
         * Supprimer un test personnalité
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('barometre', $parameters);
        }
        
    }