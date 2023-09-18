<?php
    
    /**
     * Manager de l'entité TacheAutomatique
     *
     * @author Toky
     *
     * @since 28/08/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\TacheAutomatique;

    class ManagerTacheAutomatique extends DbManager
    {
        /** 
         * Lister les Taches Automatiques
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes = null)
        {
            $tacheAutomatiques  = array();
            $queryOrderBy  = " ORDER BY idTacheAutomatique DESC";
            $resultats     = $this->findAll('tache_automatique', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $tacheAutomatique    = new TacheAutomatique($resultat);
                $tacheAutomatiques[] = $tacheAutomatique;
            }
            return $tacheAutomatiques;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new TacheAutomatique();
        }

        /** 
         * Chercher une tache automatique
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $tacheAutomatique = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('tache_automatique', $fields, $values);
            if (!empty($resultat)) {
                $tacheAutomatique = new TacheAutomatique($resultat);
            }
            return $tacheAutomatique;
        }


        /**
         * Ajouter une TacheAutomatique
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idTacheAutomatique'] = $this->insert('tache_automatique', $parameters);
            return new TacheAutomatique($parameters);
        }

        /**
         * Modifier une TacheAutomatique
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('tache_automatique', $parameters);
            return new TacheAutomatique($parameters);
        }

        /**
         * Supprimer une TacheAutomatique
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('tache_automatique', $parameters);
        }
        
    }