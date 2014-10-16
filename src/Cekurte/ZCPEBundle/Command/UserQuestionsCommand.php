<?php

namespace Cekurte\ZCPEBundle\Command;

use Cekurte\Custom\UserBundle\Entity\Group;
use Cekurte\Custom\UserBundle\Entity\User;
use Cekurte\ZCPEBundle\Entity\Question;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author João Paulo Cercal <sistemas@cekurte.com>
 */
class UserQuestionsCommand extends ContainerAwareCommand
{
    const GROUP_NAME = 'Default';

    /**
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @return \FOS\UserBundle\Doctrine\GroupManager
     */
    protected function getGroupManager()
    {
        return $this->getContainer()->get('fos_user.group_manager');
    }

    /**
     * @return \FOS\UserBundle\Doctrine\UserManager
     */
    protected function getUserManager()
    {
        return $this->getContainer()->get('fos_user.user_manager');
    }

    /**
     * @param User $user
     * @param int $numberOfQuestions
     * @return mixed
     */
    protected function getQuestions(User $user, $numberOfQuestions)
    {
        $queryBuilder = $this
            ->getManager()
            ->getRepository('CekurteZCPEBundle:Question')
            ->createQueryBuilder('ck')
        ;

        return $queryBuilder
            ->andWhere($queryBuilder->expr()->neq('ck.createdBy', ':createdBy'))
            ->andWhere($queryBuilder->expr()->eq('ck.deleted', ':deleted'))
            ->andWhere($queryBuilder->expr()->eq('ck.importedFromGoogleGroups', ':importedFromGoogleGroups'))
            ->andWhere($queryBuilder->expr()->eq('ck.approved', ':approved'))
            ->andWhere($queryBuilder->expr()->eq('ck.revised', ':revised'))
            ->setParameter('createdBy', $user)
            ->setParameter('deleted', false)
            ->setParameter('importedFromGoogleGroups', true)
            ->setParameter('approved', false)
            ->setParameter('revised', false)
            ->orderBy('ck.id', 'ASC')
            ->setMaxResults($numberOfQuestions)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('cekurte:zcpe:setup:user')
            ->setDescription('Setup a user to import questions')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                new InputArgument('questions', InputArgument::REQUIRED, 'The number of questions'),
            ))
            ->setHelp($this->getHelpMessage())
        ;
    }

    /**
     * @return string
     */
    protected function getHelpMessage()
    {
        return <<<EOT
The command <info>cekurte:zcpe:setup:user</info> setup a user to import questions

<info>php app/console cekurte:zcpe:setup:user username 10</info>
EOT;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username           = $input->getArgument('username');
        $numberOfQuestions  = $input->getArgument('questions');

        $groupEntity = $this->getGroupManager()->findGroupByName(self::GROUP_NAME);

        if (!$groupEntity instanceof Group) {
            throw new \Exception(sprintf('The group "%s" not found.', self::GROUP_NAME));
        }

        $userEntity = $this->getUserManager()->findUserByUsername($username);

        if (!$userEntity instanceof User) {
            throw new \Exception(sprintf('The username "%s" not found.', $username));
        }

        $userEntity->addGroup($groupEntity);

        $this->getUserManager()->updateUser($userEntity);

        try {

            $this->getManager()->getConnection()->beginTransaction();

            $questions = $this->getQuestions($userEntity, $numberOfQuestions);

            foreach ($questions as $question) {

                $question
                    ->setCreatedBy($userEntity)
                    ->setUpdatedBy($userEntity)
                    ->setUpdatedAt(new \DateTime('NOW'))
                ;

                $this->getManager()->persist($question);
                $this->getManager()->flush();
            }

            $this->getManager()->getConnection()->commit();

            $output->writeln('Setup complete with successfully.');

        } catch (\Exception $e) {

            $this->getManager()->getConnection()->rollback();
            $output->writeln($e->getMessage());
        }
    }
    
    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('username')) {
            $username = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please enter a username: ',
                function($username) {
                    if (empty($username)) {
                        throw new \Exception('The username cannot be empty.');
                    }
                    return $username;
                }
            );
            $input->setArgument('username', $username);
        }

        if (!$input->getArgument('questions')) {
            $questions = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please enter a number of questions: ',
                function($questions) {
                    if (empty($questions)) {
                        throw new \Exception('The number of questions should be greater than zero.');
                    }
                    return $questions;
                }
            );
            $input->setArgument('questions', $questions);
        }
    }
}
