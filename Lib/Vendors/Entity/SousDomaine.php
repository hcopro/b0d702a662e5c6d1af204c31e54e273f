<?php 
	
	/** 
	 * Entité SousDomaine
	 *
	 * @author Voahirana 
	 *
	 * @since 03/10/19
	 */

	namespace Entity;

	class SousDomaine
	{
		private $idSousDomaine;
		private $idDomaine;
		private $nomSousDomaine;
		
		/** 
		 * Initialisation d'un SousDomaine
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
		public function getIdSousDomaine()
		{
			return $this->idSousDomaine;
		}

		public function getIdDomaine()
		{
			return $this->idDomaine;
		}

		public function getnomSousDomaine()
		{
			return $this->nomSousDomaine;
		}

	// Seters
		public function setIdSousDomaine($idSousDomaine)
		{
			$this->idSousDomaine = $idSousDomaine;
		}

		public function setIdDomaine($idDomaine)
		{
			$this->idDomaine = $idDomaine;
		}

		public function setnomSousDomaine($nomSousDomaine)
		{
			$this->nomSousDomaine = $nomSousDomaine;
		}
		
	}