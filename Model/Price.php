<?php

App::uses('AppModel', 'Model');

/**
 * Price Model
 *
 * @property Drug $Drug
 */
class Price extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'date_end';


    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'License' => array(
            'className' => 'License',
            'foreignKey' => 'license_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}
