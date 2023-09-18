<?php
    
    /**
     * Manager de l'entité Pointage
     *
     * @author Toky
     *
     * @since 16/07/2020
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Pointage;

    class ManagerPointage extends DbManager
    {
        /** 
         * Lister les pointages
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs = null)
        {
            $pointages   = array();
            $orderByQuery = " ORDER BY idPointage ASC";
            $resultats = $this->findAll('pointage', $attributs, $orderByQuery);
            foreach ($resultats as $resultat) {
                $pointage   = new Pointage($resultat);
                $pointages[] = $pointage;
            }
            return $pointages;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Pointage();
        }

        /** 
         * Chercher un pointage
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields    = array();
            $values    = array();
            $pointage  = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('pointage', $fields, $values);
            if (!empty($resultat)) {
                $pointage = new Pointage($resultat);
            }
            return $pointage;
        }

        /**
         * Ajouter un pointage
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idPointage'] = $this->insert('pointage', $parameters);
            return new Pointage($parameters);
        }

        /**
         * Modifier un pointage
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('pointage', $parameters);
            return new Pointage($parameters);
        }

        /**
         * Supprimer un pointage
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('pointage', $parameters);
        }
    }