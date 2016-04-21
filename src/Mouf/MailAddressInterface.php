<?php

namespace Mouf\Utils\Mailer;

/**
 * This interface represents an email address.
 * It contains:
 *  - an email address
 *  - the name that should be displayed.
 */
interface MailAddressInterface
{
    /**
     * Returns the mail address.
     *
     * @return string
     */
    public function getMail():string;

    /**
     * Returns the full name of the mail address owner.
     *
     * @return string
     */
    public function getDisplayAs():string;
}
