<?php defined('SYSPATH') OR die('No direct script access.');

set_time_limit(240);

class Controller_Crontab_Report extends BaseController {

	public function action_day() {
		$day = intval($this->request->query('day'));
		if ($day == 0) $day = intval(date('Ymd'));
		if ($day < 0) $day = intval(date('Ymd', strtotime($day.' day', time())));

		$count = Model::factory('App')->crontab_report_day($day);
		echo $count;
		exit;
	}

}