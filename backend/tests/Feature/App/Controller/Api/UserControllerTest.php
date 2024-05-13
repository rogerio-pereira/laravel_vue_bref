<?php

namespace Tests\Feature\App\Controller\Api;

use App\Jobs\ResizeImage;
use App\Jobs\ResizeImageJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_route_user_should_return_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/api/user')
            ->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'picture' => null,
            ]);
    }
    
    public function test_route_user_should_redirect_when_guest()
    {
        $user = User::factory()->create();

        $this->get('/api/user')
            ->assertStatus(302);
    }

    public function test_can_update_self_data()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $user1Data = $user1->toArray();
        $user2Data = $user2->toArray();

        $this->assertDatabaseHas('users', [
            'id' => $user1Data['id'],
            'name' => $user1Data['name'],
            'email' => $user1Data['email'],
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user2Data['id'],
            'name' => $user2Data['name'],
            'email' => $user2Data['email'],
        ]);

        //Update User 1
        $this->actingAs($user1);
        $this->put('/api/user/update', [
            'name' => 'User 1 Name',
            'email' => 'user1@email.com'
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $user1Data['id'],
            'name' => $user1Data['name'],
            'email' => $user1Data['email'],
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user1Data['id'],
            'name' => 'User 1 Name',
            'email' => 'user1@email.com'
        ]);
        //User2 should be kept
        $this->assertDatabaseHas('users', [
            'id' => $user2Data['id'],
            'name' => $user2Data['name'],
            'email' => $user2Data['email'],
        ]);



        //Update User 2
        $this->actingAs($user2);
        $this->put('/api/user/update', [
            'name' => 'User 2 Name',
            'email' => 'user2@email.com'
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $user2Data['id'],
            'name' => $user2Data['name'],
            'email' => $user2Data['email'],
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user2Data['id'],
            'name' => 'User 2 Name',
            'email' => 'user2@email.com'
        ]);
        //Shoudn't change user 1
        $this->assertDatabaseHas('users', [
            'id' => $user1Data['id'],
            'name' => 'User 1 Name',
            'email' => 'user1@email.com'
        ]);
    }

    public function test_can_update_can_upload_image()
    {
        Storage::fake();
        Queue::fake([
            ResizeImageJob::class,
        ]);
        
        $user = User::factory()->create();
        $id = $user->id;
        $fileName = "profiles/{$id}.jpg";

        Storage::assertMissing($fileName);
        Queue::assertNothingPushed();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'picture' => $fileName
        ]);
        
        //Update User 1
        $this->actingAs($user);
        $uploadFile = UploadedFile::fake()->image('picture.jpg');
        $this->put('/api/user/update', [
            'file' => $uploadFile,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'picture' => $fileName
        ]);
        Storage::assertExists($fileName);
        Queue::assertPushed(function (ResizeImageJob $job) use ($fileName) {
            return $job->image === $fileName;
        });
    }
}