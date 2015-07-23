<?php
namespace Mouf\Utils\Mailer;

/**
 * This interface must be implemented by mails to be sent using a class extending the MailerInterface interface.
 * 
 */
interface MailInterface {
	
	/**
	 * Returns the mail text body.
	 *
	 * @return string
	 */
	function getBodyText();
	
	/**
	 * Returns the mail html body.
	 *
	 * @return string
	 */
	function getBodyHtml();
	
	/**
	 * Returns the mail title.
	 *
	 * @return string
	 */
	function getTitle();
	
	/**
	 * Returns the "From" email address
	 *
	 * @return MailAddressInterface 
	 */
	function getFrom();
	
	/**
	 * Returns an array containing the recipients.
	 *
	 * @return MailAddressInterface[]
	 */
	function getToRecipients();
	
	/**
	 * Returns an array containing the recipients in Cc.
	 *
	 * @return MailAddressInterface[]
	 */
	function getCcRecipients();
	
	/**
	 * Returns an array containing the recipients in Bcc.
	 *
	 * @return MailAddressInterface[]
	 */
	function getBccRecipients();
	
	/**
	 * Returns an array of attachements for that mail.
	 *
	 * @return array<MailAttachmentInterface>
	 */
	function getAttachements();
	
	/**
	 * Returns the encoding of the mail.
	 *
	 * @return string
	 */
	function getEncoding();
}
