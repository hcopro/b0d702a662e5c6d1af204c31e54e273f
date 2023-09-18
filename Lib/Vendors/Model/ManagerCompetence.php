<?php
    
    /**
     * Manager de l'entité competence
     *
     * @author Billy Bam
     *
     * @since 04/10/19 
    */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Competence;

    class ManagerCompetence extends DbManager
    {
       /**
         * Lister les competences
         *
         * @return array
        */
        public function lister() 
        {
            $competences = array();
            $string        = " ORDER BY competence ASC";
            $resultats     = $this->findAll('competence', null, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $competence    = new Competence($resultat);
                    $competences[] = $competence;
                }
            }            
            return $competences;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
        */
        public function initialiser()
        {
            return new Competence();
        }

        /** 
         * Chercher une competence
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
        */
        public function chercher($parameters)
        {
            $fields       = array();
            $values       = array();
            $competence = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('competence', $fields, $values);
            if (!empty($resultat)) {
                $competence = new Personnalite($resultat);
            }
            return $competence;
        }

        /**
         * Ajouter une competence
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
        */
        public function ajouter($parameters) 
        {
            $this->insert('competence', $parameters);
            return new Competence($parameters);
        }

        /**
         * Modifier une competence
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
        */
        public function modifier($parameters) 
        {
            $this->update('competence', $parameters);
            return new Competence($parameters);
        }

        /**
         * Supprimer une competence
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimer($parameters) 
        {
            return $this->delete('competence', $parameters);
        }
    }