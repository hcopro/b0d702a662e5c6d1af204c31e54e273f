<?php 
	
	/** 
	 * Entité ValidationPermission
	 *
	 * @author Lansky
	 *
	 * @since 2023-06-07
	 */

	namespace Entity;

	class ValidationPermission
	{
		private $idValidationPermission;
		private $idCompte;
		private $idEmployePermission;
		private $idMessage;
		private $statut;
		private $etat;
		
		/** 
		 * Initialisation d'une Validation de Permission
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
	    public function getIdValidationPermission()
	    {
	    	return $this->idValidationPermission;
	    }

		public function getidCompte()
		{
			return $this->idCompte;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getIdEmployePermission()
		{
			return $this->idEmployePermission;
		}

		public function getEtat()
		{
			return $this->etat;
		}

		public function getIdMessage()
		{
			return $this->idMessage;
		}

	// Seters
		public function setIdValidationPermission($idValidationPermission)
		{
			$this->idValidationPermission = $idValidationPermission;
		}

		public function setidCompte($idCompte)
		{
			$this->idCompte = $idCompte;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setIdEmployePermission($idEmployePermission)
		{
			$this->idEmployePermission = $idEmployePermission;
		}

		public function setEtat($etat)
		{
			$this->etat = $etat;
		}

		public function setIdMessage($idMessage)
		{
			$this->idMessage = $idMessage;
		}
		
	}