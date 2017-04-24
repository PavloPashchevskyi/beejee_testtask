<?php

/**
 * Description of UserController
 *
 * @author ppd
 */
class UserController extends Controller
{
    public function registerAction()
    {
        if(!isset($this->post['loginpassword'])) {
            echo $this->render('User/register.html.twig');
        } else {
            
            $loginCorrect = preg_match('/^[A-Za-z]{1}[A-Za-z0-9]{1,31}$/', $this->post['login']);
            
            if(empty($this->post['login']) || !$loginCorrect) {
                $messages[] = [
                    'display' => true,
                    'type' => 'warning',
                    'text' => 'Login must not be empty, must contain from 1 to 32 symbols and start with letter.'
                ];
            }
        
            if(!empty($this->post['password']) && strlen($this->post['password']) >= 3) {
                $hash = password_hash($this->post['password'], PASSWORD_DEFAULT);
            } else {
                $messages[] = [
                    'display' => true,
                    'type' => 'danger',
                    'text' => 'Password may not be empty or less than 3 symbols.'
                ];
            }
            
            if($this->post['password'] != $this->post['confirm_password']) {
                $messages[] = [
                    'display' => true,
                    'type' => 'warning',
                    'text' => 'Inputed password is not the same as confirmed one!'
                ];
            }
            
            $emailCorrect = preg_match('/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD', $this->post['email']);
            
            if(empty($this->post['email']) || !$emailCorrect) {
                $messages[] = [
                    'display' => true,
                    'type' => 'warning',
                    'text' => 'Inputed email is not valid!'
                ];
            }
            
            if(!empty($this->post['login']) && $loginCorrect 
                    && !empty($this->post['password']) 
                    && strlen($this->post['password']) >= 3
                    && $this->post['password'] == $this->post['confirm_password']
                    && !empty($this->post['email']) && $emailCorrect) {
                
                $em = $this->getEntityManager();
                $userModel = $em->getModel('reg_auth:User');
                
                $users = $userModel->findBy(['Login' => '"'.$this->post['login'].'"']);
                
                if(!empty($users)) {
                    $messages[] = [
                        'display' => true,
                        'type' => 'warning',
                        'text' => 'Unable to create an account. User with login '.$this->post['login'].' already exists.'
                    ];
                    $inserted = false;
                } else {
                    $nextID = $userModel->calculateNextID();
                    $inserted = $userModel->insert([
                        'user_id' => $nextID,
                        'Login' => '"'.$this->post['login'].'"',
                        'Passwd' => '"'.$hash.'"',
                        'Email' => '"'.$this->post['email'].'"'
                    ]);

                    if($inserted) {
                        $messages[] = [
                            'display' => true,
                            'type' => 'success',
                            'text' => 'You are successfully registered!'
                        ];
                        $_SESSION['loggedin_id'] = $nextID;
                        $this->redirect('/reg_auth/user/profile/'.$_SESSION['loggedin_id']);
                    } else {
                        $messages[] = [
                            'display' => true,
                            'type' => 'warning',
                            'text' => 'Unable to create an account. Probably, incorrect SQL query for inserting data into database.'
                        ];
                    }
                }
            }
            
            echo $this->render('User/register.html.twig', ['messages' => $messages]);
            
        }
    }
    
    public function authorizeAction()
    {
        if(empty($_SESSION['loggedin_id'])) {
            
            $loginCorrect = (!empty($this->post['login'])) ? preg_match('/^[A-Za-z]{1}[A-Za-z0-9]{1,31}$/', $this->post['login']) : false;
            
            if(empty($this->post['login']) || !$loginCorrect) {
                $messages[] = [
                    'display' => true,
                    'type' => 'warning',
                    'text' => 'Login must not be empty, must contain from 1 to 32 symbols and start with letter.'
                ];
            }
        
            if(!empty($this->post['password']) && strlen($this->post['password']) >= 3) {
                $hash = password_hash($this->post['password'], PASSWORD_DEFAULT);
            } else {
                $messages[] = [
                    'display' => true,
                    'type' => 'danger',
                    'text' => 'Password may not be empty or less than 3 symbols.'
                ];
            }
            if(!empty($this->post['login']) && $loginCorrect && !empty($this->post['password']) && strlen($this->post['password']) >= 3) {
                $em = $this->getEntityManager();
                $user = $em->getModel('reg_auth:User')->findBy(['Login' => '"'.$this->post['login'].'"']);
                if(!empty($user) && is_array($user) 
                        && $this->post['login'] == $user[0]['Login'] 
                        && password_verify($this->post['password'], $hash)) {
                    $_SESSION['loggedin_id'] = $user[0]['user_id'];
                    $messages[] = [
                        'display' => true,
                        'type' => 'success',
                        'text' => 'You are successfully logged in!'
                    ];
                    $this->redirect('/reg_auth/user/profile/'.$_SESSION['loggedin_id']);
                } else {
                    $messages[] = [
                        'display' => true,
                        'type' => 'warning',
                        'text' => 'Incorrect login and/or password'
                    ];
                }
            }
            echo (isset($this->post['loginpassword'])) ? $this->render('User/authorize.html.twig', [
                'messages' => $messages
                ]) : $this->render('User/authorize.html.twig');
        } else {
            $loggedin_id = (is_numeric($_SESSION['loggedin_id'])) ? $_SESSION['loggedin_id'] : '';
            $redirect_href = (empty($_SESSION['redirect_registered_to'])) ? '/reg_auth/user/profile/'.$loggedin_id : $_SESSION['redirect_registered_to'];
            $this->redirect($redirect_href);
        }
    }
    
    public function profileAction($id)
    {
        $em = $this->getEntityManager();
        $user = $em->getModel('reg_auth:User')->findOne($id);
        echo $this->render('User/profile.html.twig', [
            'user' => $user,
            ]);
    }
    
    public function logoutAction()
    {
        unset($_SESSION['loggedin_id']);
        $this->redirect('/reg_auth/user/authorize');
    }
}
