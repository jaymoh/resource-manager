<?php

namespace App\Interfaces;

interface PostRepositoryInterface
{
    public function getAllPosts();
    public function indexPaginatedPosts(array $search);
    public function getPostById($id);
    public function deletePost($id);
    public function createLinkPost(array $data);
    public function updateLinkPost($id, array $data);
    public function createPdfPost(array $data);
    public function updatePdfPost($id, array $data);
    public function createHtmlPost(array $data);
    public function updateHtmlPost($id, array $data);
}
