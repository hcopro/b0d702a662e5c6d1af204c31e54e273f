<?php
    
    /**
     * Manager de l'entité EntrepriseFerie
     *
     * @author Toky
     *
     * @since 14/07/2020
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EntrepriseFerie;

    class ManagerEntrepriseFerie extends DbManager
    {
        /** 
         * Lister les liaisons entreprise - jour férié
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs = null)
        {
            $entrepriseFeries   = array();
            $orderByQuery       = " ORDER BY date ASC";
            $resultats          = $this->findAll('entreprise_ferie', $attributs, $orderByQuery);
            foreach ($resultats as $resultat) {
                $entrepriseFerie    = new EntrepriseFerie($resultat);
                $entrepriseFeries[] = $entrepriseFerie;
            }
            return $entrepriseFeries;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new EntrepriseFerie();
        }

        /** 
         * Chercher une liaison entreprise - jour férié
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields          = array();
            $values          = array();
            $entrepriseFerie = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('entreprise_ferie', $fields, $values);
            if (!empty($resultat)) {
                $entrepriseFerie = new EntrepriseFerie($resultat);
            }
            return $entrepriseFerie;
        }

        /**
         * Ajouter une liaison entreprise - jour férié
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idEntrepriseFerie'] = $this->insert('entreprise_ferie', $parameters);
            return new EntrepriseFerie($parameters);
        }

        /**
         * Modifier une liaison entreprise - jour férié
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('entreprise_ferie', $parameters);
            return new EntrepriseFerie($parameters);
        }

        /**
         * Supprimer une liaison entreprise - jour férié
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('entreprise_ferie', $parameters);
        }
        
    }