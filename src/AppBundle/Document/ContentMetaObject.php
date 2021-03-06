<?php
// src/AppBundle/Document/ContentMetaObject.php

namespace AppBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * @ES\Object
 */
class ContentMetaObject
{
    /**
     * @ES\Property(type="string")
     */
    public $lat;

    /**
     * @ES\Property(type="string")
     */
    public $lon;
}