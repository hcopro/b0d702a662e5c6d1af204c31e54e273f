<?php 

	/** 
	 * Entité Logiciel
	 *
	 * @author Billy Bam 
	 *
	 * @since 21/11/2022
	*/

	namespace Entity;

	class Logiciel{
		private $idLogiciel;
		private $idCandidat;
		private $categorieLogiciel;
		private $nomLogiciel;
		private $dateDeSortie;
		private $fonctionnaliteLogiciel;
		private $maitriseLogiciel;
		private $photoLogiciel;

		public function __construct($data = array())
		{
			if(!empty($data)){
				$this->hydrate($data);
			}
		}

		public function hydrate($data)
		{
			foreach ($data as $attribut => $data) {
	            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
	            if (is_callable(array($this, $method))) {
	                $this->$method($data);
	            }
	        }
		}

		public function toArray()
		{
			return get_object_vars($this);
		}

		//Getter
		public function getIdLogiciel()
		{
			return $this->idLogiciel;
		}

		public function getIdCandidat()
	    {
	    	return $this->idCandidat;
	    }

		public function getCategorieLogiciel()
		{
			return $this->categorieLogiciel;
		}

		public function getNomLogiciel()
		{
			return $this->nomLogiciel;
		}

		public function getDateDeSortie()
		{
			return $this->dateDeSortie;
		}

		public function getFonctionnaliteLogiciel()
		{
			return $this->fonctionnaliteLogiciel;
		}

		public function getMaitriseLogiciel()
		{
			return $this->maitriseLogiciel;
		}

		public function getPhotoLogiciel()
		{
			return $this->photoLogiciel;
		}

		//Setter
		public function setIdLogiciel($idLogiciel)
		{
			$this->idLogiciel = $idLogiciel;
		}

		public function setIdCandidat($idCandidat)
	    {
	    	$this->idCandidat = $idCandidat;
	    }

		public function setCategorieLogiciel($categorieLogiciel)
		{
			$this->categorieLogiciel = $categorieLogiciel;
		}

		public function setNomLogiciel($nomLogiciel)
		{
			$this->nomLogiciel = $nomLogiciel;
		}

		public function setDateDeSortie($dateDeSortie)
		{
			$this->dateDeSortie = $dateDeSortie;
		}

		public function setFonctionnaliteLogiciel($fonctionnaliteLogiciel)
		{
			$this->fonctionnaliteLogiciel = $fonctionnaliteLogiciel;
		}

		public function setMaitriseLogiciel($maitriseLogiciel)
		{
			$this->maitriseLogiciel = $maitriseLogiciel;
		}
		
		public function setPhotoLogiciel($photoLogiciel)
		{
			$this->photoLogiciel = $photoLogiciel;
		}
	}