<?php

namespace Tests\Unit\Http\Controllers;

use Tests\Unit\Http\AbstractHttpTest;

class PostControllerTest extends AbstractHttpTest
{
    /**
     * Test posts index returns array of posts with a specific format
     */
    public function testPostsIndex()
    {
        // First create something in the database that can be fetched
        $title = 'Kubernetes.';
        $url = 'https://kubernetes.io/';

        $requestBody = [
            'title' => $title,
            'url' => $url,
        ];

        $route = route('posts.storeLink');

        $response = $this
            ->json('POST', $route, $requestBody);


        // Now we can try to fetch it from the database
        $perPage = 10;

        $requestBody = [
            'perPage' => $perPage,
            'includeRelations' => ["pdfPost", "linkPost", "htmlPost"],
        ];

        $route = route('posts.index');

        $response = $this->json('GET', $route, $requestBody);

        $response->assertStatus(200)
            ->assertJsonStructure(require __DIR__ . '/responses/posts/posts-index-response.php');
    }

    /**
     * Test can store link post
     *
     * @return void
     */
    public function testStoreLinkPostSuccess()
    {
        $title = 'Kubernetes.';
        $url = 'https://kubernetes.io/';

        $requestBody = [
            'title' => $title,
            'url' => $url,
        ];

        $route = route('posts.storeLink');

        $response = $this
            ->json('POST', $route, $requestBody);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', $title)
            ->assertJsonPath('data.relationships.link.url', $url)
            ->assertJsonPath('data.relationships.link.open_in_new_tab', false);
    }

    /**
     * Test can store link post open in new tab
     *
     * @return void
     */
    public function testStoreLinkPostOpenInNewTabSuccess()
    {
        $title = 'Kubernetes.';
        $url = 'https://kubernetes.io/';

        $requestBody = [
            'title' => $title,
            'url' => $url,
            'open_in_new_tab' => true,
        ];

        $route = route('posts.storeLink');

        $response = $this
            ->json('POST', $route, $requestBody);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', $title)
            ->assertJsonPath('data.relationships.link.url', $url)
            ->assertJsonPath('data.relationships.link.open_in_new_tab', true);
    }

    /**
     * test link post store with invalid data should fail
     */
    public function testLinkPostStoreInvalid(): void
    {
        $requestBody = [];

        // this end point stores and returns the saved model as json
        $route = route('posts.storeLink');

        $response = $this->json('POST', $route, $requestBody);

        $response
            ->assertStatus(422)
            ->assertJson(require __DIR__ . '/responses/posts/posts-link-store-invalid-response.php');
    }

    /**
     * Test can store html post
     *
     * @return void
     */
    public function testStoreHtmlPostSuccess()
    {
        $title = 'HTML Example.';
        $description = 'Learn To code with the world\'s most popular programming language.';
        $html = '<!DOCTYPE html>
                <html>
                <title>HTML Tutorial</title>
                <body>

                <h1>This is a heading</h1>
                <p>This is a paragraph.</p>

                </body>
                </html>';

        $requestBody = [
            'title' => $title,
            'description' => $description,
            'html_snippet' => $html,
        ];

        $route = route('posts.storeHtml');

        $response = $this
            ->json('POST', $route, $requestBody);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', $title)
            ->assertJsonPath('data.relationships.html.description', $description)
            ->assertJsonPath('data.relationships.html.html_snippet', $html);
    }

    /**
     * test html post store with invalid data should fail
     */
    public function testHtmlPostStoreInvalid(): void
    {
        $requestBody = [];

        // this end point stores and returns the saved model as json
        $route = route('posts.storeHtml');

        $response = $this->json('POST', $route, $requestBody);

        $response
            ->assertStatus(422)
            ->assertJson(require __DIR__ . '/responses/posts/posts-html-store-invalid-response.php');
    }

    /**
     * test post delete success
     */
    public function testPostDeleteSuccess()
    {
        // First create a post to delete
        $title = 'Kubernetes.';
        $url = 'https://kubernetes.io/';

        $requestBody = [
            'title' => $title,
            'url' => $url,
        ];

        $route = route('posts.storeLink');

        $response = $this
            ->json('POST', $route, $requestBody);

        $postId = $response->json('data.id');

        $route = route('posts.destroy', ['post' => $postId]);

        // now delete the post
        $response = $this->json('DELETE', $route);

        $response->assertStatus(200)
            ->assertJsonFragment(["message" => "Post deleted successfully"]);

        // try to show the deleted post and assert it is not found
        $showRoute = route('posts.show', ['post' => $postId]);
        $response = $this->json('GET', $showRoute);

        $response->assertStatus(404)
            ->assertJsonFragment(["message" => "Post not found"]);
    }
}
