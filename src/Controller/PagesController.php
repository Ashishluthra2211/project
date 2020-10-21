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

		// $connect = $email->connect(
			// '{imap.gmail.com:993/imap/ssl}INBOX', //host
			// '', //username
			// '' //password
			// ,OP_READONLY
		// );
$hostname = "{imap.gmail.com:993/imap/ssl}INBOX";
$username = '';
$password = '';
$inbox = imap_open($hostname,$username,$password);


$mails = imap_search($inbox, 'UNSEEN');







	if($mails){
		rsort($mails);
		
		
		// $inbox = $email->getMessages('html', 'UNSEEN');
		// $result = imap_search(, 'UNSEEN');
		
		foreach($mails as $emaildata){
			
			$overview = imap_fetch_overview($inbox,$emaildata,0);
			$message = imap_fetchbody($inbox,$emaildata, 1);
			echo "<pre>";
			print_r($overview);
		$user_register = $userTableObject->newEntity();
		$user_patched = $userTableObject->patchEntity($user_register,$post );			
		$user_patched->subject = $overview[0]->subject;
		$user_patched->add_date = $overview[0]->date;
		$user_patched->message = $message;
		if(!empty($overview[0]->attachments)){
			$user_patched->attachment = $overview[0]->attachments;
		}
		$userTableObject->save($user_patched);
		}
		
		print_r('Added List');  die;
	
}else{
	echo json_encode(array("status" => "error", "message" => "Not Data found."), JSON_PRETTY_PRINT);
}
		
		
		
		
		
        
    }
}
