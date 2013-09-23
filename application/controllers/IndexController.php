<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    }

    public function cronAction()
    {
        $emailModel = new Application_Model_DbTable_Emails();

        $emails = $emailModel->getEmailsToSend($this->_getParam("force", false));

        foreach($emails as $email)
        {
            $smtpServer = 'smtp.sendgrid.net';

            $config = array('ssl' => 'tls',
            'port' => '587',
            'auth' => 'login',
            'username' => 'sendgrid_user',
            'password' => 'sendgrid_password');

            $transport = new Zend_Mail_Transport_Smtp($smtpServer, $config);

            $mail = new Zend_Mail();

            $mail->setFrom('hax@stephenbussey.com', 'Auto-Mate');
            $mail->addTo($email['to'],'Car Owner');
            $mail->setSubject('Car Maintenance Notification');
            $mail->setBodyText($email['body']);

            $mail->send($transport);

            echo "Email sent to " . $email['to'] . "<br>";
        }
        die();
    }
}

