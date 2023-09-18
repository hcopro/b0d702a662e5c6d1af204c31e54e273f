<?php 
	
	/** 
	 * Entité ValidationConge
	 *
	 * @author Toky
	 *
	 * @since 21/08/20
	 */

	namespace Entity;

	class ValidationConge
	{
		private $idValidationConge;
		private $idCompte;
		private $statut;
		private $idConge;
		private $etat;
		private $idMessage;
		
		/** 
		 * Initialisation d'une Validation de Congé
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
	    public function getIdValidationConge()
	    {
	    	return $this->idValidationConge;
	    }

		public function getidCompte()
		{
			return $this->idCompte;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getIdConge()
		{
			return $this->idConge;
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
		public function setIdValidationConge($idValidationConge)
		{
			$this->idValidationConge = $idValidationConge;
		}

		public function setidCompte($idCompte)
		{
			$this->idCompte = $idCompte;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setIdConge($idConge)
		{
			$this->idConge = $idConge;
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