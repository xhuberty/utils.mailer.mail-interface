<?php
namespace Mouf\Utils\Mailer;

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
			return $this->removeHtml($this->getBodyHtml());
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
	 * Removes the HTML tags from the text.
	 * 
	 * @param string $s
	 * @param string $keep The list of tags to keep
	 * @param string $expand The list of tags to remove completely, along their content
	 */
	private function removeHtml($s , $keep = '' , $expand = 'script|style|noframes|select|option'){
        /**///prep the string
        $s = ' ' . $s;
       
        /**///initialize keep tag logic
        if(strlen($keep) > 0){
            $k = explode('|',$keep);
            for($i=0;$i<count($k);$i++){
                $s = str_replace('<' . $k[$i],'[{(' . $k[$i],$s);
                $s = str_replace('</' . $k[$i],'[{(/' . $k[$i],$s);
            }
        }

		$pos = array();
		$len = array();
       
        //begin removal
        /**///remove comment blocks
        while(stripos($s,'<!--') > 0){
            $pos[1] = stripos($s,'<!--');
            $pos[2] = stripos($s,'-->', $pos[1]);
            $len[1] = $pos[2] - $pos[1] + 3;
            $x = substr($s,$pos[1],$len[1]);
            $s = str_replace($x,'',$s);
        }
       
        /**///remove tags with content between them
        if(strlen($expand) > 0){
            $e = explode('|',$expand);
            for($i=0;$i<count($e);$i++){
                while(stripos($s,'<' . $e[$i]) > 0){
                    $len[1] = strlen('<' . $e[$i]);
                    $pos[1] = stripos($s,'<' . $e[$i]);
                    $pos[2] = stripos($s,$e[$i] . '>', $pos[1] + $len[1]);
                    $len[2] = $pos[2] - $pos[1] + $len[1];
                    $x = substr($s,$pos[1],$len[2]);
                    $s = str_replace($x,'',$s);
                }
            }
        }
       
        /**///remove remaining tags
        while(stripos($s,'<') > 0){
            $pos[1] = stripos($s,'<');
            $pos[2] = stripos($s,'>', $pos[1]);
            $len[1] = $pos[2] - $pos[1] + 1;
            $x = substr($s,$pos[1],$len[1]);
            $s = str_replace($x,'',$s);
        }
       
        /**///finalize keep tag
        if (isset($k)) {
	        for($i=0;$i<count($k);$i++){
	            $s = str_replace('[{(' . $k[$i],'<' . $k[$i],$s);
	            $s = str_replace('[{(/' . $k[$i],'</' . $k[$i],$s);
	        }
        }
       
        return trim($s);
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
