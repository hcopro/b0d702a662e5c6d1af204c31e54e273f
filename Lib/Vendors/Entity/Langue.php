<?php

    /** 
	 * Entité Langue
	 *
	 * @author Billy Bam 
	 *
	 * @since 17/11/2022
	*/

    namespace Entity;

    class Langue {
    	private $idLangue;
    	private $idCandidat;
    	private $nomLangue;
    	private $niveauEcrit;
    	private $niveauParle;

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

        //GETTER
        public function getIdLangue()
        {
        	return $this->idLangue;
        }

        public function getIdCandidat()
        {
        	return $this->idCandidat;
        }

        public function getNomLangue()
        {
        	return $this->nomLangue;
        }

        public function getNiveauEcrit()
        {
        	return $this->niveauEcrit;
        }

        public function getNiveauParle()
        {
        	return $this->niveauParle;
        }

        //SETTER
        public function setIdLangue($idLangue)
        {
        	$this->idLangue = $idLangue;
        }

        public function setIdCandidat($idCandidat)
        {
        	$this->idCandidat = $idCandidat;
        }

        public function setNomLangue($nomLangue)
        {
        	$this->nomLangue = $nomLangue;
        }

        public function setNiveauEcrit($niveauEcrit)
        {
        	$this->niveauEcrit = $niveauEcrit;
        }

        public function setNiveauParle($niveauParle)
        {
        	$this->niveauParle = $niveauParle;
        }
    }