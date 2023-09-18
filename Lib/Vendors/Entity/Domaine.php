<?php 
	
	/** 
	 * Entité Domaine
	 *
	 * @author Voahirana 
	 *
	 * @since 03/10/19
	 */

	namespace Entity;

	class Domaine
	{
		private $idDomaine;
		private $nomDomaine;
		
		/** 
		 * Initialisation d'un Domaine
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
		public function getIdDomaine()
		{
			return $this->idDomaine;
		}

		public function getNomDomaine()
		{
			return $this->nomDomaine;
		}

	// Seters
		public function setIdDomaine($idDomaine)
		{
			$this->idDomaine = $idDomaine;
		}

		public function setNomDomaine($nomDomaine)
		{
			$this->nomDomaine = $nomDomaine;
		}
		
	}