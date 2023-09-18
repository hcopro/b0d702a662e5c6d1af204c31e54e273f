<?php
    
    /**
     * Manager de l'entité InterlocuteurNiveauEntretien
     *
     * @author Voahirana
     *
     * @since 09/12/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\InterlocuteurNiveauEntretien;

    class ManagerInterlocuteurNiveauEntretien extends DbManager
    {
       /**
         * Lister les interlocuteurs d'un niveau d'entretien
         *
         * @param array $attributes Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes) 
        {
            $interlocuteurNiveauEntretiens  = array();
            $resultats                      = $this->findAll('interlocuteur_niveau_entretien', $attributes);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $interlocuteurNiveauEntretien    = new InterlocuteurNiveauEntretien($resultat);
                    $interlocuteurNiveauEntretiens[] = $interlocuteurNiveauEntretien;
                }
            }            
            return $interlocuteurNiveauEntretiens;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new InterlocuteurNiveauEntretien();
        }

        /** 
         * Chercher un interlocuteurNiveauEntretien
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields  = array();
            $values  = array();
            $interlocuteurNiveauEntretien = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }            
            $resultat = $this->findOne('interlocuteur_niveau_entretien', $fields, $values);
            if (!empty($resultat)) {
                $interlocuteurNiveauEntretien = new InterlocuteurNiveauEntretien($resultat);
            }
            return $interlocuteurNiveauEntretien;
        }

        /**
         * Ajouter un interlocuteurNiveauEntretien
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        { 
            $parameters['idinterlocuteurNiveauEntretien'] = $this->insert('interlocuteur_niveau_entretien', $parameters);
            return new InterlocuteurNiveauEntretien($parameters);
        }

        /**
         * Modifier un interlocuteurNiveauEntretien
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('interlocuteur_niveau_entretien', $parameters);
            return new InterlocuteurNiveauEntretien($parameters);
        }

        /**
         * Supprimer un interlocuteurNiveauEntretien
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('interlocuteur_niveau_entretien', $parameters);
        }
    }