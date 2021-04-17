<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EventsController
 *
 * @author GayasuddinK
 */

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Collection\Collection;

class EventsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Cookie');	
    }
	
	public function add() {
        $this->set('title_for_layout', __('Add Event'));
        $event = $this->Events->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $event = $this->Events->patchEntity($event, $this->request->getData(), ['validate' => 'AddEditEvent']);
            if (empty($event->errors())) {
            	$event->created	=	date("Y-m-d H:i:s");
				$event->modified	=	date("Y-m-d H:i:s");

                //pr($event);die;
                if ($this->Events->save($event)) {
                    
                    $this->Flash->success(__('Event has been added'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Event could not be added. Please, try again.'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below.'));
            }
        }
        $this->set(compact('event'));

    }

    public function index() {
        if (!empty($this->request->query)) {
            $this->request->session()->write('sorting_query', $this->request->query);
        }
        $this->set('title_for_layout', __('List Events'));
        $this->request->session()->delete('sorting_query');
        if (!empty($this->request->query)) {
			$this->request->session()->write('sorting_query', $this->request->query);
        }
        $limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		
        if ($this->request->is('Ajax')) {
            $this->viewBuilder()->layout(false);
        }
		
        $query = $this->Events->find('search', ['search' => $this->request->query]);
		        
		$users = $this->paginate($query, [
			'limit'		=>	$limit,
			'order'		=>	['Events.id'=>'DESC'],
			'group'		=>	['Events.id']
		]);
		if(isset($this->request->params['?']['page'])){
			$page = $this->request->params['?']['page'];
		}else{
			$page = '';
		}
		
        $this->set(compact('users','page','unverified','pc'));
    }

    public function edit($id = NULL) {
        $this->set('title_for_layout', __('Edit User'));
        try {
            $event = $this->Events->get($id);
        } catch (\Throwable $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $event = $this->Events->patchEntity($event, $this->request->getData(), ['validate' => 'AddEditEvent']);
            if (empty($event->errors())) {
				$event->modified	=	date("Y-m-d H:i:s");
                if ($this->Events->save($event)) {
                    $this->Flash->success(__('Event has been updated'));
                    return $this->redirect(['action' => 'index', '?' => $this->request->session()->read('sorting_query')]);
                }
                $this->Flash->error(__('Event could not be added, please try again.'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below.'));
            }
        }
        $this->set(compact('event'));
    }
	
	public function detail($id = NULL) {
        $this->set('title_for_layout', __('Event Detail'));
        try {
            $event = $this->Events->get($id);

        } catch (\Throwable $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $this->set(compact('event'));
    }

    public function delete($id = null) {
        if (isset($id) && !empty($id))
        {
            $entity = $this->Events->get($id);
            if($this->Events->delete($entity)) {
                $this->Flash->success(__('Event has been successfully deleted.'));
                return $this->redirect(['action' => 'index']);
            }else
            {
                $this->Flash->error(__('Unable to delete Event, please try again.'));
                return $this->redirect(['action' => 'index']);
            }
        }else
        {
            $this->Flash->error(__('Unable to delete Event, please try again.'));
            return $this->redirect(['action' => 'index']);
        }
    }
	
	
}
