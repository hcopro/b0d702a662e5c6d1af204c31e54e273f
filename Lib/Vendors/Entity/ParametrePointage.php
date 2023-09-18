<?php 
	
	/** 
	 * Entité ParametrePointage
	 *
	 * @author Toky
	 *
	 * @since 29/10/20
	 */

	namespace Entity;

	class ParametrePointage
	{
		private $idParametrePointage;
		private $idEntreprise;
		private $arretActive;
		private $heureArret;
		private $listIp;
		private $heureDebut;
		private $isFixedTime;
		private $receiveNotif;
		
		
		/** 
		 * Initialisation d'un paramètre de pointage d'une entreprise
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
		public function getIdParametrePointage()
		{
			return $this->idParametrePointage;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getArretActive()
		{
			return $this->arretActive;
		}

		public function getHeureArret()
		{
			return $this->heureArret;
		}

		public function getListIp()
		{
			return unserialize($this->listIp);
		}

		public function getHeureDebut()
		{
			return $this->heureDebut;
		}

		public function getIsFixedTime()
		{
			return $this->isFixedTime;
		}

		public function getReceiveNotif()
		{
			return $this->receiveNotif;
		}

	// Seters
		public function setIdParametrePointage($idParametrePointage)
		{
			$this->idParametrePointage = $idParametrePointage;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setArretActive($arretActive)
		{
			$this->arretActive = $arretActive;
		}

		public function setHeureArret($heureArret)
		{
			$this->heureArret = $heureArret;
		}

		public function setListIp($listIp)
		{
			$this->listIp = $listIp;
		}
		
		public function setHeureDebut($heureDebut)
		{
			$this->heureDebut = $heureDebut;
		}
		
		public function setIsFixedTime($isFixedTime)
		{
			$this->isFixedTime = $isFixedTime;
		}
		
		public function setReceiveNotif($receiveNotif)
		{
			$this->receiveNotif = $receiveNotif;
		}
	}