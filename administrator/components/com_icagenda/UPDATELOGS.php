<?php defined('_JEXEC') or die(); ?>

<big><b style="color: red;">iC</b><span style="color: #666666;">agenda</span> - ChangeLog</big>
================================================================================
: <b style="color: green;">&nbsp;&nbsp;&nbsp;+</b><i style="color: green;">&nbsp;added</i>&nbsp;&nbsp;|&nbsp;&nbsp;<b style="color: red;">-</b><i style="color: red;">&nbsp;removed</i>&nbsp;&nbsp;|&nbsp;&nbsp;<b style="color: #666;">~</b><i style="color: #666;">&nbsp;changed</i>&nbsp;&nbsp;|&nbsp;&nbsp;<b style="color: #cc8833;">!</b><i style="color: #cc8833;">&nbsp;important</i>&nbsp;&nbsp;|&nbsp;&nbsp;<b style="color: blue;">#</b><i style="color: blue;"> &nbsp;fixed</i><br/><i>Info: access to the beta versions and pre-releases are reserved for Pro users.</i><br/>iCagenda™ is distributed under the terms of the GNU General Public License version 2 or later; see LICENSE.txt.


iCagenda 3.2.7 <small style="font-weight:normal;">(2013.11.23)</small>
================================================================================
~ [MODULE iC calendar] Changed : minor edit in sql request of module iC calendar
# [LOW] Fixed : bug in breadcrumbs event details view.
# [THEME PACKS][LOW] Fixed : possible issue of display break when using ic_rounded theme (depending of your site template).

Changed files in 3.2.7
~ [MODULE] modules/mod_iccalendar/helper.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_list.php
~ site/views/list/tmpl/event.php


iCagenda 3.2.6 <small style="font-weight:normal;">(2013.11.21)</small>
================================================================================
! New : Option (menu and global) to display Category informations; title and/or description (in header of list of events).
+ Added : Event Details view added to Breadcrumbs.
+ Added : Option Top & Bottom for navigation arrows (list of events).
# [MODULE iC Event List][PRO] Fixed : time not displayed correctly in module iC Event List.
# [MODULE iC Event List][PRO] Fixed : clic to event details views was not working on IE 9 (and under) with icrounded layout.

Changed files in 3.2.6
~ admin/config.xml
+ admin/models/fields/modal/icmulti_checkbox.php
+ admin/models/fields/modal/icmulti_opt.php
~ admin/models/forms/category.xml
~ admin/views/icagenda/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/event.php
~ site/views/list/view.html.php


iCagenda 3.2.5 <small style="font-weight:normal;">(2013.11.11)</small>
================================================================================
! Terms and Conditions Option added to registration form.
! Design compatibility with Joomla 3.2.0 (admin header html) and enhancements in admin display.
+ [THEME PACKS] Added css and php integration of registration infos in calendar tooltip.
+ [MODULE iC Calendar] Added : Options to display city, name of venue, short description, and registration infos (number of seats, seats available and already registered).
~ [MODULE iC Calendar] Changed : 'today' day is now using joomla timezone (was server timezone before).

Changed files in 3.2.5
~ admin/add/css/icagenda.css
~ admin/add/css/icagenda.j25.css
~ admin/config.xml
- admin/models/fields/eventtitle.php
+ admin/models/fields/modal/ictxt_article.php
+ admin/models/fields/modal/ictxt_content.php
+ admin/models/fields/modal/ictxt_default.php
+ admin/models/fields/modal/ictxt_type.php
~ admin/models/forms/category.xml
~ admin/models/forms/event.xml
~ admin/views/categories/view.html.php
~ admin/views/category/tmpl/edit.php
~ admin/views/category/view.html.php
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php
~ admin/views/events/view.html.php
~ admin/views/icagenda/view.html.php
~ admin/views/info/view.html.php
~ admin/views/mail/view.html.php
~ admin/views/registrations/view.html.php
~ admin/views/themes/view.html.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ script.icagenda.php
- site/add/js/address.js
- site/add/js/dates.js
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ site/views/list/tmpl/registration.php


iCagenda 3.2.4 <small style="font-weight:normal;">(2013.10.29)</small>
================================================================================
+ [MODULE iC Event List][PRO] Added : category color as background of the date, in 'default' layout.
~ [MODULE iC Calendar] Changed : authorizes <br /> and <br> html tags in Short Description.
# Fixed : Issue when only sunday selected for period events, all days of the week were displayed.
# Fixed : Not display of Google Maps (blank) after update to last release 3.2.3, when Google Maps Global Options were not set before.
# Fixed : safehtml filter from joomla not working in frontend (skipping html tags, as should not). Filter set now to raw to not skip tags.
# Fixed : issue when access levels to Event Submission Form set to multiple levels (was not filtering access levels as expected).
# [THEME PACKS] Fixed : Issue Alignement of editor buttons in submission form.
# [MODULE iC Event List][PRO] Fixed : wrong display of events in column, due to a conflict in some site templates.

Changed files in 3.2.4
~ admin/views/event/tmpl/edit.php
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/list/tmpl/default.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/view.html.php


iCagenda 3.2.3 <small style="font-weight:normal;">(2013.10.20)</small>
================================================================================
! [THEME PACKS] Updated : enhancements of ic_rounded theme pack, to give a better responsive experience. All table tags have been removed, and replace with div tags, and with addition of @media css styling depending of the device (mobile, tablet, desktop). This new version of ic_rounded theme pack will now have version number respectively to the component version. (to improve tracking updates by users creating their own theme. For your information, a website page is in preparation for you to get more information and documentation about creating and updating a personal Theme Pack, and new features for Theme Pack manager are in brainstorming!).
! No loading of Google Maps scripts, if no address is set, or if global option is set on Hide (to speed up loading when this files are not needed).
+ Added : missing Options Week Days in Frontend Submission Form.
+ [MODULE iC Event List][PRO] Added : Options to display date and time, city, short description, and registration infos (number of seats, seats available and already booked).
~ [THEME PACKS] Changed : enhancements of the back arrow to detect if a previous page has been visited. Code in themes php file is now simplified.
~ Changed : enhancements of Open Graph tags (title, type, image, url, description, sitename).
~ Changed : enhancements and changes in <hn> tags used in iCagenda, to able a better structural hierarchy of list of events. (auto-detect if page heading is displayed in content or not, to set properly the Hn tag).
~ Changed : views php files to speed up loading of iCagenda (list of events, event details and event registration).
# Fixed : Calendar Issue; Bug in some countries about the time change. If a date of an event over a period was the day of the time change, it was generated 2 times. The new feature integrates this setting to not double this day.


Changed files in 3.2.3
+ admin/models/fields/modal/icvalue_field.php
+ admin/models/fields/modal/icvalue_opt.php
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
+ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_calendar.php
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_list.php
~ [THEME PACKS] site/themes/packs/default/default_registration.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
+ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_alldates.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_calendar.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_list.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_registration.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/send.php


iCagenda 3.2.2 <small style="font-weight:normal;">(2013.10.10)</small>
================================================================================
! [iCicons] Use of integrated vector icons 'iCicons' designed for iCagenda (will evolve!).
# Fixed : List of dates in registration form (was not filtering by weekdays).
# [iCicons] Fixed : Android not display of arrows in ascii code (calendar, back button, back/next navigation).
# [iCicons] Fixed : Iphone/Ipad, arrows were not clickable (calendar, back button, back/next navigation).
# Fixed : ACL access levels filtering for events in front-end.
# Fixed : Request of Itemid in submit form.
~ Changed : better filtering of Approval access.
~ Changed : clean-up of some php functions, and sql request in frontend.
~ [THEME PACKS] Changed : enhancements of module css, and adding vector icon for back button.

Changed files in 3.2.2
~ admin/views/events/tmpl/default.php
~ icagenda.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ site/helpers/icmodel.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/default.xml
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
+ [FOLDER] media/icicons/
+ [FOLDER] media/icicons/fonts/
+ media/icicons/fonts/iCicons.eot
+ media/icicons/fonts/iCicons.svg
+ media/icicons/fonts/iCicons.ttf
+ media/icicons/fonts/iCicons.woff
+ media/icicons/lte-ie7.js
+ media/icicons/style.css


iCagenda 3.2.1 <small style="font-weight:normal;">(2013.10.07)</small>
================================================================================
! First Stable release with 'Submit an Event' feature. For Users of the free version, see all the Release Notes of previous RC versions (available for Pro).
~ Changed : Use of DATE_FORMAT_LC3 in list of events, admin (to get date in Russian on windows server).
# Fixed : Remove nowrap css class attribute, to prevent not wrapping to the next line for long title (this is solved in iCagenda, but you may have the same problem in Joomla 3 articles. Proposal of modification added on Joomla core Github).
# Fixed : Error message when updating from an older version, if category filter was set to one category (new option multiple-categories filtering).

Changed files in 3.2.1
~ admin/views/events/tmpl/default.php
~ site/helpers/icmodel.php
~ site/models/list.php


iCagenda 3.2.0 RC4 <small style="font-weight:normal;">(2013.10.04)</small>
================================================================================
! Added : New option, Multi-selection of categories, in parameters of the menu link to list of events.
! Changed : Updated Google Maps API to V3 https
+ Added : Notification email to a user when his event submitted has been approved by a manager.
+ Added : Redirect to login page if Approval Manager is not connected on event details page (replacing 404 page).
+ Added : New icons for 'Approve this event' (J2.5 using icons, and J3 using icomoon).
+ Added : New tooltip script for manager icons.
+ Added : Router SEF for Submit an Event.
# [LOW] Bug : inserting an extra number data at the end of the footer text line, in notification email send to Approval managers.
# [LOW] Bug : Number of events in header was not well set, when an Approval Manager is logged-in.
# [LOW] Display : Display of info tooltip when Phone Field not shown in registration form.
# [MEDIUM] Bug : display of 'sunday', when no days of the week selected for a period event, in event details view.
~ [THEME PACKS] Changed : Manager Icons are removed from theme packs (to prevent not display in personal theme pack) and added in event.php file.
~ Changed : Attachment opens now in a new window (target blanck).

Changed files in 3.2.0 RC4
~ admin/config.xml
~ admin/models/fields/modal/cat.php
+ admin/models/fields/modal/multicat.php
~ admin/models/forms/event.xml
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ icagenda.xml
~ site/add/css/style.css
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ site/router.php
~ [THEME PACKS] site/themes/default.xml
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_list.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_list.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/send.php
~ site/views/submit/view.html.php
+ [FOLDER] media/css/
+ media/css/tipTip.css
+ [FOLDER] media/css/manager/
+ media/images/manager/approval_16.png
+ [FOLDER] media/js/
+ media/js/jquery.tipTip.js



iCagenda 3.2.0 RC3 <small style="font-weight:normal;">(2013.09.26)</small>
================================================================================
! Changes in the display of Global Options (added General Settings Tab)
! Fixed : important issue in notification emails send to managers authorized to approve events (due to a bug if user is depending of more than one user groups)
! Changed : Approval can be processed directly in Frontend, at event preview page.
+ Added : Check if managers with Approval permissions are Enabled and Activated.
+ Added : Option to select Template in menu-item link 'Submit an Event'.
+ Added : Global option to enable or disable auto login in url links included in notification emails.
+ Added : implemented Page Header and page class suffix in 'Submit an Event' page.
~ Changed : Events submitted in Frontend by a user (manager) belonging to an authorized group will be automatically approved.
~ Changed : Back button in event details view return to list of events ( replace history.go(-1) ).

Changed files in 3.2.0 RC3
+ admin/add/elements/desc.php
~ admin/config.xml
~ admin/models/forms/event.xml
~ script.icagenda.pro.php
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/list/tmpl/registration.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml
~ site/views/submit/tmpl/send.php
~ site/views/submit/view.html.php


iCagenda 3.2.0 RC2 <small style="font-weight:normal;">(2013.09.22)</small>
================================================================================
# Fixed : Access Permissions to 'Submit an Event' form (missing global option).
+ Added : Options to customize the content when a user access to the 'Submit an Event' page, and this user is not connected, or connected but does not have sufficient rights.

Changed files in 3.2.0 RC2
~ admin/config.xml
+ admin/models/fields/modal/ictext_content.php
+ admin/models/fields/modal/ictext_type.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ site/views/submit/tmpl/default.php


iCagenda 3.2.0 RC <small style="font-weight:normal;">(2013.09.20)</small>
================================================================================
! NEW : Menu Type to 'Submit an Event' in frontend.
! NEW : Selection of days of the week for period events (additional options to come for dates settings!).
! NEW : Plugin iCagenda Autologin.

Changed files in 3.2.0 RC
~ admin/config.xml
~ admin/models/event.php
~ admin/models/events.php
+ admin/models/fields/modal/tos_article.php
~ admin/models/fields/modal/tos_content.php
+ admin/models/fields/modal/tos_default.php
+ admin/models/fields/modal/tos_type.php
~ admin/tables/event.php
~ admin/views/event/tmpl/edit.php
~ [MODULE PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ script.icagenda.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/default.xml
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_list.php
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_list.php
+ site/views/submit/tmpl/default.php
+ site/views/submit/tmpl/default.xml
+ site/views/submit/tmpl/send.php
+ site/views/submit/view.html.php
+ [PLUGIN] plugins/plg_ic_autologin/ic_autologin.php
+ [PLUGIN] plugins/plg_ic_autologin/ic_autologin.xml
+ SQL : Adding 'daystime' column to table icagenda_events


iCagenda 3.1.13 <small style="font-weight:normal;">(2013.09.20)</small>
================================================================================
# Fixed : display in frontend of the fake date 30 november 1999, if no single date is set.

Changed files in 3.1.13
~ site/helpers/icmodel.php


iCagenda 3.1.12 <small style="font-weight:normal;">(2013.09.17)</small>
================================================================================
# Fixed : A problem with the control of the upcoming date for events over a period (unpublished event and message 'no valid date'). This bug is present since version 3.1.5, and rarely appeared.
# Fixed : conflict CSS days font color in calendar module with some Shape5 templates.

Changed files in 3.1.12
~ site/helpers/icmodel.php
~ [THEME PACKS] site/themes/default.xml
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css


iCagenda 3.1.11 <small style="font-weight:normal;">(2013.09.13)</small>
================================================================================
# Fixed : Italian bug in translation files, responsible of missing features in event edit (admin).
# Fixed : Error mktime when saving a new event (due to no filling of single dates). A fix should update in the same way events with this issue in the frontend.

Changed files in 3.1.11
~ admin/models/fields/modal/date.php
~ admin/views/event/tmpl/edit.php
~ site/helpers/icmodel.php


iCagenda 3.1.10 <small style="font-weight:normal;">(2013.09.12)</small>
================================================================================
+ added : control if allow_url_fopen and GD are enabled (thumbnails generator)
+ added : files to prepare the next release with Submit an Event feature!
+ added : Approval option in event edit (will be operating in release 3.2!).
~ Changed : new dates control when saving an event, display now an alert message for new event, and block saving of a new event if no valid date.
~ Changed : enhancement of period datepicker (not possible now to have end date before start date)
# Fixed : not generation of thumbs when extension of a file in caps.
# MODULE iC calendar : Fixed possible conflicts due to div tags enclosed within scripts (rare conflict, manifested by the appearance of a part of the script on the page, and the non-functioning of the calendar).
# THEME IC_ROUNDED : display of next date (Time 2 times), list of events.

Changed files in 3.1.10
~ admin/add/js/icdates.js
~ admin/config.xml
~ admin/controllers/events.php
+ admin/helpers/html/events.php
~ admin/models/event.php
~ admin/models/fields/modal/date.php
~ admin/models/fields/modal/enddate.php
~ admin/models/fields/modal/startdate.php
+ admin/models/fields/modal/tos_content.php
~ admin/models/forms/event.xml
~ admin/tables/event.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ [PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ modules/mod_iccalendar/helper.php
~ modules/mod_iccalendar/mod_iccalendar.php
~ site/add/js/icdates.js
~ site/helpers/icmodel.php
+ site/models/forms/submit.xml
~ site/models/list.php
+ site/models/submit.php
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_list.php
+ SQL : Adding 'approval' column to table icagenda_events


iCagenda 3.1.9 <small style="font-weight:normal;">(2013.09.06)</small>
================================================================================
! MODULE iC calendar : possibility now to publish many calendars on a single page.
+ Added : Extra-control if mime-type of the event's image is correct (in order to process thumbnails creation).
+ Added : Complete or not form fields 'Name' and 'Email' with the profile information of a Joomla user connected, in registration form.
+ Added : Option to enable or disable the thumbnail generator.
+ Added : 'Notes' field text area in Registration form (set disabled as default).
+ Added : Option Show/Hide 'Notes' in registration form.
+ Added : Option Show/Hide 'Phone' in registration form.
+ Added : Information and control of folder creation used by iCagenda (thumbnails, attachments).
~ THEME PACKS : version 2.0 (default and ic_rounded).
~ Changed : period of dates with start date the same day than end date is now displayed as 'date start time - end time' (eg. 23 April 2013 10:00-19:00)
~ Changed : list of date formats was without <optgroup> infos in Joomla 3
# MODULE iC calendar : Fixed, Tooltip Close X button was not working on Apple mobile devices.
# Fixed : bugs in thumbnails generator if ROOT/images folder doesn't exist. Solve an issue if path to images is not 'images'.

Changed files in 3.1.9
~ admin/config.xml
~ admin/models/event.php
~ admin/models/fields/iclist/globalization.php
~ admin/models/fields/modal/enddate.php
~ admin/models/fields/modal/startdate.php
~ admin/models/forms/event.xml
~ admin/models/registrations.php
~ admin/tables/event.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/registrations/tmpl/default.php
~ media/scripts/icthumb.php
~ [PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
+ modules/mod_iccalendar/helper.php
~ modules/mod_iccalendar/mod_iccalendar.php
~ modules/mod_iccalendar/mod_iccalendar.xml
~ script.icagenda.php
- site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ [THEME PACKS] site/themes/default.xml
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
+ SQL : Adding 'created_by_email' column to table icagenda_events
+ SQL : Adding 'weekdays' column to table icagenda_events
+ SQL : Adding 'notes' column to table icagenda_registration


iCagenda 3.1.8 <small style="font-weight:normal;">(2013.08.30)</small>
================================================================================
# Fixed : Error message in liveupdate (developped by Nicholas from Akeeba) to work under php 5.2. I've added a php control to be able to load storage.php file. But, we truly recommend every user to upgrade their php version to a minimum of 5.3, as recommended by Joomla core, and as minimum to be able to install Joomla 3. In the future, you can encounter other such issue, or error message, if you're still in a PHP version lower than 5.3.
+ Added : Alert Message in control panel of the component, if PHP version is lower than 5.3.

Changed files in 3.1.8
~ admin/liveupdate/classes/storage/storage.php
~ admin/views/icagenda/tmpl/default.php


iCagenda 3.1.7 <small style="font-weight:normal;">(2013.08.29)</small>
================================================================================
+ Added : Created_by filter in list of registered users (admin).
+ Added : Option to use php function checkdnsrr in registration form, to check if email provider is valid (this option is now disabled by default).
+ Added : Options for event details view: show/hide dates, Google Maps, information... and set access level for some.
+ Added : Options to order by dates list of single dates, and display a vertical or horizontal list.
+ Added : Option for registration form : auto-filled name or username, in name's form field (was only name before).
+ MODULE iC calendar : Option to display only start date in the calendar, in case of an event over a period.
~ MODULE iC calendar : Changes in script code of function.js file to prevent some conflict.
~ Changed : Search in registrations list extended: username, name, email, date, phone, people... (only search in Title before this release)
~ Changed : Default value is now set to "by individual date" in 'Registration Type' field.
~ Changed : Upgraded files of LiveUpdate by Akeeba, updates system integrated in iCagenda.
# Fixed : sending notification email to author of an event, when new registration. Fixed of [AUTHOREMAIL] tag.
# Fixed : Error Debug of Google Maps (icmap.js).

Changed files in 3.1.7
~ admin/config.xml
~ admin/liveupdate/ (All php files of this folder updated)
+ admin/models/fields/modal/checkdnsrr.php
~ admin/models/forms/event.xml
~ admin/models/registrations.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/registrations/tmpl/default.php
~ modules/mod_iccalendar/js/function.js
~ modules/mod_iccalendar/mod_iccalendar.xml
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/js/icmap.js
~ site/themes/packs/default/default_event.php
~ site/views/list/tmpl/registration.php


iCagenda 3.1.6 <small style="font-weight:normal;">(2013.08.20)</small>
================================================================================
# Fixed : NextDate control when event set on a period in the future.
+ Added : Control of time when event with a date in a period the same as a single date.
+ Added : On windows server and php version < 5.3, disable check function if provider of an email address during registration is valid, as checkdnsrr is implemented on windows server only since php 5.3.0

Changed files in 3.1.6
~ site/helpers/icmodel.php


iCagenda 3.1.5 Security Release and enhancements! <small style="font-weight:normal;">(2013.08.19)</small>
================================================================================
! Security Release : fixed a XSS vulnerability discovered by Stefan Horlacher from Compass Security AG (www.csnc.ch) (many thanks Stefan to keep the web clean and secured!). Another issue was resolved, discovered by Giusebos, which allowed sending spam to the administrator and the creator of the event, using cookies via registration form. And that's not all! As we always want to add much more security, some filtering enhancements have been added to the registration form (see below).
! Change : Now, when an event over a period with an end date and its time set to 00:00:00, this end date is displayed in frontend (list of events, and modules).
+ Added : New options in filtering events in menuitem. Now you can display all events, upcoming events, past events, events of the day and upcoming, or today's events.
+ Added : Page 404 when event not found.
+ Added : Enhancement of Email control during registration. Test if provider is valid.
+ Added : Test of the Name during registration. Now, a name cannot start with a number and cannot contain any of the following characters: / \ < > "_QQ_" [ ] ( ) " ; = + &.
+ Added : Control in front-end if dates of events are valid (control was before only in admin edit)
# Fixed : was counted archived events in header of list of events, and should not.
# Fixed : if end time is lower or equal to start time of an event over a period, end date is displayed.
# Fixed : Author name and username were not correctly displayed in admin events list, and now display correctly the user selected in 'created by'.

Changed files in 3.1.5
~ admin/models/events.php
~ admin/tables/event.php
~ admin/views/events/tmpl/default.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php


iCagenda 3.1.4 <small style="font-weight:normal;">(2013.08.13)</small>
================================================================================
# Fixed : bug in function for detecting wrong dates entered by user, which was not always working as expected, depending of time setting in joomla config
# Fixed : change in function for globalized date format of month and of day, to prevent some errors due to locale (Russian...)
# Fixed : Not sending notification email to the registered user (if his email address is entered and required)
+ Added : Control of event ID to prevent spamming emails to administrator by a robot (notification email admin)
~ Changed : Translation of Date in current language (admin - list of events)

Changed files in 3.1.4
~ admin/tables/event.php
~ admin/views/events/tmpl/default.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/themes/packs/default/css/default_module.css
~ site/themes/packs/ic_rounded/css/ic_rounded_module.css


iCagenda 3.1.3 <small style="font-weight:normal;">(2013.08.09)</small>
================================================================================
# Fixed : global option to hide the participants list not working properly
# Fixed : notice message above registration option field, in event edit
~ MODULE iC calendar : changed, access levels control, to speed up loading of pages with calendar
+ MODULE iC calendar : loading picture when charging a new month

Changed files in 3.1.3
~ admin/models/fields/modal/ph_regbt.php
~ modules/mod_iccalendar/js/function.js
~ modules/mod_iccalendar/mod_iccalendar.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/themes/packs/default/css/default_module.css
~ site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ (added) site/themes/packs/default/images/ic_load.png
~ (added) site/themes/packs/ic_rounded/images/ic_load.png


iCagenda 3.1.2 <small style="font-weight:normal;">(2013.08.05)</small>
================================================================================
! Important editing of thumbnails generator (List of events in admin, Calendar module, and Event List module). Now, file renaming for thumbnails (remove all special caracters to get a clean url for image), and copy of distant pictures (to prevent broken link). Accepted as image extensions (File Types) for event image : jpg, jpeg, png, gif, bmp
# Fixed : Slow change of month of the calendar (thumbnail generator error function)
# Fixed : Slow display of events in module iC Event List (Pro Version)
~ changed : [J3 issue] jQuery UI version in admin, from 1.9.2 to 1.8.23 to prevent a conflict with description tooltip (appeared since joomla 3.1.4)

Changed files in 3.1.2
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ media/scripts/icthumb.php
~ script.icagenda.php
~ site/helpers/icmodcalendar.php


iCagenda 3.1.1 <small style="font-weight:normal;">(2013.07.29)</small>
================================================================================
# Fixed : Wrong filtering of Viewing Access Levels in list of events page
# Fixed : error in modules (front-end), when url to image is broken or invalid
~ changed : url of image when sharing on facebook (other enhancements planned)

Changed files in 3.1.1
~ admin/views/icagenda/view.html.php
~ script.icagenda.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/views/list/tmpl/event.php


iCagenda 3.1.0 <small style="font-weight:normal;">(2013.07.26)</small>
================================================================================
! New : Automatic thumbnails generator in modules (some options, and enhancements will be added later in theme packs)
# Fixed : Issues with J3 after upgrade from joomla 3.1.x to 3.1.4 (error 500 default layout missing, and JFile not found)
# Fixed : not sending admin notification email (error in 3.0.1 and 3.0 pre-releases)
# Fixed : No updating of Next Date when menu set to Upcoming Events
# Fixed : participant slide effect and display options not working
+ Added : Global Option for email field in frontend registration (required or not)
~ many code review

Changed files in 3.1.0
~ admin/config.xml
~ admin/models/categories.php
~ admin/models/fields/modal/ph_regbt.php
~ admin/tables/event.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/view.html.php
~ media/scripts/icthumb.php
~ script.icagenda.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ site/themes/packs/default/default_event.php
~ site/themes/packs/default/css/default_component.css
~ site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/list/tmpl/registration.php


iCagenda 3.0.1 <small style="font-weight:normal;">(2013.07.04)</small>
================================================================================
# Fixed : auto-play of the tutorial video on Chrome and Safari (the video should not autoplay)
# Fixed : missing admin pagination in categories list
# Fixed : buttons display over the datepicker (time show/hide button activated)

Changed files in 3.0.1
~ admin/add/css/jquery-ui-1.8.17.custom.css
~ admin/views/categories/tmpl/default.php
~ admin/views/categories/view.html.php
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php


iCagenda 3.0 RC <small style="font-weight:normal;">(2013.06.30)</small>
================================================================================
# Fixed : Thumbnail generator in events list admin : error when using a distant url
# Fixed : Position and zooming in admin, in events created before update
# Fixed : Colors of options buttons in event admin edit : not always visible, in events created before update
# Fixed : Theme ic_rounded : problem of display with long title
+ Added : Custom text option for registration button
+ Added : Control if link to event picture is valid, in admin
~ updated : display in Global Options of the component and modules


iCagenda 3.0 beta 1 <small style="font-weight:normal;">(2013.06.09)</small>
================================================================================
! First beta version compatible with Joomla 3 and Joomla 2.5

Changed files in 3.0
! Given that this new version brings compatibility with Joomla 3, all php files were reviewed to allow dual Joomla 2.5 / 3.x compatibility. Other files were also reviewed, with a major overhaul of logic and graphic structure of iCagenda. The list of modified files is reset with this new version 3.0 of iCagenda and the list of modified files will be detailed again from future release 3.0.1


iCagenda 2.1.14 <small style="font-weight:normal;">(2013.05.29)</small>
================================================================================
# Fixed : Url to the event details page in the email notifications, and in the 'view event' link.
# Fixed : Failing filtering language when joomla is set in multiple languages (Incorrect display of the number of events).
# Fixed : Conflict of Google Maps with some editor buttons, enabled in textarea of the description.
# Fixed : File attachment field, incorrect display of clear button.

Changed files in 2.1.14
~ admin/models/fields/modal/icfile.php
~ admin/views/event/view.html.php
~ admin/views/event/tmpl/edit.php
~ site/helpers/icmodel.php
~ site/views/list/tmpl/registration.php
~ SQL : Events whose language is not registered, is set by default to "all" (integration of events created prior to version 2.1.7)
+ SQL : Adding 'itemid' column to table icagenda_registration


iCagenda 2.1.13 <small style="font-weight:normal;">(2013.05.23)</small>
================================================================================
# Fixed : page class suffix when error reporting is active.

Changed files in 2.1.13
~ site/views/list/tmpl/default.php


iCagenda 2.1.12 <small style="font-weight:normal;">(2013.05.21)</small>
================================================================================
! New Translation Pack sl-SI : Slovenian Pack available for download on joomlic.com - Author: erbi (Ervin Bizjak)
# Fixed : Global Options to filter registrations per email and date
# MODULE iC calendar : Adding a default space before the module class suffix to prevent malfunction of the scripts used by the calendar (When the class module is not added as it should be : http://docs.joomla.org/Using_Class_Suffixes)
+ [PRO] MODULE iC Event List : New filter (All, past or future events) and ordering by dates
+ [PRO] MODULE iC Event List : New languages, Slovenian and Spanish

Changed files in 2.1.12
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ [PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ modules/mod_iccalendar/mod_iccalendar.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php


iCagenda 2.1.11 <small style="font-weight:normal;">(2013.05.13)</small>
================================================================================
~ THEME PACKS : version 1.6 (default and ic_rounded).
# Fixed : to not display Registration button when Global option set to no, and access to registration not public
# Fixed : keep value when iso date format selected
# MODULE iC calendar : Date Format not working in module

Changed files in 2.1.11
~ admin/globalization/iso.php
~ admin/liveupdate/classes/model.php
~ admin/models/fields/iclist/globalization.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php


iCagenda 2.1.10 <small style="font-weight:normal;">(2013.05.07)</small>
================================================================================
+ MODULE iC calendar : control of the color for text in Tooltip depending of the color of the category background (when no image).
~ THEME PACKS : version 1.5.9 (default and ic_rounded).
# MODULE iC calendar : Fixed a bug with the navigation arrows if the calendar is not displayed on the page by default (since joomla 2.5.11)

Changed files in 2.1.10
~ site/helpers/icmodcalendar.php
~ module/mod_iccalendar/js/function.js


iCagenda 2.1.9 <small style="font-weight:normal;">(2013.05.03)</small>
================================================================================
+ Added : Access permission to registration (set in event options).
+ MODULES : Access and language control (set in event options).
+ Added : alias field in event edit.
~ THEME PACKS : version 1.5.8 (default and ic_rounded).
# Fixed : Weird display in event edit page (admin).
# Fixed : Missing file to display Date Format with separator.
# Fixed : Bug in dates period after fields start and end date cleaned and saved.
# MODULE iC Event List : fixed bug of thumbnail in IE10
# MODULE iC Event List : fixed bug of link in IE8

Changed files in 2.1.9
~ admin/add/css/icmap.css
+ admin/globalization/iso.php
~ admin/models/list.php
~ admin/models/fields/iclist/globalization.php
~ admin/models/forms/event.xml
~ admin/tables/event.php
~ admin/views/event/view.html.php
~ admin/views/event/tmpl/edit.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php


iCagenda 2.1.8 <small style="font-weight:normal;">(2013.04.30)</small>
================================================================================
# Fixed : Google Maps not display in front-end.
# Fixed : Date Format not working.
# Fixed : Arrows Page navigation not display in event list.

Changed files in 2.1.8
~ script.php
~ site/helpers/icmodel.php
~ site/views/list/tmpl/default.php


iCagenda 2.1.7 <small style="font-weight:normal;">(2013.04.29)</small>
================================================================================
! New Google Maps with auto-field (country, locality...)
! New Date Format with globalization (auto-detect your current language to display date formats in your culture)
! New Option for language of an event
! New Option for access to an event
+ MODULE iC calendar : Infos added to the tooltip (city, country, place, short description).
~ THEME PACKS : version 1.5.7 (default and ic_rounded).
~ Filter of description field to allow raw code (content plugin and html).
~ Cleaning of the code in different files.
+ Added : Page params : Header in events list, and title + sitename in browser (global joomla configuration).
+ Added : Facebook metadata for sharing events.
+ Added : Control if a period is valid, if start date is not later than end date.
# Fixed : AM/PM for date start and end of a period.
# Fixed : Undefined variable "toDay".
# Fixed : Some conflict in admin, with Google Maps and date picker (JSN Power Admin, Hikashop...).
# Fixed : Publish status can be edit in event edit page.
# Fixed : Correct next date, when an event is saved in admin (for events with period and single dates).
- Removed some unused files from old versions of module iC calendar

Changed files in 2.1.7
+ admin/add/css/icmap.css
+ admin/add/image/blanck.png
+ admin/add/js/icmap.js
+ admin/globalization/
+ admin/models/fields/iclist/
+ admin/models/fields/icmap/
~ admin/icagenda.php
~ admin/models/forms/event.xml
~ admin/tables/event.php
~ admin/views/event/view.html.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/tmpl/default.php
~ modules/mod_iccalendar/mod_iccalendar.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ site/views/list/view.html.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php


iCagenda 2.1.6 PRO<small style="font-weight:normal;">(2013.04.12)</small>
================================================================================
~ THEME PACKS : version 1.5.6 (default and ic_rounded).
# MODULE iC Event List : Bug Fixed link (itemid=0) when first install on 2.1.3, without having change settings of the module before upgrade to 2.1.5

iCagenda 2.1.5 <small style="font-weight:normal;">(2013.04.10)</small>
================================================================================
~ THEME PACKS : version 1.5.5 (default and ic_rounded).
~ MODULE iC calendar : Edit coding and css of arrows to prevent some conflict.
# Fixed : Google Maps not being display on front-end with some site templates (new icmap.js file).

iCagenda 2.1.4 <small style="font-weight:normal;">(2013.04.05)</small>
================================================================================
+ Added : Triggering content plugins in description text.
~ THEME PACKS : version 1.5.4 (default and ic_rounded).
~ Modified : function to get a short description containing only text.
# Fixed : 'undefined constant auto' message notice.

iCagenda 2.1.3 <small style="font-weight:normal;">(2013.04.01)</small>
================================================================================
+ Added : Alias of the event in URL SEF router.
+ MODULE : Option, link to a specific Menu Item (module).
~ MODULE : New order of Events in tooltip (Desc nextDate).
~ THEME PACKS : version 1.5.3 (default and ic_rounded).
- Removed some unused files.

iCagenda 2.1.2.1 <small style="font-weight:normal;">(2013.03.27)</small>
================================================================================
~ Edit : cleaning of the code of a few files

iCagenda 2.1.2 <small style="font-weight:normal;">(2013.03.21)</small>
================================================================================
! New Translation Pack en-US : English American Pack available for download on joomlic.com - Author: Lyr!C
! New : Registration is now limited to one submission per email address (global options).
+ Added : Global options, Header display option (show/hide Title and/or Subtitle).
+ Added : Global options, phone requirement or not during registration.
+ Added : New Date Format (menu-item and module option).
+ Added : email cloacking in event details page.
+ Added : default contact email (site config email) in notification email, if no user is selected as the author of the event.
~ MODULE : New coding for day over color and background (view chanlog.txt in theme pack).
~ THEME PACKS : version 1.5.2 (default and ic_rounded).
# Fixed : permission access to add new event in admin, for not super admin users (error introduced with edit own).
# Fixed : event URL during registration, when joomla installed in a subpath of the domaine.
- disabled read more and pagebreak buttons from description textarea (not compatible with iCagenda).

iCagenda 2.1.1 <small style="font-weight:normal;">(2013.03.14)</small>
================================================================================
+ Added : missing strings of translation for admin notification email, when a new registration (will be added in 2.1.1 Translation Packs by translators).
# Fixed : when update, default setting for Arrows Position in list of events;
# Fixed : URL to event in registration notification email (when SEF off);
# Fixed : display of time in All Dates list;
# Fixed : bug error in option "Registration Type" for display of list of dates in registration form;
# Fixed : missing ";" in html code for arrow back &#9668 (THEMENAME_event.php);
# Fixed : a bug in period end date (this bug was only with php 5.2; But, it is recommended to use php 5.3 minimum!);
# Fixed MODULE : event jQuery, responsible of some conflict;

iCagenda 2.1 <small style="font-weight:normal;">(2013.03.11)</small>
================================================================================
! New : List of Participants in event details view.
! New : automatic email when registration to an event.
! New : New features in Global Options.
+ MODULE : Added background color and image options for calendar.
+ Added : ACL options and user infos (date, name) in admin (for event edit).
+ Added : Edit Own in Permissions (for event edit).
+ Added : editing body and subject of registration automatic email (Global Options).
+ Added : position top or bottom for navigator in events list (Global Options).
+ Added : slide effect for List of Participants (Global Options).
+ Added : Time Format 24h - 12h am/pm (Global Options).
+ Added : Show or Hide Time of an event (Event Edit - Dates).
+ Added : Global Options in the component, for parameters of Menu Items (only Short Description, others coming in next release).
~ Update : ic_rounded theme packs v1.5 (with list of participants).
~ Update : default theme packs v1.5 (with list of participants).
~ MODULE : scripts files of the module iC calendar.
~ MODULE : New options Date Format (now the same as in menu item params).
~ Edit : cleaning of the code of some files.
# Fixed : error message on event details page after transfer via ftp
# Fixed : display of special characters in date (Russian, Croatian, etc) for tooltip in calendar module
# Fixed : missing div in arrows display, events list page
# Fixed : missing ";" in html code for arrows #9668; and #9658;

iCagenda 2.0.6 <small style="font-weight:normal;">(2013.02.06)</small>
================================================================================
~ Update : default and ic_rounded theme packs v1.4.
# Fixed : uninitialized variables

iCagenda 2.0.5 <small style="font-weight:normal;">(2013.02.01)</small>
================================================================================
! New Translation Pack sv-SE : Swedish Pack available for download on joomlic.com - Author: Rickard Norberg
! New Translation Pack hr-HR : Croatian Pack available for download on joomlic.com - Author: Davor Colic
+ Added : button to remove attached file from an event (This function will be improve in the future. If you clear the field, you need to save the event to be able to upload an other file)
+ Added : advanced option in module to prevent conflict js.
~ Update : mod_iccalendar, open and close tooltip updated (links removed).
~ Update : mod_iccalendar, add script variables in module (no more in header, to prevent conflict).
~ Update : default and ic_rounded theme pack v1.3 (new: changelog.txt included in the pack folder).
~ Update : cache set to false in panel of event edit (default panel set : 'Event').
# Fixed : Alert message if no valid date in an event (admin)
# Fixed : Bug no dates display in dates list, when registration to an event with single dates and period dates.

iCagenda 2.0.4 <small style="font-weight:normal;">(2013.01.23)</small>
================================================================================
! New Translation Pack pt-PT : Portuguese Pack available for download on joomlic.com - Author: macedorl
+ Added : new script for tooltip in calendar (one file for all positions).
+ Added : Phone number and UserID columns in admin, registrations list.
~ Update of Italian language included (English and French already done)
~ Update of Theme Packs included - Default and ic_rounded v1.2 (css ictip span (com.) and NAVIGATOR (mod.))
# Fixed : uninitialized variables
# Fixed : SEO wrong indexing of the calendar navigator arrows, were treated as urls.
# Fixed : display of special characters in date (Russian, Croatian, etc) for front-end component (module will be reviewed in 2.1).
# Fixed : Not save of User ID in database, in registration process (you need to edit sql database manually to add User ID of registered member done before version 2.0.4).
# Fixed : wrong String of translation in registration phone field description.
# Fixed : edit modal fields in admin (datetime picker, GoogleMaps, Color Picker), to solved some jQuery conflict (with ZOO).

iCagenda 2.0.3 <small style="font-weight:normal;">(2013.01.10)</small>
================================================================================
# Fixed : Show now only total number of published registered people to an event (no need to empty trash)
# Fixed : Bug COM_ICAGENDA_EVENT_DATE
~ Change "info" class css to "icinfo" (solves css conflict with some templates as joomspirit76)
~ Change load English translation in Front-End if a string is missing

iCagenda 2.0.2 RC <small style="font-weight:normal;">(2013.01.04)</small>
================================================================================
# Fixed : Bug single date in front-end 30/11/1999 when no single date enter in event edit (missing :00 (seconds) in default datetime format)

iCagenda 2.0.1 RC <small style="font-weight:normal;">(2013.01.01)</small>
================================================================================
# Fixed : Bug when only 1 place per registration
# Fixed : Registration global setting when update from 1.2.9 to 2.0

iCagenda 2.0.0 RC <small style="font-weight:normal;">(2012.12.31)</small>
================================================================================
! Registration to event is now available!
! Newsletter for user registered to an event
! Theme Packages management added! (packages on process)
! New Translation Pack zh-TW : Chinese Traditionnal Pack available for download on joomlic.com - Author: jedi
+ New way of templating your iCagenda and Calendar : all files needed in one package
+ Added : Possibility of events with start and end date
+ Added : url field in event edit
+ Added : class suffix for module
+ Added : separator option for dates (in component)
+ Added : individual params for event
+ Added : calendar show Today (css class)
+ Added : icone to show date with more than one event, in calendar
+ Added : General Options for registration
+ Added : control of fields in registration (against spam scripts)
+ Added : country field in event edit
+ Added : New strings of translation in event edit (admin and site)
+ Added : Message "no event" in calendar if no event found in iCagenda
~ Time is now included in date field
~ Change display of event edit in admin
~ Control of next date is now included directly in component list display, so no need to published module to add this function
# Fixed : Translation of dates for all languages
# Fixed : many css conflict with site templates
~ And many more enhancements !

iCagenda 1.2.9 <small style="font-weight:normal;">(2012.10.28)</small>
================================================================================
! Added more security against Full Path Disclosure (Thanks Reinhard for aid!).
# Fixed IE and Safari no display of image, when no image in event details
~ Updated info Panel and credits

iCagenda 1.2.8 Security Release ! <small style="font-weight:normal;">(2012.10.22)</small>
================================================================================
! IMPORTANT : improving security and checking in different files of iCagenda and iC calendar.
! New Translation Pack fi-FI : Finnish Pack available for download on joomlic.com - Author: Kai Metsävainio
! New Translation Pack pl-PL : Polish Pack available for download on joomlic.com - Author: Andrzej Opejda
# Fixed css bug display of the module iC calendar with a few site templates
~ Updated function Alert in module iC calendar

iCagenda 1.2.7 <small style="font-weight:normal;">(2012.10.18)</small>
================================================================================
# Fixed jquery Conflict with Widget Kit by Yootheme
+ Joomla Update Server added gradually in Translation Packs v1.2.7 : http://www.joomlic.com/translations

iCagenda 1.2.6 <small style="font-weight:normal;">(2012.10.15)</small>
================================================================================
! Preparing Release 1.3 (with Registration)
! First VIDEO TUTORIAL done by Giusebos on use of extension iCagenda v 1.2 !!!
! New Translation Pack el-GR : Greek Pack available for download on joomlic.com - Author: E.Gkana-D.Kontogeorgis
! New Translation Pack lv-LV : Latvian Pack available for download on joomlic.com - Author: kredo9
! New Translation Pack sk-SK : Slovak Pack available for download on joomlic.com - Author: JRIBARSZKI
! Checking language files update will be added gradually to all Translations Packs
+ iCagenda Release System use now Akeeba Live Update
+ JQuery.noConflict.js file and not loading jQuery Librairy if already loaded
# html code is preserved in description when the form is processed
# Fixed bug in calendar navigator in some site templates
# Fixed Event list navigation when category filter is active
~ Better Retrieving and Filtering GET and POST requests (component and module)
~ Changes in CSS files of the module iC Calendar (arrows next/previous month and year separated)

iCagenda 1.2.5 <small style="font-weight:normal;">(2012.09.25)</small>
================================================================================
! Fixed a bug in display of the calendar when update (default new option not set in 1.2.4).

iCagenda 1.2.4 <small style="font-weight:normal;">(2012.09.25)</small>
================================================================================
! Fixed bug conflict with other module as login or Community Builder (white blanck page).
! New Translation Pack ru-RU : Russian Pack available for download on joomlic.com - Author: MSV
! New Translation Pack nb-NO : Norwegian Pack available for download on joomlic.com - Author: Rikard Tømte Reitan
! New Translation Pack cs-CZ : Spanish Pack available for download on joomlic.com - Author: Bong
+ Added option first day of the week : Monday or Sunday
+ Added option background colors of the day columns
+ Added a function in calendar : double-arrow in navigation to change year
+ css files and language for the calendar are now included in the module
# Fixed some css conflict with a number of site templates.
# Fixed, date translation in info-tip (mod_iccalendar).

iCagenda 1.2.3 <small style="font-weight:normal;">(2012.09.15)</small>
================================================================================
! Changing the date format options in the module and menu-link to component (thank you change this setting in your params !)
# Fixed dates show in mod_iccalendar.
# Fixed next and back links in list display when SEF disable
# fixed bug display in some site templates, with ic_rounded template (thanks reports by community)
# Fixed a bug in the display of the marker on the map in some cases
+ Untranslated strings will appear in English and not as untranslated keys
+ Options : add param show/hide text with forward/back arrows (based on an idea by Leland)
+ Added auto detect color of the category to settle date color (white or grey) in event list (default template)
+ Added auto detect color of the category to settle date color (white or grey) in iC calendar module
~ Update Default template (more joomla site template friendly) by walldorff
~ Update ic_rounded template (fixed some compatibilities with other extensions)

iCagenda 1.2.2 bug <small style="font-weight:normal;">(2012.09.09)</small>
================================================================================
# Fixed a bug in coding (thanks Il_maca!).

iCagenda 1.2.1 Security Release ! <small style="font-weight:normal;">(2012.09.08)</small>
================================================================================
! IMPORTANT : Security update SQLi .
! New logic for multi-dates event.
! New Translation Pack pt-BR : Portugues-Brasil Pack available for download on joomlic.com - Author: Carosouza
! New Translation Pack de-DE : German Pack available for download on joomlic.com - Author: BMB
! New Translation Pack es-ES : Spanish Pack available for download on joomlic.com - Author: elerizo
! New Translation Pack nl-NL : Dutch Pack available for download on joomlic.com - Author: walldorff
! Enhanced security - folders and data access
+ Translation of the month in the calendar module -- by Leland Vandervort
+ Automatic SetLocale Language (no longer necessary to declare Server Language in .ini files)
+ New Feature : AddThis (social sharing) parameters now available in General Options (admin)
+ Templates css : add […] style for Short Description in event list
+ Position infotip params : left, center or right (option top or bottom for center param)
+ Open infotip options : click or mouseover
# Fixed 'Next Date' display in admin events list, and in front-end view
# Update Back button template default
~ JText Translation in modal dates "Delete" - js file
~ Short description in Front-End improved
~ Template default hide fields if empty & new css file
~ Update Template ic_rounded
- Search menu-link for security test

iCagenda 1.1.4 <small style="font-weight:normal;">(2012.08.29)</small>
================================================================================
+ info update in control panel if new release
# fixed urls when SEF not activated
~ url SEF links to event changed and simplified
~ Back button now return to the last page viewed
~ Try to correct date next display in admin - events list when multi-dates in one event
~ Update it-IT language
~ AddThis on event list removed (just stay on event details page)

iCagenda 1.1.3 <small style="font-weight:normal;">(2012.08.27)</small>
================================================================================
! Miss one line in v1.1.2 that makes unable to choose ic_rounded template. Sorry for desagrement

iCagenda 1.1.2 <small style="font-weight:normal;">(2012.08.27)</small>
================================================================================
! jQuery No Conflict in mod_iCcalendar
# Module iC calendar : fixed dates display on every page of a joomla site
+ Title in Browser in event details page
~ Update Translation it-IT

iCagenda 1.1.1 <small style="font-weight:normal;">(2012.08.26)</small>
================================================================================
# Search link : correction date, and bug displays title "Search Results" in past/futur event list
~ Template 'Default' - add backlink

iCagenda 1.1 <small>RC</small> <small style="font-weight:normal;">(2012.08.24)</small>
================================================================================
! First Public Version
~ Translations IT

iCagenda 1.0.16 <small>BETA DEV</small> <small style="font-weight:normal;">(2012.08.22)</small>
================================================================================
+ Translations in English available
~ Search : new search page

iCagenda 1.0.15 <small>BETA DEV</small> <small style="font-weight:normal;">(2012.08.19)</small>
================================================================================
+ template : ic_rounded
+ admin : events list - new infos for date next + add languages
# fixed date 'next' bug when module activated

iCagenda 1.0.14 <small>BETA DEV</small> <small style="font-weight:normal;">(2012.08.16)</small>
================================================================================
~ Show number of events and pages in cases of All, Futur or Past selected
~ changes in sql (location -> place)
# links events synchronized between the component and the module

iCagenda 1.0.13 <small>BETA DEV</small> <small style="font-weight:normal;">(2012.08.15)</small>
================================================================================
+ Add MOD_ICCALENDAR_DESC in fr-FR.mod_iccalendar.ini
- 'Registration' and 'Newsletter' menus removed (under development)

iCagenda 1.0.12 <small>BETA DEV</small> <small style="font-weight:normal;">(2012.08.15)</small>
================================================================================
# Fixed errors with links to event in iCcalendar. Now works with multi-languages configuration

iCagenda 1.0.3 -> 1.0.11 <small>BETA DEV</small> <small style="font-weight:normal;">(2012.08.13)</small>
================================================================================
+ Tests script.php Remove Files and Folders during updates

iCagenda 1.0.2 <small>BETA DEV</small> <small style="font-weight:normal;">(2012.08.13)</small>
================================================================================
+ UPDATELOGS.php to view change log in the Back-End
~ updates Module iC calendar
- folder tmpl removed from mod_iccalendar

iCagenda 1.0.1 <small>BETA DEV</small> <small style="font-weight:normal;">(2012.08.12)</small>
================================================================================
+ Translations in Italian available
+ Multi-languages Module in Front-End
+ Multi-languages JText support for date.js and timepicker.js
+ Module iC calendar added (mod_iccalendar)
+ New SQL table '#__icagenda' for install/uninstall/update control
+ Add 'Phone', 'Email' and 'address' to Event edit

iCagenda 1.0 <small>BETA DEV</small> <small style="font-weight:normal;">(2012.08.07)</small>
================================================================================
! New development based on xCal 2 (beta) created by Jonxduo
! New name iCagenda: start of a new project (1st step, review of all the code)
+ New images package created by Lyr!C
+ Location is now in event editing
- Location management
