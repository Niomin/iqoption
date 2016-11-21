<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:init');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->runDoctrine();

        $this->getContainer()->get('app.repository.product')->init();
        try {
            $this->getContainer()->get('app.services.generator')->generateCategories();
        } catch (\Exception $e) {

        }
        $this->getContainer()->get('app.services.generator')->generateProducts();
    }

    protected function runDoctrine()
    {
        $kernel = $this->getContainer()->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(['command' => 'doctrine:schema:create']);
        $output = new NullOutput();

        $application->run($input, $output);
    }
}
