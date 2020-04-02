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

        # Get vars
        $email = isset($obj->email) ? $obj->email : '';
        $name = isset($obj->name) ? $obj->name : '-';
        $subject = isset($obj->subject) ? $obj->subject : '';
        $body = isset($obj->body) ? $obj->body : '';

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
            $response['message'] = 'Specify body';
        }
        else{
            $this->load->library('email');

            $config['mailtype'] = 'html';
            $config['protocol'] = 'mail';

            $this->email->initialize($config);
            $this->email->set_newline("\r\n");

            $serviceName = $this->config->item('site_name');
            $serviceEmail = $this->config->item('email');

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

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
