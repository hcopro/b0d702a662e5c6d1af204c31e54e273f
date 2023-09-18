<?php
    
    /**
     * Manager de l'entité Diplôme
     *
     * @author Voahirana
     *
     * @since 09/10/19 
     */

    namespace Model;

	use \Core\DbManager;
    use \Entity\Diplome;

	class ManagerDiplome extends DbManager
	{
        /**
         * Lister les diplômes
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($parameters) 
        {
            $diplomes  = array();
            $string    = " ORDER BY dateObtention DESC";
            $resultats = $this->findAll('diplome', $parameters, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $diplome    = new Diplome($resultat);
                    $diplomes[] = $diplome;
                }
            } 
            return $diplomes;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Diplome();
        }

        /** 
         * Chercher un diplôme
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields  = array();
            $values  = array();
            $diplome = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }            
            $resultat = $this->findOne('diplome', $fields, $values);
            if (!empty($resultat)) {
                $diplome = new Diplome($resultat);
            }
            return $diplome;
        }

        /**
         * Insérer un diplôme
         *
         * @param array $parameters Les données à insérer
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $this->insert('diplome', $parameters);
            return new Diplome($parameters);
        }

        /**
         * Modifier un diplôme
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('diplome', $parameters);
            return new Diplome($parameters);
        }

        /**
         * Supprimer une diplôme
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('diplome', $parameters);
        }
	}