<?php
    
    /**
     * Manager de l'entité Experience
     *
     * @author Voahirana
     *
     * @since 08/10/19 
     */

    namespace Model;

	use \Core\DbManager;
    use \Entity\Experience;

	class ManagerExperience extends DbManager
	{
        /**
         * Lister les expériences
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($parameters) 
        {
            $experiences = array();
            $string      = " ORDER BY dateFin DESC";
            $resultats   = $this->findAll('experience', $parameters, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $experience    = new Experience($resultat);
                    $experiences[] = $experience;
                }
            } 
            return $experiences;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Experience();
        }

        /** 
         * Chercher une experience
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields     = array();
            $values     = array();
            $experience = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('experience', $fields, $values);
            if (!empty($resultat)) {
                $experience = new Experience($resultat);
            }
            return $experience;
        }

        /**
         * Insérer une experience
         *
         * @param array $parameters Les données à insérer
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $this->insert('experience', $parameters);
            return new Experience($parameters);
        }

        /**
         * Modifier une experience
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('experience', $parameters);
            return new Experience($parameters);
        }

        /**
         * Supprimer une experience
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('experience', $parameters);
        }
	}