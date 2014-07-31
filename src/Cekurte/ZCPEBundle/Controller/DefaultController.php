<?php

namespace Cekurte\ZCPEBundle\Controller;

use Cekurte\ZCPEBundle\Entity\Parser;
use Cekurte\ZCPEBundle\Entity\Question;
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
     * @Route("/parser")
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
     * @Route("/parser-database")
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
     * @Route("/parser-database-content")
     * @Method("GET")
     * @Template("CekurteZCPEBundle:Default:parser.html.twig")
     */
    public function parserDatabaseContentAction()
    {
        set_time_limit(0);

        $em     = $this->get('doctrine')->getManager();
        $itens  = $em->getRepository('CekurteZCPEBundle:Parser')->findAll();

        $index  = 0;

        foreach ($itens as $item) {

            $index++;

            if ($index < 1066) {
                continue;
            }

            $content = $item->getContent();

            if (preg_match_all("/[\r\n]+[^:alpha]{1}\s*[\:\)\/]{1}\s*(.*)/", $content, $matches)) {

                $answers = end($matches);

                echo "Match was found <br />";
                var_dump($matches, $content, $answers);
            }

            exit;
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
