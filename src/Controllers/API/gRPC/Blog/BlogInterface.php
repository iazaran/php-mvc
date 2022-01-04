<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: blog.proto

namespace Blog;

/**
 * Protobuf type <code>blog.Blog</code>
 */
interface BlogInterface
{
    /**
     * READ all
     *
     * Method <code>index</code>
     *
     * @param \Google\Protobuf\GPBEmpty $request
     * @return \Blog\ListPostsResponse
     */
    public function index(\Google\Protobuf\GPBEmpty $request);

    /**
     * READ one
     *
     * Method <code>show</code>
     *
     * @param \Blog\PostRequest $request
     * @return \Blog\PostResponse
     */
    public function show(\Blog\PostRequest $request);

    /**
     * STORE
     *
     * Method <code>store</code>
     *
     * @param \Blog\PostDataRequest $request
     * @return \Blog\SuccessResponse
     */
    public function store(\Blog\PostDataRequest $request);

    /**
     * UPDATE
     *
     * Method <code>update</code>
     *
     * @param \Blog\PostDataRequest $request
     * @return \Blog\SuccessResponse
     */
    public function update(\Blog\PostDataRequest $request);

    /**
     * DELETE
     *
     * Method <code>delete</code>
     *
     * @param \Blog\PostRequest $request
     * @return \Blog\SuccessResponse
     */
    public function delete(\Blog\PostRequest $request);

}
