<?php

use Phalcon\Mvc\Controller;
use App\Component\myescaper;

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
            $escaper = new myescaper();
            foreach ($this->request->getPost() as $key => $value) {
                $value = $escaper->sanatize($value);
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
                $this->logger
                ->excludeAdapters(['error'])
                ->warning("SignUp Success Name: ".$arr["name"]." Email: ".$arr["email"]." Password: ".$arr["password"]);

            } else {
                $this->view->message = "Authentication Failure";
            }
        }else {
            $this->logger
                ->excludeAdapters(['access'])
            ->warning("Fill All Detail Name: ".$arr["name"]." Email: ".$arr["email"]." Password: ".$arr["password"]);
            $this->view->message = "Fill all the Details";
        }
    }
}
