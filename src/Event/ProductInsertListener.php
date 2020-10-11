<?php


namespace App\Event;


use Swift_Mailer;
use Swift_SmtpTransport;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class ProductInsertListener implements EventSubscriberInterface
{

	/**
	 * @var Swift_Mailer $mail
	 */
	private $mail;

	/**
	 * ProductInsertListener constructor.
	 * @param Swift_Mailer $mailer
	 */
	public function __construct(Swift_Mailer $mailer)
	{
		$this->mail = $mailer;
	}

	public static function getSubscribedEvents()
	{
		return[
			ProductInsertEvent::class => 'productInsertSendMail'
		];
	}

	public function productInsertSendMail(ProductInsertEvent $event)
	{
		$name = $event->getProduct()->getName();
		$desc = $event->getProduct()->getDescription();
		$price = $event->getProduct()->getPrice();

		$mail =(new \Swift_Message('test'))
				->setFrom('test@i.ua')
				->setTo('asd@gmail.com')
				->setBody("NAME: $name;\nDESCRIPTION:$desc;\n PRICE:$price \n");

		return $this->mail->send($mail);
	}
}