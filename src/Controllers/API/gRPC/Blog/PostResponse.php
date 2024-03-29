<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: blog.proto

namespace Blog;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * ----------------- Responses -----------------
 * A resource.
 *
 * Generated from protobuf message <code>blog.PostResponse</code>
 */
class PostResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>int64 id = 1;</code>
     */
    protected $id = 0;
    /**
     * Generated from protobuf field <code>string category = 2;</code>
     */
    protected $category = '';
    /**
     * Generated from protobuf field <code>string title = 3;</code>
     */
    protected $title = '';
    /**
     * Generated from protobuf field <code>string slug = 4;</code>
     */
    protected $slug = '';
    /**
     * Generated from protobuf field <code>string subtitle = 5;</code>
     */
    protected $subtitle = '';
    /**
     * Generated from protobuf field <code>string body = 6;</code>
     */
    protected $body = '';
    /**
     * Generated from protobuf field <code>int32 position = 7;</code>
     */
    protected $position = 0;
    /**
     * Generated from protobuf field <code>string created_at = 8;</code>
     */
    protected $created_at = '';
    /**
     * Generated from protobuf field <code>string updated_at = 9;</code>
     */
    protected $updated_at = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $id
     *     @type string $category
     *     @type string $title
     *     @type string $slug
     *     @type string $subtitle
     *     @type string $body
     *     @type int $position
     *     @type string $created_at
     *     @type string $updated_at
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Blog::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>int64 id = 1;</code>
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Generated from protobuf field <code>int64 id = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setId($var)
    {
        GPBUtil::checkInt64($var);
        $this->id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string category = 2;</code>
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Generated from protobuf field <code>string category = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setCategory($var)
    {
        GPBUtil::checkString($var, True);
        $this->category = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string title = 3;</code>
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Generated from protobuf field <code>string title = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setTitle($var)
    {
        GPBUtil::checkString($var, True);
        $this->title = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string slug = 4;</code>
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Generated from protobuf field <code>string slug = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setSlug($var)
    {
        GPBUtil::checkString($var, True);
        $this->slug = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string subtitle = 5;</code>
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Generated from protobuf field <code>string subtitle = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setSubtitle($var)
    {
        GPBUtil::checkString($var, True);
        $this->subtitle = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string body = 6;</code>
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Generated from protobuf field <code>string body = 6;</code>
     * @param string $var
     * @return $this
     */
    public function setBody($var)
    {
        GPBUtil::checkString($var, True);
        $this->body = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 position = 7;</code>
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Generated from protobuf field <code>int32 position = 7;</code>
     * @param int $var
     * @return $this
     */
    public function setPosition($var)
    {
        GPBUtil::checkInt32($var);
        $this->position = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string created_at = 8;</code>
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Generated from protobuf field <code>string created_at = 8;</code>
     * @param string $var
     * @return $this
     */
    public function setCreatedAt($var)
    {
        GPBUtil::checkString($var, True);
        $this->created_at = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string updated_at = 9;</code>
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Generated from protobuf field <code>string updated_at = 9;</code>
     * @param string $var
     * @return $this
     */
    public function setUpdatedAt($var)
    {
        GPBUtil::checkString($var, True);
        $this->updated_at = $var;

        return $this;
    }

}

