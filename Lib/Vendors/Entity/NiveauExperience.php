<?php 
	
	/** 
	 * Entité NiveauExperience
	 *
	 * @author Voahirana 
	 *
	 * @since 14/10/19
	 */

	namespace Entity;

	class NiveauExperience
	{
		private $idNiveauExperience;
		private $niveau;
		private $ordre;
		
		/** 
		 * Initialisation d'un NiveauExperience
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
		public function getIdNiveauExperience()
		{
			return $this->idNiveauExperience;
		}

		public function getNiveau()
		{
			return $this->niveau;
		}
		
		public function getOrdre()
		{
			return $this->ordre;
		}

	// Seters
		public function setIdNiveauExperience($idNiveauExperience)
		{
			$this->idNiveauExperience = $idNiveauExperience;
		}

		public function setNiveau($niveau)
		{
			$this->niveau = $niveau;
		}

		public function setOrdre($ordre)
		{
			$this->ordre = $ordre;
		}
		
	}