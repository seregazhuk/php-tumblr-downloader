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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $blog = $input->getArgument('blog');
        
        $message = 'Saving photos from ' . $blog;
        $output->writeln("<info>$message</info>");

        $progress = new ProgressBar($output);

        $progress->start($this->downloader->getTotalPosts($blog));
        $this->downloader
            ->save($blog, function() use ($progress) {
                $progress->advance();
            });

        $saved = $this->downloader->getTotalSaved();
        $progress->finish();

        $output->writeln('');
        $output->writeln("<comment>Finished. $saved photos saved. </comment>");
    }
}