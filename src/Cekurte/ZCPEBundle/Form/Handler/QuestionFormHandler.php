<?php

namespace Cekurte\ZCPEBundle\Form\Handler;

use Cekurte\ZCPEBundle\Entity\Answer;

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

        if ($this->formIsValid()) {

            $data = $this->getForm()->getData();

            $id = $data->getId();

            if (empty($id)) {

                var_dump($options, $correct, ($options));
                exit;

                foreach ($answers as $index => $answer) {

                    $answerEntity = new Answer();

                    $this->getForm()->getData()
                        ->addAnswer(

                        )
                    ;
                }

                $this->getForm()->getData()
                    ->setDeleted(false)
                    ->setCreatedBy($this->getUser())
                    ->setCreatedAt(new \DateTime('NOW'))
                ;
            } else {
                $this->getForm()->getData()
                    ->setUpdatedBy($this->getUser())
                    ->setUpdatedAt(new \DateTime('NOW'))
                ;
            }

            $this->getFlashBag()->add('message', array(
                'type'      => 'success',
                'message'   => sprintf('The record has been %s successfully.', $data->getId() ? 'updated ' : 'created'),
            ));

            $this->getManager()->persist($data);
            $this->getManager()->flush();

            return $data->getId();
        }

        return false;
    }
}
