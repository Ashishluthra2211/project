<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;
require_once(ROOT .DS. 'vendor' . DS  . "class.imap.php");
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Google_Client;
use Google_Service_Gmail;
use Imap;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
	
	
	
	
	
    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Network\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function display(...$path)
    {
		$post = [];
			$userTableObject = TableRegistry::get('Users');
		
		
		
		$email = new Imap();

		$connect = $email->connect(
			'{:993/imap/ssl}INBOX', //host
			'', //email
			'' //password
		);

	if($connect){
		$inbox = $email->getMessages('html', 'UNSEEN');
		// $result = imap_search(, 'UNSEEN');
		
		foreach($inbox['data'] as $emaildata){
			
			
			
		$user_register = $userTableObject->newEntity();
		$user_patched = $userTableObject->patchEntity($user_register,$post );			
		$user_patched->subject = $emaildata['subject'];
		$user_patched->add_date = $emaildata['date'];
		$user_patched->message = $emaildata['message'];
		if(!empty($emaildata['attachments'][0])){
			$user_patched->attachment = $emaildata['attachments'][0];
		}
		$userTableObject->save($user_patched);
		}
		
	
}else{
	echo json_encode(array("status" => "error", "message" => "Not connect1."), JSON_PRETTY_PRINT);
}
		
		
		
		
		print_r('Added List');  die;
		
        // $count = count($path);
        // if (!$count) {
            // return $this->redirect('/');
        // }
        // if (in_array('..', $path, true) || in_array('.', $path, true)) {
            // throw new ForbiddenException();
        // }
        // $page = $subpage = null;

        // if (!empty($path[0])) {
            // $page = $path[0];
        // }
        // if (!empty($path[1])) {
            // $subpage = $path[1];
        // }
        // $this->set(compact('page', 'subpage'));

        // try {
            // $this->render(implode('/', $path));
        // } catch (MissingTemplateException $exception) {
            // if (Configure::read('debug')) {
                // throw $exception;
            // }
            // throw new NotFoundException();
        // }
    }
}
