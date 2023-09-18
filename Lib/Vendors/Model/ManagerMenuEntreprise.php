<?php
    
    /**
     * Manager de l'entité MenuEntreprise
     *
     * @author Lansky
     *
     * @since 02/12/2021 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\MenuEntreprise;

    class ManagerMenuEntreprise extends DbManager
    {
        /** 
         * Lister les menu entreprise
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $menus    = array();
            $queryOrderBy   = " ORDER BY libelle ASC";
            $resultats      = $this->findAll('menu_entreprise', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $menu    = new MenuEntreprise($resultat);
                $menus[] = $menu;
            }
            return $menus;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new MenuEntreprise();
        }

        /** 
         * Chercher un menu entreprise
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $menu = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('menu_entreprise', $fields, $values);
            if (!empty($resultat)) {
                $menu = new MenuEntreprise($resultat);
            }
            return $menu;
        }

        /**
         * Ajouter un menu entreprise
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idMenu'] = $this->insert('menu_entreprise', $parameters);
            return new MenuEntreprise($parameters);
        }
    }