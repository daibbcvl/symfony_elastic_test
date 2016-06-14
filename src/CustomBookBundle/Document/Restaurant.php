<?php

namespace CustomBookBundle\Document;


use CustomBookBundle\CustomBookBundle;
use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\ElasticsearchBundle\Collection\Collection;

/**
 * @ES\Document(type="restaurant")
 */
class Restaurant
{
    /**
     * @var string
     *
     * @ES\Id()
     */
    public $id;

    /**
     * @ES\Property(type="string")
     */
    public $name;


    /**
     * @var LocationMetaObject
     *
     * @ES\Embedded(class="CustomBookBundle:LocationMetaObject",multiple=true)
     */
    public $location;


    /**
     * @ES\Property(type="string")
     */
    public $address;


    /**
     * Initialize collection.
     */
    public function __construct()
    {
        $this->location = new Collection();
    }

}