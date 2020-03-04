<?php
class Module{
	private $name;
	private $number;
	private $description;
	private $type;
	private $image;
	private $temperature;
	private $time;
	private $nombre;
	private $state;

	public function __construct($name, $number, $description, $type, $image, $temperature, $time, $nombre, $state){

		$this->name = $name;
		$this->number = $number;
		$this->description = $description;
		$this->type = $type;
		$this->image = $image;
		$this->temperature = $temperature;
		$this->time = $time;
		$this->nombre = $nombre;
		$this->state = $state;
	}


	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getNumber()
	{
		return $this->number;
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @param mixed $number
	 */
	public function setNumber($number)
	{
		$this->number = $number;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getImage()
	{
		return $this->image;
	}

	/**
	 * @param mixed $image
	 */
	public function setImage($image)
	{
		$this->image = $image;
	}

	/**
	 * @return mixed
	 */
	public function getTemperature()
	{
		return $this->temperature;
	}

	/**
	 * @param mixed $temperature
	 */
	public function setTemperature($temperature)
	{
		$this->temperature = $temperature;
	}

	/**
	 * @return mixed
	 */
	public function getTime()
	{
		return $this->time;
	}

	/**
	 * @param mixed $time
	 */
	public function setTime($time)
	{
		$this->time = $time;
	}

	/**
	 * @return mixed
	 */
	public function getNombre()
	{
		return $this->nombre;
	}

	/**
	 * @param mixed $nombre
	 */
	public function setNombre($nombre)
	{
		$this->nombre = $nombre;
	}

	/**
	 * @return mixed
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * @param mixed $state
	 */
	public function setState($state)
	{
		$this->state = $state;
	}


}

?>
