<?php 
	
	/** 
	 * Entité Entreprise
	 *
	 * @author Voahirana 
	 *
	 * @since 25/09/19
	 */

	namespace Entity;

	class Entreprise
	{
		private $idEntreprise;
		private $idCompte; 
		private $logo; 
		private $nom; 
		private $secteurActivite; 
		private $description; 
		private $adresse;
		private $ville;
		private $nif; 
		private $stat; 
		private $rcs;
		private $nomRepresentant;
		private $qualiteRepresentant;
		private $photoRepresentant;
		private $contact; 
		private $email;
		private $contactRh;

		/** 
		 * Initialisation d'un Entreprise
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
		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getIdCompte()
		{
			return $this->idCompte;
		}

		public function getLogo()
		{
			return $this->logo;
		}

		public function getNom()
		{
			return $this->nom;
		}

		public function getSecteurActivite()
		{
			return $this->secteurActivite;
		}

		public function getDescription()
		{
			return $this->description;
		}

		public function getAdresse()
		{
			return $this->adresse;
		}

		public function getVille()
		{
			return $this->ville;
		}

		public function getNif()
		{
			return $this->nif;
		}

		public function getStat()
		{
			return $this->stat;
		}

		public function getRcs()
		{
			return $this->rcs;
		}

		public function getNomRepresentant()
		{
			return $this->nomRepresentant;
		}

		public function getQualiteRepresentant()
		{
			return $this->qualiteRepresentant;
		}

		public function getPhotoRepresentant()
		{
			return $this->photoRepresentant;
		}

		public function getContact()
		{
			return $this->contact;
		}

		public function getEmail()
		{
			return $this->email;
		}

		public function getContactRh()
		{
			return $this->contactRh;
		}

	// Seters
		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setIdCompte($idCompte)
		{
			$this->idCompte = $idCompte;
		}

		public function setLogo($logo)
		{
			$this->logo = $logo;
		}

		public function setNom($nom)
		{
			$this->nom = $nom;
		}

		public function setSecteurActivite($secteurActivite)
		{
			$this->secteurActivite = $secteurActivite;
		}

		public function setDescription($description)
		{
			$this->description = $description;
		}

		public function setAdresse($adresse)
		{
			$this->adresse = $adresse;
		}

		public function setVille($ville)
		{
			$this->ville = $ville;
		}

		public function setNif($nif)
		{
			$this->nif = $nif;
		}

		public function setStat($stat)
		{
			$this->stat = $stat;
		}

		public function setRcs($rcs)
		{
			$this->rcs = $rcs;
		}

		public function setNomRepresentant($nomRepresentant)
		{
			$this->nomRepresentant = $nomRepresentant;
		}

		public function setQualiteRepresentant($qualiteRepresentant)
		{
			$this->qualiteRepresentant = $qualiteRepresentant;
		}

		public function setPhotoRepresentant($photoRepresentant)
		{
			$this->photoRepresentant = $photoRepresentant;
		}

		public function setContact($contact)
		{
			$this->contact = $contact;
		}

		public function setEmail($email)
		{
			$this->email = $email;
		}

		public function setContactRh($contactRh)
		{
			$this->contactRh = $contactRh;
		}


	}