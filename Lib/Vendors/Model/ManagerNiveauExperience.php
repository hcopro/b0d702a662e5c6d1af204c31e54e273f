<?php
    
    /**
     * Manager de l'entité Niveau d'expérience
     *
     * @author Voahirana
     *
     * @since 14/10/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\NiveauExperience;

    class ManagerNiveauExperience extends DbManager
    {
       /**
         * Lister les niveaux d'expériences
         *
         * @return array
         */
        public function lister() 
        {
            $niveauExperiences = array();
            $string            = " ORDER BY ordre ASC";
            $resultats         = $this->findAll('niveau_experience', null, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $niveauExperience    = new NiveauExperience($resultat);
                    $niveauExperiences[] = $niveauExperience;
                }
            }            
            return $niveauExperiences;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new NiveauExperience();
        }

        /** 
         * Chercher un niveau d'expérience
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields           = array();
            $values           = array();
            $niveauExperience = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('niveau_experience', $fields, $values);
            if (!empty($resultat)) {
                $niveauExperience = new NiveauExperience($resultat);
            }
            return $niveauExperience;
        }

        /** 
         * Chercher le dérnier identifiant d'un niveau d'expérience
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('niveau_experience', 'idNiveauExperience');
        }

        /**
         * Ajouter un niveau d'expérience
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idNiveauExperience'] = $this->insert('niveau_experience', $parameters);
            return new NiveauExperience($parameters);
        }

        /**
         * Modifier un niveau d'expérience
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('niveau_experience', $parameters);
            return new NiveauExperience($parameters);
        }

        /**
         * Supprimer un niveau d'expérience
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('niveau_experience', $parameters);
        }
    }