<?php

App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class DrugsController extends AppController {

    public $name = 'Drugs';
    public $paginate = array();
    public $helpers = array();

    public function beforeFilter() {
        parent::beforeFilter();
        if (isset($this->Auth)) {
            $this->Auth->allow('index', 'view');
        }
    }

    function index($name = null) {
        $scope = array(
            'Drug.active_id IS NULL',
        );
        if (!empty($name)) {
            $name = Sanitize::clean($name);
            $scope['OR'] = array(
                'Drug.name LIKE' => "%{$name}%",
                'Drug.name_english LIKE' => "%{$name}%",
                'Drug.license_id LIKE' => "%{$name}%",
                'Drug.vendor LIKE' => "%{$name}%",
                'Drug.manufacturer LIKE' => "%{$name}%",
            );
        }
        $this->paginate['Drug'] = array(
            'limit' => 20,
            'order' => array('Drug.submitted' => 'DESC'),
        );
        $this->set('url', array($name));
        if (!empty($name)) {
            $name = "{$name} 相關";
        }
        $this->set('title_for_layout', $name . '藥品一覽 @ ');
        $this->set('items', $this->paginate($this->Drug, $scope));
    }

    function view($id = null) {
        if (!empty($id)) {
            $this->data = $this->Drug->find('first', array(
                'conditions' => array('Drug.id' => $id),
            ));
            if (!empty($this->data['Drug']['linked_id'])) {
                $linkedId = $this->data['Drug']['linked_id'];
            } else {
                $linkedId = $this->data['Drug']['id'];
            }
            if (!empty($this->data['Drug']['active_id'])) {
                $activeId = $this->data['Drug']['active_id'];
            } else {
                $activeId = $this->data['Drug']['id'];
            }
        }
        if (empty($this->data)) {
            $this->Session->setFlash(__('Please do following links in the page', true));
            $this->redirect(array('action' => 'index'));
        } else {
            $logs = $this->Drug->find('list', array(
                'conditions' => array(
                    'OR' => array(
                        'Drug.active_id' => $activeId,
                        'Drug.id' => $activeId,
                    ),
                ),
                'fields' => array('id', 'submitted'),
                'order' => array('Drug.submitted' => 'DESC'),
            ));
            $this->set('title_for_layout', "{$this->data['Drug']['name']} {{$this->data['Drug']['name_english']}} @ ");
            $this->set('desc_for_layout', "{$this->data['Drug']['name']} {$this->data['Drug']['name_english']} / {$this->data['Drug']['disease']} / ");
            $this->set('logs', $logs);
        }
    }

    function admin_index() {
        $this->paginate['Drug'] = array(
            'limit' => 20,
        );
        $this->set('items', $this->paginate($this->Drug, array('Drug.active_id IS NULL')));
    }

    function admin_view($id = null) {
        if (!$id || !$this->data = $this->Drug->read(null, $id)) {
            $this->Session->setFlash(__('Please do following links in the page', true));
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->Drug->create();
            if ($this->Drug->save($this->data)) {
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Please do following links in the page', true));
            $this->redirect($this->referer());
        }
        if (!empty($this->data)) {
            if ($this->Drug->save($this->data)) {
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
        $this->set('id', $id);
        $this->data = $this->Drug->read(null, $id);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Please do following links in the page', true));
        } else if ($this->Drug->delete($id)) {
            $this->Session->setFlash(__('The data has been deleted', true));
        }
        $this->redirect(array('action' => 'index'));
    }

}