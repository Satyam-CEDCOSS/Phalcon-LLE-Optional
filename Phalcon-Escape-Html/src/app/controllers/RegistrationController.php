<?php

use Phalcon\Mvc\Controller;
use Phalcon\Escaper;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;

class RegistrationController extends Controller
{

    public function IndexAction()
    {
        // Redirected to View
    }

    public function registrationAction()
    {
        // Redirected to View
    }

    public function processAction()
    {
        $arr = $this->request->getPost();
        if ($arr["name"] && $arr["email"] && $arr["password"]) {
            $escape = new Escaper();
            $user = new Users();
            foreach ($arr as $key => $value) {
                $arr[$key] = $escape->escapeHtml($value);
            }
            foreach ($this->request->getPost() as $key => $value) {
                if ($value != $arr[$key]) {
                    $adapter = new Stream(APP_PATH .'/logs/signup.log');
                    $logger  = new Logger(
                        'messages',
                        [
                            'main' => $adapter,
                        ]
                    );
                $logger->error("Hacked Name: ".$arr["name"]." Email: ".$arr["email"]." Password: ".$arr["password"]);
                    break;
                }
            }
            $user->assign(
                $arr,
                [
                    'name',
                    'email',
                    'password'
                ]
            );

            $success = $user->save();

            $this->view->success = $success;

            if ($success) {
                $this->view->message = "Register succesfully";
            } else {
                $this->view->message = "Authentication Failure";
            }
        } else {
            $this->view->message = "Please fill all inputs";
        }
    }
}
