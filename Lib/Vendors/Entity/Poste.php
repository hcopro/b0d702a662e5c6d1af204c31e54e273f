<?php 
	
	/** 
	 * Entité Poste
	 *
	 * @author Voahirana 
	 *
	 * @since 11/03/20
	 */

	namespace Entity;

	class Poste
	{
		private $idPoste;
		private $nomPoste;
		
		/** 
		 * Initialisation d'un Poste
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
		public function getIdPoste()
		{
			return $this->idPoste;
		}

		public function getNomPoste()
		{
			return $this->nomPoste;
		}

	// Seters
		public function setIdPoste($idPoste)
		{
			$this->idPoste = $idPoste;
		}

		public function setNomPoste($nomPoste)
		{
			$this->nomPoste = $nomPoste;
		}
		
	}