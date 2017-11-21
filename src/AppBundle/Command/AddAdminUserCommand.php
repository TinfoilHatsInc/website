<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 21-11-17
 * Time: 12:05
 */

namespace AppBundle\Command;


use AppBundle\Messaging\Command\AddAdminUser;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class AddAdminUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tinfoil:createadmin')
            ->setDescription('Creates a new admin user.')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
            ->addArgument('email', InputArgument::REQUIRED, 'Email');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Creating new admin user");
        $addAdminuser = new AddAdminUser(
                            $input->getArgument('username'),
                            $input->getArgument('password'),
                            $input->getArgument('email'));
        $this->getContainer()->get('command_bus')->handle($addAdminuser);
    }
}