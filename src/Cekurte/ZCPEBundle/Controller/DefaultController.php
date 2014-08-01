<?php

namespace Cekurte\ZCPEBundle\Controller;

use Cekurte\ZCPEBundle\Entity\Answer;
use Cekurte\ZCPEBundle\Entity\Category;
use Cekurte\ZCPEBundle\Entity\Parser;
use Cekurte\ZCPEBundle\Entity\Question;
use Cekurte\ZCPEBundle\Entity\QuestionHasAnswer;
use Cekurte\ZCPEBundle\Entity\QuestionType;
use Cekurte\ZCPEBundle\Form\Type\QuestionAnonymousFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\DomCrawler\Crawler;

class DefaultController extends Controller
{
    /**
     * @var string
     */
    const GOOGLE_GROUPS_TOPIC_URL = 'https://groups.google.com/d/topic/<GROUP>/<TOPIC>';

    /**
     * @Route("/", name="home")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createForm(new QuestionAnonymousFormType(), new Question());

        return array(
            'form'              => $form->createView(),
            'subject_template'  => sprintf('%s: ', $this->container->getParameter('cekurte_zcpe_google_group_subject')),
        );
    }

    /**
     * @Route("/admin/parser")
     * @Method("GET")
     * @Template()
     */
    public function parserAction()
    {
        set_time_limit(0);

        $rootDir = $this->get('kernel')->getRootDir();

        $content = file_get_contents(realpath($rootDir . '/../docs/google-groups-posts.bkp.html'));

        $crawler = new Crawler($content);

        $subjectFilterPrefix = 'pergunta';

        $em = $this->get('doctrine')->getManager();

        $crawler->filter('body > table > tbody > tr > td > div > div > div:first-child')->each(function (Crawler $node, $i) use (&$subjectFilterPrefix, $em) {

            $subject    = $node->filter('a')->first();
            $author     = $node->filter('div:first-child > div')->attr('data-name');

            $time  = $node->filter('div')->last()->children()->attr('title');

            setlocale(LC_ALL, NULL);
            setlocale(LC_ALL, 'pt_BR');

            if (substr(strtolower(utf8_decode($subject->text())), 0, strlen($subjectFilterPrefix)) == $subjectFilterPrefix) {

                $timeParts = explode(',', utf8_decode($time));

                $timeParsed = strptime(
                    end($timeParts),
                    '%d de %B de %Y %Hh%Mmin%Ss'
                );

                $createdAt = new \DateTime(date('Y-m-d h:i:s', mktime(
                    $timeParsed['tm_hour'],
                    $timeParsed['tm_min'],
                    $timeParsed['tm_sec'],
                    1,
                    $timeParsed['tm_yday'] + 1,
                    $timeParsed['tm_year'] + 1900
                )));

                $entity = $em->getRepository('CekurteZCPEBundle:Parser')->findOneBy(array(
                    'subject' => utf8_decode($subject->text())
                ));

                if (!$entity instanceof Parser) {

                    $parser = new Parser();
                    $parser
                        ->setSubject(utf8_decode($subject->text()))
                        ->setUrl($subject->attr('href'))
                        ->setAuthor(utf8_decode($author))
                        ->setCreatedAt($createdAt)
                    ;

                    $em->persist($parser);
                    $em->flush();
                }
            }
        });

        return array();
    }

    /**
     * Get the google group
     *
     * @return string
     */
    protected function getGoogleGroup()
    {
        return 'rumo-a-certificacao-php';
    }

    /**
     * @Route("/admin/parser-database")
     * @Method("GET")
     * @Template("CekurteZCPEBundle:Default:parser.html.twig")
     */
    public function parserDatabaseAction()
    {
        set_time_limit(0);

        $baseUrl = str_replace('<GROUP>', $this->getGoogleGroup(), self::GOOGLE_GROUPS_TOPIC_URL);

        $em     = $this->get('doctrine')->getManager();
        $itens  = $em->getRepository('CekurteZCPEBundle:Parser')->findAll();

        foreach ($itens as $item) {

            $url  = str_replace('<TOPIC>', $item->getUrl(), $baseUrl);

            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible;  MSIE 7.01; Windows NT 5.0)");

            $response = curl_exec($curl);

            $crawler  = new Crawler($response);

            $content  = $crawler->filter('body > table > tr:first-child > td.snippet')->first()->html();

            $contentFiltered = trim(utf8_decode(strip_tags(str_replace('<br>', "\r\n", $content))));

            $item->setContent($contentFiltered);

            $em->persist($item);
            $em->flush();
        }

        return array();
    }

    /**
     * @Route("/admin/parser-database-content")
     * @Method("GET")
     * @Template("CekurteZCPEBundle:Default:parser.html.twig")
     */
    public function parserDatabaseContentAction()
    {
        set_time_limit(0);

        $em     = $this->get('doctrine')->getManager();
        $itens  = $em->getRepository('CekurteZCPEBundle:Parser')->findAll();

        foreach ($itens as $item) {

            $subject = $item->getSubject();

            $content = $item->getContent();

            if (preg_match_all("/[\r\n]+[^:alpha]{1}\s*[\:\)\/]{1}\s*(.*)/", $content, $matches)) {

                $answers            = end($matches);
                $answersComplete    = $matches[0];

                $question           = $content;

                foreach ($answersComplete as $answer) {
                    $question = str_replace($answer, '', $question);
                }
            }

            if (preg_match_all("/[\r\n]+[^:alpha]{1}\s*[\:\)\/]{1}\s*(.*)/", $content, $matches)) {

                $answers            = end($matches);
                $answersComplete    = $matches[0];

                $question           = $content;

                foreach ($answersComplete as $answer) {
                    $question = str_replace($answer, '', $question);
                }
            }

            $questionType = 'Single Choice';

            if (preg_match_all("/.*(\_{5}).*/", $content, $matches)) {
                $questionType = 'Text';
            }

            if (preg_match("/\(\s*choose\s*(\d+)\)/i", $content, $matches)) {

                $choose = (int) end($matches);

                if ($choose > 1) {
                    $questionType = 'Multiple Choice';
                }

                $question = str_replace($matches[0], '', $question);
            }

            if (preg_match("/\d{1,4}/", $subject, $matches)) {
                $googleGroupsId = (int) $matches[0];
            }

            $category = array();

            if (preg_match("/(Categoria|Category){1}\:{1}\s+(.*)\./i", $content, $matches)) {
                $category = explode(', ', end($matches));
            }

            $entity = $em->getRepository('CekurteZCPEBundle:Question')->findOneBy(array(
                'googleGroupsId'    => $googleGroupsId,
            ));

            if (!$entity instanceof Question) {

                $entity = new Question();

                $entity
                    ->setDeleted(false)
                    ->setCreatedBy($this->getUser())
                    ->setCreatedAt($item->getCreatedAt())

                    ->setGoogleGroupsId($googleGroupsId)
                    ->setGoogleGroupsAuthor($item->getAuthor())

                    ->setTitle(trim(nl2br($question)))
                    ->setComment($content)
                ;

                $questionTypeEntity = $em->getRepository('CekurteZCPEBundle:QuestionType')->findOneBy(array(
                    'title' => trim($questionType)
                ));

                if ($questionTypeEntity instanceof QuestionType) {
                    $entity->setQuestionType($questionTypeEntity);
                }

                if (!empty($category)) {
                    foreach ($category as $title) {

                        $categoryEntity = $em->getRepository('CekurteZCPEBundle:Category')->findOneBy(array(
                            'title' => trim($title)
                        ));

                        if ($categoryEntity instanceof Category) {
                            $entity->addCategory($categoryEntity);
                        }
                    }
                }

                if (!empty($answers)) {

                    $em->persist($entity);
                    $em->flush();

                    $entity
                        ->setApproved(false)
                        ->setImportedFromGoogleGroups(true)
                        ->setDifficulty(0)
                        ->setEmailHasSent(true)
                    ;

                    $em->persist($entity);
                    $em->flush();

                    foreach ($answers as $answer) {

                        $answerEntity = new Answer();
                        $answerEntity
                            ->setTitle($answer)
                            ->setDeleted(false)
                            ->setCreatedBy($this->getUser())
                            ->setCreatedAt(new \DateTime('NOW'))
                        ;

                        $em->persist($answerEntity);
                        $em->flush();

                        $answerEntityRel = new QuestionHasAnswer();
                        $answerEntityRel
                            ->setAnswer($answerEntity)
                            ->setQuestion($entity)
                            ->setCorrect(false)
                        ;

                        $em->persist($answerEntityRel);
                        $em->flush();
                    }

                }
            }
        }

        return array();
    }

    /**
     * @Route("/admin/", name="admin")
     * @Method("GET")
     * @Template()
     */
    public function indexAdminAction()
    {
        return array();
    }
}
