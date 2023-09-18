<?php
    
    /**
     * Manager de l'entité HeureEmploye
     *
     * @author Toky
     *
     * @since 21/10/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\HeureEmploye;

    class ManagerHeureEmploye extends DbManager
    {
        /** 
         * Lister les HeureEmployes
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $HeureEmployes      = array();
            $queryOrderBy   = " ORDER BY idHeureEmploye DESC";
            $resultats      = $this->findAll('heure_employe', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $HeureEmploye    = new HeureEmploye($resultat);
                $HeureEmployes[] = $HeureEmploye;
            }
            return $HeureEmployes;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new HeureEmploye();
        }

        /** 
         * Chercher un HeureEmploye
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $HeureEmploye = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('heure_employe', $fields, $values);
            if (!empty($resultat)) {
                $HeureEmploye = new HeureEmploye($resultat);
            }
            return $HeureEmploye;
        }

        /**
         * Ajouter un HeureEmploye
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idHeureEmploye'] = $this->insert('heure_employe', $parameters);
            return new HeureEmploye($parameters);
        }

        /**
         * Modifier un HeureEmploye
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('heure_employe', $parameters);
            return new HeureEmploye($parameters);
        }

        /**
         * Supprimer un HeureEmploye
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('heure_employe', $parameters);
        }
        
    }