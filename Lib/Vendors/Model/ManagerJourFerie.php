<?php
    
    /**
     * Manager de l'entité JourFerie
     *
     * @author Toky
     *
     * @since 14/07/2020
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\JourFerie;

    class ManagerJourFerie extends DbManager
    {
        /** 
         * Lister les jours fériés
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs = null)
        {
            $jourFeries   = array();
            $orderByQuery = " ORDER BY idjourFerie ASC";
            $resultats = $this->findAll('jour_ferie', $attributs, $orderByQuery);
            foreach ($resultats as $resultat) {
                $jourFerie   = new JourFerie($resultat);
                $jourFeries[] = $jourFerie;
            }
            return $jourFeries;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new JourFerie();
        }

        /** 
         * Chercher un jourFerie
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields    = array();
            $values    = array();
            $jourFerie = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('jour_ferie', $fields, $values);
            if (!empty($resultat)) {
                $jourFerie = new JourFerie($resultat);
            }
            return $jourFerie;
        }

        /**
         * Ajouter un jour férié
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idjourFerie'] = $this->insert('jour_ferie', $parameters);
            return new JourFerie($parameters);
        }

        /**
         * Modifier une jour férié
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('jour_ferie', $parameters);
            return new JourFerie($parameters);
        }

        /**
         * Supprimer un jour férié
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('jour_ferie', $parameters);
        }
        
    }