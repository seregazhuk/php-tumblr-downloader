<?php

namespace seregazhuk\TumblrDownloader;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SaveCommand extends Command
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
        $this->setName('save')
            ->setDescription('save photos from a specified blog.')
            ->addArgument('blog', InputArgument::REQUIRED, 'Blog to save photos from.');
    }    

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $blog = $input->getArgument('blog');
        
        $message = 'Saving photos from ' . $blog;
        $output->writeLn("<info>$message</info>");

        $progress = new ProgressBar($output);

        $this->downloader
            ->setProgressBar($progress)
            ->save($blog);

        $saved = $this->downloader->getTotalSaved();

        $output->writeLn('');
        $output->writeLn("<comment>Finished. $saved photos saved. </comment>");
    }
}