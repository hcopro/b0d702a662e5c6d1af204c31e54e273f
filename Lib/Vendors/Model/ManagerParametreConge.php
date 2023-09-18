<?php
    
    /**
     * Manager de l'entité ParametreConge
     *
     * @author Toky
     *
     * @since 02/09/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ParametreConge;

    class ManagerParametreConge extends DbManager
    {
        /** 
         * Lister les parametres de permission
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister()
        {
            $parametreConges   = array();
            $queryOrderBy            = " ORDER BY idParametreConge ASC";
            $resultats               = $this->findAll('parametre_conge', null, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $parametreConge    = new ParametreConge($resultat);
                $parametreConges[] = $parametreConge;
            }
            return $parametreConges;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new ParametreConge();
        }

        /** 
         * Chercher un paramètre de congé
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $parametreConge = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('parametre_conge', $fields, $values);
            if (!empty($resultat)) {
                $parametreConge = new ParametreConge($resultat);
            }
            return $parametreConge;
        }

        /**
         * Ajouter un parametre de permission
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idParametreConge'] = $this->insert('parametre_conge', $parameters);
            return new ParametreConge($parameters);
        }

        /**
         * Modifier un parametre de permission
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('parametre_conge', $parameters);
            return new ParametreConge($parameters);
        }

        /**
         * Supprimer un parametre de permission
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('parametre_conge', $parameters);
        }
        
    }