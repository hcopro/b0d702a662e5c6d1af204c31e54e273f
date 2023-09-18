<?php
    
    /**
     * Manager de l'entité Formation
     *
     * @author Voahirana
     *
     * @since 08/10/19 
     */

    namespace Model;

	use \Core\DbManager;
    use \Entity\Formation;

	class ManagerFormation extends DbManager
	{
        /**
         * Lister les formations
         *
         * @return array
         */
        public function lister($attributes) 
        {
            $formations = array();
            $string     = " ORDER BY dateFin DESC";
            $resultats  = $this->findAll('formation', $attributes, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $formation    = new Formation($resultat);
                    $formations[] = $formation;
                }
            } 
            return $formations;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Formation();
        }

        /** 
         * Chercher une formation
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields    = array();
            $values    = array();
            $formation = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('formation', $fields, $values);
            if (!empty($resultat)) {
                $formation = new Formation($resultat);
            }
            return $formation;
        }

        /**
         * Insérer une formation
         *
         * @param array $parameters Les données à insérer
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $this->insert('formation', $parameters);
            return new Formation($parameters);
        }

        /**
         * Modifier une formation
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('formation', $parameters);
            return new Formation($parameters);
        }

        /**
         * Supprimer une formation
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('formation', $parameters);
        }
	}