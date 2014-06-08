<?php

namespace Cekurte\ZCPEBundle\Form\Handler;

use Cekurte\ZCPEBundle\Entity\Answer;
use Cekurte\ZCPEBundle\Entity\QuestionHasAnswer;

/**
 * Question handler.
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class QuestionFormHandler extends CustomFormHandler
{
    /**
     * {@inherited}
     */
    public function save()
    {
        $answers = array_filter($this->getRequest()->get('option_answers',  array()));
        $correct = array_filter($this->getRequest()->get('correct_answers', array()));

        if (empty($correct)) {

            $this->getFlashBag()->add('message', array(
                'type'      => 'error',
                'message'   => 'Select correct answer!',
            ));

            return false;
        }

        try {

            $this->getManager()->getConnection()->beginTransaction();

            $data = $this->getForm()->getData();

            $questionId = $data->getId();

            if (!empty($questionId)) {

                $results = $this->getManager()->getRepository('CekurteZCPEBundle:QuestionHasAnswer')->findBy(array(
                    'question' => $data->getId(),
                ));

                foreach ($results as $key => $result) {
                    $this->getManager()->remove($result);
                }
            }

            $id = parent::save();

            foreach ($answers as $index => $answer) {

                $answerEntity = new Answer();

                $answerEntity
                    ->setTitle($answer)
                    ->setDeleted(false)
                    ->setCreatedBy($this->getUser())
                    ->setCreatedAt(new \DateTime('NOW'))
                ;

                $this->getManager()->persist($answerEntity);
                $this->getManager()->flush();

                $correctAnswer = false;

                foreach ($correct as $key => $indexCorrect) {
                    if ($indexCorrect == $index) {
                        $correctAnswer = true;
                    }
                }

                $questionAnswerEntity = new QuestionHasAnswer();

                $questionAnswerEntity
                    ->setAnswer($answerEntity)
                    ->setQuestion($data)
                    ->setCorrect($correctAnswer)
                ;

                $this->getManager()->persist($questionAnswerEntity);
                $this->getManager()->flush();
            }

            $this->getManager()->getConnection()->commit();

            return $id;

        } catch (\Exception $e) {

            $this->getFlashBag()->clear();

            $this->getFlashBag()->add('message', array(
                'type'      => 'error',
                'message'   => $e->getMessage(),
            ));

            $this->getManager()->getConnection()->rollback();

            return false;
        }

        return false;
    }
}
