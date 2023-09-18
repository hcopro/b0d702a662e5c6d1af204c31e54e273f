<?php

	/**
	 * Manager de l'entité Logiciel
	 *
	 * @author Billy Bam
	 *
	 * @since 21/11/2022 
	*/

	namespace Model;

	use \Core\DbManager;
	use \Entity\Logiciel;

	class ManagerLogiciel extends DbManager{

		/** 
	     * Lister les logiciels
	     *
	     * @param array $parameters Critères des données à lister
	     *
	     * @return array
	    */
		public function lister($attributes = null)
		{
			$logiciels      = array();
	        $queryOrderBy   = " ORDER BY nom_logiciel ASC";
	        $resultats      = $this->findAll('logiciel', $attributes, $queryOrderBy);
	        foreach ($resultats as $resultat) {
	            $logiciel    = new Logiciel($resultat);
	            $logiciels[] = $logiciel;
	        }
	        return $logiciels;
		}
		/** 
	     * Créer un objet vide
	     *
	     * @return object|empty
	    */
		public function initialiser()
		{
			return new Logiciel();
		}
		/** 
	     * Chercher un logiciel
	     *
	     * @param array $parameters Critères des données à chercher
	     *
	     * @return object|empty
	    */
		public function chercher($parameters)
		{
			$fields = array();
	        $values = array();
	        $Logiciel   = null;
	        foreach ($parameters as $key => $value) {
	            if (filter_var($value, FILTER_VALIDATE_INT) == true) {
	                $value = $value;
	            } else {
	                $value =  "'" . $value . "'";
	            }
	            $fields [] = $key;
	            $values [] = $value;
	        }
	        $resultat = $this->findOne('logiciel', $fields, $values);
	        if (!empty($resultat)) {
	            $logiciel = new Logiciel($resultat);
	        }
	        return $logiciel;
		}
		/**
	     * Ajouter un logiciel
	     *
	     * @param array $parameters Les données à ajouter
	     *
	     * @return object
	    */
		public function ajouter($parameters)
		{
			$parameters['idLogiciel'] = $this->insert('logiciel', $parameters);
	        return new Logiciel($parameters);
		}
		/**
	     * Modifier un logiciel
	     *
	     * @param array $parameters Les données à modifier
	     *
	     * @return object
	    */
		public function modifier($parameters)
		{
			$update = $this->update('logiciel', $parameters);
	        return new Logiciel($parameters);
		}
		/**
	     * Supprimer un logiciel
	     *
	     * @param array $parameters Critères des données à supprimer
	     *
	     * @return empty
	    */
		public function supprimer($parameters)
		{
			return $this->delete('logiciel', $parameters);
		}
	}