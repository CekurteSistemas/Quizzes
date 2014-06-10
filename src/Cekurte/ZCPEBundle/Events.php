<?php

namespace Cekurte\ZCPEBundle;

/**
 * ZCPE Events.
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
final class Events
{
    /**
     * Disable constructor method
     */
    private function __construct()
    {

    }

    /**
     * This event is dispatched when create a new Question
     */
    const NEW_QUESTION = 'cekurte.zcpe.new.question';

}
