<?php 
	
	/** 
	 * Entité Campaign
	 *
	 * @author Lansky 
	 *
	 * @since 2023-05-26
	*/

	namespace Entity;

	class Campaign
	{
		private $idCampaign;
		private $idEntreprise;
		private $libelle;
		private $startDate;
		private $endDate;
		private $startTime;
		private $endTime;
		
		/** 
		 * Initialisation du Ratard
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

	    /**
	     * Convertir l'identifiant en string pour permettre aux fontions php de faire des comparaisons ou autres
	     *
	     * @return string
	    */
	    public function __toString()
    	{
        	try 
        	{
            	return (string) $this->idEmploye;
        	} 
        	catch (Exception $exception) 
        	{
            	return '';
        	}
    	}

	    // Getters
	    public function getIdCampaign()
	    {
	    	return $this->idCampaign;
	    }

	    public function getIdEntreprise()
	    {
	    	return $this->idEntreprise;
	    }
	    
	    public function getLibelle()
	    {
	    	return $this->libelle;
	    }

	    public function getStartDate()
	    {
	    	return $this->startDate;
	    }
	    
	    public function getEndDate()
	    {
	    	return $this->endDate;
	    }

	    public function getStartTime()
	    {
	    	return $this->startTime;
	    }
	    
	    public function getEndTime()
	    {
	    	return $this->endTime;
	    }
	    
	    // Setters
	    public function setIdCampaign($idCampaign)
	    {
	    	$this->idCampaign = $idCampaign;
	    }

	    public function setIdEntreprise($idEntreprise)
	    {
	    	$this->idEntreprise = $idEntreprise;
	    }

	    public function setLibelle($libelle)
	    {
	    	$this->libelle = $libelle;
	    }
	    
	    public function setStartDate($startDate)
	    {
	    	$this->startDate = $startDate;
	    }
	    
	    public function setEndDate($endDate)
	    {
	    	$this->endDate = $endDate;
	    }
	    
	    public function setStartTime($startTime)
	    {
	    	$this->startTime = $startTime;
	    }
	    
	    public function setEndTime($endTime)
	    {
	    	$this->endTime = $endTime;
	    }
	}