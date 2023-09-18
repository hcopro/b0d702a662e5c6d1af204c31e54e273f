<?php
    
    /**
     * Manager de l'entité Pesonnalite
     *
     * @author Voahirana
     *
     * @since 04/10/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Personnalite;

    class ManagerPersonnalite extends DbManager
    {
       /**
         * Lister les personalités
         *
         * @return array
         */
        public function lister() 
        {
            $personnalites = array();
            $string        = " ORDER BY qualite ASC";
            $resultats     = $this->findAll('personnalite', null, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $personnalite    = new Personnalite($resultat);
                    $personnalites[] = $personnalite;
                }
            }            
            return $personnalites;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Personnalite();
        }

        /** 
         * Chercher une personnalité
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields       = array();
            $values       = array();
            $personnalite = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('personnalite', $fields, $values);
            if (!empty($resultat)) {
                $personnalite = new Personnalite($resultat);
            }
            return $personnalite;
        }

        /**
         * Ajouter une personnalité
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $this->insert('personnalite', $parameters);
            return new Personnalite($parameters);
        }

        /**
         * Modifier une personnalité
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('personnalite', $parameters);
            return new Personnalite($parameters);
        }

        /**
         * Supprimer une personnalité
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('personnalite', $parameters);
        }
    }