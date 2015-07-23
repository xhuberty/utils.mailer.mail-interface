<?php
namespace Mouf\Utils\Mailer;

use Html2Text\Html2Text;
use Pelago\Emogrifier;

/**
 * This class represents a mail to be sent using a Mailer class extending the MailerInterface.
 * + it has special features to add a text mail for any HTML mail that has not been provided the text mail.
 * 
 * Note: default encoding for the mail is UTF-8 if not specified.
 * 
 * @Component
 */
class Mail implements MailInterface {
	
	protected $bodyText;
	protected $bodyHtml;
	protected $title;
	protected $from;
	protected $toRecipients = array();
	protected $ccRecipients = array();
	protected $bccRecipients = array();
	protected $attachements = array();
	protected $encoding = "utf-8";
	protected $autocreateMissingText = true;
	protected $css;
	
	/**
	 * Returns the mail text body.
	 *
	 * @return string
	 */
	public function getBodyText() {
		if ($this->bodyText != null) {
			return $this->bodyText;
		} elseif ($this->autocreateMissingText == true) {
			return Html2Text::convert($this->getBodyHtml());
		}
	}
	
	/**
	 * The mail text body.
	 *
	 * @Property
	 * @param string $bodyText
	 */
	public function setBodyText($bodyText) {
		$this->bodyText = $bodyText;
	}
	
	/**
	 * Returns the HTML text before "emogrification".
	 * This method can be overwritten by subclasses to overwrite the mail body and still applying "emogrification".
	 * 
	 * @return string
	 */
	protected function getBodyHtmlBeforeEmogrify() {
		return $this->bodyHtml;
	}
	
	/**
	 * Returns the mail html body.
	 *
	 * @return string
	 */
	public function getBodyHtml() {
		if ($this->css) {
			$emogrifier = new Emogrifier($this->getBodyHtmlBeforeEmogrify(), $this->css);
			$finalHtml = $emogrifier->emogrify();
		} else {
			$finalHtml = $this->getBodyHtmlBeforeEmogrify();
		}
		
		return $finalHtml;
	}

	/**
	 * The mail html body.
	 *
	 * @Property
	 * @param string $bodyHtml
	 */
	public function setBodyHtml($bodyHtml) {
		$this->bodyHtml = $bodyHtml;
	}
	
	/**
	 * Returns the mail title.
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * The mail title.
	 *
	 * @Property
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * Returns the "From" email address
	 *
	 * @return MailAddressInterface The first element is the email address, the second the name to display.
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * The mail from address.
	 *
	 * @Property
	 * @param MailAddressInterface $from
	 */
	public function setFrom(MailAddressInterface $from) {
		$this->from = $from;
	}
	
	/**
	 * Returns an array containing the recipients.
	 *
	 * @return array<MailAddressInterface>
	 */
	public function getToRecipients() {
		return $this->toRecipients;
	}

	/**
	 * An array containing the recipients.
	 *
	 * @Property
	 * @param array<MailAddressInterface> $toRecipients
	 */
	public function setToRecipients(array $toRecipients) {
		$this->toRecipients = $toRecipients;
	}
	
	/**
	 * Adss a recipient.
	 *
	 * @param MailAddressInterface $toRecipient
	 */
	public function addToRecipient(MailAddressInterface $toRecipient) {
		$this->toRecipients[] = $toRecipient;
	}
	
	/**
	 * Returns an array containing the recipients in Cc.
	 *
	 * @return array<MailAddressInterface>
	 */
	public function getCcRecipients() {
		return $this->ccRecipients;
	}
	
	/**
	 * An array containing the recipients.
	 *
	 * @Property
	 * @param array<MailAddressInterface> $ccRecipients
	 */
	public function setCcRecipients(array $ccRecipients) {
		$this->ccRecipients = $ccRecipients;
	}
	
	/**
	 * Adds a recipient.
	 *
	 * @param MailAddressInterface $ccRecipient
	 */
	public function addCcRecipient(MailAddressInterface $ccRecipient) {
		$this->ccRecipients[] = $ccRecipient;
	}
	
	/**
	 * Returns an array containing the recipients in Bcc.
	 *
	 * @return array<MailAddressInterface>
	 */
	public function getBccRecipients() {
		return $this->bccRecipients;
	}
	
	/**
	 * An array containing the recipients.
	 *
	 * @Property
	 * @param array<MailAddressInterface> $bccRecipients
	 */
	public function setBccRecipients(array $bccRecipients) {
		$this->bccRecipients = $bccRecipients;
	}
	
	/**
	 * Adds a recipient.
	 *
	 * @param MailAddressInterface $bccRecipient
	 */
	public function addBccRecipient(MailAddressInterface $bccRecipient) {
		$this->bccRecipients[] = $bccRecipient;
	}
	
	/**
	 * Returns an array of attachements for that mail.
	 *
	 * @return array<MailAttachmentInterface>
	 */
	public function getAttachements() {
		return $this->attachements;
	}
	
	/**
	 * An array containing the attachments.
	 *
	 * @Property
	 * @param array<MailAttachmentInterface> $attachements
	 */
	public function setAttachements(array $attachements) {
		$this->attachements = $attachements;
	}
	
	/**
	 * Adds an attachment.
	 *
	 * @param MailAttachmentInterface attachement
	 */
	public function addAttachement(MailAttachmentInterface $attachement) {
		$this->attachements[] = $attachement;
	}
	
	/**
	 * Returns the encoding of the mail.
	 *
	 * @return string
	 */
	public function getEncoding() {
		return $this->encoding;
	}
	
	/**
	 * The mail encoding. Defaults to utf-8.
	 *
	 * @Property
	 * @param string $encoding
	 */
	public function setEncoding($encoding) {
		$this->encoding = $encoding;
	}
	
	/**
	 * If no body text is set for that mail, and if autoCreateBodyText is set to true, this object will create the body text from the body HTML text,
	 * by removing any tags.
	 * 
	 * @param boolean $autoCreate
	 */
	public function autoCreateBodyText($autoCreate) {
		$this->autocreateMissingText = $autoCreate;
	}
	
    /**
     * Registers some CSS to be applied to the HTML.
     * When sending the mail, the CSS will be DIRECTLY applied to the HTML, resulting in some HTML with inline CSS.
     * 
     * CSS is inlined using the Emogrifier library.
     * 
     * @param string $css The CSS to apply.
     */
    public function addCssText($css) {
    	$this->css .= $css; 
    }
    
    /**
     * Registers a CSS file to be applied to the HTML.
     * When sending the mail, the CSS will be DIRECTLY applied to the HTML, resulting in some HTML with inline CSS.
     *
     * CSS is inlined using the Emogrifier library.
     *
     * @param string $file The CSS file to apply.
     */
    public function addCssFile($file) {
    	$this->css .= file_get_contents($file);
    }
}
