<?php 
	
	/** 
	 * Entité DomaineFormateur
	 *
	 * @author Toky
	 *
	 * @since 17/09/20
	 */

	namespace Entity;

	class DomaineFormateur
	{
		private $idDomaineFormateur;
		private $idFormateur;
		private $idSousDomaine;
		
		/** 
		 * Initialisation d'un domaine de formateur
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
	    public function getIdDomaineFormateur()
	    {
	    	return $this->idDomaineFormateur;
	    }

		public function getIdFormateur()
		{
			return $this->idFormateur;
		}

		public function getIdSousDomaine()
		{
			return $this->idSousDomaine;
		}

	// Seters
		public function setIdDomaineFormateur($idDomaineFormateur)
		{
			$this->idDomaineFormateur = $idDomaineFormateur;
		}

		public function setIdFormateur($idFormateur)
		{
			$this->idFormateur = $idFormateur;
		}

		public function setIdSousDomaine($idSousDomaine)
		{
			$this->idSousDomaine = $idSousDomaine;
		}
	}