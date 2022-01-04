<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: blog.proto

namespace Blog;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request message for Update method.
 *
 * Generated from protobuf message <code>blog.PostDataRequest</code>
 */
class PostDataRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string category = 1;</code>
     */
    protected $category = '';
    /**
     * Generated from protobuf field <code>string title = 2;</code>
     */
    protected $title = '';
    /**
     * Generated from protobuf field <code>string subtitle = 3;</code>
     */
    protected $subtitle = '';
    /**
     * Generated from protobuf field <code>string body = 4;</code>
     */
    protected $body = '';
    /**
     * Generated from protobuf field <code>int32 position = 5;</code>
     */
    protected $position = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $category
     *     @type string $title
     *     @type string $subtitle
     *     @type string $body
     *     @type int $position
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Blog::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string category = 1;</code>
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Generated from protobuf field <code>string category = 1;</code>
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
     * Generated from protobuf field <code>string title = 2;</code>
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Generated from protobuf field <code>string title = 2;</code>
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
     * Generated from protobuf field <code>string subtitle = 3;</code>
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Generated from protobuf field <code>string subtitle = 3;</code>
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
     * Generated from protobuf field <code>string body = 4;</code>
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Generated from protobuf field <code>string body = 4;</code>
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
     * Generated from protobuf field <code>int32 position = 5;</code>
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Generated from protobuf field <code>int32 position = 5;</code>
     * @param int $var
     * @return $this
     */
    public function setPosition($var)
    {
        GPBUtil::checkInt32($var);
        $this->position = $var;

        return $this;
    }

}
