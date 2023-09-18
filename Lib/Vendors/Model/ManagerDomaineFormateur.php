<?php
    
    /**
     * Manager de l'entité DomaineFormateur
     *
     * @author Toky
     *
     * @since 17/09/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\DomaineFormateur;

    class ManagerDomaineFormateur extends DbManager
    {
        /** 
         * Lister les domaines des formateurs
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs)
        {
            $domaineFormateurs   = array();
            $queryOrderBy   = " ORDER BY idDomaineFormateur DESC";
            $resultats      = $this->findAll('domaine_formateur', $attributs, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $domaineFormateur    = new DomaineFormateur($resultat);
                $domaineFormateurs[] = $domaineFormateur;
            }
            return $domaineFormateurs;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new DomaineFormateur();
        }

        /** 
         * Chercher un domaine de formateur
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $domaineFormateur = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('domaine_formateur', $fields, $values);
            if (!empty($resultat)) {
                $domaineFormateur = new DomaineFormateur($resultat);
            }
            return $domaineFormateur;
        }

        /**
         * Ajouter un domaine de formateur
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idDomaineFormateur'] = $this->insert('domaine_formateur', $parameters);
            return new DomaineFormateur($parameters);
        }

        /**
         * Modifier un domaine de formateur
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('domaine_formateur', $parameters);
            return new DomaineFormateur($parameters);
        }

        /**
         * Supprimer un domaine de formateur
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('domaine_formateur', $parameters);
        }
        
    }