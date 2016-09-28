<?php

namespace seregazhuk\TumblrDownloader;

use seregazhuk\TumblrDownloader\Downloader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PhotosCommand extends Command
{
    /**
     * Downloader
     */
    protected $downloader;

    public function __construct(Downloader $downloader)
    {
        $this->downloader = $downloader;

        parent::__construct();
    }

    public function configure()
    {
        $this->setName('photos')
            ->setDescription('save photos from a specified blog.')
            ->addArgument('blog', InputArgument::REQUIRED, 'Blog to save photos from.');
    }    

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $blog = $input->getArgument('blog');
        
        $message = 'Saving photos from ' . $blog;
        $output->writeLn("<info>$message</info>");

        $this->downloader->photos($blog);

        $output->line('Finished.');
    }
}