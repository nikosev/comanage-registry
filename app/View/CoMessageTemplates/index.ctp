<?php
/**
 * COmanage Registry CO Message Templates Index View
 *
 * Portions licensed to the University Corporation for Advanced Internet
 * Development, Inc. ("UCAID") under one or more contributor license agreements.
 * See the NOTICE file distributed with this work for additional information
 * regarding copyright ownership.
 *
 * UCAID licenses this file to you under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with the
 * License. You may obtain a copy of the License at:
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry
 * @since         COmanage Registry v2.0.0
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

  // Add breadcrumbs
  print $this->element("coCrumb");
  $this->Html->addCrumb(_txt('ct.co_message_templates.pl'));

  // Add page title
  $params = array();
  $params['title'] = $title_for_layout;

  // Add top links
  $params['topLinks'] = array();

  if($permissions['add']) {
    $params['topLinks'][] = $this->Html->link(
      _txt('op.add-a', array(_txt('ct.co_message_templates.1'))),
      array(
        'controller' => 'co_message_templates',
        'action' => 'add',
        'co' => $cur_co['Co']['id']
      ),
      array('class' => 'addbutton')
    );
  }

  print $this->element("pageTitleAndButtons", $params);
?>

<table id="co_message_templates">
  <thead>
    <tr>
      <th><?php print $this->Paginator->sort('description', _txt('fd.desc')); ?></th>
      <th><?php print $this->Paginator->sort('context', _txt('fd.mt.context')); ?></th>
      <th><?php print $this->Paginator->sort('status', _txt('fd.status')); ?></th>
      <th><?php print _txt('fd.actions'); ?></th>
    </tr>
  </thead>
  
  <tbody>
    <?php $i = 0; ?>
    <?php foreach ($co_message_templates as $c): ?>
    <tr class="line<?php print ($i % 2)+1; ?>">
      <td>
        <?php
          print $this->Html->link($c['CoMessageTemplate']['description'],
                                  array('controller' => 'co_message_templates',
                                        'action' => ($permissions['edit'] ? 'edit' : ($permissions['view'] ? 'view' : '')),
                                        $c['CoMessageTemplate']['id']));
        ?>
      </td>
      <td>
        <?php
          if(!empty($c['CoMessageTemplate']['context'])) {
            print _txt('en.mt.context', null, $c['CoMessageTemplate']['context']);
          }
        ?>
      </td>
      <td>
        <?php
          if(!empty($c['CoMessageTemplate']['status'])) {
            print _txt('en.status', null, $c['CoMessageTemplate']['status']);
          }
        ?>
      </td>
      <td>
        <?php
          if($permissions['edit']) {
            print $this->Html->link(_txt('op.edit'),
                array(
                  'controller' => 'co_message_templates',
                  'action' => 'edit',
                  $c['CoMessageTemplate']['id']
                ),
                array('class' => 'editbutton')) . "\n";
          }
          
          if($permissions['duplicate']) {
            print $this->Html->link(_txt('op.dupe'),
                                    array('controller' => 'co_message_templates',
                                          'action' => 'duplicate',
                                          $c['CoMessageTemplate']['id']),
                                    array('class' => 'copybutton')) . "\n";
          }
          
          if($permissions['delete']) {
            print '<button type="button" class="deletebutton" title="' . _txt('op.delete')
              . '" onclick="javascript:js_confirm_generic(\''
              . _txt('js.remove') . '\',\''    // dialog body text
              . $this->Html->url(              // dialog confirm URL
                array(
                  'controller' => 'co_message_templates',
                  'action' => 'delete',
                  $c['CoMessageTemplate']['id']
                )
              ) . '\',\''
              . _txt('op.remove') . '\',\''    // dialog confirm button
              . _txt('op.cancel') . '\',\''    // dialog cancel button
              . _txt('op.remove') . '\',[\''   // dialog title
              . filter_var(_jtxt($c['CoMessageTemplate']['description']),FILTER_SANITIZE_STRING)  // dialog body text replacement strings
              . '\']);">'
              . _txt('op.delete')
              . '</button>';
          }
        ?>
        <?php ; ?>
      </td>
    </tr>
    <?php $i++; ?>
    <?php endforeach; ?>
  </tbody>
</table>
  
<?php
  print $this->element("pagination");