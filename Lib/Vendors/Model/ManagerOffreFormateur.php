<?php
    
    /**
     * Manager de l'entité OffreFormateur
     *
     * @author Toky
     *
     * @since 10/09/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\OffreFormateur;

    class ManagerOffreFormateur extends DbManager
    {
        /** 
         * Lister les offres des formateurs
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs)
        {
            $offreFormateurs   = array();
            $queryOrderBy   = " ORDER BY statut DESC";
            $resultats      = $this->findAll('offre_formateur', $attributs, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $offreFormateur    = new OffreFormateur($resultat);
                $offreFormateurs[] = $offreFormateur;
            }
            return $offreFormateurs;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new OffreFormateur();
        }

        /** 
         * Chercher un intérim
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $offreFormateur = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('offre_formateur', $fields, $values);
            if (!empty($resultat)) {
                $offreFormateur = new OffreFormateur($resultat);
            }
            return $offreFormateur;
        }

        /**
         * Ajouter un offre formateur
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idOffreFormateur'] = $this->insert('offre_formateur', $parameters);
            return new OffreFormateur($parameters);
        }

        /**
         * Modifier un intérim
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('offre_formateur', $parameters);
            return new OffreFormateur($parameters);
        }

        /**
         * Supprimer un OffreFormateur
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('offre_formateur', $parameters);
        }
        
    }