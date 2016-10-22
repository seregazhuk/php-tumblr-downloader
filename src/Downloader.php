<?php 

namespace seregazhuk\TumblrDownloader;

use stdClass;
use Tumblr\API\Client;

class Downloader 
{	
	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * @var int
	 */
	protected $totalSaved = 0;

	/**
	 * @var Client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}

    /**
     * @var string $blogName
     * @param callable $processCallback
     */
	public function save($blogName, callable $processCallback = null)
	{
        $options = [
            'type'   => 'photo',
            'limit'  => 20,
            'offset' => 0,
        ];

        while(true) {
	        $posts = $this->getPosts($blogName, $options);

	        if(empty($posts)) break;

	        foreach($posts as $post) {
	            $this->saveImagesFromPost($post, $blogName);

                if($processCallback) $processCallback($post);
	        }

	        $options['offset'] += $options['limit'];
	    }
    }

	/**
	 * @param string $blogName
	 * @return integer
	 */
	public function getTotalPosts($blogName)
	{
		return $this->client
			->getBlogPosts($blogName, ['type' => 'photo'])
			->total_posts;
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

	        $this->totalSaved ++;
	    }
	}

    /**
     * @param string $directory
     * @return string
     */
	protected function getSavePath($directory)
	{
		$path = 'photos' . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR;
		if(!is_dir($path))  {
			mkdir($path,  0777, true);
		}

		return $path;
	}

    /**
     * @param string $blogName
     * @param array $options
     * @return mixed
     */
    protected function getPosts($blogName, array $options)
    {
        return $this
            ->client
            ->getBlogPosts($blogName, $options)
            ->posts;
	}

	/**
	 * @return int
	 */
	public function getTotalSaved() 
	{
		return $this->totalSaved;
	}
}