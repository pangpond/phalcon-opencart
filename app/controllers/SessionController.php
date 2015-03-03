<?php
namespace Phoenix\Controllers;

use Phalcon\Tag as Tag;
use Phoenix\Models\Users;

// use Phoenix\Forms\MembersForm;

class SessionController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setLayout('login');
        Tag::setTitle('Sign Up/Sign In');
        $this->loadCustomTrans('session');
        parent::initialize();
    }

    public function indexAction()
    {
        if (!$this->request->isPost()) {
            Tag::setDefault('username', 'admin');
            // Tag::setDefault('password', 'phalcon');
        }
        
        if ($this->session->has("User_PersonID")) {
             //Retrieve its value
             echo $name = $this->session->get("User_PersonID");
         }

         Tag::setDefault('username', 'user70');
         Tag::setDefault('password', 'user70');
         // $form = new MembersForm(new Users(), array('new' => true));
         // $this->view->setVar("form", $form);

    }

    public function registerAction()
    {
        $request = $this->request;
        if ($request->isPost()) {

            $name = $request->getPost('name', array('string', 'striptags'));
            $username = $request->getPost('username', 'alphanum');
            $username = $request->getPost('username');
            $password = $request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');

            if ($password != $repeatPassword) {
                $this->flash->error('Passwords are diferent');
                return false;
            }

            $user = new Users();
            $user->username = $username;
            $user->password = sha1($password);
            $user->name = $name;
            $user->username = $username;
            $user->created_at = new Phalcon\Db\RawValue('now()');
            $user->active = 'Y';
            if ($user->save() == false) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                Tag::setDefault('username', '');
                Tag::setDefault('password', '');
                $this->flash->success('Thanks for sign-up, please log-in to start generating invoices');
                return $this->forward('session/index');
            }
        }
    }

    /**
     * Register authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession($user, $usersMeta)
    {
        $this->session->set('auth', array(
            'id' => $user->user_id,
            'name' => $user->name,
            'username' => $user->username,
            'role' => $user->role,
            'province' => $usersMeta['province'],
            'area' => $usersMeta['area']
        ));
    }

    /**
     * This actions receive the input from the login form
     *
     */
    public function startAction()
    {
        global $config;

        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $password = sha1($password);

            $user = Users::findFirst("username='$username' AND password='$password' AND active='1'");

            if ($user != false) {
                foreach ($user->usersMeta as $userMeta) {
                    if($userMeta->meta_key == 'province_id')
                        $userMetaArray['province'] = $userMeta->meta_value;
                    if($userMeta->meta_key == 'area_id')
                        $userMetaArray['area'] = $userMeta->meta_value;
                }

                $this->_registerSession($user, $userMetaArray);
                $this->flash->success('Welcome ' . $user->name);

                //Go to the last place
                $referer = $this->request->getHTTPReferer();
                if (strpos($referer, $this->request->getHttpHost()."/")!==false) {
                    // return $this->response->setHeader("Location", $referer = $config->application->baseUri);

                    $this->logger->log("[$this->clientIP] user {{$username}} is a logged in");

                    if(strstr($referer, "session"))
                        return $this->response->setHeader("Location", $config->application->baseUri);
                    else
                        return $this->response->setHeader("Location", $referer);
                } else {
                    return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
                }
            }

            $username = $this->request->getPost('username', 'alphanum');
            $user = Users::findFirst("username='$username' AND password='$password' AND active='1'");
            if ($user != false) {
                foreach ($user->userMeta as $userMeta) {
                    if($userMeta->meta_key == 'province_id')
                        $userMetaArray['province'] = $userMeta->meta_value;
                }

                $this->_registerSession($user, $userMetaArray);
                $this->flash->success('Welcome ' . $user->name);

                //Go to the last place
                $referer = $this->request->getHTTPReferer();
                if (strpos($referer, $this->request->getHttpHost()."/")!==false) {
                    return $this->response->setHeader("Location", $referer);
                } else {
                    return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
                }
            }

            $this->flash->error('Wrong username/password');
        }

        return $this->forward('session/index');
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction()
    {
        global $config;

        $auth = $this->session->get('auth');
        $this->logger->log("[$this->clientIP] user {{$auth['username']}} is a logouted");

        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        
        // return $this->redirect('session/index');
        return $this->response->setHeader("Location", $referer = $config->application->baseUri."");
    }
}
