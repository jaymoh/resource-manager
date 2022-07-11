<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHtmlPostRequest;
use App\Http\Requests\StoreLinkPostRequest;
use App\Http\Requests\StorePdfPostRequest;
use App\Http\Resources\PostResource;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PostController extends AbstractController
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $searchData = request()->toArray();

        $searchData['perPage'] = $searchData['perPage'] ?? $this->perPage;

        return PostResource::collection($this->postRepository->indexPaginatedPosts($searchData));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return PostResource
     */
    public function show(int $id): PostResource
    {
        $post = $this->postRepository->getPostById($id);

        if (!$post) {
            throw new NotFoundHttpException('Post not found');
        }

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        if (!$this->postRepository->deletePost($id)) {
            throw new UnprocessableEntityHttpException('Post cannot be deleted!');
        }

        return $this->transformResponse([], 'Post deleted successfully');
    }

    /**
     * Store Link Post.
     *
     * @param StoreLinkPostRequest $request
     *
     * @return PostResource
     */
    public function storeLink(StoreLinkPostRequest $request): PostResource
    {
        $data = $request->validated();

        $post = $this->postRepository->createLinkPost($data);

        return new PostResource($post);
    }

    /**
     * Update Link Post.
     *
     * @param int $id
     * @param StoreLinkPostRequest $request
     */
    public function updateLink(int $id, StoreLinkPostRequest $request): PostResource
    {
        $data = $request->validated();

        $post = $this->postRepository->updateLinkPost($id, $data);

        if (!$post) {
            throw new NotFoundHttpException('Post not found');
        }

        return new PostResource($post);
    }


    /**
     * Store PDF Post.
     *
     * @param StorePdfPostRequest $request
     * @param PostService $postService
     *
     * @return PostResource
     */
    public function storePdf(StorePdfPostRequest $request, PostService $postService): PostResource
    {
        $data = $request->validated();

        $data['pdf_path'] = $postService->saveUploadedPdfToStorage();

        $post = $this->postRepository->createPdfPost($data);

        return new PostResource($post);
    }

    /**
     * Update PDF Post.
     *
     * @param int $id
     * @param StorePdfPostRequest $request
     */
    public function updatePdf(int $id, StorePdfPostRequest $request, PostService $postService): PostResource
    {
        $data = $request->validated();

        $post = $this->postRepository->getPostById($id);

        if (!$post) {
            throw new NotFoundHttpException('Post not found');
        }

        // delete previous file
        $postService->deletePdfFromStorage($post);

        $data['pdf_path'] = $postService->saveUploadedPdfToStorage();

        $post = $this->postRepository->updatePdfPost($id, $data);

        return new PostResource($post);
    }

    /**
     * @param StoreHtmlPostRequest $request
     * @param PostService $postService
     * @return PostResource
     */

    public function storeHtml(StoreHtmlPostRequest $request, PostService $postService): PostResource
    {
        $data = $request->validated();

        $post = $this->postRepository->createHtmlPost($data);

        // save html to file
        $postService->saveHtmlSnippetToStorage($post);

        return new PostResource($post);
    }

    /**
     * Update Html Post
     *
     */
    public function updateHtml(int $id, StoreHtmlPostRequest $request, PostService $postService): PostResource
    {
        $data = $request->validated();

        $post = $this->postRepository->updateHtmlPost($id, $data);

        if (!$post) {
            throw new NotFoundHttpException('Post not found');
        }

        // save html to file
        $postService->saveHtmlSnippetToStorage($post);

        return new PostResource($post);
    }

    /**
     * Download pdf file
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse/|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadPdfPost($id)
    {
        $link = URL::to('/404');

        $pdfPost = $this->postRepository->getPostById($id);

        if (!$pdfPost || !$pdfPost->pdfPost) {
            return redirect($link);
        }

        return response()->streamDownload(function () use ($pdfPost) {
            echo file_get_contents(storage_path('app/') . $pdfPost->getPdfStoragePath());
        }, basename($pdfPost->title . '.pdf'));
    }
}
