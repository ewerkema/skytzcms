<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomFormMail extends Mailable
{
    use Queueable, SerializesModels;

    private $fields;
    private $input;
    /**
     * @var
     */
    private $form;

    /**
     * Create a new message instance.
     *
     * @param $fields
     * @param $input
     */
    public function __construct($form, $fields, $input)
    {
        $this->fields = $fields;
        $this->input = $input;
        $this->form = $form;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lines = $this->buildFieldLines();

        $emailField = $this->form->getEmailField();
        $from = ($emailField) ? $this->input[$emailField] : config('mail.from.address');

        return $this->subject("Reactie formulier: ".$this->form->name)
                    ->view('vendor.notifications.email-contact')
                    ->from($from)
                    ->with([
                        'greeting' => 'Nieuwe reactie formulier',
                        'introLines' => array("Er is een nieuwe reactie geplaatst op het ".$this->form->name." formulier op je website. De volgende gegevens zijn daarbij ingevuld: "),
                        'outroLines' => $lines
                    ]);
    }

    public function buildFieldLines()
    {
        $lines = array();

        foreach ($this->fields as $field) {
            if (isset($this->input[$field->formName()]) && !empty($this->input[$field->formName()]))
                $lines[] = "<strong>$field->name:</strong> ".$this->input[$field->formName()]."<br>";
            else
                $lines[] = "<strong>$field->name:</strong> Geen waarde ingevuld / niet aangevinkt."."<br>";
        }

        return $lines;
    }
}
