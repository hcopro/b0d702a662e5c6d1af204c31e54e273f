<?php
    
    /**
     * Manager de l'email de contact
     *
     * @author Voahirana
     *
     * @since 18/11/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EmailContact;

    class ManagerEmailContact extends DbManager
    {
       /**
         * Lister les emails de contact
         *
         * @return array
         */
        public function lister() 
        {
            $emailsContacts = array();
            $string         = " ORDER BY email ASC";
            $tab            = $this->findAll('email_contact', null, $string);
            if (!empty($tab)) {
                foreach ($tab as $data) {
                    $emailContact     = new EmailContact($data);
                    $emailsContacts[] = $emailContact;
                }
            }            
            return $emailsContacts;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new EmailContact();
        }

        /** 
         * Chercher un email de contact
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields       = array();
            $values       = array();
            $emailContact = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('email_contact', $fields, $values);
            if (!empty($resultat)) {
                $emailContact = new EmailContact($resultat);
            }
            return $emailContact;
        }

        /** 
         * Chercher le dérnier identifiant d'un email de contact
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('email_contact', 'idEmailContact');
        }

        /**
         * Ajouter un email de contact
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $this->insert('email_contact', $parameters);
            return new EmailContact($parameters);
        }

        /**
         * Modifier un email de contact
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('email_contact', $parameters);
            return new EmailContact($parameters);
        }

        /**
         * Supprimer un email de contact
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('email_contact', $parameters);
        }
    }