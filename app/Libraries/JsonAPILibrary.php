<?php namespace App\Libraries;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use App\User;
use App\Post;
use App\Comment;

class JsonAPILibrary
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var string[]
     */
    private $params;

    public function __construct()
    {
        $this->client = new Client();
        $this->params = [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];
    }

    public function importUsers()
    {
        $response = $this->getData('users');
        if ($response['body']) {
            foreach (array_slice($response['body'], 0, 10) as $user) {
                $found = User::where('id', $user['id'])->first();
                if (!$found) {
                    $userM = new User();
                    $userM->id = $user['id'];
                    $userM->name = $user['name'];
                    $userM->email = $user['email'];
                    $userM->password = $user['phone'];
                    $userM->save();
                }
            }
        }

    }

    public function importPosts()
    {
        $users = User::all();
        $postArr = [];
        foreach ($users as $user) {
            $response = $this->getData(
                'posts',
                null,
                ['userId' => $user->id]
            );
            $postArr = array_merge ($postArr, $response['body']);
        }
        foreach (array_slice($postArr, 0, 50) as $post) {
            $found = Post::where('id', $post['id'])->first();
            if (!$found) {
                $postM = new Post();
                $postM->id = $post['id'];
                $postM->title = $post['title'];
                $postM->content = $post['body'];
                $postM->user_id = $post['userId'];
                $postM->save();
            }
        }

    }

    public function importComments()
    {
        $posts = Post::all();
        $commentArr = [];
        foreach ($posts as $post) {
            $response = $this->getData(
                'comments',
                null,
                ['postId' => $post->id]
            );
            $commentArr = array_merge ($commentArr, $response['body']);
        }
        foreach ($commentArr as $comment) {
            $found = Comment::where('id', $comment['id'])->first();
            if (!$found) {
                $commentM = new Comment();
                $commentM->id = $comment['id'];
                $commentM->comment = $comment['body'];
                $commentM->post_id = $comment['postId'];
                $commentM->name = $comment['name'];
                $commentM->email = $comment['email'];
                $commentM->save();
            }
        }

    }

    public function getData($url, $param = null, $query = null)
    {
        $url = config('services.json_api.url').'/'.$url;
        if ($param) $url .= '/'.$param;
        if ($query) $this->params['query'] = $query;
        $cacheKey = md5($url.json_encode($query));

        return Cache::remember($cacheKey, 86400, function() use ($url, $param, $query) {
            try {
                $response = $this->client->request('GET', $url, $this->params);

                $body = $response->getBody()->getContents();

                return [
                    'body' => json_decode($body, true),
                    'headers' => $response->getHeaders()
                ];
            } catch (\Exception $e) {
                if ($e->getCode() == 404)
                    return ['body' => ''];
                abort(500, $e->getMessage());
            }
        });
    }
}
