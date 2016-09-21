<?php 

namespace seregazhuk\TumblrDownloader;

use Tumblr\API\Client;

class Downloader 
{	
	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * @var Client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * @var string $blogName
	 */
	public function photos($blogName)
	{
	    $options = [
	        'type' => 'photo',
	        'limit' => 20,
	        'offset' => 0
	    ];

	    while(true) {
	        $posts = $this->client->getBlogPosts($blogName, $options)->posts;
	        if(empty($posts)) break;

	        foreach($posts as $post) {
	            $this->saveImagesFromPost($post, $blogName);
	        }

	        $options['offset'] += $options['limit'];
	    }
	}

	/**
	 * @param stdClass $post
	 * @param string $directory
	 */
	protected function saveImagesFromPost($post, $directory)
	{
	    foreach($post->photos as $photo) {
	        $imageUrl = $photo->original_size->url;

	        $path = $this->getSavePath($directory);
	        file_put_contents(
	            $path . basename($imageUrl), 
	            file_get_contents($imageUrl)
	        );
	    }
	}

	/**
	 * @param string $directory
	 */
	protected function getSavePath($directory)
	{
		$path = 'photos' . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR;
		if(!is_dir($path))  {
			mkdir($path,  0777, true);
		}

		return $path;
	}
}