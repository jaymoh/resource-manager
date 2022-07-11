<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class PostRepository extends AbstractRepository implements PostRepositoryInterface
{
    /**
     * Get all posts.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Post[]
     */
    public function getAllPosts()
    {
        return Post::all();
    }

    /**
     * Get paginated posts.
     *
     * @param array $search
     * @return LengthAwarePaginator
     */
    public function indexPaginatedPosts(array $search): LengthAwarePaginator
    {
        $query = Post::query();

        $filters = preg_split('/\s+/', $search['searchQuery'] ?? '', -1, PREG_SPLIT_NO_EMPTY);

        if (count($filters) > 0) {
            $query->where(function (Builder $q) use ($filters) {
                foreach ($filters as $filter) {
                    $q->where(function (Builder $qq) use ($filter) {
                        $qq->where('title', 'LIKE', "%{$filter}%");
                    });
                }
            });
        }

        // add relationships to be included if it exists
        if ($search['includeRelations'] ?? false) {
            $includes = $search['includeRelations'];
            $addInclude = [];
            foreach ($includes as $include) {
                if (in_array($include, Post::availableRelationships, true)) {
                    $addInclude[] = $include;
                }
            }
            $query->with($addInclude);
        }

        // order by has to be the last one
        if (($search['sortBy']?? false) && $this->allowedSortDir($search['sortDir'])) {
            if (Schema::hasColumn('posts', $search['sortBy']))
                $query->orderBy($search['sortBy'], $search['sortDir']);
        }

        return $query->paginate($search['perPage'] ?? 10);
    }

    /**
     * Get post by id.
     *
     * @param $id
     * @return Post|null
     */
    public function getPostById($id): ?Post
    {
        return Post::with([ 'pdfPost', 'linkPost', 'htmlPost'])->find($id);
    }

    public function deletePost($id): ?bool
    {
        $task = Post::find($id);

        if (!$task) {
            return null;
        }

        $task->delete();

        return true;
    }

    /**
     * Create Link Post.
     *
     * @param array $data
     * @return Post|null
     */
    public function createLinkPost(array $data): ?Post
    {
        $post = Post::create([
            'title' => $data['title'],
            'post_type' => Post::POST_TYPE_LINK
        ]);

        // attach url
        $post->linkPost()->create([
            'url' => $data['url'],
            'open_in_new_tab' => $data['open_in_new_tab'] ?? false
        ]);


        return $post->load(['linkPost']);
    }

    /**
     * Update post Link Post.
     *
     * @param $id
     * @param array $data
     * @return Post|null
     */
    public function updateLinkPost($id, array $data): ?Post
    {
        $post = Post::find($id);

        if (!$post) {
            return null;
        }

        $post->update([
            'title' => $data['title']
        ]);

        // attach url
        $post->linkPost()->update([
            'url' => $data['url'],
            'open_in_new_tab' => $data['open_in_new_tab'] ?? false
        ]);

        return $post->load([ 'linkPost']);
    }

    /**
     * Create HTML Post.
     *
     * @param array $data
     * @return Post|null
     */
    public function createPdfPost(array $data): ?Post
    {
        $post = Post::create([
            'title' => $data['title'],
            'post_type' => Post::POST_TYPE_PDF
        ]);

        // attach pdf
        $post->pdfPost()->create([
            'pdf_path' => $data['pdf_path']
        ]);

        return $post->load([ 'pdfPost']);
    }

    /**
     * Update PDF Post.
     *
     * @param $id
     * @param array $data
     * @return Post|null
     */
    public function updatePdfPost($id, array $data) : ?Post
    {
        $post = Post::find($id);

        if (!$post) {
            return null;
        }

        $post->update([
            'title' => $data['title']
        ]);

        // attach pdf
        $post->pdfPost()->update([
            'pdf_path' => $data['pdf_path']
        ]);

        return $post->load([ 'pdfPost']);
    }

    /**
     * Create Post
     *
     * @param array $data
     * @return Post|null
     */
    public function createHtmlPost(array $data): ?Post
    {
        $post = Post::create([
            'title' => $data['title'],
            'post_type' => Post::POST_TYPE_HTML
        ]);

        // attach html
        $post->htmlPost()->create([
            'description' => $data['description']
        ]);

        return $post->load([ 'htmlPost']);
    }

    /**
     * Update Html Post
     *
     * @param $id
     * @param array $data
     * @return Post|null
     */
    public function updateHtmlPost($id, array $data): ?Post
    {
        $post = Post::find($id);

        if (!$post) {
            return null;
        }

        $post->update([
            'title' => $data['title']
        ]);

        $post->htmlPost()->update([
            'description' => $data['description']
        ]);

        return $post->load([ 'htmlPost']);
    }
}

