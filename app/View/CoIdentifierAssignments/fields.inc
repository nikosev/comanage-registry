<!--
/**
 * COmanage Registry CO Identifier Assignment Fields
 *
 * Copyright (C) 2012 University Corporation for Advanced Internet Development, Inc.
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
 * @copyright     Copyright (C) 2012 University Corporation for Advanced Internet Development, Inc.
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry
 * @since         COmanage Registry v0.6
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @version       $Id$
 */
-->
<?php
  // Determine if fields are editable
  $e = false;
  
  if(($this->action == "add" && $permissions['add']) || ($this->action == "edit" && $permissions['edit']))
    $e = true;
    
  // We shouldn't get here if we don't have at least read permission, but check just in case
  
  if(!$e && !$permissions['view'])
    return(false);
    
  if($e) {
    print $this->Html->link(_txt('op.cancel'),
                            array('controller' => 'co_identifier_assignments', 'action' => 'index', 'co' => $cur_co['Co']['id']),
                            array('class' => 'cancelbutton'));
  } else {
    print $this->Html->link(_txt('op.back'),
                            array('controller' => 'co_identifier_assignments', 'action' => 'index', 'co' => $cur_co['Co']['id']),
                            array('class' => 'backbutton'));
  }
  
  print '
    <br />
    <br />
    ';
  
  print $this->Form->hidden('co_id', array('default' => $cur_co['Co']['id'])) . "\n";
?>
<script type="text/javascript">
  <!-- JS specific to these fields -->
  
  function fields_update_gadgets() {
    // Enable or disable gadgets according to current state
    
    document.getElementById('CoIdentifierAssignmentMaximum').disabled =
      (document.getElementById('CoIdentifierAssignmentAlgorithm').value != 'R');
  }
  
  function js_local_onload() {
    fields_update_gadgets();
  }
  
  function on_submit()
  {
    // There appears to be an issue with Cake blackholing the request if text
    // fields are disabled (but not, strangely, if checkboxes or selects are
    // disabled). Renable these fields (in case they were disabled) to allow
    // the form to submit. See CO-350.
    
    document.getElementById('CoIdentifierAssignmentMaximum').disabled = false;
    document.getElementById('CoIdentifierAssignmentMinimum').disabled = false;
  }
</script>
<table id="<?php print $this->action; ?>_co_identifier_assignment" class="ui-widget">
  <tbody>
    <tr class="line1">
      <td>
        <b><?php print _txt('fd.desc'); ?></b><br />
      </td>
      <td>
        <?php print ($e
                     ? $this->Form->input('description')
                     : Sanitize::html($co_identifier_assignments[0]['CoIdentifierAssignment']['description'])); ?>
      </td>
    </tr>
    <tr class="line2">
      <td>
        <b><?php print _txt('fd.type'); ?></b>
      </td>
      <td>
        <?php
          $attrs = array();
          $attrs['value'] = (isset($co_identifier_assignments) ? $co_identifier_assignments[0]['CoIdentifierAssignment']['identifier_type'] : "");
          $attrs['empty'] = false;
          
          if($e) {
            print $this->Form->select('identifier_type',
                                      $identifier_types,
                                      $attrs);
            
            if($this->Form->isFieldError('identifier_type')) {
              print $this->Form->error('identifier_type');
            }
          } else {
            print Sanitize::html($co_identifier_assignments[0]['CoIdentifierAssignment']['identifier_type']);
          }
        ?>
      </td>
    </tr>
    <tr class="line1">
      <td>
        <b><?php print _txt('fd.identifier.login'); ?></b><br />
        <font class="desc"><?php print _txt('fd.identifier.login.desc'); ?></font>
      </td>
      <td>
        <?php print ($e ? $this->Form->input('login') : Sanitize::html($co_identifier_assignments[0]['CoIdentifierAssignment']['login'])); ?>
      </td>
    </tr>
    <tr class="line2">
      <td>
        <b><?php print _txt('fd.ia.algorithm'); ?></b><br />
        <font class="desc"><?php print _txt('fd.ia.algorithm.desc'); ?></font>
      </td>
      <td>
        <?php
          global $cm_lang, $cm_texts;
          $attrs['value'] = (isset($co_identifier_assignments)
                              ? $co_identifier_assignments[0]['CoIdentifierAssignment']['algorithm']
                              : IdentifierAssignmentEnum::Sequential);
          $attrs['empty'] = false;
          $attrs['onChange'] = 'fields_update_gadgets()';
          
          if($e) {
            print $this->Form->select('algorithm',
                                      $cm_texts[ $cm_lang ]['en.ia.algorithm'],
                                      $attrs);
            
            if($this->Form->isFieldError('algorithm')) {
              print $this->Form->error('algorithm');
            }
          } else {
            print _txt('en.ia.algorithm', null, $co_identifier_assignments[0]['CoIdentifierAssignment']['algorithm']);
          }
        ?>
      </td>
    </tr>
    <tr class="line1">
      <td>
        <b><?php print _txt('fd.ia.format'); ?></b><br />
        <font class="desc"><?php print _txt('fd.ia.format.desc'); ?></font>
      </td>
      <td>
        <?php print ($e ? $this->Form->input('format') : Sanitize::html($co_identifier_assignments[0]['CoIdentifierAssignment']['format'])); ?>
        <?php if($e): ?>
        <select
          id="CoIdentifierAssignmentPrefabricatedFormats"
          onchange="javascript:document.getElementById('CoIdentifierAssignmentFormat').value=this.value">
          <option value=""><?php print _txt('fd.ia.format.prefab'); ?></option>
          <option value=""><?php print _txt('fd.ia.format.p0'); ?></option>
          <option value="(G).(F)[1:.(#)]@myvo.org"><?php print _txt('fd.ia.format.p1'); ?></option>
          <option value="(G)[1:.(M:1)].(F)[2:.(#)]@myvo.org"><?php print _txt('fd.ia.format.p2'); ?></option>
          <option value="(G:1)(M:1)(F:1)(#)@myvo.org"><?php print _txt('fd.ia.format.p3'); ?></option>
        </select>
        <?php endif; ?>
      </td>
    </tr>
    <tr class="line2">
      <td>
        <b><?php print _txt('fd.ia.minimum'); ?></b><br />
        <font class="desc"><?php print _txt('fd.ia.minimum.desc'); ?></font>
      </td>
      <td>
        <?php print ($e ? $this->Form->input('minimum') : Sanitize::html($co_identifier_assignments[0]['CoIdentifierAssignment']['minimum'])); ?>
      </td>
    </tr>
    <tr class="line1">
      <td>
        <b><?php print _txt('fd.ia.maximum'); ?></b><br />
        <font class="desc"><?php print _txt('fd.ia.maximum.desc'); ?></font>
      </td>
      <td>
        <?php print ($e ? $this->Form->input('maximum') : Sanitize::html($co_identifier_assignments[0]['CoIdentifierAssignment']['maximum'])); ?>
      </td>
    </tr>
    <tr class="line2">
      <td>
        <b><?php print _txt('fd.ia.exclusions'); ?></b><br />
        <font class="desc"><?php print _txt('fd.ia.exclusions.desc'); ?></font>
      </td>
    </tr>
  </tbody>
</table>
<?php
  if($e)
    print $this->Form->submit($submit_label, array('onClick' => 'on_submit()'));
?>