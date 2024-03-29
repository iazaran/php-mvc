<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: blog.proto

namespace Blog;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * ----------------- Requests -----------------
 * Request message for Show/Delete method.
 *
 * Generated from protobuf message <code>blog.PostRequest</code>
 */
class PostRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string slug = 1;</code>
     */
    protected $slug = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $slug
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Blog::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string slug = 1;</code>
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Generated from protobuf field <code>string slug = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setSlug($var)
    {
        GPBUtil::checkString($var, True);
        $this->slug = $var;

        return $this;
    }

}

