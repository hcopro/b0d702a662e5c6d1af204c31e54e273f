<?php
    
    /**
     * Manager de l'entité Formateur
     *
     * @author Toky
     *
     * @since 10/09/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Formateur;

    class ManagerFormateur extends DbManager
    {
        /** 
         * Lister les formateurs
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs)
        {
            $formateurs   = array();
            $queryOrderBy   = " ORDER BY idFormateur DESC";
            $resultats      = $this->findAll('formateur', $attributs, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $formateur    = new Formateur($resultat);
                $formateurs[] = $formateur;
            }
            return $formateurs;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Formateur();
        }

        /** 
         * Chercher un intérim
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $formateur = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('formateur', $fields, $values);
            if (!empty($resultat)) {
                $formateur = new Formateur($resultat);
            }
            return $formateur;
        }

        /**
         * Ajouter un intérim
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idFormateur'] = $this->insert('formateur', $parameters);
            return new Formateur($parameters);
        }

        /**
         * Modifier un intérim
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('formateur', $parameters);
            return new Formateur($parameters);
        }

        /**
         * Supprimer un Formateur
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('formateur', $parameters);
        }
        
    }