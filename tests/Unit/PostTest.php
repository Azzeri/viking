<?php

namespace Tests\Unit;

use App\Models\Privilege;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{ // DODATKOWY ASSERT REDIRECTÓW, POPRAWIĆ TE STATUSY, PODMIENIC STRINGI NA ROUTY
    use RefreshDatabase;

    private const URL_POSTS_ADMIN = '/admin/posts/';
    private const URL_POSTS_PUBLIC = '/news/';

    /**
     * @test
     * @dataProvider displayDataMember
     * @author mwaloszczyk
     */
    public function test_user_can_see_posts_in_admin_panel(
        $privilege,
        $responseStatus
    ) {
        // Arrange
        $this->arrangeUser($privilege);

        // Act
        $response = $this->get(self::URL_POSTS_ADMIN);

        // Assert
        $response->assertStatus($responseStatus);
    }

    /**
     * @test
     * @dataProvider displayDataMember
     * @author mwaloszczyk
     */
    public function test_user_can_see_post_details_in_admin_panel(
        $privilege,
        $responseStatus
    ) {
        // Arrange
        $this->arrangeUser($privilege);
        $post = Post::Factory()->create();

        // Act
        $response = $this->get(self::URL_POSTS_ADMIN . $post->id);

        // Assert
        $response->assertStatus($responseStatus);
    }

    /**
     * @test
     * @dataProvider displayDataGuest
     * @author mwaloszczyk
     */
    public function test_user_can_see_posts_in_public_section(
        $privilege,
        $responseStatus
    ) {
        // Arrange
        $this->arrangeUser($privilege);

        // Act
        $response = $this->get(self::URL_POSTS_PUBLIC);

        // Assert
        $response->assertStatus($responseStatus);
    }

    /**
     * @test
     * @dataProvider displayDataGuest
     * @author mwaloszczyk
     */
    public function test_user_can_see_post_details_in_public_section(
        $privilege,
        $responseStatus
    ) {
        // Arrange
        $this->arrangeUser($privilege);
        $post = Post::Factory()->create();

        // Act
        $response = $this->get(self::URL_POSTS_PUBLIC . $post->id);

        // Assert
        $response->assertStatus($responseStatus);
    }

    /**
     * @test
     * @dataProvider manipulateData
     * @author mwaloszczyk
     */
    public function test_user_can_create_post(
        $privilege,
        $responseStatus,
        $redirectURL,
        $successExpected
    ) {
        // Arrange
        $this->arrangeUser($privilege);

        $requestData = [
            'title' => 'test',
            'body' => 'test',
            'resource_link' => 'test',
            'user_id' => 1,
            'photo_path' => 'path'
        ];

        // Act
        $response = $this->post(route('admin.posts.store'), $requestData);

        // Assert
        $response->assertRedirect($redirectURL);
        $response->assertStatus($responseStatus);

        if ($successExpected) {
            $this->assertDatabaseHas('posts', [
                'title' => 'test',
                'body' => 'test',
                'resource_link' => 'test',
            ]);
        } else {
            $this->assertDatabaseMissing('posts', [
                'title' => 'test',
                'body' => 'test',
                'resource_link' => 'test',
            ]);
        }
    }

    /**
     * @test
     * @dataProvider manipulateData
     * @author mwaloszczyk
     */
    public function test_user_can_update_post(
        $privilege,
        $responseStatus,
        $redirectURL,
        $successExpected
    ) {
        // Arrange
        $this->arrangeUser($privilege);
        $newTitle = 'updated';
        $post = Post::Factory()->create([
            'title' => 'before_update'
        ]);
        $requestData = [
            'title' => $newTitle
        ];

        // Act
        $response = $this->put(route('admin.posts.update',$post->id), $requestData);

        // Assert
        $response->assertRedirect($redirectURL);
        $response->assertStatus($responseStatus);

        if ($successExpected) {
            $this->assertSame($post->title, $newTitle);
        } else {
            $this->assertNotSame($post->title, $newTitle);
        }
    }

    /**
     * @author mwaloszczyk
     */
    public function displayDataMember()
    {
        return [
            'Administrator' => [Privilege::IS_ADMIN, self::STATUS_SUCCESS],
            'Coordinator' => [Privilege::IS_COORDINATOR, self::STATUS_SUCCESS],
            'Guest' => [null, self::STATUS_REDIRECTED],
        ];
    }

    /**
     * @author mwaloszczyk
     */
    public function displayDataGuest()
    {
        return [
            'Administrator' => [
                Privilege::IS_ADMIN, 
                self::STATUS_SUCCESS
            ],
            'Coordinator' => [
                Privilege::IS_COORDINATOR, 
                self::STATUS_SUCCESS
            ],
            'Guest' => [
                null, 
                self::STATUS_SUCCESS
            ],
        ];
    }

    /**
     * @author mwaloszczyk
     */
    public function manipulateData()
    {
        return [
            'Administrator' => [
                Privilege::IS_ADMIN,
                self::STATUS_REDIRECTED,
                self::URL_POSTS_ADMIN,
                true
            ],
            'Coordinator' => [
                Privilege::IS_COORDINATOR,
                self::STATUS_REDIRECTED,
                self::URL_POSTS_ADMIN,
                true
            ],
            'Guest' => [
                null,
                self::STATUS_REDIRECTED,
                self::URL_INDEX,
                false
            ],
        ];
    }
}
