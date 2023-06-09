<?php

use Phalcon\Mvc\Controller;
use App\Component\myescaper;

class LoginController extends Controller
{
    public function indexAction()
    {
        // Redirected to View
    }



    public function loginAction()
    {
        if ($_POST['email'] && $_POST['password']) {
            $sql = 'SELECT * FROM Users WHERE email = :email: AND password = :password:';
            $query = $this->modelsManager->createQuery($sql);
            $escaper = new myescaper();
            $cars = $query->execute(
                [
                    'email' => $escaper->sanatize($_POST["email"]),
                    'password' => $escaper->sanatize($_POST["password"])
                ]
            );
            if (isset($cars[0])) {
                $this->view->message = "success";
                $this->logger
                    ->excludeAdapters(['error'])
                    ->warning("Login Success Email: " . $_POST["email"] . " Password: " . $_POST["password"]);
                $this->view->name = "Hello " . $cars[0]->name;
            } else {
                $this->logger
                    ->excludeAdapters(['access'])
                    ->warning("Fill Valid Detail login Email: " . $_POST["email"] . " Password: " . $_POST["password"]);
                $this->view->message = "error";
            }
        } else {
            $this->logger
                ->excludeAdapters(['access'])
                ->warning("Fill All Detail login Email: " . $_POST["email"] . " Password " . $_POST["password"]);
            $this->view->message = "error";
        }
    }
}
