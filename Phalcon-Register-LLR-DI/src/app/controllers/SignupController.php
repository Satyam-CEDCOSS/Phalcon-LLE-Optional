<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{

    public function IndexAction()
    {
        // Redirected to View
    }

    public function registerAction()
    {
        $arr = $this->request->getPost();
        if ($arr["name"] && $arr["email"] && $arr["password"]) {
            $user = new Users();
            foreach ($this->request->getPost() as $key => $value) {
                $value = $this->escaper->escapeHtml($value);
                $arr[$key] = $value;
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
                $this->view->message = "Not Register succesfully due to following\
                 reason: <br>" . implode("<br>", $user->getMessages());
            }
        }else {
            $this->logger
                ->excludeAdapters(['login'])
                ->warning("Fill All Detail Name: ".$arr["name"]." Email: ".$arr["email"]." Password ".$arr["password"]);
                $this->view->message = "Fill all the Details";
        }
    }
}
