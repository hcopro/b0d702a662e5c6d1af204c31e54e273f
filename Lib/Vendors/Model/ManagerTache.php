<?php
    
    /**
     * Manager de l'entité Tache
     *
     * @author Toky
     *
     * @since 16/07/2020
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Tache;

    class ManagerTache extends DbManager
    {
        /** 
         * Lister les tâches
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs = null)
        {
            $taches   = array();
            $orderByQuery = " ORDER BY idTache ASC";
            $resultats = $this->findAll('tache', $attributs, $orderByQuery);
            foreach ($resultats as $resultat) {
                $tache   = new Tache($resultat);
                $taches[] = $tache;
            }
            return $taches;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Tache();
        }

        /** 
         * Chercher une tâche
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields    = array();
            $values    = array();
            $tache     = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('tache', $fields, $values);
            if (!empty($resultat)) {
                $tache = new Tache($resultat);
            }
            return $tache;
        }

        /**
         * Ajouter une tâche
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idTache'] = $this->insert('tache', $parameters);
            return new Tache($parameters);
        }

        /**
         * Modifier une tâche
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('tache', $parameters);
            return new Tache($parameters);
        }

        /**
         * Supprimer une présence
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('tache', $parameters);
        }
        
    }