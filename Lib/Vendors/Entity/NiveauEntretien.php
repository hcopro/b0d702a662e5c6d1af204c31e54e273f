<?php 
	
	/** 
	 * Entité NiveauEntretien
	 *
	 * @author Voahirana 
	 *
	 * @since 13/11/19
	 */

	namespace Entity;

	class NiveauEntretien
	{
		private $idNiveauEntretien;
		private $service;
		private $idEntreprise;
		private $ordre;
		
		/** 
		 * Initialisation d'un NiveauEntretien
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
		public function getIdNiveauEntretien()
		{
			return $this->idNiveauEntretien;
		}

		public function getService()
		{
			return $this->service;
		}
		
		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}
		
		public function getOrdre()
		{
			return $this->ordre;
		}

	// Seters
		public function setIdNiveauEntretien($idNiveauEntretien)
		{
			$this->idNiveauEntretien = $idNiveauEntretien;
		}

		public function setService($service)
		{
			$this->service = $service;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setOrdre($ordre)
		{
			$this->ordre = $ordre;
		}
	}