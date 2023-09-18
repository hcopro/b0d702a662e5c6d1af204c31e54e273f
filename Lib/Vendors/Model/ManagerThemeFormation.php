<?php
    
    /**
     * Manager de l'entité ThemeFormation
     *
     * @author Toky
     *
     * @since 10/09/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ThemeFormation;

    class ManagerThemeFormation extends DbManager
    {
        /** 
         * Lister les intérim
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs)
        {
            $themeFormations   = array();
            $queryOrderBy   = " ORDER BY priorite DESC";
            $resultats      = $this->findAll('theme_formation', $attributs, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $themeFormation    = new ThemeFormation($resultat);
                $themeFormations[] = $themeFormation;
            }
            return $themeFormations;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new ThemeFormation();
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
            $themeFormation = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('theme_formation', $fields, $values);
            if (!empty($resultat)) {
                $themeFormation = new ThemeFormation($resultat);
            }
            return $themeFormation;
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
            $parameters['idThemeFormation'] = $this->insert('theme_formation', $parameters);
            return new ThemeFormation($parameters);
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
            $this->update('theme_formation', $parameters);
            return new ThemeFormation($parameters);
        }

        /**
         * Supprimer un ThemeFormation
         *
         * @param array $parameters Critères