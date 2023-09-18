<?php 
	
	/** 
	 * Entité ParametreAvance
	 *
	 * @author Toky
	 *
	 * @since 18/11/20
	 */

	namespace Entity;

	class ParametreAvance
	{
		private $idParametreAvance;
		private $idEntreprise;
		private $dureeMax;
		private $tauxMax;
		
		/** 
		 * Initialisation d'un ParametreAvance
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
		public function getIdParametreAvance()
		{
			return $this->idParametreAvance;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getDureeMax()
		{
			return $this->dureeMax;
		}

		public function getTauxMax()
		{
			return $this->tauxMax;
		}

	// Seters
		public function setIdParametreAvance($idParametreAvance)
		{
			$this->idParametreAvance = $idParametreAvance;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setDureeMax($dureeMax)
		{
			$this->dureeMax = $dureeMax;
		}

		public function setTauxMax($tauxMax)
		{
			$this->tauxMax = $tauxMax;
		}
	}