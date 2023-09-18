<?php 
	
	/** 
	 * Entité InterlocuteurNiveauEntretien
	 *
	 * @author Voahirana 
	 *
	 * @since 09/12/19
	 */

	namespace Entity;

	class InterlocuteurNiveauEntretien
	{
		private $idInterlocuteurNiveauEntretien;
		private $idInterlocuteur;
		private $idNiveauEntretien;
		
		/** 
		 * Initialisation d'un InterlocuteurNiveauEntretien
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
		public function getIdInterlocuteurNiveauEntretien()
		{
			return $this->idInterlocuteurNiveauEntretien;
		}

		public function getIdInterlocuteur()
		{
			return $this->idInterlocuteur;
		}

		public function getIdNiveauEntretien()
		{
			return $this->idNiveauEntretien;
		}

	// Seters
		public function setIdInterlocuteurNiveauEntretien($idInterlocuteurNiveauEntretien)
		{
			$this->idInterlocuteurNiveauEntretien = $idInterlocuteurNiveauEntretien;
		}

		public function setIdInterlocuteur($idInterlocuteur)
		{
			$this->idInterlocuteur = $idInterlocuteur;
		}

		public function setIdNiveauEntretien($idNiveauEntretien)
		{
			$this->idNiveauEntretien = $idNiveauEntretien;
		}
		
	}