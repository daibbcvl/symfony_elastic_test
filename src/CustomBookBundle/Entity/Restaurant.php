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


//    /**
//     * @ORM\OneToOne(targetEntity="Location", inversedBy="restaurant")
//     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
//     */
//    protected $location;

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


    public function setAddress($address){
       $this->address = $address;
    }

    public function getAddress(){
        return $this->address;
    }




}