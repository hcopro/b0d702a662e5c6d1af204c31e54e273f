<?php
    
    /**
     * Manager de l'entité Item
     *
     * @author Lansky
     *
     * @since 09/07/2021 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Item;

    class ManagerItem extends DbManager
    {
        /** 
         * Lister les items
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $items      = array();
            $queryOrderBy   = " ORDER BY idItem DESC";
            $resultats      = $this->findAll('item', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $item    = new Item($resultat);
                $items[] = $item;
            }
            return $items;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Item();
        }

        /** 
         * Chercher un Item
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $Item = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('item', $fields, $values);
            if (!empty($resultat)) {
                $item = new Item($resultat);
            }
            return $item;
        }

        /**
         * Ajouter un Item
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idAvance'] = $this->insert('item', $parameters);
            return new Item($parameters);
        }

        /**
         * Modifier un Item
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('item', $parameters);
            return new Item($parameters);
        }

        /**
         * Supprimer un Item
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('item', $parameters);
        }
        
    }