<?php

namespace CustomBookBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Restaurant
{
    /**
     * @Assert\NotBlank(message="Please input Restaurant name")
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Your restaurant name must be at least {{ limit }} characters long",
     *      maxMessage = "Your restaurant name cannot be longer than {{ limit }} characters"
     * )
     */
    private $name;

    /**
     * @Assert\Regex(pattern="/\d+/", message="Please input a valid number")
     */
    private $lat;

    /**
     * @Assert\NotBlank()
     */
    private $lon;

    /**
     * @Assert\NotBlank()
     */
    private $address;


    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }

    public function setLat($lat){
        $this->lat = $lat;
    }

    public function getLat(){
        return $this->lat;
    }


    public function setLon($lon){
        $this->lon = $lon;
    }

    public function getLon(){
        return $this->lon;
    }

    public function setAddress($address){
       $this->address = $address;
    }

    public function getAddress(){
        return $this->address;
    }




}