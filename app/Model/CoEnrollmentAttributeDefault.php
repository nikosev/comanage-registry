<?php
/**
 * COmanage Registry CO Enrollment Attribute Default Model
 *
 * Copyright (C) 2013-15 University Corporation for Advanced Internet Development, Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software distributed under
 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 * @copyright     Copyright (C) 2013-15 University Corporation for Advanced Internet Development, Inc.
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry
 * @since         COmanage Registry v0.8
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @version       $Id$
 */

class CoEnrollmentAttributeDefault extends AppModel {
  // Define class name for cake
  public $name = "CoEnrollmentAttributeDefault";
  
  // Current schema version for API
  public $version = "1.0";
  
  // Add behaviors
  public $actsAs = array('Containable', 'Changelog' => array('priority' => 5));
  
  // Association rules from this model to other models
  public $belongsTo = array("CoEnrollmentAttribute");
  
  // Associated models that should be relinked to the archived attribute during Changelog archiving
  public $relinkToArchive = array('CoEnrollmentAttribute');
  
  // Default display field for cake generated views
  public $displayField = "value";
  
  // Default ordering for find operations
  public $order = array("value");
  
  // Validation rules for table elements
  public $validate = array(
    'affiliation'  => array(
      'required'   => false,
      'allowEmpty' => true,
      // This should probably use a dynamic validation rule instead
      'rule'       => '/.*/'
    ),
    'value' => array(
      'rule' => 'notBlank',
      'required'   => true,
      'allowEmpty' => true,
      'message'    => 'A value must be provided'
    ),
    'modifiable' => array(
      'rule'       => array('boolean'),
      'required'   => true,
      'allowEmpty' => false
    )
  );
  
  /**
   * Perform CoEnrollmentAttributeDefault model upgrade steps for version 1.0.5.
   * This function should only be called by UpgradeVersionShell.
   *
   * @since  COmanage Registry v1.0.5
   */
  
  public function _ug105() {
    // 1.0.5 fixes a bug (CO-1287) that created superfluous attribute default entries.
    // We clear out residual entries, those that are attached to enrollment attributes
    // of types that don't support default values.
    
    // Note these calls will honor changelog. That's probably OK, it means our deletes
    // aren't physical deletes, so if there's a bug here we can presumably recover,
    // but to the application the records will have just disappeared.
    
    // First pull the relevant CO Enrollment Attributes.
    $args = array();
    $args['conditions']['NOT'] = array(
      array('CoEnrollmentAttribute.attribute LIKE' => 'g:%'),
      array('CoEnrollmentAttribute.attribute LIKE' => 'k:%'),
      array('CoEnrollmentAttribute.attribute LIKE' => 'o:%'),
      array('CoEnrollmentAttribute.attribute LIKE' => 'r:%'),
    );
    $args['fields'] = array('id', 'attribute');
    $args['contain'] = false;
    
    $attrs = $this->CoEnrollmentAttribute->find('list', $args);
    
    // Now use the list of $attrs to delete extraneous defaults.
    
    $args = array();
    $args['conditions']['CoEnrollmentAttributeDefault.co_enrollment_attribute_id'] = array_keys($attrs);
    $args['fields'] = array('id', 'value');
    $args['contain'] = false;

    $attrDefs = $this->find('list', $args);
    
    $this->deleteAll(array('CoEnrollmentAttributeDefault.id' => array_keys($attrDefs)));
  }
}