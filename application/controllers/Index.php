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
    function mailer($obj){
        $obj = (object) $this->input->post();

        # Get vars
        $email = $obj->email ?: '';
        $name = $obj->name ?: '-';
        $subject = $obj->subject ?: '';
        $body = $obj->body ?: '';

        # var_dump($obj);

        if(!$email){
            $response['message'] = 'Specify email';
        }
        elseif(!$name){
            $response['message'] = 'Specify name';
        }
        elseif(!$subject){
            $response['message'] = 'Specify subject';
        }
        elseif(!$body){
            $response['subject'] = 'Specify message';
        }
        else{
            $this->load->library('email');

            $config['mailtype'] = 'html';
            $config['protocol'] = 'mail';

            $this->email->initialize($config);
            $this->email->set_newline("\r\n");

            $serviceName = $this->config->item('site_name');
            $serviceEmail = $this->config->item('site_email');

            $this->email->from($serviceEmail, $serviceName);
            $this->email->to($email);
            $this->email->reply_to($email, $name);
            $this->email->subject($subject);
            $this->email->message($body);

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

        return $response;
    }
}
