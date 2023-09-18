<?php 
	
	/** 
	 * Entité Contrat
	 *
	 * @author Voahirana 
	 *
	 * @since 14/10/19
	 */

	namespace Entity;

	class Contrat
	{
		private $idContrat;
		private $designation;
		private $offreUniquement;
		
		/** 
		 * Initialisation d'un Contrat
		 *
		 * @param array $data Données à intialiser 
		 *
		 * @return empty
		 */
	    public function __construct($data = array())
	    {
	        if(!empty($data)) {
	        	$this->hydrate($data);
	        }	            
	    }

	    /** 
		 * Remplir la structure d'un objet 
		 *
		 * @param array $data Données à remplir
		 *
		 * @return empty
		 */
	    public function hydrate($data)
	    {
	        foreach ($data as $attribut => $data) {
	            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
	            if (is_callable(array($this, $method))) {
	                $this->$method($data);
	            }
	        }
	    }

	    /** 
	     * Convertir un objet en tableau 
	     *
	     * @return array
	     */
	    public function toArray()
	    {
	    	return get_object_vars($this);
	    }

	// Getters
		public function getIdContrat()
		{
			return $this->idContrat;
		}

		public function getDesignation()
		{
			return $this->designation;
		}
		public function getOffreUniquement()
		{
			return $this->offreUniquement ;
		}

	// Seters
		public function setIdContrat($idContrat)
		{
			$this->idContrat = $idContrat;
		}

		public function setDesignation($designation)
		{
			$this->designation = $designation;
		}
		public function setOffreUniquement($offreUniquement)
		{
			$this->offreUniquement = $offreUniquement;
		}
	}