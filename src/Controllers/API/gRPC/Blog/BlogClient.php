<?php
// GENERATED CODE -- DO NOT EDIT!

// Original file comments:
// Copyright 2016 Google Inc. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//
// //////////////////////////////////////////////////////////////////////////////
//
namespace Blog;

/**
 */
class BlogClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * READ all
     * @param \Google\Protobuf\GPBEmpty $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Blog\ListPostsResponse
     */
    public function Index(\Google\Protobuf\GPBEmpty $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/blog.Blog/Index',
        $argument,
        ['\Blog\ListPostsResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * READ one
     * @param \Blog\PostRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Blog\PostResponse
     */
    public function Show(\Blog\PostRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/blog.Blog/Show',
        $argument,
        ['\Blog\PostResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * STORE
     * @param \Blog\PostStoreRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Blog\SuccessResponse
     */
    public function Store(\Blog\PostStoreRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/blog.Blog/Store',
        $argument,
        ['\Blog\SuccessResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * UPDATE
     * @param \Blog\PostUpdateRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Blog\SuccessResponse
     */
    public function Update(\Blog\PostUpdateRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/blog.Blog/Update',
        $argument,
        ['\Blog\SuccessResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * DELETE
     * @param \Blog\PostRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Blog\SuccessResponse
     */
    public function Delete(\Blog\PostRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/blog.Blog/Delete',
        $argument,
        ['\Blog\SuccessResponse', 'decode'],
        $metadata, $options);
    }

}
