[![Latest Stable Version](https://poser.pugx.org/mouf/utils.mailer.mail-interface/v/stable.svg)](https://packagist.org/packages/mouf/utils.mailer.mail-interface)
[![Latest Unstable Version](https://poser.pugx.org/mouf/utils.mailer.mail-interface/v/unstable.svg)](https://packagist.org/packages/mouf/utils.mailer.mail-interface)
[![License](https://poser.pugx.org/mouf/utils.mailer.mail-interface/license.svg)](https://packagist.org/packages/mouf/utils.mailer.mail-interface)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecodingmachine/utils.mailer.mail-interface/badges/quality-score.png?b=2.0)](https://scrutinizer-ci.com/g/thecodingmachine/utils.mailer.mail-interface/?branch=2.0)

Mouf Mail system
================

The Mouf framework is only an IOC framework. As such, it does not provide any means for managing any kind of cache. Hopefully,
the Mouf team provides also a range of packages to manage sending mails.

The mail architecture
---------------------

In Mouf, <em>emails</em> are sent using <em>MailServices</em>.<br/>
Mouf provides 4 implementations of mail services. You can provide your own if you want.


By default, Mouf provides these 3 implementations:

- [**SwiftMailService**](http://mouf-php.com/packages/mouf/utils.mailer.swift-mail-service/README.md): a mail service that uses a SMTP server to send mails (this is a wrapper using the Swift mail library).
- [**SmtpMailService**](http://mouf-php.com/packages/mouf/utils.mailer.smtp-mail-service/README.md): a mail service that uses a SMTP server to send mails (this is a wrapper using the Zend_Mail library).
- [**DBMailService**](http://mouf-php.com/packages/mouf/utils.mailer.db-mail-service/README.md): a mail service that does not send any mails. Instead, it writes the mail in a MySQL database. It 
  can forward the mail later to another mail service that will actually send the mail. This mail server is available in the 
  package <em>utils/mailer/db-mail-service</em>.


Each mail service must extend the <code>MailServiceInterface</code> interface that is part of the
package utils/mailer/mail-interface.

The mail service interface
--------------------------

Each class implementing the <code>MailServiceInterface</code> provides one simple method to send mails:
```php
interface MailServiceInterface {
	
	/**
	 * Sends the mail passed in parameter.
	 *
	 * @param MailInterface $mail The mail to send.
	 */
	function send(MailInterface $mail);
}
```

The mail passed in parameter must implement the <code>MailInterface</code> interface. Hopefully, Mouf provides a <code>Mail</code> class
that does just that!

For instance, to send a mail, you just need to write:

```php
$mailService = Mouf::getSmtpMailService();

$mail = new Mail();
$mail->setBodyText("This is my mail!");
$mail->setBodyHtml("This is my &lt;b&gt;mail&lt;/b&gt;!");
$mail->setFrom(new MailAddress("my@server.com", "Server"));
$mail->addToRecipient(new MailAddress("david@email.com", "David"));
$mail->setTitle("My mail");

$mailService->send($mail);
```

The code above assumes that you configured an instance in Mouf called "smtpMailService".

The <code>MailInterface</code> interface supports:

- Text/Html mails (<code>setBodyText</code> and <code>setBodyHtml</code> methods)
- Multiple recipients (<code>addToRecipient</code> method)
- Multiple Cc recipients (<code>addCcRecipient</code> method)
- Multiple Bcc recipients (<code>addBccRecipient</code> method)
- File attachments (<code>addAttachment</code> method)

Mail addresses are passed as a <code>MailAddress</code> object, that contains 2 strings: the mail address and the alias.


Automatic text body generation
------------------------------
When sending mails, it is a good practice to send 2 *bodies*: one in **plain text** and one in **HTML**.
Forget the **plain text** and your mail could be flagged as spam.
However, most of the time, your users will look at the mail in HTML. Mail clients that can only read plain text
are really rare those days. 

The `Mail` class can help you here. Indeed, it will automatically generate the plain text version of your mail
from the HTML, by stripping all tags.

```php
$mail = new Mail();
$mail->setBodyHtml("This is my &lt;b&gt;mail&lt;/b&gt;!");
// No need to call $mail->setBodyText()
```

If you want to explicitly disable the plain text generation you can call this method:

```php
$mail = new Mail();
$mail->setBodyHtml("This is my &lt;b&gt;mail&lt;/b&gt;!");
// Disable plaing text generation from HTML. The mail will NOT contain the plain text part.
$mail->autoCreateBodyText(false);
```
