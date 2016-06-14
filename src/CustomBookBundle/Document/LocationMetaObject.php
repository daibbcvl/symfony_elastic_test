<?php
// src/AppBundle/Document/ContentMetaObject.php

namespace CustomBookBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * @ES\Object
 */
class LocationMetaObject
{
    /**
     * @ES\Property(type="float")
     */
    public $lat;

    /**
     * @ES\Property(type="string")
     */
    public $lon;
}