<?php

	/**
	 * Entité ParametrePaie
	 *
	 * @author Toky
	 *
	 * @since 21/10/20
	 */

	namespace Entity;

	class ParametrePaie
	{
		private $idParametrePaie;
		private $idEntreprise;
		private $minimumDePerception;
		private $chargeFamiliale;
		private $salaireMinimumEmbauche;
		private $seuilImposition;
		private $limiteAllocationConge;
		private $template;
		private $modeIrsa;
		private $typeArrondissement;

		/**
		 * Initialisation d'un paramètre de paie employe
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
		public function getIdParametrePaie()
		{
			return $this->idParametrePaie;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getMinimumDePerception()
		{
			return $this->minimumDePerception;
		}

		public function getChargeFamiliale()
		{
			return $this->chargeFamiliale;
		}

		public function getSalaireMinimumEmbauche()
		{
			return $this->salaireMinimumEmbauche;
		}

		public function getSeuilImposition()
		{
			return $this->seuilImposition;
		}

		public function getLimiteAllocationConge()
		{
			return $this->limiteAllocationConge;
		}

		public function getTemplate()
		{
			return $this->template;
		}

		public function getModeIrsa()
		{
			return $this->modeIrsa;
		}

		public function getTypeArrondissement()
		{
			return $this->typeArrondissement;
		}

	// Seters
		public function setIdParametrePaie($idParametrePaie)
		{
			$this->idParametrePaie = $idParametrePaie;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setMinimumDePerception($minimumDePerception)
		{
			$this->minimumDePerception = $minimumDePerception;
		}

		public function setChargeFamiliale($chargeFamiliale)
		{
			$this->chargeFamiliale = $chargeFamiliale;
		}

		public function setSalaireMinimumEmbauche($salaireMinimumEmbauche)
		{
			$this->salaireMinimumEmbauche = $salaireMinimumEmbauche;
		}

		public function setSeuilImposition($seuilImposition)
		{
			$this->seuilImposition = $seuilImposition;
		}

		public function setLimiteAllocationConge($limiteAllocationConge)
		{
			$this->limiteAllocationConge = $limiteAllocationConge;
		}

		public function setTemplate($template)
		{
			$this->template = $template;
		}

		public function setModeIrsa($modeIrsa)
		{
			$this->modeIrsa = $modeIrsa;
		}

		public function setTypeArrondissement($typeArrondissement)
		{
			$this->typeArrondissement = $typeArrondissement;
		}
	}
