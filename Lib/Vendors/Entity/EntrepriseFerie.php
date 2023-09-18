<?php 
	
	/** 
	 * Entité EntrepiseFerie
	 *
	 * @author Toky
	 *
	 * @since 14/07/20
	 */

	namespace Entity;

	class EntrepriseFerie
	{
		private $idEntrepriseFerie;
		private $idEntreprise;
		private $idJourFerie;
		private $date;
		
		/** 
		 * Initialisation de EntrepriseFerie
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
	    public function getIdEntrepriseFerie()
	    {
	    	return $this->idEntrepriseFerie;
	    }

	    public function getIdEntreprise()
	    {
	    	return $this->idEntreprise;
	    }

	    public function getIdJourFerie()
	    {
	    	return $this->idJourFerie;
	    }

	    public function getDate()
	    {
	    	return $this->date;
	    }

	    // Setters
	    public function setIdEntrepriseFerie($idEntrepriseFerie)
	    {
	    	$this->idEntrepriseFerie = $idEntrepriseFerie;
	    }

	    public function setIdEntreprise($idEntreprise)
	    {
	    	$this->idEntreprise = $idEntreprise;
	    }

	    public function setIdJourFerie($idJourFerie)
	    {
	    	$this->idJourFerie = $idJourFerie;
	    }

	    public function setDate($date)
	    {
	    	$this->date = $date;
	    }
	}