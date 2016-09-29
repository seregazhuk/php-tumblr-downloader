<?php 

namespace seregazhuk\TumblrDownloader;

use stdClass;
use Tumblr\API\Client;
use Symfony\Component\Console\Helper\ProgressBar;

class Downloader 
{	
	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * @var ProgressBar 
	 */
	protected $progress;

	/**
	 * @var Client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * @param ProgressBar $progress
	 */
	public function setProgressBar(ProgressBar $progress) 
	{
		$this->progress = $progress;

		return $this;
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

	    $totalPosts = $this->getTotalPosts($blogName);
	    $this->progress->start($totalPosts);

	    while(true) {
	        $posts = $this->client->getBlogPosts($blogName, $options)->posts;
	        if(empty($posts)) break;

	        foreach($posts as $post) {
	            $this->saveImagesFromPost($post, $blogName);
	            $this->progress->advance();
	        }

	        $options['offset'] += $options['limit'];
	    }

	    $this->progress->finish();
	}

	/**
	 * @param string $blogName
	 * @return integer
	 */
	protected function getTotalPosts($blogName)
	{
		return $this->client->getBlogPosts($blogName, ['type' => 'photo'])->total_posts;
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