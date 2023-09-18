<?php
    
    /**
     * Manager de l'entité FichePaie
     *
     * @author Toky
     *
     * @since 10/11/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\FichePaie;

    class ManagerFichePaie extends DbManager
    {
        /** 
         * Lister les FichePaies
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $fichePaies      = array();
            $queryOrderBy   = " ORDER BY idFichePaie DESC";
            $resultats      = $this->findAll('fiche_paie', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $fichePaie    = new FichePaie($resultat);
                $fichePaies[] = $fichePaie;
            }
            return $fichePaies;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new FichePaie();
        }

        /** 
         * Chercher une FichePaie
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $fichePaie = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('fiche_paie', $fields, $values);
            if (!empty($resultat)) {
                $fichePaie = new FichePaie($resultat);
            }
            return $fichePaie;
        }

        /**
         * Ajouter une FichePaie
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idFichePaie'] = $this->insert('fiche_paie', $parameters);
            return new FichePaie($parameters);
        }

        /**
         * Modifier une FichePaie
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('fiche_paie', $parameters);
            return new FichePaie($parameters);
        }

        /**
         * Supprimer une FichePaie
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('fiche_paie', $parameters);
        }
        
    }