<?php
    /** 
	 * Entité Langue
	 *
	 * @author Billy Bam 
	 *
	 * @since 1/12/2022
	*/

namespace Entity;

class CentreInteret{
	private $idCentreInteret;
	private $idCandidat;
	private $categorieCentreInteret;
	private $descriptionCentreInteret;

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
    public function getIdCentreInteret()
    {
    	return $this->idCentreInteret;
    }

    public function getIdCandidat()
    {
    	return $this->idCandidat;
    }

    public function getCategorieCentreInteret()
    {
    	return $this->categorieCentreInteret;
    }

    public function getDescriptionCentreInteret()
    {
    	return $this->descriptionCentreInteret;
    }
    
    //Setter

    public function setIdCentreInteret($idCentreInteret)
    {
    	$this->idCentreInteret = $idCentreInteret;
    }

    public function setIdCandidat($idCandidat)
    {
    	$this->idCandidat = $idCandidat;
    }
    
    public function setCategorieCentreInteret($categorieCentreInteret)
    {
    	$this->categorieCentreInteret = $categorieCentreInteret;
    }
    
    public function setDescriptionCentreInteret($descriptionCentreInteret)
    {
    	$this->descriptionCentreInteret = $descriptionCentreInteret;
    }
    
}