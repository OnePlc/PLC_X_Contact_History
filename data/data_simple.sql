--
-- add type fields
--
INSERT INTO `core_form_field` (`Field_ID`, `type`, `label`, `fieldkey`, `tab`, `form`, `class`, `url_view`, `url_list`, `show_widget_left`, `allow_clear`, `readonly`, `tbl_cached_name`, `tbl_class`, `tbl_permission`) VALUES
(NULL, 'select', 'Type', 'type_idfs', 'history-base', 'contacthistory-single', 'col-md-3', '', '/tag/api/list/contacthistory-single/type', 0, 1, 0, 'entitytag-single', 'OnePlace\\Tag\\Model\\EntityTagTable', 'add-OnePlace\\Contact\\History\\Controller\\TypeController'),
(NULL, 'datetime', 'Date', 'date', 'history-base', 'contacthistory-single', 'col-md-2', '', '', 0, 1, 0, '', '', '');



--
-- permission
--
INSERT INTO `permission` (`permission_key`, `module`, `label`, `nav_label`, `nav_href`, `show_in_menu`) VALUES
('add', 'OnePlace\\Contact\\History\\Controller\\TypeController', 'Add Type', '', '', '0');

--
-- Custom Tags
--
-- todo: add select before and check if tag exists
--
INSERT INTO `core_tag` (`Tag_ID`, `tag_key`, `tag_label`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
(NULL, 'type', 'Type', '1', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00');

--
-- quicksearch fix
--
INSERT INTO `settings` (`settings_key`, `settings_value`) VALUES ('quicksearch-contacthistory-customlabel', 'comment');

