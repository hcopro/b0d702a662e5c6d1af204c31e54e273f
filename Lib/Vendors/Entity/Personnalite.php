<?php 
	
	/** 
	 * Entité Personnalite
	 *
	 * @author Voahirana 
	 *
	 * @since 07/10/19
	 */

	namespace Entity;

	class Personnalite
	{
		private $idPersonnalite;
		private $qualite;
		
		/** 
		 * Initialisation d'un Candidat
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
		public function getIdPersonnalite()
		{
			return $this->idPersonnalite;
		}

		public function getQualite()
		{
			return $this->qualite;
		}

	// Seters
		public function setIdPersonnalite($idPersonnalite)
		{
			$this->idPersonnalite = $idPersonnalite;
		}

		public function setQualite($qualite)
		{
			$this->qualite = $qualite;
		}
		
	}