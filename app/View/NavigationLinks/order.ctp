<!--
/**
 * COmanage Registry Navigation Links Order View
 *
 * Copyright (C) 2011-13 University Corporation for Advanced Internet Development, Inc.
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
 * @copyright     Copyright (C) 2011-13 University Corporation for Advanced Internet Development, Inc.
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry
 * @since         COmanage Registry v0.8.2
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @version       $Id$
 */
-->

<?php
  // Set page title
  $params = array('title' => $title_for_layout);
  print $this->element("pageTitle", $params);

  // Add buttons to sidebar
  $sidebarButtons = $this->get('sidebarButtons');
  
  // Add button
  if($permissions['add']) {
    $sidebarButtons[] = array(
      'icon'    => 'circle-plus',
      'title'   => _txt('op.add') . ' ' . _txt('ct.navigation_links.1'),
      'url'     => array(
        'controller' => 'navigation_links', 
        'action' => 'add'
      )
    );
  }
  
  $this->set('sidebarButtons', $sidebarButtons);

  // Enable and configure drag/drop sorting 
  $this->Js->get('#sortable');
  $this->Js->sortable(array(
    'complete' => '$.post("/registry/navigation_links/reorder", $("#sortable").sortable("serialize"))',
    ));
?>

<table id="navigation_links" class="ui-widget">  
  <thead>
    <tr class="ui-widget-header">
      <th><?php print _txt('fd.ea.order'); ?></th>

      <th><?php print _txt('fd.link.title'); ?></th>
      <th><?php print _txt('fd.link.url'); ?></th>
      <th><?php print _txt('fd.desc'); ?></th>
      <th><?php print _txt('fd.actions'); ?></th>
    </tr>
  </thead>
  
  <tbody id="sortable">
    <?php $i = 0; ?>
    <?php foreach ($navigation_links as $c): ?>
      <tr id = "NavigationLinkId_<?php print $c['NavigationLink']['id']?>" class="line1">
        <td class = "order">
          <span class="ui-icon ui-icon-arrowthick-2-n-s"></span> 
        </td>
        <td>
          <?php
            print $this->Html->link($c['NavigationLink']['title'],
                                    array('controller' => 'navigation_links',
                                          'action' => ($permissions['edit'] ? 'edit' : ($permissions['view'] ? 'view' : '')), $c['NavigationLink']['id']));
          ?>
        </td>
        <td><?php print Sanitize::html($c['NavigationLink']['url']); ?></td>
        <td><?php print Sanitize::html($c['NavigationLink']['description']); ?></td>
        <td>
          <?php
            if($permissions['edit'])
              print $this->Html->link(_txt('op.edit'),
                                      array('controller' => 'navigation_links', 'action' => 'edit', $c['NavigationLink']['id']),
                                      array('class' => 'editbutton')) . "\n";
              
            if($permissions['delete'])
              print '<button class="deletebutton" title="' . _txt('op.delete') . '" onclick="javascript:js_confirm_delete(\'' . _jtxt(Sanitize::html($c['NavigationLink']['title'])) . '\', \'' . $this->Html->url(array('controller' => 'navigation_links', 'action' => 'delete', $c['NavigationLink']['id'])) . '\')";>' . _txt('op.delete') . '</button>';
          ?>
          <?php ; ?>
        </td>
      </tr>
    <?php $i++; ?>
    <?php endforeach; ?>
  </tbody>
  
  <tfoot>
    <tr class="ui-widget-header">
      <th colspan="5">
        <?php print $this->Paginator->numbers(); ?>
      </th>
    </tr>
  </tfoot>
</table>

<?php 
  // Buffer javascript and run after page elements are in place
  print $this->Js->writeBuffer();
?>