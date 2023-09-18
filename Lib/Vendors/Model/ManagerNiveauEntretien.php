<?php
    
    /**
     * Manager de l'entité Niveau d'entretien
     *
     * @author Voahirana
     *
     * @since 13/11/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\NiveauEntretien;

    class ManagerNiveauEntretien extends DbManager
    {
       /**
         * Lister les niveaux d'entretien
         *
         * @return array
         */
        public function lister($parameters) 
        {
            $niveauEntretiens = array();
            $string           = " ORDER BY ordre ASC";
            $resultats        = $this->findAll('niveau_entretien', $parameters, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $niveauEntretien    = new NiveauEntretien($resultat);
                    $niveauEntretiens[] = $niveauEntretien;
                }
            }            
            return $niveauEntretiens;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new NiveauEntretien();
        }

        /** 
         * Chercher un niveau d'entretien
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields          = array();
            $values          = array();
            $niveauEntretien = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('niveau_entretien', $fields, $values);
            if (!empty($resultat)) {
                $niveauEntretien = new NiveauEntretien($resultat);
            }
            return $niveauEntretien;
        }

        /** 
         * Chercher le dérnier identifiant d'un niveau d'entretien
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('niveau_entretien', 'idNiveauEntretien');
        }

        /** 
         * Recupérer l'ordre min de niveau d'entretien
         * 
         * @param int $parameters l'entreprise concerné 
         *
         * @return array
         */
        public function getMinOrdre($parameters)
        {
            $query   = "SELECT min(ordre) AS minOrdre
                          FROM niveau_entretien 
                          WHERE idEntreprise = " . $parameters;
            $requete = $this->pdo()->prepare($query);
            $requete->execute();
            return $requete->fetch();            
        }

        /**
         * Ajouter un niveau d'entretien
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idNiveauEntretien'] = $this->insert('niveau_entretien', $parameters);
            return new NiveauEntretien($parameters);
        }

        /**
         * Modifier un niveau d'entretien
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('niveau_entretien', $parameters);
            return new NiveauEntretien($parameters);
        }

        /**
         * Supprimer un niveau d'entretien
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('niveau_entretien', $parameters);
        }
    }