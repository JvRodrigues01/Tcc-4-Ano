<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable
{
	use Queueable, SerializesModels;

	public $data;

	public $subject;

	public $template;

	public $attach;

	public $from;

	public function __construct($subject, array $data, $template, $attach = null)
	{
		$this->subject = $subject;
		$this->data = $data;
		$this->template = $template;
		$this->attach = $attach;

		$this->from = [
			'address' => 'BizSeller' . env('MAIL_FROM_DOMAIN', '@bizseller.com.br'),
			'name' => env('MAIL_FROM_NAME', 'BizSeller'),
		];
	}

	public function build()
	{

		if($this->attach == null)
			return $this
				->from($this->from)
				->subject($this->subject)
				->view($this->template, $this->data);
		else
			return $this
				->from($this->from)
				->subject($this->subject)
				->view($this->template, $this->data)
				->attachData($this->attach['Data'], $this->attach['Nome']);
	}
}