<?php

class CalendarEntriesController extends AppController {

    public $components = array(
        'LoungeArea'
    );

    const HOUR_SECONDS = 3600;
    const DAY_SECONDS = 86400;
    const WEEK_SECONDS = 604800;

    /**
     * Handles authorization for lounge specific actions
     */
    public function isLoungeAuthorized($loungePrivacy, $userLoungeRole){

        if($userLoungeRole == 'admin')
            return true;

        return false;
    }

    /**
     * Callback
     */
    public function beforeFilter(){

        parent::beforeFilter();

        $this->set('title_for_layout','Calendar');
    }

    /**
     * View the lounge calendar
     */
    public function index($view='week',$ref_datetime=false){

        $view = strtolower($view);

        if(!in_array($view,array('day','week','month','list'))){
            $this->_setFlash('Invalid view specified.');
            return $this->redirect($this->referer(array('action' => 'index')));
        }

        if($ref_datetime === false)
            $ref_datetime = time();

        $start_datetime = false;
        $end_datetime = false;
        $viewDescription = false;

        //Adjust start date/time based on the current reference time
        if($view == 'day'){
            $start_datetime = strtotime('today midnight', $ref_datetime);
            $end_datetime = strtotime('tomorrow midnight', $start_datetime);
            $previous_datetime = strtotime('yesterday midnight', $start_datetime);
            $next_datetime = $end_datetime;
            $viewDescription = date('M, D j',$start_datetime);
        }
        elseif($view == 'week') {
            $start_datetime = strtotime('Monday this week', $ref_datetime);
            $end_datetime = strtotime('Monday next week', $start_datetime);
            $previous_datetime = strtotime('Monday last week', $start_datetime);
            $next_datetime = $end_datetime;
            $viewDescription = date('M j', $start_datetime) . " - " . date('M j', $end_datetime-1);
        }
        elseif($view == 'month') {
            $start_datetime = strtotime('first day of this month', $ref_datetime);
            $end_datetime = strtotime('first day of next month', $start_datetime);
            $previous_datetime = strtotime('first day of last month', $start_datetime);
            $next_datetime = $end_datetime;
            $viewDescription = date('F \'y', $start_datetime);
        }
        else { //list
            $start_datetime = strtotime('first day of January this year', $ref_datetime);
            $end_datetime = strtotime('first day of January next year', $start_datetime);
            $previous_datetime = strtotime('first day of January last year', $start_datetime);
            $next_datetime = $end_datetime;
            $viewDescription = date('Y', $start_datetime);
        }

        $start_datetime_db = date('Y-m-d H:i:s', $start_datetime);
        $end_datetime_db = date('Y-m-d H:i:s', $end_datetime);

        $entries = $this->CalendarEntry->find('all',array(
            'contain' => array(),
            'conditions' => array(
                'OR' => array(
                    array(
                        'start >=' =>  $start_datetime_db,
                        'start < ' => $end_datetime_db
                    ),
                    array(
                        'end >=' => $start_datetime_db,
                        'end <' => $end_datetime_db
                    )
                )
            ),
            'order' => array(
                'start' => 'desc'
            )
        ));

        //Group entries appropriately
        $grouped_entries = array(); 
        $group_offset = false;
        $group_index = 0;
        if($view == 'day'){
            //Group by hour
            $group_offset = self::HOUR_SECONDS;
        }
        elseif($view == 'week'){
            //Group by day
            $group_offset = self::DAY_SECONDS;
        }
        elseif($view == 'month'){
            //Group by week
            $group_offset = self::WEEK_SECONDS;
        }
        else {
            //Group by month
            $group_offset = self::DAY_SECONDS*31; //31 days in Jan
        }
        $group_starttime = $start_datetime;
        $group_endtime = $start_datetime + $group_offset - 1;
        while($group_starttime < $end_datetime){
            $grouped_entries[$group_index] = array(
                'meta' => array(
                    'starttime' => $group_starttime,
                    'endtime' => $group_endtime
                ),
                'items' => array()
            );
            foreach($entries as $entry){
                $entry_starttime = strtotime($entry['CalendarEntry']['start']);
                $entry_endtime = strtotime($entry['CalendarEntry']['end']);
                if($entry_starttime >= $group_starttime && $entry_starttime < $group_endtime){
                    $grouped_entries[$group_index]['items'][] = $entry;
                    continue;
                }
                if($entry_endtime > $group_starttime && $entry_endtime < $group_endtime){
                    $grouped_entries[$group_index]['items'][] = $entry;
                }
            }
            $group_index++;

            if($view == 'list'){
                $group_starttime = $group_endtime + 1;
                $group_endtime = strtotime('first day of next month', $group_starttime) - 1;
            }
            else {
                $group_starttime += $group_offset;
                $group_endtime += $group_offset;
                if($group_endtime > $end_datetime)
                    $group_endtime = $end_datetime-1;
            }
        }

        $this->set(array(
            'view' => $view,
            'viewDescription' => $viewDescription,
            'calendarItems' => $grouped_entries,
            'currentDatetime' => $start_datetime,
            'previousDatetime' => $previous_datetime,
            'nextDatetime' => $next_datetime
        ));
    }

    public function view($entryId){

        $entry = $this->CalendarEntry->find('first', array(
            'contains' => array(
                'User'
            ),
            'conditions' => array(
                'CalendarEntry.id' => $entryId
            )
        ));

        if(empty($entry))
            $this->redirect('/calendar');

        $this->set(array(
            'entry' => $entry
        ));
    }

    /**
     * Add a calendar entry
     */
    public function add(){

        $this->set('add', true);

        if($this->request->is('post')){
            $this->request->data['CalendarEntry']['user_id'] = $this->Auth->User('id');
            if($this->CalendarEntry->save($this->request->data)){
                $this->_setFlash('Calendar entry added', 'success');
                return $this->redirect('/calendar');
            }
            else {
                $this->_setFlash($this->CalendarEntry->validationErrorsAsString());
            }
        }

        $this->render('add-edit');
    }

    /**
     * Edit a calendar entry
     */
    public function edit($entryId){

        $entry = $this->CalendarEntry->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'CalendarEntry.id' => $entryId
            )
        ));

        if(empty($entry))
            $this->redirect('/calendar');

        $this->set('add', false);
        $this->set('entryId', $entryId);

        if($this->request->is('put')){
            $this->CalendarEntry->id = $entryId;
            if($this->CalendarEntry->save($this->request->data)){
                $this->_setFlash('Calendar entry updated');
                return $this->redirect('/calendar');
            }
            else {
                debug("Something failed");
                $this->_setFlash($this->CalendarEntry->validationErrorsAsString());
                $this->_setFlash('Test');
            }
        }
        else {
            $this->request->data = $entry;
        }

        $this->render('add-edit');
    }

    /**
     * Delete a calendar entry
     */
    public function delete($entryId){

        $this->autoRender = false;

        $entry = $this->CalendarEntry->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'CalendarEntry.id' => $entryId
            )
        ));

        if(empty($entry)){
            $this->_setFlash('Calendar entry does not exist');
            return $this->redirect('/calendar');
        }

        if($this->CalendarEntry->delete($entryId)){
            $this->_setFlash('Calendar entry deleted','success');
            return $this->redirect('/calendar');
        }
        else {
            $this->_setFlash('We encountered a problem while trying to delete this calendar entry');
            return $this->redirect("/calendaar/view/$entryId");
        }
    }
}
