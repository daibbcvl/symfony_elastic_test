<?php

namespace CustomBookBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Location
{

    /**
     * @Assert\Regex(pattern="/\d+/", message="Please input a valid number")
     */
    private $lat;

    /**
     * @Assert\NotBlank()
     */
    private $lon;





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

//    /**
//     * @ORM\OneToOne(targetEntity="Restaurant")
//     */
//    private $restaurant;






}