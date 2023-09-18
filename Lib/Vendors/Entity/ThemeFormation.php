<?php 
	
	/** 
	 * Entité ThemeFormation
	 *
	 * @author Toky
	 *
	 * @since 15/09/20
	 */

	namespace Entity;

	class ThemeFormation
	{
		private $idThemeFormation;
		private $idEntreprise;
		private $idSousDomaine;
		private $annee;
		private $theme;
		private $description;
		private $priorite;
		private $statut;
		private $idEmploye;
		
		/** 
		 * Initialisation d'un ThemeFormation
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
		public function getIdThemeFormation()
		{
			return $this->idThemeFormation;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getIdSousDomaine()
		{
			return $this->idSousDomaine;
		}

		public function getAnnee()
		{
			return $this->annee;
		}

		public function getTheme()
		{
			return $this->theme;
		}

		public function getDescription()
		{
			return $this->description;
		}

		public function getPriorite()
		{
			return $this->priorite;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getStringPriorite()
		{
			switch ($this->priorite) {
				case 1:
					return "Pas urgent";
					break;
				case 2:
					return "Important / Pas urgent";
					break;
				case 3:
					return "Important";
					break;
				case 4:
					return "Urgent";
					break;
				default:
					return "N/A";
					break;
			}
		}

	// Seters
		public function setIdThemeFormation($idThemeFormation)
		{
			$this->idThemeFormation = $idThemeFormation;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setIdSousDomaine($idSousDomaine)
		{
			$this->idSousDomaine = $idSousDomaine;
		}

		public function setAnnee($annee)
		{
			$this->annee = $annee;
		}

		public function setTheme($theme)
		{
			$this->theme = $theme;
		}

		public function setDescription($description)
		{
			$this->description = $description;
		}

		public function setPriorite($priorite)
		{
			$this->priorite = $priorite;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}
	}