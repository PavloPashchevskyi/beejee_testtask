<?php

/**
 * Description of TaskController
 *
 * @author ppd
 */
class TaskController extends Controller
{
    private function isAllowedMimeType($mime_type)
    {
        $allowed_mime_types = ['image/jpeg', 'image/gif', 'image/png'];
        return in_array($mime_type, $allowed_mime_types);
    }
    
    private function resizeImage($imageFileInfo, $destination, 
            $new_width = 320, $new_height = 240)
    {
        if(!empty($imageFileInfo['tmp_name'])) {
            
            $filename11 = $imageFileInfo['tmp_name'];

            list($width, $height) = getimagesize($filename11);
            
            if($width > 320 || $height > 240) {
                $nw = $new_width;
                $nh = $new_height;
            } else {
                $nw = $width;
                $nh = $height;
            }
            
            $image_p = imagecreatetruecolor($nw, $nh);

            $mimeTypeParts = explode('/', $imageFileInfo['type']);
            $imageType = $mimeTypeParts[1];
            $imageCreateFunction = 'imagecreatefrom'.$imageType;

            $image = $imageCreateFunction($filename11);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $nw, $nh, $width, $height);

            $imageToFileFunction = 'image'.$imageType;
            $written = $imageToFileFunction($image_p, $destination);
            if($written) {
                unlink($imageFileInfo['tmp_name']);
            }
        }
    }
    
    public function listAction($user_id = null)
    {
        $em = $this->getEntityManager();
        $taskModel = $em->getModel('task_manager:Task');
        if($user_id) {
            $user = $em->getModel('reg_auth:User')->findOne($user_id);
            $tasks = ($user['Login'] !== 'admin') ? 
                    $taskModel->findBy(['UserID' => $user_id]) : 
                    $taskModel->findAll();
        } else {
            $tasks = $taskModel->findAll();
        }
        
        $tmpTasks = $tasks;
        foreach ($tmpTasks as $key => $tmpTask) {
            $user = $em->getModel('reg_auth:User')->findOne($tasks[$key]['UserID']);
            $tasks[$key]['Login'] = (!empty($user['Login'])) ? $user['Login'] 
                    : 'guest';
            $tasks[$key]['Email'] = $user['Email'];
            unset($tasks[$key]['UserID']);
        }
        unset($tmpTasks);
        
        $tasks_header = (!empty($tasks[0]) && is_array($tasks[0])) ? 
                array_keys($tasks[0]) : [];
        $tasks_rows = [];
        foreach ($tasks_header as $columnName) {
            foreach ($tasks as $task) {
                $tasks_rows[] = $task[$columnName];
            }
        }
        
        $authorized_user_id = (!empty($_SESSION['loggedin_id'])) ?
                $_SESSION['loggedin_id'] : 0;
        $authorized_user = $em->getModel('reg_auth:User')->
                findOne($authorized_user_id);
        
        echo $this->render('Task/list.html.twig', [
            'tasks' => $tasks,
            'tasks_header' => $tasks_header,
            'authorized_user' => $authorized_user
        ]);
    }
    
    public function addAction()
    {
        if(isset($this->post['submittask'])) {
            $em = $this->getEntityManager();
            $taskModel = $em->getModel('task_manager:Task');
            if(!empty($_FILES['taskimage']['type']) && !$this->isAllowedMimeType($_FILES['taskimage']['type'])) {
                throw new InvalidDataException($_FILES['taskimage']['type'], 'loading file mime type');
            }
            $destination = 'application/modules/'. Application::getModuleName().
                '/views/includes/images/'.$_FILES['taskimage']['name'];
            $this->resizeImage($_FILES['taskimage'], $destination);
            
            $taskModel->insert([
                'task_id' => $taskModel->calculateNextID(),
                'UserID' => (isset($_SESSION['loggedin_id'])) ? 
                $_SESSION['loggedin_id'] : 0,
                'TaskText' => trim('"'.$this->post['tasktext'].'"'),
                'TaskImage' => (!empty($_FILES['taskimage']['name'])) ? 
                '"/'.$destination.'"' : '""',
            ]);
            
            $this->redirect('/task_manager/task/list');
            
        }
        echo $this->render('Task/add_edit.html.twig', ['action' => 'add']);
    }
    
    public function editAction($id)
    {
        $em = $this->getEntityManager();
        $taskModel = $em->getModel('task_manager:Task');
        
        if(isset($this->post['submittask'])) {
            if(!empty($_FILES['taskimage']['type']) && !$this->isAllowedMimeType($_FILES['taskimage']['type'])) {
                throw new InvalidDataException($_FILES['taskimage']['type'], 'loading file mime type');
            }
            
            $destination = 'application/modules/'. Application::getModuleName().
                        '/views/includes/images/'.$_FILES['taskimage']['name'];
            $this->resizeImage($_FILES['taskimage'], $destination);

            $taskModel->update([
                'UserID' => (isset($_SESSION['loggedin_id'])) ? 
                $_SESSION['loggedin_id'] : 0,
                'TaskText' => trim('"'.$this->post['tasktext'].'"'),
                'TaskImage' => (!empty($_FILES['taskimage']['name'])) ? 
                    '"/'.$destination.'"' : '""',
                'Done' => (isset($this->post['taskdone'])) ? 
                $this->post['taskdone'] : 0,
            ], ['task_id' => $id]);
            
            $this->redirect('/task_manager/task/list');
            
        }
        
        $task = $taskModel->findOne($id);
        
        echo $this->render('Task/add_edit.html.twig', [
            'action' => 'edit',
            'task' => $task
        ]);
    }
    
    public function deleteAction($id)
    {
        $em = $this->getEntityManager();
        $em->getModel('task_manager:Task')->delete(['task_id' => $id]);
        $this->redirect('/task_manager/task/list');
    }
    
    public function executeAction($id)
    {
        $em = $this->getEntityManager();
        $em->getModel('task_manager:Task')->update(['Done' => 1], 
                ['task_id' => $id]);
        $this->redirect('/task_manager/task/list');
    }
    
    public function reopenAction($id)
    {
        $em = $this->getEntityManager();
        $em->getModel('task_manager:Task')->update(['Done' => 0], 
                ['task_id' => $id]);
        $this->redirect('/task_manager/task/list');
    }
}
