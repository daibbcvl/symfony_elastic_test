<?php
// src/AppBundle/Document/Content.php

namespace AppBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\ElasticsearchBundle\Collection;

/**
 * @ES\Document(type="content")
 */
class Content
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
    public $title;
}