<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

    /**
     * 
     * Send email
     * 
     * @param email: email of recipient
     * @param name: name of recipient
     * @param subject: subject of email
     * @param body: body of email
     * 
    */
    function mailer(){

        /**
         * 
         * Due to this error on some servers, we are removing error reporting to enable proper response rendering
         * 
         * <p>Severity: Notice</p>
         * <p>Message: Use of undefined constant INTL_IDNA_VARIANT_UTS46 - assumed 'INTL_IDNA_VARIANT_UTS46'</p>
         * <p>Filename: libraries/Email.php</p>
         * <p>Line Number: 1859</p>
         * 
         * CI version 3.1.11
         * 
        */
        error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

        $obj = (object) $this->input->post();

        # var_dump($obj); exit();
        
        if(isset($obj->debug)){
            $response['post'] = $this->input->post();
        }
        
        $serviceName = $this->config->item('site_name');
        $serviceEmail = $this->config->item('email');

        # Email vars
        $email = isset($obj->email) ? $obj->email : '';
        $subject = isset($obj->subject) ? $obj->subject : '';
        $body = isset($obj->body) ? $obj->body : '';

        # Sender info
        $fromEmail = isset($obj->from_email) ? $obj->from_email : $serviceEmail;
        $fromName = isset($obj->from_name) ? $obj->from_name : $serviceName;

        # Reply to
        $replyEmail = isset($obj->reply_email) ? $obj->reply_email : $serviceEmail;
        $replyName = isset($obj->reply_name) ? $obj->reply_name : $serviceName;

        # Meta
        $protocol = isset($obj->protocol) ? $obj->protocol : 'mail';

        # SMTP vars
        $SMTPHost = isset($obj->smtp_host) ? $obj->smtp_host : '';
        $SMTPUser = isset($obj->smtp_user) ? $obj->smtp_user : '';
        $SMTPPassword = isset($obj->smtp_pass) ? $obj->smtp_pass : '';
        $SMTPPort = isset($obj->smtp_port) ? $obj->smtp_port : '';
        $SMTPCrypto = isset($obj->smtp_crypto) ? $obj->smtp_crypto : '';

        if(!$email){
            $response['message'] = 'Specify email';
        }
        elseif(!$subject){
            $response['message'] = 'Specify subject';
        }
        elseif(!$body){
            $response['message'] = 'Specify body';
        }
        else{
            $this->load->library('email');
            
            $config['mailtype'] = 'html';

            if($protocol == 'smtp'){
                $config['protocol'] = $protocol;
                $config['smtp_host'] = $SMTPHost;
                $config['smtp_user'] = $SMTPUser;
                $config['smtp_pass'] = $SMTPPassword;
                $config['smtp_port'] = $SMTPPort;
                $config['smtp_crypto'] = $SMTPCrypto;
            }

            $this->email->initialize($config);
            $this->email->set_newline("\r\n");

            $this->email->from($fromEmail, $fromName);
            $this->email->to($email);
            $this->email->subject($subject);
            $this->email->message($body);
            $this->email->reply_to($replyEmail, $replyName);

            if($this->email->send()){
                $response['message'] = 'E-mail sent to '. $email; 
            }
            else{
                $response = array(
                    'error'=>true,
                    'message'=>$this->email->print_debugger(),
                ); 
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
