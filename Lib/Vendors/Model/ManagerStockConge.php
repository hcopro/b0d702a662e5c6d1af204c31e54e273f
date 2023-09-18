<?php
    
    /**
     * Manager de l'entité StockConge
     *
     * @author Toky
     *
     * @since 21/08/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\StockConge;

    class ManagerStockConge extends DbManager
    {
        /** 
         * Lister les stocks de Congés
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $stockConges  = array();
            $queryOrderBy  = " ORDER BY idStockConge DESC";
            $resultats     = $this->findAll('stock_conge', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $stockConge    = new StockConge($resultat);
                $stockConges[] = $stockConge;
            }
            return $stockConges;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new StockConge();
        }

        /** 
         * Chercher un stock de Congé
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $stockConge = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('stock_conge', $fields, $values);
            if (!empty($resultat)) {
                $stockConge = new StockConge($resultat);
            }
            return $stockConge;
        }

        /**
         * Selectionner des StockConges dans un intervalle
         *
         * @param array @attributes critère avec égalité
         * @param array @min        intervalle minimum
         * @param array @max        interval maximum
         *
         * @return array
         */
        public function selectionner($attributes=null, $min, $max)
        {
            $stockConges     = array();
            $orderByQuery = " ORDER BY idStockConge DESC";
            $resultats = $this->selectAll('stock_conge', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $stockConge    = new StockConge($resultat);
                $stockConges[] = $stockConge;
            }
            return $stockConges;
        }

        /**
         * Ajouter un StockConge
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idStockConge'] = $this->insert('stock_conge', $parameters);
            return new StockConge($parameters);
        }

        /**
         * Modifier un StockConge
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('stock_conge', $parameters);
            return new StockConge($parameters);
        }

        /**
         * Supprimer un StockConge
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('stock_conge', $parameters);
        }
        
    }